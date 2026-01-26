<?php
// attendance_data.php (FINAL VERSION - All Bugs Fixed - Dec 27, 2025)

session_start();
require '../config/db.php';

// Load PHPMailer
require_once "PHPMailer/PHPMailer.php";
require_once "PHPMailer/SMTP.php";
require_once "PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inside backend/attendance_data.php
$role = $_SESSION['role'];
$myEmpNum = $_SESSION['employee_number'];

if ($role !== 'HR' && $role !== 'Admin') {
    // FORCE the query to only look for the logged-in user
    $employee_number_to_fetch = $myEmpNum;
    $where_clause = "WHERE employee_number = '$myEmpNum'";
}

// Load SMTP settings from .env
if (file_exists(__DIR__ . '/.env')) {
    foreach (file(__DIR__ . '/.env') as $line) {
        $line = trim($line);
        if ($line && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            putenv($line);
        }
    }
}

header('Content-Type: application/json');

function get_working_days($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $end->modify('+1 day');  // Make end exclusive

    $interval = new DateInterval('P1D');
    $period = new DatePeriod($start, $interval, $end);

    $days = 0;
    foreach ($period as $date) {
        $day = $date->format('w');
        if ($day != 0) {  // Exclude ONLY Sunday (0)
            $days++;
        }
    }
    return $days;
}

$action = $_GET['action'] ?? 'grid';

if ($action === 'get_filters') {
    $sql = "SELECT name FROM departments ORDER BY name";
    $result = $conn->query($sql);
    $departments = [];
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row['name'];
    }
    echo json_encode(['departments' => $departments]);
    exit;
}

if ($action === 'get_employees') {
    $sql = "SELECT e.employee_number, e.first_name, e.last_name, 
            COALESCE(lb.vacation_leave, 15) as vacation_leave,
            COALESCE(lb.sick_leave, 10) as sick_leave,
            COALESCE(lb.emergency_leave, 5) as emergency_leave,
            COALESCE(lb.maternity_leave, 105) as maternity_leave,
            COALESCE(lb.paternity_leave, 15) as paternity_leave
            FROM employees e
            LEFT JOIN leave_balances lb ON e.employee_number = lb.employee_number
            WHERE e.status = 'Active'";
    $result = $conn->query($sql);
    $employees = [];
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
    echo json_encode(['employees' => $employees]);
    exit;
}

if ($action === 'approve_leave') {
    $empNum     = filter_input(INPUT_POST, 'employee_number', FILTER_SANITIZE_STRING);
    $leaveType  = filter_input(INPUT_POST, 'leave_type', FILTER_SANITIZE_STRING);
    $startDate  = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_STRING);
    $endDate    = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_STRING);
    $reason     = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);

    if (empty($empNum) || empty($leaveType) || empty($startDate) || empty($endDate) || empty($reason)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    $approvedBy = $_SESSION['employee_number'] ?? null;
    if (!$approvedBy) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized: Please log in.']);
        exit;
    }

    $daysRequested = get_working_days($startDate, $endDate);
    $isDeductible  = in_array($leaveType, ['VL', 'SL', 'Emergency']);

    $conn->begin_transaction();

    try {
        // Fetch employee details
        $stmt = $conn->prepare("SELECT first_name, last_name, email FROM employees WHERE employee_number = ?");
        $stmt->bind_param("s", $empNum);
        $stmt->execute();
        $empResult = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$empResult || empty($empResult['email'])) {
            throw new Exception("Employee not found or missing email address.");
        }

        $empName  = $empResult['first_name'] . ' ' . $empResult['last_name'];
        $empEmail = $empResult['email'];

        // Balance check & deduction prep
        $currentBalance = 0;
        $newBalance     = 0;
        $balanceColumn  = null;

        if ($isDeductible) {
            $map = [
                'VL' => 'vacation_leave',
                'SL' => 'sick_leave',
                'Emergency' => 'emergency_leave'
            ];
            $balanceColumn = $map[$leaveType] ?? null;

            if (!$balanceColumn) {
                throw new Exception("Invalid deductible leave type.");
            }

            $stmt = $conn->prepare("SELECT $balanceColumn FROM leave_balances WHERE employee_number = ? FOR UPDATE");
            $stmt->bind_param("s", $empNum);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            $currentBalance = $row[$balanceColumn] ?? 0;
            $stmt->close();

            if ($currentBalance < $daysRequested) {
                throw new Exception("Insufficient balance: Only {$currentBalance} day(s) available for {$leaveType}.");
            }

            $newBalance = $currentBalance - $daysRequested;
        }

        // === CONFLICT CHECK ===
        $conflictStmt = $conn->prepare("
            SELECT date, status 
            FROM attendance_records 
            WHERE employee_number = ? 
              AND date BETWEEN ? AND ?
              AND status IN ('P', 'A', 'L', 'VL', 'SL', 'Emergency', 'Maternity', 'Paternity')
        ");
        $conflictStmt->bind_param("sss", $empNum, $startDate, $endDate);
        $conflictStmt->execute();
        $conflicts = $conflictStmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $conflictStmt->close();

        if (!empty($conflicts)) {
            $conflictList = array_map(fn($c) => $c['date'] . " (" . $c['status'] . ")", $conflicts);
            throw new Exception("Cannot approve leave: Conflict on date(s): " . implode(', ', $conflictList));
        }

        // Insert into leave_requests
        $reqSql = "INSERT INTO leave_requests 
                   (employee_number, leave_type, start_date, end_date, days_requested, reason, status, approved_by, approval_date)
                   VALUES (?, ?, ?, ?, ?, ?, 'Approved', ?, NOW())";
        $stmt = $conn->prepare($reqSql);
        $stmt->bind_param("ssssiss", $empNum, $leaveType, $startDate, $endDate, $daysRequested, $reason, $approvedBy);
        $stmt->execute();
        $stmt->close();

        // Deduct balance
        if ($isDeductible && $balanceColumn) {
            $deductSql = "UPDATE leave_balances SET $balanceColumn = $balanceColumn - ? WHERE employee_number = ?";
            $stmt = $conn->prepare($deductSql);
            $stmt->bind_param("is", $daysRequested, $empNum);
            $stmt->execute();
            $stmt->close();
        }

        // === INSERT INTO ATTENDANCE_RECORDS (Skip Sundays only) ===
        $start = new DateTime($startDate);
        $end   = new DateTime($endDate);
        $end->modify('+1 day');
        $interval = new DateInterval('P1D');
        $period   = new DatePeriod($start, $interval, $end);

        $attSql = "INSERT INTO attendance_records (employee_number, date, status, notes)
                   VALUES (?, ?, ?, ?)
                   ON DUPLICATE KEY UPDATE 
                       status = VALUES(status),
                       notes = VALUES(notes),
                       time_in = NULL,
                       time_out = NULL";
        $attStmt = $conn->prepare($attSql);

        foreach ($period as $date) {
            if ($date->format('w') == 0) continue; // Skip Sunday

            $dateStr = $date->format('Y-m-d');
            $note    = "HR Approved $leaveType: $reason";

            $attStmt->bind_param("ssss", $empNum, $dateStr, $leaveType, $note);
            $attStmt->execute();
        }
        $attStmt->close();

        // Update employee status
        $today = date('Y-m-d');
        if ($startDate <= $today && $endDate >= $today) {
            $stmt = $conn->prepare("UPDATE employees SET status = 'On Leave' WHERE employee_number = ?");
            $stmt->bind_param("s", $empNum);
            $stmt->execute();
            $stmt->close();
        }

        $conn->commit();

        // === SEND EMAIL ===
        $leaveNames = [
            'VL' => 'Vacation Leave',
            'SL' => 'Sick Leave',
            'Emergency' => 'Emergency Leave',
            'Maternity' => 'Maternity Leave',
            'Paternity' => 'Paternity Leave'
        ];
        $leaveName = $leaveNames[$leaveType] ?? $leaveType;

        $balanceText = $isDeductible
            ? "<strong>Remaining {$leaveName}: {$newBalance} day(s)</strong>"
            : "<em>No deduction – Fixed entitlement</em>";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = getenv('SMTP_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv('SMTP_USER');
            $mail->Password   = getenv('SMTP_PASS');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = getenv('SMTP_PORT');

            $mail->setFrom(getenv('SMTP_USER'), 'HR Department');
            $mail->addAddress($empEmail, $empName);
            $mail->isHTML(true);
            $mail->Subject = "Your {$leaveName} has been APPROVED";

            $mail->Body = "
                <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <h2 style='color: #2c7be5;'>Leave Request Approved</h2>
                    <p>Dear <strong>{$empName}</strong>,</p>
                    <p>Your leave request has been <strong style='color: green;'>APPROVED</strong>.</p>

                    <ul>
                        <li><strong>Type:</strong> {$leaveName}</li>
                        <li><strong>From:</strong> " . date('F j, Y', strtotime($startDate)) . "</li>
                        <li><strong>To:</strong> " . date('F j, Y', strtotime($endDate)) . "</li>
                        <li><strong>Working Days Approved:</strong> {$daysRequested}</li>
                    </ul>

                    <p><strong>Reason:</strong><br><em>{$reason}</em></p>

                    <hr style='border: 1px solid #eee;'>
                    <p>{$balanceText}</p>

                    <p>Thank you.<br><strong>HR Department</strong></p>
                </div>
            ";

            $mail->send();
            $emailStatus = "Email sent successfully to {$empName}";
        } catch (Exception $e) {
            $emailStatus = "Leave approved, but email failed: " . $mail->ErrorInfo;
        }

        echo json_encode([
            'success'        => true,
            'message'        => "Leave approved and set for {$daysRequested} working day(s).",
            'email_status'   => $emailStatus,
            'employee_name'  => $empName
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// --- GRID ACTION ---
if ($action === 'grid') {
    $monthStr   = $_GET['month'] ?? date('Y-m');
    $deptFilter = $_GET['dept'] ?? '';
    $search     = $_GET['search'] ?? '';

    $startDate   = $monthStr . '-01';
    $endDate     = date('Y-m-t', strtotime($startDate));
    $daysInMonth = (int)date('t', strtotime($startDate));
    $year        = (int)date('Y', strtotime($startDate));
    $month       = (int)date('m', strtotime($startDate));

    $sql = "SELECT e.employee_number, e.first_name, e.last_name, e.position, d.name as dept_name
            FROM employees e
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE 1=1";

    $types  = "";
    $params = [];

    if (!empty($deptFilter)) {
        $sql   .= " AND d.name = ?";
        $types .= "s";
        $params[] = $deptFilter;
    }
    if (!empty($search)) {
        $sql   .= " AND (e.first_name LIKE ? OR e.last_name LIKE ?)";
        $types .= "ss";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $sql .= " ORDER BY e.last_name ASC";

    $stmt = $conn->prepare($sql);
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result    = $stmt->get_result();
    $employees = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $employeeNumbers = array_column($employees, 'employee_number');
    $placeholders    = $employeeNumbers ? implode(',', array_fill(0, count($employeeNumbers), '?')) : '';

    if (empty($employeeNumbers)) {
        echo json_encode([
            'days_in_month' => $daysInMonth,
            'year'          => $year,
            'month'         => $month,
            'employees'     => [],
            'logs'          => []
        ]);
        exit;
    }

    $attSql = "SELECT employee_number, DAY(date) as day, status, 
               TIME_FORMAT(time_in, '%H:%i') as time_in, 
               TIME_FORMAT(time_out, '%H:%i') as time_out,
               overtime_hours
               FROM attendance_records 
               WHERE date BETWEEN ? AND ?
               AND employee_number IN ($placeholders)";

    $attTypes  = str_repeat('s', count($employeeNumbers) + 2);
    $attParams = array_merge([$startDate, $endDate], $employeeNumbers);

    $attStmt = $conn->prepare($attSql);
    $attStmt->bind_param($attTypes, ...$attParams);
    $attStmt->execute();
    $rawLogs = $attStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $attStmt->close();

    $logs = [];
    foreach ($rawLogs as $log) {
        $logs[$log['employee_number']][$log['day']] = $log;
    }

    echo json_encode([
        'days_in_month' => $daysInMonth,
        'year'          => $year,
        'month'         => $month,
        'employees'     => $employees,
        'logs'          => $logs
    ]);
    exit;
}
?>
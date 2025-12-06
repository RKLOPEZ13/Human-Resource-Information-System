<?php
// attendance_data.php (Fixed to use $_SESSION and handle foreign key constraint)
session_start(); // START SESSION HERE

require '../config/db.php'; // Includes your $conn object

header('Content-Type: application/json');

// --- Helper Function for Working Days Calculation (Approximate) ---
function get_working_days($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $end->modify('+1 day'); // Include the end date

    $interval = new DateInterval('P1D');
    $period = new DatePeriod($start, $interval, $end);

    $days = 0;
    foreach ($period as $date) {
        $day = $date->format('w');
        // Exclude Saturday (6) and Sunday (0)
        if ($day != 0 && $day != 6) {
            $days++;
        }
    }
    return $days;
}

$action = $_GET['action'] ?? 'grid';

if ($action === 'get_filters') {
    // Fetch Departments for the dropdown
    $sql = "SELECT name FROM departments ORDER BY name";
    $result = $conn->query($sql);
    
    $departments = [];
    while($row = $result->fetch_assoc()) {
        $departments[] = $row['name'];
    }

    echo json_encode(['departments' => $departments]);
    exit;
}

if ($action === 'get_employees') {
    // Fetch Employees AND their Leave Balances for the Modal Datalist
    $sql = "SELECT e.employee_number, e.first_name, e.last_name, 
            lb.vacation_leave, lb.sick_leave, lb.emergency_leave, 
            lb.maternity_leave, lb.paternity_leave
            FROM employees e
            LEFT JOIN leave_balances lb ON e.employee_number = lb.employee_number
            WHERE e.status = 'Active'";
    
    $result = $conn->query($sql);
    
    $employees = [];
    while($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
    
    echo json_encode(['employees' => $employees]);
    exit;
}

if ($action === 'approve_leave') {
    // 1. Input and Validation
    $empNum = filter_input(INPUT_POST, 'employee_number', FILTER_SANITIZE_STRING);
    $leaveType = filter_input(INPUT_POST, 'leave_type', FILTER_SANITIZE_STRING);
    $startDate = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_STRING);
    $endDate = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_STRING);
    $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);

    if (empty($empNum) || empty($leaveType) || empty($startDate) || empty($endDate) || empty($reason)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    // --- FIX: Use session variable for 'approved_by' ---
    $approvedBy = $_SESSION['employee_number'] ?? NULL;
    
    if (is_null($approvedBy)) {
        http_response_code(401); 
        echo json_encode(['success' => false, 'message' => "Transaction Failed: HR User (employee_number) not found in session. Please log in."]);
        exit;
    }
    // -----------------------------------------------------------------------

    // Calculate days requested (excluding weekends)
    $daysRequested = get_working_days($startDate, $endDate);
    
    $isDeductible = in_array($leaveType, ['VL', 'SL', 'Emergency']);
    $balanceColumn = [
        'VL' => 'vacation_leave', 'SL' => 'sick_leave', 'Emergency' => 'emergency_leave'
    ][$leaveType] ?? null;

    // Start Transaction
    $conn->begin_transaction();

    try {
        // 2. Balance Check (Only for deductible leaves)
        if ($isDeductible) {
            $stmt = $conn->prepare("SELECT $balanceColumn FROM leave_balances WHERE employee_number = ? FOR UPDATE");
            $stmt->bind_param("s", $empNum);
            $stmt->execute();
            $result = $stmt->get_result();
            $currentBalance = $result->fetch_assoc()[$balanceColumn] ?? 0;
            $stmt->close();

            if ($currentBalance < $daysRequested) {
                throw new Exception("Insufficient leave balance ($currentBalance days left) for $daysRequested days requested.");
            }
        }

        // 3. Log into leave_requests table (HR Approved)
        $reqSql = "INSERT INTO leave_requests (employee_number, leave_type, start_date, end_date, days_requested, reason, status, approved_by, approval_date) 
                   VALUES (?, ?, ?, ?, ?, ?, 'Approved', ?, NOW())";
        
        $stmt = $conn->prepare($reqSql);
        $stmt->bind_param("ssssiss", $empNum, $leaveType, $startDate, $endDate, $daysRequested, $reason, $approvedBy);
        $stmt->execute();
        $stmt->close();
        
        // 4. Deduct from leave_balances
        if ($isDeductible) {
            $deductSql = "UPDATE leave_balances SET $balanceColumn = $balanceColumn - ? WHERE employee_number = ?";
            $stmt = $conn->prepare($deductSql);
            $stmt->bind_param("is", $daysRequested, $empNum);
            $stmt->execute();
            $stmt->close();
        }

        // 5. Insert/Update attendance_records for the entire range
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->modify('+1 day'); // Include the end date
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start, $interval, $end);
        
        $attStatus = $leaveType; 
        
        $attSql = "INSERT INTO attendance_records (employee_number, date, status, time_in, time_out, notes) 
                   VALUES (?, ?, ?, NULL, NULL, ?)
                   ON DUPLICATE KEY UPDATE status = VALUES(status), time_in = NULL, time_out = NULL, notes = VALUES(notes)";
                   
        $stmt = $conn->prepare($attSql);

        foreach ($period as $date) {
            $day = $date->format('w');
            // Skip weekends (0=Sun, 6=Sat)
            if ($day != 0 && $day != 6) {
                $dateStr = $date->format('Y-m-d');
                $note = "HR Approved $leaveType. Reason: $reason";
                
                $stmt->bind_param("ssss", $empNum, $dateStr, $attStatus, $note);
                $stmt->execute();
            }
        }
        $stmt->close();

        // 6. Update employee status to 'On Leave' if leave duration is current/future
        $today = date('Y-m-d');
        if ($endDate >= $today) {
             $empStatusSql = "UPDATE employees SET status = 'On Leave' WHERE employee_number = ?";
             $stmt = $conn->prepare($empStatusSql);
             $stmt->bind_param("s", $empNum);
             $stmt->execute();
             $stmt->close();
        }

        // Commit transaction
        $conn->commit();
        echo json_encode(['success' => true, 'message' => "Leave approved and set successfully for $daysRequested working days."]);

    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => "Transaction Failed: " . $e->getMessage()]);
    }
    exit;
}

// --- GRID ACTION (Remaing code) ---
if ($action === 'grid') {
    // 1. Receive Filters
    $monthStr = $_GET['month'] ?? date('Y-m');
    $deptFilter = $_GET['dept'] ?? '';
    $search = $_GET['search'] ?? '';

    // Calculate Date Range
    $startDate = $monthStr . '-01';
    $endDate = date('Y-m-t', strtotime($startDate));
    $daysInMonth = (int)date('t', strtotime($startDate));
    $year = (int)date('Y', strtotime($startDate));
    $month = (int)date('m', strtotime($startDate));

    // 2. Build Employee Query
    $sql = "SELECT e.employee_number, e.first_name, e.last_name, e.position, d.name as dept_name
            FROM employees e
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE 1=1";
    
    $types = "";
    $params = [];

    if (!empty($deptFilter)) {
        $sql .= " AND d.name = ?";
        $types .= "s";
        $params[] = $deptFilter;
    }

    if (!empty($search)) {
        $sql .= " AND (e.first_name LIKE ? OR e.last_name LIKE ?)";
        $types .= "ss";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $sql .= " ORDER BY e.last_name ASC";

    // Prepare and execute employee statement
    $stmt = $conn->prepare($sql);
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $employees = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    // Get list of employee numbers for the next query
    $employeeNumbers = array_column($employees, 'employee_number');
    $placeholders = implode(',', array_fill(0, count($employeeNumbers), '?'));
    
    // 3. Build Attendance Data Dictionary
    if (empty($employeeNumbers)) {
        echo json_encode([
            'days_in_month' => $daysInMonth,
            'year' => $year,
            'month' => $month,
            'employees' => [],
            'logs' => []
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
    
    $attTypes = str_repeat('s', count($employeeNumbers) + 2); // 'ss' for start/end date + 's' for each employee number
    $attParams = array_merge([$startDate, $endDate], $employeeNumbers);

    $attStmt = $conn->prepare($attSql);
    $attStmt->bind_param($attTypes, ...$attParams);
    $attStmt->execute();
    $rawLogs = $attStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $attStmt->close();

    // Reorganize logs: $logs['EMP001'][5] = {data}
    $logs = [];
    foreach ($rawLogs as $log) {
        $logs[$log['employee_number']][$log['day']] = $log;
    }

    // 4. Return Combined Data
    echo json_encode([
        'days_in_month' => $daysInMonth,
        'year' => $year,
        'month' => $month,
        'employees' => $employees,
        'logs' => $logs
    ]);
    exit;
}
?>
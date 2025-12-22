<?php
header('Content-Type: application/json');
require_once "../config/db.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$response = ['success' => false, 'data' => [], 'debug' => []];

try {
    // Check if connection is alive
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    $response['debug']['connection'] = 'OK';
    $response['debug']['database'] = $conn->query("SELECT DATABASE()")->fetch_row()[0];

    $today = date('Y-m-d');
    $response['debug']['today'] = $today;

    // First: Let's see ALL leave requests, no filter
    $test_sql = "SELECT id, employee_number, leave_type, start_date, status FROM leave_requests LIMIT 10";
    $test_result = $conn->query($test_sql);
    $response['debug']['total_leaves_in_table'] = $test_result->num_rows;

    $all_leaves = [];
    while ($row = $test_result->fetch_assoc()) {
        $all_leaves[] = $row;
    }
    $response['debug']['sample_leaves'] = $all_leaves;

    $sql = "
        SELECT 
            lr.id,
            lr.employee_number,
            e.full_name,
            d.name AS department_name,
            lr.leave_type,
            lr.start_date,
            lr.end_date,
            lr.status
        FROM leave_requests lr
        JOIN employees e ON lr.employee_number = e.employee_number
        LEFT JOIN departments d ON e.department_id = d.id
        WHERE lr.start_date >= ?
        AND lr.status IN ('Approved', 'Pending')
        ORDER BY lr.start_date ASC
        LIMIT 15
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $today);
    $stmt->execute();
    $result = $stmt->get_result();

    $response['debug']['filtered_row_count'] = $result->num_rows;

    $leaves = [];
    while ($row = $result->fetch_assoc()) {
        $initials = substr(implode('', array_map(fn($n) => strtoupper($n[0] ?? ''), explode(' ', trim($row['full_name'])))), 0, 2) ?: '??';

        $start = date('M j', strtotime($row['start_date']));
        $end = date('M j', strtotime($row['end_date']));
        $dates = ($row['start_date'] === $row['end_date']) ? $start : "$start - $end";

        $leaves[] = [
            'initials' => $initials,
            'full_name' => $row['full_name'],
            'department' => $row['department_name'] ?? 'N/A',
            'type' => (['VL'=>'Vacation Leave','SL'=>'Sick Leave','Emergency'=>'Emergency Leave'][$row['leave_type']] ?? $row['leave_type']),
            'type_class' => $row['leave_type'] === 'SL' ? 'warning' : ($row['leave_type'] === 'Emergency' ? 'danger' : 'info'),
            'dates' => $dates,
            'status' => $row['status'],
            'status_badge' => $row['status'] === 'Approved' ? 'success' : 'warning'
        ];
    }

    $response['success'] = true;
    $response['leaves'] = $leaves;

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
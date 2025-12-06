<?php
require '../config/db.php';

$metrics = [];

$totalEmployees = $conn->query("SELECT COUNT(*) AS total FROM employees");
$totalEmp = $totalEmployees->fetch_assoc();
$metrics['totalEmployees'] = $totalEmp['total'];

$onLeaveQuery = $conn->query("SELECT COUNT(*) AS total FROM employees WHERE status='On Leave'");
$onLeave = $onLeaveQuery->fetch_assoc();
$metrics['onLeave'] = $onLeave['total'];

$activeToday = $conn->query("
    SELECT COUNT(*) AS total 
    FROM employees 
    WHERE status = 'Active'
")->fetch_assoc()['total'];

$metrics['activeToday'] = $activeToday;

header('Content-Type: application/json');
echo json_encode($metrics);
?>

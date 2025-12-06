<?php
header('Content-Type: application/json');
$mysqli = new mysqli("localhost", "root", "", "hr_management_system");

if ($mysqli->connect_error) {
    die(json_encode(['error' => $mysqli->connect_error]));
}

$query = "SELECT d.name AS department, COUNT(e.employee_number) AS count
          FROM departments d
          LEFT JOIN employees e ON d.id = e.department_id AND e.status = 'Active'
          GROUP BY d.id, d.name
          ORDER BY d.name";

$result = $mysqli->query($query);

$departments = [];
$counts = [];

while ($row = $result->fetch_assoc()) {
    $departments[] = $row['department'] ?: 'Unassigned';
    $counts[] = (int)$row['count'];
}

echo json_encode([
    'departments' => $departments,
    'counts' => $counts
]);
?>
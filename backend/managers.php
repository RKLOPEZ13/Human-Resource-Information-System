<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$result = $conn->query("
    SELECT employee_number AS id,
           CONCAT(first_name,' ',last_name) AS full_name
    FROM employees
    WHERE status = 'Active'
    ORDER BY first_name
");

$managers = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $managers[] = $row;
    }
}
echo json_encode($managers);
?>
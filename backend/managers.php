<?php
header('Content-Type: application/json');
require_once '../config/db.php';

// Fetch all active employees who can be managers
// Return employee_number as 'id' to match your frontend expectation
$sql = "SELECT employee_number AS id, 
               CONCAT(first_name, ' ', last_name) as full_name 
        FROM employees 
        WHERE status = 'Active'
        ORDER BY first_name, last_name";

$result = $conn->query($sql);

$managers = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $managers[] = [
            'id' => $row['id'],  // This is actually employee_number
            'full_name' => $row['full_name']
        ];
    }
}

echo json_encode($managers);
$conn->close();
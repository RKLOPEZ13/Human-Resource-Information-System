<?php
// backend/get_announcement_data.php

// Correct path to db.php
require_once "../config/db.php";

header("Content-Type: application/json");

// Enable error reporting temporarily (remove later)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test if DB connection works
if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Get departments
$departments = [];
$result = $conn->query("SELECT id, name FROM departments ORDER BY name ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// Get employees
$employees = [];
$sql = "
    SELECT 
        e.employee_number,
        e.full_name,
        e.position,
        d.name AS dept_name,
        e.status
    FROM employees e
    LEFT JOIN departments d ON e.department_id = d.id
    ORDER BY e.full_name ASC
";

$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Final output
echo json_encode([
    "departments" => $departments,
    "employees"   => $employees
], JSON_UNESCAPED_UNICODE);
?>
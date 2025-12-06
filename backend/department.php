<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$result = $conn->query("SELECT name FROM departments ORDER BY name");
$depts = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $depts[] = $row['name'];
    }
}
echo json_encode($depts);
?>
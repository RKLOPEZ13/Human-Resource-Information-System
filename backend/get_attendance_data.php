<?php
header('Content-Type: application/json');
$mysqli = new mysqli("localhost", "root", "", "hr_management_system");

if ($mysqli->connect_error) {
    die(json_encode(['error' => $mysqli->connect_error]));
}

// Prepare data arrays
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$present = array_fill(0, 12, 0);
$absent = array_fill(0, 12, 0);
$late = array_fill(0, 12, 0);

$query = "SELECT DATE_FORMAT(date, '%m') AS month, 
                 SUM(status='P') AS present_days,
                 SUM(status='A') AS absent_days,
                 SUM(status='L') AS late_days
          FROM attendance_records
          WHERE YEAR(date)=2025
          GROUP BY month";
$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()) {
    $idx = intval($row['month']) - 1;
    $present[$idx] = intval($row['present_days']);
    $absent[$idx] = intval($row['absent_days']);
    $late[$idx] = intval($row['late_days']);
}

echo json_encode([
    'present' => $present,
    'absent'  => $absent,
    'late'    => $late
]);
?>

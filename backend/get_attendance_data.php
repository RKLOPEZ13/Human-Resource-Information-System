<?php
header('Content-Type: application/json');

$mysqli = new mysqli("localhost", "root", "", "hr_management_system");

if ($mysqli->connect_error) {
    echo json_encode(['error' => $mysqli->connect_error]);
    exit;
}

// Current year (auto)
$year = date('Y');

// Prepare data arrays
$months  = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$present = array_fill(0, 12, 0);
$absent  = array_fill(0, 12, 0);
$late    = array_fill(0, 12, 0);

$query = "
    SELECT DATE_FORMAT(date, '%m') AS month,
           SUM(status = 'P') AS present_days,
           SUM(status = 'A') AS absent_days,
           SUM(status = 'L') AS late_days
    FROM attendance_records
    WHERE YEAR(date) = $year
    GROUP BY month
";

$result = $mysqli->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $idx = (int)$row['month'] - 1;
        $present[$idx] = (int)$row['present_days'];
        $absent[$idx]  = (int)$row['absent_days'];
        $late[$idx]    = (int)$row['late_days'];
    }
}

// Return year for frontend display (optional)
echo json_encode([
    'year'    => $year,
    'present' => $present,
    'absent'  => $absent,
    'late'    => $late
]);
?>

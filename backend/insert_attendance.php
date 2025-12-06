<?php
require '../config/db.php';

// Get POST values
$empNumber       = $_POST['empNumber'];
$actionType      = $_POST['action_type'];   // in / out
$date            = $_POST['date'];
$timeIn          = $_POST['time_in'];
$timeOut         = $_POST['time_out'];
$status          = $_POST['status'];
$undertimeHours  = $_POST['undertime_hours'];
$overtimeHours   = $_POST['overtime_hours'];

// 1. VALIDATE EMPLOYEE EXISTS
$empCheck = $conn->prepare("
    SELECT employee_number 
    FROM employees 
    WHERE employee_number = ?
    LIMIT 1
");
$empCheck->bind_param("s", $empNumber);
$empCheck->execute();
$empResult = $empCheck->get_result();

if ($empResult->num_rows == 0) {
    die("Invalid employee number.");
}


// ======================================================
// ============= CLOCK IN LOGIC =========================
// ======================================================

if ($actionType === "in") {

    $check = $conn->prepare("
        SELECT id 
        FROM attendance_records
        WHERE employee_number = ?
          AND date = ?
        LIMIT 1
    ");
    $check->bind_param("ss", $empNumber, $date);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        die("You already clocked in today.");
    }

    // Insert new attendance row
    $insert = $conn->prepare("
        INSERT INTO attendance_records
        (employee_number, date, status, time_in)
        VALUES (?, ?, ?, ?)
    ");
    $insert->bind_param("ssss", $empNumber, $date, $status, $timeIn);

    if ($insert->execute()) {
        echo "Clock in recorded.";
    } else {
        echo "Error: " . $conn->error;
    }

    exit;
}



// ======================================================
// ============= CLOCK OUT LOGIC ========================
// ======================================================

if ($actionType === "out") {

    // Must find today's row
    $check = $conn->prepare("
        SELECT id
        FROM attendance_records
        WHERE employee_number = ?
          AND date = ?
          AND time_in IS NOT NULL
          AND time_out IS NULL
        LIMIT 1
    ");
    $check->bind_param("ss", $empNumber, $date);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows == 0) {
        die("Cannot clock out. No clock-in found or already clocked out.");
    }

    $row = $result->fetch_assoc();
    $attendanceId = $row['id'];

    // Update existing row
    $update = $conn->prepare("
        UPDATE attendance_records
        SET time_out = ?, undertime_hours = ?, overtime_hours = ?
        WHERE id = ?
    ");
    $update->bind_param("sddi", $timeOut, $undertimeHours, $overtimeHours, $attendanceId);

    if ($update->execute()) {
        echo "Clock out recorded.";
    } else {
        echo "Error: " . $conn->error;
    }

    exit;
}

echo "Invalid action.";

?>
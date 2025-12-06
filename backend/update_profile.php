<?php
session_start();
require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Invalid request");
}

$emp = $_SESSION['employee_number'];

$full = $_POST['fullName'];
$position = $_POST['position'];
$employment = $_POST['employment'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$email = $_POST['email'];

$parts = explode(" ", $full, 2);
$first = $parts[0];
$last = $parts[1] ?? "";

$sql = "UPDATE employees SET 
            first_name=?, 
            last_name=?, 
            position=?, 
            employment_type=?, 
            address=?, 
            phone=?, 
            email=?
        WHERE employee_number=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss",
    $first, $last, $position, $employment, 
    $address, $phone, $email, 
    $emp
);

if ($stmt->execute()) {

    $_SESSION['full_name'] = $full;
    $_SESSION['position'] = $position;
    $_SESSION['employment_type'] = $employment;
    $_SESSION['address'] = $address;
    $_SESSION['phone'] = $phone;
    $_SESSION['email'] = $email;

    header("Location: ../main.php?page=settings&updated=1");
    exit;
}

header("Location: ../main.php?page=settings&error=1");
exit;

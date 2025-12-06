<?php
session_start();
require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Invalid");
}

$emp = $_SESSION['employee_number'];

$current = $_POST['password'];
$new = $_POST['newpassword'];
$renew = $_POST['renewpassword'];

if ($new !== $renew) {
    header("Location: ../main.php?page=settings&./pages/settings.php?pw_mismatch=1");
    exit;
}

$stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE employee_number=?");
$stmt->bind_param("s", $emp);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!password_verify($current, $user['password_hash'])) {
    header("Location: ../main.php?page=settings&pw_wrong=1");
    exit;
}

$newHash = password_hash($new, PASSWORD_BCRYPT);

$update = $conn->prepare("UPDATE users SET password_hash=? WHERE id=?");
$update->bind_param("si", $newHash, $user['id']);
$update->execute();

header("Location: ../main.php?page=settings&pw_updated=1");
exit;

<?php
require '../config/db.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

$stmt = $conn->prepare("SELECT u.*, e.* 
                        FROM users u
                        JOIN employees e ON u.employee_number = e.employee_number
                        WHERE u.username = ? 
                        AND u.is_active = 1
                        LIMIT 1");

$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<script>alert('Invalid username or account inactive'); window.location.href='../index.php';</script>";
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password_hash'])) {
    echo "<script>alert('Incorrect password'); window.location.href='../index.php';</script>";
    exit;
}

$_SESSION['logged_in']          = true;
$_SESSION['user_id']            = $user['id'];                
$_SESSION['employee_number']    = $user['employee_number'];   
$_SESSION['full_name']          = $user['full_name'];
$_SESSION['first_name']         = $user['first_name'];
$_SESSION['last_name']          = $user['last_name'];
$_SESSION['address']            = $user['address'];
$_SESSION['email']              = $user['email'];
$_SESSION['phone']              = $user['phone'];
$_SESSION['position']           = $user['position'];
$_SESSION['employment_type']    = $user['employment_type'];

// 4. Update last login time
$update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE employee_number = ?");
$update->bind_param('s', $user['employee_number']);
$update->execute();

// 5. Redirect to dashboard
header('Location: ../main.php');
exit;
?>
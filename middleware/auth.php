<?php
session_start();

$publicPages = ['biometric'];

$page = $_GET['page'] ?? 'dashboard';

/* Allow public pages without login */
if (!in_array($page, $publicPages)) {
    if (empty($_SESSION['logged_in'])) {
        header('Location: index.php');
        exit;
    }
}

$allowedPages = [
    'announcement',
    'employees',
    'attendance',
    'settings',
    'dashboard',
];

$page = $_GET['page'] ?? 'dashboard';

if (!in_array($page, $allowedPages)) {
    http_response_code(404);
    exit('Page not found');
}
?>
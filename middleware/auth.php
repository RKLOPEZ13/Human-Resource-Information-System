<?php
// 1. Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Define Public Pages (Accessible without logging in)
$publicPages = ['biometric'];
$page = $_GET['page'] ?? 'dashboard';

// 3. Login Check: Allow public pages, otherwise force login
if (!in_array($page, $publicPages)) {
    if (empty($_SESSION['logged_in'])) {
        header('Location: index.php');
        exit;
    }
}

// 4. Role-Based Access Control (RBAC) Mapping
// Define which roles are allowed to access specific pages
$permissions = [
    'employees'    => ['HR', 'Admin'],
    'announcement' => ['HR', 'Admin'],
    'settings'     => ['HR', 'Employee', 'Manager', 'Admin'],
    'attendance'   => ['Employee', 'Manager', 'HR', 'Admin'], // Shared
    'dashboard'    => ['Employee', 'Manager', 'HR', 'Admin']  // Shared
];

// 5. Enforcement: Check if the current page has a restriction
if (array_key_exists($page, $permissions)) {
    $userRole = $_SESSION['role'] ?? 'Employee';
    
    // If the user's role is NOT in the allowed list for this page
    if (!in_array($userRole, $permissions[$page])) {
        // Redirect them back to the dashboard with an error message
        header('Location: main.php?page=dashboard&error=unauthorized');
        exit; // This stops main.php from rendering the restricted page
    }
}

// 6. 404 Protection: Final check for valid pages (your existing logic)
$allowedPages = ['announcement', 'employees', 'attendance', 'settings', 'dashboard', 'biometric'];

if (!in_array($page, $allowedPages)) {
    http_response_code(404);
    exit('Page not found');
}
?>
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

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main id="main" class="main">
    <?php include "pages/{$page}.php"; ?>
</main>

<?php
include 'includes/footer.php';
include 'includes/scripts.php';

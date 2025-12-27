<?php require 'middleware/auth.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main id="main" class="main">
    <?php include "pages/{$page}.php"; ?>
</main>

<?php include 'includes/footer.php';?>
<?php include 'includes/scripts.php'; ?>

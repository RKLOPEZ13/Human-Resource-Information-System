<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<main id="main" class="main">
  
  <?php
    if(isset($_SESSION['user_id'])){
      $page = $_GET['page'] ?? 'dashboard';
      include "pages/" . $page . ".php";
    }else{
      $error = "Force Logout, you need to login";
      echo "<script>
          alert('{$error}');
          window.location.href = 'index.php';
      </script>";
      exit;
    }

  ?>

</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>

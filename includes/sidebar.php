<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$userRole = $_SESSION['role'] ?? 'Employee'; // Get the role from the session
?>
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link <?php echo ($page == 'dashboard') ? 'active' : 'collapsed'; ?>" href="main.php?page=dashboard">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?php echo ($page == 'biometric') ? 'active' : 'collapsed'; ?>" href="main.php?page=biometric">
        <i class="bi bi-person-fill"></i>
        <span>Biometric</span>
      </a>
    </li>

    <?php if ($userRole === 'HR' || $userRole === 'Admin'): ?>
    <li class="nav-item">
      <a class="nav-link <?php echo ($page == 'employees') ? 'active' : 'collapsed'; ?>" href="main.php?page=employees">
        <i class="bi bi-people-fill"></i>
        <span>Employees</span>
      </a>
    </li>
    <?php endif; ?>

    <li class="nav-item">
      <a class="nav-link <?php echo ($page == 'attendance') ? 'active' : 'collapsed'; ?>" href="main.php?page=attendance">
        <i class="bi bi-calendar-check"></i>
        <span>Attendance</span>
      </a>
    </li>

    <?php if ($userRole === 'HR' || $userRole === 'Admin'): ?>
    <li class="nav-item">
      <a class="nav-link <?php echo ($page == 'announcement') ? 'active' : 'collapsed'; ?>" href="main.php?page=announcement">
        <i class="bi bi-megaphone-fill"></i>
        <span>Announcement</span>
      </a>
    </li>
    <?php endif; ?>

    <?php if ($userRole === 'Admin'): ?>
    <li class="nav-item">
      <a class="nav-link <?php echo ($page == 'settings') ? 'active' : 'collapsed'; ?>" href="main.php?page=settings">
        <i class="bi bi-gear-fill"></i>
        <span>User Settings</span>
      </a>
    </li>
    <?php endif; ?>

  </ul>
</aside>
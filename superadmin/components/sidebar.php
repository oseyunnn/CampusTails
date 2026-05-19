<?php
// Active page detection
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="logo-container">
        <img src="../resources/Logo.png" alt="CampusTails" width="160">
    </div>
    <nav>
        <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="users.php" class="<?= $current_page == 'users.php' ? 'active' : '' ?>">Users</a>
        <!-- Admins & Faculty Link Removed -->
        <a href="admin_codes.php" class="<?= $current_page == 'admin_codes.php' ? 'active' : '' ?>">Admin Codes</a>
        <a href="logs.php" class="<?= $current_page == 'logs.php' ? 'active' : '' ?>">Activity Logs</a>
        <a href="settings.php" class="<?= $current_page == 'settings.php' ? 'active' : '' ?>">System Settings</a>
        <hr style="border: 0.5px solid #eee; margin: 20px 0;">
        <a href="../logout.php" class="logout-link">Logout</a>
    </nav>
</div>
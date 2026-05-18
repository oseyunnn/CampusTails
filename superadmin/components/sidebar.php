<?php
// Active page detection
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="logo">
        <!-- Using your logo-white.png for contrast on the purple background -->
        <img src="../resources/logo-white.png" alt="CampusTails Logo" style="max-width: 180px;">
    </div>
    <nav>
        <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="users.php" class="<?= $current_page == 'users.php' ? 'active' : '' ?>">Users</a>
        <a href="admins.php" class="<?= $current_page == 'admins.php' ? 'active' : '' ?>">Admins & Faculty</a>
        <a href="admin_codes.php" class="<?= $current_page == 'admin_codes.php' ? 'active' : '' ?>">Admin Codes</a>
        <a href="logs.php" class="<?= $current_page == 'logs.php' ? 'active' : '' ?>">Activity Logs</a>
        <a href="settings.php" class="<?= $current_page == 'settings.php' ? 'active' : '' ?>">System Settings</a>
        <hr style="border: 0.5px solid var(--lavender); margin: 20px 0;">
        <a href="../logout.php" style="color: var(--soft-pink);">Logout</a>
    </nav>
</div>
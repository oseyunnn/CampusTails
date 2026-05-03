<?php
$role = $_SESSION['role'] ?? 'guest';
?>
<div class="navbar window">
    <a href="index.php" class="nav-item">Home</a>
    <a href="pets.php" class="nav-item">Pets</a>
    
    <?php if ($role == 'guest'): ?>
        <a href="login.php" class="nav-item">Login</a>
    <?php else: ?>
        <a href="profile.php" class="nav-item">Profile</a>
        <?php if ($role == 'admin'): ?>
            <a href="users.php" class="nav-item">Users</a>
            <a href="activity.php" class="nav-item">Activity Logs</a>
        <?php endif; ?>
        <a href="logout.php" class="nav-item">Logout</a>
    <?php endif; ?>
</div>
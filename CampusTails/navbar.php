<?php $role = $_SESSION['role'] ?? 'guest'; ?>
<nav class="navbar">
    <div class="logo">
        <h2 style="margin:0; font-size: 22px;">CampusTails</h2>
    </div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="pets.php">Pets</a>
        <?php if ($role == 'guest'): ?>
            <a href="login.php" style="background: var(--ct-purple); color:white; padding: 8px 20px; border-radius: 5px;">Login</a>
        <?php else: ?>
            <a href="dashboard.php">Dashboard</a>
            <?php if ($role == 'admin'): ?>
                <a href="users.php">Users</a>
                <a href="activity.php">Activity Logs</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>
</nav>
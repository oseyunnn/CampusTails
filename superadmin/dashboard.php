<?php 
session_start();
require_once '../utils/db_config.php';

// Role Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') { header("Location: ../login.php"); exit(); }

// Fetch Stats
$users = supabase_query("paw_users");
$pets = supabase_query("pets");
$admins = supabase_query("paw_users?account_type=eq.admin");
$codes = supabase_query("admin_codes?is_active=eq.true");

$logs = supabase_query("activity_logs?order=created_at.desc&limit=8");
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/superadmin.css">
    <title>Superadmin Dashboard</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo-container"><img src="../resources/Logo.png" width="160"></div>
        <nav>
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="users.php">Users</a>
            <a href="admin_codes.php">Admin Codes</a>
            <a href="logs.php">Activity Logs</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <h1>Superadmin Overview</h1>
        
        <div class="stats-grid">
            <div class="stat-card" style="background: var(--soft-pink);">
                <h2><?= is_array($users) ? count($users) : 0 ?></h2>
                <p>Total Users</p>
            </div>
            <div class="stat-card" style="background: var(--lavender);">
                <h2><?= is_array($pets) ? count($pets) : 0 ?></h2>
                <p>Registered Pets</p>
            </div>
            <div class="stat-card" style="background: var(--periwinkle);">
                <h2><?= is_array($admins) ? count($admins) : 0 ?></h2>
                <p>Admins</p>
            </div>
            <div class="stat-card" style="background: var(--primary-purple);">
                <h2><?= is_array($codes) ? count($codes) : 0 ?></h2>
                <p>Active Codes</p>
            </div>
        </div>

        <div class="glass-panel">
            <h3>Recent System Activity</h3>
            <table>
                <thead>
                    <tr><th>Action</th><th>Timestamp</th></tr>
                </thead>
                <tbody>
                    <?php if(is_array($logs)): foreach($logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td><?= date('M d, Y - h:i A', strtotime($log['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
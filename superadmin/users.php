<?php 
session_start();
require_once '../utils/db_config.php';

// Superadmin Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') { header("Location: ../login.php"); exit(); }

// Fetch All Users from Supabase
$users = supabase_query("paw_users?order=role.asc");
?>
<!DOCTYPE html>
<html>
<head>
    <title>CampusTails | User Management</title>
    <link rel="stylesheet" href="css/superadmin.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo-container"><img src="../resources/Logo.png" width="160"></div>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="users.php" class="active">Users</a>
            <a href="admin_codes.php">Admin Codes</a>
            <a href="logs.php">Activity Logs</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <h1>User Directory</h1>
        
        <div class="glass-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3>Registered Accounts</h3>
                <p style="color: var(--primary-purple); font-weight: 600;"><?= is_array($users) ? count($users) : 0 ?> Total Users</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Account Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($users)): foreach($users as $u): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <div class="user-avatar" style="display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--primary-purple);">
                                    <?= substr($u['first_name'], 0, 1) ?>
                                </div>
                                <div>
                                    <strong><?= htmlspecialchars($u['first_name'] . " " . $u['last_name']) ?></strong><br>
                                    <small style="color: #888;">@<?= htmlspecialchars($u['username']) ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="badge badge-<?= strtolower($u['role']) ?>">
                                <?= strtoupper($u['role']) ?>
                            </span>
                        </td>
                        <td><span style="text-transform: capitalize; color: #666;"><?= $u['account_type'] ?></span></td>
                        <td>
                            <button class="btn-pretty" style="padding: 5px 12px; font-size: 0.8rem; background: var(--periwinkle);">View Profile</button>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
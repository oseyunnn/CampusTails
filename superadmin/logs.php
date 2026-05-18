<?php 
session_start();
require_once '../utils/db_config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') { header("Location: ../login/index.php"); exit(); }

$logs = supabase_query("activity_logs?order=created_at.desc");
?>
<!DOCTYPE html>
<html>
<head>
    <title>CampusTails | Activity Logs</title>
    <link rel="stylesheet" href="css/superadmin.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo-container"><img src="../resources/Logo.png" width="160"></div>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="users.php">Users</a>
            <a href="admin_codes.php">Admin Codes</a>
            <a href="logs.php" class="active">Activity Logs</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <!-- --- NEW: THIS LOGO ONLY SHOWS IN PDF --- -->
        <div class="print-only-header">
            <img src="../resources/Logo.png" width="200" alt="CampusTails Logo">
            <h1 style="margin-top: 10px;">System Activity Audit Report</h1>
            <p>Generated on: <?= date('M d, Y - h:i A') ?></p>
            <hr style="border: 0.5px solid #eee; margin: 20px 0;">
        </div>

        <h1>System Activity Logs</h1>
        
        <div class="glass-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;" class="no-print">
                <h3>Event History</h3>
                <!-- Trigger the browser print dialog -->
                <button class="btn-pretty" style="background: var(--soft-pink);" onclick="window.print()">
                    Export to PDF
                </button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Action Performed</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($logs)): foreach($logs as $log): ?>
                    <tr>
                        <td style="color: #888; font-size: 0.9rem;">
                            <strong><?= date('M d, Y', strtotime($log['created_at'])) ?></strong><br>
                            <?= date('h:i A', strtotime($log['created_at'])) ?>
                        </td>
                        <td>
                            <div style="padding: 10px; background: rgba(246, 214, 248, 0.3); border-radius: 10px; border-left: 4px solid var(--primary-purple);">
                                <?= htmlspecialchars($log['action']) ?>
                            </div>
                        </td>
                        <td><span class="badge" style="background: #e2f9e1; color: #2d6a4f;">Success</span></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="3">No system logs available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>



    
</body>
</html>
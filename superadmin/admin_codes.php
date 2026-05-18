<?php 
session_start();
require_once '../utils/db_config.php';

// Verification
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') { header("Location: ../login.php"); exit(); }

// Generation Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
    $role = $_POST['target_role'];
    $code = "CT-" . strtoupper(bin2hex(random_bytes(2))) . "-" . strtoupper(bin2hex(random_bytes(2)));
    $data = ["admin_code" => $code, "role" => $role, "created_by" => $_SESSION['user_id'], "is_active" => true];
    supabase_query("admin_codes", "POST", $data);
}

$active_codes = supabase_query("admin_codes?is_active=eq.true&order=created_at.desc");
?>
<!DOCTYPE html>
<html>
<head>
    <title>CampusTails | Admin Codes</title>
    <link rel="stylesheet" href="css/superadmin.css">
    <style>
        .copy-btn {
            background: var(--periwinkle);
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.75rem;
            margin-left: 10px;
            transition: 0.3s;
        }
        .copy-btn:hover { background: var(--primary-purple); }
        .code-display { font-family: 'Courier New', Courier, monospace; font-weight: bold; color: var(--primary-purple); font-size: 1.1rem; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo-container"><img src="../resources/Logo.png" width="160"></div>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="users.php">Users</a>
            <a href="admin_codes.php" class="active">Admin Codes</a>
            <a href="logs.php">Activity Logs</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <h1>Registration Codes</h1>
        
        <div class="glass-panel" style="max-width: 500px;">
            <h3>Generate New Code</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Target Role</label>
                    <select name="target_role">
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                    </select>
                </div>
                <button type="submit" name="generate" class="btn-pretty">Create Code</button>
            </form>
        </div>

        <div class="glass-panel">
            <h3>Active Invitation Codes</h3>
            <table>
                <thead>
                    <tr><th>Code</th><th>Role</th><th>Created At</th></tr>
                </thead>
                <tbody>
                    <?php if(is_array($active_codes)): foreach($active_codes as $c): ?>
                    <tr>
                        <td>
                            <span class="code-display" id="code-<?= $c['admin_code'] ?>"><?= $c['admin_code'] ?></span>
                            <button class="copy-btn" onclick="copyCode('<?= $c['admin_code'] ?>', this)">Copy</button>
                        </td>
                        <td><span class="badge badge-purple"><?= ucfirst($c['role']) ?></span></td>
                        <td><?= date('M d, Y', strtotime($c['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="3">No active codes available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function copyCode(code, btn) {
            // Copy to clipboard
            navigator.clipboard.writeText(code).then(() => {
                // Visual feedback
                const originalText = btn.innerText;
                btn.innerText = "Copied!";
                btn.style.background = "#b85c95"; // Green feedback
                
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.style.background = ""; // Back to periwinkle
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    </script>
</body>
</html>
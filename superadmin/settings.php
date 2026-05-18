<?php require_once 'components/auth_check.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/superadmin.css">
    <title>Settings | CampusTails</title>
</head>
<body>
    <?php include 'components/sidebar.php'; ?>
    <div class="main-content">
        <h1>System Settings</h1>
        <div class="data-card" style="max-width: 600px;">
            <form action="actions/update_settings.php" method="POST">
                <div class="form-group">
                    <label>Site Name</label>
                    <input type="text" name="site_name" value="CampusTails" class="form-control">
                </div>
                <div class="form-group">
                    <label>Maintenance Mode</label>
                    <select name="maint_mode" class="form-control">
                        <option value="0">Disabled (Site Live)</option>
                        <option value="1">Enabled (Admin Only)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Allow New Registrations</label>
                    <input type="checkbox" checked>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top:20px;">Save Configuration</button>
            </form>
        </div>
    </div>
</body>
</html>
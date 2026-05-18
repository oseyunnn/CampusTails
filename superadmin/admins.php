<?php
require_once 'components/auth_check.php';
require_once '../db_config.php';

// Fetch users who are NOT regular 'user' role
$query = "SELECT u.*, f.office, f.institutional_email 
          FROM paw_users u 
          LEFT JOIN faculty_profiles f ON u.user_id = f.user_id 
          WHERE u.role IN ('admin', 'faculty') 
          ORDER BY u.role ASC";
$staff = $pdo->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/superadmin.css">
    <title>Staff Management | CampusTails</title>
</head>
<body>
    <?php include 'components/sidebar.php'; ?>
    <div class="main-content">
        <h1>Admins & Faculty Profiles</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Staff Member</th>
                        <th>Role</th>
                        <th>Office/Affiliation</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($s = staff->fetch()): ?>
                    <tr>
                        <td><strong><?= $s['first_name'] ?></strong></td>
                        <td><span class="badge"><?= strtoupper($s['role']) ?></span></td>
                        <td><?= $s['office'] ?? $s['affiliation'] ?? 'Not Set' ?></td>
                        <td><?= $s['institutional_email'] ?? $s['email'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
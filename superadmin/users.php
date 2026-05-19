<?php 
session_start();
require_once '../utils/db_config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') { header("Location: ../login.php"); exit(); }

// Fetch Users
$users = supabase_query("paw_users?order=role.asc");
?>
<!DOCTYPE html>
<html>
<head>
    <title>CampusTails | User Directory</title>
    <link rel="stylesheet" href="css/superadmin.css">
</head>
<body>
    <?php include 'components/sidebar.php'; ?>

    <div class="main-content">
        <h1>User Directory</h1>
        
        <!-- ... inside main content ... -->
<div class="glass-panel">
   <table>
    <thead>
        <tr>
            <th class="col-user">User</th>
            <th class="col-email">Email</th>
            <th class="col-role">Role</th>
            <th class="col-type">Account Type</th>
            <th class="col-action">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $u): ?>
        <tr>
            <td class="col-user">
                <strong><?= $u['first_name'] ?></strong><br>
                <small>@<?= $u['username'] ?></small>
            </td>
            <td class="col-email"><?= $u['email'] ?></td>
            <td class="col-role">
                <span class="badge badge-<?= strtolower($u['role']) ?>"><?= $u['role'] ?></span>
            </td>
            <td class="col-type"><?= ucfirst($u['account_type']) ?></td>
            <td class="col-action">
    <?php 
    // Check if the role is superadmin OR if the account type is admin
    $role = strtolower($u['role'] ?? '');
    $type = strtolower($u['account_type'] ?? '');

    if ($type !== 'superadmin'): 
    ?>
        <button class="btn-pretty" 
                style="background: #F0A6B1; padding: 10px 22px; font-size: 0.8rem; border-radius: 12px;" 
                onclick="openDeleteModal('<?= $u['user_id'] ?>')">
            Delete
        </button>
    <?php endif; ?>
</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
    </div>

    <!-- --- Centered Modals (Previously added logic) --- -->
    <div id="confirmModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Confirm Deletion</h3>
            <p style="color: #777;">Are you sure you want to permanently delete this user account? This action cannot be undone.</p>
            <div class="modal-btns">
                <button class="btn-modal btn-cancel" onclick="closeModal('confirmModal')">Cancel</button>
                <button id="finalDeleteBtn" class="btn-modal btn-delete-final">Delete</button>
            </div>
        </div>
    </div>

    <div id="successModal" class="modal-overlay">
        <div class="modal-box">
            <h3 style="color: #F0A6B1;">Account Deleted</h3>
            <p style="color: #777;">The user account has been successfully removed from the system registry.</p>
            <div style="margin-top: 25px;">
                <button class="btn-modal btn-close-success" style="width: 100%;" onclick="location.reload()">Close</button>
            </div>
        </div>
    </div>

    <script>
      // This function is called when you click the "Delete" button in the table
let userIdToDelete = null;

function openDeleteModal(id) {
    userIdToDelete = id;
    document.getElementById('confirmModal').style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// This runs when you click the "Delete" button INSIDE the pop-up
document.getElementById('finalDeleteBtn').onclick = function() {
    if (!userIdToDelete) return;

    // We call the PHP script we just made
    fetch(`actions/delete_user.php?id=${userIdToDelete}`)
        .then(response => {
            if (!response.ok) throw new Error('File not found or Server Error');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                closeModal('confirmModal');
                document.getElementById('successModal').style.display = 'flex';
            } else {
                // Log full response for debugging without exposing secrets
                console.error('Delete failed response:', data);
                alert("Deletion failed: " + data.message + "\nCheck browser console for details.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Could not reach the server. Check if 'superadmin/actions/delete_user.php' exists.");
        });
};
    </script>
</body>
</html>
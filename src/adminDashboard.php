<?php 
include 'db.php'; 
if (!isset($_SESSION['user_id'])) header("Location: login.php");
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="window">
        <div class="title-bar"><span>PawCenterbase - Dashboard</span></div>
        <div class="window-body">
            <h2 align="center">PawCenterbase</h2>
            
            <div class="grid-4">
                <div class="card pink">50 Registered Pets</div>
                <div class="card lavender">25 Fully Vaccinated</div>
                <div class="card indigo">5 Under Observation</div>
                <div class="card purple">120 Activity Logs</div>
            </div>

            <?php if ($_SESSION['role'] == 'admin'): ?>
            <fieldset>
                <legend>PawCrew Tools (Admin Only)</legend>
                <div style="display:flex; gap:20px; padding:10px;">
                    <div onclick="location.href='pets.php?action=add'" style="cursor:pointer; text-align:center;">
                        <img src="https://img.icons8.com/color/48/000000/dog.png"/><br>Add PawFriend
                    </div>
                    <div onclick="location.href='vaccines.php'" style="cursor:pointer; text-align:center;">
                        <img src="https://img.icons8.com/color/48/000000/syringe.png"/><br>Vaccination
                    </div>
                    <div onclick="location.href='users.php'" style="cursor:pointer; text-align:center;">
                        <img src="https://img.icons8.com/color/48/000000/group.png"/><br>User Mgmt
                    </div>
                    <div onclick="location.href='activity.php'" style="cursor:pointer; text-align:center;">
                        <img src="https://img.icons8.com/color/48/000000/list.png"/><br>Activity Logs
                    </div>
                </div>
            </fieldset>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
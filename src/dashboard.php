<?php include 'db.php'; 
if(!isset($_SESSION['user_id'])) header("Location: login.php");
?>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>PawCenterbase | Dashboard</title>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1 align="center">PawCenterbase Overview</h1>
        
        <!-- 4-Column Stat Grid from PDF Page 3 -->
        <div class="stat-grid">
            <div class="stat-card pink">
                <h2>50</h2>
                <p>Registered Pets</p>
            </div>
            <div class="stat-card lavender">
                <h2>25</h2>
                <p>Fully Vaccinated</p>
            </div>
            <div class="stat-card indigo">
                <h2>5</h2>
                <p>Under Observation</p>
            </div>
            <div class="stat-card purple">
                <h2>120</h2>
                <p>Activity Logs</p>
            </div>
        </div>

        <?php if($_SESSION['role'] == 'admin'): ?>
        <h3>PawCrew Tools</h3>
        <div class="tools-container">
            <div class="tool-item" onclick="location.href='pets.php?add=1'">
                <div style="font-size:30px;">🐾</div>
                <p>Add a PawFriend</p>
            </div>
            <div class="tool-item">
                <div style="font-size:30px;">💉</div>
                <p>Vaccination Records</p>
            </div>
            <div class="tool-item" onclick="location.href='users.php'">
                <div style="font-size:30px;">👥</div>
                <p>User Management</p>
            </div>
            <div class="tool-item" onclick="location.href='activity.php'">
                <div style="font-size:30px;">📝</div>
                <p>Activity Logs</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
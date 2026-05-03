<?php 
include 'db.php'; 

// Fetch actual counts for the "Impact" section
$petCount = $pdo->query("SELECT COUNT(*) FROM pets")->fetchColumn();
$adminCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
$logCount = $pdo->query("SELECT COUNT(*) FROM activity_logs")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Welcome to CampusTails</title>
    <style>
        .hero-container {
            text-align: center;
            max-width: 800px;
            margin: 50px auto;
        }
        .main-title {
            font-family: "Times New Roman", serif; /* Stylized serif per instructions */
            font-size: 48px;
            color: var(--ct-purple);
            margin-bottom: 10px;
            text-shadow: 2px 2px #fff;
        }
        .impact-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 30px 0;
        }
        .impact-card {
            background: var(--win-gray);
            border: 2px outset #fff;
            padding: 20px;
        }
        .impact-card h2 { margin: 0; font-size: 24px; color: var(--ct-indigo); }
        .impact-card p { margin: 5px 0 0; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        
        .contact-box {
            margin-top: 40px;
            padding: 20px;
            border: 2px inset #fff;
            background: #e6e6e6;
        }
    </style>
</head>
<body>

    <!-- Role-Based Navbar (Guest version will show) -->
    <?php include 'navbar.php'; ?>

    <div class="hero-container">
        <h1 class="main-title">CampusTails</h1>
        <p style="font-size: 16px;">Campus Pet Monitoring System</p>

        <div class="window">
            <div class="title-bar"><span>System Overview</span></div>
            <div class="window-body">
                <p>Welcome to the official monitoring system for our campus furry friends. 
                   Browse our pet gallery to see who is currently on campus and check their health status.</p>
                
                <h3 style="margin-top:20px; text-decoration: underline;">Our Impact</h3>
                <div class="impact-grid">
                    <div class="impact-card">
                        <h2><?php echo $petCount; ?></h2>
                        <p>Total Pets Monitored</p>
                    </div>
                    <div class="impact-card">
                        <h2><?php echo $adminCount; ?></h2>
                        <p>Active Admins</p>
                    </div>
                    <div class="impact-card">
                        <h2><?php echo $logCount; ?></h2>
                        <p>Monthly Logs</p>
                    </div>
                </div>

                <div class="contact-box">
                    <h3>Contact</h3>
                    <p>Want to contribute? Contact an Administrator for access.</p>
                    <p>Questions about CampusTails? <a href="mailto:admin@campus.edu">Email us</a></p>
                </div>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <a href="pets.php"><button style="padding: 10px 20px; font-weight: bold;">View Pet Gallery -></button></a>
        </div>
    </div>

</body>
</html>
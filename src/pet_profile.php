<?php 
include 'db.php'; 
$isAdmin = ($_SESSION['role'] == 'admin');
// Fetch pet details... (Jerry example)
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="window" style="max-width: 600px; margin: auto;">
        <div class="title-bar"><span>Pet Profile: Jerry</span></div>
        <div class="window-body">
            <div style="display:flex; gap:15px;">
                <img src="pet-avatar.jpg" style="width:100px; height:100px; border-radius:50%; border:2px solid var(--ct-purple);">
                <div>
                    <h2>Jerry <span class="badge">RTL Building</span> <span class="badge">Healthy</span></h2>
                    <p>Species: Dog | Golden Retriever</p>
                </div>
            </div>

            <div class="window" style="margin-top:15px;">
                <div class="navbar" style="background:#d0d0d0">
                    <button onclick="showTab('profile')">Pet Profile</button>
                    <button onclick="showTab('health')">Health Records</button>
                </div>
                
                <div id="tab-profile" class="tab-content" style="padding:10px;">
                    <p><b>Date Found:</b> March 12, 2024 <?php if($isAdmin) echo "✏️"; ?></p>
                    <p><b>Description:</b> Very friendly campus dog. <?php if($isAdmin) echo "✏️"; ?></p>
                </div>

                <div id="tab-health" class="tab-content" style="padding:10px; display:none;">
                    <p><b>Vaccine:</b> Anti-Rabies</p>
                    <p><b>Last Vaccine:</b> Feb 14, 2024</p>
                    <p><b>Next Due:</b> April 14, 2025 <?php if($isAdmin) echo "✏️"; ?></p>
                    <?php if($isAdmin): ?>
                        <button style="background:red; color:white; margin-top:10px;">Delete Pet Record</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    function showTab(tab) {
        document.getElementById('tab-profile').style.display = (tab === 'profile') ? 'block' : 'none';
        document.getElementById('tab-health').style.display = (tab === 'health') ? 'block' : 'none';
    }
    </script>
</body>
</html>
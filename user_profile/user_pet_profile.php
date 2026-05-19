<?php
session_start();
include('../utils/db_config.php');

$pet_id = $_GET['id'] ?? null;
if (!$pet_id) { header("Location: user_pets.php"); exit(); }

// Fetch Pet + Joined Health Records
// We use Supabase select with parens to get related table data
$endpoint = "pets?pet_id=eq.$pet_id&select=*,pet_vaccinations(*),medications(*),medical_history(*)";
$result = supabase_query($endpoint);

if (empty($result)) { die("Pet not found."); }
$pet = $result[0];

$vaccines = $pet['pet_vaccinations'] ?? [];
$meds = $pet['medications'] ?? [];
$history = $pet['medical_history'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CampusTails | <?php echo $pet['pet_name']; ?></title>
    <link rel="stylesheet" href="pets_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="master-wrapper">
        <header>
            <div class="nav-container">
                <div class="logo"><img src="../resources/Logo.png" alt="Logo"></div>
                <nav class="main-nav">
                    <a href="../user/index.php">home</a>
                    <a href="user_pets.php" class="active">pets</a>
                    <a href="index.php">profile</a>
                    <a href="../login/index.php">login</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <h1 class="page-title">Paw Profile</h1>

            <!-- HERO SECTION -->
            <section class="hero-block">
                <div class="cover-photo" style="background-image: url('<?php echo $pet['image_url']; ?>');"></div>
                <div class="hero-id-area">
                    <div class="avatar-border">
                        <div class="avatar-img" style="background-image: url('<?php echo $pet['image_url']; ?>');"></div>
                    </div>
                    <div class="name-controls">
                        <div class="title-row">
                            <h2><?php echo htmlspecialchars($pet['pet_name']); ?></h2>
                            <div class="action-icons">
                                <button class="icon-circle heart-btn"><i class="fas fa-heart"></i></button>
                                <button class="icon-circle gift-btn"><i class="fas fa-gift"></i></button>
                            </div>
                        </div>
                        <div class="hero-pills">
                            <div class="pill"><i class="fas fa-map-marker-alt"></i> <?php echo $pet['breed'] ?? 'Main Campus'; ?> Building</div>
                            
                            <?php if($pet['status'] === 'adopted'): ?>
                                <div class="pill adopted-pill"><i class="fas fa-home"></i> Already Adopted</div>
                            <?php else: ?>
                                <div class="pill adopt-request-pill"><i class="fas fa-home"></i> Request to Adopt</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <div class="tab-switcher">
                <button class="tab-btn active" onclick="openTab(event, 'pet-info')">Pet Profile</button>
                <button class="tab-btn" onclick="openTab(event, 'health-info')">Health Records</button>
            </div>

            <!-- TAB: PET PROFILE -->
            <div id="pet-info" class="tab-content active">
                <div class="data-card">
                    <div class="data-row"><label>Name</label> <strong><?php echo $pet['pet_name']; ?></strong></div>
                    <div class="data-row"><label>Species</label> <strong><?php echo $pet['species']; ?></strong></div>
                    <div class="data-row no-border"><label>Likes</label></div>
                    <div class="large-text"><strong><?php echo $pet['description']; ?></strong></div>
                    <div class="data-row"><label>Date Found</label> <strong>March 25, 2026</strong></div>
                    <div class="data-row no-border"><label>Allergies</label></div>
                    <div class="large-text"><strong>Cheese, dairy, and eggs.</strong></div>
                </div>
            </div>

            <!-- TAB: HEALTH RECORDS -->
            <div id="health-info" class="tab-content">
                <div class="data-card">
                    <h3 class="record-title">Vaccine Records</h3>
                    <?php foreach($vaccines as $v): ?>
                        <div class="record-item">
                            <div class="data-row"><label>Vaccine Name</label> <strong><?php echo $v['vaccine_name']; ?></strong></div>
                            <div class="data-row"><label>Date Administered</label> <strong><?php echo $v['vaccination_date']; ?></strong></div>
                            <!-- Add other rows as needed -->
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
        <footer>www.campustails.com</footer>
    </div>

    <script>
        function openTab(evt, tabName) {
            document.querySelectorAll('.tab-content').forEach(t => t.style.display = 'none');
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(tabName).style.display = 'block';
            evt.currentTarget.classList.add('active');
        }
    </script>
</body>
</html>
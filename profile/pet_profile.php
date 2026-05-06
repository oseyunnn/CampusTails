<?php
include('../utils/db_config.php');

$pet_id = $_GET['id'] ?? null;
if (!$pet_id) { die("Error: Pet ID required."); }

// Fetch Pet + Records
$endpoint = "pets?id=eq.$pet_id&select=*,vaccine_records(*),medications(*),medical_history(*)";
$result = supabase_query($endpoint);
if (empty($result)) { die("Error: Pet not found."); }
$pet = $result[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | <?php echo $pet['name']; ?>'s Profile</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="view-mode" id="page-body"> <!-- 'edit-mode' class will be toggled here -->

    <div class="dashboard-wrapper">
        <header>
            <div class="nav-container">
                <div class="logo"><img src="../resources/Logo.png" alt="CampusTails"></div>
                <nav class="main-nav">
                    <a href="dashboard.php">home</a>
                    <a href="#" class="active">pets</a>
                    <a href="#">profile</a>
                    <a href="#" class="logout-btn">logout</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <h1 class="section-heading">Paw Profile</h1>

            <!-- HERO SECTION -->
            <div class="profile-hero">
                <div class="cover-photo" style="background-image: url('<?php echo $pet['cover_img']; ?>');">
                    <div class="edit-overlay"><i class="fas fa-camera"></i></div>
                </div>
                
                <div class="hero-details">
                    <div class="avatar-box" style="background-image: url('<?php echo $pet['profile_img']; ?>');">
                        <div class="edit-overlay"><i class="fas fa-camera"></i></div>
                    </div>
                    
                    <div class="name-block">
                        <h2 class="view-only"><?php echo $pet['name']; ?></h2>
                        <input type="text" class="edit-only name-input" value="<?php echo $pet['name']; ?>">
                        
                        <div class="hero-actions">
                            <div class="action-pill">
                                <i class="fas fa-map-marker-alt"></i> 
                                <span class="view-only"><?php echo $pet['location']; ?></span>
                                <input type="text" class="edit-only pill-input" value="<?php echo $pet['location']; ?>">
                            </div>
                            <button class="action-pill edit-trigger view-only" onclick="toggleEdit(true)">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB SWITCHER -->
            <div class="tab-controls">
                <button id="tab-btn-profile" class="tab-btn active" onclick="switchSegment('profile')">Pet Profile</button>
                <button id="tab-btn-health" class="tab-btn" onclick="switchSegment('health')">Health Records</button>
            </div>

            <form id="updatePetForm">
                <!-- SEGMENT: BASIC PROFILE -->
                <div id="seg-profile" class="info-segment active">
                    <div class="info-card">
                        <div class="info-row">
                            <label><i class="fas fa-pencil-alt edit-only"></i> Name</label>
                            <span class="view-only"><?php echo $pet['name']; ?></span>
                            <input type="text" class="edit-only" value="<?php echo $pet['name']; ?>">
                        </div>
                        <div class="info-row">
                            <label><i class="fas fa-pencil-alt edit-only"></i> Species</label>
                            <span class="view-only"><?php echo $pet['species']; ?></span>
                            <select class="edit-only">
                                <option <?php if($pet['species']=='Dog') echo 'selected'; ?>>Dog</option>
                                <option <?php if($pet['species']=='Cat') echo 'selected'; ?>>Cat</option>
                            </select>
                        </div>
                        <div class="info-row vertical">
                            <label><i class="fas fa-pencil-alt edit-only"></i> Likes</label>
                            <span class="view-only"><?php echo $pet['likes']; ?></span>
                            <textarea class="edit-only"><?php echo $pet['likes']; ?></textarea>
                        </div>
                        <div class="info-row">
                            <label><i class="fas fa-pencil-alt edit-only"></i> Date Found</label>
                            <span class="view-only"><?php echo date("M d, Y", strtotime($pet['date_found'])); ?></span>
                            <input type="date" class="edit-only" value="<?php echo $pet['date_found']; ?>">
                        </div>
                        <div class="info-row vertical">
                            <label><i class="fas fa-pencil-alt edit-only"></i> Allergies</label>
                            <span class="view-only"><?php echo $pet['allergies']; ?></span>
                            <textarea class="edit-only"><?php echo $pet['allergies']; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- SEGMENT: HEALTH RECORDS -->
                <div id="seg-health" class="info-segment">
                    <!-- Vaccine Section -->
                    <div class="record-header">
                        <span>Vaccine Records</span>
                        <button type="button" class="add-btn edit-only">Add</button>
                    </div>
                    <div class="info-card">
                        <?php foreach($pet['vaccine_records'] as $v): ?>
                        <div class="dynamic-entry">
                            <div class="info-row">
                                <label><i class="fas fa-pencil-alt edit-only"></i> Vaccine Name</label>
                                <span class="view-only"><?php echo $v['vaccine_name']; ?></span>
                                <input type="text" class="edit-only" value="<?php echo $v['vaccine_name']; ?>">
                            </div>
                            <!-- ... Repeat rows for administered, due date, etc ... -->
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Other Health Segments (Medications, History) following same pattern -->
                </div>

                <!-- FOOTER BUTTONS (Edit Only) -->
                <div class="edit-footer-btns edit-only">
                    <button type="button" class="btn-cancel" onclick="toggleEdit(false)">Cancel</button>
                    <button type="submit" class="btn-save">Save</button>
                </div>
            </form>
        </main>

        <footer>www.campustails.com</footer>
    </div>

    <script src="script.js"></script>
</body>
</html>
<?php
session_start();
// Pointing to utils from the user_profile folder
include('../utils/db_config.php'); 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch User Data with Student or Faculty details
try {
    // Note: Adjusting column names (password_hash, etc) based on your Project_Database.txt
    $query = "SELECT u.*, s.student_number, s.program, s.year_level, f.office, f.institutional_email 
              FROM users u 
              LEFT JOIN student_profiles s ON u.user_id = s.user_id 
              LEFT JOIN faculty_profiles f ON u.user_id = f.user_id 
              WHERE u.user_id = :uid";
    
    // Assuming your db_config.php provides a PDO object named $conn
    $stmt = $conn->prepare($query);
    $stmt->execute([':uid' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) die("User not found in database.");

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// Fetch Favorite Pets for this user
// Endpoint uses Supabase join syntax: favorites?user_id=eq.ID&select=pets(*)
$fav_endpoint = "favorites?user_id=eq.$user_id&select=pets(*)";
$fav_results = supabase_query($fav_endpoint);

// If the API returns nested objects, we extract the pet data
$favorite_pets = [];
if (!empty($fav_results)) {
    foreach ($fav_results as $row) {
        if (isset($row['pets'])) {
            $favorite_pets[] = $row['pets'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | <?php echo htmlspecialchars($user['first_name']); ?>'s Profile</title>
    <link rel="stylesheet" href="user_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="profile-view-body">

    <div class="profile-master-wrapper">
        <header>
            <div class="nav-container">
                <!-- Resources are in root/resources/ -->
                <div class="logo"><img src="../resources/Logo.png" alt="CampusTails"></div>
                <nav class="main-nav">
                    <a href="../dashboard.php">home</a>
                    <a href="../pets_directory/pets.php">pets</a>
                    <a href="#" class="active">profile</a>
                    <a href="../login/index.php" class="logout-btn">login</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <h1 class="page-title">Paw Profile</h1>

            <!-- HERO SECTION -->
            <section class="hero-block">
                <!-- Cover Photo (Uses user's image or a default purple box) -->
                <div class="cover-photo" style="background-image: url('<?php echo $user['profile_image'] ?? ''; ?>');">
                    <?php if(empty($user['profile_image'])): ?>
                        <i class="fas fa-image placeholder-icon"></i>
                    <?php endif; ?>
                </div>

                <div class="hero-id-area">
                    <div class="avatar-border">
                        <div class="avatar-img" style="background-image: url('<?php echo $user['profile_image'] ?? ''; ?>');">
                             <?php if(empty($user['profile_image'])): ?>
                                <i class="fas fa-image"></i>
                             <?php endif; ?>
                        </div>
                    </div>
                    <div class="name-controls">
                        <h2><?php echo htmlspecialchars($user['first_name']); ?></h2>
                        <div class="hero-pills">
                            <div class="pill"><i class="fas fa-heart"></i> <?php echo ucfirst($user['role']); ?></div>
                            <!-- Inside user_profile/index.php -->
                            <a href="edit_profile.php" class="pill edit-btn">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            </section>

           <!-- TAB SWITCHER -->
<div class="tab-switcher">
    <button class="tab-link active" onclick="openTab(event, 'all-about-me')">All About Me</button>
    <button class="tab-link" onclick="openTab(event, 'my-favorites')">My Favorite Pets</button>
</div>

<!-- TAB 1: ALL ABOUT ME (Your existing view card) -->
<!-- VIEW MODE: ALL ABOUT ME -->
            <div id="view-content" class="data-card">
                <div class="data-row"><label>Full Name</label> <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong></div>
                
                <div class="data-row">
                    <label><?php echo ($user['role'] == 'student') ? 'Student ID No.' : 'Employee ID'; ?></label> 
                    <strong><?php echo htmlspecialchars($user['student_number'] ?? $user['id_number'] ?? 'N/A'); ?></strong>
                </div>

                <?php if($user['role'] == 'student'): ?>
                    <div class="data-row"><label>Program</label> <strong><?php echo htmlspecialchars($user['program'] ?? 'N/A'); ?></strong></div>
                    <div class="data-row"><label>Year Level</label> <strong><?php echo htmlspecialchars($user['year_level'] ?? 'N/A'); ?></strong></div>
                <?php else: ?>
                    <div class="data-row"><label>Office</label> <strong><?php echo htmlspecialchars($user['office'] ?? 'N/A'); ?></strong></div>
                <?php endif; ?>

                <div class="data-row"><label>Birthday</label> <strong>March 22, 2026</strong></div> <!-- Sample date from Figma -->
                <div class="data-row"><label>Contact No.</label> <strong><?php echo htmlspecialchars($user['contact_number'] ?? 'N/A'); ?></strong></div>
                <div class="data-row"><label>Email</label> <strong><?php echo htmlspecialchars($user['email']); ?></strong></div>
                
                <div class="data-row no-border"><label>Affiliations</label></div>
                <div class="affiliations-display">
                    <strong><?php echo htmlspecialchars($user['affiliation'] ?? 'No affiliations listed.'); ?></strong>
                </div>
            </div>

<!-- TAB 2: MY FAVORITE PETS -->
<div id="my-favorites" class="tab-content">
    <div class="pets-grid">
        <?php if (empty($favorite_pets)): ?>
            <p class="no-favs">You haven't favorited any pets yet!</p>
        <?php else: ?>
            <?php foreach ($favorite_pets as $pet): ?>
                <div class="pet-card-mini">
                    <div class="pet-img-box">
                        <img src="<?php echo $pet['profile_img']; ?>" alt="Pet">
                        <div class="heart-badge"><i class="fas fa-heart"></i></div>
                    </div>
                    <div class="pet-info-box">
                        <h3><?php echo htmlspecialchars($pet['name']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($pet['likes'], 0, 80)) . '...'; ?></p>
                        <!-- Links to the admin pet profile folder as per your structure -->
                        <a href="../admin/pet_profile/profile.php?id=<?php echo $pet['id']; ?>" class="see-more-mini">See More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

            

            <!-- EDIT MODE: FORM (Hidden by Default) -->
            <form id="edit-content" class="data-card" style="display:none;" method="POST" action="update_user.php">
                <div class="data-row"><i class="fas fa-pencil-alt edit-label-icon"></i><label>Full Name</label> <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-label-icon"></i><label>ID No.</label> <input type="text" name="id_no" value="<?php echo htmlspecialchars($user['student_number'] ?? ''); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-label-icon"></i><label>Program</label> <input type="text" name="program" value="<?php echo htmlspecialchars($user['program'] ?? ''); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-label-icon"></i><label>Year Level</label> <input type="text" name="year" value="<?php echo htmlspecialchars($user['year_level'] ?? ''); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-label-icon"></i><label>Birthday</label> <input type="text" name="bday" value="March 22, 2026"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-label-icon"></i><label>Contact No.</label> <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-label-icon"></i><label>Email</label> <input type="text" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"></div>
                
                <div class="data-row no-border"><i class="fas fa-pencil-alt edit-label-icon"></i><label>Affiliations</label></div>
                <textarea name="affiliations" class="edit-textarea"><?php echo htmlspecialchars($user['affiliation'] ?? ''); ?></textarea>

                <div class="edit-actions">
                    <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn-save">Save</button>
                </div>
            </form>

        </main>
        <footer class="profile-footer">www.campustails.com</footer>
    </div>

    <script>
        const editTrigger = document.getElementById('editTrigger');
        const cancelBtn = document.getElementById('cancelBtn');
        const viewContent = document.getElementById('view-content');
        const editContent = document.getElementById('edit-content');

        editTrigger.onclick = () => {
            viewContent.style.display = 'none';
            editContent.style.display = 'block';
            editTrigger.parentElement.style.visibility = 'hidden'; // Hide the pills row while editing
        };

        cancelBtn.onclick = () => {
            viewContent.style.display = 'block';
            editContent.style.display = 'none';
            editTrigger.parentElement.style.visibility = 'visible';
        };

        function openTab(evt, tabName) {
    // Hide all tab contents
    const tabContents = document.getElementsByClassName("tab-content");
    for (let i = 0; i < tabContents.length; i++) {
        tabContents[i].classList.remove("active");
    }

    // Deactivate all tab links
    const tabLinks = document.getElementsByClassName("tab-link");
    for (let i = 0; i < tabLinks.length; i++) {
        tabLinks[i].classList.remove("active");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}

    </script>
</body>
</html>
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

// Swapped out broken local PDO SQL query for your Supabase API engine
$user_endpoint = "paw_users?user_id=eq." . urlencode($user_id) . "&select=*";
$user_results = supabase_query($user_endpoint);

if (is_array($user_results) && isset($user_results[0])) {
    $user = $user_results[0];
} else {
    die("User profile not found in Supabase database.");
}

// Fetch Favorite Pets for this user
$fav_endpoint = "favorites?user_id=eq." . urlencode($user_id) . "&select=*,pets(*)";
$fav_results = supabase_query($fav_endpoint);

// Extract the pet data from the payload
$favorite_pets = [];
if (is_array($fav_results)) {
    foreach ($fav_results as $row) {
        if (!empty($row['pets'])) {
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
    <title>CampusTails | <?php echo htmlspecialchars($user['first_name'] ?? 'User'); ?>'s Profile</title>
    <link rel="stylesheet" href="user_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Irish+Grover&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="profile-view-body">

    <div class="profile-master-wrapper">
        <header>
            <div class="nav-container">
                <div class="logo"><img src="../resources/Logo.png" alt="CampusTails"></div>
                <nav class="main-nav">
                    <a href="../dashboard.php">home</a>
                    <a href="../pets_directory/pets.php">pets</a>
                    <a href="#" class="active">profile</a>
                    <a href="../login/index.php" class="logout-btn">logout</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <h1 class="page-title profile-heading-name">Paw Profile</h1>

            <section class="hero-block">
                <div class="cover-photo" style="background-image: url('<?php echo htmlspecialchars($user['cover_image'] ?? ''); ?>'); background-color: #9396E6;">
                    <?php if(empty($user['cover_image'])): ?>
                        <i class="fas fa-image placeholder-icon"></i>
                    <?php endif; ?>
                </div>

                <div class="hero-id-area">
                    <div class="avatar-border">
                        <div class="avatar-img" style="background-image: url('<?php echo htmlspecialchars($user['profile_image'] ?? '../resources/avatar-placeholder.png'); ?>'); background-size: cover; background-position: center;">
                        </div>
                    </div>
                    <div class="name-controls">
                        <h2 class="profile-user-display-name"><?php echo htmlspecialchars($user['first_name'] ?? 'Paw'); ?></h2>
                        <div class="hero-pills">
                            <div class="pill"><i class="fas fa-heart"></i> <?php echo htmlspecialchars(ucfirst($user['role'] ?? 'Member')); ?></div>
                            <button type="button" id="editTrigger" class="pill edit-btn" style="border:none; cursor:pointer;">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <div class="tab-switcher">
                <button class="tab-link active" onclick="openTab(event, 'all-about-me')">All About Me</button>
                <button class="tab-link" onclick="openTab(event, 'my-favorites')">My Favorite Pets</button>
            </div>

            <div id="all-about-me" class="tab-content active">
                <div id="view-content" class="data-card">
                    <div class="data-row">
                        <label>Full Name</label> 
                        <strong><?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?></strong>
                    </div>
                    
                    <div class="data-row">
                        <label>Account Role</label> 
                        <strong><?php echo htmlspecialchars(ucfirst($user['role'] ?? 'Student')); ?></strong>
                    </div>

                    <div class="data-row">
                        <label>Contact No.</label> 
                        <strong><?php echo htmlspecialchars($user['contact_number'] ?? 'N/A'); ?></strong>
                    </div>
                    
                    <div class="data-row"><label>Email</label> <strong><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></strong></div>
                    
                    <div class="data-row no-border"><label>Affiliations</label></div>
                    <div class="affiliations-display">
                        <strong><?php echo htmlspecialchars($user['affiliation'] ?? 'No affiliations listed.'); ?></strong>
                    </div>
                </div>
            </div>

            <div id="my-favorites" class="tab-content">
                <div class="pets-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; padding: 20px 0;">
                    <?php if (empty($favorite_pets)): ?>
                        <p class="no-favs" style="grid-column: 1/-1; text-align: center; color: #888;">You haven't favorited any pets yet!</p>
                    <?php else: ?>
                        <?php foreach ($favorite_pets as $pet): ?>
                            <div class="pet-card-mini" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                                <div class="pet-img-box" style="position: relative; height: 150px; background: #eee;">
                                    <img src="<?php echo htmlspecialchars($pet['image_url'] ?? '../resources/pet-placeholder.png'); ?>" alt="Pet" style="width: 100%; height: 100%; object-fit: cover;">
                                    <div class="heart-badge" style="position: absolute; top: 10px; right: 10px; color: #FF8BA7;"><i class="fas fa-heart"></i></div>
                                </div>
                                <div class="pet-info-box" style="padding: 15px;">
                                    <h3 style="margin: 0 0 5px; font-size: 1.1rem; color: #333;"><?php echo htmlspecialchars($pet['name'] ?? 'Unnamed'); ?></h3>
                                    <p style="font-size: 0.85rem; color: #666; margin: 0 0 10px;">Status: <?php echo htmlspecialchars($pet['health_status'] ?? 'Healthy'); ?></p>
                                    <a href="../admin/pet_profile/profile.php?id=<?php echo htmlspecialchars($pet['pet_id'] ?? ''); ?>" class="see-more-mini" style="color: #9396E6; font-weight: 600; text-decoration: none; font-size: 0.85rem;">See More</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <form id="edit-content" class="data-card" style="display:none;" method="POST" action="update_user.php">
                <div class="data-row"><i class="fas fa-pencil-alt edit-label-icon"></i><label>First Name</label> <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-label-icon"></i><label>Last Name</label> <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-label-icon"></i><label>Contact No.</label> <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-label-icon"></i><label>Email</label> <input type="text" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"></div>
                
                <div class="data-row no-border"><i class="fas fa-pencil-alt edit-label-icon"></i><label>Affiliations</label></div>
                <textarea name="affiliations" class="edit-textarea" style="width: 100%; min-height: 80px; border-radius: 8px; border: 1px solid #ddd; padding: 10px; font-family: inherit;"><?php echo htmlspecialchars($user['affiliation'] ?? ''); ?></textarea>

                <div class="edit-actions" style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn-cancel" id="cancelBtn" style="padding: 8px 20px; border-radius: 20px; border: 1px solid #ccc; background: white; cursor: pointer;">Cancel</button>
                    <button type="submit" class="btn-save" style="padding: 8px 20px; border-radius: 20px; border: none; background: #9396E6; color: white; cursor: pointer; font-weight: 600;">Save</button>
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

        if(editTrigger) {
            editTrigger.onclick = () => {
                viewContent.style.display = 'none';
                editContent.style.display = 'block';
                editTrigger.style.visibility = 'hidden'; 
            };
        }

        if(cancelBtn) {
            cancelBtn.onclick = () => {
                viewContent.style.display = 'block';
                editContent.style.display = 'none';
                editTrigger.style.visibility = 'visible';
            };
        }

        function openTab(evt, tabName) {
            const tabContents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove("active");
                if(tabContents[i].id !== 'all-about-me') {
                     tabContents[i].style.display = "none";
                }
            }

            const tabLinks = document.getElementsByClassName("tab-link");
            for (let i = 0; i < tabLinks.length; i++) {
                tabLinks[i].classList.remove("active");
            }

            const targets = document.getElementById(tabName);
            if(tabName === 'all-about-me') {
                document.getElementById('all-about-me').classList.add("active");
                viewContent.style.display = 'block';
                editContent.style.display = 'none';
                if(editTrigger) editTrigger.style.visibility = 'visible';
            } else {
                targets.style.display = "block";
                targets.classList.add("active");
            }
            evt.currentTarget.classList.add("active");
        }
    </script>
</body>
</html>
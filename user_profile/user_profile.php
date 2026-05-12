<?php
session_start();
require_once '../../db_connection.php'; // Your Supabase PDO connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch User Data with Student or Faculty details
try {
    $query = "SELECT u.*, s.student_number, s.program, s.year_level, f.office, f.institutional_email 
              FROM users u 
              LEFT JOIN student_profiles s ON u.user_id = s.user_id 
              LEFT JOIN faculty_profiles f ON u.user_id = f.user_id 
              WHERE u.user_id = :uid";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([':uid' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) die("User not found.");

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | Profile</title>
    <link rel="stylesheet" href="user_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="profile-master">
        <header>
            <div class="nav-container">
                <div class="logo"><img src="../../resources/Logo.png" alt="CampusTails"></div>
                <nav class="main-nav">
                    <a href="../dashboard.php">home</a>
                    <a href="../pets_directory/pets.php">pets</a>
                    <a href="#" class="active">profile</a>
                    <a href="../login/login.php">login</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <h1 class="page-title">Paw Profile</h1>

            <!-- HERO SECTION -->
            <section class="hero-block">
                <!-- Cover Photo Placeholder -->
                <div class="cover-photo">
                    <i class="fas fa-image cover-placeholder-icon"></i>
                </div>
                
                <div class="hero-id-area">
                    <div class="avatar-border">
                        <!-- Profile Image Placeholder -->
                        <div class="avatar-img">
                             <i class="fas fa-image"></i>
                        </div>
                    </div>
                    <div class="name-header">
                        <h2 class="user-display-name"><?php echo htmlspecialchars($user['first_name']); ?></h2>
                        <div class="hero-pills">
                            <div class="pill role-pill">
                                <i class="fas fa-heart"></i> <?php echo ucfirst($user['role']); ?>
                            </div>
                            <button class="pill edit-trigger" id="editBtn">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <div class="tab-switcher">
                <button class="tab-btn active" id="tabMe">All About Me</button>
                <button class="tab-btn" id="tabPets">My Favorite Pets</button>
            </div>

            <!-- VIEW MODE CARD -->
            <div id="viewMode" class="profile-card">
                <div class="data-row">
                    <label>Full Name</label>
                    <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong>
                </div>
                <div class="data-row">
                    <label><?php echo ($user['role'] == 'student') ? 'Student ID No.' : 'Employee ID'; ?></label>
                    <strong><?php echo htmlspecialchars($user['student_number'] ?? $user['id_number'] ?? 'N/A'); ?></strong>
                </div>
                <?php if($user['role'] == 'student'): ?>
                <div class="data-row">
                    <label>Program</label>
                    <strong><?php echo htmlspecialchars($user['program'] ?? 'N/A'); ?></strong>
                </div>
                <div class="data-row">
                    <label>Year Level</label>
                    <strong><?php echo htmlspecialchars($user['year_level'] ?? 'N/A'); ?></strong>
                </div>
                <?php else: ?>
                <div class="data-row">
                    <label>Office</label>
                    <strong><?php echo htmlspecialchars($user['office'] ?? 'N/A'); ?></strong>
                </div>
                <?php endif; ?>
                <div class="data-row">
                    <label>Birthday</label>
                    <strong>March 22, 2026</strong> <!-- Placeholder like screenshot -->
                </div>
                <div class="data-row">
                    <label>Contact No.</label>
                    <strong><?php echo htmlspecialchars($user['contact_number'] ?? 'N/A'); ?></strong>
                </div>
                <div class="data-row">
                    <label>Email</label>
                    <strong><?php echo htmlspecialchars($user['email']); ?></strong>
                </div>
                <div class="data-row no-border">
                    <label>Affiliations</label>
                </div>
                <div class="affiliations-text">
                    <?php echo htmlspecialchars($user['affiliation'] ?? 'No affiliations added.'); ?>
                </div>
            </div>

            <!-- EDIT MODE CARD (Hidden by default) -->
            <form id="editMode" class="profile-card" style="display:none;" method="POST" action="update_profile.php">
                <div class="data-row"><i class="fas fa-pencil-alt edit-icon"></i> <label>Full Name</label> <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-icon"></i> <label>ID No.</label> <input type="text" name="id_no" value="<?php echo htmlspecialchars($user['student_number'] ?? ''); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-icon"></i> <label>Program</label> <input type="text" name="program" value="<?php echo htmlspecialchars($user['program'] ?? ''); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-icon"></i> <label>Year Level</label> <input type="text" name="year" value="<?php echo htmlspecialchars($user['year_level'] ?? ''); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-icon"></i> <label>Birthday</label> <input type="text" name="bday" value="March 22, 2026"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-icon"></i> <label>Contact No.</label> <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>"></div>
                <div class="data-row"><i class="fas fa-pencil-alt edit-icon"></i> <label>Email</label> <input type="text" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"></div>
                <div class="data-row no-border"><i class="fas fa-pencil-alt edit-icon"></i> <label>Affiliations</label></div>
                <textarea name="affiliation" class="edit-area"><?php echo htmlspecialchars($user['affiliation'] ?? ''); ?></textarea>
                
                <div class="edit-actions">
                    <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn-save">Save</button>
                </div>
            </form>

        </main>

        <footer class="profile-footer">
            www.campustails.com
        </footer>
    </div>

    <script>
        const editBtn = document.getElementById('editBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const viewMode = document.getElementById('viewMode');
        const editMode = document.getElementById('editMode');

        editBtn.onclick = () => {
            viewMode.style.display = 'none';
            editMode.style.display = 'block';
            editBtn.style.display = 'none';
        };

        cancelBtn.onclick = () => {
            viewMode.style.display = 'block';
            editMode.style.display = 'none';
            editBtn.style.display = 'flex';
        };
    </script>
</body>
</html>
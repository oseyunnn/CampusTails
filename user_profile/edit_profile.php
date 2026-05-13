<?php
session_start();
include('../utils/db_config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $query = "SELECT u.*, s.student_number, s.program, s.year_level, f.office 
              FROM users u 
              LEFT JOIN student_profiles s ON u.user_id = s.user_id 
              LEFT JOIN faculty_profiles f ON u.user_id = f.user_id 
              WHERE u.user_id = :uid";
    $stmt = $conn->prepare($query);
    $stmt->execute([':uid' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile | CampusTails</title>
    <link rel="stylesheet" href="user_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="edit-profile-page">

    <div class="profile-master-wrapper">
        <header>
            <div class="nav-container">
                <div class="logo"><img src="../resources/Logo.png" alt="Logo"></div>
                <nav class="main-nav">
                    <a href="../dashboard.php">home</a>
                    <a href="../pets_directory/pets.php">pets</a>
                    <a href="index.php" class="active">profile</a>
                    <a href="../login/index.php">login</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <h1 class="page-title">Paw Profile</h1>

            <!-- Hero (Matches View Screen) -->
            <section class="hero-block">
                <div class="cover-photo" style="background-image: url('<?php echo $user['profile_image'] ?? ''; ?>');">
                    <?php if(empty($user['profile_image'])): ?><i class="fas fa-image placeholder-icon"></i><?php endif; ?>
                </div>
                <div class="hero-id-area">
                    <div class="avatar-border">
                        <div class="avatar-img" style="background-image: url('<?php echo $user['profile_image'] ?? ''; ?>');">
                             <?php if(empty($user['profile_image'])): ?><i class="fas fa-image"></i><?php endif; ?>
                        </div>
                    </div>
                    <div class="name-controls">
                        <h2><?php echo htmlspecialchars($user['first_name']); ?></h2>
                        <div class="hero-pills">
                            <div class="pill"><i class="fas fa-heart"></i> <?php echo ucfirst($user['role']); ?></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Full Width "All About Me" bar -->
            <div class="section-bar">All About Me</div>

            <!-- EDIT FORM -->
            <form action="update_profile_action.php" method="POST">
                <div class="data-card edit-card">
                    <div class="data-row">
                        <div class="row-label"><i class="fas fa-pencil-alt"></i> Full Name</div>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>" required>
                    </div>

                    <div class="data-row">
                        <div class="row-label"><i class="fas fa-pencil-alt"></i> <?php echo ($user['role'] == 'student') ? 'Student ID No.' : 'Employee ID'; ?></div>
                        <input type="text" name="id_number" value="<?php echo htmlspecialchars($user['student_number'] ?? $user['id_number'] ?? ''); ?>" required>
                    </div>

                    <?php if($user['role'] == 'student'): ?>
                        <div class="data-row"><div class="row-label"><i class="fas fa-pencil-alt"></i> Program</div> 
                            <input type="text" name="program" value="<?php echo htmlspecialchars($user['program'] ?? ''); ?>"></div>
                        <div class="data-row"><div class="row-label"><i class="fas fa-pencil-alt"></i> Year Level</div> 
                            <input type="text" name="year" value="<?php echo htmlspecialchars($user['year_level'] ?? ''); ?>"></div>
                    <?php else: ?>
                        <div class="data-row"><div class="row-label"><i class="fas fa-pencil-alt"></i> Office</div> 
                            <input type="text" name="office" value="<?php echo htmlspecialchars($user['office'] ?? ''); ?>"></div>
                    <?php endif; ?>

                    <div class="data-row"><div class="row-label"><i class="fas fa-pencil-alt"></i> Birthday</div> 
                        <input type="text" name="bday" value="March 22, 2026"></div>
                    <div class="data-row"><div class="row-label"><i class="fas fa-pencil-alt"></i> Contact No.</div> 
                        <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>"></div>
                    <div class="data-row"><div class="row-label"><i class="fas fa-pencil-alt"></i> Email</div> 
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"></div>
                    
                    <div class="data-row no-border"><div class="row-label"><i class="fas fa-pencil-alt"></i> Affiliations</div></div>
                    <textarea name="affiliations" class="edit-textarea"><?php echo htmlspecialchars($user['affiliation'] ?? ''); ?></textarea>
                </div>

                <!-- Footer Buttons -->
                <div class="edit-footer-btns">
                    <a href="index.php" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-save">Save</button>
                </div>
            </form>
        </main>
        <footer class="profile-footer">www.campustails.com</footer>
    </div>
</body>
</html>
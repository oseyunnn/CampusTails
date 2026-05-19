<?php
session_start();
include('../utils/db_config.php');

// Simple Pagination Logic
$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch pets from Supabase with limit/offset for pagination
$endpoint = "pets?select=*&order=created_at.desc&limit=$limit&offset=$offset";
$pets = supabase_query($endpoint);

// Ideally, you'd fetch the total count to calculate "1 out of 3"
// For this prototype, we'll assume a count or fetch it separately
$total_count_res = supabase_query("pets?select=count", "GET", null);
$total_pets = 24; // Placeholder or parsed from Range header
$total_pages = ceil($total_pets / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CampusTails | Meet Our Pets</title>
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
                    <a href="dashboard.php">home</a>
                    <a href="#" class="active">pets</a>
                    <a href="profile/index.php">profile</a>
                    <a href="../login/index.php">logout</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <div class="directory-header">
                <p>Meet our</p>
                <h1>Campus Tails</h1>
            </div>

            <div class="pets-grid">
                <?php foreach($pets as $pet): ?>
                <div class="pet-card">
                    <div class="pet-img-side">
                        <img src="<?php echo $pet['image_url'] ?? '../resources/man-dog.png'; ?>" alt="Pet">
                    </div>
                    <div class="pet-info-side">
                        <h3><?php echo htmlspecialchars($pet['pet_name']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($pet['description'], 0, 85)) . '...'; ?></p>
                        <a href="user_pet_profile.php?id=<?php echo $pet['pet_id']; ?>" class="see-more-btn">See More</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <a href="?page=<?php echo max(1, $page-1); ?>" class="page-link">Prev</a>
                <span class="page-info"><?php echo $page; ?> out of <?php echo $total_pages; ?></span>
                <a href="?page=<?php echo min($total_pages, $page+1); ?>" class="page-link">Next</a>
            </div>
        </main>
        <footer>www.campustails.com</footer>
    </div>
</body>
</html>
<?php
session_start();
include('../utils/db_config.php');

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: ../login/index.php");
    exit();
}

$first_name = $_SESSION['first_name'] ?? 'User';

// 1. Fetch ALL Favorites to get total count and pool for randomization
$fav_endpoint = "favorites?user_id=eq.$user_id&select=pets(*)";
$fav_results = supabase_query($fav_endpoint);

// Initialize variables
$total_fav_count = 0;
$display_pets = [];

if (is_array($fav_results) && !empty($fav_results)) {
    // The actual total count for the stat card
    $total_fav_count = count($fav_results);

    // Shuffle the entire array to make the selection random
    shuffle($fav_results);

    // Pick only the first 4 items from the shuffled list
    $random_selection = array_slice($fav_results, 0, 4);

    // Extract the pet data from the joined result
    foreach ($random_selection as $row) {
        if (isset($row['pets'])) {
            $display_pets[] = $row['pets'];
        }
    }
}

// 2. Fetch Adopted Pets Count
$adopted_results = supabase_query("pets?is_adopted=eq.true&user_id=eq.$user_id");
$adopted_count = is_array($adopted_results) ? count($adopted_results) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | User Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="dashboard-wrapper">
        <!-- HEADER (Same as before) -->
        <header>
            <div class="nav-container">
                <div class="logo"><img src="../resources/Logo.png" alt="CampusTails"></div>
                <nav class="main-nav">
                    <a href="#" class="active">home</a>
                    <a href="../pets_directory/pets.php">pets</a>
                    <a href="../user_profile/index.php">profile</a>
                    <a href="../login/index.php" class="logout-btn">logout</a>
                </nav>
            </div>
        </header>

        <!-- WELCOME & STATS -->
        <main class="user-hero">
            <div class="welcome-text">
                <h1>Welcome, <?php echo htmlspecialchars($first_name); ?>!</h1>
                <p>Have a pawmazing day today!</p>
            </div>

            <div class="user-stats-grid">
                <div class="stat-card bg-blue">
                    <span class="stat-number"><?php echo $adopted_count; ?></span>
                    <span class="stat-label">Pets<br>Adopted</span>
                </div>
                <div class="stat-card bg-lavender">
                    <!-- The counter shows the ACTUAL total (e.g. 10) -->
                    <span class="stat-number"><?php echo $total_fav_count; ?></span>
                    <span class="stat-label">Favorite<br>Pets</span>
                </div>
            </div>
        </main>

        <!-- RANDOM FAVORITES SPOTLIGHT -->
        <section class="favorites-section">
            <div class="section-header">
                <h2>Your Favorites</h2>
                <p>Today's Random Spotlight</p>
            </div>

            <div class="favorites-grid">
                <?php if (!empty($display_pets)): ?>
                    <?php foreach ($display_pets as $pet): ?>
                        <div class="pet-mini-card">
                            <div class="pet-img-container">
                                <img src="<?php echo $pet['profile_image'] ?? '../resources/default-pet.png'; ?>" alt="Pet">
                                <div class="heart-icon"><i class="fas fa-heart"></i></div>
                            </div>
                            <div class="pet-details">
                                <h3><?php echo htmlspecialchars($pet['pet_name']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($pet['description'], 0, 100)) . '...'; ?></p>
                                <a href="../pet_profile/profile.php?id=<?php echo $pet['pet_id']; ?>" class="see-more-btn">See More</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-msg">You haven't added any favorites yet!</p>
                <?php endif; ?>
            </div>
        </section>

        <footer>www.campustails.com</footer>
    </div>

</body>
</html>
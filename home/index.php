<?php
include('../utils/db_config.php');

// 1. Fetch Stats & Randomized Pets (6 max)
$all_pets_raw = supabase_query("pets");
if (!is_array($all_pets_raw)) { $all_pets_raw = []; }

$total_pets = count($all_pets_raw);

$display_pets = $all_pets_raw;
shuffle($display_pets); 
$display_pets = array_slice($display_pets, 0, 6);

// Stats Logic
$adopted_count = 0;
foreach ($all_pets_raw as $p) {
    if (isset($p['is_adopted']) && $p['is_adopted'] == true) $adopted_count++;
}

$vaccine_data = supabase_query("vaccine_records?select=pet_id");
$vaccinated_count = 0;
if (is_array($vaccine_data) && !empty($vaccine_data)) {
    $vaccinated_count = count(array_unique(array_column($vaccine_data, 'pet_id')));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | Be a Hero</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header class="landing-header">
        <div class="nav-container">
            <div class="logo">
                <img src="../resources/logo-white.png" alt="CampusTails">
            </div>
            <nav class="main-nav">
                <a href="#" class="active">home</a>
                <a href="#">about</a>
                <a href="../guest/pets.php">pets</a>
                <a href="../login/index.php">login</a>
            </nav>
        </div>
    </header>

    <main>
        <!-- HERO SECTION -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Be a Hero,<br>Help a Paw</h1>
                <a href="../register/index.php" class="hero-btn">Sign Up</a>
            </div>
            <div class="hero-cats-container">
                <img src="../resources/cats_home.png" class="main-cats" alt="cats">
            </div>
        </section>

        <!-- STATS PILLS -->
        <section class="stats-row">
            <div class="stat-pill bg-blue">
                <h2 class="stat-num"><?php echo $total_pets; ?></h2>
                <span class="stat-label">Registered Pets</span>
            </div>
            <div class="stat-pill bg-lavender">
                <h2 class="stat-num"><?php echo $vaccinated_count; ?></h2>
                <span class="stat-label">Fully Vaccinated</span>
            </div>
            <div class="stat-pill bg-blue">
                <h2 class="stat-num"><?php echo $adopted_count; ?></h2>
                <span class="stat-label">New Fur Parents</span>
            </div>
        </section>

        <div class="breathable-white-space"></div>

        <div class="paw-center-banner">
            <h2>PawCenterbase</h2>
            <p>Join CampusTails and get real-time updates on campus pets</p>
        </div>

        <!-- CAMPUS PETS -->
        <section class="section-container">
            <p class="section-sub">Meet some of your</p>
            <h2 class="section-main">Campus Pets</h2>
            <div class="pets-grid-landing">
                <?php foreach($display_pets as $pet): ?>
                <div class="pet-pill-card">
                    <div class="card-inner">
                        <div class="pet-img-squircle" style="background-image: url('<?php echo $pet['profile_img'] ?: '../resources/placeholder.png'; ?>');"></div>
                        <div class="pet-info">
                            <h2 class="pet-name"><?php echo $pet['name']; ?></h2>
                            <p class="pet-desc">
                                <strong><?php echo $pet['name']; ?></strong> likes <?php echo strtolower($pet['likes']); ?> 
                                and can be usually found at <strong><?php echo $pet['location']; ?></strong>.
                            </p>
                            <a href="../pet_profile/profile.php?id=<?php echo $pet['id']; ?>" class="see-more-pill">See More</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- COMMUNITY WALL -->
        <section class="community-wall">
            <p class="section-sub">Take a peek at our</p>
            <h2 class="section-main">Community Wall</h2>
            <div class="wall-grid">
                <div class="wall-post">
                    <h4>OUR SHARED SPACE</h4>
                    <p>It's heart-warming to see our campus becoming a second home for these stray paws. Small acts of kindness truly make a huge difference!</p>
                </div>
                <div class="wall-post">
                    <h4>OUR SHARED SPACE</h4>
                    <p>I love how everyone comes together to feed and care for the pets. CampusTails has really helped us organize our efforts better.</p>
                </div>
                <div class="wall-post">
                    <h4>OUR SHARED SPACE</h4>
                    <p>Seeing the cats lounging by the fountain always brightens my stress morning. I'm so glad we have a community that protects them.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="uniform-footer">
        <div class="footer-wrap">
            <div class="footer-img-container">
                <img src="../resources/footer.png" alt="crew" class="footer-crew">
            </div>
            <div class="footer-text-side">
                <h2>Want to be a<br>Campus Crew?</h2>
                <p>Email us at campustails@gmail.com</p>
                <div class="social-icons">
                    <i class="fab fa-facebook"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-tiktok"></i>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
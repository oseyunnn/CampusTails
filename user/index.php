<?php
session_start();
include('../utils/db_config.php');

// 1. Session Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Query Base Account Info
$user_data = supabase_query("paw_users?user_id=eq." . urlencode($user_id) . "&select=*");
$current_user = (is_array($user_data) && isset($user_data[0])) ? $user_data[0] : null;

// 3. Query Linked Favorite Relationships
$favorites_endpoint = "favorites?user_id=eq." . urlencode($user_id) . "&select=*,pets(*)";
$raw_favorites = supabase_query($favorites_endpoint);

$favorite_pets = [];
if (is_array($raw_favorites)) {
    foreach ($raw_favorites as $fav) {
        if (!empty($fav['pets'])) {
            $favorite_pets[] = $fav['pets'];
        }
    }
}

// 4. Randomization & Absolute Slicing Bounds
$total_favorites_count = count($favorite_pets); // Metric remains true to full database entries

if (!empty($favorite_pets)) {
    shuffle($favorite_pets); // Randomize positions dynamically on reload
}
$displayed_favorites = array_slice($favorite_pets, 0, 4); // Limit gallery to a maximum of 4 records
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | User Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="dash-master">
        <header class="dash-header">
            <div class="nav-container">
                <div class="logo-side">
                    <img src="../resources/Logo.png" alt="CampusTails Logo">
                </div>
                <nav class="nav-links">
                    <a href="#" class="active">dashboard</a>
                    <a href="#">pets</a>
                    <a href="../login/login.php"><i class="fas fa-sign-out-alt"></i> logout</a>
                </nav>
            </div>
        </header>

        <main class="dash-content">
            
            <section class="left-panel">
                <div class="profile-card">
                    <div class="avatar-holder">
                        <img src="../resources/avatar-placeholder.png" alt="Profile Avatar" class="avatar-img">
                    </div>
                    <div class="profile-details">
                        <h2>
                            <?php 
                            echo htmlspecialchars(($current_user['first_name'] ?? 'Paw') . ' ' . ($current_user['last_name'] ?? 'Crew')); 
                            ?>
                        </h2>
                        <span class="user-role-badge"><?php echo htmlspecialchars(ucfirst($current_user['role'] ?? 'User')); ?></span>
                        
                        <p class="meta-info"><i class="far fa-envelope"></i> <?php echo htmlspecialchars($current_user['email'] ?? 'N/A'); ?></p>
                        <p class="meta-info"><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($current_user['contact_number'] ?? 'N/A'); ?></p>
                        
                        <?php if (!empty($current_user['affiliation'])): ?>
                            <div class="user-affiliation-segment">
                                <strong>Affiliation:</strong>
                                <p><?php echo htmlspecialchars($current_user['affiliation']); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="right-panel">
                
                <div class="stats-strip">
                    <div class="stat-card fav-metric-theme">
                        <div class="stat-info">
                            <h3><?php echo $total_favorites_count; ?></h3>
                            <p>My Favorite Pets</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                    </div>
                </div>

                <div class="panel-headline">
                    <h3>My Favorites Gallery <span class="rotation-notice">(Randomized Rotation)</span></h3>
                </div>

                <div class="tools-grid">
                    <?php if (!empty($displayed_favorites)): ?>
                        <?php foreach ($displayed_favorites as $pet): ?>
                            <div class="tool-card pet-card-item">
                                <div class="pet-img-frame">
                                    <?php if (!empty($pet['image_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($pet['image_url']); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>">
                                    <?php else: ?>
                                        <img src="../resources/pet-placeholder.png" alt="Pet Profile Picture">
                                    <?php endif; ?>
                                    
                                    <span class="status-badge <?php echo isset($pet['is_adopted']) && $pet['is_adopted'] ? 'badge-adopted' : 'badge-monitoring'; ?>">
                                        <?php echo isset($pet['is_adopted']) && $pet['is_adopted'] ? 'Adopted' : 'Monitoring'; ?>
                                    </span>
                                </div>
                                <div class="pet-card-info">
                                    <div class="pet-card-header">
                                        <h4><?php echo htmlspecialchars($pet['name'] ?? 'Unnamed Pet'); ?></h4>
                                        <span class="species-label"><?php echo htmlspecialchars($pet['species'] ?? 'Unknown'); ?></span>
                                    </div>
                                    <p class="pet-meta-desc"><i class="fas fa-map-marker-alt"></i> Found: <?php echo htmlspecialchars($pet['location_found'] ?? 'Campus Grounds'); ?></p>
                                    <p class="pet-meta-desc"><strong>Condition:</strong> <?php echo htmlspecialchars($pet['health_status'] ?? 'Stable'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-fallback-state">
                            <i class="far fa-heart"></i>
                            <p>You haven't marked any campus pets as favorites yet.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </section>
        </main>
    </div>

</body>
</html>
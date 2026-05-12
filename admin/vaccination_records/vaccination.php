<?php
include('../../utils/db_config.php');

$limit = 8;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$search = $_GET['q'] ?? '';
$species_filter = $_GET['species'] ?? '';
$sort = $_GET['sort'] ?? 'vaccine.latest'; 

// 1. Fetch EVERYTHING
$endpoint = "pets?select=*,vaccine_records(*)&limit=1000";
if (!empty($search)) $endpoint .= "&name=ilike.*" . urlencode($search) . "*";
if (!empty($species_filter)) $endpoint .= "&species=eq." . urlencode($species_filter);

$raw_data = supabase_query($endpoint);
if (!is_array($raw_data)) { $raw_data = []; }

// 2. THE FILTER: ONLY KEEP PETS WITH VACCINE RECORDS
$vaccinated_pets = [];
foreach ($raw_data as $row) {
    // Check if the pet has at least one vaccine record
    if (!empty($row['vaccine_records']) && is_array($row['vaccine_records'])) {
        
        // Deduplicate by ID just in case
        if (!isset($vaccinated_pets[$row['id']])) {
            
            // Sort this pet's vaccines internally so index [0] is the latest
            usort($row['vaccine_records'], function($a, $b) {
                return strtotime($b['date_administered']) - strtotime($a['date_administered']);
            });

            // Calculate sorting timestamp
            $row['latest_vac_ts'] = strtotime($row['vaccine_records'][0]['date_administered']);
            
            $vaccinated_pets[$row['id']] = $row;
        }
    }
}

// Convert map to simple list (This list should now have exactly 11 pets)
$all_pets = array_values($vaccinated_pets); 

// 3. SORT THE 11 PETS
usort($all_pets, function($a, $b) use ($sort) {
    if ($sort === 'vaccine.latest') {
        $res = $b['latest_vac_ts'] - $a['latest_vac_ts'];
    } elseif ($sort === 'vaccine.earliest') {
        $res = $a['latest_vac_ts'] - $b['latest_vac_ts'];
    } elseif ($sort === 'name.asc') {
        $res = strcmp($a['name'], $b['name']);
    } else {
        $res = strcmp($b['name'], $a['name']);
    }
    // Tie-breaker
    return ($res === 0) ? strcmp($a['id'], $b['id']) : $res;
});

// 4. PAGINATION CALCULATIONS
$total_count = count($all_pets); // Should result in 11
$total_pages = max(1, ceil($total_count / $limit));
$display_pets = array_slice($all_pets, $offset, $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | Vaccination Records</title>
    <link rel="stylesheet" href="style.css?v=1.2">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="master-wrapper">
        <header>
            <div class="nav-container">
                <div class="logo"><img src="../../resources/Logo.png" alt="CampusTails"></div>
                <nav class="main-nav">
                    <a href="../dashboard.php">home</a>
                    <a href="../pets_directory/pets.php" class="active">pets</a>
                    <a href="#">users</a>
                    <a href="#" class="logout-btn">logout</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <div class="page-header">
                <p class="sub-label">View Only</p>
                <h1 class="main-title">Vaccination Records</h1>
            </div>

            <section class="tools-bar">
                <form action="vaccination.php" method="GET" class="filter-form">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="q" placeholder="Search Pet Name" value="<?php echo htmlspecialchars($search); ?>">
                    </div>

                    <select name="species" onchange="this.form.submit()" class="custom-select">
                        <option value="">All Species</option>
                        <option value="Cat" <?php echo $species_filter == 'Cat' ? 'selected' : ''; ?>>Cats</option>
                        <option value="Dog" <?php echo $species_filter == 'Dog' ? 'selected' : ''; ?>>Dogs</option>
                    </select>

                    <select name="sort" onchange="this.form.submit()" class="custom-select">
                        <option value="vaccine.latest" <?php echo $sort == 'vaccine.latest' ? 'selected' : ''; ?>>Sort by: Latest Vaccine</option>
                        <option value="vaccine.earliest" <?php echo $sort == 'vaccine.earliest' ? 'selected' : ''; ?>>Sort by: Earliest Vaccine</option>
                        <option value="name.asc" <?php echo $sort == 'name.asc' ? 'selected' : ''; ?>>Sort by: Name (A-Z)</option>
                        <option value="name.desc" <?php echo $sort == 'name.desc' ? 'selected' : ''; ?>>Sort by: Name (Z-A)</option>
                    </select>
                </form>
            </section>

            <div class="records-grid">
                <?php foreach($display_pets as $pet): 
                    $records = $pet['vaccine_records'] ?? [];
                    // Since we sorted $records in Step 3, index [0] is definitely the latest.
                    $latest = !empty($records) ? $records[0] : null;

                    // Find the upcoming one (next_due_date in the future)
                    $next = null;
                    foreach($records as $r) {
                        if(!empty($r['next_due_date']) && strtotime($r['next_due_date']) > time()) {
                            $next = $r;
                            // We don't break here because we want the "soonest" future date
                            // (If multiple are in the future, sorting records beforehand helps)
                        }
                    }
                ?>
                <div class="vaccine-card">
                    <div class="species-tag"><?php echo $pet['species']; ?></div>
                    <div class="card-header">
                        <div class="pet-thumb" style="background-image: url('<?php echo $pet['profile_img']; ?>');"></div>
                        <h2><?php echo htmlspecialchars($pet['name']); ?></h2>
                    </div>

                    <div class="status-rows">
                        <div class="status-row done">
                            <i class="fas fa-check-circle"></i>
                            <span>
                                <?php echo $latest ? htmlspecialchars($latest['vaccine_name'])." - ".date("M d, Y", strtotime($latest['date_administered']))." - ".htmlspecialchars($latest['veterinarian']) : "No records found"; ?>
                            </span>
                        </div>

                        <div class="status-row pending">
                            <i class="fas fa-calendar-alt"></i>
                            <span>
                                <?php echo $next ? htmlspecialchars($next['vaccine_name'])." - ".date("M d, Y", strtotime($next['next_due_date']))." - TBA" : "No upcoming schedule"; ?>
                            </span>
                        </div>
                    </div>
                    <a href="../pet_profile/profile.php?id=<?php echo $pet['id']; ?>" class="view-btn">View Health Records</a>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="pagination">
                <?php $params = "q=".urlencode($search)."&species=".urlencode($species_filter)."&sort=".urlencode($sort); ?>
                <a href="?page=<?php echo max(1, $page-1); ?>&<?php echo $params; ?>" class="pag-link <?php echo $page <= 1 ? 'disabled' : ''; ?>">Prev</a>
                <span class="pag-info"><?php echo $page; ?> out of <?php echo $total_pages; ?></span>
                <a href="?page=<?php echo min($total_pages, $page+1); ?>&<?php echo $params; ?>" class="pag-link <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">Next</a>
            </div>
        </main>
        <footer>www.campustails.com</footer>
    </div>
</body>
</html>
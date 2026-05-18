<?php
include('../utils/db_config.php');

// 1. SETTINGS & PARAMETERS
$limit = 8;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$search = $_GET['q'] ?? '';
$loc_filter = $_GET['location'] ?? '';
$sort = $_GET['sort'] ?? 'name.asc';

// 2. FETCH TOTAL COUNT FOR PAGINATION (Matching filters, ignoring limit)
// We get just the IDs to count how many pets match our search
$count_endpoint = "pets?select=id";
if (!empty($search)) {
    $count_endpoint .= "&name=ilike.*" . urlencode($search) . "*";
}
if (!empty($loc_filter)) {
    $count_endpoint .= "&location=eq." . urlencode($loc_filter);
}

$all_matching = supabase_query($count_endpoint);
$total_count = is_array($all_matching) ? count($all_matching) : 0;
$total_pages = ceil($total_count / $limit);
if($total_pages == 0) $total_pages = 1;

// 3. FETCH ACTUAL PAGE DATA (With Limit and Offset)
$data_endpoint = "pets?select=*";
if (!empty($search)) {
    $data_endpoint .= "&name=ilike.*" . urlencode($search) . "*";
}
if (!empty($loc_filter)) {
    $data_endpoint .= "&location=eq." . urlencode($loc_filter);
}
$data_endpoint .= "&order=" . $sort . "&limit=" . $limit . "&offset=" . $offset;

$pets = supabase_query($data_endpoint);
if (!is_array($pets)) { $pets = []; }

// 4. GET UNIQUE LOCATIONS FOR FILTER DROPDOWN
$loc_data = supabase_query("pets?select=location");
$locations = [];
if(is_array($loc_data)) {
    $locations = array_unique(array_column($loc_data, 'location'));
    sort($locations); // Alphabetical order
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | Pets Directory</title>
    <link rel="stylesheet" href="style.css?v=2.0">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="dashboard-wrapper">
        <!-- HEADER (UNIFORM) -->
        <header>
            <div class="nav-container">
                <div class="logo">
                    <img src="../resources/Logo.png" alt="CampusTails">
                </div>
                <nav class="main-nav">
                    <a href="../home/index.php">home</a>
                    <a href="../guest/aboutpage.php">about</a>
                    <a href="#"class="active">pets</a>
                    <a href="../login/index.php">login</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <div class="meet-our-section">
                <p class="sub-intro">Meet our</p>
                <h1 class="main-intro">Campus Tails</h1>
            </div>

            <!-- SEARCH & FILTER BAR -->
            <section class="tools-bar">
                <form action="pets.php" method="GET" class="filter-form">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="q" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
                    </div>

                    <select name="location" onchange="this.form.submit()">
                        <option value="">All Locations</option>
                        <?php foreach($locations as $loc): ?>
                            <option value="<?php echo $loc; ?>" <?php echo $loc_filter == $loc ? 'selected' : ''; ?>>
                                <?php echo $loc; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="sort" onchange="this.form.submit()">
                        <option value="name.asc" <?php echo $sort == 'name.asc' ? 'selected' : ''; ?>>Sort by: Name (A-Z)</option>
                        <option value="name.desc" <?php echo $sort == 'name.desc' ? 'selected' : ''; ?>>Sort by: Name (Z-A)</option>
                        <option value="created_at.desc" <?php echo $sort == 'created_at.desc' ? 'selected' : ''; ?>>Sort by: Newest</option>
                    </select>
                </form>
            </section>

            <!-- PETS GRID -->
            <div class="pets-grid">
                <?php if(empty($pets)): ?>
                    <p style="grid-column: span 2; text-align: center; color: #BBB; padding: 50px;">No pets found matching your criteria.</p>
                <?php else: ?>
                    <?php foreach($pets as $pet): ?>
                    <div class="pet-card-item">
                        <div class="card-flex">
                            <div class="thumb-container">
                                <div class="pet-img-circle" style="background-image: url('<?php echo $pet['profile_img'] ?: '../resources/placeholder.png'; ?>');"></div>
                            </div>
                            <div class="pet-info-box">
                                <h2><?php echo $pet['name']; ?></h2>
                                <p class="summary-sentence">
                                    <strong><?php echo $pet['name']; ?></strong> likes 
                                    <?php echo strtolower($pet['likes']); ?> 
                                    and can be usually found at 
                                    <strong><?php echo $pet['location']; ?></strong>.
                                </p>
                                </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- PAGINATION (X out of Y) -->
            <div class="pag-wrapper">
                <?php 
                    // Build query string to keep filters active when changing pages
                    $query_str = "q=".urlencode($search)."&location=".urlencode($loc_filter)."&sort=".urlencode($sort); 
                ?>
                
                <a href="pets.php?page=<?php echo max(1, $page - 1); ?>&<?php echo $query_str; ?>" 
                   class="pag-btn <?php echo $page <= 1 ? 'disabled' : ''; ?>">Prev</a>
                
                <span class="pag-count"><?php echo $page; ?> out of <?php echo $total_pages; ?></span>
                
                <a href="pets.php?page=<?php echo $page + 1; ?>&<?php echo $query_str; ?>" 
                   class="pag-btn <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">Next</a>
            </div>
        </main>

        <footer>www.campustails.com</footer>
    </div>

    <script src="script.js"></script>
</body>
</html>
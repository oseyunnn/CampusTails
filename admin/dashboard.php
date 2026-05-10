<?php
include('../utils/db_config.php');

// 1. Fetch all pets to calculate general stats
$pets = supabase_query("pets");
$total_pets = is_array($pets) ? count($pets) : 0;

// 2. Fetch vaccine records to count unique vaccinated animals
// We select only 'pet_id' to keep the query light
$vaccine_data = supabase_query("vaccine_records?select=pet_id");
$vaccinated_count = 0;

if (is_array($vaccine_data) && !empty($vaccine_data)) {
    // Extract all pet_ids into a simple array
    $all_vaccinated_ids = array_column($vaccine_data, 'pet_id');
    // array_unique removes duplicates (so 1 pet with 5 vaccines counts as 1)
    $unique_vaccinated_ids = array_unique($all_vaccinated_ids);
    $vaccinated_count = count($unique_vaccinated_ids);
}

$adopted_count = 0;
$recent_count = 0;
$one_day_ago = strtotime("-1 day");

if ($total_pets > 0) {
    foreach ($pets as $pet) {
        // Count Adopted
        if (isset($pet['is_adopted']) && $pet['is_adopted'] == true) {
            $adopted_count++;
        }
        
        // Recently Added Logic (Last 24 hours)
        if (isset($pet['created_at']) && strtotime($pet['created_at']) > $one_day_ago) {
            $recent_count++;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | Admin Dashboard</title>
    <link rel="stylesheet" href="style.css?v=1.1">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="dashboard-wrapper">
        <header>
            <div class="nav-container">
                <div class="logo">
                    <img src="../resources/Logo.png" alt="CampusTails" style="height: 45px; width: auto; object-fit: contain;">
                </div>
                <nav class="main-nav">
                    <a href="#" class="active">home</a>
                    <a href="pets_directory/pets.php">pets</a>
                    <a href="#">users</a>
                    <a href="#" class="logout-btn">logout</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <h1 class="section-heading">PawCenterbase</h1>
            <div class="stats-grid">
                <div class="stat-card bg-pink"><span class="stat-number"><?php echo $total_pets; ?></span><span class="stat-label">Registered Pets</span></div>
                <div class="stat-card bg-lavender"><span class="stat-number"><?php echo $vaccinated_count; ?></span><span class="stat-label">Fully Vaccinated</span></div>
                <div class="stat-card bg-blue"><span class="stat-number"><?php echo $adopted_count; ?></span><span class="stat-label">Pets Adopted</span></div>
                <div class="stat-card bg-purple"> <span class="stat-number"><?php echo $recent_count; ?></span> <span class="stat-label">Recently Added</span></div>
            </div>
        </main>

        <section class="tools-section">
            <h1 class="section-heading">PawCrew Tools</h1>
            <div class="tools-grid">
                <div class="tool-card" onclick="openModal()">
                    <div class="paw-circle"><i class="fas fa-paw"></i></div>
                    <p>Add a PawFriend</p>
                </div>
                <div class="tool-card">
                    <div class="paw-circle"><i class="fas fa-file-medical"></i></div>
                    <p>Vaccination Records</p>
                </div>
                <div class="tool-card">
                    <div class="paw-circle"><i class="fas fa-utensils"></i></div>
                    <p>Donations Directory</p>
                </div>
                <div class="tool-card">
                    <div class="paw-circle"><i class="fas fa-heart"></i></div>
                    <p>Adoption Requests</p>
                </div>
            </div>
        </section>

        <footer>www.campustails.com</footer>
    </div>

    <div id="pawModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-content-scrollable">
                <h2 class="section-heading" style="margin: 30px 0 20px;">New Paw Profile</h2>
                
                <form id="petForm" enctype="multipart/form-data">
                    <div id="step1">
                        <div class="img-header">
                            <div class="cover-box" id="cover-prev">
                                <input type="file" id="cover-in" hidden accept="image/*">
                                <label for="cover-in" class="upload-trigger"><i class="fas fa-image"></i></label>
                            </div>
                            <div class="profile-circle" id="profile-prev">
                                <input type="file" id="profile-in" hidden accept="image/*">
                                <label for="profile-in" class="upload-trigger"><i class="fas fa-image"></i></label>
                            </div>
                        </div>

                        <div class="blue-banner">Pet Profile</div>

                      <div class="form-box">
                        <!-- Updated with name="name" -->
                        <div class="form-row"><label>Name</label><input type="text" name="name" placeholder="Pet Name"></div>
                        
                        <!-- Updated with name="species" -->
                        <div class="form-row"><label>Species</label><select name="species"><option>Cat</option><option>Dog</option></select></div>
                        
                        <!-- Updated with name="likes" -->
                        <div class="form-row vertical-stack"><label>Likes</label><input type="text" name="likes" placeholder="Treats, long walks, belly rubs..."></div>
                        
                        <!-- Updated with name="date_found" -->
                        <div class="form-row"><label>Date Found</label><input type="date" name="date_found"></div>
                        
                        <!-- Updated with name="allergies" -->
                        <div class="form-row vertical-stack"><label>Allergies</label><input type="text" name="allergies" placeholder="List any allergies here..."></div>
                        
                        <div class="form-row">
                            <label>Location Found</label>
                            <div class="loc-wrapper">
                                <!-- Updated with name="location" -->
                                <select id="locSel" name="location"><option>Main Campus</option></select>
                                <button type="button" class="add-loc-btn" onclick="addLocation()"><i class="fas fa-plus-circle"></i></button>
                            </div>
                        </div>
                    </div>

                        <div class="modal-btns">
                            <button type="button" class="btn btn-cancel" onclick="closeModal()">Cancel</button>
                            <button type="button" class="btn btn-save" onclick="goToStep2()">Next</button>
                        </div>
                    </div>

                    <div id="step2" style="display: none;">
                        <div class="blue-banner">Health Records</div>
                        <div class="form-box">
                            <div class="section-header">
                                <span>Vaccine Records</span>
                                <button type="button" class="add-pill" onclick="addNewRow('vaccine-wrap', 'vaccine')">Add</button>
                            </div>
                            <div id="vaccine-wrap"></div>

                            <div class="section-header">
                                <span>Current Medications</span>
                                <button type="button" class="add-pill" onclick="addNewRow('med-wrap', 'medication')">Add</button>
                            </div>
                            <div id="med-wrap"></div>

                            <div class="section-header">
                                <span>Medical History</span>
                                <button type="button" class="add-pill" onclick="addNewRow('hist-wrap', 'history')">Add</button>
                            </div>
                            <div id="hist-wrap"></div>
                        </div>

                        <div class="modal-btns">
                            <button type="button" class="btn btn-cancel" onclick="goToStep1()">Back</button>
                            <button type="submit" class="btn btn-save">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <script src="script.js"></script>
</body>
</html>
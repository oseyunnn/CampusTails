<?php
include('../../utils/db_config.php');

$pet_id = $_GET['id'] ?? null;
if (!$pet_id) { die("Error: Pet ID is required."); }

// Fetch Pet + All related Health Records
$endpoint = "pets?id=eq.$pet_id&select=*,vaccine_records(*),medications(*),medical_history(*)";
$result = supabase_query($endpoint);

if (empty($result)) { die("Error: Pet not found."); }
$pet = $result[0];

// Prepare arrays for UI loops
$vaccines = $pet['vaccine_records'] ?? [];
$meds     = $pet['medications'] ?? [];
$history  = $pet['medical_history'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | <?php echo htmlspecialchars($pet['name']); ?></title>
    <link rel="stylesheet" href="style.css?v=1.1">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="profile-view-body">

    <!-- DATA STORE FOR JS MODAL POPULATION -->
    <div id="pet-data-store" style="display:none;" 
         data-vaccines='<?php echo json_encode($vaccines); ?>'
         data-meds='<?php echo json_encode($meds); ?>'
         data-history='<?php echo json_encode($history); ?>'>
    </div>

    <div class="profile-master-wrapper">
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
            <h1 class="page-title">Paw Profile</h1>

            <!-- HERO SECTION -->
            <section class="hero-block">
                <div class="cover-photo" style="background-image: url('<?php echo $pet['cover_img']; ?>');"></div>
                <div class="hero-id-area">
                    <div class="avatar-border">
                        <div class="avatar-img" style="background-image: url('<?php echo $pet['profile_img']; ?>');"></div>
                    </div>
                    <div class="name-controls">
                        <h2><?php echo htmlspecialchars($pet['name']); ?></h2>
                        <div class="hero-pills">
                            <div class="pill"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($pet['location']); ?></div>
                            <button class="pill edit-btn" onclick="openModal()"><i class="fas fa-pencil-alt"></i> Edit</button>
                        </div>
                    </div>
                </div>
            </section>

            <div class="tab-switcher">
                <button class="tab-link active" onclick="openTab(event, 'pet-profile')">Pet Profile</button>
                <button class="tab-link" onclick="openTab(event, 'health-records')">Health Records</button>
            </div>

            <!-- VIEW: BASIC PROFILE -->
            <div id="pet-profile" class="tab-content active">
                <div class="data-card">
                    <div class="data-row"><label>Name</label> <strong><?php echo htmlspecialchars($pet['name']); ?></strong></div>
                    <div class="data-row"><label>Species</label> <strong><?php echo $pet['species']; ?></strong></div>
                    <div class="data-row"><label>Likes</label> <strong><?php echo htmlspecialchars($pet['likes']); ?></strong></div>
                    <div class="data-row"><label>Date Found</label> <strong><?php echo $pet['date_found'] ? date("F d, Y", strtotime($pet['date_found'])) : 'N/A'; ?></strong></div>
                    <div class="data-row"><label>Allergies</label> <strong><?php echo htmlspecialchars($pet['allergies']); ?></strong></div>
                </div>
            </div>

            <!-- VIEW: HEALTH RECORDS -->
            <div id="health-records" class="tab-content">
                <div class="data-card">
                    <div class="sub-section">
                        <h3>Vaccine Records</h3>
                        <?php foreach($vaccines as $v): ?>
                            <div class="record-group">
                                <div class="data-row"><label>Vaccine Name</label> <strong><?php echo htmlspecialchars($v['vaccine_name']); ?></strong></div>
                                <div class="data-row"><label>Date Administered</label> <strong><?php echo $v['date_administered']; ?></strong></div>
                                <div class="data-row"><label>Next Due Date</label> <strong><?php echo $v['next_due_date'] ?: 'N/A'; ?></strong></div>
                                <div class="data-row"><label>Veterinarian</label> <strong><?php echo htmlspecialchars($v['veterinarian']); ?></strong></div>
                                <?php if($v['document_url']): ?>
                                    <div class="data-row"><label>Proof</label> <a href="<?php echo $v['document_url']; ?>" target="_blank" style="color:var(--primary-purple); font-weight:700;">View PDF</a></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="sub-section">
                        <h3>Current Medications</h3>
                        <?php foreach($meds as $m): ?>
                            <div class="record-group">
                                <div class="data-row"><label>Medicine Name</label> <strong><?php echo htmlspecialchars($m['medicine_name']); ?></strong></div>
                                <div class="data-row"><label>Dosage</label> <strong><?php echo htmlspecialchars($m['dosage']); ?></strong></div>
                                <div class="data-row"><label>Date Started</label> <strong><?php echo $m['date_started']; ?></strong></div>
                                <div class="data-row"><label>Purpose</label> <strong><?php echo htmlspecialchars($m['purpose']); ?></strong></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="sub-section">
                        <h3>Medical History</h3>
                        <?php foreach($history as $h): ?>
                            <div class="record-group">
                                <div class="data-row"><label>Past Illness Name</label> <strong><?php echo htmlspecialchars($h['illness_name']); ?></strong></div>
                                <div class="data-row"><label>Category</label> <strong><?php echo $h['category']; ?></strong></div>
                                <div class="data-row"><label>Date Diagnosed</label> <strong><?php echo $h['date_diagnosed']; ?></strong></div>
                                <div class="data-row"><label>Ongoing</label> <strong><?php echo $h['is_ongoing'] ? 'Yes' : 'No'; ?></strong></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
        <footer>www.campustails.com</footer>
    </div>

    <!-- MODAL -->
    <div id="pawModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-content-scrollable">
                <button type="button" class="btn-delete-record" onclick="deletePet()">
                <i class="fas fa-trash-alt"></i> Delete Pet
                </button>
                <h2 class="section-heading" style="margin: 30px 0 20px;">Edit Paw Profile</h2>
                <form id="fullEditForm" enctype="multipart/form-data">
                    <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">
                    <div id="step1">
                        <div class="img-header">
                            <div class="cover-box" id="cover-prev" style="background-image: url('<?php echo $pet['cover_img']; ?>');">
                                <input type="file" id="cover-in" name="cover_img_file" hidden accept="image/*">
                                <label for="cover-in" class="upload-trigger"><i class="fas fa-image"></i></label>
                            </div>
                            <div class="profile-circle" id="profile-prev" style="background-image: url('<?php echo $pet['profile_img']; ?>');">
                                <input type="file" id="profile-in" name="profile_img_file" hidden accept="image/*">
                                <label for="profile-in" class="upload-trigger"><i class="fas fa-image"></i></label>
                            </div>
                        </div>

                        <div class="blue-banner">Pet Profile</div>
                        <div class="form-box">
                            <div class="form-row"><label>Name</label><input type="text" name="name" value="<?php echo htmlspecialchars($pet['name']); ?>"></div>
                            <div class="form-row"><label>Species</label>
                                <select name="species">
                                    <option value="Cat" <?php echo $pet['species']=='Cat'?'selected':''; ?>>Cat</option>
                                    <option value="Dog" <?php echo $pet['species']=='Dog'?'selected':''; ?>>Dog</option>
                                </select>
                            </div>
                            <div class="form-row vertical-stack"><label>Likes</label><input type="text" name="likes" value="<?php echo htmlspecialchars($pet['likes']); ?>"></div>
                            <div class="form-row"><label>Date Found</label><input type="date" name="date_found" value="<?php echo $pet['date_found']; ?>"></div>
                            <div class="form-row vertical-stack"><label>Allergies</label><input type="text" name="allergies" value="<?php echo htmlspecialchars($pet['allergies']); ?>"></div>
                            <div class="form-row">
                                <label>Location</label>
                                <div class="loc-wrapper">
                                    <select id="locSel" name="location"><option value="<?php echo $pet['location']; ?>"><?php echo $pet['location']; ?></option></select>
                                    <button type="button" class="add-loc-btn" onclick="addLocation()"><i class="fas fa-plus"></i></button>
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
                            <div class="section-header"><span>Vaccine Records</span><button type="button" class="add-pill" onclick="addNewRow('vaccine-wrap', 'vaccine')">Add</button></div>
                            <div id="vaccine-wrap"></div>
                            <div class="section-header"><span>Current Medications</span><button type="button" class="add-pill" onclick="addNewRow('med-wrap', 'medication')">Add</button></div>
                            <div id="med-wrap"></div>
                            <div class="section-header"><span>Medical History</span><button type="button" class="add-pill" onclick="addNewRow('hist-wrap', 'history')">Add</button></div>
                            <div id="hist-wrap"></div>
                        </div>
                        <div class="modal-btns">
                            <button type="button" class="btn btn-cancel" onclick="goToStep1()">Back</button>
                            <button type="submit" class="btn btn-save">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="script.js?v=1.1"></script>
</body>
</html>
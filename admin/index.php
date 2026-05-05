<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="dashboard-wrapper">
        <header>
            <div class="nav-container">
                <div class="logo">🐾 CampusTails</div>
                <nav class="main-nav">
                    <a href="#" class="active">home</a>
                    <a href="#">pets</a>
                    <a href="#">users</a>
                    <a href="#" class="logout-btn">logout</a>
                </nav>
            </div>
        </header>

        <main class="container">
            <h1 class="section-heading">PawCenterbase</h1>
            <div class="stats-grid">
                <div class="stat-card bg-pink"><span class="stat-number">50</span><span class="stat-label">Registered Pets</span></div>
                <div class="stat-card bg-lavender"><span class="stat-number">25</span><span class="stat-label">Fully Vaccinated</span></div>
                <div class="stat-card bg-blue"><span class="stat-number">5</span><span class="stat-label">Pets Under Observation</span></div>
                <div class="stat-card bg-purple"><span class="stat-number">3</span><span class="stat-label">Recently Added</span></div>
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
                    <p>Pet Foods Directory</p>
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
                
                <form id="petForm">
                    <div id="step1">
                        <div class="img-header">
                            <div class="cover-box" id="cover-prev">
                                <input type="file" id="cover-in" hidden accept="image/*">
                                <label for="cover-in" class="upload-trigger"><i class="fas fa-image"></i><br><span>Edit Cover</span></label>
                            </div>
                            <div class="profile-circle" id="profile-prev">
                                <input type="file" id="profile-in" hidden accept="image/*">
                                <label for="profile-in" class="upload-trigger"><i class="fas fa-image"></i><br><span>Edit Photo</span></label>
                            </div>
                        </div>

                        <div class="blue-banner">Pet Profile</div>

                        <div class="form-box">
                            <div class="form-row"><label>Name</label><input type="text" placeholder="Pet Name"></div>
                            <div class="form-row"><label>Specie</label><select><option>Cat</option><option>Dog</option></select></div>
                            <div class="form-row vertical-stack"><label>Likes</label><input type="text" placeholder="Treats, long walks, belly rubs..."></div>
                            <div class="form-row"><label>Date Found</label><input type="date"></div>
                            <div class="form-row vertical-stack"><label>Allergies</label><input type="text" placeholder="List any allergies here..."></div>
                            <div class="form-row">
                                <label>Location Found</label>
                                <div class="loc-wrapper">
                                    <select id="locSel"><option>Main Campus</option></select>
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
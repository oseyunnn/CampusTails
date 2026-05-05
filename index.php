<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | Admin</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="main-content">
        <header>
            <div class="nav-pill">
                <div class="logo">🐾 CampusTails</div>
                <nav class="main-nav">
                    <a href="#" class="active">home</a>
                    <a href="#">pets</a>
                    <a href="#">users</a>
                    <a href="#">logout</a>
                </nav>
            </div>
        </header>

        <section>
            <h1 class="section-title">PawCenterbase</h1>
            <div class="stats-grid">
                <div class="stat-card bg-pink"><span class="stat-number">50</span><span class="stat-label">Registered Pets</span></div>
                <div class="stat-card bg-lavender"><span class="stat-number">25</span><span class="stat-label">Fully Vaccinated</span></div>
                <div class="stat-card bg-blue"><span class="stat-number">5</span><span class="stat-label">Pets Under Observation</span></div>
                <div class="stat-card bg-purple"><span class="stat-number">3</span><span class="stat-label">Recently Added</span></div>
            </div>
        </section>

        <section class="tools-section">
            <h1 class="section-title">PawCrew Tools</h1>
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
    </div>

    <footer>www.campustails.com</footer>

    <!-- MODAL -->
    <div id="pawModal" class="modal-overlay">
        <div class="modal-card">
            <h1 class="section-title" style="margin: 25px 0; font-size: 2rem;">New Paw Profile</h1>
            <form action="#" method="POST">
                <div class="img-header">
                    <div class="cover-img" id="c-prev">
                        <input type="file" id="c-in" hidden accept="image/*">
                        <label for="c-in" class="upload-btn"><i class="fas fa-image"></i></label>
                    </div>
                    <div class="profile-img" id="p-prev">
                        <input type="file" id="p-in" hidden accept="image/*">
                        <label for="p-in" class="upload-btn"><i class="fas fa-camera"></i></label>
                    </div>
                </div>

                <div class="blue-banner">Pet Profile</div>

                <div class="form-inner">
                    <div class="row"><label>Name</label><input type="text" placeholder="Pet Name"></div>
                    <div class="row"><label>Species</label><select><option>Cat</option><option>Dog</option></select></div>
                    <div class="row"><label>Likes</label><input type="text" placeholder="Treats, walks..."></div>
                    <div class="row"><label>Date Found</label><input type="date"></div>
                    <div class="row"><label>Allergies</label><input type="text" placeholder="Any allergies?"></div>
                    <div class="row">
                        <label>Location Found</label>
                        <div class="loc-row">
                            <select id="locSel">
                                <option value="Main Campus">Main Campus</option>
                                <option value="Dormitory">Dormitory</option>
                            </select>
                            <!-- type="button" is critical here so it doesn't submit the form -->
                            <button type="button" class="add-btn" onclick="addLoc()">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-btns">
                    <button type="button" class="btn btn-c" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-s">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
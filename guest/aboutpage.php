<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | CampusTails</title>
    <link rel="stylesheet" href="aboutpage.css?v=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- NAVBAR (Copied exactly from Home) -->
    <header class="landing-header">
        <div class="nav-container">
            <div class="logo">
                <img src="../resources/logo-white.png" alt="CampusTails">
            </div>
            <nav class="main-nav">
                <a href="../home/index.php">home</a>
                <a href="aboutpage.php" class="active">about</a>
                <a href="../pets_directory/pets.php">pets</a>
                <a href="../login/index.php">login</a>
            </nav>
        </div>
    </header>

    <main>
        <!-- HERO: Framing logic from Home with About content -->
        <section class="about-hero">
            <div class="hero-frame">
                <img src="../resources/campusPets.png" class="hero-bg" alt="Hero">
                <div class="hero-overlay">
                    <span class="small-label">About</span>
                    <h1 class="main-title">CampusTail</h1>
                </div>
            </div>
        </section>

        <!-- MISSION & VISION: Using the white space logic from Home -->
        <div class="content-wrapper">
            <section class="mv-section">
                <div class="mv-row">
                    <div class="mv-text">
                        <span class="tag">Our</span>
                        <h2 class="mv-title">Mission</h2>
                        <p>CampusTails supports the efforts of CIT-U Paws by providing a platform that promotes awareness, responsible care, and community involvement for campus animals at Cebu Institute of Technology–University.</p>
                    </div>
                    <div class="mv-img">
                        <img src="../resources/image1.png" alt="Mission">
                    </div>
                </div>

                <div class="mv-row reverse">
                    <div class="mv-text">
                        <span class="tag">Our</span>
                        <h2 class="mv-title">Vision</h2>
                        <p>To foster a compassionate campus community at Cebu Institute of Technology–University where students actively support and advocate for the welfare of campus animals through the initiatives of CIT-U Paws.</p>
                    </div>
                    <div class="mv-img">
                        <img src="../resources/image2.png" alt="Vision">
                    </div>
                </div>
            </section>
        </div>

        <!-- CAMPUS CREW: Using the "Community Wall" pink background logic -->
        <section class="crew-section">
            <div class="section-header">
                <p>Be Inspired by our</p>
                <h2 class="purple-main-title">CampusCrew</h2>
            </div>
            <div class="crew-grid">
                <?php
                $crew_members = [
                    ["Angela Jahziel Encabo", "Helping fellow campus pets around campus is really inspiring. I am constantly moved by the generous hearts in our community."],
                    ["Jhen Nina Grace Aloyon", "It's heart-warming to see our campus becoming a second home for these stray paws. Small acts of kindness make a huge difference."],
                    ["Sophia Logarta", "I love how everyone comes together to care for the pets. CampusTails has really helped us organize our efforts better."],
                    ["Aissha Monceda", "Seeing the cats lounging by the fountain always brightens my stress morning. I'm so glad we have a community that protects them."],
                    ["Gerald Benedict Ares", "Every paw matters. By organizing our efforts, we're ensuring that no pet on campus is left hungry or uncared for."],
                    ["Leah Barbaso", "I am proud to be part of a team that puts animals first. We are building a legacy of kindness that will last for years."]
                ];
                foreach($crew_members as $m): ?>
                <div class="crew-card">
                    <h3><?php echo $m[0]; ?></h3>
                    <span class="since">CampusCrew since May 2025</span>
                    <p>"<?php echo $m[1]; ?>"</p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <!-- FOOTER (Copied exact logic from Home) -->
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

    <script src="aboutpage.js"></script>
</body>
</html>
<?php
// Ensure NO spaces or text exist above this <?php tag
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === "admin" && $password === "password1234") {
        // Path: Go up one folder, then into admin folder to dashboard.php
        header("Location: ../admin/dashboard.php"); 
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">

    <div class="login-master">
        <!-- HEADER -->
        <header class="login-header">
            <div class="nav-container login-nav-box">
                <div class="logo-side">
                    <img src="../resources/Logo.png" alt="CampusTails">
                </div>
                <nav class="nav-links">
                    <a href="#">home</a>
                    <a href="#">about</a>
                    <a href="#">pets</a>
                    <a href="#" class="active">login</a>
                </nav>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="login-split">
            <section class="hero-col">
                <div class="bubbles-frame">
                    <img src="../resources/LoginGreetings.png" alt="Greetings">
                </div>
            </section>

            <section class="form-col">
                <div class="auth-card">
                    <div class="auth-logo">
                        <img src="../resources/Logo.png" alt="CampusTails">
                        <h3>Welcome Back!</h3>
                    </div>

                    <!-- action="" ensures it posts to the current file -->
                    <form action="" method="POST">
                        <div class="auth-row">
                            <input type="text" name="username" placeholder="username" required>
                        </div>
                        <div class="auth-row pass-wrapper">
                            <input type="password" name="password" id="passInput" placeholder="password" required>
                            <i class="fas fa-eye-slash" id="eyeBtn"></i>
                        </div>

                        <?php if(isset($error) && $error != ""): ?><p class="err-msg" style="color:#FF5252; font-size:0.85rem; margin-bottom:10px;"><?php echo $error; ?></p><?php endif; ?>

                        <div class="auth-extras">
                            <label><input type="checkbox"> Remember me</label>
                            <a href="#">Forgot Password?</a>
                        </div>

                        <button type="submit" class="submit-btn">LOGIN</button>
                        <p class="signup-link">Don't have an account yet? <a href="#">Signup here!</a></p>
                    </form>
                </div>
            </section>
        </main>

        <footer class="login-footer">
            <p>Do you want to be a part of our PawCrew? <a href="#">Send us an email!</a></p>
        </footer>

        <!-- CHARACTER -->
        <div class="character-anchor">
            <img src="../resources/footer.png" alt="Character">
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
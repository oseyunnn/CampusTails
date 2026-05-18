<?php
session_start();
include('../utils/db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Fetch user and join profile tables
    $endpoint = "users?username=eq.$username&select=*,student_profiles(*),faculty_profiles(*)";
    $result = supabase_query($endpoint);

    if (!empty($result)) {
        $user = $result[0];
        
        // Verify Password
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['account_type'] = $user['account_type'];

            // Redirect based on account type
        
        if ($user['account_type'] === 'admin') {
    // Go up one, then into admin folder
             header("Location: ../admin/dashboard.php");
        } else {
    // Go up one, then into user_profile folder
            header("Location: ../user_profile/user_profile.php");
        }
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
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

                    <!-- Inside login/index.php -->
                    <?php if(isset($_GET['signup']) && $_GET['signup'] == 'success'): ?>
                    <p style="color: #4CAF50; background: #e8f5e9; padding: 10px; border-radius: 10px; font-size: 0.9rem; font-weight: 600;">
                     Registration successful! You can now log in.
                    </p>
                    <?php endif; ?>
                    
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
                        <p class="signup-link">Don't have an account yet? <a href="../register/index.php">Signup here!</a></p>
                    </form>
                </div>
            </section>
        </main>

        <footer class="login-footer">
            <p>Be a part of our PawCrew!<a href="../register/index.php">Send us an email!</a></p>
        </footer>

        <!-- CHARACTER -->
        <div class="character-anchor">
            <img src="../resources/footer.png" alt="Character">
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
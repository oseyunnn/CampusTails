<?php
session_start();
include('../utils/db_config.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim whitespace to prevent accidental trailing space typos
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = $_POST['password'] ?? '';

    // STREAMLINED: Query just the core user details first to guarantee a match from paw_users
    $endpoint = "paw_users?username=eq." . urlencode($username) . "&select=*";
    $result = supabase_query($endpoint);

    if ($result === null) {
        $error = "Connection Error: Please check your internet or .env settings.";
    } elseif (is_array($result) && isset($result[0])) {
        $user = $result[0];
        
        // Verifying the hashed password safely against database column 'password_hash'
        if (password_verify($password, $user['password_hash'])) {
          $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['account_type']; // or $user['role'] depending on your DB column

            // Redirect based on account type
            if ($user['account_type'] === 'superadmin') {
                header("Location: ../superadmin/dashboard.php");
            } elseif ($user['account_type'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../user/index.php");
            }
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        // If it falls here, 'paw_users' literally does not contain a row where username = $username
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
        <header class="login-header">
            <div class="nav-container login-nav-box">
                <div class="logo-side">
                    <img src="../resources/Logo.png" alt="CampusTails">
                </div>
                <nav class="nav-links">
                   <a href="../home/index.php" >home</a>
                    <a href="../guest/aboutpage.php">about</a>
                    <a href="../pets_directory/pets.php">pets</a>
                    <a href="../login/index.php" class="active">login</a>
                </nav>
            </div>
        </header>

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
                    
                    <form action="" method="POST">
                        <div class="auth-row">
                            <input type="text" name="username" placeholder="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
                        </div>
                        <div class="auth-row pass-wrapper">
                            <input type="password" name="password" id="passInput" placeholder="password" required>
                            <i class="fas fa-eye-slash" id="eyeBtn"></i>
                        </div>

                        <?php if(!empty($error)): ?>
                            <p class="err-msg" style="color:#FF5252; font-size:0.85rem; margin-bottom:10px; font-weight: 600;">
                                <?php echo htmlspecialchars($error); ?>
                            </p>
                        <?php endif; ?>

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

        <div class="character-anchor">
            <img src="../resources/footer.png" alt="Character">
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
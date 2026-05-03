<?php include 'db.php'; ?>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>CampusTails - Login</title>
</head>
<body>
<div class="window" style="width: 300px; margin: 100px auto;">
    <div class="title-bar"><span>System Login</span></div>
    <div class="window-body">
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <div style="display:flex; justify-content: space-between; margin-top:10px;">
                <button type="submit" name="login">Login</button>
                <a href="register.php" class="btn">Register</a>
            </div>
        </form>
        <?php
        if (isset($_POST['login'])) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$_POST['username']]);
            $user = $stmt->fetch();
            if ($user && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: dashboard.php");
            } else {
                echo "<p style='color:red'>Invalid Login!</p>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
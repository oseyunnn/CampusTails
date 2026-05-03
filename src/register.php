<?php include 'db.php'; ?>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>CampusTails - Register</title>
</head>
<body>
<div class="window" style="width: 300px; margin: 100px auto;">
    <div class="title-bar"><span>Create Account</span></div>
    <div class="window-body">
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit" name="reg">Create Student Account</button>
        </form>
        <?php
        if (isset($_POST['reg'])) {
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'student')");
            try {
                $stmt->execute([$_POST['username'], $_POST['email'], $hash]);
                echo "Success! <a href='login.php'>Login now</a>";
            } catch(Exception $e) { echo "Error: User exists."; }
        }
        ?>
    </div>
</div>
</body>
</html>
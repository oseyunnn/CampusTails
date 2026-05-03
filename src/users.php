<?php 
include 'db.php';
if ($_SESSION['role'] !== 'admin') die("Unauthorized");

// Toggle Role Logic
if (isset($_GET['toggle'])) {
    $stmt = $pdo->prepare("UPDATE users SET role = IF(role='admin', 'student', 'admin') WHERE id = ?");
    $stmt->execute([$_GET['toggle']]);
    header("Location: users.php");
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="window">
        <div class="title-bar"><span>User Management</span><button>+</button></div>
        <div class="window-body">
            <table>
                <tr><th>User</th><th>Email</th><th>Role</th><th>Actions</th></tr>
                <?php
                $users = $pdo->query("SELECT * FROM users")->fetchAll();
                foreach ($users as $u):
                ?>
                <tr>
                    <td><?= $u['username'] ?></td>
                    <td><?= $u['email'] ?></td>
                    <td><span class="badge"><?= strtoupper($u['role']) ?></span></td>
                    <td>
                        <a href="?toggle=<?= $u['id'] ?>"><button>Toggle Role</button></a>
                        <button style="color:red">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
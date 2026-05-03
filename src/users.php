<?php include 'db.php'; 
if($_SESSION['role'] != 'admin') header("Location: dashboard.php");

// Logic to switch roles
if(isset($_GET['toggle_id'])){
    $stmt = $pdo->prepare("UPDATE users SET role = IF(role='admin', 'student', 'admin') WHERE id = ?");
    $stmt->execute([$_GET['toggle_id']]);
    header("Location: users.php");
}
?>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h2>User Management</h2>
        <table>
            <thead>
                <tr><th>User Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php
                $users = $pdo->query("SELECT * FROM users")->fetchAll();
                foreach($users as $u):
                ?>
                <tr>
                    <td><strong><?= $u['username'] ?></strong></td>
                    <td><?= $u['email'] ?></td>
                    <td><span class="badge" style="background:<?= $u['role']=='admin'?'#d1d8ff':'#eee' ?>"><?= strtoupper($u['role']) ?></span></td>
                    <td>
                        <button class="btn" onclick="toggleRole(<?= $u['id'] ?>)">Switch Role</button>
                        <button class="btn btn-delete">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    function toggleRole(id) {
        if(confirm("Change role for this user?")) {
            window.location.href = 'users.php?toggle_id=' + id;
        }
    }
    </script>
</body>
</html>
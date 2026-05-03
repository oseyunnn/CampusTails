<?php
$host = 'localhost';
$db   = 'campustails';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (\PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

session_start();

// Helper function for Activity Logs
function logActivity($pdo, $action, $target) {
    if(isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, target_pet) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $action, $target]);
    }
}
?>
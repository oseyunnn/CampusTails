<?php
session_start();
// Check if user is logged in and has the Superadmin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ../login.php?error=unauthorized");
    exit();
}
?>
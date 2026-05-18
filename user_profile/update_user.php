<?php
session_start();
// Pointing to your existing centralized database configuration utility
include('../utils/db_config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.php");
    exit();
}

// Ensure request is coming from a form POST action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    
    // Capture user inputs from the profile edit card form elements
    $first_name   = trim($_POST['first_name'] ?? '');
    $last_name    = trim($_POST['last_name'] ?? '');
    $contact_no   = trim($_POST['contact'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $affiliations = trim($_POST['affiliations'] ?? '');

    // Validate that required core operational variables are present
    if (empty($first_name) || empty($last_name) || empty($email)) {
        header("Location: index.php?error=Missing required profile fields");
        exit();
    }

    // Build the query data block mapping directly to your Supabase paw_users schema columns
    $update_payload = [
        "first_name"     => $first_name,
        "last_name"      => $last_name,
        "contact_number" => $contact_no,
        "email"          => $email,
        "affiliation"    => $affiliations
    ];

    // Supabase target REST target path utilizing the unique filter constraint
    $endpoint = "paw_users?user_id=eq." . urlencode($user_id);
    
    // Send request via PATCH method to execute an update operation
    $response = supabase_query($endpoint, "PATCH", $update_payload);

    // Bounce the view straight back to the user index to render structural edits dynamically
    header("Location: index.php?success=Profile updated successfully");
    exit();
} else {
    // If someone accesses the page directly without submitting, route them back gracefully
    header("Location: index.php");
    exit();
}
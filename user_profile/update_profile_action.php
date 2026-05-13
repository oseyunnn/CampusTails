<?php
session_start();
include('../utils/db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_SESSION['user_id'];
    
    // Split Full Name back into First and Last
    $names = explode(' ', $_POST['full_name'], 2);
    $fname = $names[0];
    $lname = $names[1] ?? '';

    // 1. Update Users Table
    $userData = [
        "first_name" => $fname,
        "last_name" => $lname,
        "email" => $_POST['email'],
        "contact_number" => $_POST['contact'],
        "affiliation" => $_POST['affiliations']
    ];
    
    // Using PATCH to update only changed fields
    supabase_query("users?user_id=eq.$uid", "PATCH", $userData);

    // 2. Update Role Specific Table
    $role = $_SESSION['role']; // Assuming role is stored in session
    if ($role === 'student') {
        $studentData = [
            "student_number" => $_POST['id_number'],
            "program" => $_POST['program'],
            "year_level" => $_POST['year']
        ];
        supabase_query("student_profiles?user_id=eq.$uid", "PATCH", $studentData);
    } else {
        $facultyData = [
            "office" => $_POST['office']
        ];
        supabase_query("faculty_profiles?user_id=eq.$uid", "PATCH", $facultyData);
    }

    // Success redirect
    header("Location: index.php");
    exit();
}
<?php
session_start();
header('Content-Type: application/json');
require_once '../../utils/db_config.php'; 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    // 1. Clear dependencies
    // Remove role-specific profiles and favorites
    supabase_query("student_profiles?user_id=eq." . $id, "DELETE");
    supabase_query("faculty_profiles?user_id=eq." . $id, "DELETE");
    supabase_query("favorites?user_id=eq." . $id, "DELETE");
    // Remove activity logs referencing this user (blocks deletion via FK)
    supabase_query("activity_logs?user_id=eq." . $id, "DELETE");
    // Remove admin codes created by this user (if any)
    supabase_query("admin_codes?created_by=eq." . $id, "DELETE");
    // Remove donations, adoption requests linked to this user
    supabase_query("donations?user_id=eq." . $id, "DELETE");
    supabase_query("adoption_requests?user_id=eq." . $id, "DELETE");
    // Nullify ownership references on pets to avoid orphan FK issues
    supabase_query("pets?user_id=eq." . $id, "PATCH", ["user_id" => null]);

    // 2. Determine which user table exists in the current DB and delete only from that table.
    $candidateTables = ['users', 'paw_users'];
    $foundTable = null;
    $detectionResponses = [];

    foreach ($candidateTables as $t) {
        $resp = supabase_query($t . "?user_id=eq." . $id, "GET");
        $detectionResponses[$t] = $resp;
        // Accept only true result arrays (empty array or numeric-indexed rows).
        // Supabase error responses are associative arrays with a 'code' key,
        // so we must ensure we don't treat those as valid table detections.
        if (is_array($resp) && (empty($resp) || isset($resp[0]))) {
            $foundTable = $t;
            break;
        }
    }

    if (!$foundTable) {
        echo json_encode([
            "success" => false,
            "message" => "Could not detect a user table (tried users and paw_users).",
            "detection" => $detectionResponses
        ]);
        exit();
    }

    // 3. Get the user's display name before deleting it.
    $userName = $id;
    $userResp = supabase_query($foundTable . "?user_id=eq." . $id, "GET");
    if (is_array($userResp) && isset($userResp[0])) {
        $userData = $userResp[0];
        $userName = trim(($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? '')) ?: $id;
    }

    // 4. Perform the DELETE on the detected user table
    $deleteResp = supabase_query($foundTable . "?user_id=eq." . $id, "DELETE");
    $check = supabase_query($foundTable . "?user_id=eq." . $id, "GET");
    $gone = is_array($check) && empty($check);

    if ($gone) {
        supabase_query("activity_logs", "POST", [
            "user_id" => $_SESSION['user_id'],
            "action" => "Confirmed deletion of user: " . $userName . " removed from users"
        ]);

        echo json_encode(["success" => true]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Delete failed on detected user table.",
            "table" => $foundTable,
            "delete_response" => $deleteResp,
            "check_result" => $check
        ]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No ID provided"]);
}
exit();
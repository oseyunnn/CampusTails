<?php
header('Content-Type: application/json');
include('../../utils/db_config.php');

try {
    $petId = $_POST['pet_id'] ?? null;
    if (!$petId) throw new Exception("Pet ID missing.");

    // Note: If your Database has Foreign Key constraints set to 'CASCADE', 
    // deleting the pet will automatically delete vaccines, meds, and history.
    $endpoint = "pets?id=eq." . $petId;
    $result = supabase_query($endpoint, "DELETE");

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
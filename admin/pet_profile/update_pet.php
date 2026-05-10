<?php
header('Content-Type: application/json');
include('../../utils/db_config.php');

try {
    $petId = $_POST['pet_id'] ?? null;
    if (!$petId) throw new Exception("Pet ID (UUID) missing.");

    // 1. UPDATE BASIC INFO
    $petData = [
        "name" => $_POST['name'], 
        "species" => $_POST['species'], 
        "location" => $_POST['location'], 
        "likes" => $_POST['likes'], 
        "allergies" => $_POST['allergies'], 
        "date_found" => !empty($_POST['date_found']) ? $_POST['date_found'] : null
    ];
    
    if (!empty($_FILES['profile_img_file']['tmp_name'])) {
        $petData['profile_img'] = supabase_upload('pets-images', $_FILES['profile_img_file']);
    }
    if (!empty($_FILES['cover_img_file']['tmp_name'])) {
        $petData['cover_img'] = supabase_upload('pets-images', $_FILES['cover_img_file']);
    }
    supabase_query("pets?id=eq.$petId", "PATCH", $petData);

    // 2. REFRESH VACCINE RECORDS
    if (isset($_POST['vaccines'])) {
        $newVaccines = json_decode($_POST['vaccines'], true);
        // Only delete if we have a valid petId to prevent wiping the whole table
        $oldVaccines = supabase_query("vaccine_records?pet_id=eq.$petId");
        supabase_query("vaccine_records?pet_id=eq.$petId", "DELETE");

        foreach($newVaccines as $idx => $v) {
            $fileKey = "vaccine_doc_" . $idx;
            if (!empty($_FILES[$fileKey]['tmp_name'])) {
                $v['document_url'] = supabase_upload('vaccination-records', $_FILES[$fileKey]);
            } else {
                // Restore old document URL if record name matches
                foreach($oldVaccines as $old) {
                    if($old['vaccine_name'] === $v['vaccine_name']) {
                        $v['document_url'] = $old['document_url'];
                    }
                }
            }
            supabase_query("vaccine_records", "POST", $v);
        }
    }

    // 3. REFRESH MEDICATIONS
    if (isset($_POST['medications'])) {
        supabase_query("medications?pet_id=eq.$petId", "DELETE");
        $meds = json_decode($_POST['medications'], true);
        foreach($meds as $m) {
            supabase_query("medications", "POST", $m);
        }
    }

    // 4. REFRESH HISTORY
    if (isset($_POST['history'])) {
        supabase_query("medical_history?pet_id=eq.$petId", "DELETE");
        $hist = json_decode($_POST['history'], true);
        foreach($hist as $h) {
            supabase_query("medical_history", "POST", $h);
        }
    }

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
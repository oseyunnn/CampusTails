<?php
header('Content-Type: application/json');
include('../utils/db_config.php');

try {
    // 1. Collect basic data
    $petData = [
        "name"       => $_POST['name'] ?? 'Unnamed',
        "species"    => $_POST['species'] ?? '',
        "likes"      => $_POST['likes'] ?? '',
        "date_found" => $_POST['date_found'] ?? null,
        "allergies"  => $_POST['allergies'] ?? '',
        "location"   => $_POST['location'] ?? '',
        "is_adopted" => false
    ];

    // 2. Upload Images ONLY if they are provided
    if (isset($_FILES['profile_img_file']) && $_FILES['profile_img_file']['size'] > 0) {
        $petData['profile_img'] = supabase_upload('pets-images', $_FILES['profile_img_file']);
    }

    if (isset($_FILES['cover_img_file']) && $_FILES['cover_img_file']['size'] > 0) {
        $petData['cover_img'] = supabase_upload('pets-images', $_FILES['cover_img_file']);
    }

    // 3. Save Pet Profile
    $petResponse = supabase_query("pets", "POST", $petData);

    if (is_array($petResponse) && isset($petResponse[0]['id'])) {
        $petId = $petResponse[0]['id'];

        // 4. Save Vaccines
        if (isset($_POST['vaccines'])) {
            $vaccines = json_decode($_POST['vaccines'], true);
            foreach ($vaccines as $index => $v) {
                if (!empty($v['vaccine_name'])) {
                    $v['pet_id'] = $petId;
                    $fileKey = "vaccine_doc_" . $index;
                    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['size'] > 0) {
                        $v['document_url'] = supabase_upload('vaccination-records', $_FILES[$fileKey]);
                    }
                    supabase_query("vaccine_records", "POST", $v);
                }
            }
        }

        // 5. Save Medications
        if (isset($_POST['medications'])) {
            $meds = json_decode($_POST['medications'], true);
            foreach ($meds as $m) {
                if (!empty($m['medicine_name'])) {
                    $m['pet_id'] = $petId;
                    supabase_query("medications", "POST", $m);
                }
            }
        }

        // 6. Save Medical History
        if (isset($_POST['history'])) {
            $history = json_decode($_POST['history'], true);
            foreach ($history as $h) {
                if (!empty($h['illness_name'])) {
                    $h['pet_id'] = $petId;
                    supabase_query("medical_history", "POST", $h);
                }
            }
        }

        echo json_encode(["success" => true]);

    } else {
        echo json_encode(["success" => false, "message" => "Supabase rejection: " . json_encode($petResponse)]);
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Server Error: " . $e->getMessage()]);
}
?>
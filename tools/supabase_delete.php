<?php
if ($argc < 2) {
    echo "Usage: php supabase_delete.php <user_id>\n";
    exit(1);
}
require __DIR__ . '/../utils/db_config.php';
$id = $argv[1];

function pretty($v) {
    echo json_encode($v, JSON_PRETTY_PRINT) . "\n";
}

echo "DETECT TABLES\n";
$candidateTables = ['users','paw_users'];
$found = null;
$detection = [];
foreach ($candidateTables as $t) {
    $r = supabase_query($t . "?user_id=eq." . $id, 'GET');
    $detection[$t] = $r;
    if (is_array($r) && (empty($r) || isset($r[0]))) {
        $found = $t;
        break;
    }
}
pretty($detection);

if (!$found) {
    echo "No user table detected. Exiting.\n";
    exit(1);
}

echo "FOUND TABLE: $found\n";

echo "CLEAR DEPENDENCIES\n";
$deps = [];
$deps['student_profiles'] = supabase_query("student_profiles?user_id=eq." . $id, 'DELETE');
$deps['faculty_profiles'] = supabase_query("faculty_profiles?user_id=eq." . $id, 'DELETE');
$deps['favorites'] = supabase_query("favorites?user_id=eq." . $id, 'DELETE');
$deps['activity_logs'] = supabase_query("activity_logs?user_id=eq." . $id, 'DELETE');
$deps['admin_codes'] = supabase_query("admin_codes?created_by=eq." . $id, 'DELETE');
$deps['donations'] = supabase_query("donations?user_id=eq." . $id, 'DELETE');
$deps['adoption_requests'] = supabase_query("adoption_requests?user_id=eq." . $id, 'DELETE');
$deps['pets_nullify'] = supabase_query("pets?user_id=eq." . $id, 'PATCH', ["user_id" => null]);
pretty($deps);

echo "PERFORMING DELETE ON $found\n";
$del = supabase_query($found . "?user_id=eq." . $id, 'DELETE');
pretty($del);

echo "VERIFY\n";
$verify = supabase_query($found . "?user_id=eq." . $id, 'GET');
pretty($verify);

echo "DONE\n";

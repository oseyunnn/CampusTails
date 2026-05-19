<?php
if ($argc < 2) {
    echo "Usage: php supabase_test.php <user_id>\n";
    exit(1);
}
require __DIR__ . '/../utils/db_config.php';
$id = $argv[1];

function pretty($v) {
    echo json_encode($v, JSON_PRETTY_PRINT) . "\n";
}

echo "GET_BEFORE\n";
$before = supabase_query("users?user_id=eq." . $id, "GET");
pretty($before);

echo "DELETE\n";
$del = supabase_query("users?user_id=eq." . $id, "DELETE");
pretty($del);

echo "GET_AFTER\n";
$after = supabase_query("users?user_id=eq." . $id, "GET");
pretty($after);

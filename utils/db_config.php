<?php
// --- ROBUST .ENV LOADER ---
$baseDir = dirname(__DIR__); 
$envFile = $baseDir . '/.env';
$env = []; // Use a custom array instead of $_ENV

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            // Trim both sides and remove any surrounding quotes
            $env[trim($name)] = trim($value, " \t\n\r\0\x0B\"'");
        }
    }
} else {
    header('Content-Type: application/json');
    die(json_encode(["success" => false, "message" => ".env file missing in " . $baseDir]));
}

// Extract variables from our custom $env array
$SUPABASE_URL = $env['SUPABASE_URL'] ?? null;
$SUPABASE_KEY = $env['SUPABASE_KEY'] ?? null;
$SUPABASE_USER_URL = $env['SUPABASE_USER_URL'] ?? null;
$SUPABASE_USER_KEY = $env['SUPABASE_USER_KEY'] ?? null;

// Validation
if (!$SUPABASE_URL || !$SUPABASE_KEY) {
    header('Content-Type: application/json');
    die(json_encode(["success" => false, "message" => "Main Supabase keys missing in .env"]));
}

function supabase_query($endpoint, $method = 'GET', $data = null) {
    global $SUPABASE_URL, $SUPABASE_KEY;

    $baseUrl = rtrim($SUPABASE_URL, '/');
    $apiKey = $SUPABASE_KEY;

    // Ensure URL has /rest/v1/
    if (strpos($baseUrl, '/rest/v1') === false) {
        $url = $baseUrl . "/rest/v1/" . ltrim($endpoint, '/');
    } else {
        $url = $baseUrl . "/" . ltrim($endpoint, '/');
    }

    $ch = curl_init($url);
    $headers = [
        "apikey: $apiKey",
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json",
        "Prefer: return=representation"
    ];
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if ($data !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

function supabase_upload($bucket, $file) {
    global $SUPABASE_URL, $SUPABASE_KEY;
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . "." . $ext;
    $url = rtrim($SUPABASE_URL, '/') . "/storage/v1/object/" . $bucket . "/" . $filename;
    
    $ch = curl_init($url);   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($file['tmp_name']));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_KEY",
        "Authorization: Bearer $SUPABASE_KEY",
        "Content-Type: " . $file['type'],
        "x-upsert: true"
    ]);
    
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($status == 200 || $status == 201) {
        return rtrim($SUPABASE_URL, '/') . "/storage/v1/object/public/" . $bucket . "/" . $filename;
    }
    return null;
}
?>
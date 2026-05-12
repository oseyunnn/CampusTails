<?php
// Replace these with your actual Supabase details from the URI string
$host = "db.your-project-id.supabase.co";
$port = "5432";
$dbname = "postgres"; // Default Supabase DB name
$user = "postgres.your-username";
$password = "your-database-password";

try {
    // Create the Data Source Name (DSN) for PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    
    // Create a PDO connection
    $conn = new PDO($dsn);
    
    // Set error mode to exception to catch errors
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
    
    // Success (Optional: remove this line once verified)
    // echo "Connected to Supabase successfully!";

} catch (PDOException $e) {
    die("Could not connect to the Supabase database: " . $e->getMessage());
}
?>
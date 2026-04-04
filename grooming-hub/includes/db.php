<?php
// Database connection file - db.php
// SECURITY: Store credentials in environment variables in production

$host = "localhost";
$user = "root";
$pass = "";  // SET A PASSWORD IN PRODUCTION!
$dbname = "grooming_hub";

try {
    // Create PDO connection with error mode
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch(PDOException $e) {
    // Don't expose error details to users
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection error. Please contact support.");
}
?>

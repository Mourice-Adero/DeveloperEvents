<?php
// Database configuration
$db_host = 'localhost:3307'; // Change this to your database host
$db_name = 'developerevents'; // Change this to your database name
$db_user = 'root'; // Change this to your database username
$db_password = ''; // Change this to your database password

// Establish database connection using PDO
try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    // Set PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Display error message if connection fails
    die("Connection failed: " . $e->getMessage());
}
?>

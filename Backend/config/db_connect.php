<?php
$host = "localhost";  
$username = "root";   
$password = "";       
$database = "digital_wallet";  

try {
    // Create connection
    $conn = new mysqli($host, $username, $password, $database);
    
    // Check if connection failed
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>

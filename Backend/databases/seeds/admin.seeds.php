<?php
include __DIR__ . '../../../config/db_connect.php';

$adminEmail = "adminwallet@gmail.com";
$adminPassword = "adminWallet"; // Plaintext password
$hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
$adminRole = "admin";
$verificationStatus = "verified";

// Check if the admin user already exists
$query = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $adminEmail);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $insertQuery = "INSERT INTO users (name, email, phone, password, role, verification_status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($insertQuery);
    $adminName = "Admin";
    $adminPhone = "00000000"; // Placeholder phone number
    $stmtInsert->bind_param("ssssss", $adminName, $adminEmail, $adminPhone, $hashedPassword, $adminRole, $verificationStatus);
    
    if ($stmtInsert->execute()) {
        echo "✅ Admin user inserted successfully.";
    } else {
        echo "Error inserting admin user: " . $stmtInsert->error;
    }
} else {
    echo "⚠️ Admin user already exists.";
}

$stmt->close();
$conn->close();
?>

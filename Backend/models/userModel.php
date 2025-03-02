<?php
include __DIR__ . '/../config/db_connect.php';

class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registerUser($fullname, $email, $phone, $password, $address, $documentPath) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert User Data
        $query = "INSERT INTO users (name, email, phone, password, address, verification_status) VALUES (?, ?, ?, ?, ?, 'pending')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $fullname, $email, $phone, $hashedPassword, $address);

        if ($stmt->execute()) {
            $userId = $stmt->insert_id; // Get last inserted user ID

            
            $queryVerification = "INSERT INTO identity_verifications (user_id, document_path, status) VALUES (?, ?, 'pending')";
            $stmtVerification = $this->conn->prepare($queryVerification);
            $stmtVerification->bind_param("is", $userId, $documentPath);

            if ($stmtVerification->execute()) {
                return ['success' => true, 'message' => 'Registration successful. Please wait for verification.'];
            }
        }
        return ['success' => false, 'message' => 'Registration failed.'];
    }
}
?>

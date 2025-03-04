<?php
include __DIR__ . '/../config/db_connect.php';


class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registerUser($fullname, $email, $phone, $password, $address, $documentPath) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (name, email, phone, password, address) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $fullname, $email, $phone, $hashedPassword, $address);

        if ($stmt->execute()) {
            $userId = $stmt->insert_id;

            // Insert into identity_verifications table
            $queryVerification = "INSERT INTO identity_verifications (user_id, document_path, status) VALUES (?, ?, 'pending')";
            $stmtVerification = $this->conn->prepare($queryVerification);
            $stmtVerification->bind_param("is", $userId, $documentPath);

            if ($stmtVerification->execute()) {
                return ['status' => 'success', 'message' => 'Registration successful. Please wait for verification.'];
            }
        }
        return ['status' => 'error', 'message' => 'Registration failed.'];
    }

    public function loginUser($email, $password) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
    
            if (password_verify($password, $user['password'])) {
                require_once __DIR__ . '/../utils/authJWT.php';
                $token = authJWT($user); 
                
                $role = isset($user['role']) ? $user['role'] : 'client';
    
                return [
                    'status' => 'success',
                    'role' => $role,
                    'token' => $token, 
                    'message' => 'Login successful.'
                ];
            } else {
                return ['status' => 'error', 'message' => 'Incorrect password.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'No user found with that email.'];
        }
    }
    

    // Update user profile
    public function updateUserProfile($userId, $fullname, $address, $phone) {
        $query = "UPDATE users SET name = ?, address = ?, phone = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $fullname, $address, $phone, $userId);

        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Profile updated successfully.'];
        }
        return ['status' => 'error', 'message' => 'Failed to update profile.'];
    }

    // Get user profile
    public function getUserProfile($userId) {
        $query = "SELECT name, email, phone, address FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return ['status' => 'success', 'user' => $result->fetch_assoc()];
        }
        return ['status' => 'error', 'message' => 'User not found.'];
    }
}
?>

<?php
require_once '../config/db_connect.php';

class UserModel {
    public function registerUser($name, $email, $phone, $password, $address, $id_document) {
        global $conn;

        // Check if email already exists
        $email_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $email_check->bind_param("s", $email);
        $email_check->execute();
        $email_check->store_result();
        if ($email_check->num_rows > 0) {
            return "Email already exists.";
        }
        
        // Check if phone number already exists
        $phone_check = $conn->prepare("SELECT id FROM users WHERE phone = ?");
        $phone_check->bind_param("s", $phone);
        $phone_check->execute();
        $phone_check->store_result();
        if ($phone_check->num_rows > 0) {
            return "Phone number already exists.";
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role, verification_status)
         VALUES (?, ?, ?, ?, 'client', 'pending')");
        $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // Handle file upload for ID document
            $document_path = 'uploads/' . $id_document['name'];
            move_uploaded_file($id_document['tmp_name'], $document_path);

            // Insert the identity verification record
            $verification_stmt = $conn->prepare("INSERT INTO identity_verifications (user_id, document_type, document_path) VALUES (?, ?, ?)");
            $document_type = 'passport'; // You can modify this depending on the form input
            $verification_stmt->bind_param("iss", $user_id, $document_type, $document_path);
            $verification_stmt->execute();

            return "User registered successfully.";
        } else {
            return "Error registering user: " . $conn->error;
        }
    }
}
?>

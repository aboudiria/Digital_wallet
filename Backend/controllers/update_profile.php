<?php
session_start();
include __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../models/UserModel.php';
include __DIR__ . '/../utils/authJWT.php';

header('Content-Type: application/json');

// Check if the Authorization header contains the JWT token
if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    echo json_encode(['status' => 'error', 'message' => 'Authorization header missing.']);
    exit;
}

$jwt = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']); // Extract the token

error_log("Authorization Header: " . $_SERVER['HTTP_AUTHORIZATION']); // Log the token for debugging

try {
    // Decode the JWT token
    $decoded = JWT::decode($jwt, 'JWT_SECRET_KEY'); // Ensure 'JWT_SECRET_KEY' is correct

    if (isset($decoded->data->id)) {
        $userId = $decoded->data->id;

        // Fetch user details from POST data
        $fullname = trim($_POST['fullname']);
        $address = trim($_POST['address']);
        $phone = trim($_POST['phone']);

        if (empty($fullname) || empty($address) || empty($phone)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit;
        }

        // Update profile
        $userModel = new UserModel($conn);
        $response = $userModel->updateUserProfile($userId, $fullname, $address, $phone);
        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid token.']);
    }
} catch (Exception $e) {
    error_log("JWT Decode Error: " . $e->getMessage()); // Log the error
    echo json_encode(['status' => 'error', 'message' => 'Invalid token.']);
}
?>

<?php
session_start();
include __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../models/UserModel.php';
include __DIR__ . '/../utils/authJWT.php';

header('Content-Type: application/json');
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(['success' => false, 'message' => 'Authorization header missing']);
    exit;
}
$jwt = str_replace('Bearer ', '', $headers['Authorization']);
 

error_log("Authorization Header: " . $_SERVER['HTTP_AUTHORIZATION']); 

try {
    $secret_key = "JWT_SECRET_KEY"; 
    
    $decoded = JWT ::decode($jwt, new Key($secret_key, 'HS256'));

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

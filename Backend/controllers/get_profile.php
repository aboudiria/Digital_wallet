<?php
// Include necessary files
include __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../models/UserModel.php';
include __DIR__ . '/../utils/authJWT.php';

header('Content-Type: application/json');

if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    echo json_encode(['status' => 'error', 'message' => 'Authorization header missing.']);
    exit;
}

$jwt = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);

error_log("Authorization Header: " . $_SERVER['HTTP_AUTHORIZATION']);

try {
    $decoded = JWT::decode($jwt, 'JWT_SECRET_KEY');

    if (isset($decoded->data->id)) {
        $userId = $decoded->data->id;

        $userModel = new UserModel($conn);
        $response = $userModel->getUserProfile($userId);

        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid token.']);
    }
} catch (Exception $e) {
    error_log("JWT Decode Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Invalid token.']);
}
?>

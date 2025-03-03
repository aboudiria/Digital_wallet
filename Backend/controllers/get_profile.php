<?php
session_start();
include __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../models/UserModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated.']);
    exit;
}

$userModel = new UserModel($conn);
$response = $userModel->getUserProfile($_SESSION['user_id']);
echo json_encode($response);
?>

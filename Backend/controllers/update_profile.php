<?php
session_start();
include __DIR__ . '/../config/db_connect.php';
include __DIR__ . '/../models/UserModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated.']);
    exit;
}

$userId = $_SESSION['user_id'];
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
?>

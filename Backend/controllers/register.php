<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json'); 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../config/db_connect.php";
require_once "../models/userModel.php";

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

// Retrieve input data
$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';
$address = $_POST['address'] ?? '';

// Validate required fields
if (!$fullname || !$email || !$phone || !$password || !$confirmPassword || !$address) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
    exit();
}

// Validate passwords match
if ($password !== $confirmPassword) {
    echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
    exit();
}

// Process file upload
$documentPath = null;
if (!empty($_FILES['id_document']['name'])) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileTmpPath = $_FILES['id_document']['tmp_name'];
    $originalName = basename($_FILES['id_document']['name']);
    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, PNG, and PDF allowed.']);
        exit();
    }

    $newFileName = uniqid('id_', true) . '.' . $fileExtension;
    $destPath = $uploadDir . $newFileName;

    if (!move_uploaded_file($fileTmpPath, $destPath)) {
        echo json_encode(['status' => 'error', 'message' => 'Error uploading file.']);
        exit();
    }
    $documentPath = 'uploads/' . $newFileName;
}

// Register user
$userModel = new UserModel($conn);
$result = $userModel->registerUser($fullname, $email, $phone, $password, $address, $documentPath);

// Return response as JSON
echo json_encode($result);
?>

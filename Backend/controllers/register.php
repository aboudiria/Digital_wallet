<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

include '../config/db_connect.php';
include '../models/UserModel.php';

$userModel = new UserModel($conn);
if($_SERVER['CONTENT_TYPE']==='application/json'){
    $data=json_decode(file_get_contents('php://input'),true);
} else{
    $data= $_POST;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate Form Data
    $fullname = $_POST['fullname'] ??'';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$fullname || !$email || !$phone || !$address || !$password || !$confirm_password || !isset($_FILES['id_document'])) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit();
    }

    if ($password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit();
    }

    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo json_encode(['success' => false, 'message' => 'Weak password. Must include letters, numbers, and special characters.']);
        exit();
    }

    $documentPath = '../uploads/' . basename($_FILES['id_document']['name']);

    if ($_FILES['id_document']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'File upload error.']);
        exit();
    }
    
    if (!move_uploaded_file($_FILES['id_document']['tmp_name'], $documentPath)) {
        echo json_encode(['success' => false, 'message' => 'File upload failed.']);
        exit();
    }
    
    
    $response = $userModel->registerUser($fullname, $email, $phone, $password, $address, $documentPath);

    echo json_encode($response);
}

$conn->close();
?>

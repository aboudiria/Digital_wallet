<?php
require_once '../models/userModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords match (This can be redundant since the frontend already checks it)
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Validate password strength (PHP validation in case someone bypasses JS)
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo "Password must be at least 8 characters long, include a special character, a digit, and an alphabetic letter.";
        exit;
    }

    // Handle the file upload for ID document
    $id_document = $_FILES['id_document'];
    if ($id_document['error'] != 0) {
        echo "Error uploading document.";
        exit;
    }

    
    $userModel = new UserModel();
    $response = $userModel->registerUser($name, $email, $phone, $password, $address, $id_document);
    echo $response;
}
?>

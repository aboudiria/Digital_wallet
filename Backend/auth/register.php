<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Enable mysqli exceptions for error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once "../config/db.php"; // Assumes $conn is created using mysqli_connect in db.php

try {
    // Retrieve POST data
    $data = $_POST;

    // Validate required fields
    if (empty($data['fullname']) || empty($data['email']) || empty($data['phone']) || empty($data['password']) || empty($data['confirm_password'])) {
        http_response_code(400);
        echo json_encode(["message" => "All fields are required."]);
        exit;
    }

    // Check if passwords match
    if ($data['password'] !== $data['confirm_password']) {
        http_response_code(400);
        echo json_encode(["message" => "Passwords do not match."]);
        exit;
    }

    // Validate password strength
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/', $data['password'])) {
        http_response_code(400);
        echo json_encode(["message" => "Password must be at least 8 characters long and include a digit, a special character, and an alphabetic character."]);
        exit;
    }

    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid email format."]);
        exit;
    }

    // Check if email already exists
    $query = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $data['email']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        http_response_code(400);
        echo json_encode(["message" => "Email already exists."]);
        exit;
    }
    mysqli_stmt_close($stmt);

    // Hash the password
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // Handle optional ID document upload
    $id_document = null;
    if (!empty($_FILES['id_document']['name'])) {
        $target_dir = "../uploads/";
        $id_document = basename($_FILES["id_document"]["name"]);
        $target_file = $target_dir . $id_document;

        if (!move_uploaded_file($_FILES["id_document"]["tmp_name"], $target_file)) {
            http_response_code(500);
            echo json_encode(["message" => "File upload failed."]);
            exit;
        }
    }

    // Insert user into database
    $query = "INSERT INTO users (fullname, email, phone, password, id_document) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $data['fullname'], $data['email'], $data['phone'], $hashedPassword, $id_document);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    http_response_code(201);
    echo json_encode(["message" => "User registered successfully."]);
} catch (mysqli_sql_exception $exception) {
    http_response_code(500);
    echo json_encode(["message" => "Registration failed: " . $exception->getMessage()]);
}
?>

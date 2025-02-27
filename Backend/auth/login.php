<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/db.php";

// Retrieve POST data
$data = $_POST;

if (empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(["message" => "Email and password are required."]);
    exit;
}

// Check if user exists
$query = "SELECT id, fullname, email, phone, password, is_verified FROM users WHERE email = :email LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindParam(":email", $data['email']);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    http_response_code(401);
    echo json_encode(["message" => "Invalid credentials."]);
    exit;
}

// Fetch user data
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verify password
if (!password_verify($data['password'], $user['password'])) {
    http_response_code(401);
    echo json_encode(["message" => "Invalid credentials."]);
    exit;
}

// Return user data
http_response_code(200);
echo json_encode([
    "message" => "Login successful.",
    "user" => [
        "id" => $user['id'],
        "fullname" => $user['fullname'],
        "email" => $user['email'],
        "phone" => $user['phone'],
        "is_verified" => $user['is_verified']
    ]
]);
?>

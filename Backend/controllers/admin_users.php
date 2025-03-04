<?php
// File: api/admin_users.php

require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(['success' => false, 'message' => 'Authorization header missing']);
    exit;
}
$jwt = str_replace('Bearer ', '', $headers['Authorization']);

try {
    $secret_key = "JWT_SECRET_KEY"; 
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    
    if (!isset($decoded->data->role) || $decoded->data->role !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Invalid token']);
    exit;
}


$query = "SELECT id, name, email, verification_status FROM users";
$result = $conn->query($query);
$users = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode(['success' => true, 'users' => $users]);
?>

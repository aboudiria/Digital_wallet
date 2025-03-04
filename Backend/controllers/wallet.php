<?php

require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../models/WalletModel.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(['success' => false, 'message' => 'Authorization header missing.']);
    exit;
}
$jwt = str_replace('Bearer ', '', $headers['Authorization']);

try {
    $secret_key = "JWT_SECRET_KEY"; 
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    
    if (!isset($decoded->data->id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid token.']);
        exit;
    }
    $userId = $decoded->data->id;
} catch (Exception $e) {
    error_log("JWT Decode Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Invalid token.']);
    exit;
}

$walletModel = new WalletModel($conn);
$overview = $walletModel->getWalletOverview($userId);

echo json_encode(array_merge(['success' => true], $overview));
?>

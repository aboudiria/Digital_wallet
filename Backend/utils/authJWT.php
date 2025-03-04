<?php
// backend/utils/authJWT.php

// Autoload the JWT library if using Composer
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;

function authJWT($user) {
    $secret_key = "JWT_SECRET_KEY";
    $issuer_claim = "http://localhost";
    $audience_claim = "http://localhost"; 
    $issuedat_claim = time();             
    $notbefore_claim = $issuedat_claim;      
    $expire_claim = $issuedat_claim + 3600;    

    $token = [
        "iss"  => $issuer_claim,
        "aud"  => $audience_claim,
        "iat"  => $issuedat_claim,
        "nbf"  => $notbefore_claim,
        "exp"  => $expire_claim,
        "data" => [
            "id"    => $user['id'],
            "email" => $user['email'],
            "role"  => isset($user['role']) ? $user['role'] : 'client'
        ]
    ];

    // Encode the token
    $jwt = JWT::encode($token, $secret_key, 'HS256');
    return $jwt;
}
?>

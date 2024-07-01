<?php
require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "betterstackedthensticked";

function generateJWT($userId, $userRole, $secret_key) {
    $payload = array(
        "iat" => time(),
        "nbf" => time(),
        "exp" => time() + (60 * 60 * 24), // Token expires in 1 day
        "data" => array(
            "userId" => $userId,
            "userRole" => $userRole
        )
    );

    $jwt = JWT::encode($payload, $secret_key, 'HS256');
    return $jwt;
}

function verifyJWT($jwt) {
    global $secret_key;
    try {
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        return $decoded->data;
    } catch (Exception $e) {
        return null;
    }
}

function authenticateRequest() {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $jwt = trim(str_replace('Bearer ', '', $headers['Authorization']));
        $userData = verifyJWT($jwt);
        if ($userData) {
            return $userData;
        }
    }
    http_response_code(401);
    echo json_encode(array("message" => "Unauthorized"));
    exit;
}
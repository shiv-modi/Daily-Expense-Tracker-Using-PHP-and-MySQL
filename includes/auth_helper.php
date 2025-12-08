<?php
include_once(__DIR__ . '/jwt.php');

function getAuthenticatedUserId() {
    $jwtUserId = JWT::getUserId();
    if ($jwtUserId) {
        return $jwtUserId;
    }
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!empty($_SESSION['detsuid'])) {
        return $_SESSION['detsuid'];
    }
    
    return null;
}

function requireAuthentication() {
    $userId = getAuthenticatedUserId();
    
    if (!$userId) {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized - Please login']);
        exit;
    }
    
    return $userId;
}
?>

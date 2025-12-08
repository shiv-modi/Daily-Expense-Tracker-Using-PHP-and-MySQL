<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include_once('../database.php');
include_once('../auth_helper.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = requireAuthentication();
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid lending ID']);
        exit;
    }
    
    $checkQuery = mysqli_query($db, "SELECT id FROM lending WHERE id='$id' AND UserId='$userid'");
    if (mysqli_num_rows($checkQuery) === 0) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Lending record not found']);
        exit;
    }
    
    $deleteQuery = mysqli_query($db, "DELETE FROM lending WHERE id='$id' AND UserId='$userid'");
    
    if ($deleteQuery) {
        echo json_encode(['status' => 'success', 'message' => 'Lending record deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete lending record']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>

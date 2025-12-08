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

    $id = $_POST['id'] ?? 0;
    $type = $_POST['type'] ?? '';

    if (empty($id) || empty($type)) {
        echo json_encode(['status' => 'error', 'message' => 'ID and Type are required']);
        exit;
    }

    $table = '';
    if (strtolower($type) === 'expense') {
        $table = 'tblexpense';
    } elseif (strtolower($type) === 'income') {
        $table = 'tblincome';
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid transaction type']);
        exit;
    }

    $stmt = $db->prepare("DELETE FROM $table WHERE ID = ? AND UserId = ?");
    $stmt->bind_param("ii", $id, $userid);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => ucfirst($type) . ' deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $db->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

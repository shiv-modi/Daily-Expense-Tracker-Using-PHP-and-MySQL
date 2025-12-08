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

    $name = $_POST['name'] ?? '';
    $date_of_lending = $_POST['date'] ?? '';
    $amount = $_POST['amount'] ?? 0;
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? 'pending';

    if (empty($name) || empty($date_of_lending) || empty($amount)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    $stmt = $db->prepare("INSERT INTO lending (name, UserId, date_of_lending, amount, description, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $name, $userid, $date_of_lending, $amount, $description, $status);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'New lending record created successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

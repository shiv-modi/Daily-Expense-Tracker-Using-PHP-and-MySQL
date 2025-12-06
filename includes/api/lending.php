<?php
session_start();
header('Content-Type: application/json');
include_once('../database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_SESSION['detsuid'])) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $userid = $_SESSION['detsuid'];
    $name = $_POST['name'] ?? '';
    $date_of_lending = $_POST['date'] ?? '';
    $amount = $_POST['amount'] ?? 0;
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? 'pending';

    if (empty($name) || empty($date_of_lending) || empty($amount)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    // Use prepared statements for security
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

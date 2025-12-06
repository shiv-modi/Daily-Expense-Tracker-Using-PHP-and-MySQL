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
    $incomeId = $_POST['id'] ?? 0;

    if (empty($incomeId)) {
        echo json_encode(['status' => 'error', 'message' => 'Income ID is required']);
        exit;
    }

    $stmt = $db->prepare("DELETE FROM tblincome WHERE ID = ? AND UserId = ?");
    $stmt->bind_param("ii", $incomeId, $userid);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Income deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

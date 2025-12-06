<?php
session_start();
header('Content-Type: application/json');
include_once('../database.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (empty($_SESSION['detsuid'])) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $userid = $_SESSION['detsuid'];
    $mode = $_GET['mode'] ?? 'expense';
    
    if (!in_array($mode, ['expense', 'income'])) {
        $mode = 'expense';
    }

    $stmt = $db->prepare("SELECT CategoryId, CategoryName, Mode FROM tblcategory WHERE UserId = ? AND Mode = ? ORDER BY CategoryName");
    $stmt->bind_param("is", $userid, $mode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    echo json_encode(['status' => 'success', 'data' => $categories]);
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

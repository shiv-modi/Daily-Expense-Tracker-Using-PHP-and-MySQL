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
    $categoryName = $_POST['category-name'] ?? '';
    $mode = ($_POST['mode'] === 'income') ? 'income' : 'expense';

    if (empty($categoryName)) {
        echo json_encode(['status' => 'error', 'message' => 'Category name is required']);
        exit;
    }

    $stmt = $db->prepare("INSERT INTO tblcategory (CategoryName, Mode, UserId) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $categoryName, $mode, $userid);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Category added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

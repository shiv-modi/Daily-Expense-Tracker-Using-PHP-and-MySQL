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
    $incomeDate = $_POST['incomeDate'] ?? '';
    $categoryId = $_POST['category'] ?? '';
    $incomeAmount = $_POST['incomeAmount'] ?? 0;
    $description = $_POST['description'] ?? '';

    if (empty($incomeDate) || empty($categoryId) || empty($incomeAmount)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    // Replicating original logic: INSERT ... SELECT ... FROM tblcategory
    $stmt = $db->prepare("INSERT INTO tblincome (UserId, IncomeDate, CategoryId, category, IncomeAmount, Description) SELECT ?, ?, CategoryId, CategoryName, ?, ? FROM tblcategory WHERE CategoryId = ?");
    $stmt->bind_param("isiss", $userid, $incomeDate, $incomeAmount, $description, $categoryId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Income added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

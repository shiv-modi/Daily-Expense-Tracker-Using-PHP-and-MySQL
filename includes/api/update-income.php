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
    $incomeId = $_POST['incomeid'] ?? 0;
    $incomeDate = $_POST['incomeDate'] ?? '';
    $category = $_POST['category'] ?? '';
    $amount = $_POST['incomeAmount'] ?? 0;
    $description = $_POST['description'] ?? '';

    if (empty($incomeId) || empty($incomeDate) || empty($category) || empty($amount)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    $catStmt = $db->prepare("SELECT CategoryName FROM tblcategory WHERE CategoryId = ?");
    $catStmt->bind_param("i", $category);
    $catStmt->execute();
    $catResult = $catStmt->get_result();
    $catRow = $catResult->fetch_assoc();
    $categoryName = $catRow ? $catRow['CategoryName'] : '';
    $catStmt->close();

    $stmt = $db->prepare("UPDATE tblincome SET IncomeDate = ?, CategoryId = ?, category = ?, IncomeAmount = ?, Description = ? WHERE ID = ? AND UserId = ?");
    $stmt->bind_param("sisssii", $incomeDate, $category, $categoryName, $amount, $description, $incomeId, $userid);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Income updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

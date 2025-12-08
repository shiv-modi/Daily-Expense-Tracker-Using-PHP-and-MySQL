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

    $incomeDate = $_POST['incomeDate'] ?? '';
    $categoryId = $_POST['category'] ?? '';
    $incomeAmount = $_POST['incomeAmount'] ?? 0;
    $description = $_POST['description'] ?? '';

    if (empty($incomeDate) || empty($categoryId) || empty($incomeAmount)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

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

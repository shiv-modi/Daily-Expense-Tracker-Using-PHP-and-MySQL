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

    $dateexpense = $_POST['dateexpense'] ?? '';
    $category = $_POST['category'] ?? '';
    $Description = $_POST['category-description'] ?? '';
    $costitem = $_POST['costitem'] ?? 0;

    if (empty($dateexpense) || empty($category) || empty($costitem)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    $stmt = $db->prepare("INSERT INTO tblexpense (UserId, ExpenseDate, CategoryId, category, ExpenseCost, Description) SELECT ?, ?, CategoryId, CategoryName, ?, ? FROM tblcategory WHERE CategoryId = ?");
    $stmt->bind_param("isiss", $userid, $dateexpense, $costitem, $Description, $category);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Expense added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

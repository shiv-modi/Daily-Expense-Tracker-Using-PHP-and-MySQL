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
    $dateexpense = $_POST['dateexpense'] ?? '';
    $category = $_POST['category'] ?? '';
    $Description = $_POST['category-description'] ?? '';
    $costitem = $_POST['costitem'] ?? 0;

    if (empty($dateexpense) || empty($category) || empty($costitem)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    // Use prepared statements
    // Note: The original query selected CategoryName from tblcategory. We can do that with a subquery or join, or just trust the ID if the name isn't strictly needed in the expense table (normalization).
    // However, the original query was: INSERT INTO tblexpense ... SELECT ... FROM tblcategory WHERE CategoryId = '$category'
    // We will replicate that logic securely.

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

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

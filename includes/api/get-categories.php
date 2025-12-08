<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include_once('../database.php');
include_once('../auth_helper.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userid = requireAuthentication();

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
        $categories[] = [
            'categoryid' => $row['CategoryId'],
            'categoryname' => $row['CategoryName']
        ];
    }
    
    echo json_encode(['status' => 'success', 'data' => $categories]);
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

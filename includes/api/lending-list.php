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
    
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $status = isset($_GET['status']) ? strtolower($_GET['status']) : 'all';
    $offset = ($page - 1) * $limit;
    
    $limit = min(max($limit, 1), 100);
    $page = max($page, 1);
    
    $statusCondition = "";
    if ($status === 'pending') {
        $statusCondition = " AND status='pending'";
    } elseif ($status === 'received') {
        $statusCondition = " AND status='received'";
    }
    
    $query = "SELECT * FROM lending WHERE UserId='$userid' $statusCondition ORDER BY date_of_lending DESC LIMIT $limit OFFSET $offset";
    $countQuery = "SELECT COUNT(*) as total FROM lending WHERE UserId='$userid' $statusCondition";
    
    $result = mysqli_query($db, $query);
    $lending = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $lending[] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'date_of_lending' => $row['date_of_lending'],
                'amount' => (float)$row['amount'],
                'description' => $row['description'],
                'status' => $row['status'],
                'created_at' => $row['current_time']
            ];
        }
    }
    
    $countResult = mysqli_query($db, $countQuery);
    $countData = mysqli_fetch_assoc($countResult);
    $total = (int)$countData['total'];
    $total_pages = ceil($total / $limit);
    
    $pendingQuery = mysqli_query($db, "SELECT SUM(amount) as total FROM lending WHERE UserId='$userid' AND status='pending'");
    $pendingData = mysqli_fetch_assoc($pendingQuery);
    $total_pending = (float)($pendingData['total'] ?? 0);
    
    $receivedQuery = mysqli_query($db, "SELECT SUM(amount) as total FROM lending WHERE UserId='$userid' AND status='received'");
    $receivedData = mysqli_fetch_assoc($receivedQuery);
    $total_received = (float)($receivedData['total'] ?? 0);
    
    echo json_encode([
        'status' => 'success',
        'data' => [
            'lending' => $lending,
            'summary' => [
                'total_pending' => $total_pending,
                'total_received' => $total_received
            ],
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_records' => $total,
                'limit' => $limit
            ]
        ]
    ]);
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>

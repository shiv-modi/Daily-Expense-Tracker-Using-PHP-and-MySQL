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
    
    $reportType = isset($_GET['type']) ? strtolower($_GET['type']) : 'expense';
    $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
    $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
    
    $startDate = $db->real_escape_string($startDate);
    $endDate = $db->real_escape_string($endDate);
    
    $response = [
        'status' => 'success',
        'report_type' => $reportType,
        'date_range' => [
            'start' => $startDate,
            'end' => $endDate
        ]
    ];
    
    if ($reportType === 'expense') {
        $query = "SELECT e.ID, e.ExpenseDate as date, c.CategoryName as category, e.ExpenseCost as amount, e.Description 
                  FROM tblexpense e 
                  LEFT JOIN tblcategory c ON e.CategoryId = c.CategoryId 
                  WHERE e.UserId='$userid' AND e.ExpenseDate BETWEEN '$startDate' AND '$endDate' 
                  ORDER BY e.ExpenseDate DESC";
        
        $result = mysqli_query($db, $query);
        $data = [];
        $totalAmount = 0;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'id' => (int)$row['ID'],
                'date' => $row['date'],
                'category' => $row['category'],
                'amount' => (float)$row['amount'],
                'description' => $row['Description']
            ];
            $totalAmount += (float)$row['amount'];
        }
        
        $response['data'] = $data;
        $response['summary'] = [
            'total_records' => count($data),
            'total_amount' => $totalAmount
        ];
        
    } elseif ($reportType === 'income') {
        $query = "SELECT i.ID, i.IncomeDate as date, c.CategoryName as category, i.IncomeAmount as amount, i.Description 
                  FROM tblincome i 
                  LEFT JOIN tblcategory c ON i.CategoryId = c.CategoryId 
                  WHERE i.UserId='$userid' AND i.IncomeDate BETWEEN '$startDate' AND '$endDate' 
                  ORDER BY i.IncomeDate DESC";
        
        $result = mysqli_query($db, $query);
        $data = [];
        $totalAmount = 0;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'id' => (int)$row['ID'],
                'date' => $row['date'],
                'category' => $row['category'],
                'amount' => (float)$row['amount'],
                'description' => $row['Description']
            ];
            $totalAmount += (float)$row['amount'];
        }
        
        $response['data'] = $data;
        $response['summary'] = [
            'total_records' => count($data),
            'total_amount' => $totalAmount
        ];
        
    } elseif ($reportType === 'pending') {
        $query = "SELECT id, name, date_of_lending as date, amount, description, status 
                  FROM lending 
                  WHERE UserId='$userid' AND status='pending' AND date_of_lending BETWEEN '$startDate' AND '$endDate' 
                  ORDER BY date_of_lending DESC";
        
        $result = mysqli_query($db, $query);
        $data = [];
        $totalAmount = 0;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'date' => $row['date'],
                'amount' => (float)$row['amount'],
                'description' => $row['description'],
                'status' => $row['status']
            ];
            $totalAmount += (float)$row['amount'];
        }
        
        $response['data'] = $data;
        $response['summary'] = [
            'total_records' => count($data),
            'total_pending' => $totalAmount
        ];
        
    } elseif ($reportType === 'received') {
        $query = "SELECT id, name, date_of_lending as date, amount, description, status 
                  FROM lending 
                  WHERE UserId='$userid' AND status='received' AND date_of_lending BETWEEN '$startDate' AND '$endDate' 
                  ORDER BY date_of_lending DESC";
        
        $result = mysqli_query($db, $query);
        $data = [];
        $totalAmount = 0;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'date' => $row['date'],
                'amount' => (float)$row['amount'],
                'description' => $row['description'],
                'status' => $row['status']
            ];
            $totalAmount += (float)$row['amount'];
        }
        
        $response['data'] = $data;
        $response['summary'] = [
            'total_records' => count($data),
            'total_received' => $totalAmount
        ];
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid report type. Use: expense, income, pending, or received']);
        exit;
    }
    
    echo json_encode($response);
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>

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
    
    $tdate = date('Y-m-d');
    $ydate = date('Y-m-d', strtotime("-1 days"));
    $monthdate = date("Y-m-d", strtotime("-1 month"));
    
    $todayQuery = mysqli_query($db, "SELECT sum(ExpenseCost) as todaysexpense FROM tblexpense WHERE ExpenseDate='$tdate' AND UserId='$userid'");
    $todayResult = mysqli_fetch_array($todayQuery);
    $sum_today_expense = $todayResult['todaysexpense'] ?? 0;
    
    $yesterdayQuery = mysqli_query($db, "SELECT sum(ExpenseCost) as yesterdayexpense FROM tblexpense WHERE ExpenseDate='$ydate' AND UserId='$userid'");
    $yesterdayResult = mysqli_fetch_array($yesterdayQuery);
    $sum_yesterday_expense = $yesterdayResult['yesterdayexpense'] ?? 0;
    
    $monthlyQuery = mysqli_query($db, "SELECT sum(ExpenseCost) as monthlyexpense FROM tblexpense WHERE ExpenseDate BETWEEN '$monthdate' AND '$tdate' AND UserId='$userid'");
    $monthlyResult = mysqli_fetch_array($monthlyQuery);
    $sum_monthly_expense = $monthlyResult['monthlyexpense'] ?? 0;
    
    $totalQuery = mysqli_query($db, "SELECT sum(ExpenseCost) as totalexpense FROM tblexpense WHERE UserId='$userid'");
    $totalResult = mysqli_fetch_array($totalQuery);
    $sum_total_expense = $totalResult['totalexpense'] ?? 0;
    
    $totalIncomeQuery = mysqli_query($db, "SELECT sum(IncomeAmount) as totalincome FROM tblincome WHERE UserId='$userid'");
    $totalIncomeResult = mysqli_fetch_array($totalIncomeQuery);
    $sum_total_income = $totalIncomeResult['totalincome'] ?? 0;
    
    $userQuery = mysqli_query($db, "SELECT name, email FROM users WHERE id='$userid'");
    $userResult = mysqli_fetch_array($userQuery);
    $userName = $userResult['name'] ?? '';
    $userEmail = $userResult['email'] ?? '';
    
    $chartQuery = mysqli_query($db, "SELECT ExpenseDate, SUM(ExpenseCost) as total_cost FROM tblexpense WHERE UserId='$userid' AND ExpenseDate > DATE_SUB(NOW(), INTERVAL 30 day) GROUP BY ExpenseDate ORDER BY ExpenseDate ASC");
    $chartData = [];
    $chartLabels = [];
    while ($row = mysqli_fetch_array($chartQuery)) {
        $chartData[] = (float)$row['total_cost'];
        $chartLabels[] = date('Y-m-d', strtotime($row['ExpenseDate']));
    }
    
    $categoryQuery = mysqli_query($db, "SELECT c.CategoryName as category, SUM(e.ExpenseCost) as total_expense FROM tblexpense e JOIN tblcategory c ON e.CategoryId = c.CategoryId WHERE e.UserId='$userid' GROUP BY c.CategoryName ORDER BY total_expense DESC LIMIT 10");
    $categories = [];
    while ($row = mysqli_fetch_array($categoryQuery)) {
        $categories[] = [
            'category' => $row['category'],
            'total_expense' => (float)$row['total_expense']
        ];
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => [
            'user' => [
                'name' => $userName,
                'email' => $userEmail
            ],
            'today_expense' => (float)$sum_today_expense,
            'yesterday_expense' => (float)$sum_yesterday_expense,
            'monthly_expense' => (float)$sum_monthly_expense,
            'total_expense' => (float)$sum_total_expense,
            'total_income' => (float)$sum_total_income,
            'balance' => (float)$sum_total_income - (float)$sum_total_expense,
            'chart' => [
                'labels' => $chartLabels,
                'data' => $chartData
            ],
            'categories' => $categories
        ]
    ]);
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>

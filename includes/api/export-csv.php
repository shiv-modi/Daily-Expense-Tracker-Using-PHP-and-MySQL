<?php
include_once('../database.php');
include_once('../auth_helper.php');

$userid = getAuthenticatedUserId();

if (!$userid) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$type = $_GET['type'] ?? 'all';
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

$data = [];

if ($type === 'all' || $type === 'expense') {
    $sql = "SELECT ExpenseDate as date, Description as particulars, ExpenseCost as expense, 0 as income, category, 'no' as is_lending FROM tblexpense WHERE UserId = ?";
    $types = "i";
    $params = [$userid];
    
    if ($startDate && $endDate) {
        $sql .= " AND ExpenseDate BETWEEN ? AND ?";
        $types .= "ss";
        $params[] = $startDate;
        $params[] = $endDate;
    }
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}

if ($type === 'all' || $type === 'income') {
    $sql = "SELECT IncomeDate as date, Description as particulars, 0 as expense, IncomeAmount as income, category, 'no' as is_lending FROM tblincome WHERE UserId = ?";
    $types = "i";
    $params = [$userid];
    
    if ($startDate && $endDate) {
        $sql .= " AND IncomeDate BETWEEN ? AND ?";
        $types .= "ss";
        $params[] = $startDate;
        $params[] = $endDate;
    }
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}

if ($type === 'all' || $type === 'lending') {
    $sql = "SELECT date_of_lending as date, name as particulars, 
            CASE WHEN status = 'pending' THEN amount ELSE 0 END as expense,
            CASE WHEN status = 'received' THEN amount ELSE 0 END as income,
            'Lending' as category, 'yes' as is_lending 
            FROM lending WHERE UserId = ?";
    $types = "i";
    $params = [$userid];
    
    if ($startDate && $endDate) {
        $sql .= " AND date_of_lending BETWEEN ? AND ?";
        $types .= "ss";
        $params[] = $startDate;
        $params[] = $endDate;
    }
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}

usort($data, function($a, $b) {
    return strcmp($b['date'], $a['date']);
});

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="expenditure_export_' . date('Y-m-d_His') . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

fputcsv($output, ['Date', 'Particulars', 'expense', 'income', 'category', 'is_lending']);

foreach ($data as $row) {
    fputcsv($output, [
        $row['date'],
        $row['particulars'],
        $row['expense'],
        $row['income'],
        $row['category'],
        $row['is_lending']
    ]);
}

fclose($output);
exit;
?>

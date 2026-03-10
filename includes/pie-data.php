<?php
session_start();
include_once('database.php');
$userid = $_SESSION['detsuid'];

// Retrieve total expense of each category
$query = "SELECT category, SUM(ExpenseCost) AS total_expense FROM tblexpense WHERE UserId = ? GROUP BY category";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();

// Create an array to store the results
$data = array();

// Loop through the results and add them to the array
while ($row = $result->fetch_assoc()) {
  $data[] = array(
    'category' => $row['category'],
    'total_expense' => $row['total_expense'],
  );
}

// Calculate the total expense
$total_expense = array_reduce($data, function($acc, $item) {
  return $acc + $item['total_expense'];
});

// Calculate the percentage for each category
foreach ($data as &$item) {
  $item['percentage'] = ($item['total_expense'] / $total_expense) * 100;
}

// Convert the array to JSON format and output it
echo json_encode($data);
?>

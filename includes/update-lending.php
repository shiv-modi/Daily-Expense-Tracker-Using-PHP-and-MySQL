<?php
// Start the session and retrieve the user ID from the session data
session_start();
if (empty($_SESSION['detsuid'])) {
    header('location:index.php');
    exit;
}
$userid = $_SESSION['detsuid']; 

// Include the database connection file
include_once 'database.php';

// Check if the form was submitted
if (isset($_POST['expense-id'])) {
  $userid = $_SESSION['detsuid'];
  $id = isset($_POST['expense-id']) ? $_POST['expense-id'] : '';
  
  // Retrieve the updated expense details from the form data
  $name = isset($_POST['name']) ? $_POST['name'] : '';
  $date = isset($_POST['date_of_lending']) ? $_POST['date_of_lending'] : '';
  $description = isset($_POST['description']) ? $_POST['description'] : '';
  $amount = isset($_POST['amount']) ? $_POST['amount'] : '';
  $status = isset($_POST['status']) ? $_POST['status'] : '';

  // Prepare and execute the SQL query to update the expense in the database
  $stmt = $db->prepare('UPDATE lending SET name = ?, date_of_lending = ?, description = ?, amount = ?, status = ? WHERE id = ? AND UserId = ?');
  $stmt->bind_param('ssssssi', $name, $date, $description, $amount, $status, $id, $userid);
  $result = $stmt->execute();

  // Check if the update was successful and redirect to the expenses page with a success message
  if ($result) {
    $message = "Lending updated successfully";
    echo "<script type='text/javascript'>alert('$message');</script>";
    echo " <script type='text/javascript'>window.location.href = 'manage-lending.php';</script>";
    exit();
  } else {
    // If the update failed, redirect to the edit expense page with an error message
    $message = "Please try again !";
    echo "<script type='text/javascript'>alert('$message');</script>";
    echo " <script type='text/javascript'>window.location.href = 'manage-lending.php?id=' . $id';</script>";
    exit();
  }
}
?>
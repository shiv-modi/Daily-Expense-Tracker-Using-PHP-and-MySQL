<?php
session_start(); // added session_start() to start session
include('database.php');

if (empty($_SESSION['detsuid'])) {
    header('location:index.php');
    exit;
}

if (isset($_POST['submit'])) {
  $userid = $_SESSION['detsuid'];
  $dateexpense = $_POST['dateexpense'];
  $category = $_POST['category'];
  $description = $_POST['description']; // changed 'category-description' to 'description'
  $costitem = $_POST['cost'];
  $expenseid = $_POST['expenseid'];
  $query = mysqli_query($db, "UPDATE tblexpense SET ExpenseDate='$dateexpense', category=(SELECT CategoryName FROM tblcategory WHERE CategoryId='$category'), ExpenseCost='$costitem', Description='$description' WHERE ID='$expenseid' AND UserId='$userid'");
  if ($query) {
    $message = "Expense updated successfully";
    echo "<script type='text/javascript'>alert('$message');</script>";
    echo " <script type='text/javascript'>window.location.href = 'manage-expenses.php';</script>";
  } else {
    $message = "Expense could not be updated";
    echo "<script type='text/javascript'>alert('$message');</script>";
  }
}
?>

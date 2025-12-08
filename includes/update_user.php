<?php
session_start(); // added session_start() to start session
include('database.php');

if (empty($_SESSION['detsuid'])) {
    header('location:index.php');
    exit;
}

if (isset($_POST['update_user'])) {

    $userid = $_SESSION['detsuid'];
    $username = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Update the user's information in the database using an UPDATE query
    $update_query = "UPDATE users SET name='$username', email='$email' , phone='$phone' WHERE id=$userid";
    // Execute the query using your database connection object
    $result = mysqli_query($db, $update_query);
    if ($result) {
        $message = "Profile updated successfully";
        echo "<script type='text/javascript'>alert('$message');</script>";
        echo " <script type='text/javascript'>window.location.href = 'user_profile.php';</script>";
        exit();
    } else {
        // Handle the error case
        echo "Error updating user information: " . mysqli_error($db);
    }
}

?>
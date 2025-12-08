
<?php
session_start();
error_reporting(0);
include('database.php');
if (empty($_SESSION['detsuid'])) {
  header('location:logout.php');
  } else{
?>
  
  <!DOCTYPE html>
<!-- Designined by CodingLab | www.youtube.com/codinglabyt -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <!--<title> Responsiive Admin Dashboard | CodingLab </title>-->
    <link rel="stylesheet" href="css/style.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">



     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/auth.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   </head>
<body>
  <div class="sidebar">
    <div class="logo-details">
      <i class='bx bx-album'></i>
      <span class="logo_name">Expenditure</span>
    </div>
      <ul class="nav-links">
        <li>
          <a href="home.php">
            <i class='bx bx-grid-alt' ></i>
            <span class="links_name">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="add-expenses.php">
            <i class='bx bx-box' ></i>
            <span class="links_name">Expenses</span>
          </a>
        </li>
       <li>
          <a href="add-income.php">
            <i class='bx bx-box'></i>
            <span class="links_name">Income</span>
          </a>
        </li>
        <li>
          <a href="manage-transaction.php">
            <i class='bx bx-list-ul'></i>
            <span class="links_name">Manage List</span>
          </a>
        </li>
        
        <li>
          <a href="lending.php">
          <i class='bx bx-money'></i>
            <span class="links_name">lending</span>
          </a>
        </li>
        <li>
        <a href="manage-lending.php" >
        <i class='bx bx-coin-stack'></i>
            <span class="links_name">Manage lending</span>
          </a>
        </li>
        <li>
          <a href="analytics.php">
            <i class='bx bx-pie-chart-alt-2' ></i>
            <span class="links_name">Analytics</span>
          </a>
        </li>
        <li>
          <a href="report.php">
          <i class="bx bx-file"></i>
            <span class="links_name">Report</span>
          </a>
        </li>
       <li>
          <a href="#"  class="active">
            <i class='bx bx-cog' ></i>
            <span class="links_name">Setting</span>
          </a>
        </li>
        <li class="log_out">
          <a href="logout.php">
            <i class='bx bx-log-out'></i>
            <span class="links_name">Log out</span>
          </a>
        </li>
      </ul>
  </div>
  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Setting</span>
      </div>



      <?php
$uid=$_SESSION['detsuid'];
$ret=mysqli_query($db,"select name  from users where id='$uid'");
$row=mysqli_fetch_array($ret);
$name=$row['name'];

?>

      <div class="profile-details">
  <img src="images/maex.png" alt="">
  <span class="admin_name"><?php echo $name; ?></span>
  <i class='bx bx-chevron-down' id='profile-options-toggle'></i>
  <ul class="profile-options" id='profile-options'>
    <li><a href="#"><i class="fas fa-user-circle"></i> User Profile</a></li>
    <!-- <li><a href="#"><i class="fas fa-cog"></i> Account Settings</a></li> -->
    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
  </ul>
</div>


<script>
  const toggleButton = document.getElementById('profile-options-toggle');
  const profileOptions = document.getElementById('profile-options');
  
  toggleButton.addEventListener('click', () => {
    profileOptions.classList.toggle('show');
  });
</script>


    </nav>
    
   
	<?php
// assume $conn is your database connection object
$userid = $_SESSION['detsuid'];

// retrieve user data from the database
$sql = "SELECT * FROM users WHERE id = $userid";
$result = mysqli_query($db, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    // set input field values to user data
    $first_name = $row['name'];
    $email = $row['email'];
	$phone = $row['phone'];


}
?>


<br>
<br>
<br>
<br>
<br>

	<div class="container mx-auto">
					<div class="bg-white shadow rounded-lg d-block d-sm-flex">
					<div class="profile-tab-nav border-right">
  <div class="p-4">
	<center>
    <label for="profile-image-input">
      <div class="img-circle text-center mb-3">
        <img id="profile-image-preview" src="images/maex.png" alt="Image" class="shadow">
        <div class="overlay">
		<i class="fas fa-camera fa-lg text" style="color: white;"></i>
        </div>
      </div>
    </label>
	</center>
    <input type="file" id="profile-image-input" style="display: none;">
    <h4 class="text-center"><?php echo $name; ?></h4>
  </div>
					<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
						<a class="nav-link active" id="account-tab" data-toggle="pill" href="#account" role="tab" aria-controls="account" aria-selected="true">
							<i class="fa fa-home text-center mr-1"></i> 
							Account
						</a>
						<a class="nav-link" id="password-tab" data-toggle="pill" href="#password" role="tab" aria-controls="password" aria-selected="false">
							<i class="fa fa-key text-center mr-1"></i> 
							Password
						</a> 
						 <!-- <a class="nav-link" id="security-tab" data-toggle="pill" href="#security" role="tab" aria-controls="security" aria-selected="false">
							<i class="fa fa-user text-center mr-1"></i> 
							Security
						</a>
						<a class="nav-link" id="application-tab" data-toggle="pill" href="#application" role="tab" aria-controls="application" aria-selected="false">
							<i class="fa fa-tv text-center mr-1"></i> 
							Application
						</a>
						<a class="nav-link" id="notification-tab" data-toggle="pill" href="#notification" role="tab" aria-controls="notification" aria-selected="false">
							<i class="fa fa-bell text-center mr-1"></i> 
							Notification
						</a> -->
					</div>
				</div>
				<div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
					<div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
						<h3 class="mb-4">Account Settings</h3>
						<form method="POST" action="update_user.php">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								  	<label>Username</label>
								  	<input type="text" class="form-control" name = "name"  value="<?php echo $first_name; ?> "required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								  	<label>Email</label>
								  	<input type="text" class="form-control" name = "email" value="<?php echo $email; ?>"required>
								</div>
							</div>
						

							<div class="col-md-6">
								<div class="form-group">
								  	<label>Registered Date</label>
									  <input type="date" class="form-control" value="<?php echo date('Y-m-d', strtotime($row['created_at'])); ?>" readonly>
								</div>
							</div>

							<div class="col-md-6">
						<div class="form-group">
							<label>Phone Number</label>
							<input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>" required>
						</div>
					</div>

						
						
						</div>
						<div>
						<button type="submit" class="btn btn-primary" name="update_user">Update</button>
       				    <button type="button" class="btn btn-light" onclick="location.href='user_profile.php'">Cancel</button>
						</div>
					</div>
					</form>

					<?php
   // Initialize variables with default values
$old_password = "";
$new_password = "";
$confirm_password = "";
$errors = array();

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Get form data
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if (empty($old_password)) {
        $errors[] = "Please enter your old password";
    }

    if (empty($new_password)) {
        $errors[] = "Please enter a new password";
    } elseif (strlen($new_password) < 8) {
        $errors[] = "Your new password must be at least 8 characters long";
    }

    if (empty($confirm_password)) {
        $errors[] = "Please confirm your new password";
    } elseif ($new_password != $confirm_password) {
        $errors[] = "Your new passwords do not match";
    }

    // Check if old password matches current password
    $userid = $_SESSION['detsuid'];
    $query = "SELECT password FROM users WHERE id = $userid";
    $result = mysqli_query($db, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if (!password_verify($old_password, $row['password'])) {
            $errors[] = "Your old password is incorrect";
        }
    } else {
        // Handle the error case
        echo "Error fetching user information: " . mysqli_error($db);
    }

    // Update password in database
    if (empty($errors)) {
        // Hash the new password
        $password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's information in the database using an UPDATE query
        $update_query = "UPDATE users SET password = '$password_hashed' WHERE id=$userid";

        // Execute the query using your database connection object
        $result = mysqli_query($db, $update_query);

        if ($result) {
            $message = "Password updated successfully";
            echo "<script type='text/javascript'>alert('$message');</script>";
            echo " <script type='text/javascript'>window.location.href = 'user_profile.php';</script>";
            exit();
        } else {
            // Handle the error case
            echo "Error updating user information: " . mysqli_error($db);
        }
    }
}

?>

<div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
    <h3 class="mb-4">Password Settings</h3>
    <form method="post">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Old password</label>
                    <input type="password" class="form-control" name="old_password" value="<?php echo htmlspecialchars($old_password); ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>New password</label>
                    <input type="password" class="form-control" name="new_password" value="<?php echo htmlspecialchars($new_password); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Confirm new password</label>
                    <input type="password" class="form-control" name="confirm_password" value="<?php echo htmlspecialchars($confirm_password); ?>" required>
                </div>
            </div>
        </div>
        <div>
            <button class="btn btn-primary" type="submit" name="submit">Update</button>
            <button class="btn btn-light" type="reset">Cancel</button>
        </div>
    </form>
</div>

		</div>
					<!-- <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
						<h3 class="mb-4">Security Settings</h3>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								  	<label>Login</label>
								  	<input type="text" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								  	<label>Two-factor auth</label>
								  	<input type="text" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="" id="recovery">
										<label class="form-check-label" for="recovery">
										Recovery
										</label>
									</div>
								</div>
							</div>
						</div>
						<div>
							<button class="btn btn-primary">Update</button>
							<button class="btn btn-light">Cancel</button>
						</div>
					</div>
					<div class="tab-pane fade" id="application" role="tabpanel" aria-labelledby="application-tab">
						<h3 class="mb-4">Application Settings</h3>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="" id="app-check">
										<label class="form-check-label" for="app-check">
										App check
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="" id="defaultCheck2" >
										<label class="form-check-label" for="defaultCheck2">
										Lorem ipsum dolor sit.
										</label>
									</div>
								</div>
							</div>
						</div>
						<div>
							<button class="btn btn-primary">Update</button>
							<button class="btn btn-light">Cancel</button>
						</div>
					</div>
					<div class="tab-pane fade" id="notification" role="tabpanel" aria-labelledby="notification-tab">
						<h3 class="mb-4">Notification Settings</h3>
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="" id="notification1">
								<label class="form-check-label" for="notification1">
									Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum accusantium accusamus, neque cupiditate quis
								</label>
							</div>
						</div>
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="" id="notification2" >
								<label class="form-check-label" for="notification2">
									hic nesciunt repellat perferendis voluptatum totam porro eligendi.
								</label>
							</div>
						</div>
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="" id="notification3" >
								<label class="form-check-label" for="notification3">
									commodi fugiat molestiae tempora corporis. Sed dignissimos suscipit
								</label>
							</div>
						</div>
						<div>
							<button class="btn btn-primary">Update</button>
							<button class="btn btn-light">Cancel</button>
						</div>
					</div>
				</div> -->
			
		<style> 
		@import url("https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap");
		body {
			background: #f9f9f9;
			font-family: "Roboto", sans-serif;
		}
		.container {
	max-width: 1200px;
	margin: 0 auto;
	padding: 20px;
}

		.shadow {
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
		}

		.profile-tab-nav {
			min-width: 250px;
		}

		.tab-content {
			flex: 1;
		}

		.form-group {
			margin-bottom: 1.5rem;
		}

		.nav-pills a.nav-link {
			padding: 15px 20px;
			border-bottom: 1px solid #ddd;
			border-radius: 0;
			color: #333;
		}

		.nav-pills a.nav-link i {
			width: 20px;
		}
		.mb-4, .my-4 {
    margin-bottom: 3.5rem!important;
}
		.img-circle img {
			height: 100px;
			width: 100px;
			border-radius: 100%;
			border: 5px solid #fff;
		}

		/* Define variables for colors */
		:root {
			--primary: #007bff;
			--success: #28a745;
			--danger: #dc3545;
			--warning: #ffc107;
		}
		.shadow {
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
		}

		.profile-tab-nav {
			min-width: 250px;
		}

		.tab-content {
			flex: 1;
		}

		.form-group {
			margin-bottom: 1.5rem;
		}

		.nav-pills a.nav-link {
			padding: 15px 20px;
			border-bottom: 1px solid #ddd;
			border-radius: 0;
			color: #333;
		}
		.nav-pills a.nav-link i {
			width: 20px;
		}

		
 .img-circle {
  position: relative;

  justify-content: center;
  align-items: center;
}



#profile-image-preview {
  height: 100px;
  width: 100px;
  border-radius: 100%;
  border: 5px solid #fff;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  opacity: 0;
  transition: .5s ease;
  background-color: rgba(0,0,0,0.7);
  border-radius: 50%;
}

.img-circle:hover .overlay {
  opacity: 1;
}

.text {
  color: white;
  font-size: 20px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  text-align: center;
}


		</style>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
 
 let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".sidebarBtn");
sidebarBtn.onclick = function() {
sidebar.classList.toggle("active");
if(sidebar.classList.contains("active")){
sidebarBtn.classList.replace("bx-menu" ,"bx-menu-alt-right");
}else
sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
}
</script>

</body>
</html>

<?php } ?>
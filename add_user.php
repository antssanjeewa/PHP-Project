<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php 

	if(!isset($_SESSION['user_id'])){
		header('Location: index.php');
	}

	
	$first_name = '';
	$last_name = '';
	$email = '';
	$password = '';
	$result = '';

	if(isset($_POST['submit'])){
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];
		$password = $_POST['password'];

		//checking email address is already exists
		$email = mysqli_real_escape_string($connection, $_POST['email']);
		$query  = "SELECT * FROM user WHERE email = '{$email}' LIMIT 1";

		$result = mysqli_query($connection, $query);


	}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Add new user</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<header>
		<div class="appname">User Management System</div>
		<div class="loggedin">Welcome <?php echo $_SESSION['first_name']; ?>!<a href="logout.php">Log Out</a></div>
	</header>
	<main>
		<h1>Add New User<span><a href="users.php"> Back to Users</a></span></h1>
		<?php 
			if($result){
				if(mysqli_num_rows($result) == 1 ){
					echo 'Email address already exists';
				}else{
					$first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
					$last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
					$password = mysqli_real_escape_string($connection, $_POST['password']);
					$hash_password = sha1($password);

					$quary = "INSERT INTO user (frist_name, last_name, email, password, is_deleted) VALUES ('{$first_name}', '{$last_name}', '{$email}', '{$hash_password}',0)";

					$result = mysqli_query($connection, $quary);
					if($result){
						header('Location: users.php?user_added=true');
					}else{
						echo "Failed to add the new record.";
					}
				}
		}
		 ?>
		<form action="add_user.php" method="post" class="userform">
			<p>
				<label>Frist Name : </label>
				<input type="text" name="first_name" required <?php echo 'value="'.$first_name.'"'; ?>>
			</p>
			<p>
				<label>Last Name : </label>
				<input type="text" name="last_name" required <?php echo 'value="'.$last_name.'"'; ?>>
			</p>
			<p>
				<label>Email Address : </label>
				<input type="email" name="email" required <?php echo 'value="'.$email.'"'; ?>>
			</p>
			<p>
				<label>New Password : </label>
				<input type="password" name="password" required>
			</p>
			<p>
				<label>&nbsp;</label>
				<button type="submit" name="submit">Save</button>
			</p>
		</form>
	</main>
</body>
</html>
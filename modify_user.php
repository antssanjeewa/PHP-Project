<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php 

	if(!isset($_SESSION['user_id'])){
		header('Location: index.php');
	}
	$user_id = '';
	$first_name = '';
	$last_name = '';
	$email = '';
	$error = array();

	if(isset($_GET['user_id'])){
		$user_id = mysqli_real_escape_string($connection, $_GET['user_id'] );
		$query = "SELECT * FROM user WHERE id = {$user_id} LIMIT 1";

		$result = mysqli_query($connection, $query);
		if($result){
			if(mysqli_num_rows($result) == 1 ){
				$results = mysqli_fetch_assoc($result);
				$first_name = $results['frist_name'];
				$last_name = $results['last_name'];
				$email = $results['email'];

			}else{
				header('Location: users.php?err=user_not_found');
			}
		}else{
			header('Location: users.php?err=query failed');
		}
	}

	if(isset($_POST['submit'])){
		$user_id = $_POST['user_id'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];

		//checking email address is already exists
		$email = mysqli_real_escape_string($connection, $_POST['email']);
		$query  = "SELECT * FROM user WHERE email = '{$email}' AND id != {$user_id} LIMIT 1";

		$result = mysqli_query($connection, $query);
		if($result){
			if(mysqli_num_rows($result) == 1 ){
				$error[] ='Email address already exists';
			}
		}

		if(empty($error)){
			$first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
			$last_name = mysqli_real_escape_string($connection, $_POST['last_name']);

			$query = "UPDATE user SET ";
			$query .= "frist_name = '{$first_name}',";
			$query .= "last_name = '{$last_name}',";
			$query .= "email = '{$email}'" ;
			$query .= "WHERE id= {$user_id} LIMIT 1";

			$result = mysqli_query($connection, $query);
			if($result){
				header('Location: users.php?user_modified=true');
			}else{
				$error[] = "Failed to modify the new record.";
			}
		}

	}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Modify user</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<header>
		<div class="appname">User Management System</div>
		<div class="loggedin">Welcome <?php echo $_SESSION['first_name']; ?>!<a href="logout.php">Log Out</a></div>
	</header>
	<main>
		<h1>View / Modify User<span><a href="users.php"> Back to Users</a></span></h1>
		<?php 
			if(!empty($error)){
				echo '<div class="errmsg">';
				echo '<b> There were error on your form</b><br>';
				foreach ($error as $err) {
					echo '- '.$err.'</br>';
				}
				echo '</div>';
			}
		 ?>
		<form action="modify_user.php" method="post" class="userform">
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
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
				<label>Password : </label>
				<span>******</span>|<a href="change_password.php?user_id=<?php echo $user_id; ?>">Change Password</a>
			</p>
			<p>
				<label>&nbsp;</label>
				<button type="submit" name="submit">Save</button>
			</p>
		</form>
	</main>
</body>
</html>
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
		$password = $_POST['password'];

		if(empty($error)){
			
			$password = mysqli_real_escape_string($connection, $_POST['password']);
			$hash_password = sha1($password);

			$query = "UPDATE user SET ";
			$query .= "password = '{$hash_password}' ";
			$query .= "WHERE id= {$user_id} LIMIT 1";

			$result = mysqli_query($connection, $query);
			if($result){
				header('Location: users.php?user_modified=true');
			}else{
				echo "Failed to modify the Password.";
			}
			
		}
	}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>change password</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<header>
		<div class="appname">User Management System</div>
		<div class="loggedin">Welcome <?php echo $_SESSION['first_name']; ?>!<a href="logout.php">Log Out</a></div>
	</header>
	<main>
		<h1>Change Password<span><a href="users.php"> Back to Users</a></span></h1>
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
		<form action="change_password.php" method="post" class="userform">
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
			<p>
				<label>Frist Name : </label>
				<input type="text" name="first_name" required <?php echo 'value="'.$first_name.'"'; ?> disabled>
			</p>
			<p>
				<label>Last Name : </label>
				<input type="text" name="last_name" required <?php echo 'value="'.$last_name.'"'; ?> disabled>
			</p>
			<p>
				<label>Email Address : </label>
				<input type="email" name="email" required <?php echo 'value="'.$email.'"'; ?> disabled>
			</p>
			<p>
				<label>New Password : </label>
				<input type="password" name="password" id="password">
			</p>
			<p>
				<label>Show Password : </label>
				<input type="checkbox" name="showpassword" id="showpassword" style="width:20px; height:20px;">
			</p>
			<p>
				<label>&nbsp;</label>
				<button type="submit" name="submit">Update Password</button>
			</p>
		</form>
	</main>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#showpassword').click(function(){
				if($('#showpassword').is(':checked')){
					$('#password').attr('type','text');
				}else{
					$('#password').attr('type','password');
				}
			});
		});
	</script>
</body>
</html>
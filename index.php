<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php 
	if(isset($_POST['submit'])){
		$errors = array();

		if(!isset($_POST['email']) ||  strlen(trim($_POST['email'])) < 1){
			$errors[] = 'Username is Missing / Invalid';
 		}
 		if(!isset($_POST['password']) ||  strlen(trim($_POST['password'])) < 1){
			$errors[] = 'Password is Missing / Invalid';
 		}

 		if(empty($errors)){
 			$email = mysqli_real_escape_string($connection,$_POST['email']);
 			$password = mysqli_real_escape_string($connection,$_POST['password']);
 			$hash_password = sha1($password);

 			$quary = "SELECT * FROM user WHERE 
 						email = '{$email}'
 						AND password='{$password}'
 						LIMIT 1";

 			$result = mysqli_query($connection, $quary);

 			if($result){
 				if(mysqli_num_rows($result) == 1){

 					$user = mysqli_fetch_assoc($result);
 					$_SESSION['user_id'] = $user['id'];
 					$_SESSION['first_name'] = $user['frist_name'];

 					$query = "UPDATE user SET last_login = NOW()";
 					$query .= "WHERE id={$_SESSION['user_id']} LIMIT 1";

 					$result = mysqli_query($connection, $query);
 					if(!$result){
 						die("Database query failed");
 					}

 					header('Location: users.php');
 				}else{
 					$errors[] = 'Invalid Username / Password';
 				}
 			}else{
 				$errors[] = 'Database Quary failed';
 			}
 		}
	}

 ?>
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='UTF-8'>
	<title>Log In - User Management System</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<div class="login">
		<form action="index.php" method="post">
			<fieldset>
				<legend><h1>Log In</h1></legend>

				<?php 
					if(isset($errors) && !empty($errors)){
						echo '<p class="error">Invalid Username / Password</p>';
					}
				 ?>

				<p>
					<label for="">Username:</label>
					<input type="text" name="email" placeholder="Email Address">
				</p>
				<p>
					<label for="">Password:</label>
					<input type="password" name="password" placeholder="Password">
				</p>
				<p>
					<button type="submit" name="submit">Log In</button>
				</p>

			</fieldset>
		</form>
	</div>

</body>
</html>
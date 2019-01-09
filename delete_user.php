<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php 

	if(!isset($_SESSION['user_id'])){
		header('Location: index.php');
	}
	$user_id = '';
	if(isset($_GET['user_id'])){
		$user_id = mysqli_real_escape_string($connection, $_GET['user_id'] );
		
		if($user_id == $_SESSION['user_id']){
			header('Location: users.php?err=cannot_delete_current_user');
		}else{
			$query = "UPDATE user SET is_deleted = 1 WHERE id={$user_id} LIMIT 1";

			$result = mysqli_query($connection, $query);

			if($result){
				header('Location: users.php?mmsg=user_deleted');
			}else{
				header('Location: users.php?err=delete_failed');
			}
		}

		
	}else{
		header('Location: users.php');
	}

?>
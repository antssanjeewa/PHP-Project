<?php 
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'userdb';

	$connection = mysqli_connect('localhost','root','','userdb');

	if(mysqli_connect_errno()){
		die('Database connection failed'.mysqli_connect_error());
	}

 ?>
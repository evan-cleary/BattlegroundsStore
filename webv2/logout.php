<?php
	session_start();
	session_destroy();
	session_start();
	$_SESSION['error'] = "You have been logged out.";
	header("Location: index.php");
?>
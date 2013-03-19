<?php
	
	session_start();
	
	include_once("./common_lib/store_lib.php");
	if(!isset($_SESSION['user_id']) || !isset($_SESSION['authToken'])){
		$_SESSION['error'] = "You are not logged in";
		header("Location: index.php");
	} else {
		if(!compareAuthTokens($_SESSION['user_id'],$_SESSION['authToken'])){
			$_SESSION['error'] = "Bad login. Please log in again.";
			header("Location: index.php");
		}
	}
	if(isset($_GET['item_id']) && isset($_GET['user_id'])){
		if($_GET['user_id'] != $_SESSION['user_id']){
			header("Location: index.php");
			$_SESSION['error'] = "You're sessions do not match.";
			exit;
		}
		purchaseItem($_GET['item_id'],$_GET['user_id']);
	}
?>
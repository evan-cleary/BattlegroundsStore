<?php
	session_start();
	require_once("./common_lib/store_lib.php");
	if(isset($_SESSION['user_id']) && isset($_SESSION['authToken'])){
		if(compareAuthTokens($_SESSION['user_id'],$_SESSION['authToken'])){
			header("Location: index.php");
		}
	}
	require_once("./includes/config.php");
	$postUsername = $_POST['username'];
	$postAuthCode = $_POST['authcode'];
	if(empty($postAuthCode)){
		$_SESSION['error'] = "The Auth Code field cannot be blank";
		header("Location: index.php");
		exit;
	}
	$mysqlSafeUsername = mysql_real_escape_string($postUsername);
	$mysqlSafeAuthCode = mysql_real_escape_string($postAuthCode);
	$query = mysql_query("SELECT id FROM ".$tablePrefix."players WHERE player_name='".$mysqlSafeUsername."' AND authcode='".$mysqlSafeAuthCode."'");
	if(mysql_num_rows($query) == 1){
		$row = mysql_fetch_assoc($query);
		$_SESSION['user_id'] = $row['id'];
		$_SESSION['authToken'] = md5($_SESSION['user_id'].$postUsername.$postAuthCode);
		$query = mysql_query("UPDATE ".$tablePrefix."players SET authToken='".$_SESSION['authToken']."' WHERE id=".$row['id']);
		$_SESSION['error'] = "You have successfully logged in!";
	} else {
		$_SESSION['error'] = "Invalid username or authorization code.";
	}
	header("Location: index.php");
?>
<?php

	session_start();
	if(isset($_GET['filter'])){
		$_SESSION['filter'] = $_GET['filter'];
	} else{
		unset($_SESSION['filter']);
	}
	header("Location: ".$_SERVER['HTTP_REFERER']);
?>
<?php
	
	session_start();
	if(isset($_POST['search']) || isset($_GET['search'])){
		$searchQuery = "";
		if(isset($_GET['search'])){
			$searchQuery = $_GET['search'];
		}
		if(isset($_POST['search'])){
			$searchQuery = $_POST['search'];
		}
		$_SESSION['searchQuery'] = $searchQuery;
		unset($_SESSION['filter']);
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}
	header("Location: ".$_SERVER['HTTP_REFERER']);
	
?>
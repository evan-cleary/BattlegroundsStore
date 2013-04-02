<?php
	require_once("./common_lib/store_lib.php");
	session_start();

		if(!isset($_SESSION['user_id']) || !isset($_SESSION['authToken'])){
			echo "<div id=\"login_button\" class=\"half_inner clickable\"> Log In </div>
      <div class=\"half_inner clickable\"> Sign up </div>";
		} else {
			if(compareAuthTokens($_SESSION['user_id'],$_SESSION['authToken'])){
				echo "<div class=\"half_inner\">Tokens: ".getTokens($_SESSION['user_id'])."</div>
      <div id=\"signout\" class=\"half_inner clickable\">Sign Out</div>";

			} else {
				echo "<div id=\"login_button\" class=\"half_inner clickable\"> Log In </div>
      <div class=\"half_inner clickable\"> Sign up </div>";
				$_SESSION['error'] = "Bad Login. Please log in again.";
			}
		}

?>
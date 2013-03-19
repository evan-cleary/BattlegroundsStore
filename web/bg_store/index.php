<?php 
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Battlegrounds Store</title>
<link href="style/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
  <div id="store">
  	<div id="login">
    <?php 
		include("./includes/login_frame.php");
		if(!isset($_SESSION['user_id']) || !isset($_SESSION['authToken'])){
			getFrame(1);
		} else {
			require_once("./common_lib/store_lib.php");
			if(compareAuthTokens($_SESSION['user_id'],$_SESSION['authToken'])){
				echo '<span style="text-align:center;">Welcome back '.getUsername($_SESSION['user_id']).'!<br /><span style="font-size:24px">Tokens: '.getTokens($_SESSION['user_id']).'</span><br /><a href="logout.php"><input type="button" class="submit" value="Logout"/></a></span>';
			} else {
				getFrame(1);
				$_SESSION['error'] = "Bad Login. Please log in again.";
			}
		}
	?>
	</div>
    <?php if(isset($_SESSION['error'])){
		echo '<span style="color:#fff;text-align:center;">'.$_SESSION['error'].'</span>';
		unset($_SESSION['error']);
	}?>
  	<?php include('./includes/store.php');?>
      <p style="clear:both">
    	<a href="http://validator.w3.org/check?uri=referer"><img
      src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" height="31" width="88"/></a>
  	</p>
  </div>
</div>
</body>
</html>
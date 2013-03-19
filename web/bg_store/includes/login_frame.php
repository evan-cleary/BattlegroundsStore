<?php
	require_once("./common_lib/store_lib.php");
	
	function getFrame($style){
		if($style == 0){
			echo '<form action="login.php" method="post">
					<table align="center">
						<tr><td align="right">Username: </td><td><input type="text" name="username" /></td></tr>
						<tr><td align="right">Auth Code: </td><td><input type="text" name="authcode" /></td></tr>
						<tr><td colspan="2" align="center"><input type="submit" class="submit" value="Sign In" name="login" /></td></tr>
					</table>
				</form>';
		} else {
			echo '<form action="login.php" method="post">
				<label for="username">Username:</label>
				<input type="text" name="username"/>
				<label for="authcode">Auth Code:</label>
				<input type="text" name="authcode"/>
				<input type="submit" class="submit" value="Sign in" name="login"/>
			</form>';
		}
	}
?>
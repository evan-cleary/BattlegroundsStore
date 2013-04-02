<?php
	
	require_once("./includes/config.php");
	$enc = array();
	$enc[0] = 'Protection';
	$enc[1] = 'Fire Protection';
	$enc[2] = 'Feather Falling';
	$enc[3] = 'Blast Protection';
	$enc[4] = 'Projectile Protection';
	$enc[5] = 'Respiration';
	$enc[6] = 'Aqua Affinity';
	$enc[7] = 'Thorns';
	$enc[34] = 'unbreaking';
	$enc[16] = 'Sharpness';
	$enc[17] = 'Smite';
	$enc[18] = 'Bane of Arthropods';
	$enc[19] = 'Knockback';
	$enc[20] = 'Fire Aspect';
	$enc[21] = 'Looting';
	$enc[32] = 'Efficiency';
	$enc[33] = 'Silk Touch';
	$enc[35] = 'Fortune';
	$enc[48] = 'Power';
	$enc[49] = 'Punch';
	$enc[50] = 'Flame';
	$enc[51] = 'Infinity';
		// A function to return the Roman Numeral, given an integer
 	function numberToRoman($num) 
 	{
     // Make sure that we only use the integer portion of the value
     	$n = intval($num);
     	$result = '';
 
     // Declare a lookup array that we will use to traverse the number:
     	$lookup = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
     	'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
     	'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
 
     	foreach ($lookup as $roman => $value) 
     	{
         	// Determine the number of matches
         	$matches = intval($n / $value);
 
         	// Store that many characters
         	$result .= str_repeat($roman, $matches);
 
         	// Substract that from the number
         	$n = $n % $value;
     	}
 
     // The Roman numeral should be built, return it
     	return $result;
 	}
	
 	function getAttributes($atts){
		if(empty($atts)){
			return "";
		}
		global $enc;
		$attributes = split(",",$atts);
		$output = "";
		foreach($attributes as $attr){
			$break  = split(":",$attr);
			$level = numberToRoman($break[1]);
			$name = $enc[intval($break[0])];
			$output.="<li>".$enc[intval($break[0])]." ".$level."</li>";
		}
		return "<ul>".$output."</ul>";
	}

	function getUsername($id){
		global $tablePrefix;
		$query = mysql_query("SELECT * FROM ".$tablePrefix."players WHERE id=".$id);
		$count = mysql_num_rows($query);
		if($count == 1){
			$row = mysql_fetch_assoc($query);
			return $row['player_name'];
		} else {
			return "Unknown";
		}
	}
	
	function getTokens($id){
		global $tablePrefix;
		$query = mysql_query("SELECT tokens FROM ".$tablePrefix."tokens WHERE id=".$id);
		if(mysql_num_rows($query) == 1){
			$row = mysql_fetch_assoc($query);
			return $row['tokens'];
		} else {
			return 0;
		}
	}
	
	function compareAuthTokens($id,$token){
		global $tablePrefix;
		$query = mysql_query("SELECT authToken FROM ".$tablePrefix."players WHERE id=".$id);
		if(mysql_num_rows($query) == 1){
			$row = mysql_fetch_assoc($query);
			if($row['authToken'] == $token){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function purchaseItem($item_id,$id){
		global $tablePrefix;
		$item_query = mysql_query("SELECT * FROM ".$tablePrefix."store_items WHERE id=".$item_id);
		if(mysql_num_rows($item_query) == 1){
			$item = mysql_fetch_assoc($item_query);
			$cost = intval($item['price']);
			$tokens = intval(getTokens($id));
			if($cost <= $tokens){
				$query = mysql_query("INSERT INTO ".$tablePrefix."purchases(item_id, player_id) VALUES (".$item_id.",".$id.")");
				if($query){
					$_SESSION['error'] = "You have successfully purchased ".$item['amount']." ".ucwords($item['item']).".";
				} else {
					$_SESSION['error'] = mysql_error();
					header("Location: index.php");
					exit;
				}
				$query = mysql_query("UPDATE ".$tablePrefix."tokens SET tokens=".($tokens-$cost)." WHERE id=".$id);
				header("Location: index.php");
			} else {
				$_SESSION['error'] = "You do not have enough tokens to purchase this item.";
				header("Location: index.php");
			}
		} else{
			$_SESSION['error'] = "Unable to find item with id: ".$item_id;
			header("Location: index.php");
		}
	}
?>
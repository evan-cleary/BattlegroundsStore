<?php
	require_once("./includes/config.php");
	require_once("./common_lib/store_lib.php");	
	require_once("./common_lib/filter.php");
	session_start();
	
	$query_string = "SELECT * FROM ".$tablePrefix."store_items";
	if(isset($_GET['sort'])){
		$sort = $_GET['sort'];
		if($sort == 'ASC'){
			$query_string = "SELECT * FROM ".$tablePrefix."store_items ORDER BY price ASC";
		} elseif($sort == 'DESC'){
			$query_string = "SELECT * FROM ".$tablePrefix."store_items ORDER BY price DESC";
		}
	}
	if(isset($_SESSION['filter'])){
		$filter = filter($_SESSION['filter']);
	}
	
	if(isset($_SESSION['searchQuery'])){
		$query_string.=" WHERE item LIKE '%".$_SESSION['searchQuery']."%'";
		unset($_SESSION['searchQuery']);
	}
	
	$query = mysql_query($query_string);
	if(mysql_num_rows($query) == 0){
		echo "No Items found.";
		exit;
	}
	while($row = mysql_fetch_assoc($query)){
		if(isset($filter)){
			if(!in_array($row['item_id'], $filter)){
				continue;
			}
		}
		$imgIcon = $row['item_id'];
		if($row['damage'] != 0){
			$imgIcon .= '_'.$row['damage'];
		}
		echo "    <div class=\"store_item\">
      <div class=\"icon\"><img src=\"./images/item_images/".$imgIcon.".gif\" width=\"50px\" height=\"50px\" alt=\"".ucwords($row['item'])."\"/> </div>
      <div class=\"item_stats\">".$row['amount']." ".ucwords($row['item'])."
      <div class=\"attributes\">"
		.getAttributes($row['attributes']).
		"</div>
		</div>
      <div class=\"item_cost\">".$row['price']." Tokens </div>
      <a href=\"purchase.php?item_id=".$row['id']."&amp;user_id=".$_SESSION['user_id']."\" class=\"buy\"></a>
    </div>";
	}
?>
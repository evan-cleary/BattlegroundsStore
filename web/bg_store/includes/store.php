<?php
	require_once("./includes/config.php");
	require_once("./common_lib/store_lib.php");	
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
	
	$query = mysql_query($query_string);
	while($row = mysql_fetch_assoc($query)){
		$imgIcon = $row['item_id'];
		if($row['damage'] != 0){
			$imgIcon .= '_'.$row['damage'];
		} else{
		}
		echo "    <div class=\"store_item\">
      <div class=\"icon\"><img src=\"./images/item_images/".$imgIcon.".gif\" width=\"50px\" height=\"50px\" alt=\"".ucwords($row['item'])."\"/> </div>
      <div class=\"item_name\">".$row['amount']." ".ucwords($row['item'])."</div>
      <div class=\"attributes\">"
		.getAttributes($row['attributes']).
		"</div>
      <div class=\"item_cost\">".$row['price']." Tokens </div>
      <a href=\"purchase.php?item_id=".$row['id']."&amp;user_id=".$_SESSION['user_id']."\" class=\"buy\"></a>
    </div>";
	}
?>
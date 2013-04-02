<?php

	//Tool Item Id's
	$axes = array(271,275,258,286,279);
	$picks = array(270,274,257,285,278);
	$shovels = array(269,273,256,284,277);
	$swords = array(268,272,267,283,276);
	$hoes = array(290,291,292,293,294);
	$tools = array_merge($axes,$picks,$shovels,$swords,$hoes);

	//Armor Item Id's
	$boots = array(301,305,309,313,317);
	$chest = array(299,303,307,311,315);
	$legs = array(300,304,308,312,316);
	$helm = array(298,302,306,310,314);
	$armor = array_merge($boots,$chest,$legs,$helm);

	//Consumable Item Id's
	$food = array(260,322,392,393,394,297,92,354,391,396,363,364,365,366,367,349,350,357,360,282,400,375);
	$potions = array(373);
	$consumables = array_merge($food,$potions);

	function filter($filter_id){
		if($filter_id == 1){
			global $tools;
			return $tools;
		}
		if($filter_id == 10){
			global $swords;
			return $swords;
		}
		if($filter_id == 11){
			global $axes;
			return $axes;
		}
		if($filter_id == 12){
			global $picks;
			return $picks;
		}
		if($filter_id == 13){
			global $shovels;
			return $shovels;
		}
		if($filter_id == 14){
			global $hoes;
			return $hoes;
		}
		if($filter_id == 2){
			global $armor;
			return $armor;
		}
		if($filter_id == 20){
			global $boots;
			return $boots;
		}
		if($filter_id == 21){
			global $chest;
			return $chest;
		}
		if($filter_id == 22){
			global $helm;
			return $helm;
		}
		if($filter_id == 23){
			global $legs;
			return $legs;
		}
		if($filter_id == 3){
			global $consumables;
			return $consumables;
		}
		if($filter_id == 30){
			global $food;
			return $food;
		}
		if($filter_id == 31){
			global $potions;
			return $potions;
		}
	}

?>
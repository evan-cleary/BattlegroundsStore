// JavaScript Document

function setStoreContents(storeItems){
	$("#store").empty();
	$("#store").add(storeItems);
}
function setCurrent(newCurrent){
	$(".current").removeClass("current");
	$("#"+newCurrent).addClass("current");
	$.cookie("curFilter", newCurrent);
	$.cookie("curMenu", $("#"+newCurrent).parent().parent().attr('id'));
}


function resetFilters(){
	$.removeCookie("curMenu");
	$.removeCookie("curFilter");
}

function setMenu(activeMenu){
	$("#"+activeMenu).slideToggle();
	$.cookie("curMenu", activeMenu);
}

function initiate(){
	if($.cookie("curFilter") != null){
		setCurrent($.cookie("curFilter"));
	}
	if($.cookie("curMenu") != null){
		setMenu($.cookie("curMenu"));
	}
	$("#swords").click(function(){
		setCurrent("swords");
		window.location.replace("filter.php?filter=10");
	});
	$("#axes").click(function(){
		setCurrent("axes");
		window.location.replace("filter.php?filter=11");
	});
	$("#picks").click(function(){
		setCurrent("picks");
		window.location.replace("filter.php?filter=12");
	});
	$("#shovels").click(function(){
		setCurrent("shovels");
		window.location.replace("filter.php?filter=13");
	});
	$("#hoes").click(function(){
		setCurrent("hoes");
		window.location.replace("filter.php?filter=14");
	});
	$("#boots").click(function(){
		setCurrent("boots");
		window.location.replace("filter.php?filter=20");
	});
	$("#chestplate").click(function(){
		setCurrent("chestplate");
		window.location.replace("filter.php?filter=21");
	});
	$("#helmet").click(function(){
		setCurrent("helmet");
		window.location.replace("filter.php?filter=22");
	});
	$("#leggings").click(function(){
		setCurrent("leggings");
		window.location.replace("filter.php?filter=23");
	});
	$("#food").click(function(){
		setCurrent("food");
		window.location.replace("filter.php?filter=30");
	});
	$("#potion").click(function(){
		setCurrent("potion");
		window.location.replace("filter.php?filter=31");
	});
	$("#signout").click(function(){
		window.location.replace("logout.php");
	});
}
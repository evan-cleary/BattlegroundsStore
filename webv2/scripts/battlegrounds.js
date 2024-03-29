// JavaScript Document

function setStoreContents(storeItems){
	$("#store").empty();
	$("#store").add(storeItems);
}
function setCurrent(newCurrent){
	$(".current").removeClass("current");
	$("#"+newCurrent).addClass("current");
	$.cookie("curFilter", newCurrent);
	$("#"+newCurrent).parent().parent().show();
}

function resetFilters(){
	$.removeCookie("curFilter");
}

function openMenu(activeMenu){
	$("#"+activeMenu).slideToggle(function(){
		var pane = $('#filters');
		var api = pane.data('jsp');
		api.reinitialise();
	});
	if($.cookie("openMenus") == null){
		$.cookie("openMenus",activeMenu+",");
		return;
	}
	var menus = $.cookie("openMenus");
	if(menus.indexOf(activeMenu)!= -1){
		menus = menus.replace(activeMenu+",","");
	} else{
		menus+=activeMenu+",";
	}
	if(menus == ""){
		$.removeCookie("openMenus");
	} else{
		$.cookie("openMenus",menus);
	}
}

function restoreMenus(){
	if($.cookie("openMenus") == null){
		return;
	}
	var openMenus = $.cookie("openMenus").split(",");
	for(var i=0;i<openMenus.length;i++){
		$("#"+openMenus[i]).show();
	}
	$('#filters').jScrollPane({
		horizontalGutter:0,
		verticalGutter:2,
		'showArrows': false
	});
	var pane = $('#filters');
	var api = pane.data('jsp');
	api.reinitialise();
}

function initiate(){
	if($.cookie("curFilter") != null){
		setCurrent($.cookie("curFilter"));
	}
	if($.cookie("openMenus") != null){
		restoreMenus();
	}
	$("#allTools").click(function(){
		setCurrent("allTools");
		window.location.replace("filter.php?filter=1");
	});
	$("#weapons").click(function(){
		setCurrent("weapons");
		window.location.replace("filter.php?filter=15");
	});
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
	$("#allArmor").click(function(){
		setCurrent("allArmor");
		window.location.replace("filter.php?filter=2");
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
	$("#allConsumables").click(function(){
		setCurrent("allConsumables");
		window.location.replace("filter.php?filter=3");
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
	$('#filters').each(
		function()
		{
			$(this).jScrollPane(
				{
					showArrows: $(this).is('.arrow')
				}
			);
			var api = $(this).data('jsp');
			var throttleTimeout;
			$(window).bind(
				'resize',
				function()
				{
					if ($.browser.msie) {
						// IE fires multiple resize events while you are dragging the browser window which
						// causes it to crash if you try to update the scrollpane on every one. So we need
						// to throttle it to fire a maximum of once every 50 milliseconds...
						if (!throttleTimeout) {
							throttleTimeout = setTimeout(
								function()
								{
									api.reinitialise();
									throttleTimeout = null;
								},
								50
							);
						}
					} else {
						api.reinitialise();
					}
				}
			);
		}
	);
}

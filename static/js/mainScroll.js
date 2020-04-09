$(document).ready(function(){
	//½ÃÀÛ
	function layerMoveFixed(){		
		var scrollPos = ($(window).scrollTop() + $(".header-wrap").height());
		var scrollFirstLayerPos = $(".moveLayerLine").offset().top + $(".moveLayerLine").height();

		var st = $(window).scrollTop();

		if (st > lastScroll){
			if(scrollPos > scrollFirstLayerPos){
				$('.scroll-move-wrap').css("position", "fixed").css("top", $(".header-wrap").height() - 20 +"px");
			}
		}
		else {
			if(scrollPos > scrollFirstLayerPos){
				$('.scroll-move-wrap').css("position", "fixed").css("top", $(".header-wrap").height() - 20 +"px");
			}else{
				$('.scroll-move-wrap').css("position", "relative").css("top", "0px");
			}
		}
		lastScroll = st;
	}

	function layerScrollMoveInit(flag){	
		var scrollPos1 = ($(window).scrollTop() + $(".header-wrap").height());
		var newClickNumber = 0;
		$(".moveDirectLayer").each(function(){
			if(flag == "dn"){
				if(scrollPos1 >= parseInt($(this).offset().top, 10)){
					clickNumber = newClickNumber;
					newClickNumber++;
					return true;
				}
			}else{
				if(scrollPos1 > parseInt($(this).offset().top, 10)){
					if(newClickNumber == 0) newClickNumber = 1;
					clickNumber = newClickNumber;
					newClickNumber++;
					return true;
				}
			}
		})
	}




	var lastScroll = 0;
	$(window).scroll(function(event){
		layerMoveFixed();
	});
	layerMoveFixed();



	var clickNumber = -1;
	var maxLayerCount = $(".moveDirectLayer").length - 1;
	$(".down").bind('click', function() {
		layerScrollMoveInit("dn");

		if ($(".moveDirectLayer:eq("+clickNumber+")").length > 0 ) {
			if(maxLayerCount <= clickNumber){
				clickNumber = maxLayerCount;
			}else{
				clickNumber++;
			}
			$('html, body').animate({scrollTop : $(".moveDirectLayer:eq("+(clickNumber)+")").offset().top - $(".header-wrap").height()}, 300);
		}else{
			clickNumber = maxLayerCount;
		}
	});

	$(".up").bind('click', function() {
		layerScrollMoveInit("up");
		if(clickNumber <= 0) clickNumber = 0;

		if(clickNumber == $(".moveDirectLayer").length){
			clickNumber = $(".moveDirectLayer").length - 1;
			if(clickNumber <= 0) clickNumber = 0;
			$('html, body').animate({scrollTop : $(".moveDirectLayer:eq("+(clickNumber)+")").offset().top - $(".header-wrap").height()}, 300);
		}else{
			if ($(".moveDirectLayer:eq("+clickNumber+")").length > 0) {
				clickNumber--;
				if(clickNumber <= 0) clickNumber = 0;
				$('html, body').animate({scrollTop : $(".moveDirectLayer:eq("+(clickNumber)+")").offset().top - $(".header-wrap").height()}, 300);
			}
		}
	});


	$(".top").bind('click', function() {
		clickNumber = -1;
	});
	// ³¡
});
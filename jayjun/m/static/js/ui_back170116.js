$(document).ready(function(){ 
	/* ==============================
		common	
	============================== */
	//resize
	window_resize();

	function window_resize() {
		$("#page").css({ paddingTop:$("#header").outerHeight() });
		var pageMinH = $("body").height() - $("#header").outerHeight();
		$("#page").css({ minHeight:pageMinH });
		var contentMinH = pageMinH - $("#footer").outerHeight();
		$("#content").css({ minHeight:contentMinH });
	}

	//lnb open/close
	$('.btn-lnb-open').click(function(){
		$('.lnb-layer').addClass('on');
		$('.lnb-layer-inner').stop().animate({left:0},300);
		$('html,body').css({'position':'absolte','overflow':'hidden'});
	})
	$('.btn-lnb-close, .lnb-layer-dim').click(function(){
		$('.lnb-layer-inner').stop().animate({left:'-100%'},200,function(){
			$('.lnb-layer').removeClass('on');
		});
		$('html,body').css({'position':'static','overflow':'visible'});
	});

	//rnb open/close
	$('.btn-rnb-open').click(function(){
		$('.rnb-layer').addClass('on');
		$('.rnb-layer-inner').stop().animate({right:0},300);
		$('html,body').css({'position':'absolte','overflow':'hidden'});
	})
	$('.btn-lnb-close, .rnb-layer-dim').click(function(){
		$('.rnb-layer-inner').stop().animate({right:'-100%'},200,function(){
			$('.rnb-layer').removeClass('on');
		});
		$('html,body').css({'position':'static','overflow':'visible'});
	});

	//lnb category submenu open
	$('.main_category a').click(function(){
		var subMenu = $(this).next('.sub_category');
		if( subMenu.css('display') == 'block' ){
			subMenu.hide();
		}else{
			$(this).parent('li').siblings().find('.sub_category').hide();
			subMenu.show();
		}
	});

	//header > search popup
	$('#btn_search').click(function(){
		$('.pop_search').show();
		$('html,body').css({'position':'absolte','overflow':'hidden'});
	});
	$('.pop_search .close_search').click(function(){
		$('.pop_search').hide();
		$('html,body').css({'position':'static','overflow':'visible'});
	});


	/* ==============================
		component	
	============================== */
	// 탭 메뉴 - 일반형
	$("[data-ui=TabMenu]").each(function() {
		var $ui = $(this);
		var $menu = $ui.find("[data-content=menu]");
		var $content = $ui.find("[data-content=content]");
		$menu.on("click", function(_e) {
			_e.preventDefault();
			var index = $menu.index(this);
			$menu.removeClass("active").removeAttr("title").eq(index).addClass("active").attr("title", "선택됨");
			$content.removeClass("active").eq(index).addClass("active");
		});
	});

	//파일 첨부
	var fileTarget = $('.filebox .upload-hidden');
	fileTarget.on('change', function(){  // 값이 변경되면
		if(window.FileReader){  // modern browser
		  var filename = $(this)[0].files[0].name;
		} 
		else {  // old IE
		  var filename = $(this).val().split('/').pop().split('\\').pop();  // 파일명만 추출
		}
		// 추출한 파일명 삽입
		$(this).siblings('.upload-nm').val(filename);
	});


	/* ==============================
		content	
	============================== */
	//메인 > 메인 비주얼 슬라이드 
	$('.main_visual .slide').bxSlider({
		controls: false,
		auto: true
	});

	//메인 > NEW ARRIVALS 슬라이드 
	$('.new_arrivals .goodslist').bxSlider({
		controls: false,
		slideWidth: 320,
		minSlides: 2,
		maxSlides: 2,
		moveSlides: 2
	});

	//메인 > BEST SELLER 슬라이드
	$('.best_slider').carousel({
		num: 3,
		maxWidth: 135,
		maxHeight: 200,
		distance: 80,
		autoPlay: false,
		scale: 0.9,
		animationTime: 500,
		showTime: 500
	});

/*
	$('.best_seller .goodslist').slick({
		infinite: true,
		centerMode: true,
		centerPadding: '34.5%',
		//centerPadding: '33.6%',
		slidesToShow: 1,
		zIndex: 1,
		arrows: false
	});

	var slickOn = function(){
		$('.best_seller .slick-slide').removeClass('on');
		$('.best_seller .slick-active').next('.slick-slide').addClass('on');
		$('.best_seller .slick-active').prev('.slick-slide').addClass('on');
	};
	slickOn();

	$('.best_seller .goodslist').on('swipe', function(){
		slickOn();
	});
*/

/*
    var swiper = new Swiper('.swiper-container', {
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: '3',
		loop: true
    });
*/
	//메인 > 하단 슬라이드 
	$('.btm_banner .slide').bxSlider({
		controls: false
	});
	
	//리스트 > breadcrumb 드롭다운
	$('.breadcrumb .depth2 > li > a').click(function(){
		if( $(this).next('.depth3').css('display') == 'none' ){
			$(this).parents('li').siblings().find('.depth3').hide();
			$(this).next('.depth3').show();
			$(this).parents('.breadcrumb').addClass('on');
			$('body').css({'position':'fixed','overflow':'hidden'});
		}else{
			$(this).next('.depth3').hide();
			$(this).parents('.breadcrumb').removeClass('on');
			$('body').css({'position':'static','overflow':'visible'});
		}
	});

	//리스트 > 필터 레이어 팝업
	$('.btn_filter').click(function(){
		$('.filter_pop').show();
		$('html,body').css({'position':'fixed','overflow':'hidden'});
	});
	$('.filter_pop .btn_close').click(function(){
		$('.filter_pop').hide();
		$('html,body').css({'position':'static','overflow':'visible'});
	});

	$('.filter_menu .filter_name').click(function(){ //필터 메뉴 오픈
		var filterMenu = $(this).parent('li');
		if( $(this).next('.filter_con').css('display') == 'none' ){
			filterMenu.siblings('li').removeClass('on');
			filterMenu.addClass('on');
		}else{
			filterMenu.removeClass('on');
		}
	});








});
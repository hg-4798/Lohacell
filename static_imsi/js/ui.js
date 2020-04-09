$(document).ready(function(){ 

	//레이팝업 공통 닫기
	var LayerPopClose= $('.layer-dimm-wrap button.btn-close');
	var LayerPop = $('.layer-dimm-wrap');

	LayerPopClose.click(function(){
		LayerPop.fadeOut('fast');
	});

	$('.store-list dt').hover(function(){
		$(this).next().fadeIn(200);
	});
	$('.store-list dt').mouseleave(function(){
		$(this).next().fadeOut(200);
	});

	//뭐지핫티? 레이어팝업
	$('#btnAboutHott').click(function(){
		$('.layer-about-hott').fadeIn('fast');
	})

	//회원가입 레이어팝업
	$('#btnJoin01').click(function(){
		$('#joinLayer01').fadeIn('fast');
	});

	//매장 레이어팝업
	$('#btnLocation').click(function(){
		$('.layer-location').fadeIn('fast');
	})

	//회원가입 약관 레이어팝업
	$('.term01 .term_view').click(function(){
		$('.layer-dimm-wrap.layer_term_use01').fadeIn();
	});

	$('.term02 .term_view').click(function(){
		$('.layer-dimm-wrap.layer_term_use02').fadeIn();
	});

	$('.term03 .term_view').click(function(){
		$('.layer-dimm-wrap.layer_term_use03').fadeIn();
	});

	$('.term04 .term_view').click(function(){
		$('.layer-dimm-wrap.layer_term_use04').fadeIn();
	});

	$('.term05 .term_view').click(function(){
		$('.layer-dimm-wrap.layer_term_use05').fadeIn();
	});

});

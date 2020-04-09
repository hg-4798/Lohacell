function tabMenu(tName){
				$(tName+" ul").not(":first").hide();
				$(tName+" p").not(":first").hide();
				$(tName+">li>a").mouseover(function(){
					$(tName+" ul").hide();
					$(tName+" p").hide();
					$(this).siblings().show();
					
					$(tName+">li>a>img").each(function(){
						
						$(this).attr("src",$(this).attr("src").replace("_over.gif",".gif"));
					});
					$(">img",this).attr("src",$(">img",this).attr("src").replace(".gif","_over.gif"));
				});
			};

			$(function(){
				tabMenu("#tabMenu");
				tabMenu("#tabMenu1");
				tabMenu("#tabMenu2");
				tabMenu("#tabMenu3");

			});


function bluring(){
if(event.srcElement.tagName=="A"||event.srcElement.tagName=="IMG") document.body.focus();
}
//document.onfocusin=bluring;



$(document).ready(function(){
	$("#all_cate").click(function(){
		$('#viewallcate').toggle(function(){
            if ($(this).css('display') == 'none') {
                $('#arr_cate').attr("src", $('#arr_cate').attr("src").replace("../image/header/top_left_04_over.gif", "../image/header/top_left_04.gif"));
            } else {
                $('#arr_cate').attr("src", $('#arr_cate').attr("src").replace("../image/header/top_left_04.gif", "../image/header/top_left_04_over.gif"));
            }
		});
		$('#arr_cate').attr('src', '../image/header/top_left_04_over.gif');
	});

$("#close_allmenu").click(function(){
		var toggle_switch = $(this);
		$('#viewallcate').hide();
		$('#arr_cate').attr('src', '../image/header/top_left_04.gif');
	});
});



$(function(){
	$(".my_menu").hover(function(){
		$(this).stop().animate({width:'87px',height:'130px',backgroundColor:"#FFF"},200);
	}, function() {
		$(this).stop().animate({width:'87px',height:'20px',backgroundColor:"#f3f3f3"},200);
	});
});


$(function(){
	$(".quick_bt").hover(function(){
		$(this).stop().animate({left:'-1px',backgroundColor:"#78b396"},200);
	}, function() {
		$(this).stop().animate({left:'-66px',backgroundColor:"#cbcbcb"},200);
	});
});


$(function(){
	$(".quick_bt2").hover(function(){
		$(this).stop().animate({left:'-20px'},200);
	}, function() {
		$(this).stop().animate({left:'-66px'},200);
	});
});


$(function(){
		$('#top_category li').bind('mouseenter',function(){
			var $elem = $(this);
			$elem.find('.sdt_wrap')
				 .stop(true)
				 .animate({'margin-top':'3px'},200)
				 .andSelf()
			.find('.sdt_box')
				 .stop(true)
				 .animate({height:'100px',opacity:'100'},300);

		}).bind('mouseleave',function(){
			var $elem = $(this);			
			$elem.find('.sdt_wrap')
				 .stop(true)
				 .animate({'margin-top':'0'},200)
                 .andSelf()
			.find('.sdt_box')
				 .stop(true)
				 .animate({height:'0',opacity:'0'},300);
		});
	});

$(function(){
		$('#lecipe_wrap').bind('mouseenter',function(){
			var $elem = $(this);
			$elem.find('.lecipe')
				 .stop(true)
				 .animate({'margin-top':'3px'},200)
				 .andSelf()
			.find('.lecipe_box')
				 .stop(true)
				 .animate({height:'220px',opacity:'90'},300);

		}).bind('mouseleave',function(){
			var $elem = $(this);			
			$elem.find('.lecipe')
				 .stop(true)
				 .animate({'margin-top':'0'},200)
                 .andSelf()
			.find('.lecipe_box')
				 .stop(true)
				 .animate({height:'0',opacity:'0'},300);
		});
	});



$(document).ready(function(){	
						var sudoSlider_s1 = $("div.slides_container1").sudoSlider({
							effect: "slide",
							continuous:true,
							slideCount:3,
							prevNext:false,
							moveCount:1,
							customLink:'a.slides_container1',
							auto:true
					   });
});



$(document).ready(function(){	
						var sudoSlider_s2 = $("div.slider1").sudoSlider({
							effect: "fade",
							continuous:true,
							slideCount:1,
							prevNext:false,
							moveCount:1,
							customLink:'li.a_big_banner_title',
							auto:true,
							animationZIndex:80
					   });
					});


$(document).ready(function(){	
		    var sudoSlider_s3 = $("div.slider2,#productListBest,div.gongu_list_wrap div.gongu_list").sudoSlider({
				autowidth:false,
				slideCount:100,
				continuous:true,
                effect: "slide",
				auto:true,
				prevNext:false,
				customLink:'a.best_slider_btn'
		   });
		});

$(document).ready(function(){	
		    var sudoSlider_s4 = $("div.gongu_list_wrap div.gongu_list").sudoSlider({
				autowidth:false,
				slideCount:100,
				continuous:true,
                effect: "slide",
				auto:true,
				prevNext:false,
				customLink:'a.best_slider_btn'
		   });
		});


$(document).ready(function(){	
							var sudoSlider_s5 = $("div.event_content_rolling").sudoSlider({
								effect: "slide",
								continuous:true,
								auto:true,
								prevNext:false,
								fadespeed: '200',
								beforeAnimation: function(slide){
									$('li.roll_titles').removeClass('on');
									var substract = $('#event_roll_title_ul').offset();
									var position = $('#event_roll_title_ul li').eq(slide-1).offset();
									var marginTop = position.top-substract.top;
									var speed = 800;
									$('#event_roll_title_li').animate(
										{ marginTop: marginTop},
										{
											queue:false,
											duration:speed
										}
									);
									
									
									
								},
								afterAnimation:function(slide){
									$('li.roll_titles').eq(slide-1).addClass('on');
								},
								customLink: '.roll_titles'
						   });
						});

/*var slideNum = slide-1;
									if(slideNum<1){
										slideNum = $("#slider3 ul li").size()-2;
									}
									$("#slider3 ul li:eq("+slideNum+") dd.detail_word").hide();*/


$(document).ready(function(){	
							var sudoSlider_s6 = $("#slider3").sudoSlider({
								effect: "fade",
								continuous:true,
								auto:true,
								prevNext:false,
								fadespeed: '200',
								numeric:'pages',
								beforeAnimation: function(slide){
									var slideNum = slide-1;
									if(slideNum<1){
										slideNum = $("#slider3 ul li").size()-2;
									}
									$("#slider3 ul li:eq("+slideNum+") dd.detail_word").hide();
								},
								afterAnimation: function(slide){
									var slideNum = slide-1;
									if(slideNum<1){
										slideNum = $("#slider3 ul li").size()-2;
									}
									$("#slider3 ul li:eq("+slideNum+") dd.detail_word").show();
								}
						   });
						});


$(document).ready(function(){	
							var sudoSlider_s7 = $("#slider4").sudoSlider({
								effect: "fade",
								continuous:true,
								auto:true,
								prevNext:false,
								fadespeed: '200',
								numeric:'pages',
								beforeAnimation: function(slide){
									var slideNum = slide-1;
									if(slideNum<1){
										slideNum = $("#slider3 ul li").size()-2;
									}
									$("#slider4 ul li:eq("+slideNum+") dd.detail_word").hide();
								},
								afterAnimation: function(slide){
									var slideNum = slide-1;
									if(slideNum<1){
										slideNum = $("#slider3 ul li").size()-2;
									}
									$("#slider4 ul li:eq("+slideNum+") dd.detail_word").show();
								}
						   });
						});

$(document).ready(function(){	
						var sudoSlider_s12 = $("div.related_goods_slider").sudoSlider({
							effect: "slide",
							speed:1000,
							continuous:true,
							slideCount:7,
							prevNext:false,
							moveCount:1,
							customLink:'div.related_products a.slider_btn',
							auto:false,
							animationZIndex:0
					   });
					});

function jsSetComa(str_result){
 var reg = /(^[+-]?\d+)(\d{3})/;   // 정규식
 str_result += '';  // 숫자를 문자열로 변환
 while (reg.test(str_result)){
  str_result = str_result.replace(reg, '$1' + ',' + '$2');
 }
 
 return str_result;
}

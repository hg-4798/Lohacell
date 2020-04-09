
$(document).ready(function(){
// 상단고정
	var nav = $('#header');
    $(window).scroll(function () {
        if ($(this).scrollTop() >100) {
            nav.addClass("float-nav");
        }else {
            nav.removeClass("float-nav");
        }
    });

// 하단고정

$('.scrollup').hide();
$(window).scroll(function(){
       
		if ($(this).scrollTop() > 100) {
                $('.scrollup').fadeIn();
        } else {
                $('.scrollup').fadeOut();
            }
        }); 
        $('.scrollup').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 1000);
            return false;
        });


$(window).on("scroll", function() { 

var scrollHeight = $(document).height(); 
var scrollPosition = $(window).height() + $(window).scrollTop(); 
	if ((scrollHeight - scrollPosition) / scrollHeight === 0) { 
 	    $('.scrollup').addClass("scrollup2");
		$('.lnb_nav').addClass("lnb_fixed");
	 } else {
		 $('.scrollup').removeClass("scrollup2");
		 $('.lnb_nav').removeClass("lnb_fixed");
	} 
}); 

//tab
	$(".tab_c").hide(); 
	$("ul.tab li:first").addClass("active").show(); 
	$(".tab_c:first").show(); 

	$("ul.tab li").click(function() {
		$("ul.tab li").removeClass("active"); 
		$(this).addClass("active");  
		$(".tab_c").hide(); 
		var activeTab = $(this).find("a").attr("href");
		$(activeTab).show(); 
		return false;
	});
//wing tab
	$(".top_con").hide(); 
	$("ul.member_tab li:first").addClass("active").show(); 
	$(".top_con:first").show(); 

	$("ul.member_tab li").click(function() {
		$("ul.member_tab li").removeClass("active"); 
		$(this).addClass("active");  
		$(".top_con").hide(); 
		var activeTab = $(this).find("a").attr("href");
		$(activeTab).show(); 
		return false;
	});

//popup
	$(".pop_info").hide(); 
	$(".btn_member_more").click(function() {
		$(".pop_info").fadeIn(); 
	});
	$(".btn_popclose").click(function() {
		$(".pop_info").hide(); 
	});

//list align
$("ul.align_cate li").click(function() {
		$("ul.align_cate li").removeClass("align_on"); 
		$(this).addClass("align_on");  
	return false;
	});
//hover 
/*$("ul.goods_list li span.hover_off").mouseover(function() {
	 $("span.hover").removeClass("hover_on") 
		$(this).addClass("hover_on");  
	});*/
$( "ul.goods_list li " ).hover(

  function() {

    $( this ).append( $( "<span> ***</span>" ) );

  }, function() {

    $( this ).find( "span:last" ).remove();

  }

);

$( "ul.product_list li " ).hover(

  function() {

    $( this ).append( $( "<span> ***</span>" ) );

  }, function() {

    $( this ).find( "span:last" ).remove();

  }

);

 



});




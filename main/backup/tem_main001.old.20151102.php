<?php

/*if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}*/

$imagepath=$Dir.DataDir."shopimages/etc/main_logo.gif";
$flashpath=$Dir.DataDir."shopimages/etc/main_logo.swf";
$menu_type2 ="Y";
if (file_exists($imagepath)) {
	$mainimg="<img src=\"".$imagepath."\" border=\"0\" align=\"absmiddle\">";
} else {
	$mainimg="";
}
if (file_exists($flashpath)) {
	if (preg_match("/\[MAINFLASH_(\d{1,4})X(\d{1,4})\]/",$_data->shop_intro,$match)) {
		$width=$match[1];
		$height=$match[2];
	}
	$mainflash="<script>flash_show('".$flashpath."','".$width."','".$height."');</script>";
} else {
	$mainflash="";
}
$pattern=array("(\[DIR\])","(\[MAINIMG\])","/\[MAINFLASH_(\d{1,4})X(\d{1,4})\]/");
$replace=array($Dir,$mainimg,$mainflash);
$shop_intro=preg_replace($pattern,$replace,$_data->shop_intro);


if (stripos($shop_intro,"<table")!==false || strlen($mainflash)>0)
	$main_banner=$shop_intro;
else
	$main_banner=nl2br($shop_intro);

?>
<?include ($Dir.MainDir.$_data->menu_type.".php");?>
<!--won function -->
<?
$imgurl=$Dir.DataDir."shopimages/mainbanner/";
$goodslist=$main_disp_goods[1] ;

$goodslistArray[0] = $main_disp_goods[2];
$goodslistArray[1] = $main_disp_goods[3];
$goodslistArray[2] = $main_disp_goods[4];
$pick_articleArray = array("a1","a2","a3","a4");
$pick_articleClass = array("w190","w280","w130","w190");
$pick_imgClass = array("img190","img280","img130","img190");
$pick_sizeArray = array(190,280,140,190);
$pick_numArray1 = array(0,2,3,5);
$pick_numArray2 = array(1,"x",4,6);

//mdpick배너 이미지 받아오기
$mdpick = $mainBanner[mdpick_banner];

//메인 상단 롤링 배너 이미지 받오이기
$mainbanner = $mainBanner[maintop_rolling];
//메인 중단 배너 롤링 이미지 받아오기
$middlebannerimg = $mainBanner[mainmiddle_rolling];
function max2($numberArray){
	if(count($numberArray) <=3 ){
		return $max = 3;
	}
	else{
		return $max=count($numberArray);
	}
} //출력이 3개 미만으로 될시 디자인 흐트러지는것 때문에 최소 3개는 출력되도록

$max=count($goodslist); //NEW ARRIVALS 노출수 제한 나중에 조건 추가해야됨

//카테고리 BEST ITEM BAGS받아오기
$bagslist_sql="
SELECT a.category_idx,a.sort,b.productcode,b.productname,b.sellprice,b.consumerprice,b.tinyimage,c.code_a,b.reserve,b.reservetype
FROM tblrecommendlist a
JOIN tblproduct b ON a.pridx=b.pridx
JOIN tblproductcode c ON a.category_idx=c.idx
WHERE b.display ='Y'
AND code_b = '000'
AND group_code != 'NO'
AND a.category_idx='107'
ORDER BY a.category_idx,a.sort ASC
LIMIT 3";
$bagslist_res = pmysql_query($bagslist_sql, get_db_conn());
while($bagslist_row = pmysql_fetch_array($bagslist_res)){
	$bagslist[]=$bagslist_row;
}
//카테고리 BEST ITEM WALLET 받아오기
$walletlist_sql="
SELECT a.category_idx,a.sort,b.productcode,b.productname,b.sellprice,b.consumerprice,b.tinyimage,c.code_a,b.reserve,b.reservetype
FROM tblrecommendlist a
JOIN tblproduct b ON a.pridx=b.pridx
JOIN tblproductcode c ON a.category_idx=c.idx
WHERE b.display ='Y'
AND code_b = '000'
AND group_code != 'NO'
AND a.category_idx='108'
ORDER BY a.category_idx,a.sort ASC
LIMIT 3";

$walletlist_res = pmysql_query($walletlist_sql, get_db_conn());
while($walletlist_row = pmysql_fetch_array($walletlist_res)){
	$walletlist[]=$walletlist_row;
}

//카테고리 BEST ITEM 악세서리 받아오기
$outlet_sql="
SELECT a.category_idx,a.sort,b.productcode,b.productname,b.sellprice,b.consumerprice,b.tinyimage,c.code_a,b.reserve,b.reservetype
FROM tblrecommendlist a
JOIN tblproduct b ON a.pridx=b.pridx
JOIN tblproductcode c ON a.category_idx=c.idx
WHERE b.display ='Y'
AND code_b = '000'
AND group_code != 'NO'
AND a.category_idx='109'
ORDER BY a.category_idx,a.sort ASC
LIMIT 3";

$outlet_res = pmysql_query($outlet_sql, get_db_conn());
while($outlet_row = pmysql_fetch_array($outlet_res)){
	$outlet[]=$outlet_row;
}

//카테고리 BEST ITEM 시즌오프 받아오기
$starshop_sql="
SELECT a.category_idx,a.sort,b.productcode,b.productname,b.sellprice,b.consumerprice,b.tinyimage,c.code_a,b.reserve,b.reservetype
FROM tblrecommendlist a
JOIN tblproduct b ON a.pridx=b.pridx
JOIN tblproductcode c ON a.category_idx=c.idx
WHERE b.display ='Y'
AND code_b = '000'
AND group_code != 'NO'
AND a.category_idx='110'
AND c.display_list is NULL
ORDER BY a.category_idx,a.sort ASC
LIMIT 3";

$starshop_res = pmysql_query($starshop_sql, get_db_conn());
while($starshop_row = pmysql_fetch_array($starshop_res)){
	$starshop[]=$starshop_row;
}

##### 공지사항
$sql_notice = "SELECT writetime, num, title FROM tblboard where board='notice' ORDER BY writetime DESC LIMIT 4 OFFSET 0";
$res_notice = pmysql_query($sql_notice);
while($row_notice = pmysql_fetch_array($res_notice)){
	$data_notice[]=$row_notice;
}
pmysql_free_result($res_notice);


?>

<!-- won fuction end-->
<HTML>

<script type="text/javascript" src="<?=$Dir?>js/modernizr.custom.js"></script>
<script type="text/javascript" src="<?=$Dir?>js/jquery.cbpFWSlider.js"></script>
<script type="text/javascript">
<!--

function goView(num,board) { //공지사항 보기
	document.form2.action="/board/board.php";
	document.form2.num.value=num;
	document.form2.board.value=board;
	document.form2.submit();
};

function displayswitch(id){ //md's pick 메뉴 전환

    var objDiv = document.getElementById(id);
    $(".mds_pick").hide();
	if(id==('mds_0')){
		$("#mds_0").show();
		$("#menu1").css("color","red");
		$("#menu2").css("color","");
		$("#menu3").css("color","");
	}
	if(id==('mds_1')){
		$("#mds_1").show();
		$("#menu2").css("color","red");
		$("#menu1").css("color","");
		$("#menu3").css("color","");
	}
	if(id==('mds_2')){
		$("#mds_2").show();
		$("#menu3").css("color","red");
		$("#menu1").css("color","");
		$("#menu2").css("color","");
	}

};

/*function layer_goods_link(linkType,linkUrl){
	
	if(linkType==1){
		CheckForm('',linkUrl);
		return;
	}
	if(linkType==3){
		document.productlist_basket.action="../front/productlist_basket.php";
		document.productlist_basket.target="test";
		document.productlist_basket.productcode2.value=linkUrl;
		window.open("","test","width=401,height=309,scrollbars=no,resizable=no, status=no,");
		document.productlist_basket.submit();
		document.back.submit();
		return;
	}
	if(linkType==2){
		location.href=linkUrl;
		return;
	}
	if(linkType==0){
		location.href=linkUrl;
		return;
	}
}
*/
function CheckForm(gbn,temp2) {
	var itemCount = 0;

	if(gbn=="ordernow") {
		document.form1.ordertype.value="ordernow";
	}

	if (gbn != "ordernow"){
		document.form1.action="../front/confirm_basket.php";
		document.form1.target="confirmbasketlist";
		document.form1.productcode.value= temp2;
		window.open("about:blank","confirmbasketlist","width=401,height=309,scrollbars=no,resizable=no, status=no,");
		document.form1.submit();
	}

}

$(document).ready(function(){

	var sudoSliderMainTop = $("div.banner_top_slider").sudoSlider({
		effect: "slide",
		continuous:true,
		slideCount:3,
		prevNext:true,
		moveCount:1,
		customLink:'div.main_visual_rolling p a',
		auto:true,
		pause:4000,
		animationZIndex:80
	});

	var sudoSliderMainBottom = $("div.banner_bottom_slider").sudoSlider({
		effect: "slide",
		continuous:true,
		slideCount:1,
		prevNext:false,
		moveCount:1,
		customLink:'a.a_rtop_banner_bottom',
		auto:true,
		pause:4000,
		animationZIndex:80
	});

	$(".visual_list").show();
	$(".visual_list_btn").show();
	$(".visual_list_loading").hide();

	$("#middle_slide").sudoSlider({
	effect: "slide",
	speed:1000,
	continuous:true,
	slideCount:4,
	prevNext:false,
	moveCount:1,
	customLink:'a.prevnext',
	autoheight:false,
	auto:false,
	animationZIndex:1,
	});

	$("#bestitem_slide").sudoSlider({
		effect: "fade",
		speed:1000,
		continuous:true,
		slideCount:1,
		prevNext:false,
		moveCount:1,
		customLink:'a.bi_btn',
		autoheight:false,
		auto:false,
		animationZIndex:1
	});

   var autoStopped_fix = false;


   var mainSide = $("#main_slide").sudoSlider({
	   effect: "slide",
		continuous:true,
		slideCount:3,
		prevNext:false,
		moveCount:1,
		autoWidth:false,
		autoHeight:true,
		auto:true,
		pause:4000,
		customlink: 'a.sudoSlide-on',
		animationZIndex:80,
		initcallback:function(){
			$(this).width('100%');
			$(this).find("ul").css('width', 100 * $(this).find("li").length + '%');
			$(this).find("li").css('width', 100 / $(this).find("li").length + '%');
		},
		afterAnimation: function(t){
			$(".onNumber").removeClass("on")
			$(".onNumber").each(function(){
				if($(this).attr("rel") == t) $(this).addClass("on");
			});
			if(!mainSide.getValue('autoAnimation')){
				$("a.onStop").hide();
				$("a.onPlay").show();
			}else{
				$("a.onStop").show();
				$("a.onPlay").hide();
			}
		}
    });
    $("a.onStop").click(function(e){
    	$("a.onStop").hide();
		$("a.onPlay").show();
    	mainSide.stopAuto();
    });
    $("a.onPlay").click(function(e){
    	$("a.onStop").show();
		$("a.onPlay").hide();
    	mainSide.startAuto();
    });
    $("#main_slide").find("li").click(function(){
    	location.href = $(this).find("a").attr("href");
    }).hover(function(){
    	$(this).css('cursor','pointer');
    });
});

//-->
$(document).ready(function(){
	
	$(".layer_goods_icon").on("click",function(e){
    	var target = e.target
    	if($(target).attr("class") == "cart" || $(target).attr("class") == "view" ) return; 
    	location.href = $(this).attr("link_url");
    });
    
    $(".cart").on("click",function(e){
    	var chkOption = $(this).attr("option_chk");
    	var chkLink = $(this).attr("cart_chk");
    	if(chkOption == 1){
			CheckForm('',chkLink);
		}else if(chkOption == 3){
	    	$("#productlist_basket").attr("action","../front/productlist_basket.php");
	    	$("#productlist_basket").attr("target","basketOpen");
	    	$("#productcode2").val(chkLink);
			window.open("","basketOpen","width=440,height=420,scrollbars=no,resizable=no, status=no,");
			$("#productlist_basket").submit();
		} 
    });
    
    $(".view").on("click",function(){
    	location.href = $(this).attr("link_url");
    });
});

</script>

<body<?=($_data->layoutdata["MOUSEKEY"][0]=="Y"?" oncontextmenu=\"return false;\"":"")?><?=($_data->layoutdata["MOUSEKEY"][1]=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=($_data->layoutdata["MOUSEKEY"][2]=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>
<?php include_once($Dir.FrontDir."analyticstracking.php") ?>
<div id="body_contents">
	<!-- 메인 롤링 -->
	<div class="index_rolling_wrap" style="position:relative; overflow: hidden;">
		<div class="index_rolling" id="main_slide">
			<ul>
			<?for($i=1 ; $i <= count($mainbanner) ; $i++){?>
				<?if($i==0){?>
				<li style="background: url(<?=$mainbanner[$i][banner_img]?>) 50% 50% no-repeat; height: 480px;">
				<?}else{?>
				<li style="background: url(<?=$mainbanner[$i][banner_img]?>) 50% 50% no-repeat; height: 480px;display:none;">
				<?}?>
					<a href="<?=$mainbanner[$i][banner_link]?>"></a>
				</li>
			<? } ?>
			</ul>
		</div>
		<img src="../m/img/won_arrow1.png" style="z-index:10;position:relative;margin-top:-260px;margin-left:20px; cursor:pointer" onclick="p_n_click(1)">
		<img src="../m/img/won_arrow2.png" style="z-index:10;position:relative;margin-top:-260px;margin-right:70px;float:right; cursor:pointer" onclick="p_n_click()">
		<span id="controls" class="mainR_control">
			<ol class="controls" style="position:relative; bottom:0px; left:43%; z-index:10;">
			<li><a class="sudoSlide-on onStop" style="cursor: pointer;" ></a></li>
			<li><a class="sudoSlide-on onPlay" style="cursor: pointer;display: none;" ></a></li>
			<? for($i=1 ; $i <= count($mainbanner) ; $i++){ ?>
				<li class="onNumber <? if($i==0){echo "on";}?>" rel="<?=$i?>"><a class="sudoSlide-on" href="javascript:void(<?$i?>);" data-target="<?=$i?>"><span><?=$i?></span></a></li>
			<? } ?>
			</ol>
		</span>
	</div>
	<a href="javascript:vode(0);" id="p1" class="prev sudoSlide-on" data-target="prev"></a>
	<a href="javascript:vode(0);" id="n1" class="next sudoSlide-on" data-target="next"></a>


	<style type="text/css">
	div.index_rolling ul li{text-align:center;}
	span.mainR_control {position:absolute; display:block; bottom:0px; width:100%; text-align:center;}
	span.mainR_control ul {display:inline-block;}
	</style>
	<script>
	function p_n_click(onNumber){
		if (onNumber==1)
		{
			document.getElementById("p1").click();
		}else{
			document.getElementById("n1").click();
		}

	}
	</script>

	<div class="containerBody">

		<!-- 중간 소배너 4개 -->
		<ul class="main_middle_banner">
		<? for($i=1 ; $i<=4 ; $i++){?>
			<li><a href="<?=$middlebannerimg[$i][banner_link];?>"><img src="<?=$middlebannerimg[$i][banner_img];?>" alt="" /></a></li> <?}?>

		</ul><!-- //중간 소배너 4개 -->

		<div class="new_arrival_slide">
			<h3 class="title">NEW ARRIVALS</h3>
			<div class="new_goods4ea">

					<a href="javascript:void(0);" class="arrow right prevnext" data-target="next"  title="왼쪽 이동"></a>
					<a href="javascript:void(0);" class="arrow left prevnext" data-target="prev"  title="왼쪽 이동"></a>
			<div id="middle_slide" style="width: 1101px;">
				<ul class="list" style="margin-left: 1px;">
					 <? for ($i=0; $i< $max; $i++ ) { ?>
					<li class="in_icon">
						<div class="goods_A"> <!--new arrivals 첫번째 리스트-->
							<a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$goodslist[$i][productcode]?>">
								<p class="img190">
									<img src="<?=$Dir.DataDir."shopimages/product/".$goodslist[$i][tinyimage]?>" width="190" height="190" alt="" />
								</p>
								<span class="subject"><?=$goodslist[$i][productname]?></span>
								
								<span class="price">
									<del><?=number_format($goodslist[$i][consumerprice])?>원</del>&nbsp;
									<!--<?=number_format($goodslist[$i][3])?>원-->
									<?=number_format($goodslist[$i][sellprice])?>원
								</span>
							</a>
						</div>
						<?if($goodslist[$i][option1]) $option_chk=3; else $option_chk=1;?>
						<div class="layer_goods_icon" link_url="<?=$Dir.FrontDir."productdetail.php?productcode=".$goodslist[$i][productcode]?>">
							<p class="icon">
								<a href="javascript:;" link_url="<?=$Dir.FrontDir."productdetail.php?productcode=".$goodslist[$i][productcode]?>" class="view" title="상세보기"></a>
								<a href="javascript:;" option_chk="<?=$option_chk?>" cart_chk="<?=$goodslist[$i][productcode]?>" class="cart" title="장바구니"></a>
							</p>
						</div>
					</li>
					<? } ?> 
				</ul>
			</div>
		</div><!-- //div.new_arrival_slide -->
		<!--mdpick시작-->
		<div class="mds_pick_section">
			<h3 class="title">MD'S PICK</h3>
			<div class="pick_type">
				<a href="#" id="menu1" onclick="displayswitch('mds_0');return false;" style="color:red;">Trend Item</a>
				<a href="#" id="menu2" onclick="displayswitch('mds_1');return false;">Season Color Trend</a>
				<a href="#" id="menu3" onclick="displayswitch('mds_2');return false;">Style up! Item</a>
			</div>
			<? foreach($goodslistArray as $goodsKey=>$goodsVal){ ?>
			<div class="mds_pick" id="mds_<?=$goodsKey?>" style="<?if($goodsKey == 0){ echo "display:block;"; }else{  echo "display:none;"; }?>">
				<? for($i=0;$i<4;$i++){ ?>
				<ul class="pick_article <?=$pick_articleArray[$i]?>">
					<? if($pick_articleArray[$i] == "a2"){ ?>
					<li><a href="<?=$mdpick[$goodsKey+1][banner_link]?>"><img src="<?=$mdpick[$goodsKey+1][banner_img]?>" style="width:359px;height:178px;" alt="" /></a></li>
					<? } ?>
					<li>
						<div class="goods_A w<?=$pick_sizeArray[$i]?>">
							<a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$goodsVal[$pick_numArray1[$i]][productcode]?>">
								<? if(is_file($Dir.DataDir."shopimages/product/".$goodsVal[$pick_numArray1[$i]][tinyimage])){?>
								<p class="img<?=$pick_sizeArray[$i]?>">
									<img src="<?=$Dir.DataDir.'shopimages/product/'.$goodsVal[$pick_numArray1[$i]][tinyimage]?>" width='<?=$pick_sizeArray[$i]?>' height='<?=$pick_sizeArray[$i]?>' alt='' />
								</p>
								<span class="subject"><?=$goodsVal[$pick_numArray1[$i]][productname]?></span>
								<!-- 업체측에서 쿠폰할인가 노출 안되게 요청함
								<del><span class="price"><?=number_format($$goodsVal[$pick_numArray1[$i]][3])."원";?></span></del>
								<span class="price"><?=number_format($$goodsVal[$pick_numArray1[$i]][sellprice])."원";?></span>
								-->
								<span class="price">
									<del><?=number_format($goodsVal[$pick_numArray1[$i]][consumerprice])?>원</del>
									<!--<?=number_format($goodsVal[$pick_numArray1[$i]][3])."원";?>-->
									<?=number_format($goodsVal[$pick_numArray1[$i]][sellprice])."원";?>
								</span>
								<?} else {?>
								<p class="img<?=$pick_sizeArray[$i]?>"></p>
								<? } ?>
							</a>
						</div>
					</li> 
					<? if($pick_articleArray[$i] != "a2"){ ?>
					<li>
						<div class="goods_A w<?=$pick_sizeArray[$i]?>">
							<a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$goodsVal[$pick_numArray2[$i]][productcode]?>">
								<? if(is_file($Dir.DataDir."shopimages/product/".$goodsVal[$pick_numArray2[$i]][tinyimage])){?>
								<p class="img<?=$pick_sizeArray[$i]?>">
									<img src="<?=$Dir.DataDir.'shopimages/product/'.$goodsVal[$pick_numArray2[$i]][tinyimage]?>" width='<?=$pick_sizeArray[$i]?>' height='<?=$pick_sizeArray[$i]?>' alt='' />
								</p>
								<span class="subject"><?=$goodsVal[$pick_numArray2[$i]][productname]?></span>
								<span class="price">
									<del><?=number_format($goodsVal[$pick_numArray2[$i]][consumerprice])?>원</del>
									<!--<?=number_format($goodsVal[$pick_numArray2[$i]][3])."원";?>-->
									<?=number_format($goodsVal[$pick_numArray2[$i]][sellprice])."원";?>
								</span>
								<?} else {?>
								<p class="img<?=$pick_sizeArray[$i]?>"></p>
								<? } ?>
							</a>
						</div>
					</li>
					<? } ?>
				</ul>
				<? } ?>
			</div> <!-- mds_pick-->
			<? } ?>
		</div><!-- //div.mds_pick_section -->
		
		<div class="best_item_section">
			<h3 class="title">BEST ITEM</h3>
			<div class="category">
				<ul class="list">
				<?for($i=0,$i2=1;$i<4; $i++,$i2++){?>
					<li><a href="#" class="bi_btn" data-target="<?=$i2?>"> <?=$cateListA[$i]->code_name?></a></li>
				<?}?>
				</ul>
				<p class="banner"><a href="/front/promotion.php?pidx=18"><img src="../img/common/banner_p_20150923.jpg" alt="" /></a></p>
			</div>

			<div class="best_item" id="bestitem_slide" >
			<ul class="slide_ul"> <!--ul태그 위에 다른 ul태그 삽입. 이중리스트-->
				<li>
					<ul class="goods" id="bagslist"> <!--best item BAGS출력-->
<?
						for($i2=0; $i2<max2($bagslist);$i2++){
						$bagslist_sellprice=$bagslist[$i2][sellprice];
						##### 쿠폰에 의한 가격 할인
						$cou_data = couponDisPrice($bagslist[$i2][productcode]);
						if($cou_data['coumoney']){
							$nomalprice_baglist = $bagslist[$i2][sellprice];
							$bagslist_sellprice  = $bagslist[$i2][sellprice]-$cou_data['coumoney'];
						}

						#####즉시적립금 할인 적용가 150901원재
		
						if($bagslist[$i2][reserve]>0){
						$ReserveConversionPrice = 0;
						$ReserveConversionPrice = getReserveConversion($bagslist[$i2][reserve], $bagslist[$i2][reserve],$nomalprice_baglist,'Y');
						$bagslist_sellprice  = $bagslist_sellprice  - $ReserveConversionPrice;
						}
		
						##### 오늘의 특가, 타임세일에 의한 가격
						if(getSpeDcPrice($bagslist[$i2][productcode])){
							$bagslist_sellprice = getSpeDcPrice($bagslist[$i2][productcode]);
							if($bagslist_sellprice <= 0){
							$bagslist_sellprice = $bagslist[$i2][sellprice];
							}
						}

						##### //오늘의 특가, 타임세일에 의한 가격
?>
						<li>
							<div class="goods_A">
								<a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$bagslist[$i2][productcode]?>">
									<p class="img190"><? if(is_file($Dir.DataDir."shopimages/product/".$bagslist[$i2][tinyimage])){?><img src="<?=$Dir.DataDir.'shopimages/product/'.$bagslist[$i2][tinyimage]?>" width="190" height="190" alt="" />
									<span class="subject"><?=$bagslist[$i2][productname]?></span>
									<!--
									<del><span class="price"><?=number_format($nomalprice_baglist)."원";?></span></del>
									<span class="price"><?=number_format($bagslist_sellprice)."원";?></span>
									-->
									
									<span class="price">
										<del><?=number_format($bagslist[$i2][consumerprice])?>원</del>
										<?=number_format($bagslist_sellprice)."원";?>
									</span>
									<?}?>
									</p>
								</a>
							</div>
						</li>
						<?}?>
					</ul>
				</li>
				<li>
					<ul class="goods" id="walletlist">
<?
					$max3=count($walletlist);
					for($i3=0; $i3<max2($walletlist);$i3++){
						$walletlist_sellprice=$walletlist[$i3][sellprice];	

						##### 쿠폰에 의한 가격 할인
						$cou_data_w = couponDisPrice($walletlist[$i3][productcode]);
						if($cou_data_w['coumoney']){
							$nomalprice_walletlist = $walletlist[$i3][sellprice];
							$walletlist_sellprice  = $walletlist[$i3][sellprice]-$cou_data_w['coumoney'];
						}

						#####즉시적립금 할인 적용가 150901원재
		
						if($walletlist[$i3][reserve]>0){
							$ReserveConversionPrice = 0;
							$ReserveConversionPrice = getReserveConversion($walletlist[$i3][reserve], $walletlist[$i3][reserve],$nomalprice_walletlist,'Y');
							$walletlist_sellprice  = $walletlist_sellprice  - $ReserveConversionPrice;
						}
						
						##### 오늘의 특가, 타임세일에 의한 가격
						if(getSpeDcPrice($walletlist[$i3][productcode])){
							$walletlist_sellprice = getSpeDcPrice($walletlist[$i3][productcode]);
							if($walletlist_sellprice <= 0){
							$walletlist_sellprice = $walletlist[$i3][sellprice];
							}
						}

						##### //오늘의 특가, 타임세일에 의한 가격
?>
						<li>
							<div class="goods_A">
								<a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$walletlist[$i3][productcode]?>">
									<p class="img190"><? if(is_file($Dir.DataDir."shopimages/product/".$walletlist[$i3][tinyimage])){?><img src="<?=$Dir.DataDir.'shopimages/product/'.$walletlist[$i3][tinyimage]?>" width="190" height="190" alt="" />
									<span class="subject"><?=$walletlist[$i3][productname]?></span>
									<!--
									<del><span class="price"><?=number_format($nomalprice_walletlist)."원";?></span></del>
									-->
									<span class="price">
										<del><?=number_format($walletlist[$i3][consumerprice])?>원</del>
										<?=number_format($walletlist_sellprice)."원";?>
									</span>
									<?}?>
									</p>
								</a>
							</div>
						</li>
					<?}?>
					</ul>
				</li>

				<li>
					<ul class="goods" id="outlet">
<?
					$max4=count($outlet);
					for($i4=0; $i4<max2($outlet);$i4++){ 
						$outlet_sellprice = $outlet[$i4][sellprice];

						##### 쿠폰에 의한 가격 할인
						$cou_data_o = couponDisPrice($outlet[$i4][productcode]);
						if($cou_data_o['coumoney']){
							$nomalprice_outlet = $outlet[$i4][sellprice];
							$outlet_sellprice  = $outlet[$i4][sellprice]-$cou_data_o['coumoney'];
							}

						#####즉시적립금 할인 적용가 150901원재
		
						if($outlet[$i4][reserve]>0){
							$ReserveConversionPrice = 0;
							$ReserveConversionPrice = getReserveConversion($outlet[$i4][reserve], $outlet[$i4][reserve],$nomalprice_outlet,'Y');
							$outlet_sellprice  = $outlet_sellprice  - $ReserveConversionPrice;
						}

						##### 오늘의 특가, 타임세일에 의한 가격
						if(getSpeDcPrice($outlet[$i4][productcode])){
							$outlet_sellprice = getSpeDcPrice($outlet[$i4][productcode]);
							if($outlet_sellprice <= 0){
							$outlet_sellprice = $outlet[$i4][sellprice];
							}
						}

						##### //오늘의 특가, 타임세일에 의한 가격
?>
						<li>
							<div class="goods_A">
								<a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$outlet[$i4][productcode]?>">
									<p class="img190">
									<? if(is_file($Dir.DataDir."shopimages/product/".$outlet[$i4][tinyimage])){?>
										<img src="<?=$Dir.DataDir.'shopimages/product/'.$outlet[$i4][tinyimage]?>" width="190" height="190" alt="" />
									<span class="subject"><?=$outlet[$i4][productname]?></span>
									<span class="price">
										<del><?=number_format($outlet[$i4][consumerprice])?>원</del>
										<?=number_format($outlet_sellprice)."원";?>
									</span> 
									<?}?>
									</p>
								</a>
							</div>
						</li>
					<?}?>
					</ul>
				</li>

				<li>
					<ul class="goods" id="$starshop"> <!--starshop출력-->
<?
					$max4=count($starshop);
					for($i5=0; $i5<max2($starshop);$i5++){ 
					
						$starshop_sellprice = $starshop[$i5][sellprice];

						##### 쿠폰에 의한 가격 할인
						$cou_data_s = couponDisPrice($starshop[$i5][productcode]);
						if($cou_data_s['coumoney']){
							$nomalprice_starshop = $starshop[$i5][sellprice];
							$starshop_sellprice  = $starshop[$i5][sellprice]-$cou_data_s['coumoney'];
							}

						if($starshop[$i5][reserve]>0){
							$ReserveConversionPrice = 0;
							$ReserveConversionPrice = getReserveConversion($starshop[$i5][reserve], $starshop[$i5][reserve],$nomalprice_starshop,'Y');
							$starshop_sellprice  = $starshop_sellprice  - $ReserveConversionPrice;
						}
						##### 오늘의 특가, 타임세일에 의한 가격
						if(getSpeDcPrice($starshop[$i5][productcode])){
							$starshop_sellprice = getSpeDcPrice($outlet[$i5][productcode]);
							if($starshop_sellprice <= 0){
							$starshop_sellprice = $starshop[$i5][sellprice];
							}
						}

						##### //오늘의 특가, 타임세일에 의한 가격
?>
						<li>
							<div class="goods_A">
								<a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$starshop[$i5][productcode]?>">
									<p class="img190"><? if(is_file($Dir.DataDir."shopimages/product/".$starshop[$i5][tinyimage])){?><img src="<?=$Dir.DataDir.'shopimages/product/'.$starshop[$i5][tinyimage]?>" width="190" height="190" alt="" />
									<span class="subject"><?=$starshop[$i5][productname]?></span>
									<span class="price">
										<del><?=number_format($starshop[$i5][consumerprice])?>원</del>
										<?=number_format($starshop_sellprice)."원";?>
									</span> 
									<?}?>
									</p>
								</a>
							</div>
						</li>
					<?}?>
					</ul>
				</li>

			</ul><!--ul태그 밖에 또다른 ul태그 끝-->
			</div><!--<div class="best_item"끝-->
		</div><!-- //div.best_item_section -->
		
		<div class="bottom_visual">
			<ul class="banner">
				<li><a href="/front/productlist.php?code=005"><img src="../img/common/bottom_b01.jpg" alt="STAR SHOP" /></a></li>
				<li><a href="/front/productlist.php?code=004"><img src="../img/common/bottom_b02.jpg" alt="OUTLET" /></a></li>
				<li><a href="/front/store.php"><img src="../img/common/bottom_b03.jpg" alt="OFFLINE SHOP" /></a></li>
			</ul>
		</div><!-- //div.bottom_visual -->

		<div class="bottom_cs_section">
			<div class="wrap a1">
				<h5>CUSTOMER SERVICE</h5>
				<p class="tel">070&bull;8290&bull;3187</p>
				<ul class="address">
					<li>평일. 09:00 ~ 18:00 (토/일/공휴일 휴무)</li>
					<li>점심. 12:00 ~ 13:00</li>
					<li>* 토/일/공휴일에는 1:1문의를 이용해 주세요</li>
				</ul>
				<p class="btn"><a href="/front/mypage_personal.php"><img src="../img/btn/btn_qna_bbs.gif" alt="일대일문의 게시판" /></a></p>
			</div>

			<div class="wrap">
				<ul class="icon">
					<li>
						<a href="/board/board.php?board=faq">
							<p class="faq"></p>
							<span>FAQ</span>
						</a>
					</li>
					<li>
						<a href="/board/board.php?board=qna">
							<p class="qna"></p>
							<span>Q&amp;A</span>
						</a>
					</li>
					<li>
						<a href="/front/reviewall.php">
							<p class="review"></p>
							<span>REVIEW</span>
						</a>
					</li>
				</ul>
			</div>
			<!--공지사항 불러오는 폼-->
			<form name=form2 method=get action="<?=$_SERVER['PHP_SELF']?>">
			<input type="hidden" name="pagetype" value="view">
			<input type="hidden" name="listnum" value="<?=$listnum?>">
			<input type="hidden" name="sort" value="<?=$sort?>">
			<input type="hidden" name="block" value="<?=$block?>">
			<input type="hidden" name="gotopage" value="<?=$gotopage?>">
			<input type="hidden" name="category_code" value="<?=$category_code?>">
			<input type="hidden" name="searchtxt" value="<?=$searchtxt?>">
			<input type="hidden" name="tab" value="<?=$tab?>">
			<input type="hidden" name="num" value="">
			<input type="hidden" name="board" value="">
			</form>

			<div class="wrap a3">
				<h5>NEWS & NOTICE<a style="float:right;" class="btn_more" href="/board/board.php?board=notice" target="_self"><img style="margin-left:-125px" src="<?=$Dir?>img/button/customer_main_notice_more_btn.gif" alt="공지사항 더보기" /></a></h5>

				<ul class="notice">
				<?php
					foreach($data_notice as $notice){
					$reg_date = date("Y-m-d",$notice['writetime']);
				?>
					<li>
						<a href="javascript:goView('<?=$notice['num']?>','notice')" target="_self">
						<span class="title"><?=$notice['title']?></span>
						<span class="date"><?=$reg_date?></span>
									</a>
					</li><?php }?>

					</ul>

			</div>
		</div><!-- //div.bottom_cs_section -->

	</div><!-- //div.containerBody -->

</div><!-- //#body_contents -->
</div>

<script type="text/javascript">


	///////////////////////////////////////////
</script>
<div id="create_openwin" style="display:none"></div>
<?
	include_once($Dir."lib/eventpopup.php");
	include_once($Dir."lib/eventlayer.php");
	include ($Dir."lib/bottom.php");
?>
<!--///////////////////////////////////////////-->

<form name=form1 id = 'ID_goodsviewfrm' method=post action="<?=$Dir.FrontDir?>basket.php">
	<input type="hidden" name="productcode"></input>
</form>

<form name="productlist_basket" id="productlist_basket">
<input type="hidden" name="productcode2" id="productcode2">
</form>

<form name="back" action="../front/productdetail.php">
<input type="hidden" name="back2" value="1">
</form>

<!--///////////////////////////////////////////-->
<div id="overDiv" style="position:absolute;top:0px;left:0px;z-index:100;display:none;" class="alpha_b60" ></div>
<div class="popup_preview_warp" style="margin-left: 50%;left: -459px;display:none;" ></div>


<!--///////////////////////////////////////////-->
</BODY>
</HTML>

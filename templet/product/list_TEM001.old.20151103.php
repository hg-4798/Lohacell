<?
include_once dirname(__FILE__)."/../../lib/product.class.php";
if(!$product) $product = new PRODUCT();
$menu_type2 = "Y";
$chk_layer=0;
?>

<!-- 메인 컨텐츠 -->
<style>
	div.tab_subnavi{display:none;}
</style>

<script>
$(document).ready(function(){
	$(".tab_subnavi").css("display","block");
});
function goProductDetail(locationUrl){
	location.href = locationUrl;
}

</script>
<?php

	//	<!-- 상품목록 시작 -->
	if($_cdata->islist=="Y"){


	//$sql = "SELECT COUNT(*) as t_count FROM tblproduct AS a ";
	$sql = "SELECT DISTINCT(a.productcode) AS dis, * FROM tblproduct AS a ";
	$sql.= "JOIN tblproductlink link on(a.productcode=link.c_productcode AND c_maincate=1) ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.= $qry." ";
	//exdebug($likeBrand);
	if($likeCate){
		$sql.=$likeCate;
	} else {
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') AND staff_product != '1' ";
	}
	if($likeBrand){
		$sql.=$likeBrand;
	}
	if(strlen($not_qry)>0) {
		$sql.= $not_qry." ";
	}
	//$listnum
	//exdebug($sql);
	$paging = new Tem001_saveheels_Paging($sql,10,$listnum,'GoPage',true);
	$t_count = $paging->t_count;
	$gotopage = $paging->gotopage;
	?>
<div id="body_contents">
	<div class="line_map">
		<div class="container">
		<? for($i=0;$i<count($codenavi);$i++) {?>
			<? if($i != count($codenavi)-1) { ?>
			<em>&gt;</em><a><?=$codenavi[$i]?></a>
			<? } else { ?>
			<em>&gt;</em><span><a><?=$codenavi[$i]?></a></span>
			<? } ?>
		<? } ?>
		</div>

		<h3 class="def"><?=$codenavi[count($codenavi)-1]?></h3>
		<div class="subnavi">
			<? if($cateList){?>
			<ul class="tabs">
			<? foreach($cateList as $lKey=>$lVal){ ?>
				<li <? if($code_b == $lKey) echo "class='active'"?> >
				<?if($lVal[1]){?>
					<a href="#tab<?=$lKey?>"><?=$lVal[0][code_name]?></a>
				<?}else{?>
					<a href="<?=$Dir.FrontDir."productlist.php?code=".$lVal[0]['code_a'].$lVal[0]['code_b']?>"><?=$lVal[0][code_name]?></a>
				<?}?>
				</li>
			<? } ?>
			</ul>
			<? }?>
			<div class="tab_subnavi">
			<? if($cateList){?>
			<? foreach($cateList as $listBKey=>$listBVal){ ?>
				<div id="tab<?=$listBKey?>" class="tab_content">
					<ul>
			<?	foreach($listBVal as $listCKey=>$listCVal) {
					if($listCKey != '0'){
			?>
						<li>
							<a <? if($code_a.$code_b.$code_c == $listCVal['code_a'].$listCVal['code_b'].$listCVal['code_c']) echo "class='on'";?>
								href="<?=$Dir.FrontDir."productlist.php?code=".$listCVal['code_a'].$listCVal['code_b'].$listCVal['code_c']?>"><?=$listCVal['code_name']?>
							</a><? if($listCKey != count($listBVal)-1) echo " | "; ?>
						</li>
			<? 		}
				}
			?>
					</ul>
				</div>
			<? } ?>
			<? }?>
			</div>
		</div>
	</div>

	<div class="containerBody">
		<!-- 중간 소배너 4개 -->
		<ul class="main_middle_banner">
		<? foreach($mainBanner[mainmiddle_rolling] as $bannerVal){ ?>
			<li><a href="<?=$bannerVal[banner_link]?>"><img src="<?=$bannerVal[banner_img]?>" alt="" /></a></li>
		<? } ?>
		</ul><!-- //중간 소배너 4개 -->
		<? if($max>0){ ?>
		<div class="new_arrival_slide">
			<h3 class="title">BEST ITEMS</h3>
			<div class="new_goods4ea">

					<a href="javascript:void(0);" class="arrow right prevnext" data-target="next"  title="왼쪽 이동"></a>
					<a href="javascript:void(0);" class="arrow left prevnext" data-target="prev"  title="왼쪽 이동"></a>
			<div id="middle_slide">
				<ul class="list">
<?					for ($i=0; $i< $max; $i++ ) {

						$onPrice = $goodslist[$i][sellprice];
					 	##### 쿠폰 적용가
						$couData= couponDisPrice($goodslist[$i][productcode]);
						if($couData['coumoney']){
							$nomalprice = $goodslist[$i][sellprice];
							$onPrice = $goodslist[$i][sellprice] - $couData['coumoney'];
						}
						##### //쿠폰 적용가	
						#####즉시적립금 할인 적용가 150901원재
			
						if($goodslist[$i][reserve]>0){
							$ReserveConversionPrice1 = 0;
							$ReserveConversionPrice1 = getReserveConversion($goodslist[$i][reserve], $goodslist[$i][reservetype], $nomalprice ,'Y');
							$onPrice  = $onPrice - $ReserveConversionPrice1;
						}
			
				#####//즉시적립금 할인 적용가
				
						
					 	 ##### 오늘의 특가, 타임세일에 의한 가격
					     if(getSpeDcPrice($goodslist[$i][productcode])){ 
							$onPrice = getSpeDcPrice($goodslist[$i][productcode]);
							if($onPrice <= 0){
								$onPrice = $goodslist[$i][sellprice];
							}
						 }
					      ##### //오늘의 특가, 타임세일에 의한 가격

?>
					<li class="in_icon">
						<div class="goods_A"> <!--new arrivals 첫번째 리스트-->
						<? if($i < 4) { ?>
							<span><img src="../img/icon/best0<?=$i+1?>.png" alt=""></span>
						<? } ?>
							<a href="#">
								<p class="img190"><img src="<?=$Dir.DataDir."shopimages/product/".$goodslist[$i][maximage]?>"" width="190" height="190" alt="" /></p>
								<span class="subject"><?=$goodslist[$i][productname]?></span>
								<span class="price">
									<del><?=number_format($goodslist[$i][consumerprice]);?>원</del>&nbsp;
									<?=number_format($onPrice);?>원
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
			</div>
		</div><!-- //div.best_items_slide -->
		<? } ?>

		<div class="goods_list_wrap">
			<div class="item_sort">
				<p class="total">(총 <span><?=$t_count?>개</span> 상품)</p>
				<div class="sort">
					<ul class="type">
						<li><a href="javascript:ChangeSort('opendate')" <?=$sort_current["sort_current"][opendate]?$sort_current["sort_current"][opendate]:$sort_not?>>신규등록순</a></li>
						<li><a href="javascript:ChangeSort('best')" <?=$sort_current["sort_current"][best]?$sort_current["sort_current"][best]:$sort_not?>>인기판매순</a></li>
						<li><a href="javascript:ChangeSort('price_desc')" <?=$sort_current["sort_current"][price_desc]?$sort_current["sort_current"][price_desc]:$sort_not?>>높은가격순</a></li>
						<li><a href="javascript:ChangeSort('price')" <?=$sort_current["sort_current"][price]?$sort_current["sort_current"][price]:$sort_not?>>낮은가격순</a></li>
						<li>
							<select name="" id="" value="" onchange="ChangeSort('<?=$sort?>',this.value)">
								<option <?=$selected["listnum"][20]?> value="20">20개</option>
								<option <?=$selected["listnum"][40]?> value="40">40개</option>
								<option <?=$selected["listnum"][60]?> value="60">60개</option>
							</select>
						</li>
					</ul>
				</div>
			</div><!-- //.item_sort -->

			<div class="new_goods16ea">
				<ul class="list">

<?
		//번호, 사진, 상품명, 제조사, 가격
		$tmp_sort=explode("_",$sort);
		if($tmp_sort[0]=="reserve") {
			$addsortsql=",CASE WHEN a.reservetype='N' THEN CAST(a.reserve AS FLOAT)*1 ELSE CAST(a.reserve AS FLOAT)*a.sellprice*0.01 END AS reservesort ";
		}
		
		$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, a.option1, a.option2, a.option_quantity, a.mdcomment, ";
		if($_cdata->sort=="date2") $sql.="CASE WHEN a.quantity<=0 THEN '11111111111111' ELSE a.date END as date, ";
		$sql.= "a.maximage, a.minimage,a.tinyimage, a.etctype, a.option_price, a.consumerprice, a.tag, a.selfcode ";
		$sql.= $addsortsql;
		
		$sql.= "FROM tblproduct AS a  ";
		//$sql.= "JOIN (select c_productcode as c_productcode from tblproductlink group by c_productcode) AS link ON a.productcode=link.c_productcode ";
		$sql.= "JOIN tblproductlink link on(a.productcode=link.c_productcode AND c_maincate=1) ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		//$sql.= "LEFT OUTER JOIN (SELECT * FROM tblmembergroup_price where group_code = '{$_ShopInfo->memgroup}') c ";
		//$sql.= "ON a.productcode = c.productcode ";
		$sql.= $qry." ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') AND staff_product != '1' ";

		if($likeCate){
			//exdebug($likeCate);
			$sql.=$likeCate;
		}
		if($likeBrand){
			//exdebug($likeBrand);
			$sql.=$likeBrand;
		}
		if(strlen($not_qry)>0) {
			$sql.= $not_qry." ";
		}
		//exdebug($tmp_sort);
		if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
		elseif($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
		elseif($tmp_sort[0]=="price") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
		elseif($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
		elseif($tmp_sort[0]=="opendate") $sql.= "ORDER BY opendate DESC, date desc ";
		elseif($tmp_sort[0]=="dcprice") $sql.= "ORDER BY case when consumerprice>0 then  100 - cast((cast(sellprice as float)/cast(consumerprice as float))*100 as integer) else 0 end desc ";
		elseif($tmp_sort[0]=="order" ){

			$bestsql="select COALESCE(sum(cnt),0) sumcnt, a.productcode from tblproduct a left join tblcounterproduct b on (a.productcode=b.productcode) where a.productcode like '".$likecode."%' group by a.productcode order by sumcnt desc";
			$bestresult=pmysql_query($bestsql);

			$count=0;
			$lk=1;
			$casewhen="";
			while($bestrow=pmysql_fetch_object($bestresult)){
				$productcode[$count]=$bestrow->productcode;
				$casewhen[]=" '".$bestrow->productcode."' then ".$lk;
				$count++;
				$lk++;
			}
			$sql.= "ORDER BY a.start_no asc, modifydate desc";
			//$sql.= "ORDER BY a.start_no asc";
		}else if($tmp_sort[0]=="best"){

			$sql.= "ORDER BY a.start_no desc ";
			if(count($lk_casewhen)>0) $sql.= " ,case a.productcode when ".implode(" when ",$lk_casewhen)." end ";
		}else {
			if(strlen($_cdata->sort)==0 || $_cdata->sort=="date" || $_cdata->sort=="date2") {
				if(strstr($_cdata->type,"T") && strlen($t_prcode)>0) {
					$sql.= "ORDER BY FIELD(a.productcode,'".$t_prcode."'),date DESC ";
				} else {
					$sql.= "ORDER BY opendate DESC ";
					//$sql.= "ORDER BY a.start_no desc,case a.productcode when ".implode(" when ",$lk_casewhen)." end ";
				}
			} elseif($_cdata->sort=="productname") {
				$sql.= "ORDER BY a.start_no desc,a.productname ";
			} elseif($_cdata->sort=="production") {
				$sql.= "ORDER BY a.start_no desc,a.production ";
			} elseif($_cdata->sort=="price") {
				$sql.= "ORDER BY a.start_no desc,a.sellprice ";
			}
		}
		
		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		$i=0;
		$arrCriteo = array();
		$res_num = pmysql_num_rows($result);
		while($row=pmysql_fetch_object($result)) {
			$sellprice = number_format($row->sellprice);
			$consumerprice = number_format($row->consumerprice);
			$dc_data = $product->getProductDcRate($row->productcode);
			$temp = $row->option1;
			$tok = explode(",",$temp);
			$goods_count=count($tok);

			$check_optea='0';
			if($goods_count>"1"){
				$check_optea="1";
			}

			$optioncnt = explode(",",ltrim($row->option_quantity,','));
			$check_optout=array();
			$check_optin=array();
			for($gi=1;$gi<$goods_count;$gi++) {

				if(strlen($row->option2)==0 && $optioncnt[$gi-1]=="0"){ $check_optout[]='1';}
				else{  $check_optin[]='1';}
			}

			##### 쿠폰 적용가
			$couData = couponDisPrice($row->productcode);

			if($couData['coumoney']){
				$nomalprice = $row->sellprice;
				$row->sellprice = $row->sellprice - $couData['coumoney'];
			}
			##### //쿠폰 적용가

			#####즉시적립금 할인 적용가 150901원재
		
				if($row->reserve){
					$ReserveConversionPrice = 0;
					$ReserveConversionPrice = getReserveConversion($row->reserve, $row->reservetype, $nomalprice ,'Y');
					$row->sellprice = $row->sellprice - $ReserveConversionPrice;
				}
			
			#####//즉시적립금 할인 적용가
				

			##### 타임세일 가격변경
			$timesale_data=$timesale->getPdtData($row->productcode);
			$time_sale_now='';
			if($timesale_data['s_price']>0){
				$time_sale_now='Y';
				$nomalprice = $row->sellprice;
				$row->sellprice = $timesale_data['s_price'];
			}
			##### //타임세일 가격변경

			$number = ($t_count-($setup['list_num'] * ($gotopage-1))-$i);

			if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)) {
				$imgsrc = $Dir.DataDir."shopimages/product/".urlencode($row->tinyimage);
			}else{
				$imgsrc = $Dir."images/no_img.gif";
			}

			#장바구니를 위한 것들
			//장바구니를 위한 옵션

			if($row->option1){
				$opt1arr = explode(",",$row->option1);
				$opt1 = $opt1arr[1];
			}
			if($row->option2){
				$opt2arr = explode(",",$row->option2);
				$opt2 = $opt2arr[1];
			}

			## 품절 안된옵션중에 하나 가져와서 담아야 하기때문에 품절 안된것 조회 하여 옵션인덱스 셋팅 2014-08-25 12:12
			$calOpt1 = $calOpt2 = $resultOption1Val = $resultOption2Val = $resultOptionKey = 0;
			foreach($opt1arr as $opt1Key => $opt1Val){
				if($resultOption1Val) continue;
				if($opt1Key == 0) continue;
				$calOpt1 = $opt1Key-1;
				foreach($opt2arr as $opt2Key =>  $opt2Val){
					if($opt2Key == 0) continue;
					$calOpt2 = ($opt2Key-1)*10;
				}
				$resultOptionKey = $calOpt2 + $calOpt1;
				if($optioncnt[$resultOptionKey] > 0){
					$resultOption1Val = $opt1Key;
					$resultOption2Val = $opt2Key;
				}
			}

			//상품의 카테고리 코드
			$prd_cate_code = substr($row->productcode,0,12);

			$sell_price = ($row->sellprice)?$row->sellprice:"0";
			$consumerprice = ($row->consumerprice)?$row->consumerprice:"0";
			$option_reserve = ($row->option_reserve)?$row->option_reserve:"0";

			$dc_rate = getDcRate($row->consumerprice,$row->sellprice);

			$cou_Data = couponDisPrice($row->productcode);
			$dc_rate= $cou_Data["goods_sale_money"];

?>

					
					<li class="in_icon">
						<span><!--<img src="../img/icon/new.png" alt="" />--><?=viewicon($row->etctype)?></span>
						<div class="goods_A">
							<a href="#">
								<p class="img190"><img src="<?=$imgsrc?>" width="190" height="190" alt="" /></p>
								<span class="subject"><?=$row->productname?></span>
								<!--
								<span class="price"><del><?=number_format($nomalprice)?>원</del></span>
								-->
								<span class="price">
									<del><?=number_format($consumerprice)?>원</del>&nbsp;
									<?=number_format($sell_price)?>원
								</span>
							</a>
						</div>
						<!--
						<div class="layer_goods_icon" id="layer_goods" onclick="javascript:layer_goods_link('0','<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode?>')">
							<p class="icon">
								<a href="javascript:layer_goods_link('2','<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode?>')" class="view" title="상세보기"></a>
								<a href="javascript:layer_goods_link('<?=$option_chk?>','<?=$row->productcode?>')" class="cart" alt="<?=$row->productcode?>"  title="장바구니" id="layer_goods_basket"></a>
							</p>
						</div>-->
						<?if($row->option1) $option_chk=3; else $option_chk=1;?>
						<div class="layer_goods_icon" link_url="<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode?>">
							<p class="icon">
								<a href="javascript:;" link_url="<?=$Dir.FrontDir."productdetail.php?productcode=".$row->productcode?>" class="view" title="상세보기"></a>
								<a href="javascript:;" option_chk="<?=$option_chk?>" cart_chk="<?=$row->productcode?>" class="cart" title="장바구니"></a>
							</p>
						</div>
					</li>
<?			} ?>
				</ul>
			</div>
			<form name=form3 method=get action="<?=$_SERVER['PHP_SELF']?>">
				<div class="paging goods_list">
						<?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
				</div>
			</form>
		<?if($bReview){?>
		<div class="bottom_review">
		<? foreach($bReview as $reviewVal){ ?>
			<div class="review01">
				<img src="<?=$Dir.DataDir."shopimages/product/".$reviewVal['tinyimage']?>" alt="" style="width: 129px;height: 135px;" />
				<span class="starPoint">
				<? for($j=0;$j<$reviewVal['marks'];$j++) {
					echo "★";
				} ?>
				</span>
				<h5><?=$reviewVal['subject']?></h5>
				<p>(<?=substr($reviewVal['id'],-3)?>***)</p>
				<ul class="review">
					<li>
						<a href="<?=$Dir.FrontDir."review_view.php?num=".$reviewVal['num']?>">
							<?=mb_substr($reviewVal['content'],0,80,"euc-kr")?>...
						</a>
					</li>
				</ul>
			</div>
		<? } ?>
		</div><!-- //div.bottom_cs_section -->
		<?}?>
	</div><!-- //div.containerBody -->
<?}?>
</div>
</div>
<!--///////////////////////////////////////////  미리보기 popup을 위한 div   &&  장바구니 -->
<div id="overDiv" style="position:absolute;top:0px;left:0px;z-index:100;display:none;" class="alpha_b60" ></div>
<div class="popup_preview_warp" style="margin-left: 50%;left: -459px;display:none;" ></div>

<form name=form1 id = 'ID_goodsviewfrm' method=post action="<?=$Dir.FrontDir?>basket.php">
	<input type="hidden" name="productcode"></input>
</form>
<!--///////////////////////////////////////////-->
<script type="text/javascript">


function CheckForm(gbn,temp2) {


	if(gbn=="ordernow") {
		document.form1.ordertype.value="ordernow";
	}

	if (gbn != "ordernow"){
		document.form1.action="../front/confirm_basket.php";
		document.form1.target="confirmbasketlist";
		document.form1.productcode.value= temp2;
		window.open("about:blank","confirmbasketlist","width=401,height=309,scrollbars=no,resizable=no, status=no,");
		document.form1.submit();
		document.back.submit();
	}

}

function change_quantity(gbn) {
	tmp=document.form1.quantity.value;
	if(gbn=="up") {
		tmp++;
	} else if(gbn=="dn") {
		if(tmp>1) tmp--;
	}
	var cons_qu = $("#constant_quantity").val();
	if (cons_qu != "" && cons_qu != "0"){
		if (cons_qu<tmp){
			alert('재고량이 부족 합니다.');
			return;
		}
	} else if(cons_qu == "0") {
		alert('품절 입니다.');
		return;
	}

	<?php  if($_pdata->assembleuse=="Y") { ?>
		if(getQuantityCheck(tmp)) {
			if(document.form1.assemblequantity) {
				document.form1.assemblequantity.value=tmp;
			}
			document.form1.quantity.value=tmp;
			setTotalPrice(tmp);
		} else {
			alert('구성상품 중 '+tmp+'보다 재고량이 부족한 상품있어서 변경을 불가합니다.');
			return;
		}
	<?php  } else { ?>
		var tmp_price = $("#ID_goodsprice").val();
		tmp_price = Number(tmp_price)*Number(tmp);
		setDeliPrice(tmp_price,tmp);
		$("#result_total_price").html(jsSetComa(tmp_price));
		document.form1.quantity.value=tmp;
	<?php  } ?>

}


$(document).ready(function() {
	//Default Action
	var defaultType = 0;
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li").each(function(){
		if($(this).attr("class")=="active"){
			defaultType = 1;
			var tabId = $(this).find("a").attr("href");
			$(tabId).show();
		}
	});
	if(defaultType == 0){
		$("ul.tabs li:first").addClass("active").show(); //Activate first tab
		$(".tab_content:first").show(); //Show first tab content
	}

	//On Click Event
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active content
		return false;
	});

	$('.new_goods4ea ul.list li').mouseenter(function(){
	$(this).find('.layer_goods_icon').show();
	});
	$('.new_goods16ea ul.list li').mouseenter(function(){
	$(this).find('.layer_goods_icon').show();
	});
	$('.in_icon').mouseleave(function(){
	$('.layer_goods_icon').hide();
	});

<?php if($max>0){ ?>
	$("#middle_slide").sudoSlider({
		effect: "slide",
		speed:1000,
		continuous:true,
		slideCount:4,
		prevNext:false,
		moveCount:1,
		customLink:'.prevnext',
		auto:false,
		animationZIndex:0,
	});
<? } ?>
});

</script>
<script>
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

<form name="productlist_basket" id="productlist_basket">
<input type="hidden" name="productcode2" id="productcode2">
</form>


<form name="back" action="../front/productdetail.php">
<input type="hidden" name="back2" value="1">
</form>


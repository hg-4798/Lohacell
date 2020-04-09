<?php
/********************************************************************* 
// 파 일 명		: tem_main001.php 
// 설     명		: 메인 템플릿
// 상세설명	: 메인 템플릿
// 작 성 자		: hspark
// 수 정 자		: 2015.11.02 - 김재수
// 
// 
*********************************************************************/ 
?>
<?php

/*if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}*/
?>
<?include ($Dir.MainDir.$_data->menu_type.".php");?>
<!--won function -->
<?
$bannerImgPath	= $Dir.DataDir."shopimages/mainbanner/";
$brandImgPath	= $Dir.DataDir.'shopimages/brand/';
$productImgPath	= $Dir.DataDir.'shopimages/product/';
$main_ad_array	= array("77","79","80","85"); // 77 - 메인 배너 롤링, 79 - 메인 AD 배너(뜨끈뜨끈한 혜택정보), 80 - 메뉴 카테고리 배너, 85 - 텍스트 롤링

#메인 배너 모두 가져온다.
for($m=0;$m < count($main_ad_array);$m++) {
	$bannerSql = "SELECT * FROM tblmainbannerimg WHERE banner_no = '".$main_ad_array[$m]."' AND banner_hidden = '1' ";
	$bannerSql.= "AND ( banner_type = 0 OR banner_type = '".$_ShopInfo->getAffiliateType()."' ) ";
	$bannerSql.= "ORDER BY banner_sort ASC ";
	if ($main_ad_array[$m] == "79") $bannerSql.= "LIMIT 3";

	$bannerRes = pmysql_query( $bannerSql, get_db_conn() );
	while( $bannerRow = pmysql_fetch_array( $bannerRes ) ){
		if( strlen( $bannerRow['banner_link'] ) == 0 ){
			$bannerRow['banner_link'] = 'javascript:;';
		}
		if( strlen( $bannerRow['banner_t_link'] ) == 0 ){
			$bannerRow['banner_t_link'] = 'javascript:;';
		}
		if ($main_ad_array[$m] == "80") {
			$mainCateBanner[$bannerRow[banner_category]][no] = $bannerRow[no];
			$mainCateBanner[$bannerRow[banner_category]][img] = $bannerImgPath.$bannerRow[banner_img];
			$mainCateBanner[$bannerRow[banner_category]][link] = $bannerRow[banner_link];
			$mainCateBanner[$bannerRow['banner_category']]['target'] = $bannerRow['banner_target'];
		} else {
			$bannerRow[banner_img] = $bannerImgPath.$bannerRow[banner_img];
			if ($main_ad_array[$m] == "77") $mainRollBanner[] = $bannerRow;
			if ($main_ad_array[$m] == "85") $mainTextBanner[] = $bannerRow;
			if ($main_ad_array[$m] == "79") $mainAdBanner[] = $bannerRow;
		}
	}
	pmysql_free_result( $bannerRes );
}
//exdebug( $mainCateBanner );
//var_dump($mainTextBanner);

# 잘나가는놈,  디지털/가전 , 패션/잡화/기타 상품을 가져온다.
for($m=0;$m <= 2;$m++) {
	$mainitemSql = 'SELECT pr.pridx, pr.productcode, pr.productname, buyprice, pr.sellprice, pr.consumerprice, pr.maximage, pr.overseas_type, pr.mdcomment, pr.etctype ';
	$mainitemSql.= 'FROM tblproduct_mainitem_list mpr ';
	$mainitemSql.= 'LEFT JOIN tblproduct pr ON pr.pridx = mpr.pridx ';
	$mainitemSql.= "WHERE  pr.display = 'Y' ";
	$mainitemSql.= "AND (  mpr.mall_type = 0 OR mpr.mall_type = '".$_ShopInfo->getAffiliateType()."' )";
	$mainitemSql.= "AND mpr.category_type = '{$m}' ";
	$mainitemSql.= 'ORDER BY mpr.mall_type, mpr.sort ASC ';
	($m == 0) ? $mainitemSql.= 'limit 4 ' : $mainitemSql.= 'limit 8 ';

	$mainitemRes = pmysql_query( $mainitemSql, get_db_conn() );
	while( $mainitemRow = pmysql_fetch_array( $mainitemRes ) ){
		if ( is_file( $productImgPath.$mainitemRow['maximage'] ) ) {
			$mainitemRow['maximage'] = $productImgPath.$mainitemRow['maximage'];
		} else {
			$mainitemRow['maximage'] = $Dir."images/common/noimage.gif";
		}
		if ($m == 0) $mainitemlist0[] = $mainitemRow;
		if ($m == 1) $mainitemlist1[] = $mainitemRow;
		if ($m == 2) $mainitemlist2[] = $mainitemRow;
	}
	//echo $mainitemSql;
	pmysql_free_result( $mainitemRes );
}
//var_dump($mainitemlist0);
##### 브랜드별 배너 - 디지털/가전(1), 패션/잡화/기타(2)
for($b=1;$b <= 2;$b++) {
	$sql_brands = "
	SELECT * 
	FROM tblproductbrand
	where display_yn = '1'
	AND (  mall_type = 0 OR mall_type = '".$_ShopInfo->getAffiliateType()."' )
	AND category_type='{$b}'
	ORDER BY brandname, bridx DESC 
	limit 6";
	$res_brands = pmysql_query($sql_brands);
	while($row_brands = pmysql_fetch_array($res_brands)){
		$row_brands[logo_img] = $brandImgPath.$row_brands[logo_img];
		if ($b == 1) $data_brands1[]=$row_brands;
		if ($b == 2) $data_brands2[]=$row_brands;
	}
	pmysql_free_result($res_brands);
}

//신입생들 (신규등록상품순으로 보여짐)
$newlist_sql="
SELECT pridx, productcode, productname, buyprice, sellprice, consumerprice, maximage, overseas_type, mdcomment, etctype
FROM tblproduct
WHERE display = 'Y' AND staff_product != '1' 
AND (  mall_type = 0 OR mall_type = '".$_ShopInfo->getAffiliateType()."' )
ORDER BY regdate DESC, pridx ASC 
LIMIT 24";
$newlist_res = pmysql_query($newlist_sql, get_db_conn());
while($newlist_row = pmysql_fetch_array($newlist_res)){
	if ( is_file( $productImgPath.$newlist_row['maximage'] ) ) {
		$newlist_row['maximage'] = $productImgPath.$newlist_row['maximage'];
	} else {
		$newlist_row['maximage'] = $Dir."images/common/noimage.gif";
	}
	$newlist[]=$newlist_row;
}
pmysql_free_result($newlist_res);


?>


<script type="text/javascript">
<!--


//-->
$(document).ready(function(){
	
});

</script>

        <div id="main">

            <!-- [main_nav -->
            <div class="main_nav">
                <ul class="quick_nav" >
			<?
				$c = 1;
				$cate_cnt	= sizeof($cateListB);


				foreach($cateListB as $cateListKey=>$cateVal){
					foreach($cateListB[$cateListKey] as $bListKey=>$bListVal){
						$banner_html	="";
			?>
					<li id="nav<?=$c?>" class="nav_main_menu">
					<span class="nav<?=$c?>" ><?=$bListVal['code_name']?></span>
					<div class="q_sub<?=$c?>">
                        <h3 class="tit_wing"><?=$bListVal['code_name']?></h3>
                        <ul class="nav_sub1">
				<?if($cateListC[$cateListKey.$bListVal['code_b']]){?>
					<?foreach($cateListC[$cateListKey.$bListVal['code_b']] as $cVal){?>
					 <li><a href="<?=$Dir.FrontDir."productlist.php?code=".$cVal['cate_code']?>" class='codeLink' alt='<?=$cVal['cate_code']?>'>- <?=$cVal['code_name']?></a></li>
					<?
							($banner_html) ? $banner_view_style=" style='display:none;'" : $banner_view_style="";
							if ($mainCateBanner[$cVal['cate_code']][no]) $banner_html .= "<a href=\"".$mainCateBanner[$cVal['cate_code']][link]."\" target=\"".$mainCateBanner[$cVal['cate_code']]['target']."\" id='cate_".$cVal['cate_code']."'".$banner_view_style."><img src=\"".$mainCateBanner[$cVal['cate_code']][img]."\" alt=\" \" width=300 height=380></a>";
							// 3차 카테고리에 베너가 존재하지 않을 경우 2차 카테고리의 베너를 가져옴
							// 2015 12 09 유동혁 수정
							else if( $mainCateBanner[$cateListKey.$bListKey.'000000'][no] ) $banner_html .= "<a href=\"".$mainCateBanner[$cateListKey.$bListKey.'000000'][link]."\" target=\"".$mainCateBanner[$cateListKey.$bListKey.'000000']['target']."\" id='cate_".$cVal['cate_code']."'".$banner_view_style."><img src=\"".$mainCateBanner[$cateListKey.$bListKey.'000000'][img]."\" alt=\" \" width=300 height=380></a>";
						}
					?>
				<?}?>
                        </ul>
                        <div class="wing_banner"><?=$banner_html?></div>
                    </div>
                    </li>
				<?
						$c++;
					}
				}
				?>
                </ul>
            </div>

            <!-- //[main_nav -->

            <!-- [main_visual] -->
            <div class="main_visual">
                <div class="visual_box">
                    <div id="main_visual">
					<?
						for($i=0; $i<count($mainRollBanner);$i++){
					?>
						<a href="<?=$mainRollBanner[$i][banner_link]?>" target="<?=$mainRollBanner[$i][banner_target]?>" >
							<img src="<?=$mainRollBanner[$i][banner_img]?>" alt="" width=990 height=420>
						</a>
					<?
						}
					?>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            $(function() {
            $('#main_visual').main_visual({
            width: 990,
            height:420,
            play: {
            active: true,
            auto: true,
            interval: 4000,
            swap: true
            }
            });
            });
            </script>
            <!-- //[main_visual] -->

            <!--[ytn_news] -->
            <div class="ytn_news">
                <div id="notice" class="news">
                    <div class="open-event fl">
                        <ul class="notice-list">
						<?
							for($i=0; $i<count($mainTextBanner);$i++){
						?>
							<li>
								<a href="<?=$mainTextBanner[$i][banner_t_link]?>" target="<?=$mainTextBanner[$i][banner_target]?>" >
									<?=$mainTextBanner[$i][banner_title]?>
								</a>
							</li>
						<?
							}
						?>
                        </ul>
                        <span id="ytn">
                        <a href="#" class="prev"><img src="../images/main/btn_new_prev.png" alt=" " ></a>
                        <a href="#" class="next"><img src="../images/main/btn_new_next.png" alt=" " ></a>
                        </span>
                    </div>

                    <script type="text/javascript">fn_article('notice','ytn',true);</script>
                </div>
            </div>
            <!--[ytn_news] -->

			<div class="index-best-goods">
				<div class="inner">
					<h2 class="tit_best">잘나가는 아이들</h2>
					<div class="best-list-wrap">
					<ul class="best-list">
									
						<?
						for($i=0; $i<count($mainitemlist0);$i++){							
							#상품 가격의 % 비교값 구하기
							$sPercent = 0; // 교육 할인가
							if( !is_null($mainitemlist0[$i][consumerprice]) && $mainitemlist0[$i][consumerprice] != 0 ){
								$sPercent = floor( 100 - ( ( (int) $mainitemlist0[$i][sellprice] / (int) $mainitemlist0[$i][consumerprice] ) * 100 ) );
							}
						?>
						<li>
							<a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$mainitemlist0[$i][productcode]?>">
								<div class="goods-info">
									<h4 class="tit_goods"><?=$mainitemlist0[$i][productname]?></h4>
									<p class="stit_goods"><? if( strlen( trim( $mainitemlist0[$i][mdcomment] ) ) > 0 ) { echo $mainitemlist0[$i][mdcomment]; }?></p>
									
									<div class="price-box">
										<div class="sale-per">
											<span class="sale_price"><?=$sPercent?>%</span>
										</div>
										<div class="price-table">
											<table>
												<caption>가격정보</caption>
												<tbody>
												<tr>
													<th scope="row">정상가</th>
													<td class="p1"><?=number_format( $mainitemlist0[$i][buyprice] )?>원</td>
												</tr>
												<tr>
													<th scope="row">최저가</th>
													<td class="p2"><?=number_format( $mainitemlist0[$i][consumerprice] )?>원</td>
												</tr>
												<tr>
													<th scope="row" class="mem_price">교육할인가</th>
<?php
							if( strlen( $_ShopInfo->getMemid() ) > 0 ) {
?>
													<td class="mem_price"><?=number_format( $mainitemlist0[$i][sellprice] )?>원</td>
<?php
							} else {
?>
													<td class="mem_price"><img src="../images/common/ico_memberonly_sub.gif" alt="members only" ></td>
<?php
							}
?>
												</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<p class="thumb"><img src="<?=$mainitemlist0[$i][maximage]?>" class="img-size-list-s" alt=""></p>
							</a>
						</li>
					<?}?>
					</ul>
					</div>
				</div>
			</div><!-- //.index-best-goods -->

            <!-- [hot_goods] -->
            <div class="hot_goods">
                <div class="block">
                    <h2 class="tit_hot">뜨끈뜨끈한 혜택정보</h2>
                    <ul class="main_banner">
						<?
							for($i=0; $i<count($mainAdBanner);$i++){
						?>
                            <li>
								<a href="<?=$mainAdBanner[$i][banner_link]?>" target="<?=$mainAdBanner[$i][banner_target]?>" >
									<img src="<?=$mainAdBanner[$i][banner_img]?>" alt="" >
								</a>
							</li>
						<?
							}
						?>
                    </ul>
                </div>
            </div>
            <!-- //[hotgoods] -->

            <!-- [패션/잡화/기타] -->
            <div class="goods_list">
                <div class="block">
                    <h2 class="tit_etc">패션/잡화/기타</h2>
                    <div class="brand_category">
                        <h3 class="br_cate">브랜드별 상품보기 <img src="../images/main/ico_br_cate.gif" alt="" ></h3>
                        <ul class="brand_list">
						<?
							for($i=0; $i<count($data_brands2);$i++){
						?>
                            <li><a href="/front/productsearch.php?brand=<?=$data_brands2[$i][bridx]?>"><img src="<?=$data_brands2[$i][logo_img]?>" alt="<?=$data_brands2[$i][brandname]?>" ></a></li>
						<?
							}
						?>
                        </ul>
                    </div>
                    <ul class="product_list">
					<? for($i=0; $i<count($mainitemlist2);$i++){ ?>				
					<?
							if ($i > 0 && (($i+1) <= count($mainitemlist2))) {
								if ($i%4 == 0) {
									echo "</ul><ul class='product_list'>";
								}
							}
					?>
                        <li>
                        <!-- ico -->
                        <div class="goods_ico">
                            <?=viewicon($mainitemlist2[$i][etctype])?>
                        </div>
                        <!-- //ico -->
                        <a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$mainitemlist2[$i][productcode]?>"><img src="<?=$mainitemlist2[$i][maximage]?>" alt="" class='img-size-list'>
                        <h4 class="tit_goods"><?=$mainitemlist2[$i][productname]?></h4>
							<p class="stit_goods">
                        
<?php
			if( $mainitemlist2[$i][overseas_type] == '1' ){
?>
								<img src="../images/main/ico_outdely.png" alt="해외배송" > 
<?php
			}
			if( strlen( trim( $mainitemlist2[$i][mdcomment] ) ) > 0 ) { 
				echo $mainitemlist2[$i][mdcomment]; 
			}
?>
							</p>
							<table >
							<caption>가격정보</caption>
							<tr>
								<th scope="row">정상가</th>
								<td class="p1"><?=number_format( $mainitemlist2[$i][buyprice] )?>원</td>
							</tr>
							<tr>
								<th scope="row">최저가</th>
								<td class="p2"><?=number_format( $mainitemlist2[$i][consumerprice] )?>원</td>
							</tr>
							<tr>
								<th scope="row" class="mem_price">교육할인가</th>
<?php
			if( strlen( $_ShopInfo->getMemid() ) > 0 ) {
?>
								<td class="mem_price"><?=number_format( $mainitemlist2[$i][sellprice] )?>원</td>
<?php
			} else {
?>
								<td class="mem_price">	<img src="../images/common/ico_memberonly_sub.gif" alt="members only" ></td>
<?php
			}
?>
							</tr>
							</table>
	                        </a>
                        </li>		
					<?}?>
                    </ul>
                </div>
            </div>
            <!-- //[패션/잡화/기타] -->

            <!-- [디지털/가전] -->
            <div class="goods_list m0">
                <div class="block">
                    <h2 class="tit_dig">디지털/가전</h2>
                    <div class="brand_category">
                        <h3 class="br_cate">브랜드별 상품보기 <img src="../images/main/ico_br_cate.gif" alt="" ></h3>
                        <ul class="brand_list">
						<?
							for($i=0; $i<count($data_brands1);$i++){
						?>
                            <li><a href="/front/productsearch.php?brand=<?=$data_brands1[$i][bridx]?>"><img src="<?=$data_brands1[$i][logo_img]?>" alt="<?=$data_brands1[$i][brandname]?>" ></a></li>
						<?
							}
						?>
                        </ul>
                    </div>
                    <ul class="product_list">
					<? for($i=0; $i<count($mainitemlist1);$i++){ ?>
					<?
							if ($i > 0 && (($i+1) <= count($mainitemlist1))) {
								if ($i%4 == 0) {
									echo "</ul><ul class='product_list'>";
								}
							}
					?>
                        <li>
                        <!-- ico -->
                        <div class="goods_ico">
                            <?=viewicon($mainitemlist1[$i][etctype])?>
                        </div>
                        <!-- //ico -->
                        <a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$mainitemlist1[$i][productcode]?>"><img src="<?=$mainitemlist1[$i][maximage]?>" alt="" class='img-size-list'>
                        <h4 class="tit_goods"><?=$mainitemlist1[$i][productname]?></h4>
							<p class="stit_goods">
                        
<?php
			if( $mainitemlist1[$i][overseas_type] == '1' ){
?>
								<img src="../images/main/ico_outdely.png" alt="해외배송" > 
<?php
			}
			if( strlen( trim( $mainitemlist1[$i][mdcomment] ) ) > 0 ) { 
				echo $mainitemlist1[$i][mdcomment]; 
			}
?>
							</p>
							<table >
							<caption>가격정보</caption>
							<tr>
								<th scope="row">정상가</th>
								<td class="p1"><?=number_format( $mainitemlist1[$i][buyprice] )?>원</td>
							</tr>
							<tr>
								<th scope="row">최저가</th>
								<td class="p2"><?=number_format( $mainitemlist1[$i][consumerprice] )?>원</td>
							</tr>
							<tr>
								<th scope="row" class="mem_price">교육할인가</th>
<?php
			if( strlen( $_ShopInfo->getMemid() ) > 0 ) {
?>
								<td class="mem_price"><?=number_format( $mainitemlist1[$i][sellprice] )?>원</td>
<?php
			} else {
?>
								<td class="mem_price">	<img src="../images/common/ico_memberonly_sub.gif" alt="members only" ></td>
<?php
			}
?>
							</tr>
							</table>
	                        </a>
                        </li>
					<?}?>
                    </ul>
                </div>
            </div>
            <!-- //[디지털/가전] -->

            <!-- [신입생들] -->
            <div class="new_porduct">
                <div class="goods_list">
                    <div class="block">
                        <h2 class="tit_new">신입생들</h2>
                        <ul class="tab">
						<?
							for($i=0; $i<count($newlist);$i++){
								(($i % 8) == 0) ? $tab_num	= ($i / 8) + 1 : $tab_num	= 0;
								if ($tab_num > 0) {
						?>
                            <li><a href="#tab<?=$tab_num?>"><?=$tab_num?></a></li>
						<?
								}
							}
						?>
                        </ul>
                        <div class="tab_container ">
						<?
						$newlist_cnt	= count($newlist);
						for($i=0; $i<$newlist_cnt;$i++){	


							(($i % 8) == 0) ? $tab_num	= ($i / 8) + 1 : $tab_num	= 0;
							if ($tab_num > 0) {
								if ($tab_num == 0) {
					?>
                            <div class="tab_c" id="tab<?=$tab_num?>" >
								<ul class="product_list">
					<?
								} else {
					?>
								</ul>
							</div>
                            <div class="product_list tab_c" id="tab<?=$tab_num?>" >
								<ul class="product_list">
					<?
								}
							} else {			
								if ($i > 0 && (($i+1) <= $newlist_cnt)) {
									if ($i%4 == 0) {
										echo "</ul><ul class='product_list'>";
									}
								}
							}

						$newlist_sellprice=$newlist[$i][sellprice];
						##### 쿠폰에 의한 가격 할인
						$cou_data = couponDisPrice($newlist[$i][productcode]);
						if($cou_data['coumoney']){
							$nomalprice_baglist = $newlist[$i][sellprice];
							$newlist_sellprice  = $newlist[$i][sellprice]-$cou_data['coumoney'];
						}

						#####즉시적립금 할인 적용가 150901원재
		
						if($newlist[$i][reserve]>0){
						$ReserveConversionPrice = 0;
						$ReserveConversionPrice = getReserveConversion($newlist[$i][reserve], $newlist[$i][reserve],$nomalprice_baglist,'Y');
						$newlist_sellprice  = $newlist_sellprice  - $ReserveConversionPrice;
						}
		
						##### 오늘의 특가, 타임세일에 의한 가격
						if(getSpeDcPrice($newlist[$i][productcode])){
							$newlist_sellprice = getSpeDcPrice($newlist[$i][productcode]);
							if($newlist_sellprice <= 0){
							$newlist_sellprice = $newlist[$i][sellprice];
							}
						}

						##### //오늘의 특가, 타임세일에 의한 가격
?>
								<li>
								<!-- ico -->
								<div class="goods_ico">
									<?=viewicon($newlist[$i][etctype])?>
								</div>
								<!-- //ico -->
								<a href="<?=$Dir.FrontDir."productdetail.php?productcode=".$newlist[$i][productcode]?>"><img src="<?=$newlist[$i][maximage]?>" alt="" class='img-size-list'>
								<h4 class="tit_goods"><?=$newlist[$i][productname]?></h4>
									<p class="stit_goods">
								
<?php
									if( $newlist[$i][overseas_type] == '1' ){
?>
										<img src="../images/main/ico_outdely.png" alt="해외배송" > 
<?php
									}
									if( strlen( trim( $newlist[$i][mdcomment] ) ) > 0 ) { 
										echo $newlist[$i][mdcomment]; 
									}
?>
									</p>
									<table >
									<caption>가격정보</caption>
									<tr>
										<th scope="row">정상가</th>
										<td class="p1"><?=number_format( $newlist[$i][buyprice] )?>원</td>
									</tr>
									<tr>
										<th scope="row">최저가</th>
										<td class="p2"><?=number_format( $newlist[$i][consumerprice] )?>원</td>
									</tr>
									<tr>
										<th scope="row" class="mem_price">교육할인가</th>
<?php
									if( strlen( $_ShopInfo->getMemid() ) > 0 ) {
?>
										<td class="mem_price"><?=number_format( $newlist[$i][sellprice] )?>원</td>
<?php
									} else {
?>
										<td class="mem_price">	<img src="../images/common/ico_memberonly_sub.gif" alt="members only" ></td>
<?php
									}
?>
									</tr>
									</table>
									</a>
								</li>
							<?if (($i+1) == $newlist_cnt) {?>
								</ul>
                            </div>
							<?}?>
						<?}?>
                        </div>
                    </div>
                </div>
                <!-- //[신입생들] -->
            </div>
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

<?

// 리뷰 작성 가능 리스트 조회
$sql  = "SELECT tblResult.ordercode, tblResult.idx ";
$sql .= "FROM ";
$sql .= "   ( ";
$sql .= "       SELECT a.*, b.regdt  ";
$sql .= "       FROM tblorderproduct a LEFT JOIN tblorderinfo b ON a.ordercode = b.ordercode ";
$sql .= "       WHERE a.productcode = '" . $productcode . "' AND b.id = '" . $_ShopInfo->getMemid()  . "' and ( (b.oi_step1 = 3 AND b.oi_step2 = 0) OR (b.oi_step1 = 4 AND b.oi_step2 = 0) ) ";
$sql .= "       ORDER BY a.idx DESC ";
$sql .= "   ) AS tblResult LEFT ";
$sql .= "   OUTER JOIN tblproductreview tpr ON tblResult.productcode = tpr.productcode and tblResult.ordercode = tpr.ordercode and tblResult.idx = tpr.productorder_idx ";
$sql .= "WHERE tpr.productcode is null ";
$sql .= "ORDER BY tblResult.idx asc ";
$sql .= "LIMIT 1 ";

$result = pmysql_query($sql);
list($review_ordercode, $review_order_idx) = pmysql_fetch($sql);
pmysql_free_result($result);

?>

<!-- 상품 리뷰 dimm layer -->
<div class="layer-dimm-wrap layer-review-write">
    <div class="dimm-bg"></div>
    <div class="layer-inner ">
        <h3 class="layer-title"></h3>
        <button type="button" class="btn-close">창 닫기 버튼</button>
        <div class="layer-content">
			<h4 class="title">REVIEW</h4>
            <form name='reviewForm' id='reviewForm' method='POST' action='' >

			<table class="view-bbs-write" width="100%" summary="포토이벤트 등록">
				<caption>리뷰 등록</caption>
				<colgroup><col style="width:80px"><col style="width:auto"></colgroup>
				<tbody>
					<tr class="order-find">
						<th><label for="order-goods">주문상품</label></th>
						<td><?=$_pdata->productname?></td>
						<!--td>주문상품 선택방식 어떻게 할것인지?</td-->
						<!-- <td class="imageAdd">
							<input type="file" id="add-image">
							<div class="txt-box">이곳에 파일명이 업로드된 파일명이 나올수 있나요?</div>
							<label for="order-goods">주문한 상품 찾기</label>
						</td> -->
					</tr>
					<tr>
						<th>별점</th>
						<td>
							<div class="select small">
								<span class="ctrl"><span class="arrow"></span></span>
								<button type="button" class="my_value"><span>선택해 주세요.</span></button>
								<ul class="a_list">
									<li><a href="javascript:;" onClick="javascript:set_review_vote(5);">★★★★★</a></li>
									<li><a href="javascript:;" onClick="javascript:set_review_vote(4);">★★★★</a></li>
									<li><a href="javascript:;" onClick="javascript:set_review_vote(3);">★★★</a></li>
									<li><a href="javascript:;" onClick="javascript:set_review_vote(2);">★★</a></li>
									<li><a href="javascript:;" onClick="javascript:set_review_vote(1);">★</a></li>
								</ul>
                                <input type="hidden" name="review_vote" id="review_vote" value="" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="review-title">제목</label></th>
						<td><input type="text" id="review-title" name="review_title"></td>
					</tr>
					<tr>
						<th><label for="review-content">내용</label></th>
						<td>
							<textarea id="review-content" name="review_content" cols="30" rows="10" placeholder="내용을 입력해 주세요." title="내용 입력자리"></textarea>
							<span>※ 배송,상품문의, 취소, 교환등의 문의사항은 고객센터를 이용해 주시기 바랍니다.상품평에 작성하시면 답변을 받지 못합니다. </span>
						</td>
					</tr>
                    <tr>
                        <th><label for="add-image1">이미지</label></th>
                        <td class="imageAdd">
                            <input type="file" id="add-image1" name="up_filename[]" accept="image/*">
                            <input type="hidden" id="file_exist" name="file_exist" value="N" />
                            <div class="txt-box">&nbsp;</div>
                            <label for="add-image1">찾아보기</label>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="add-image2">이미지</label></th>
                        <td class="imageAdd">
                            <input type="file" id="add-image2" name="up_filename[]" accept="image/*">
                            <input type="hidden" id="file_exist" name="file_exist" value="N" />
                            <div class="txt-box">&nbsp;</div>
                            <label for="add-image2">찾아보기</label>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="add-image3">이미지</label></th>
                        <td class="imageAdd">
                            <input type="file" id="add-image3" name="up_filename[]" accept="image/*">
                            <input type="hidden" id="file_exist" name="file_exist" value="N" />
                            <div class="txt-box">&nbsp;</div>
                            <label for="add-image3">찾아보기</label>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="add-image4">이미지</label></th>
                        <td class="imageAdd">
                            <input type="file" id="add-image4" name="up_filename[]" accept="image/*">
                            <input type="hidden" id="file_exist" name="file_exist" value="N" />
                            <div class="txt-box">&nbsp;</div>
                            <label for="add-image4">찾아보기</label>
                            <span>파일명 : 한글,영문,숫자 / 파일용량 : 3M이하 / 첨부기능 파일형식 : GIF,JPG(JPEG)</span>
                        </td>
                    </tr>
				</tbody>
			</table>

                <input type="hidden" name="productcode" id="productcode" value="<?=$productcode?>" />
                <input type="hidden" name="ordercode" id="ordercode" value="<?=$review_ordercode?>" />
                <input type="hidden" name="productorder_idx" id="productorder_idx" value="<?=$review_order_idx?>" />
                <input type="hidden" name="review_num" id="review_num" value="0" />
            </form>
			<div class="btn-place">
				<button class="btn-dib-function" type="button" onclick='javascript:ajax_review_insert();'><span>OK</span></button>
			</div>

        </div>
    </div>
</div>

<!-- 상품 질문 dimm layer -->
<div class="layer-dimm-wrap layer-qna-write">
    <div class="dimm-bg"></div>
    <div class="layer-inner ">
        <h3 class="layer-title"></h3>
        <button type="button" class="btn-close">창 닫기 버튼</button>
        <div class="layer-content">
			
			<h4 class="title">Q&amp;A</h4>
			<table class="th-left util">
				<caption>상품에 대한 궁금증 질문</caption>
				<colgroup><col style="width:85px"><col style="auto"></colgroup>
				<tr>
					<th scope="row"><label for="qna-subject">제목</label></th>
					<td><input type="text" id="qna-subject" class="input-def" title="질문 제목 입력자리"></td>
				</tr>
				<tr>
					<th scope="row"><label for="qna-content">내용</label></th>
					<td>
						<textarea id="qna-content" cols="30" rows="10" class="textarea-def"></textarea>
						<span class="ment">※ 배송,상품문의,취소,교환등의 문의사항은 고객센터를 이용해 주시기 바랍니다.상품평에 작성하시면 답변을 받지 못합니다.</span>
					</td>
				</tr>
				<tr>
					<th scope="row">공개여부</th>
					<td>
						<input type="radio" name="view-type" id="view" value='0' class="radio-def" checked>
						<label for="view">공개</label>
						<input type="radio" name="view-type" id="no-view" value='1' class="radio-def">
						<label for="no-view">비공개</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="qna-pwd">비밀번호</label></th>
					<td><input type="password" id="qna-pwd" class="input-def"></td>
				</tr>
			</table>
			<div class="btn-place">
				<input type='hidden' id='qna-num' value='' >
				<button class="btn-dib-function" type="button" onclick='javascript:QnAController();'><span>OK</span></button>
			</div>

        </div>
    </div>
</div>
<style>
	
</style>
<!-- 사용가능 쿠폰 dimm layer -->
<div class="layer-dimm-wrap layer-detail-coupon" >
    <div class="dimm-bg"></div>
    <div class="layer-inner layer-coupon js-scroll" style='height:500px;' >
        <h3 class="layer-title"></h3>
        <button type="button" class="btn-close">창 닫기 버튼</button>
        <div class="layer-content ">
			<h4 class="title">사용가능 쿠폰</h4>
			<table class="th-top util">
				<caption>사용가능 쿠폰 리스트</caption>
				<colgroup><col style="auto"><col style="width:96px"><col style="width:134px"></colgroup>
				<thead>
					<tr>
						<th scope="col">쿠폰명</th>
						<th scope="col">할인율</th>
						<th scope="col">보유 / 유효기간</th>
					</tr>
				</thead>
				<tbody id='ID_coupon_layer'>
<?php
if( strlen( $_ShopInfo->getMemid() ) > 0 ){
	foreach( $coupon_layer as $clVal ){
?>
					<?=$clVal?>
<?php
	} // coupon_layer foreach
} else {
?>
					<!-- 로그인전인 경우 -->
					<tr class="">
						<td colspan="3" class="login-before">로그인 후 확인하세요</td>
					</tr><!-- //로그인전인 경우 -->
<?php
} // $_ShopInfo->getMemid() if else
?>
				</tbody>
			</table>
			
        </div>
    </div>
</div>

<!-- 무이자 카드 dimm layer -->
<div class="layer-dimm-wrap layer-detail-card" >
    <div class="dimm-bg"></div>
    <div class="layer-inner ">
        <h3 class="layer-title"></h3>
        <button type="button" class="btn-close">창 닫기 버튼</button>
        <div class="layer-content">
			<div class="card-month-scroll js-scroll">
				<img src="../static/img/common/card_monthly_benefit.jpg" alt="무이자할부 안내">
			</div>
        </div>
    </div>
</div>



<div id="contents">
	<div class="containerBody detail-page">
		
		<div class="breadcrumb">
			<ul>
				<li><a href="#">HOME</a></li>
				<li><a href="<?=$Dir?>front/productlist.php?code=<?=substr($thisCate[0]->category, 0, 3)?>"><?=$thisCate[0]->code_name?></a></li>
<?php
if( count( $thisCate ) > 1 ){
    $loop_cnt = count($thisCate);
    for ( $i = 1; $i < $loop_cnt; $i++ ) {
        $classOn = "";
        if ( $i == $loop_cnt - 1 ) {
            $classOn = "on";    // 마지막 카테고리에 on 처리
        }
?>
				<li class="<?=$classOn?>"><a href="<?=$Dir?>front/productlist.php?code=<?=$thisCate[$i]->category?>"><?=$thisCate[$i]->code_name?></a></li>

<?
    } // end of for
}
?>
			</ul>
		</div><!-- //.breadcrumb -->
		
		<form name='prForm' id='prForm' method='POST' action="<?=$Dir.FrontDir?>basket.php" >
		<input type='hidden' name='prcode' id='prcode' value='<?=$_pdata->productcode?>' >
		<input type='hidden' name='pridx' id='pridx' value='<?=$_pdata->pridx?>' >
		<input type="hidden" name="constant_quantity" id="constant_quantity" value="<?=$_pdata->quantity?>" >
		<input type='hidden' name="up_name" id="up_name" value="<?=$_ShopInfo->getmemname()?>" >
		<div class="goods-spec-wrap">
			
			<div class="inner-thumb-view">
				<div class="thumb-area">
					<p class="big-thumb">
							<img src="<?=getProductImage($imagepath_product, $_pdata->maximage)?>" alt=" " name="primg" >
					</p>
					
<?
    // 상품 썸네일 옆에 작은 이미지들을 배열에 저장해서 한번에 그려준다.
    $arrMiniThumbList = array();

	# 상품 큰 이미지
	if( is_file( $imagepath_product.$_pdata->maximage ) || strpos($_pdata->maximage, "http://") !== false ) {
        $tmp_imgCont = "
								<li>
									<a href=\"javascript:primg_preview('{$imagepath_product}','{$_pdata->maximage});\">
										<img src=\"" . getProductImage($imagepath_product, $_pdata->maximage) . "\" alt=\"\" >
									</a>
								</li>";
        array_push($arrMiniThumbList, $tmp_imgCont);
	}

	if ( $multi_img=="Y" && $yesimage[0] ) {
    
        $arrMultiImg = array(); // 상품 상세 설명이 없는 경우 노출하기 위해 배열에 저장
		foreach( $yesimage as $mImgKey=>$mImgVal ){
            $multiImg = getProductImage($imagepath_multi, $mImgVal);
            array_push($arrMultiImg, $multiImg);

            $tmp_imgCont = "
								<li>
									<a href=\"javascript:primg_preview('{$imagepath_multi}','{$mImgVal}');\">
										<img src=\"{$multiImg}\"  alt=\"\" >
									</a>
								</li>";
            array_push($arrMiniThumbList, $tmp_imgCont);
		}
    }

    if ( count($arrMiniThumbList) >= 1 ) {
?>

						<div class="thumb-list-wrap">
							<ul class="thumb-list" id="detail-thumb-list">
                                <?
                                    foreach ( $arrMiniThumbList as $key => $val ) {
                                        echo $val;
                                    }
                                ?>
							</ul>
						</div>
<?
    }
?>
				</div><!-- //.thumb-area -->
				<div class="promotion-sns">
<?php
	if( count( $promo_link ) > 0 ){ //프로모션 이 있을경우
?>
					<dl class="promotion">
						<dt>PROMOTION</dt>
<?php		
		foreach( $promo_link as $promoVal ){
?>
						<dd><?=$promoVal?></dd>
<?php
		}
?>
					</dl>
<?php
	}// promo_link if
?>
					<dl class="sns-share">
						<dt>SHARE SNS</dt>
						<dd><a href="javascript:sns('kakao')" class="kas" id='kakaostory-share-button'>카카오스토리</a></dd>
						<dd><a href="javascript:sns('facebook')" class="facebook">페이스북</a></dd>
						<dd><a href="javascript:sns('twitter')" class="twitter">트위터</a></dd>
					</dl>
				</div><!-- //.promotion-sns -->
			</div><!-- //.inner-thumb-view -->
			<div class="inner-spec-view">
				<div class="text-icon">
					<?=get_viewIcon( $_pdata->icon )?>
					<!-- 
					<span class="sale">SALE</span>
					<span class="best">BEST</span>
					<span class="special">SPECIAL</span>
					<span class="only">ONLY</span>
					 -->
				</div>
				<h2 class="product-name">
					<span class="<? if( $wish_row->cnt > 0 ) { echo 'wish-icon'; } ?>"></span><!-- 위시리스트에 상품이 담아져 있는경우 on 클래스 추가 -->
					<span class="promotion-ment" style="color:#<?=$_pdata->mdcommentcolor?> !important"><?=$_pdata->mdcomment?></span><?=$_pdata->productname?>
				</h2>
				<div class="product-price">
					<del><?=number_format( $_pdata->consumerprice )?></del>
					<strong><?=number_format( $_pdata->sellprice )?></strong>
					<span class="dc-per"><?=get_price_percent( $_pdata->consumerprice, $_pdata->sellprice )?>%</span>
				</div>
<?php
if( strlen( $_ShopInfo->getMemid() ) > 0 ) {
?>
				<ul class="mileage-info">
					<li><span>&bull; 예상 적립</span><?=number_format( getReserveConversion( $_pdata->reserve, $_pdata->reservetype, $_pdata->sellprice, "N") )?></li>
					<li><span>&bull; 사용 가능</span><?=number_format( $_ShopInfo->getMemreserve() )?></li>
				</ul>
<?php
}
?>
				<ul class="opt-choice">
					<li>
						<label>&bull; 무이자 할부</label>
						<button class="btn-benfit btn-card" type="button">무이자 카드 보기</button>
						
					</li>
					<li>
						<label>&bull; 쿠폰 혜택</label>
							<button class="btn-benfit btn-coupon" type="button">사용가능 쿠폰</button>
					</li>
<?php
if( strlen( $_pdata->option1 ) > 0 && $_pdata->quantity > 0  && $_pdata->option_type == '0' ){ // 조합형 옵션
	foreach( $optionNames as $nameKey=>$nameVal ) {
?>
					<li>
						<label>&bull; <?=$nameVal?></label>
						<div class="select small size CLS_options">
							<span class="ctrl"><span class="arrow"></span></span>
							<button type="button" class="my_value CLS_option_value"  data-option-code='' ><span>===선택===</span></button>
							<ul class="a_list CLS_option_select" >
								<li>
									<a href="javascript:option_select( '', '<?=$nameKey?>' );"  data-qty='' data-code='' >
										===선택===
									</a>
								</li>
<?php
		if( $nameKey == 0 ) {
			foreach( $options as $oKey=>$oVal ) {
				$option_qty = $oVal['qty'];
				$option_disable = '';
				$option_text = '';
				if( 
					( $option_qty !== null && $option_qty <= 0 ) && 
					( ( $option_depth > 0 && $nameKey != 0 ) || ( $option_depth == 1 && $nameKey == 0 ) ) &&
					$_pdata->quantity < 900000000
				){
					$option_disable = 'li-disable';
					$option_text = '[품절]&nbsp;';
				}
?>
								<li>
									<a href="javascript:option_select( '<?=$oVal["code"]?>', '<?=$nameKey?>' );" <?=$option_disable?> 
										data-qty='<?=$option_qty?>' data-code='<?=$oVal["code"]?>' >
										<?=$option_text.$oVal["code"]?>
									</a>
								</li>
<?php
			} // foreach $options
		} // nameKey if
?>
							</ul>
						</div>
					</li>
<?php
	} // optionNames foreach
} // if $_pdata->option1
if( strlen( $_pdata->option1 ) > 0 && $_pdata->quantity > 0  && $_pdata->option_type == '1' ){ // 독립형 옵션
	foreach( $optionNames as $nameKey=>$nameVal ) {
		$tf_text = '';
?>
					<li>
						<label>&bull; <?=$nameVal?></label>
						<div class="select small size">
							<span class="ctrl"><span class="arrow"></span></span>
							<button type="button" class="my_value" name='alone_option[]' data-option-code='' data-option-qty='' ><span>===선택===</span></button>
							<ul class="a_list" >
								<li>
									<a href="javascript:;" name='alone_option_val'  data-qty='' data-code='' data-tf='' >
										===선택===
									</a>
								</li>
<?php
		foreach( $options[$nameVal] as $oKey=>$oVal ) {
			if( $oVal->option_tf == 'T' ) $tf_text = '*필수';

			$option_disable = '';
			$option_text = '';
			if( 
				( $oVal->option_quantity !== null && $oVal->option_quantity <= 0 ) && 
				$_pdata->quantity < 900000000
			){
				$option_disable = 'li-disable';
				$option_text = '[품절]&nbsp;';
			}
?>
								<li>
									<a href="javascript:;" name='alone_option_val' data-qty='<?=$oVal->option_quantity?>' data-code='<?=$oVal->option_code?>' data-tf='<?=$oVal->option_tf?>' <?=$option_disable?> >
										<?=$option_text.$oKey?>
									</a>
								</li>
<?php
		}
?>
							</ul>
							<input type='hidden' name='alone_option_tf[]' value='<?=$oVal->option_tf?>' >
							<?=$tf_text?>
						</div>
					</li>
<?php
	} // $optionName foreach
} // option_type if
if( strlen( $_pdata->option2 ) > 0 && $_pdata->quantity > 0 ){
		foreach( $addOptionNames as $addKey=>$addVal ){
?>
					<li>
						<label>&bull; <?=$addVal?>
						</label>
						<div class="select">
							<input type='text' name ='addoption[]' data-option-code='<?=$addVal?>' data-option-tf='<?=$addOption_tf[$addKey]?>' maxlength='<?=$addOption_maxlen[$addKey]?>' '>
<?php
			if( $addOption_tf[$addKey] == 'T' ) echo ' *필수';
?>
							<input type='hidden' name='addoption_tf[]' value='<?=$addOption_tf[$addKey]?>' >
						</div>
					</li>
<?php
	} // addoption foreach
} // addoption if
?>
<?php
if( $_pdata->quantity <= 0){
?>
					<li>
						<input type="hidden" name='quantity' id='quantity' value="0">
					</li>
				</ul>
<?php
} else {
?>
					<li>
						<label>&bull; QUANTITY</label>
						<div class="ea-select">
							<button type="button" class="btn-minus">-</button>
							<button type="button" class="btn-plus">+</button>
							<input type="text" name='quantity' id='quantity' value="1">
						</div>
					</li>
				</ul><!-- //.opt-choice -->
				<div class="detail-buy-btn">
					<ul class="buy-btn">
						<li><a href="javascript:order_check(<?=strlen( $_ShopInfo->getMemid() )?>);" class="now">BUY NOW</a></li>
						<li><a href="javascript:basket_check();" class="cart">SHOPPING BAG</a></li>
						<li><a href="javascript:wish_check();" class="wish">WISH LIST</a></li>
					</ul>

                    <? if ( !empty($brand_code) ) { ?>
					<a href="<?=$Dir.FrontDir?>brand_detail.php?bridx=<?=$brand_code?>" class="cash-shop-b"><span><?=$brand_name?></span>브랜드 샵 가기</a>
                    <? } ?>

				</div>
<?php
} // quantity else
?>
				<!-- <div class="naver-pay-area">
					<img src="../static/img/test/@naver_pay.jpg" alt="">
				</div> -->


			</div><!-- //.inner-spec-view -->

		</div><!-- //.goods-spec-wrap -->
		</form>
<?php
if( $related_html ) {
?>
		<div class="related-goods-wrap">
			<div class="sub-title">
				<span class="roof"></span>
				<h4 class="title">RELATED PRODUCT</h4>
			</div>
			<div class="related-rolling-wrap">
			<div class="related-rolling with-btn-rolling">
<?php	
	foreach( $related_html as $key=>$related ){
		echo $related;
	} // related foreach
?>
				
			</div>
			</div><!-- //.related-rolling-wrap -->
		</div><!-- //.related-goods-wrap -->
<?php
} // related_html if
?>
		<a name="tab-product-info"></a>
		<div class="product-info-wrap">
			<span class="roof"></span>
			<ul class="detail-tab">
				<li class="on"><a href="#tab-product-info">PRODUCT INFO</a></li>
				<li><a href="#tab-product-review">REVIEW / Q&amp;A</a></li>
				<li><a href="#delivery-guide">DELIVERY GUIDE</a></li>
			</ul>
 <?php
// 브랜드 제목이 없거나 롤링이미지가 없는 경우
$hideClass = "";
if ( empty($brand_name) || count($arrRollingBannerImg) == 0 ) {
	$hideClass = "hide";
}
?>
			<div class="cash-made-wrap <?=$hideClass?>">
				<div class="made-introduce">
					<div class="inner-intro nano">
						<div class='nano-content'>
							<p class="title <?=$onBrandWishClass?>"><?=$brand_name?></p>
							<p class="ment">
								 <?=$brand_desc?>
							</p>
						</div>
					</div>
<?php 
if ( count($arrPromotionIdx) >= 1 ) { 
?>
					<dl class="related-promotion-list">
						<dt>related promotion</dt>
<?php 
	for ($i = 0; $i < count($arrPromotionIdx); $i++) { 
?>
						<dd><a href="/front/promotion_detail.php?idx=<?=$arrPromotionIdx[$i]?>">&gt; <?=$arrPromotionTitle[$i]?></a></dd>
<?php 
	} // arrPromotionIdx for
?>
					</dl>
<?php 
} // arrPromotionIdx if
?>
					<!-- <dl class="related-promotion-list">
						<dt>related promotion</dt>
						<dd><a href="#">&gt; CROSS BADY BAG NEW ARRIVAL 10% SALE</a></dd>
						<dd><a href="#">&gt; UNIQUE KIFE VALUE OPEN EVENT 10%</a></dd>
					</dl> -->
				</div>
				<div class="made-rolling-banner with-btn-rolling">
					<ul class="made-list" id="cash-made">
<?php 
	for ( $i = 0; $i < count($arrRollingBannerImg); $i++ ) { 
?>
						<li><img src="/data/shopimages/vender/<?=$arrRollingBannerImg[$i]?>" alt=""></li>
<?php 
} // arrRollingBannerImg for
?>
					</ul>
				</div>
			</div><!-- //.cash-made-wrap -->
            
			<div class="detail-contents">
<?php
    // ================================================================================
    // PRODUCT INFO
    // ================================================================================

	$_pdata_content = stripslashes($_pdata->content);
	if( strlen($detail_filter) > 0 ) {
		$_pdata_content = preg_replace($filterpattern,$filterreplace,$_pdata_content);
	}

    // <br>태그 제거
    $arrList = array("/<br\/>/", "/<br>/");
	$_pdata_content_tmp = trim(preg_replace($arrList, "", $_pdata_content));

    if ( empty($_pdata_content_tmp) ) {
        echo "<ul class=\"detail-thumb\">";
        foreach ( $arrMultiImg as $key => $val ) {
            echo "<li><img src=\"{$val}\" alt=\"\"></li>";
        }
        echo "</ul>";
    } else {
        if ( strpos($_pdata_content,"table>")!=false || strpos($_pdata_content,"TABLE>")!=false)
            echo "<pre>".$_pdata_content."</pre>";
        else if(strpos($_pdata_content,"</")!=false)
            echo nl2br($_pdata_content);
        else if(strpos($_pdata_content,"img")!=false || strpos($_pdata_content,"IMG")!=false)
            echo nl2br($_pdata_content);
        else
            echo str_replace(" ","&nbsp;",nl2br($_pdata_content));
    }
?>
			</div><!-- //.detail-contents -->

			<div class="common-spec-size-wrap">
				<!-- <div class="common-size-table">
					<p class="title">SIZE <span class="right">단위(cm)</span></p>
					<table class="common-size">
						<caption>상품 사이즈 고시</caption>
						<colgroup><col style="width:auto"><col style="width:90px"><col style="width:90px"><col style="width:90px"><col style="width:90px"><col style="width:90px"></colgroup>
						<thead>
							<tr>
								<th scope="col">사이즈</th>
								<th scope="col">90</th>
								<th scope="col">95</th>
								<th scope="col">100</th>
								<th scope="col">105</th>
								<th scope="col">110</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th scope="row">가슴둘레</th>
								<td>103</td>
								<td>108</td>
								<td>113</td>
								<td>118</td>
								<td>120</td>
							</tr>
							<tr>
								<th scope="row">목둘레</th>
								<td>56.5</td>
								<td>58</td>
								<td>59.5</td>
								<td>61</td>
								<td>62</td>
							</tr>
							<tr>
								<th scope="row">밑단둘레</th>
								<td>77</td>
								<td>82</td>
								<td>100</td>
								<td>105</td>
								<td>110</td>
							</tr>
							<tr>
								<th scope="row">상의길이</th>
								<td>60</td>
								<td>62</td>
								<td>64</td>
								<td>86</td>
								<td>95</td>
							</tr>
							<tr>
								<th scope="row">소매길이</th>
								<td>61</td>
								<td>62</td>
								<td>63</td>
								<td>64</td>
								<td>65</td>
							</tr>
							<tr>
								<th scope="row">어깨너비</th>
								<td>38</td>
								<td>40</td>
								<td>42</td>
								<td>44</td>
								<td>45</td>
							</tr>
							<tr>
								<th scope="row">총길이</th>
								<td>90</td>
								<td>95</td>
								<td>100</td>
								<td>105</td>
								<td>110</td>
							</tr>
						</tbody>
					</table>
					<ul class="attention">
						<li>위 사이즈는 해당 브랜드의 표준상품 사이즈이며, 단위는 cm 입니다.</li>
						<li>사이즈를 재는 위치나 방법에 따라 약간의 오차가 있을수있습니다.</li>
						<li>위 사항들은 교환 및 반품, 환불의 사유가 될수 없으며, 고객의 <br>단순변심으로 분류됩니다.</li>
					</ul>
				</div> -->
<?php
if( $jungbo_cnt > 3 ) {
?>
				<div class="common-spec-table">
					<p class="title"><?=$jungbo_title?></p>
					<table class="common-spec">
						<caption>상품 정보 고시</caption>
						<colgroup><col style="width:156px"><col style="width:300px"><col style="width:156px"><col style="width:auto"></colgroup>
						<tr>
							<th scope="row">소재</th>
							<td colspan="3">
								<?=$jungbo_val[1]?>
							</td>
						</tr>
						<tr>
							<th scope="col">제조년월</th>
							<td><?=$jungbo_val[2]?></td>
							<th scope="col">제조사 원산지</th>
							<td><?=$jungbo_val[3]?></td>
						</tr>
						<tr>
							<th scope="col">A/S 품질보증기간</th>
							<td><?=$jungbo_val[4]?></td>
							<th scope="col">A/S문의</th>
							<td><?=$jungbo_val[5]?></td>
						</tr>
						<tr>
							<th scope="row">세탁방법</th>
							<td colspan="3">
								<?=$jungbo_val[6]?>
							</td>
						</tr>
						<tr>
							<th scope="row">주의사항</th>
							<td colspan="3">
								<?=$jungbo_val[7]?>
							</td>
						</tr>
					</table>
				</div>
<?php
} // jungbo_cnt if
?>
			</div><!-- //.common-spec-size-wrap -->
		</div><!-- //.product-info-wrap -->

		<a name="tab-product-review"></a>
		<div class="product-review-wrap">
			<span class="roof"></span>
			<ul class="detail-tab">
				<li><a href="#tab-product-info">PRODUCT INFO</a></li>
				<li class="on"><a href="#tab-product-review">REVIEW / Q&amp;A</a></li>
				<li><a href="#delivery-guide">DELIVERY GUIDE</a></li>
			</ul>
			<div class="banner-place">
<?php
if( count( $review_banner ) > 0 ){ //리뷰베너
?>
				<a href="<?=$review_banner[94][1]['banner_link']?>" target="<?=$review_banner[94][1]['banner_target']?>" >
					<img src="<?=$review_banner[94][1]['banner_img']?>" alt="">
				</a>
<?php
}
?>
			</div>

			<div class="review-list-box">

				<? include($Dir.FrontDir."prreview_tem001.php"); ?>

			</div><!-- //.review-list-box -->

			<div class="review-list-box with-qna">

				<? include($Dir.FrontDir."prqna_tem001.php"); ?>

			</div><!-- //.review-list-box with-qna-->


		</div><!-- //.product-review-wrap -->

		<a name="delivery-guide"></a>
		<div class="delivery-guide-wrap">
			<span class="roof"></span>
			<ul class="detail-tab">
				<li><a href="#tab-product-info">PRODUCT INFO</a></li>
				<li><a href="#tab-product-review">REVIEW / Q&amp;A</a></li>
				<li class="on"><a href="#delivery-guide">DELIVERY GUIDE</a></li>
			</ul>
			<div class="delivery-info">
				<?=$deli_info?>
			</div>
		</div><!-- //.delivery-guide-wrap -->


	</div><!-- //.containerBody -->

</div><!-- //contents -->

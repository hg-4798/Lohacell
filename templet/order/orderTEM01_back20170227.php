
<div id="contents">
	<div class="cartOrder-page">

		<article class="cart-order-wrap">
			<header class="progess-title">
				<h2>주문/결제</h2>
				<ul class="flow clear">
					<li><div><i></i><span>STEP 1</span>장바구니</div></li>
					<li class="active"><div><i></i><span>STEP 2</span>주문하기</div></li>
					<li><div><i></i><span>STEP 3</span>주문완료</div></li>
				</ul>
			</header>

<!-- 브랜드별 -->
<?php
$sumprice = 0;
$deli_price = 0; // 선불 배송료
$deli_price2 = 0; //착불 배송료
$sum_product_reserve	= 0; // 총 예상 적립금
$checkTodayDelivery = false; // 당일 배송이 있는지 여부
$arrDeliveryTodayAddress = array(); // 당일 배송이 있으면 해당 주소 저장 배열
foreach( $brandArr as $brand=>$brandObj ){
	$brand_name = get_brand_name( $brand );
	$vender	=$brandVenderArr[$brand];
	$vender_price = 0;
	$product_reserve = 0;
	$product_price = 0;
?>
			<section class="mt-70">
				<header class="cart-section-title">
					<h3><?=$brand_name?> 주문상품</h3>
					<p class="att">*본사물류 또는 해당 브랜드 매장에서 택배로 고객님께 상품이 배송됩니다. (주문 완료 후, 3~5일 이내 수령)</p>
				</header>
				<table class="th-top">
					<caption><?=$brand_name?> 주문 상품</caption>
					<colgroup>
						<col style="width:auto">
						<col style="width:90px">
						<col style="width:90px">
						<col style="width:130px">
						<?php
							if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y" && $staff_order == 'N' ) {
						?>
						<col style="width:170px">
						<?	}?>
						<col style="width:136px">
					</colgroup>
					<thead>
						<tr>
							<th scope="col">상품정보</th>
							<th scope="col">수량</th>
							<th scope="col">적립</th>
							<th scope="col">판매가</th>
							<?php
								if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y" && $staff_order == 'N' ) {
							?>
							<th scope="col">쿠폰선택</th>
							<?	}?>
							<th scope="col">배송정보</th>
						</tr>
					</thead>
					
					<tbody>
<?php
	$product_count="0";
	foreach( $brandObj as $product ) {
		if($product['delivery_type'] == '0') {	//2016-10-07 libe90 매장발송일 경우 재고 가장 많은 매장으로 매장정보 표시
			$shop_code_set = getErpProdShopStock_Type($product['prodcode'], $product['colorcode'], $product['option'][0]['option_code'], 'delivery');
			$product['store_code'] = $shop_code_set['shopcd'];
		}
		$storeData = getStoreData($product['store_code']);
		$opt_price = 0; // 상품별 옶션가
		$pr_reserve = 0; //상품별 마일리지
        $tmp_opt_price = 0;
		if($product['delivery_type'] == '2'){
			$checkTodayDelivery = true;
			$arrDeliveryTodayAddress = array('post'=>$product['post_code'], 'address1'=>$product['address1'], 'address2'=>$product['address2']);
		}

		$vender_deli_price = 0;

		if( $product_deli[$vender] ){
			foreach( $product_deli[$vender] as $prDeliKey => $prDeliVal ){
				$vender_deli_price += $prDeliVal['deli_price'];
			}
		}
		
		$vender_deli_price += $vender_deli[$vender]['deli_price'];
?>
						<input type="hidden" name="obj_basketidx" value="<?=$product[basketidx]?>">
						<input type="hidden" name="obj_ci_no[<?=$product[basketidx]?>]" value="">
						<input type="hidden" name="obj_coupon_code[<?=$product[basketidx]?>]" value="">
						<input type="hidden" name="obj_dc[<?=$product[basketidx]?>]" value="">
						<input type="hidden" name="obj_product_price[<?=$product[basketidx]?>]" value="<?=$product['price']?>">

						<tr>
							<td class="pl-25">
								<div class="goods-in-td product_idx">
									<div class="thumb-img"><a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>" target="_blink"><img src="<?=getProductImage( $productImgPath, $product['tinyimage'] )?>"></a></div>
									<div class="info">
										<p class="brand-nm"><?=$brand_name?></p>
										<p class="goods-nm"><?=$product['productname']?></p>
										<p class="opt">
										<?php
											if( count( $product['option'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ){
												if( count( $product['option'] ) > 0 ){
													$tmp_opt_subject = explode( '@#', $product['option_subject'] );
													if( $product['option_type'] == 0 ){ // 조합형 옵션
														$tmp_option = $product['option'][0];
														$tmp_opt_contetnt = explode( chr(30), $tmp_option['option_code'] );
														foreach( $tmp_opt_subject as $optKey=>$optVal ){
															echo $optVal.' : '.$tmp_opt_contetnt[$optKey];
															$tmp_opt_price += $optVal['option_price'] * $product['option_quantity'];
														}// option foreach
														$opt_price += $tmp_option['option_price'] * $product['option_quantity'];
													}
													if( $product['option_type'] == 1 ){ // 독립형 옵션
														foreach( $product['option'] as $optKey=>$optVal ){
															$tmp_opt_content = explode( chr(30), $optVal['option_code'] );
															echo $tmp_opt_subject[$optKey].' : '.$tmp_opt_content[1];
															$tmp_opt_price += $optVal['option_price'] * $product['option_quantity'];
															$opt_price += $optVal['option_price'] * $product['option_quantity'];
														}// option foreach
													}
												} // count option

												if( $product['text_opt_content'] ){ // 추가문구 옵션
													$tmp_text_subejct = explode( '@#', $product['text_opt_subject'] );
													$text_opt_content = explode( '@#', $product['text_opt_content'] );
													foreach( $text_opt_content as $textKey=>$textVal ){
														if( $textVal != '' ) {
															echo $tmp_text_subejct[$textKey].' : '.$textVal;
														}
													}
												}  // text_opt_content if
												if( $tmp_opt_price > 0 ){
													echo '(추가금액 : '.number_format( $tmp_opt_price ).')';
												}
											} else {
												echo "-";
											}// count option || text_opt_subject if

											$pr_reserve = getReserveConversion( $product['reserve'], $product['reservetype'], ( $product['price'] * $product['quantity'] ) + $opt_price , "N" );
											if( strlen( $_ShopInfo->getMemid() ) == 0 ) $pr_reserve	= 0;
											$product_reserve += $pr_reserve; // 벤더별 상품 예상 적립금
											$product_price = ( $product['price']  * $product['quantity'] ) + $opt_price; //옵션가와 상품가를 합산해준다
											$vender_price += $product_price; // 벤더별 상품가격

											$sum_product_reserve += $pr_reserve; // 총 예상 적립금
										?>
										<!--색상 : NAM  / 사이즈 55-->
										</p>
									</div>
								</div>
							</td>
							<td><?=$product['quantity']?></td>
							<td class="txt-toneB"><?=number_format($pr_reserve)?></td>
							<td class="txt-toneA">\ <?=number_format($product_price)?></td>
							<?php
								if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y" && $staff_order == 'N' ) {
							?>
							<td><button class="btn-basic h-small w70 btn-couponList" type="button" onclick="javascript:product_coupon_pop(<?=$product[basketidx]?>)"><span>쿠폰사용</span></button></td>
							<?	}?>
							<!--<td class="flexible-delivery"><strong class="txt-toneA">\3,000</strong><div class="pt-5">50,000원 이상<br>무료배송</div></td>-->

							
							<td class="flexible-delivery"><div class="pt-5">
								<?if(count($storeData) > 0 && $product['delivery_type'] != '2'){	//2016-10-07 libe90 매장발송 정보표시?>
									<!--  <li style = 'color:blue;'>[<?=$arrDeliveryType[$product['delivery_type']]?>] <?=$storeData['name']?></li>-->
									<?if($product['delivery_type'] == '1'){?>
										<li style = 'color:blue;'>예약일 : <?=$product['reservation_date']?></li>
									<?}?>
								<?}else if($product['delivery_type'] == '2'){?>
									<li style = 'color:blue;'>[<?=$arrDeliveryType['2']?>] <?=$storeData['name']?></li>
									<li style = 'color:blue;'>주소 : [<?=$product['post_code']?>] <?=$product['address1']?> <?=$product['address2']?></li>
								<?}?>
							</td>
							<?if(!$product_count){?>
							<td class="flexible-delivery" rowspan="<?=count($brandObj)?>"><div class="pt-5">
								<?if($vender_deli_price>0 && !$vender_info[$vender]['deli_price_min']){?>
									\ <?=number_format($vender_deli_price)?>
								<?}else{?>
									<?if($vender_info[$vender]['deli_price_min']){?><?=number_format($vender_info[$vender]['deli_price_min'])?>원 이상<br><?}?>무료배송
								<?}?>							
							</div></td>
							<?}?>
						</tr>
<!-- //상품단위 종료 -->
<?php
		# 장바구니 쿠폰 제외
		foreach( $basket_coupon as $basketKey=>$basketVal ){
			if( !$_CouponInfo->check_coupon_product( $product['productcode'], 2, $basketVal ) ){
				unset( $basket_coupon[$basketKey] );
			}
		}
		$product_count++;
	} //foreach
?>
					</tbody>
					
					<tfoot>
<?php
	
	if( $vender_info[$vender] ){
		
?>
						<input type='hidden' name='select_price[<?=$vender?>]' value='<?=$vender_deli_price?>' data-vender='<?=$vender?>' >
						<tr>
							<td colspan="8" class="reset">
								<div class="cart-total-price clear">
									<dl>
										<dt>상품합계</dt>
										<dd>\ <?=number_format( $vender_price )?></dd>
									</dl>
									<!--
									<span class="txt point-color">-</span>
									<dl class="point-color">
										<dt>할인</dt>
										<dd>\ 0</dd>
									</dl>-->
									<span class="txt">+</span>
									<dl>
										<dt>배송비</dt>
										<dd>\ <?=number_format( $vender_deli_price )?></dd>
									</dl>
									<dl class="sum">
										<dt>합계</dt>
										<dd>\ <?=number_format( $vender_price + $vender_deli_price )?></dd>
									</dl>
								</div>
							</td>
						</tr>
<?php
	}
?>
					</tfoot>
					
				</table>
			</section><!-- //브랜드 주문상품 -->
					<!-- // 상품 리스트 -->
<?php
	if( $vender_info[$vender]['deli_select'] == '0' || $vender_info[$vender]['deli_select'] == '2' ) $deli_price += $vender_deli_price;
    if( $vender_info[$vender]['deli_select'] == '1' ) $deli_price2 += $vender_deli_price;
	$sumprice += $vender_price;
} // foreach
?>

			<section class="mt-60 hide">
				<header class="cart-section-title">
					<h3>O2O 주문상품</h3>
					<p class="att">*본사물류 또는 해당 브랜드 매장 중 가까운 매장에서 픽업할 수 있는 O2O서비스 입니다.</p>
				</header>
				<table class="th-top">
					<caption>O2O 주문상품</caption>
					<colgroup>
						<col style="width:auto">
						<col style="width:90px">
						<col style="width:90px">
						<col style="width:130px">
						<col style="width:170px">
						<col style="width:136px">
					</colgroup>
					<thead>
						<tr>
							<th scope="col">상품정보</th>
							<th scope="col">수량</th>
							<th scope="col">적립</th>
							<th scope="col">판매가</th>
							<th scope="col">쿠폰선택</th>
							<th scope="col">배송정보</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="8" class="reset">
								<div class="cart-total-price clear">
									<dl>
										<dt>상품합계</dt>
										<dd>\ 30,000,000</dd>
									</dl>
									<span class="txt point-color">-</span>
									<dl class="point-color">
										<dt>할인</dt>
										<dd>\ 0</dd>
									</dl>
									<span class="txt">+</span>
									<dl>
										<dt>배송비</dt>
										<dd>\ 0</dd>
									</dl>
									<dl class="sum">
										<dt>합계</dt>
										<dd>\ 30,000,000</dd>
									</dl>
								</div>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<td class="pl-25">
								<div class="goods-in-td">
									<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_03.jpg" alt="썸네일"></a></div>
									<div class="info">
										<p class="brand-nm">BESTI BELLI</p>
										<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
										<p class="opt">색상 : NAM  / 사이즈 55</p>
									</div>
								</div>
							</td>
							<td>2</td>
							<td class="txt-toneB">10%</td>
							<td class="txt-toneA">\ 10,955,800</td>
							<td><button class="btn-basic h-small w70 btn-couponList" type="button"><span>쿠폰사용</span></button></td>
							<td class="flexible-delivery">
								<div class="with-question">
									<strong class="txt-toneA">[발송매장]</strong>
									<div class="question-btn">
										<i class="icon-question">배송설명</i>
										<div class="comment">선택하신 상품은 당일수령이 가능한 상품입니다. </div>
									</div>
								</div>
								<strong class="txt-toneA">\3,000</strong><div class="pt-5">VIKI 강남역점</div>
								<button class="btn-basic h-small w70 mt-5 btn-infoStore" type="button"><span>매장안내</span></button>
							</td>
						</tr>
					</tbody>
				</table>
			</section><!-- //O2O 주문상품 -->

			<div class="order-infoReg clear mt-60">
				<div class="inner-input">
					<!-- 할인 및 결제정보 -->
					<section>
						<header class="cart-section-title"><h3>할인 및 결제정보</h3></header>
						<table class="th-left">
							<caption>할인 및 결제 확인</caption>
							<colgroup>
								<col style="width:178px">
								<col style="width:auto">
							</colgroup>
							<tbody>
								<tr>
									<th scope="row"><label>총 상품금액</label></th>
									<td>\ <?=number_format($sumprice+$deli_price)?></td>
								</tr>

<?php
	if ( strlen( $_ShopInfo->getMemid() ) > 0 && $_data->reserve_maxuse >= 0 && $user_reserve != 0 ){
?>
<?php
	if($okreserve<0){
		$okreserve=(int)($sumprice*abs($okreserve)/100);
		if($reserve_maxprice>$sumprice) {
			$okreserve=$user_reserve;
			$remainreserve=0;
		} else if($okreserve>$user_reserve) {
			$okreserve=$user_reserve;
			$remainreserve=0;
		} else {
			$remainreserve=$user_reserve-$okreserve;
		}
	}
?>

								<tr>
									<th scope="row"><label for="mileage-use">포인트 사용</label></th>
									<td>
										<input type="hidden" name="okreserve" id='okreserve' value="<?=$user_reserve?>">
<?php
		if( $_data->reserve_maxprice > $sumprice ) {
?>
										<input type="hidden" name="usereserve" id="mileage-use" value='0'>
<?php
		}else if( $user_reserve >= $_data->reserve_maxuse ){
?>
										<div class="input-cover">
											<input type="text" name="usereserve" id="mileage-use" title="E포인트 사용액 입력" id="use-my-ePoint" class="w100" value='0'>
											<span class="txt">P</span>
											<div class="checkbox ml-10">
												<input type="checkbox" id="all-mileage-use">
												<label for="all-mileage-use">모두사용</label>
											</div>
											<span class="pl-20 fz-13">(사용가능 포인트 <?=number_format( $user_reserve )?>P)</span>
										</div>
<?php
		}else{
?>
										<input type="hidden" name="usereserve" id="mileage-use" value='0'>
<?php
		}
?>
									</td>
								</tr>
<?php
	} else {
?>
							<input type="hidden" name="usereserve" id="mileage-use" value='0'>
							<input type="hidden" name="okreserve" id='okreserve' value="0">
<?php
	}
?>
<?php
	if ( strlen( $_ShopInfo->getMemid() ) > 0 && $_data->reserve_maxuse >= 0 && $user_point != 0 ){
?>
<?php
	if($okpoint<0){
		$okpoint=(int)($sumprice*abs($okpoint)/100);
		if($reserve_maxprice>$sumprice) {
			$okpoint=$user_point;
			$remainreserve=0;
		} else if($okpoint>$user_point) {
			$okpoint=$user_point;
			$remainreserve=0;
		} else {
			$remainreserve=$user_point-$okpoint;
		}
	}
?>

								<tr>
									<th scope="row"><label for="use-my-ePoint">E포인트 사용</label></th>
									<td>
										<input type="hidden" name="okpoint" id='okpoint' value="<?=$user_point?>">
<?php
		if( $_data->reserve_maxprice > $sumprice ) {
?>
										<input type="hidden" name="usepoint" id="point-use" value='0'>
<?php
		}else if( $user_point >= $_data->reserve_maxuse ){
?>
										<div class="input-cover">
											<input type="text" name="usepoint" id="point-use" title="E포인트 사용액 입력" id="use-my-ePoint" class="w100" value='0'>
											<span class="txt">P</span>
											<div class="checkbox ml-10">
												<input type="checkbox" id="check-epoint-all">
												<label for="check-epoint-all">모두사용</label>
											</div>
											<span class="pl-20 fz-13">(사용가능 포인트 <?=number_format( $user_point )?>P)</span>
										</div>
<?php
		}else{
?>
										<input type="hidden" name="usepoint" id="point-use" value='0'>
<?php
		}
?>
									</td>
								</tr>
<?php
	} else {
?>
							<input type="hidden" name="usepoint" id="point-use" value='0'>
							<input type="hidden" name="okpoint" id='okpoint' value="0">
<?php
	}
?>
								<tr>
									<th scope="row"><label>쿠폰할인</label></th>
									<td class="point-color">- \ <em class="CLS_prCoupon">0</em></td>
								</tr>
								<div id = "ID_coupon_code_layer">
									<div id = "ID_prd_coupon_layer" ></div>
									<div id = "ID_bk_coupon_layer" ></div>
									<div id = "ID_deli_coupon_layer" ></div>
								</div>
								<tr>
									<th scope="row"><label>배송비</label></th>
									<td>\ <em id='delivery_price'><?=number_format($deli_price)?></em></td>
								</tr>
								<tr>
									<th scope="row"><label>실 결제금액</label></th>
									<td class="fz-14 fw-bold point-color">\ <em class="price_sum" id="price_sum"><?=number_format($sumprice+$deli_price)?></em></td>
								</tr>
							</tbody>
						</table>
					</section><!-- //.할인 및 결제정보 -->

					<!-- 주문고객 정보 -->
					<section class="mt-40">
						<header class="cart-section-title"><h3>주문고객 정보</h3></header>
						<table class="th-left">
							<caption>주문고객 정보 확인</caption>
							<colgroup>
								<col style="width:178px">
								<col style="width:auto">
							</colgroup>
							<tbody>
								<tr>
									<th scope="row"><label for="order_name" class="essential">주문자</label></th>
									<td><div class="input-cover">
									<?php
if(strlen( $_ShopInfo->getMemid() ) > 0 ) {
?>
										<!-- 요청에 의해 readonly를 뺌 2015 12 09 유동혁 -->
										<input type='text' name="sender_name" id="order_name" value="<?=$userName?>" style="width:270px" required msgR="주문하시는분의 이름을 적어주세요">
<?php
} else {
?>
										<input type='text'  name="sender_name" id="order_name" value="" style="width:270px" required msgR="주문하시는분의 이름을 적어주세요">
<?php
} // else
?>
										
									</div></td>
								</tr>
								<tr>
									<th scope="row"><label for="order_email">이메일</label></th>
									<td>
										<div class="input-cover">
											<input type="text"  style="width:190px" title="이메일 입력" id="user-email" name='sender_email1' value='<?=$email[0]?>'>
											<span class="txt">@</span>
											<div class="select">
												<select style="width:170px" name="email_select" name="email_select" onchange="javascript:email_change()">
													<option value="">직접입력</option>
													<option value="naver.com" <?=$email[1]=='naver.com'?' selected':''?>>naver.com</option>
													<option value="gmail.com" <?=$email[1]=='gmail.com'?' selected':''?>>gmail.com</option>
													<option value="daum.net" <?=$email[1]=='daum.net'?' selected':''?>>daum.net</option>
													<option value="nate.com" <?=$email[1]=='nate.com'?' selected':''?>>nate.com</option>
													<option value="hanmail.net" <?=$email[1]=='hanmail.net'?' selected':''?>>hanmail.net</option>
													<option value="yahoo.com" <?=$email[1]=='yahoo.com'?' selected':''?>>yahoo.com</option>
													<option value="dreamwiz.com" <?=$email[1]=='dreamwiz.com'?' selected':''?>>dreamwiz.com</option>
												</select>
											</div>
											<input type="text" title="도메인 직접 입력" name="sender_email2" id="sender_email2" class="ml-10" value="<?=$email[1]?>" style="width:170px; display:none;"> <!-- [D] 직접입력시 인풋박스 출력 -->
										</div>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="sender_tel1" class="essential">휴대전화</label></th>
									<td>
										<div class="input-cover">
											<div class="select">
												<select id="sender_tel1" style="width:110px" name="sender_tel1" >
													<option value="010"<?=$mobile[0]=='010'?' selected':''?>>010</option>
													<option value="011"<?=$mobile[0]=='011'?' selected':''?>>011</option>
													<option value="016"<?=$mobile[0]=='016'?' selected':''?>>016</option>
													<option value="017"<?=$mobile[0]=='017'?' selected':''?>>017</option>
													<option value="018"<?=$mobile[0]=='018'?' selected':''?>>018</option>
													<option value="019"<?=$mobile[0]=='019'?' selected':''?>>019</option>
												</select>
											</div>
											<span class="txt">-</span>
											<input type="text" id="user-phone" name="sender_tel2" value="<?=$mobile[1] ?>" maxlength='4' style="width:110px" title="휴대전화번호 가운데 입력자리">
											<span class="txt">-</span>
											<input type="text" name="sender_tel3" value="<?=$mobile[2] ?>" maxlength='4' style="width:110px" title="휴대전화번호 마지막 입력자리">
										</div>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="home_tel1">전화번호(선택)</label></th>
									<td>
										<div class="input-cover">
											<div class="select">
												<select id="home_tel1" name="home_tel1" style="width:110px">
													<option value="02" selected>02</option>
													<option value="031">031</option>
													<option value="032">032</option>
													<option value="033">033</option>
													<option value="041">041</option>
													<option value="042">042</option>
													<option value="043">043</option>
													<option value="044">044</option>
													<option value="051">051</option>
													<option value="052">052</option>
													<option value="053">053</option>
													<option value="054">054</option>
													<option value="055">055</option>
													<option value="061">061</option>
													<option value="062">062</option>
													<option value="063">063</option>
													<option value="064">064</option>
												</select>
											</div>
											<span class="txt">-</span>
											<input type="text" id="home_tel2" name="home_tel2" maxlength='4' style="width:110px" title="전화번호 가운데 입력자리">
											<span class="txt">-</span>
											<input type="text" name="home_tel3" id='home_tel3' maxlength='4' style="width:110px" title="전화번호 마지막 입력자리">
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</section><!-- //.주문고객 정보 -->

					<!-- 배송지 정보 -->
					<section class="mt-40">
						<header class="cart-section-title">
							<h3>배송지 정보</h3>
							<div class="att" style="bottom:-9px">
								<?if($checkTodayDelivery == false){?>
								<div class="checkbox">
									<input type="checkbox" name="same" id="dev_orderer" value="Y" onclick="SameCheck(this.checked)">
									<label for="dev_orderer">주문고객과 동일한 주소 사용</label>
								</div>
								<button class="btn-basic h-small ml-20 btn-address-list" type="button" id="btn-deliveryList"><span>배송지목록</span></button>
								<?}?>
							</div>
						</header>
						<table class="th-left">
							<caption>배송지 정보 확인</caption>
							<colgroup>
								<col style="width:178px">
								<col style="width:auto">
							</colgroup>
							<tbody>
								<tr>
									<th scope="row"><label for="receiver_name" class="essential">받는사람</label></th>
									<td>
										<div class="input-cover">
											<input type="text" id="receiver_name" name = 'receiver_name' style="width:242px" required msgR="받으시는분 이름을 입력하세요." title="받는사람 입력자리">
											<div class="checkbox ml-20">
												<input type="checkbox" name="destinationt_type" value="Y" id="delivery_default_save">
												<label for="delivery_default_save">기본 배송지로 저장</label>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="receiver_tel21" class="essential">휴대전화</label></th>
									<td>
										<div class="input-cover">
											<div class="select">
												<select id="receiver_tel21" name="receiver_tel21" style="width:110px">
													<option value="010" selected>010</option>
													<option value="011">011</option>
													<option value="016">016</option>
													<option value="017">017</option>
													<option value="018">018</option>
													<option value="019">019</option>
												</select>
											</div>
											<span class="txt">-</span>
											<input type="text" id="receiver_tel22" name="receiver_tel22" maxlength='4' onKeyUp="strnumkeyup(this)" required style="width:110px" title="휴대전화번호 가운데 입력자리">
											<span class="txt">-</span>
											<input type="text" id="receiver_tel23" name="receiver_tel23" maxlength='4' onKeyUp="strnumkeyup(this)" required style="width:110px" title="휴대전화번호 마지막 입력자리">
										</div>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="receiver_tel11">전화번호(선택)</label></th>
									<td>
										<div class="input-cover">
											<div class="select">
												<select id="receiver_tel11" name="receiver_tel11" style="width:110px">
													<option value="02" selected>02</option>
													<option value="031">031</option>
													<option value="032">032</option>
													<option value="033">033</option>
													<option value="041">041</option>
													<option value="042">042</option>
													<option value="043">043</option>
													<option value="044">044</option>
													<option value="051">051</option>
													<option value="052">052</option>
													<option value="053">053</option>
													<option value="054">054</option>
													<option value="055">055</option>
													<option value="061">061</option>
													<option value="062">062</option>
													<option value="063">063</option>
													<option value="064">064</option>
												</select>
											</div>
											<span class="txt">-</span>
											<input type="text" id="receiver_tel12" name="receiver_tel12" maxlength='4' onKeyUp="strnumkeyup(this)" style="width:110px" title="전화번호 가운데 입력자리">
											<span class="txt">-</span>
											<input type="text" id="receiver_tel13" name="receiver_tel13" maxlength='4' onKeyUp="strnumkeyup(this)" style="width:110px" title="전화번호 마지막 입력자리">
										</div>
									</td>
								</tr>
								<tr>
									<th scope="row"><label class="essential">주소</label></th>
									<td>
										<?if($checkTodayDelivery == false){?>
										<ul class="input-multi input-cover">
											<input type='hidden' id='post5' name='post5' value='' >
											<input type="hidden" id="rpost1" name = 'rpost1'>
											<input type="hidden" id='rpost2' name = 'rpost2'>
											<li><input type="text" name = 'post' id = 'post' title="우편번호 입력자리"><button class="btn-basic" onclick="javascript:openDaumPostcode();"><span>주소찾기</span></button></li>
											<li><input type="text" name = 'raddr1' id = 'raddr1' title="검색된 주소" class="w100-per"></li>
											<li><input type="text" name = 'raddr2' id = 'raddr2'title="상세주소 입력" class="w100-per"></li>
										</ul>
										<?}else{?>
										<ul class="input-multi input-cover">
											<input type='hidden' id='post5' name='post5' value = '<?=$arrDeliveryTodayAddress['post']?>' readonly>
											<input type="hidden" id="rpost1" name = 'rpost1'>
											<input type="hidden" id='rpost2' name = 'rpost2'>
											<li><input type="text" name = 'post' id = 'post' value = '<?=$arrDeliveryTodayAddress['post']?>' title="우편번호 입력자리" readonly></li>
											<li><input type="text" name = 'raddr1' id = 'raddr1' value = '<?=$arrDeliveryTodayAddress['address1']?>' title="검색된 주소" class="w100-per" readonly></li>
											<li><input type="text" name = 'raddr2' id = 'raddr2' value = '<?=$arrDeliveryTodayAddress['address2']?>' title="상세주소 입력" class="w100-per" readonly></li>
										</ul>
										<?}?>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="prmsg_chg">배송 요청사항</label></th>
									<td>
										<ul class="input-multi input-cover">
											<li>
												<div class="select">
													<input type="hidden" name="msg_type" value="1">
													<select id="prmsg_chg" name='prmsg_chg' style="width:260px">
														<option value="" selected>직접입력</option>
														<option value="부재시 경비실에 맡겨 주세요">부재시 경비실에 맡겨 주세요</option>
														<option value="부재시 문앞에 놓아주세요">부재시 문앞에 놓아주세요</option>
														<option value="배송전에 연락주세요">배송전에 연락주세요</option>
														<option value="빠른배송 부탁드려요">빠른배송 부탁드려요</option>
														<option value="소화전에 넣어주세요">소화전에 넣어주세요</option>
														<option value="배관함에 넣어주세요">배관함에 넣어주세요</option>
													</select>
												</div>
											</li>
											<li><input type="text" title="배송 요청사항 입력" name = 'order_prmsg' id="order_prmsg" class="w100-per"></li>
										</ul>
									</td>
								</tr>
							</tbody>
						</table>
					</section><!-- //.배송지 정보 -->

				</div><!-- //.inner-input -->
				<div class="inner-confirm">
					<!-- 결제 수단 선택 -->
					<section class="order-payType">
						<header class="cart-section-title"><h3>결제 수단 선택</h3></header>
						<div class="frm">
							<dl>
								<dt>신용카드</dt>
								<?if(strstr("YC", $_data->payment_type) && ord($_data->card_id)) {?>
								<dd>
									<div class="radio">
										<input type="radio" id="dev_payment2" name="dev_payment" value="C" class='dev_payment' onclick="sel_paymethod(this);">
										<label for="dev_payment2">신용카드(일반)</label>
									</div>
								</dd>
								<?}?>
								<!--
								<?if($escrow_info["onlycard"]!="Y" && strstr("YN", $_data->payment_type)) {?>
								<dd>
									<div class="radio">
										<input type="radio" id="dev_payment1" name="dev_payment" value="B" class='dev_payment' onclick="sel_paymethod(this);">
										<label for="dev_payment1">무통장 입금</label>
									</div>
								</dd>
								<?}?>-->
							</dl>
							<dl>
								<dt>현금결제</dt>
								<?if($escrow_info["onlycard"]!="Y" && !strstr($_SERVER["HTTP_USER_AGENT"],'Mobile') && !strstr($_SERVER[HTTP_USER_AGENT],"Android") && ord($_data->trans_id)){?>
								<dd>
									<div class="radio">
										<input type="radio" id="dev_payment3" name="dev_payment" value="V" class='dev_payment' onclick="sel_paymethod(this);">
										<label for="dev_payment3">실시간 계좌이체</label>
									</div>
								</dd>
								<?}?>
								<dd>
									<div class="radio">
										<input type="radio"  id="dev_payment4" name="dev_payment" value="O" class='dev_payment' onclick="sel_paymethod(this);" >
										<label for="dev_payment4">가상계좌</label>
									</div>
								</dd>
							</dl>
							<p class="att">실행되는 보안 플러그인에 카드정보를 입력해주세요. <br>
							결제는 암호화 처리를 통해 안전합니다. 결제 후 재고가<br>
							없거나 본인이 요청이 있을 경우 배송전 결제를 취소할 수 있습니다.</p>
						</div>
						<div class="pay-type-card" id="card_type" style="display:none">
							<table border=0 cellpadding=0 cellspacing=0 width="100%" summary="임금계좌를 선택">
								<colgroup>
									<?if($etcmessage[2]=="Y") {?><col width="20%" ><?}?>
									<col >
								</colgroup>
								<?if($etcmessage[2]=="Y") {?>
								<tr>
									<th scope="row">입금자명</th>
									<td>
										<input type="text" name="bank_sender" value="" >
									</td>
								</tr>
								<?}?>
								<tr>
									<th scope="row">입금계좌</th>
									<td>
										<select name="pay_data_sel" id="pay_data_sel" onchange="sel_account(this)" style="width:100%;">
											<option value='' >입금 계좌번호 선택 (반드시 주문자 성함으로 입금)</option>
											<?foreach($bank_payinfo as $k => $v){?>
											<option value="<?=$v?>" ><?=$v?></option>
											<?}?>
										</select>
									</td>
								</tr>
								<tr>
									<th></th>
									<td>* 반드시 주문자 성함으로 입금</td>
								</tr>
							</table>
						</div>
					</section><!-- //결제 수단 선택 -->
					

					<!-- 결제금액 -->
					<section class="order-payConfirm mt-40">
						<?$p_price=$sumprice+$sumpricevat;?>
						<input type="hidden" name="total_sum" id='total_sum' value="<?=$p_price?>">
						<input type="hidden" name="total_sumprice" id='total_sumprice' value="<?=$p_price?>">
						<input type='hidden' name='total_deli_price' id='total_deli_price' value="<?=$deli_price?>" >
						<input type='hidden' name='total_deli_price2' id='total_deli_price2' value="<?=$deli_price2?>" >
						<header class="cart-section-title"><h3>결제금액</h3></header>
						<div class="frm">
							<dl>
								<dt>총 상품금액</dt>
								<dd>\<em id="paper_goodsprice" ><?=number_format($sumprice)?></em></dd>
							</dl>
							<dl>
								<dt>배송비</dt>
								<dd>\ <em id='delivery_price'><?=number_format($deli_price)?></em></dd>
							</dl>
							<dl>
								<dt>포인트 사용</dt>
								<dd class="point-color">- <em class="CLS_saleMil">0</em> P</dd>
							</dl>
							<dl>
								<dt>E포인트 사용</dt>
								<dd class="point-color">- <em class="CLS_salePoi">0</em> P</dd>
							</dl>
							<dl <?if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y" && $staff_order == 'N' ) {}else {?> class='hide'<?}?>>
								<dt>쿠폰 사용</dt>
								<dd class="point-color">- \ <em class="CLS_prCoupon">0</em></dd>
							</dl>
							<!--
							<dl>
								<dt>장바구니 쿠폰 사용</dt>
								<dd class="point-color">- \ 0</dd>
							</dl>-->
							<dl class="final-price">
								<dt>실 결제금액</dt>
								<dd class="point-color">\ <em class="price_sum" id="price_sum"><?=number_format($sumprice+$deli_price)?></em></dd>
							</dl>
							<p class="att">총 적립예정 포인트</p>
							<dl class="pt-15">
								<dt>포인트</dt>
								<dd class="fz-14 fw-bold"><?=number_format($sum_product_reserve)?>P</dd>
							</dl>
							<!--
							<dl class="pt-15">
								<dt>E포인트</dt>
								<dd class="fz-14 fw-bold"><?=number_format($sum_product_reserve)?>P</dd>
							</dl>-->
						</div>
					</section><!-- //결제금액 -->

					<div class="final-agree mt-10">
						<div class="checkbox">
							<input type="checkbox" id="dev_agree">
							<label for="dev_agree">동의합니다. (전자상거래법 제 8조 제 2항)</label>
						</div>
						<p>주문하실 상품,가격,배송정보,할인내역 등을 최종<br>확인하였으며,구매에 동의하시겠습니까?</p>
					</div>

					<div class="order-buy mt-10"><button type="button" class="btn-point w100-per" onclick="javascript:CheckForm()"><span>결제하기</span></button></div>
				</div><!-- //.inner-confirm -->
			</div>


		</article><!-- //.cart-order-wrap -->

	</div>
</div><!-- //#contents -->

<!-- 주문 > 배송지목록 -->
<div class="layer-dimm-wrap popList delivery">
	<div class="layer-inner">
		<h2 class="layer-title">배송지 목록</h2>
		<button class="btn-close" type="button"><span>닫기</span></button>
		<div class="layer-content">
		<input type="hidden" name="dn_inr" id="dn_inr">
			<ul class="list">
<?
foreach( $dn_info as $dn_vkey=>$dn_val ){
	//exdebug($dn_val);
?>
				<li>
					<div class="radio ml-20">
						<input type="radio" name="my_deliveryList" id="deliver_list1" onClick="javascript:Dn_InReceivercheck('<?=$dn_val->no.'|@|'.$dn_val->destination_name.'|@|'.$dn_val->get_name.'|@|'.addMobile($dn_val->mobile).'|@|'.$dn_val->postcode.'|@|'.$dn_val->postcode_new.'|@|'.$dn_val->addr1.'|@|'.$dn_val->addr2?>')">
						<label for="deliver_list1"></label>
					</div>
					<div class="content w300">
						<p class="bold"><?=$dn_val->destination_name?></p>
						<p class="txt-toneB"><?=$dn_val->addr1?> <?=$dn_val->addr2?></p>
					</div>
				</li>
<?
}
?>
			</ul>
			<div class="btnPlace mt-10">
				<button class="btn-line  h-large" type="button" onclick="javascript:Dn_InReceiver('cancel')"><span>취소</span></button>
				<button class="btn-point h-large" type="button" onclick="javascript:Dn_InReceiver('in')"><span>적용</span></button>
			</div>

		</div><!-- //.layer-content -->
	</div>
</div><!-- //주문 > 배송지목록 -->

<!-- 주문 > 쿠폰목록 -->
<div class="layer-dimm-wrap popList coupon">
	<div class="layer-inner">
		<h2 class="layer-title">쿠폰 목록</h2>
		<button class="btn-close" type="button"><span>닫기</span></button>
		<div class="layer-content coupon_list">
<!--
			<ul class="list">
			
				<li>
					<div class="radio ml-20">
						<input type="radio" name="my_couponList" id="coupon_list1">
						<label for="coupon_list1"></label>
					</div>
					<div class="content w350 ">
						<p class="txt-toneB">브랜드 데이 특별 할인 쿠폰</p>
						<p class="point-color">10% 할인</p>
					</div>
				</li>
				<li>
					<div class="radio ml-20">
						<input type="radio" name="my_couponList" id="coupon_list2">
						<label for="coupon_list2"></label>
					</div>
					<div class="content w350 ">
						<p class="txt-toneB">브랜드 데이 특별 할인 쿠폰</p>
						<p class="point-color">15% 할인</p>
					</div>
				</li>
				<li>
					<div class="radio ml-20">
						<input type="radio" name="my_couponList" id="coupon_list3">
						<label for="coupon_list3"></label>
					</div>
					<div class="content w350 ">
						<p class="txt-toneB">브랜드 데이 특별 할인 쿠폰</p>
						<p class="point-color">20% 할인</p>
					</div>
				</li>
			</ul>
			<div class="btnPlace mt-10">
				<button class="btn-line  h-large" type="button"><span>취소</span></button>
				<button class="btn-point h-large" type="button"><span>적용</span></button>
			</div>
-->
		</div><!-- //.layer-content -->
	</div>
</div><!-- //주문 > 쿠폰목록 -->
<?/*?>
<div id="contents">
	<div class="inner">
		<main class="mypage_wrap"><!-- 페이지 성격에 맞게 클래스 구분 -->
			<article class="order_wrap">
				<section class="mypage_main order">

					<div class="order_flow">
						<ul>
							<li><div>장바구니</div></li>
							<li class="on"><div>주문하기</div></li>
							<li><div>결제완료</div></li>
						</ul>
					</div>
<!-- 브랜드별 -->
<?php
$sumprice = 0;
$deli_price = 0; // 선불 배송료
$deli_price2 = 0; //착불 배송료
$sum_product_reserve	= 0; // 총 예상 적립금
$checkTodayDelivery = false; // 당일 배송이 있는지 여부
$arrDeliveryTodayAddress = array(); // 당일 배송이 있으면 해당 주소 저장 배열
foreach( $brandArr as $brand=>$brandObj ){
	$brand_name = get_brand_name( $brand );
	$vender	=$brandVenderArr[$brand];
	$vender_price = 0;
	$product_reserve = 0;
	$product_price = 0;
?>
					<div class="title_type1">
						<h3>[<?=$brand_name?>] 주문상품 정보</h3>
					</div>

					<!-- 상품리스트 -->
					<div class="order_list_wrap mb-30">
						<table class="th_top">
							<caption>[<?=$brand_name?>] 주문상품 정보</caption>
							<colgroup>
								<col style="width:auto">
								<col style="width:12%">
								<col style="width:12%">
								<col style="width:12%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">[<?=$brand_name?>] 상품정보</th>
									<th scope="col">옵션</th>
									<th scope="col">수량</th>
									<th scope="col">판매금액</th>
								</tr>
							</thead>
							<tbody>
<?php
	foreach( $brandObj as $product ) {
		if($product['delivery_type'] == '0') {	//2016-10-07 libe90 매장발송일 경우 재고 가장 많은 매장으로 매장정보 표시
			$shop_code_set = getErpProdShopStock_Type($product['prodcode'], $product['colorcode'], $product['option'][0]['option_code'], 'delivery');
			$product['store_code'] = $shop_code_set['shopcd'];
		}
		$storeData = getStoreData($product['store_code']);
		$opt_price = 0; // 상품별 옶션가
		$pr_reserve = 0; //상품별 마일리지
        $tmp_opt_price = 0;
		if($product['delivery_type'] == '2'){
			$checkTodayDelivery = true;
			$arrDeliveryTodayAddress = array('post'=>$product['post_code'], 'address1'=>$product['address1'], 'address2'=>$product['address2']);
		}
?>
								<!-- 상품단위 시작 -->
								<tr class="bold" name="product_list">
									<td class="goods_info">
										<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>" target="_blink">
											<img src="<?=getProductImage( $productImgPath, $product['tinyimage'] )?>">
											<ul>
												<li>[<?=$brand_name?>]</li>
												<li><?=$product['productname']?></li>
												<?if(count($storeData) > 0 && $product['delivery_type'] != '2'){	//2016-10-07 libe90 매장발송 정보표시?>
													<!--  <li style = 'color:blue;'>[<?=$arrDeliveryType[$product['delivery_type']]?>] <?=$storeData['name']?></li>-->
													<?if($product['delivery_type'] == '1'){?>
														<li style = 'color:blue;'>예약일 : <?=$product['reservation_date']?></li>
													<?}?>
												<?}else if($product['delivery_type'] == '2'){?>
													<li style = 'color:blue;'>[<?=$arrDeliveryType['2']?>] <?=$storeData['name']?></li>
													<li style = 'color:blue;'>주소 : [<?=$product['post_code']?>] <?=$product['address1']?> <?=$product['address2']?></li>
												<?}?>
											</ul>
										</a>
									</td>
									<td class="opt_text">
<?php
		if( count( $product['option'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ){
			if( count( $product['option'] ) > 0 ){
				$tmp_opt_subject = explode( '@#', $product['option_subject'] );
				if( $product['option_type'] == 0 ){ // 조합형 옵션
					$tmp_option = $product['option'][0];
					$tmp_opt_contetnt = explode( chr(30), $tmp_option['option_code'] );
					foreach( $tmp_opt_subject as $optKey=>$optVal ){
						echo '<p>'.$optVal.' : <em>'.$tmp_opt_contetnt[$optKey].'</em></p>';
                        $tmp_opt_price += $optVal['option_price'] * $product['option_quantity'];
					}// option foreach
                    $opt_price += $tmp_option['option_price'] * $product['option_quantity'];
				}
				if( $product['option_type'] == 1 ){ // 독립형 옵션
					foreach( $product['option'] as $optKey=>$optVal ){
						$tmp_opt_content = explode( chr(30), $optVal['option_code'] );
						echo '<p>'.$tmp_opt_subject[$optKey].' : <em>'.$tmp_opt_content[1].'</em></p>';
						$tmp_opt_price += $optVal['option_price'] * $product['option_quantity'];
						$opt_price += $optVal['option_price'] * $product['option_quantity'];
					}// option foreach
				}
			} // count option

			if( $product['text_opt_content'] ){ // 추가문구 옵션
				$tmp_text_subejct = explode( '@#', $product['text_opt_subject'] );
				$text_opt_content = explode( '@#', $product['text_opt_content'] );
				foreach( $text_opt_content as $textKey=>$textVal ){
					if( $textVal != '' ) {
						echo '<p>'.$tmp_text_subejct[$textKey].' : <em>'.$textVal.'</em></p>';
					}
				}
			}  // text_opt_content if
            if( $tmp_opt_price > 0 ){
    			echo '<p>(추가금액 : <em>'.number_format( $tmp_opt_price ).'</em>)</p>';
            }
		} else {
			echo "-";
		}// count option || text_opt_subject if

		$pr_reserve = getReserveConversion( $product['reserve'], $product['reservetype'], ( $product['price'] * $product['quantity'] ) + $opt_price , "N" );
		if( strlen( $_ShopInfo->getMemid() ) == 0 ) $pr_reserve	= 0;
		$product_reserve += $pr_reserve; // 벤더별 상품 예상 적립금
		$product_price = ( $product['price']  * $product['quantity'] ) + $opt_price; //옵션가와 상품가를 합산해준다
		$vender_price += $product_price; // 벤더별 상품가격

		$sum_product_reserve += $pr_reserve; // 총 예상 적립금


?>
									</td>
									<td><?=$product['quantity']?></td>
									<td class="payment"><?=number_format($product_price)?>원</td>
								</tr>
<!-- //상품단위 종료 -->
<?php
		# 장바구니 쿠폰 제외
		foreach( $basket_coupon as $basketKey=>$basketVal ){
			if( !$_CouponInfo->check_coupon_product( $product['productcode'], 2, $basketVal ) ){
				unset( $basket_coupon[$basketKey] );
			}
		}
	} //foreach
?>
							</tbody>
							<tfoot>
<?php
	$vender_deli_price = 0;
	if( $vender_info[$vender] ){
?>
								<tr class="bg">
									<td colspan="4">
							판매금액 <?=number_format( $vender_price )?>원 + 배송비
<?php
			if( $vender_info[$vender]['deli_select'] == '0' ){
				//echo '선불';
			} else if( $vender_info[$vender]['deli_select'] == '1' ) {
				//echo '착불';
			} else if( $vender_info[$vender]['deli_select'] == '2' ) {
?>
                            <select name='deli_select[<?=$vender?>]' data-vender='<?=$vender?>' >
                                <option value='0' >선불</option>
                                <option value='1' >착불</option>
                            </select>
<?php
			}

			if( $product_deli[$vender] ){
				foreach( $product_deli[$vender] as $prDeliKey => $prDeliVal ){
					$vender_deli_price += $prDeliVal['deli_price'];
				}
			}
			$vender_deli_price += $vender_deli[$vender]['deli_price'];
?>

                            <input type='hidden' name='select_price[<?=$vender?>]' value='<?=$vender_deli_price?>' data-vender='<?=$vender?>' >
							<?=number_format( $vender_deli_price )?>원 = 주문 금액 <strong><?=number_format( $vender_price + $vender_deli_price )?>원</strong>
<?php
    if( $vender_info[$vender]['deli_price_min'] != 0 ){
?>
							<br><span class="delivery-ment">(<?=$brand_name?> 제품으로만 <?=number_format( $vender_info[$vender]['deli_price_min'] )?>원 이상 구매시 무료배송됩니다. [매장픽업 & 당일수령 제외])</span>
<?php
    }
?>
									</td>
								</tr>
<?php
	}
?>
							</tfoot>
						</table>
					</div>
					<!-- // 상품 리스트 -->
<?php
	if( $vender_info[$vender]['deli_select'] == '0' || $vender_info[$vender]['deli_select'] == '2' ) $deli_price += $vender_deli_price;
    if( $vender_info[$vender]['deli_select'] == '1' ) $deli_price2 += $vender_deli_price;
	$sumprice += $vender_price;
} // foreach
?>

					<!-- 상품총액 -->
					<div class="total_wrap mt-30">
						<div class="total_price clear">
							<span>총 결제금액</span>
							<ul class="clear">
								<li><div>상품 금액 합계 <em><?=number_format($sumprice)?>원</em></div></li>
								<li class='hide'><div>할인 <em>0원</em></div></li>
								<li><div>배송비 <em><?=number_format($deli_price)?>원</em></div></li>
								<li><div><p>결제금액</p> <em><?=number_format($sumprice+$deli_price)?>원</em></div></li>
							</ul>
						</div>
					</div>
					<!-- // 상품총액 -->

					<div class="payment-reg-wrap">
						<div class="inner-info">

							<section class="pay-info01">
								<div class="title_wrap">
									<h3>할인 및 결제 정보</h3>
								</div>
								<table class="th_left">
									<caption>결제 정보를 확인합니다.</caption>
									<colgroup>
										<col style="width:160px">
										<col style="width:auto">
									</colgroup>
									<thead>
										<tr>
											<th scope="row">총 주문금액</th>
											<td><strong><?=number_format($sumprice+$deli_price)?>원</strong></td>
										</tr>
									</thead>
									<tbody class="bordernone">
<?php
if ((strlen($_ShopInfo->getMemid())>0 && $_data->reserve_maxuse>=0 && $user_reserve!=0) || (strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y")) {
?>
<?php
	if($okreserve<0){
		$okreserve=(int)($sumprice*abs($okreserve)/100);
		if($reserve_maxprice>$sumprice) {
			$okreserve=$user_reserve;
			$remainreserve=0;
		} else if($okreserve>$user_reserve) {
			$okreserve=$user_reserve;
			$remainreserve=0;
		} else {
			$remainreserve=$user_reserve-$okreserve;
		}
	}
?>


<?php
	if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y" && $staff_order == 'N' ) {
?>
										<tr>
											<th scope="row"><label for="use_c">- 쿠폰 사용</label></th>
											<td>
												<button class="btn-type1 coupon-use" type="button"><span>쿠폰선택</span></button>
											</td>
										</tr>
										<tr class='hide'>
											<th scope="row"><label for="use_c">- 장바구니 쿠폰 사용</label></th>
											<td>
												<select id='bk_coupon' name='bk_coupon' class='CLS_coupon_value' onChange="javascript:set_basket_coupon(this.value);">
												<option value="">쿠폰선택</option>
<?php
		foreach( $basket_coupon as $bcouponKey=>$bcouponVal ){
                // 사용조건 체크
                if( $bcouponVal->mini_quantity == 0 || ( $bcouponVal->mini_type == 'P' && $bcouponVal->mini_price <= $total_price_sum )
                    || ( $bcouponVal->mini_type == 'Q' && $bcouponVal->mini_quantity <= $total_qty )
                ){
?>
												<option value="<?=$bcouponVal->ci_no?>"><?=$bcouponVal->coupon_name?></option>
<?php
                }
		} // $basket_coupon foreach
?>
												</select>
												<div id = "ID_coupon_code_layer">
													<div id = "ID_prd_coupon_layer" ></div>
													<div id = "ID_bk_coupon_layer" ></div>
													<div id = "ID_deli_coupon_layer" ></div>
												</div>
											</td>
										</tr>
<?php
        if( count( $deliver_coupon ) > 0 && ( $deli_price + $deli_price2 ) > 0 ){
?>
										<tr>
											<th scope='row'>- 배송비 무료 쿠폰</th>
											<td>
												<input type='checkbox' name='dcoupon_ci_no' value='<?=$deliver_coupon[0]->ci_no?>' > <?=$deliver_coupon[0]->coupon_name?>
												<input type='hidden' name='dcoupon_price' value='0' >
											</td>
										</tr>
<?php
        }
	}
?>

<?php
	if ( strlen( $_ShopInfo->getMemid() ) > 0 && $_data->reserve_maxuse >= 0 && $user_reserve != 0 ){
?>
										<tr class='hide'>
											<th scope="row"><label for="mileage-use">- 포인트 사용</label></th>
											<td>
												<input type="hidden" name="okreserve" id='okreserve' value="<?=$user_reserve?>">
<?php
		if( $_data->reserve_maxprice > $sumprice ) {
?>
												<input type="hidden" name="usereserve" id="mileage-use" value='0'>
<?php
		}else if( $user_reserve >= $_data->reserve_maxuse ){
?>
												<div><input type="text" name="usereserve" id="mileage-use" title="포인트 사용 금액" value='0'> 원</div>
												<div><input type="checkbox" id="all-mileage-use"><label for="all-mileage-use">모두사용</label></div>
												(가용 포인트 <strong><?=number_format( $user_reserve )?>P</strong>)
<?php
		}else{
?>
												<input type="hidden" name="usereserve" id="mileage-use" value='0'>
<?php
		}
?>
											</td>
										</tr>
<?php
	} else {
?>
							<input type="hidden" name="usereserve" id="mileage-use" value='0'>
							<input type="hidden" name="okreserve" id='okreserve' value="0">
<?php
	}
?>
<?php
}
?>
										<tr>
											<th scope="row">+ 배송비</th>
											<td>
										<? if( false ){ ?>
												<?=number_format($deli_price)?>원
												<input type=radio name=deli_type class="deli_type" value="0" checked > 선불
												<input type=radio name=deli_type class="deli_type" id="deli_type1" value="1" > 착불
										<? }else{ ?>
											<!-- 배송비는 벤더별 기준이기에 0으로 고정 -->
												<?=number_format($deli_price)?>원
												<input type="hidden" id='deli_type' name=deli_type value="0" >
										<? } ?>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<th scope="row">총 결제금액</th>
											<td>
												<strong class="type_txt2"><em id="all_price_sum"><?=number_format($sumprice+$deli_price)?></em>원</strong>
												<?if($staff_order == 'Y') { // 임직원 구매이면?>
												(잔여 임직원 포인트: <span><em><?=number_format( $staff_reserve )?></em></span>)
												<?} else {?>
												(총 할인금액 <span><em id="all_dc_price_sum">0</em>원</span>)
												<?}?>
											</td>
										</tr>
									</tfoot>
								</table>
							</section><!-- //.pay-info01 -->

							<section class="pay-info02">
								<div class="title_wrap">
									<h3>주문고객 정보</h3>
								</div>
								<table class="th_left">
									<caption>주문고객의 정보를 입력합니다.</caption>
									<colgroup>
										<col style="width:160px">
										<col style="width:auto">
									</colgroup>
									<tbody>
										<tr>
											<th scope="row"><label for="">주문자</label></th>
											<td>
<?php
if(strlen( $_ShopInfo->getMemid() ) > 0 ) {
?>
										<!-- 요청에 의해 readonly를 뺌 2015 12 09 유동혁 -->
										<input class="input-def" type='text'  name="sender_name" value="<?=$userName?>" style="font-weight:bold" style='border:0' required msgR="주문하시는분의 이름을 적어주세요">
<?php
} else {
?>
										<input type='text'  name="sender_name" value="" style="font-weight:bold" style='border:0' required msgR="주문하시는분의 이름을 적어주세요">
<?php
} // else
?>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="user-email">이메일</label></th>
											<td>
												<input type="text" id="user-email" name='sender_email' title="이메일 입력자리" value='<?=$email?>' >
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="sender_tel1">휴대전화</label></th>
											<td>
												<select name="sender_tel1" id='sender_tel1'>
													<option value="010"<?=$mobile[0]=='010'?' selected':''?>>010</option>
													<option value="011"<?=$mobile[0]=='011'?' selected':''?>>011</option>
													<option value="016"<?=$mobile[0]=='016'?' selected':''?>>016</option>
													<option value="017"<?=$mobile[0]=='017'?' selected':''?>>017</option>
													<option value="018"<?=$mobile[0]=='018'?' selected':''?>>018</option>
													<option value="019"<?=$mobile[0]=='019'?' selected':''?>>019</option>
												</select>
												<span class="dash">-</span>
												<input type="text" id="user-phone" name="sender_tel2" value="<?=$mobile[1] ?>" maxlength='4' class="short" title="휴대전화번호 가운데 입력자리">
												<span class="dash">-</span>
												<input type="text" name="sender_tel3" value="<?=$mobile[2] ?>" maxlength='4' class="short" title="휴대전화번호 마지막 입력자리">
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="home_tel1">전화번호(선택)</label></th>
											<td>
												<select name="home_tel1" id='home_tel1'>
													<option value="02" selected>02</option>
													<option value="031">031</option>
													<option value="032">032</option>
													<option value="033">033</option>
													<option value="041">041</option>
													<option value="042">042</option>
													<option value="043">043</option>
													<option value="044">044</option>
													<option value="051">051</option>
													<option value="052">052</option>
													<option value="053">053</option>
													<option value="054">054</option>
													<option value="055">055</option>
													<option value="061">061</option>
													<option value="062">062</option>
													<option value="063">063</option>
													<option value="064">064</option>
												</select>
												<span class="dash">-</span>
												<input type="text" id="home_tel2" name="home_tel2" maxlength='4' class="short" title="전화번호 가운데 입력자리">
												<span class="dash">-</span>
												<input type="text" name="home_tel3" id='home_tel3' maxlength='4' class="short" title="전화번호 마지막 입력자리">
											</td>
										</tr>
									</tbody>
								</table>
							</section><!-- //.pay-info02 -->

							<section class="pay-info03">
								<div class="title_wrap">
									<h3>배송지 정보</h3>
									<p class="ment" style="bottom:-5px">
										<?if($checkTodayDelivery == false){?>
											<!-- 당일배송이 없을때만 노출 -->
											<input type="checkbox" name="same" value="Y" onclick="SameCheck(this.checked)" id="dev_orderer"><label for="dev_orderer">주문고객과 동일한 주소 사용</label>
											<button class="btn-type1 btn-address-list"><span>배송지 목록</span></button>
										<?}?>
									</p>
								</div>
								<table class="th_left">
									<caption>배송지정보를 입력합니다.</caption>
									<colgroup>
										<col style="width:160px">
										<col style="width:auto">
									</colgroup>
									<tbody>
										<tr>
											<th scope="row"><label for="receiver_name">받는사람</label></th>
											<td><input type="text" id="receiver_name" name = 'receiver_name' required msgR="주문하시는 분 이름을 입력하세요." title="주문자 입력자리"></td>
										</tr>
										<tr>
											<th scope="row"><label for="receiver_tel21">휴대전화</label></th>
											<td>
												<select name="receiver_tel21" id="receiver_tel21">
													<option value="010" selected>010</option>
													<option value="011">011</option>
													<option value="016">016</option>
													<option value="017">017</option>
													<option value="018">018</option>
													<option value="019">019</option>
												</select>
												<span class="dash">-</span>
												<input type="text" id="receiver_tel22" name="receiver_tel22" maxlength='4' onKeyUp="strnumkeyup(this)" required class="short" title="휴대전화번호 가운데 입력자리">
												<span class="dash">-</span>
												<input type="text" id="receiver_tel23" name="receiver_tel23" maxlength='4' onKeyUp="strnumkeyup(this)" required class="short" title="휴대전화번호 마지막 입력자리">
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="receiver_tel11">전화번호(선택)</label></th>
											<td>
												<select name="receiver_tel11" id='receiver_tel11'>
													<option value="02" selected>02</option>
													<option value="031">031</option>
													<option value="032">032</option>
													<option value="033">033</option>
													<option value="041">041</option>
													<option value="042">042</option>
													<option value="043">043</option>
													<option value="044">044</option>
													<option value="051">051</option>
													<option value="052">052</option>
													<option value="053">053</option>
													<option value="054">054</option>
													<option value="055">055</option>
													<option value="061">061</option>
													<option value="062">062</option>
													<option value="063">063</option>
													<option value="064">064</option>
												</select>
												<span class="dash">-</span>
												<input type="text" id="receiver_tel12" name="receiver_tel12" maxlength='4' onKeyUp="strnumkeyup(this)" required class="short" title="전화번호 가운데 입력자리">
												<span class="dash">-</span>
												<input type="text" id="receiver_tel13" name="receiver_tel13" maxlength='4' onKeyUp="strnumkeyup(this)" required class="short" title="전화번호 마지막 입력자리">
											</td>
										</tr>
										<tr class="line-duble">
											<?if($checkTodayDelivery == false){?>
												<th scope="row"><label for="recipient_post">주소</label></th>
												<td>
													<div>
														<input type='hidden' id='post5' name='post5' value='' >
														<input type="hidden" id="rpost1" name = 'rpost1'>
														<input type="hidden" id='rpost2' name = 'rpost2'>
														<input type="text" name = 'post' id = 'post' class="short" title="우편번호 첫번째 입력자리">
														<a href="javascript:openDaumPostcode();" class="btn-type1 ml-5">주소찾기</a>
													</div>
													<div class="mt-5">
														<input type="text" name = 'raddr1' id = 'raddr1' title="우편번호 선택에 의한 주소 자동입력 자리">
														<input type="text" name = 'raddr2' id = 'raddr2' title="자동입력주소 외 상세 주소 입력자리">
													</div>
												</td>
											<?}else{?>
												<!-- 당일배송이 상품이 존재하면 주소 강제 셋팅 후 수정 불가 -->
												<!-- $checkTodayDelivery $arrDeliveryTodayAddress -->
												<th scope="row"><label for="recipient_post">주소</label></th>
												<td>
													<div>
														<input type='hidden' id='post5' name='post5' value = '<?=$arrDeliveryTodayAddress['post']?>' readonly>
														<input type="hidden" id="rpost1" name = 'rpost1'>
														<input type="hidden" id='rpost2' name = 'rpost2'>
														<input type="text" name = 'post' id = 'post' value = '<?=$arrDeliveryTodayAddress['post']?>' class="short" title="우편번호 첫번째 입력자리" readonly>
													</div>
													<div class="mt-5">
														<input type="text" name = 'raddr1' id = 'raddr1' value = '<?=$arrDeliveryTodayAddress['address1']?>' title="우편번호 선택에 의한 주소 자동입력 자리" readonly>
														<input type="text" name = 'raddr2' id = 'raddr2' value = '<?=$arrDeliveryTodayAddress['address2']?>' title="자동입력주소 외 상세 주소 입력자리" readonly>
													</div>
												</td>
											<?}?>
										</tr>
										<tr class="line-duble">
											<th scope="row"><label for="prmsg_chg">배송 요청사항</label></th>
											<td>
												<div>
													<input type="hidden" name="msg_type" value="1">
													<select id='prmsg_chg' name='prmsg_chg'>
														<option value="" selected>직접입력</option>
														<option value="부재시 경비실에 맡겨 주세요">부재시 경비실에 맡겨 주세요</option>
														<option value="부재시 문앞에 놓아주세요">부재시 문앞에 놓아주세요</option>
														<option value="배송전에 연락주세요">배송전에 연락주세요</option>
														<option value="빠른배송 부탁드려요">빠른배송 부탁드려요</option>
														<option value="소화전에 넣어주세요">소화전에 넣어주세요</option>
														<option value="배관함에 넣어주세요">배관함에 넣어주세요</option>
													</select>
												</div>
												<div class="mt-5"><input type="text" name = 'order_prmsg' id="order_prmsg" style="width:503px" title="배송 요청사항 입력자리"></div>
											</td>
										</tr>
									</tbody>
								</table>
							</section><!-- //.pay-info03 -->
							<div class="title_wrap">									
								<p class="ment" style="padding-top:10px">
									<input type="checkbox" name="destinationt_type" value="Y">기본 배송지로 저장
								</p>
							</div>

						</div><!-- //.inner-info -->
						<div class="inner-pay">

							<section class="pay-info04">
								<div class="title_wrap"><h3>결제정보 입력</h3></div>
								<div class="type-wrap">
									<ul class="list CLS_paymentArea">
										<li class='hide'>
											<span>간편결제</span>
											<div>
											<?php if($escrow_info["onlycard"]!="Y") { ?>
												<label><input id="dev_payment7" name="dev_payment" type="radio" value="Y" class='dev_payment' onclick="sel_paymethod(this);">페이코</label>
											<?php } ?>
											</div>
										</li>
										<!-- 페이코 안내 -->
										<div class="pay-type-card hide" id="payco_notice">
											<p>
												PAYCO는 NHN엔터테인먼트가 만든 안전한 간편결제 서비스입니다.휴대폰과 카드 명의자가 동일해야 결제 가능하며, 결제금액 제한은 없습니다.
											</p>
										</div>
										<!-- // 페이코 안내 -->
										<li>
											<span>신용카드</span>
											<div>
											<?if(strstr("YC", $_data->payment_type) && ord($_data->card_id)) {?>
												<label><input id="dev_payment2" name="dev_payment" type="radio" value="C" class='dev_payment' onclick="sel_paymethod(this);" >신용카드</label>
											<?}?>
											</div>
										</li>
										<li>
											<span>현금결제</span>
											<div>
											<?if($escrow_info["onlycard"]!="Y" && !strstr($_SERVER["HTTP_USER_AGENT"],'Mobile') && !strstr($_SERVER[HTTP_USER_AGENT],"Android") && ord($_data->trans_id)){?>
												<label><input id="dev_payment3" name="dev_payment" type="radio" value="V" class='dev_payment' onclick="sel_paymethod(this);" >실시간 계좌이체</label>
											<?}?>

											<?if($escrow_info["onlycard"]!="Y" && ord($_data->virtual_id)){?>
												<label><input id="dev_payment4" name="dev_payment" type="radio" value="O" class='dev_payment' onclick="sel_paymethod(this);" >가상계좌</label>
											<?}?>

											<?if($escrow_info["onlycard"]!="Y" && strstr("YN", $_data->payment_type)) {?>
												<label style='display:none;'><input id="dev_payment1" name="dev_payment" type="radio" value="B" class='dev_payment' onclick="sel_paymethod(this);" >무통장 입금</label>
											<?}?>

											<?if(( $escrow_info["escrowcash"]=="A" || ($escrow_info["escrowcash"]=="Y" && (int)($sumprice+$deli_price)>=$escrow_info["escrow_limit"])) ){?>
											<?
												$pgid_info="";
												$pg_type="";
												$pgid_info=GetEscrowType($_data->escrow_id);
												$pg_type=trim($pgid_info["PG"]);
											?>
												<?if(strstr("ABCDG",$pg_type)){?>
													<label><input id="dev_payment5" name="dev_payment" type="radio" value="Q" class='dev_payment' onclick="sel_paymethod(this);" >에스크로(가상계좌)</label>
												<?}?>
											<?}?>
											</div>
										</li>
										<li class='hide'>
											<span>포인트</span>
											<div>
												<label><input id="dev_payment2" name="dev_payment" type="radio" value="G" class='dev_payment' onclick="sel_paymethod(this);" >임직원 포인트</label>
											</div>
										</li>
									</ul>

									<div class="pay-type-card hide" id="card_type">
										<table border=0 cellpadding=0 cellspacing=0 width="100%" summary="임금계좌를 선택">
											<colgroup>
												<?if($etcmessage[2]=="Y") {?><col width="20%" ><?}?>
												<col >
											</colgroup>
											<?if($etcmessage[2]=="Y") {?>
											<tr>
												<th scope="row">입금자명</th>
												<td>
													<input type="text" name="bank_sender" value="" >
												</td>
											</tr>
											<?}?>
											<tr>
												<th scope="row">입금계좌</th>
												<td>
													<select name="pay_data_sel" id="pay_data_sel" onchange="sel_account(this)" style="width:100%;">
														<option value='' >입금 계좌번호 선택 (반드시 주문자 성함으로 입금)</option>
														<?foreach($bank_payinfo as $k => $v){?>
														<option value="<?=$v?>" ><?=$v?></option>
														<?}?>
													</select>
												</td>
											</tr>
											<tr>
												<th></th>
												<td>* 반드시 주문자 성함으로 입금</td>
											</tr>
										</table>
									</div>


									<p class="pay-type-attention">
										실행되는 보안 플러그인에 카드정보를 입력해주세요.<br>
										결제는 암호화 처리를 통해 안전합니다.<br>
										결제 후 재고가 없거나 본인이 요청이 있을 경우 배송전<br>
										결제를 취소할 수 있습니다.
									</p>
								</div>
							</section><!-- //.pay-info04 -->

							<section class="pay-info05">
								<?$p_price=$sumprice+$sumpricevat;?>
								<input type="hidden" name="total_sum" id='total_sum' value="<?=$p_price?>">
								<input type="hidden" name="total_sumprice" id='total_sumprice' value="<?=$p_price?>">
								<input type='hidden' name='total_deli_price' id='total_deli_price' value="<?=$deli_price?>" >
								<input type='hidden' name='total_deli_price2' id='total_deli_price2' value="<?=$deli_price2?>" >
								<div class="title_wrap"><h3>결제금액</h3></div>
								<div class="total">
									<ul class="sum">
										<li>전체상품금액<span><em id="paper_goodsprice" ><?=number_format($sumprice)?></em>원</span></li>
										<li<?if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y" && $staff_order == 'N' ) {}else {?> class='hide'<?}?>>쿠폰 사용<span>(-) <em class="CLS_prCoupon">0</em>원</span></li>
										<li>배송비<span>(+) <em id='delivery_price'><?=number_format($deli_price)?></em>원</span></li>
										<li class='hide'>포인트 사용<span>(-) <em class="CLS_saleMil">0</em>점</span></li>
										<li class='hide'>장바구니 쿠폰<span>(-) <em class="CLS_bCoupon">0</em>원</span></li>
										<li class='hide'>배송비(후불)<span>(+) <em id='delivery_price2'><?=number_format($deli_price2)?></em>원</span></li>
										<li class="total-price">총 결제 금액<span><em id="price_sum"><?=number_format($sumprice+$deli_price)?></em>원</span></li>
										<li class="total-benefit">
											총 적립 포인트<span><em><?=number_format($sum_product_reserve)?></em>P</span>
										</li>
									</ul>
								</div>
								<div class="agree">
									<input type="checkbox" id="dev_agree">
									<label for="dev_agree">동의합니다.(전자상거래법 제 8조 제 2항)</label>
									<p>주문하실 상품,가격,배송정보,할인내역 등을<br>최종 확인하였으며,구매에 동의하시겠습니까?</p>
								</div>
								<div id="paybuttonlayer" name="paybuttonlayer">
									<a href="javascript:CheckForm()" onmouseover="window.status='결제';return true;" target="_self" class="btn-point">결제하기</a>
								</div>
								<div id="payinglayer" name="payinglayer" class='hide'>
									<img src="<?=$Dir?>img/common/paying_wait.gif" border=0>
								</div>
							</section><!-- //.pay-info05 -->

						</div><!-- //.inner-pay -->
					</div>

				</section>
			</article>
		</main>
	</div>
</div><!-- //#contents -->

<!-- 배송지 목록 팝업 -->
<div class="layer-dimm-wrap pop-address-list">
	<div class="dimm-bg"></div>
	<div class="layer-inner">
		<h3 class="layer-title"><span class="type_txt2">배송지</span> 목록</h3>
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content scroll">
			<ul class="my-address-list">
<?
foreach( $dn_info as $dn_vkey=>$dn_val ){
	//exdebug($dn_val);
?>
				<li>
					<div class="name">
						<strong><?=$dn_val->destination_name?></strong>
						<?if($dn_val->base_chk=='Y') {?><span class="btn-type1 c1">기본 배송지</span><?}?>
						<span class="tel"><?=addMobile($dn_val->mobile)?></span>
					</div>
					<div class="address">
						<p><?=$dn_val->addr1?></p>
						<p><?=$dn_val->addr2?></p>
						<button type='button' class="btn-type1" onClick="javascript:Dn_InReceiver('<?=$dn_val->no.'|@|'.$dn_val->destination_name.'|@|'.$dn_val->get_name.'|@|'.addMobile($dn_val->mobile).'|@|'.$dn_val->postcode.'|@|'.$dn_val->postcode_new.'|@|'.$dn_val->addr1.'|@|'.$dn_val->addr2?>')"><span>선택</span></button>
					</div>
				</li>
<?
}
?>
			</ul>
		</div>
	</div>
</div>
<!-- // 배송지 목록 팝업 -->
<?*/?>

	
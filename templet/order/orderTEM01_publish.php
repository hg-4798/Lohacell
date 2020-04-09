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

					<div class="title_type1 mt-30">
						<h3>주문상품 정보</h3>
					</div>

					<!-- 상품리스트 -->
					<div class="order_list_wrap">
						<table class="th_top">
							<caption>주문상품 정보</caption>
							<colgroup>
								<col style="width:auto">
								<col style="width:12%">
								<col style="width:12%">
								<col style="width:12%">
								<col style="width:12%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">상품정보</th>
									<th scope="col">옵션</th>
									<th scope="col">수량</th>
									<th scope="col">판매금액</th>
									<th scope="col">쿠폰</th>
								</tr>
							</thead>
							<tfoot>
								<tr class="bg">
									<td colspan="5">총 배송비 <em>2,500원</em></td>
								</tr>
							</tfoot>
							<tbody>
								<!-- 상품단위 시작 -->
								<tr class="bold" name="product_list">
									<td class="goods_info">
										<a href="../front/productdetail.php?productcode=001001001000000004">
											<img src="../data/shopimages/product/001001001000000004/001001001000000004_20160803121310_thum3_500X500.jpg" alt="MILANO BF">
											<ul>
												<li>[BIRKENSTOCK]</li>
												<li>MILANO BF</li>
											</ul>
										</a>
									</td>
									<td class="opt_text">
										<p>사이즈 : <em>250</em></p>
										<p>색상 : <em>블랙</em></p>
									</td>
									<td>1</td>
									<td class="payment">75,000원</td>
									<td>5%</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- // 상품 리스트 -->

					<!-- 상품총액 -->
					<div class="total_wrap mt-30">
						<div class="total_price clear">
							<span>총 결제금액</span>
							<ul class="clear">
								<li><div>상품 금액 합계 <em>870,000원</em></div></li>
								<li><div>할인 <em>0원</em></div></li>
								<li><div>배송비 <em>0원</em></div></li>
								<li><div><p>결제금액</p> <em>870,000원</em></div></li>
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
											<td><strong>2,512,000 원</strong></td>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th scope="row">총 결제금액</th>
											<td>
												<strong class="type_txt2">1,452,000원</strong>
												(총 할인금액 159,200원)
											</td>
										</tr>
									</tfoot>
									<tbody class="bordernone">
										<tr>
											<th scope="row"><label for="use_c">- 장바구니 쿠폰 내용</label></th>
											<td>
												<div><input type="text" id="use_c" title="장바구니 쿠폰 사용 금액"> 원</div>
												<button class="btn-type1 btn-coupon-list" type="button"><span>쿠폰사용</span></button>
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="use_s">- S-포인트 사용</label></th>
											<td>
												<div><input type="text" id="use_s" title="S포인트 사용 금액"> 원</div>
												<div><input type="checkbox" id="all_use_s"><label for="all_use_s">모두사용</label></div>
												(가용 포인트 <strong>10,000P</strong>)
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="use_h">- H-포인트 사용</label></th>
											<td>
												<div><input type="text" id="use_h" title="H포인트 사용 금액"> 원</div>
												<div><input type="checkbox" id="all_use_h"><label for="all_use_h">모두사용</label></div>
												(가용 포인트 <strong>10,000P</strong>)
											</td>
										</tr>
										<tr>
											<th scope="row">+ 배송비</th>
											<td>무료 (40,000원 이상 무료배송)</td>
										</tr>
									</tbody>
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
											<th scope="row"><label for="buyer_nm">주문자</label></th>
											<td><input type="text" id="buyer_nm" title="주문자 입력자리"></td>
										</tr>
										<tr>
											<th scope="row"><label for="buyer_email">이메일</label></th>
											<td><input type="text" id="buyer_email" title="이메일 입력자리"></td>
										</tr>
										<tr>
											<th scope="row"><label for="buyer_tel01">휴대전화</label></th>
											<td>
												<select id="buyer_tel01">
													<option selected="">010</option>
													<option>011</option>
													<option>016</option>
													<option>019</option>
												</select>
												<span class="dash">-</span>
												<input type="text" class="short" title="휴대전화번호 가운데 입력자리">
												<span class="dash">-</span>
												<input type="text" class="short" title="휴대전화번호 마지막 입력자리">
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="buyer_tel02">전화번호(선택)</label></th>
											<td>
												<select id="buyer_tel02">
													<option selected="">010</option>
													<option>011</option>
													<option>016</option>
													<option>019</option>
												</select>
												<span class="dash">-</span>
												<input type="text" class="short" title="전화번호 가운데 입력자리">
												<span class="dash">-</span>
												<input type="text" class="short" title="전화번호 마지막 입력자리">
											</td>
										</tr>
									</tbody>
								</table>
							</section><!-- //.pay-info02 -->

							<section class="pay-info03">
								<div class="title_wrap">
									<h3>배송지 정보</h3>
									<p class="ment" style="bottom:-5px">
										<input type="checkbox" id="same_address02"><label for="same_address02">주문고객과 동일한 주소 사용</label>
										<button class="btn-type1 btn-address-list"><span>배송지 목록</span></button>
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
											<th scope="row"><label for="recipient_nm">받는사람</label></th>
											<td><input type="text" id="recipient_nm" title="주문자 입력자리"></td>
										</tr>
										<tr>
											<th scope="row"><label for="recipient_tel01">휴대전화</label></th>
											<td>
												<select id="recipient_tel01">
													<option selected="">010</option>
													<option>011</option>
													<option>016</option>
													<option>019</option>
												</select>
												<span class="dash">-</span>
												<input type="text" class="short" title="휴대전화번호 가운데 입력자리">
												<span class="dash">-</span>
												<input type="text" class="short" title="휴대전화번호 마지막 입력자리">
											</td>
										</tr>
										<tr>
											<th scope="row"><label for="recipient_tel02">전화번호(선택)</label></th>
											<td>
												<select id="recipient_tel02">
													<option selected="">010</option>
													<option>011</option>
													<option>016</option>
													<option>019</option>
												</select>
												<span class="dash">-</span>
												<input type="text" class="short" title="전화번호 가운데 입력자리">
												<span class="dash">-</span>
												<input type="text" class="short" title="전화번호 마지막 입력자리">
											</td>
										</tr>
										<tr class="line-duble">
											<th scope="row"><label for="recipient_post">주소</label></th>
											<td>
												<div>
													<input type="text" class="short" title="우편번호 첫번째 입력자리" id="recipient_post">
													<span class="dash">-</span>
													<input type="text" class="short" title="우편번호 두번째 입력자리">
													<a href="#" target="_blank" class="btn-type1 ml-5">주소찾기</a>
												</div>
												<div class="mt-5">
													<input type="text" title="우편번호 선택에 의한 주소 자동입력 자리">
													<input type="text" title="자동입력주소 외 상세 주소 입력자리">
												</div>
											</td>
										</tr>
										<tr class="line-duble">
											<th scope="row"><label for="delivery_ment">배송 요청사항</label></th>
											<td>
												<div>
													<select id="delivery_ment">
														<option selected="">배송 요청사항 선택</option>
														<option>경비실에 맡겨주세요</option>
														<option>전화주세요</option>
														<option>직접입력</option>
													</select>
												</div>
												<div class="mt-5"><input type="text" style="width:503px" title="배송 요청사항 입력자리"></div>
											</td>
										</tr>
									</tbody>
								</table>
							</section><!-- //.pay-info03 -->

						</div><!-- //.inner-info -->
						<div class="inner-pay">

							<section class="pay-info04">
								<div class="title_wrap"><h3>결제정보 입력</h3></div>
								<div class="type-wrap">
									<ul class="list">
										<li>
											<span>간편결제</span>
											<div><label><input type="radio" name="pay_type">페이코</label></div>
										</li>
										<li>
											<span>신용카드</span>
											<div><label><input type="radio" name="pay_type">신용카드(일반)</label></div>
										</li>
										<li>
											<span>현금결제</span>
											<div>
												<label><input type="radio" name="pay_type">실시간 계좌이체</label>
												<label><input type="radio" name="pay_type">가상계좌</label>
												<label><input type="radio" name="pay_type">무통장 입금</label>
											</div>
										</li>
									</ul>
									<p class="txtarea">
										실행되는 보안 플러그인에 카드정보를 입력해주세요.<br>
										결제는 암호화 처리를 통해 안전합니다.<br>
										결제 후 재고가 없거나 본인이 요청이 있을 경우 배송전<br>
										결제를 취소할 수 있습니다.
									</p>
								</div>
							</section><!-- //.pay-info04 -->

							<section class="pay-info05">
								<div class="title_wrap"><h3>결제금액</h3></div>
								<div class="total">
									<ul class="sum">
										<li>전체상품금액<span>2,512,000원</span></li>
										<li>배송비<span>(+) 0원</span></li>
										<li>쿠폰 사용<span>(-) 0원</span></li>
										<li>S-포인트 사용<span>(-) 0점</span></li>
										<li>H-포인트 사용<span>(-) 0점</span></li>
										<li>장바구니 쿠폰<span>(-) 0원</span></li>
										<li class="total-price">총 결제 금액<span>2,512.000원</span></li>
										<li class="total-benefit">
											총 적립금
											<div>
												<label>S-포인트</label>
												<span>20,000P</span>
											</div>
											<div>
												<label>H-포인트</label>
												<span>2,000P</span>
											</div>
										</li>
									</ul>
								</div>
								<div class="agree">
									<input type="checkbox" id="agree_order">
									<label for="agree_order">동의합니다.(전자상거래법 제 8조 제 2항)</label>
									<p>주문하실 상품,가격,배송정보,할인내역 등을<br>최종 확인하였으며,구매에 동의하시겠습니까?</p>
								</div>
								<a href="#" class="btn-point">결제하기</a>
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
		<div class="layer-content">
			<ul class="my-address-list">
				<li>
					<div class="name">
						<strong>우리집</strong>
						<span class="btn-type1 c1">기본 배송지</span>
						<span class="tel">010-6231-4684</span>
					</div>
					<div class="address">
						<p>서울 은평구 불광1동 321-12번지 1층</p>
						<p>(도로명) 서울 은평구 통일로 713</p>
						<button class="btn-type1"><span>선택</span></button>
					</div>
				</li>
				<li>
					<div class="name">
						<strong>우리집</strong>
						<span class="tel">010-6231-4684</span>
					</div>
					<div class="address">
						<p>서울 은평구 불광1동 321-12번지 1층</p>
						<p>(도로명) 서울 은평구 통일로 713</p>
						<button class="btn-type1"><span>선택</span></button>
					</div>
				</li>
				<li>
					<div class="name">
						<strong>우리집</strong>
						<span class="tel">010-6231-4684</span>
					</div>
					<div class="address">
						<p>서울 은평구 불광1동 321-12번지 1층</p>
						<p>(도로명) 서울 은평구 통일로 713</p>
						<button class="btn-type1"><span>선택</span></button>
					</div>
				</li>
				<li>
					<div class="name">
						<strong>우리집</strong>
						<span class="tel">010-6231-4684</span>
					</div>
					<div class="address">
						<p>서울 은평구 불광1동 321-12번지 1층</p>
						<p>(도로명) 서울 은평구 통일로 713</p>
						<button class="btn-type1"><span>선택</span></button>
					</div>
				</li>
			</ul>
			<div class="list-paginate mt-20">
			<span class="border_wrap">
				<a href="javascript:;" class="prev-all"></a>
				<a href="javascript:;" class="prev"></a>
			</span>
			<a class="on">1</a>
			<span class="border_wrap">
				<a href="javascript:;" class="next"></a>
				<a href="javascript:;" class="next-all"></a>
			</span>
			</div>
		</div>
	</div>
</div>
<!-- // 배송지 목록 팝업 -->

<!-- 쿠폰선택 팝업 -->
<div class="layer-dimm-wrap pop-coupon-list">
	<div class="dimm-bg"></div>
	<div class="layer-inner">
		<h3 class="layer-title"><span class="type_txt2">쿠폰</span> 선택</h3>
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content">
			<!-- 쿠폰 리스트  -->
			<div class="goods_info">
				<a href="#">
					<img src="../data/shopimages/product/001001001000000004/001001001000000004_20160803121310_thum3_500X500.jpg" alt="상품이미지">
					<ul>
						<li>[BIRKENSTOCK]</li>
						<li>MILANO BF</li>
					</ul>
				</a>
				<p>
					<input id="all_coupon" type="radio" name="">
					<label for="all_coupon">전체 상품 금액 할인 쿠폰</label>
				</p>
				<p>
					<input id="no_coupon" type="radio" name="">
					<label for="no_coupon">선택안함</label>
				</p>
				<div class="total_price">
					할인금액 <em>10,000원</em>
				</div>
			</div>
			<!-- // 쿠폰 리스트  -->

			<!-- 쿠폰 리스트  -->
			<div class="goods_info">
				<a href="#">
					<img src="../data/shopimages/product/001001001000000004/001001001000000004_20160803121310_thum3_500X500.jpg" alt="상품이미지">
					<ul>
						<li>[BIRKENSTOCK]</li>
						<li>MILANO BF</li>
					</ul>
				</a>
				<p>
					<input id="all_coupon2" type="radio" name="">
					<label for="all_coupon2">전체 상품 금액 할인 쿠폰</label>
				</p>
				<p>
					<input id="no_coupon2" type="radio" name="">
					<label for="no_coupon2">선택안함</label>
				</p>
				<div class="total_price">
					할인금액 <em>10,000원</em>
				</div>
			</div>
			<!-- // 쿠폰 리스트  -->
			<div class="btn_wrap"><a href="#" class="btn-type1 c1">확인</a></div>
		</div>
	</div>
</div>
<!-- // 쿠폰선택 팝업 -->

<!-- @@@@ 20160811 이전 소스 백업 @@@@ -->
<!-- 메인 컨텐츠 -->

<div class="hide">

	<!-- 리뷰작성팝업 -->
	<div class="layer-dimm-wrap coupon-use-layer">
		<div class="dimm-bg"></div>
		<div class="layer-inner order-coupon-list"> <!-- layer-class 부분은 width,height, - margin 값으로 구성되며 클래스명은 자유 -->
			<h3 class="layer-title"></h3>
			<button type="button" class="btn-close">창 닫기 버튼</button>
			<div class="layer-content js-scroll">
				<p class="title">상품 쿠폰 선택</p>
<?php
$couponIndex = 0; // radiobox 고유번호
$productIndex = 0; // 상품별 radiobox 고유번호
foreach( $venderArr as $vender=>$vederObj ){
	foreach( $vederObj as $product ) {
		$tmp_opt_price = 0;
?>
				<div class="goods-coupon">
					<div class="inner">
						<p class="pic">

							<img src='<?=getProductImage( $productImgPath, $product['tinyimage'] )?>' >
						</p>
						<p class="info">
							<span><?=get_vender_name( $vender )?></span>
							<span><?=$product['productname']?></span>
<?php
		if( count( $product['option'] ) > 0 ){
			if( $product['option_type'] == 1 ){ // 독립형 옵션
				$tmp_opt_subject = explode( '@#', $product['option_subject'] );
				foreach( $product['option'] as $optKey=>$optVal ){
					$tmp_opt_content = explode( chr(30), $optVal['option_code'] );
					echo $tmp_opt_subject[$optKey].' : '.$tmp_opt_content[1].'<br>';
					$tmp_opt_price += $optVal['option_price'] * $product['option_quantity'];
				} // option foreach
			} else { // 조합형 옵션
				$tmp_option = $product['option'][0];
				$tmp_opt_subject = explode( '@#', $product['option_subject'] );
				$tmp_opt_contetnt = explode( chr(30), $tmp_option['option_code'] );
				foreach( $tmp_opt_subject as $optKey=>$optVal ){
					echo $optVal.' : '.$tmp_opt_contetnt[$optKey].'<br>';
				}
				$tmp_opt_price += $tmp_option['option_price'] * $product['option_quantity'];

			} // option_type else
		} // count option if

		if( $product['text_opt_content'] ){ // 추가문구 옵션
			$tmp_text_subejct = explode( '@#', $product['text_opt_subject'] );
			$text_opt_content = explode( '@#', $product['text_opt_content'] );
			foreach( $text_opt_content as $textKey=>$textVal ){
				if( $textVal != '' ) {
					echo $tmp_text_subejct[$textKey].' : '.$textVal.'<br>';
				}
			}
		}
		$prPrice = ( $product['price'] * $product['option_quantity'] ) + $tmp_opt_price;
?>
						</p>

					</div>
<!--
						<li>
							<input type="radio" class="radio-def" name="coupon_select[<?=$productIndex?>]" id='no_<?=$couponIndex?>' value='<?=$prcouponVal->ci_no?>' data-bridx='<?=$product['basketidx']?>' idx='<?=$productIndex?>' data-sellprice='<?=$prPrice?>' >
							<label for="no_<?=$couponIndex?>"><?=$prcouponVal->coupon_name?></label>
						</li>

-->
					<!-- type="checkbox" class="checkbox-def" -->
					<ul class="coupon-choice2" data-productcode='<?=$product['productcode']?>' >
<?php
		foreach( $product_coupon as $prcouponKey=>$prcouponVal ){
			if( $_CouponInfo->check_coupon_product( $product['productcode'], 2, $prcouponVal ) ){
                // 사용조건 체크
                if( $prcouponVal->mini_quantity == 0 || ( $prcouponVal->mini_type == 'P' && $prcouponVal->mini_quantity <= $total_row->total_price_sum )
                    || ( $prcouponVal->mini_type == 'Q' && $prcouponVal->mini_quantity <= $total_row->total_qty )
                ){
?>

						<li>
							<input type="radio" class="radio-def" id='no_<?=$couponIndex?>' name='coupon_select[<?=$productIndex?>]'
								value='<?=$prcouponVal->ci_no?>' data-sellprice='<?=$prPrice?>' data-bridx='<?=$product['basketidx']?>'
								idx='<?=$productIndex?>' >
							<label for="no_<?=$couponIndex?>" ><?=$prcouponVal->coupon_name?></label>
						</li>
<?php
				$couponIndex++;
                }
			} // coupon_chk if
		} // product_coupon foreach
?>
						<li>
							<input type="radio" class="radio-def" name="coupon_select[<?=$productIndex?>]" id='coupon-a<?=$productIndex?>'
							value='' data-prdouct='' checked idx='<?=$productIndex?>' data-bridx='' data-sellprice='0' >
							<label for="coupon-a<?=$productIndex?>">선택안함</label>
						</li>
					</ul>
                    <p class="coupon-price" id="coupon_price_<?=$productIndex?>" name='NM_coupon_price' >
							할인금액 0
					</p>
				</div>
<?php
	$productIndex++;
	} // $vednerObj foreach
}  // venderArr foreach
?>
				<div class="btn-place mb-20"><button class="btn-dib-function" onclick='javascript:set_prcoupon();' ><span>OK</span></button></div>
			</div><!-- //.layer-content -->
		</div>
	</div><!-- //리뷰작성팝업 -->

	<div class="containerBody sub-page">

		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li class="on"><a><? if($staff_order == 'Y') { echo '임직원 '; } ?>주문결제</a></li>
			</ul>
		</div>

		<div class="cart-wrap">
			<div class="cart-my-benefit">
				<div class="inner-flow">
                	<ul>
                    	<li><span>01</span> 쇼핑백</li>
                        <li class="on"><span>02</span> <? if($staff_order == 'Y') { echo '임직원 '; } ?>주문결제</li>
                        <li><span>03</span> 주문완료</li>
                    </ul>
                </div>
				<div class="inner-benefit ">
					<p><strong><?=$_ShopInfo->memname?></strong>님의 혜택정보</p>
					<p>마일리지 <strong><?=number_format($mem_reserve->reserve)?>M</strong> l 할인쿠폰 <strong><?=number_format($coupon_cnt)?>장</strong></p>
					<p class="hide">비회원 구매시 할인/쿠폰과 이벤트 등의<br>혜택을 받으실 수 없습니다.</p>
				</div>
				<div class="inner-benefit hide"><!-- 비회원일 경우 출력 -->
					<p class="no-member">비회원 구매시 할인/쿠폰과 이벤트 <br>등의 혜택을 받으실 수 없습니다.</p>
				</div>
			</div>

			<!-- 주문 상품 -->
			<h4 class="table-title">주문상품 정보</h4>
				<!-- 벤더별 -->
<?php
$sumprice = 0;
$deli_price = 0; // 선불 배송료
$deli_price2 = 0; //착불 배송료
foreach( $venderArr as $vender=>$vederObj ){
	$vender_price = 0;
	$product_reserve = 0;
	$product_price = 0;
	$vender_name = get_vender_name( $vender );
?>
			<table class="th-top util order-tb-margin" summary="담은 상품의 정보, 판매가, 수량, 할인금액, 결제 예정가, 적립금을 확인할 수 있습니다.">
				<caption>01. 주문 상품</caption>
				<colgroup>
					<col style="width:100px" >
					<col style="width:auto" >
					<col style="width:130px" >
					<col style="width:130px" >
					<!-- <col style="width:110px" > -->
					<col style="width:110px" >
					<col style="width:105px" >
				</colgroup>

				<thead>
					<tr>
						<th scope="col" colspan="2"><strong>업체 배송 상품 [<?=$vender_name?>]</strong></th>
						<th scope="col">수량</th>
						<th scope="col">옵션</th>
						<!-- <th scope="col">추가문구 옵션</th> -->
						<th scope="col">적립 마일리지</th>
						<th scope="col">상품금액</th>
					</tr>
				</thead>
<!-- 상품단위 시작 -->
<?php
	foreach( $vederObj as $product ) {
		$opt_price = 0; // 상품별 옶션가
		$pr_reserve = 0; //상품별 마일리지
        $tmp_opt_price = 0;
?>
				<tbody>
					<tr>
						<td class="order-item-tr">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>">
								<img class="img-size-mypage" src="<?=getProductImage( $productImgPath, $product['tinyimage'] )?>" >
							</a>
						</td>
						<td class="ta-l">
							<span class="name">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>">
								<?=$product['productname']?>
							</a>
							<br>
<?php
		if( count( $product['option'] ) > 0 || strlen( $product['text_opt_subject'] ) > 0 ){
			echo "<div>";
			if( count( $product['option'] ) > 0 ){
				$tmp_opt_subject = explode( '@#', $product['option_subject'] );
				if( $product['option_type'] == 0 ){ // 조합형 옵션
					$tmp_option = $product['option'][0];
					$tmp_opt_contetnt = explode( chr(30), $tmp_option['option_code'] );
					foreach( $tmp_opt_subject as $optKey=>$optVal ){
						echo $optVal.' : '.$tmp_opt_contetnt[$optKey].'<br>';
                        $tmp_opt_price += $optVal['option_price'] * $product['option_quantity'];
					}// option foreach
				}
				if( $product['option_type'] == 1 ){ // 독립형 옵션
					foreach( $product['option'] as $optKey=>$optVal ){
						$tmp_opt_content = explode( chr(30), $optVal['option_code'] );
						echo $tmp_opt_subject[$optKey].' : '.$tmp_opt_content[1].'<br>';
						$tmp_opt_price += $optVal['option_price'] * $product['option_quantity'];
					}// option foreach
				}
			} // count option

			if( $product['text_opt_content'] ){ // 추가문구 옵션
				$tmp_text_subejct = explode( '@#', $product['text_opt_subject'] );
				$text_opt_content = explode( '@#', $product['text_opt_content'] );
				foreach( $text_opt_content as $textKey=>$textVal ){
					if( $textVal != '' ) {
						echo $tmp_text_subejct[$textKey].' : '.$textVal.'<br>';
					}
				}
			}  // text_opt_content if
            if( $tmp_opt_price > 0 ){
    			echo '추가금액 : '.number_format( $tmp_opt_price );
            }
			echo "</div><br>";
		}  // count option || text_opt_subject if
?>
								<?=$optText?>
							</span>
						</td>
						<td><?=$product['quantity']?></td>
						<td>
<?php
            if( count( $product['option'] ) > 0 || strlen( $product['text_opt_content'] ) > 0 ){
                if( count( $product['option'] ) > 0 ){
                    if( $product['option_type'] == 1 ){ // 독립형 옵션
                        $tmp_opt_subject = explode( '@#', $product['option_subject'] );
                        foreach( $product['option'] as $optKey=>$optVal ){
                            $tmp_opt_content = explode( chr(30), $optVal['option_code'] );
                            echo $tmp_opt_subject[$optKey].' : '.$tmp_opt_content[1].'<br>';
                            $opt_price += $optVal['option_price'] * $product['option_quantity'];
                        } // option foreach
                    } else { // 조합형 옵션
                        $tmp_option = $product['option'][0];
                        $tmp_opt_subject = explode( '@#', $product['option_subject'] );
                        $tmp_opt_contetnt = explode( chr(30), $tmp_option['option_code'] );
                        foreach( $tmp_opt_subject as $optKey=>$optVal ){
                            echo $optVal.' : '.$tmp_opt_contetnt[$optKey].'<br>';
                        }
                        $opt_price += $tmp_option['option_price'] * $product['option_quantity'];

                    } // option_type else
                } // count option if
?>
<?php
                if( $product['text_opt_content'] ){ // 추가문구 옵션
                    $tmp_text_subejct = explode( '@#', $product['text_opt_subject'] );
                    $text_opt_content = explode( '@#', $product['text_opt_content'] );
                    foreach( $text_opt_content as $textKey=>$textVal ){
                        if( $textVal != '' ) {
                            echo $tmp_text_subejct[$textKey].' : '.$textVal.'<br>';
                        }
                    }
                }
            } else {
                echo "-";
            }
			$pr_reserve = getReserveConversion( $product['reserve'], $product['reservetype'], ( $product['price'] * $product['quantity'] ) + $opt_price , "N" );
			$product_reserve += $pr_reserve; // 벤더별 상품 예상 적립금
			$product_price = ( $product['price']  * $product['quantity'] ) + $opt_price; //옵션가와 상품가를 합산해준다
			$vender_price += $product_price; // 벤더별 상품가격

			if( strlen( $_ShopInfo->getMemid() ) == 0 ){ // 로그인을 안했을 경우
				$pr_reserve	= 0;
			}
?>
						</td>
						<!-- <td>

						</td> -->
						<!-- <td><?=number_format($bVal['sellprice'])?></td> -->
						<td class="point"><? if( $staff_order == 'N' ) { echo number_format( $pr_reserve ); } else { echo '0'; } ?> M</td>
						<td><strong><?=number_format($product_price)?></strong></td>
					</tr>
				</tbody>
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
				<tfoot>
<?php
	$vender_deli_price = 0;
	if( $vender_info[$vender] ){
?>
					<tr>
						<td colspan="7" bgcolor="#fafafa">
							합계 <?=number_format( $vender_price )?>
							<span>+</span>

							배송비
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
							<?=number_format( $vender_deli_price )?>
                            <input type='hidden' name='select_price[<?=$vender?>]' value='<?=$vender_deli_price?>' data-vender='<?=$vender?>' >
							<span>=</span>
							주문 금액 <strong><?=number_format( $vender_price + $vender_deli_price )?></strong>
<?php
    if( $vender_info[$vender]['deli_price_min'] != 0 ){
?>
							<span class="delivery-ment">[<?=$vender_name?>] 제품으로만 <?=number_format( $vender_info[$vender]['deli_price_min'] )?>원 이상 구매시 무료배송됩니다.</span>
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

			<!-- //벤더별 -->
<?php
	if( $vender_info[$vender]['deli_select'] == '0' || $vender_info[$vender]['deli_select'] == '2' ) $deli_price += $vender_deli_price;
    if( $vender_info[$vender]['deli_select'] == '1' ) $deli_price2 += $vender_deli_price;
	$sumprice += $vender_price;
} // foreach
?>
			<!-- // 주문 상품 -->
			<div class="order-float-cover">
				<!-- 고객정보 -->
				<div class="orderer_area">
					<h4 class="table-title">주문고객 정보</h4>
					<table class="th-left-util" summary="주문자명, 주소, 휴대폰 번호, 이메일을 작성할 수 있습니다.">
						<caption>02. 고객정보</caption>
						<colgroup>
							<col style="width:106px" >
							<col style="width:auto" >
						</colgroup>
						<tbody>
							<tr>
								<th scope="row">주문자</th>
								<td class="name">
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
									<div class="email-cover">
										<input type="text" id="user-email" name='sender_email' class="input-def w240" title="이메일 입력자리" value='<?=$email?>' onkeyup="domail_list_up(this.value)" >
										<ul class="domain-list">
											<li><a href="javascript:;" onClick="email_in(this)" alt='@naver.com'>@naver.com</a></li>
											<li><a href="javascript:;" onClick="email_in(this)" alt='@gmail.com'>@gmail.com</a></li>
											<li><a href="javascript:;" onClick="email_in(this)" alt='@nate.com'>@nate.com</a></li>
											<li><a href="javascript:;" onClick="email_in(this)" alt='@daum.net'>@daum.net</a></li>
											<li><a href="javascript:;" onClick="email_in(this)" alt='@yahoo.com'>@yahoo.com</a></li>
										</ul>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="user-phone">휴대전화</label></th>
								<td>
									<div class="select w80 small" style="z-index:250">
										<input type='hidden' name="sender_tel1" id='sender_tel1' value="<?=$mobile[0] ?>" >
										<span class="ctrl"><span class="arrow"></span></span>
										<button type="button" class="my_value" id='senderPhon' ><span>010</span></button>
										<ul class="a_list">
											<li><a href="#1"><span>010</span></a></li>
											<li><a href="#2"><span>011</span></a></li>
											<li><a href="#3"><span>016</span></a></li>
											<li><a href="#4"><span>017</span></a></li>
											<li><a href="#5"><span>018</span></a></li>
											<li><a href="#6"><span>019</span></a></li>
										</ul>
									</div>
									<span class="txt-lh">-</span>
									<input type="text" id="user-phone" name="sender_tel2" value="<?=$mobile[1] ?>" maxlength='4' class="input-def w80" title="휴대전화 가운데 입력자리">
									<span class="txt-lh">-</span>
									<input type="text" name="sender_tel3" value="<?=$mobile[2] ?>" maxlength='4' class="input-def w80" title="휴대전화 마지막 입력자리">
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="user-tel">전화번호(선택)</label></th>
								<td>
									<div class="select w80 small">
										<input type='hidden' name="home_tel1" id='home_tel1' value="" >
										<span class="ctrl"><span class="arrow"></span></span>
										<button type="button" class="my_value" id='senderTel'><span>02</span></button>
										<ul class="a_list">
											<li><a href="#1"><span>02</span></a></li>
											<li><a href="#2"><span>031</span></a></li>
											<li><a href="#3"><span>032</span></a></li>
											<li><a href="#4"><span>033</span></a></li>
											<li><a href="#5"><span>041</span></a></li>
											<li><a href="#6"><span>042</span></a></li>
											<li><a href="#7"><span>043</span></a></li>
											<li><a href="#8"><span>044</span></a></li>
											<li><a href="#9"><span>051</span></a></li>
											<li><a href="#10"><span>052</span></a></li>
											<li><a href="#11"><span>053</span></a></li>
											<li><a href="#12"><span>054</span></a></li>
											<li><a href="#13"><span>055</span></a></li>
											<li><a href="#14"><span>061</span></a></li>
											<li><a href="#15"><span>062</span></a></li>
											<li><a href="#16"><span>063</span></a></li>
											<li><a href="#17"><span>064</span></a></li>
										</ul>
									</div>
									<span class="txt-lh">-</span>
									<input type="text" id="user-tel" name="home_tel2" maxlength='4' class="input-def w80" title="집전화 가운데 입력자리">
									<span class="txt-lh">-</span>
									<input type="text" name="home_tel3" id='home_tel3' maxlength='4' class="input-def w80" title="집전화 마지막 입력자리">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- // 고객정보 -->

				<!-- 배송지정보 -->
				<div class="address_area">
					<h4 class="table-title">배송지 정보 <span class="same_box"><input type='checkbox' name="same" value="Y" onclick="SameCheck(this.checked)" id="dev_orderer" class="checkbox-def"><label for="dev_orderer">주문고객과 동일한 주소 사용</label></span></h4>
					<table class="th-left-util" summary="수령자명, 주소, 전화번호, 휴대폰번호, 이메일, 배송 메시지를 작성할 수 있습니다.">
						<caption>03. 배송지 정보 </caption>
						<colgroup>
							<col style="width:106px" >
							<col style="width:auto" >
						</colgroup>
						<tbody>
							<tr>
								<th scope="row">받는 사람</th>
								<td class="name">
									<input class="input-def" type="text" name = 'receiver_name' required msgR="주문하시는 분 이름을 입력하세요." >
									<!-- <a href="javascript:addrchoice();" target="_self"><img src="../img/button/cart_order_address_list_btn.gif" alt="배송지 목록" ></a> -->
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="user-phone">휴대전화</label></th>
								<td>
									<div class="select w80 small">
										<input  type="hidden" name="receiver_tel21" id="receiver_tel21"  >
										<span class="ctrl"><span class="arrow"></span></span>
										<button type="button" class="my_value" id='receiverPhon' ><span>010</span></button>
										<ul class="a_list">
											<li><a href="#1"><span>010</span></a></li>
											<li><a href="#2"><span>011</span></a></li>
											<li><a href="#3"><span>016</span></a></li>
											<li><a href="#4"><span>017</span></a></li>
											<li><a href="#5"><span>018</span></a></li>
											<li><a href="#6"><span>019</span></a></li>
										</ul>
									</div>
									<span class="txt-lh">-</span>
									<input type="text" id="user-phone" name="receiver_tel22" class="input-def w80" maxlength='4' onKeyUp="strnumkeyup(this)" required title="휴대전화 가운데 입력자리">
									<span class="txt-lh">-</span>
									<input type="text" name="receiver_tel23" class="input-def w80" maxlength='4' onKeyUp="strnumkeyup(this)" required title="휴대전화 마지막 입력자리">
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="user-tel">전화번호(선택)</label></th>
								<td>
									<div class="select w80 small">
										<input type="hidden" name="receiver_tel11" id='receiver_tel11'  >
										<span class="ctrl"><span class="arrow"></span></span>
										<button type="button" class="my_value" id='receiverTel'><span>02</span></button>
										<ul class="a_list">
											<li><a href="#1"><span>02</span></a></li>
											<li><a href="#2"><span>031</span></a></li>
											<li><a href="#3"><span>032</span></a></li>
											<li><a href="#4"><span>033</span></a></li>
											<li><a href="#5"><span>041</span></a></li>
											<li><a href="#6"><span>042</span></a></li>
											<li><a href="#7"><span>043</span></a></li>
											<li><a href="#8"><span>044</span></a></li>
											<li><a href="#9"><span>051</span></a></li>
											<li><a href="#10"><span>052</span></a></li>
											<li><a href="#11"><span>053</span></a></li>
											<li><a href="#12"><span>054</span></a></li>
											<li><a href="#13"><span>055</span></a></li>
											<li><a href="#14"><span>061</span></a></li>
											<li><a href="#15"><span>062</span></a></li>
											<li><a href="#16"><span>063</span></a></li>
											<li><a href="#17"><span>064</span></a></li>
										</ul>
									</div>
									<span class="txt-lh">-</span>
									<input type="text" id="user-tel" class="input-def w80" name="receiver_tel12" maxlength='4' onKeyUp="strnumkeyup(this)" required title="집전화 가운데 입력자리">
									<span class="txt-lh">-</span>
									<input type="text" class="input-def w80" name="receiver_tel13" maxlength='4' onKeyUp="strnumkeyup(this)" required title="집전화 마지막 입력자리">
								</td>
							</tr>
							<tr class="line-duble">
								<th scope="row" valign="top"><label for="post-code">주소</label></th>
								<td>
									<ul>
										<li>
											<input type='hidden' id='post5' name='post5' value='' >
											<input type="hidden" id="post-code" name = 'rpost1' class="input-def w80" title="우편번호 앞 입력자리">
											<!-- <span class="txt-lh">-</span> -->
											<input type="hidden" class="input-def w80" name = 'rpost2' id = 'rpost2' title="우편번호 뒤 입력자리">
											<input type="text" class="input-def w80" name = 'post' id = 'post' title="우편번호 통합">
											<a href="javascript:openDaumPostcode();" class="btn-dib-line ">우편번호</a>
										</li>
										<li>
											<input type="text" class="input-def w300" name = 'raddr1' id = 'raddr1' title="기본 주소 입력자리">
											<input type="text" class="input-def w250" name = 'raddr2' id = 'raddr2' title="상세 주소 입력자리">
										</li>
									</ul>
								</td>
							</tr>
							<tr class="line-duble">
								<th scope="row" valign="top"><label for="delivery-needs">배송 요청사항</label></th>
								<td>
									<ul>
										<li>
											<div class="select w300 small">
											<input type="hidden" name="msg_type" value="1">
												<span class="ctrl"><span class="arrow"></span></span>
												<button type="button" class="my_value" ><span>직접입력</span></button>
												<ul class="a_list" id='prmsg_chg' >
                                                    <li><a href="javascript:;">직접입력</a></li>
													<li><a href="javascript:;">부재시 경비실에 맡겨 주세요</a></li>
                                                    <li><a href="javascript:;">부재시 문앞에 놓아주세요</a></li>
                                                    <li><a href="javascript:;">배송전에 연락주세요</a></li>
                                                    <li><a href="javascript:;">빠른배송 부탁드려요</a></li>
                                                    <li><a href="javascript:;">소화전에 넣어주세요</a></li>
                                                    <li><a href="javascript:;">배관함에 넣어주세요</a></li>
												</ul>
											</div>
										</li>
										<li>
											<input type="text" class="input-def w300" name = 'order_prmsg' id="delivery-needs" title="배송요청 입력자리">
										</li>
									</ul>
								</td>
							</tr>

							<? if( false ){ ?>
							<tr>
								<th>배송비 결제</th>
								<td>
									<input type=radio name=deli_type class="deli_type" value="0" checked > 선불
									<input type=radio name=deli_type class="deli_type" id="deli_type1" value="1" > 착불
								</td>
							</tr>
							<? }else{ ?>
                                <!-- 배송비는 벤더별 기준이기에 0으로 고정 -->
								<input type="hidden" id='deli_type' name=deli_type value="0" >
							<? } ?>

						</tbody>
					</table>
				</div>
				<!-- // 배송지정보 -->

				<!-- 할인정보 -->
<?php
if ((strlen($_ShopInfo->getMemid())>0 && $_data->reserve_maxuse>=0 && $user_reserve!=0) || (strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y")) {
?>

				<div class="dc_area" style=''>
					<h4 class="table-title">할인정보</h4>
					<table class="th-left-util" summary="할인받을 쿠폰 및 적립금을 입력할 수 있습니다.">
						<caption>04. 할인정보</caption>
						<colgroup>
							<col style="width:106px" >
							<col style="width:auto" >
						</colgroup>
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
							<th scope="row">상품쿠폰</th>
							<td><button class="btn-dib-line coupon-use btn-coupon-list" type="button"><span>쿠폰사용</span></button></td>
						</tr>
						<tr>
							<th scope="row">할인쿠폰</th>
							<td>
								<div class="select w300 small">
									<span class="ctrl"><span class="arrow"></span></span>
									<button type="button" class="my_value CLS_coupon_value"><span>쿠폰선택</span></button>

									<ul class="a_list">
<?php
		foreach( $basket_coupon as $bcouponKey=>$bcouponVal ){
                // 사용조건 체크
                if( $bcouponVal->mini_quantity == 0 || ( $bcouponVal->mini_type == 'P' && $bcouponVal->mini_quantity <= $total_row->total_price_sum )
                    || ( $bcouponVal->mini_type == 'Q' && $bcouponVal->mini_quantity <= $total_row->total_qty )
                ){
?>
										<li><a href="javascript:set_basket_coupon('<?=$bcouponVal->ci_no?>');"><?=$bcouponVal->coupon_name?></a></li>
<?php
                }
		} // $basket_coupon foreach
?>
									</ul>

								</div>
								<div id = "ID_coupon_code_layer">
                                    <div id = "ID_prd_coupon_layer" ></div>
                                    <div id = "ID_bk_coupon_layer" ></div>
                                    <div id = "ID_deli_coupon_layer" ></div>
                                </div>
							</td>
						</tr>
<?php
        if( count( $deliver_coupon ) > 0 ){
?>
                        <tr>
                            <th scope='row'>배송비 무료 쿠폰</th>
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
						<tr>
							<th scope="row"><label for="mileage-use"><? if($staff_order == 'Y') { echo '임직원 '; } ?>마일리지</label></th>
							<td>
								<input type="hidden" name="okreserve" id='okreserve' value="<?=$user_reserve?>">
<?php
		if( $_data->reserve_maxprice > $sumprice ) {
?>
								<span>구매금액이 <?=number_format( $_data->reserve_maxprice )?>원 이상이면 사용가능합니다.</span>
								<input type="hidden" name="usereserve" id="mileage-use" value='0'>
<?php
		}else if( $user_reserve >= $_data->reserve_maxuse ){
?>
								<input type="text" name="usereserve" id="mileage-use" class="input-def" title="마일리지 금액 자리" value='0'>
								<span>(보유 <? if($staff_order == 'Y') { echo '임직원 '; } ?> 마일리지 : <?=number_format( $user_reserve )?>M)</span>
<?php
		}else{
?>
								<span>
									<?=number_format($_data->reserve_maxuse)?>원 이상이면 사용가능합니다.
									(보유 마일리지 : <?=number_format( $user_reserve )?>M)
								</span>
								<input type="hidden" name="usereserve" id="mileage-use" value='0'>
<?php
		}
?>

								<!-- <input type="text" name="usereserve" id="mileage-use" class="input-def" title="마일리지 금액 자리">
								<span>(보유 마일리지 : 350M)</span> -->
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
					</table>
				</div>
<?php
}
?>
				<!-- // 할인정보 -->

				<!-- 결제수단 -->
				<div class="means_area">
					<h4 class="table-title">결제하기</h4>
					<ul class="means CLS_paymentArea pay-type">

						<?if(strstr("YC", $_data->payment_type) && ord($_data->card_id)) {?>
							<li><input id="dev_payment2" class="dev_payment radio-def" name="dev_payment" type="radio" value="C" onclick="sel_paymethod(this);" > <label for="dev_payment2">신용카드</label></li>
						<?}?>

						<?if($escrow_info["onlycard"]!="Y" && !strstr($_SERVER["HTTP_USER_AGENT"],'Mobile') && !strstr($_SERVER[HTTP_USER_AGENT],"Android") && ord($_data->trans_id)){?>
							<li><input id="dev_payment3" class="dev_payment radio-def" name="dev_payment" type="radio" value="V" onclick="sel_paymethod(this);" > <label for="dev_payment3">계좌이체</label></li>
						<?}?>

						<?if($escrow_info["onlycard"]!="Y" && ord($_data->virtual_id)){?>
							<li><input id="dev_payment4" class="dev_payment radio-def" name="dev_payment" type="radio" value="O" onclick="sel_paymethod(this);" > <label for="dev_payment4">가상계좌</label></li>
						<?}?>

						<?if($escrow_info["onlycard"]!="Y" && strstr("YN", $_data->payment_type)) {?>
							<li><input id="dev_payment1" class="dev_payment radio-def" name="dev_payment" type="radio" value="B" onclick="sel_paymethod(this);" > <label for="dev_payment1">무통장입금</label></li>
						<?}?>

                        <?//if(ord($_data->mobile_id)){?>
                            <!--li><input id="dev_payment6" class="dev_payment" name="dev_payment" type="radio" value="M" onclick="sel_paymethod(this);" disabled > <label for="dev_payment6">휴대폰</label></li-->
                        <?//}?>

                        <?php if(strlen($_ShopInfo->getMemid())>0 && ( $_ShopInfo->getMemid() == "moondding23" || true ) ) { ?>
                        <li><input id="dev_payment6" class="dev_payment radio-def" name="dev_payment" type="radio" value="M" onclick="sel_paymethod(this);"> <label for="dev_payment6">휴대폰</label></li>
                        <li><input id="dev_payment7" class="dev_payment radio-def" name="dev_payment" type="radio" value="Y" onclick="sel_paymethod(this);"> <label for="dev_payment7">PAYCO</label></li>
                        <?php } ?>

						<?if(( $escrow_info["escrowcash"]=="A" || ($escrow_info["escrowcash"]=="Y" && (int)($sumprice+$deli_price)>=$escrow_info["escrow_limit"])) ){?>
						<?
							$pgid_info="";
							$pg_type="";
							$pgid_info=GetEscrowType($_data->escrow_id);
							$pg_type=trim($pgid_info["PG"]);
						?>
							<?if(strstr("ABCD",$pg_type)){?>
								<li><input id="dev_payment5" class="dev_payment radio-def" name="dev_payment" type="radio" value="Q" onclick="sel_paymethod(this);" > <label for="dev_payment5">에스크로 ( 가상계좌 )</label></li>
							<?}?>
						<?}?>
					</ul>

					<div class="pay-type-card" id="card_type" style="display:none">
						<table border=0 cellpadding=0 cellspacing=0 width="100%" summary="임금계좌를 선택">
							<colgroup>
								<?if($etcmessage[2]=="Y") {?><col width="20%" ><?}?>
								<col >
							</colgroup>
							<?if($etcmessage[2]=="Y") {?>
							<tr>
								<th>입금자명</th>
								<td>
									<input type="text" name="bank_sender" value="" >
								</td>
							</tr>
							<?}?>
							<tr>
								<th>입금계좌</th>
								<td>
									<select name="pay_data_sel" id="pay_data_sel" onchange="sel_account(this)" style="width:350px;">
										<option value='' >입금 계좌번호 선택 (반드시 주문자 성함으로 입금)</option>
										<?foreach($bank_payinfo as $k => $v){?>
										<option value="<?=$v?>" ><?=$v?></option>
										<?}?>
									</select>
								</td>
							</tr>
						</table>
					</div>

					<div class="pay-type-card" id="payco_notice" style="display:none">
						<table border=0 cellpadding=0 cellspacing=0 width="100%" summary="임금계좌를 선택">
							<tr>
								<td>
                                    <p>
                                        PAYCO는 NHN엔터테인먼트가 만든 안전한 간편결제 서비스입니다.<br>휴대폰과 카드 명의자가 동일해야 결제 가능하며, 결제금액 제한은 없습니다.
                                    </p>
								</td>
							</tr>
						</table>
					</div>

					<ul class="pay-type-attention">
						<li>신용카드/ 실시간 이체는 결제 후, 무통장입금은 입금확인 후 배송이 이루어집니다.</li>
						<li>은행 설정 시 주문번호마다 별도의 고객님만의 가상계좌가 생성됩니다. (주문완료 후 7일 이내 미입금 시 자동취소)</li>
						<li>일부 자동화 기기(CD/ATM)는 현금, 통장 입금이 제한될 수 있으며 입금오류 방지를 위해 정확한 금액을 입금 바랍니다.</li>
					</ul>

				</div>
				<!-- // 결제수단 -->

				<!-- 결제하기 -->
				<div class="payment-area">
					<div class="no-member-agree hide"><!-- 비회원일경우 출력됩니다. -->
						<input type="checkbox" id="no-member-check" class="checkbox-def">
						<label for="no-member-check">이용약관 및 개인정보 취급방침에 동의합니다.</label>
						<textarea name="" id="" cols="30" rows="10">이용약관 나옴다! 'ㅁ'!</textarea>
					</div>

					<div class="content">
						<?//$p_price=$sumprice+$deli_price+$sumpricevat; +$deli_price ?>
						<?$p_price=$sumprice+$sumpricevat;?>
						<input type="hidden" name="total_sum" id='total_sum' value="<?=$p_price?>">
						<input type="hidden" name="total_sumprice" id='total_sumprice' value="<?=$p_price?>">
						<input type='hidden' name='total_deli_price' id='total_deli_price' value="<?=$deli_price?>" >
                        <input type='hidden' name='total_deli_price2' id='total_deli_price2' value="<?=$deli_price2?>" >
						<p class="title">결제 금액</p>
						<ul>
							<li>
								<span class="txt"><strong>전체상품금액</strong></span>
								<span class="price">
									<strong id="paper_goodsprice" ><?=number_format($sumprice)?></strong>
								</span>
							</li>
							<li>
								<span class="txt">배송료 (선불) </span>
								<span class="price order_price_style02">
									<font id='delivery_price'><?=number_format($deli_price)?></font>
								</span>
							</li>
							<li>
								<span class="txt">배송료 (착불) </span>
								<span class="price order_price_style02">
									<font id='delivery_price2'><?=number_format($deli_price2)?></font>
								</span>
							</li>
							<li>
								<span class="txt"><? if($staff_order == 'Y') { echo '임직원 '; } ?>마일리지 사용</span>
								<span class="price CLS_saleMil">0</span>
							</li>
							<li>
								<span class="txt">할인쿠폰</span>
								<span class="price CLS_bCoupon">0</span>
							</li>
							<li>
								<span class="txt">상품쿠폰</span>
								<span class="price CLS_prCoupon">0</span>
							</li>
							<li class="last-price">
								<span class="txt">총 결제금액</span>
								<span class="price"><strong id="price_sum"><?=number_format($sumprice+$deli_price)?></strong></span>
							</li>
						</ul>
					</div>
					<div class="payment-agree">
						<div class="agree-box"><input id="dev_agree" type="checkbox" class="checkbox-def"><label for="dev_agree">동의합니다.(전자상거래법 제 8조 제 2항)</label></div>
						<p>주문하실 상품, 가격, 배송정보, 할인내역 등을 <br>최종 확인하였으며,구매에 동의하시겠습니까?</p>
					</div>

					<div class="btn-place pay">
						<div id="paybuttonlayer" name="paybuttonlayer" style="display:block;">
							<a href="javascript:CheckForm()" onmouseover="window.status='결제';return true;" target="_self" class="btn-dib-function" ><span>CHECK OUT</span></a>

							<!-- <a href="javascript:ordercancel('cancel')" onmouseover="window.status='취소';return true;" target="_self" class="btn_B" style="width:144px">취소하기</a> -->
						</div>
						<div id="payinglayer" name="payinglayer" style="display:none;">
							<img src="<?=$Dir?>img/common/paying_wait.gif" border=0>
						</div>
					</div>
				</div>
				<!-- // 결제하기 -->

			</div>
		</div>

	</div>


</div><!-- //메인 컨텐츠 -->



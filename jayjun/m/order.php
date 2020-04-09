<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
	<!-- 쿠폰사용 팝업 -->
	<section class="pop_layer layer_use_coupon">
		<div class="inner">
			<h3 class="title">쿠폰사용<button type="button" class="btn_close">닫기</button></h3>
			<div class="list_type">
				<table class="list_with_radio">
					<colgroup>
						<col style="width:40px;">
						<col style="width:auto;">
					</colgroup>
					<tbody>
						<tr>
							<th><input type="radio" id="coupon01" class="radio_def" name="couponUse"></th>
							<td>
								<label for="coupon01">
									<p>쿠폰명 노출 BRAND DAY 10% off BRAND DAY 10% off BRAND DAY 10% off </p>
									<p class="point-color">10% 할인</p>
								</label>
							</td>
						</tr>
						<tr>
							<th><input type="radio" id="coupon02" class="radio_def" name="couponUse"></th>
							<td>
								<label for="coupon02">
									<p>쿠폰명 노출 BRAND DAY 10%</p>
									<p class="point-color">10% 할인</p>
								</label>
							</td>
						</tr>
						<tr>
							<th><input type="radio" id="coupon03" class="radio_def" name="couponUse"></th>
							<td>
								<label for="coupon03">
									<p>쿠폰명 노출 BRAND DAY 10%</p>
									<p class="point-color">10% 할인</p>
								</label>
							</td>
						</tr>
						<tr>
							<th><input type="radio" id="coupon04" class="radio_def" name="couponUse"></th>
							<td>
								<label for="coupon04">
									<p>쿠폰명 노출 BRAND DAY 10% off BRAND DAY 10% off </p>
									<p class="point-color">10% 할인</p>
								</label>
							</td>
						</tr>
						<!-- 사용할 수 있는 쿠폰이 없는 경우 -->
						<!-- <tr>
							<td colspan="2" style="height:150px;text-align:center;">
								사용하실 수 있는 쿠폰이 없습니다. 
							</td>
						</tr> -->
						<!-- //사용할 수 있는 쿠폰이 없는 경우 -->
					</tbody>
				</table>

				<div class="btn_area">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-large">취소</a></li>
						<li><a href="javascript:;" class="btn-point h-large">적용</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<!-- //쿠폰사용 팝업 -->

	<!-- 배송지 목록 팝업 -->
	<section class="pop_layer layer_deli_site">
		<div class="inner">
			<h3 class="title">배송지 목록<button type="button" class="btn_close">닫기</button></h3>
			<div class="list_type">
				<table class="list_with_radio">
					<colgroup>
						<col style="width:40px;">
						<col style="width:auto;">
					</colgroup>
					<tbody>
						<tr>
							<th><input type="radio" id="deliSite01" class="radio_def" name="deliSite"></th>
							<td>
								<label for="deliSite01">
									<p class="name">우리집</p>
									<p class="mt-5">서울 강남구 강남대로 123번지 서울 강남구 강남대로 123번지</p>
								</label>
							</td>
						</tr>
						<tr>
							<th><input type="radio" id="deliSite02" class="radio_def" name="deliSite"></th>
							<td>
								<label for="deliSite02">
									<p class="name">회사</p>
									<p class="mt-5">서울 강남구 강남대로 123번지 서울 강남구 강남대로 123번지</p>
								</label>
							</td>
						</tr>
						<tr>
							<th><input type="radio" id="deliSite03" class="radio_def" name="deliSite"></th>
							<td>
								<label for="deliSite03">
									<p class="name">배송지 이름</p>
									<p class="mt-5">서울 강남구 강남대로 123번지 서울 강남구 강남대로 123번지</p>
								</label>
							</td>
						</tr>
						<tr>
							<th><input type="radio" id="deliSite04" class="radio_def" name="deliSite"></th>
							<td>
								<label for="deliSite04">
									<p class="name">배송지 2</p>
									<p class="mt-5">서울 강남구 강남대로 123번지 서울 강남구 강남대로 123번지</p>
								</label>
							</td>
						</tr>
						<!-- 등록된 배송지가 없는 경우 -->
						<!-- <tr>
							<td colspan="2" style="height:150px;text-align:center;">
								등록된 배송지가 없습니다. 
							</td>
						</tr> -->
						<!-- //등록된 배송지가 없는 경우 -->
					</tbody>
				</table>

				<div class="btn_area">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-large">취소</a></li>
						<li><a href="javascript:;" class="btn-point h-large">적용</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<!-- //배송지 목록 팝업 -->

	<!-- 매장안내 팝업 -->
	<section class="pop_layer layer_store_info">
		<div class="inner">
			<h3 class="title">매장 위치정보 <button type="button" class="btn_close">닫기</button></h3>
			<div class="select_store">
				<div class="list_store">
					<div class="info_area">
						<p class="store_name"><span class="brand">[SIEG FAHRENHEIT]</span> 강남역점</p>
						<table class="tbl_txt mt-20">
							<colgroup>
								<col style="width:52px;">
								<col style="width:auto;">
							</colgroup>
							<tbody>
								<tr>
									<th>주소 :</th>
									<td>서울 강남구 강남대로 123-12서울 강남구 강남대 123-12</td>
								</tr>
								<tr>
									<th>운영시간 :</th>
									<td>평일 09:00 ~ 18:00  (토/일 09:00 ~ 18:00) </td>
								</tr>
								<tr>
									<th>휴무정보 :</th>
									<td>매주 일요일 / 국경일</td>
								</tr>
								<tr>
									<th>전화번호 :</th>
									<td>02-1234-1111</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="map_area">
						<img src="static/img/test/@map_img2.jpg" alt="지도"><!-- [D] 구글지도 연동 -->
					</div>
				</div><!-- //.list_store -->
			</div><!-- //.select_store -->
		</div>
	</section>
	<!-- //매장안내 팝업 -->

	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>주문/결제</span>
		</h2>
		<div class="page_step">
			<ul class="clear">
				<li><span class="icon_order_step01"></span>장바구니</li>
				<li class="on"><span class="icon_order_step02"></span>주문하기</li>
				<li><span class="icon_order_step03"></span>주문완료</li>
			</ul>
		</div>
	</section><!-- //.page_local -->

	<section class="orderpage">

		<div class="list_cart">
			<!-- 브랜드별 반복 -->
			<div class="list_brand">
				<h3 class="cart_tit">BESTI BELLI 주문상품</h3>
				<ul class="cart_goods">
					<!-- 상품 반복 -->
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓 베이직 시크 엘레강스 스타일의 업그레이드 완성 코트</p><!-- [D] 상품명은 최대 2줄까지 노출 -->
										<p class="option">품번: SLOAX2520</p>
										<p class="option">색상 : NAM / 사이즈 : 74 / 1개</p>
										<p class="price">￦ 105,800 <span class="point-color">(적립 5%)</span></p>
									</div>
								</div>
							</div>
							<div class="coupon_area">
								<table>
									<colgroup>
										<col style="width:75px;">
										<col style="width:auto;">
									</colgroup>
									<tbody>
										<tr>
											<th><a href="javascript:;" class="btn_use_coupon btn-basic">쿠폰사용</a></th>
											<td><span class="coupon_name"></span></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div><!-- //.cart_wrap -->
					</li>
					<!-- //상품 반복 -->
					<!-- 상품 반복 -->
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="option">품번: SLOAX2520</p>
										<p class="option">색상 : NAM / 사이즈 : 74 / 1개</p>
										<p class="price">￦ 105,800 <span class="point-color">(적립 5%)</span></p>
									</div>
								</div>
							</div>
							<div class="coupon_area">
								<table>
									<colgroup>
										<col style="width:75px;">
										<col style="width:auto;">
									</colgroup>
									<tbody>
										<tr>
											<th><a href="javascript:;" class="btn_use_coupon btn-basic">쿠폰사용</a></th>
											<td><span class="coupon_name">쿠폰명 노출 BRAND DAY 10% off BRAND 초특가 10,000원 할인 쿠폰</span></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div><!-- //.cart_wrap -->
					</li>
					<!-- //상품 반복 -->
				</ul><!-- //.cart_goods -->
				<div class="cart_calc">
					<ul>
						<li>
							<label>상품합계</label>
							<span>￦ 30,000,000</span>
						</li>
						<li>
							<label>배송비</label>
							<span class="point-color">- ￦ 2,000</span>
						</li>
						<li class="total">
							<label>합계금액</label>
							<span>￦ 30,000,000</span>
						</li>
					</ul>
				</div>
			</div>
			<!-- //브랜드별 반복 -->

			<!-- O2O 상품 -->
			<div class="list_brand  with_deli_info">
				<h3 class="cart_tit">O2O 상품</h3>
				<ul class="cart_goods">
					<!-- 상품 반복 -->
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="option">품번: SLOAX2520</p>
										<p class="option">색상 : NAM / 사이즈 : 74 / 1개</p>
										<p class="price">￦ 105,800</p>
									</div>
								</div>
							</div>
							<div class="coupon_area">
								<table>
									<colgroup>
										<col style="width:75px;">
										<col style="width:auto;">
									</colgroup>
									<tbody>
										<tr>
											<th><a href="javascript:;" class="btn_use_coupon btn-basic">쿠폰사용</a></th>
											<td><span class="coupon_name">쿠폰명 노출 BRAND DAY 10% off BRAND</span></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div><!-- //.cart_wrap -->
						<div class="delibox">
							<h4 class="cart_tit">
								발송매장
								<div class="wrap_bubble today_shipping">
									<div class="btn_bubble"><button type="button" class="btn_help">?</button></div>
									<div class="pop_bubble">
										<div class="inner">
											<button type="button" class="btn_pop_close">닫기</button>
											<div class="container">
												<p>선택하신 상품은 당일수령이 가능한 상품입니다.</p>
											</div>
										</div>
									</div>
								</div><!-- //.wrap_bubble -->
							</h4>
							<div class="change_store">
								<span class="store_name">FAHRENHEIT 강남역점</span>
								<a href="javascript:;" class="btn_store_info btn-basic">매장안내</a>
							</div>
						</div><!-- //.delibox -->
					</li>
					<!-- //상품 반복 -->
					<!-- 상품 반복 -->
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="option">품번: SLOAX2520</p>
										<p class="option">색상 : NAM / 사이즈 : 74 / 1개</p>
										<p class="price">￦ 105,800</p>
									</div>
								</div>
							</div>
							<div class="coupon_area">
								<table>
									<colgroup>
										<col style="width:75px;">
										<col style="width:auto;">
									</colgroup>
									<tbody>
										<tr>
											<th><a href="javascript:;" class="btn_use_coupon btn-basic">쿠폰사용</a></th>
											<td><span class="coupon_name">쿠폰명 노출 BRAND DAY 10% off BRAND</span></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div><!-- //.cart_wrap -->
						<div class="delibox">
							<h4 class="cart_tit">
								픽업매장
								<div class="wrap_bubble today_shipping">
									<div class="btn_bubble"><button type="button" class="btn_help">?</button></div>
									<div class="pop_bubble">
										<div class="inner">
											<button type="button" class="btn_pop_close">닫기</button>
											<div class="container">
												<p>선택하신 매장을 방문하여 입어보고 수령하시면 됩니다. <br>(재고가 있을 경우 : 당일~3일 이내 방문수령 / 재고가 없을 경우 : 3일~5일 이내 방문수령)</p>
											</div>
										</div>
									</div>
								</div><!-- //.wrap_bubble -->
							</h4>
							<div class="change_store">
								<span class="store_name">FAHRENHEIT 강남역점(2017.01.19)</span>
								<a href="javascript:;" class="btn_store_info btn-basic">매장안내</a>
							</div>
						</div><!-- //.delibox -->
					</li>
					<!-- //상품 반복 -->
				</ul><!-- //.cart_goods -->
				<div class="cart_calc">
					<ul>
						<li>
							<label>상품합계</label>
							<span>￦ 30,000,000</span>
						</li>
						<li>
							<label>할인</label>
							<span class="point-color">- ￦ 2,000</span>
						</li>
						<li>
							<label>배송비</label>
							<span>￦ 2,000</span>
						</li>
						<li class="total">
							<label>합계금액</label>
							<span>￦ 30,000,000</span>
						</li>
					</ul>
				</div>
			</div>
			<!-- //O2O 상품 -->
		</div><!-- //.list_cart -->

		<!-- 할인 및 결제정보 -->
		<div class="order_table">
			<h3 class="cart_tit">할인 및 결제정보</h3>
			<table class="th-left">
				<colgroup>
					<col style="width:29.37%;">
					<col style="width:auto;">
				</colgroup>
				<tbody>
					<tr>
						<th>총 상품금액</th>
						<td>￦ 10,000,000</td>
					</tr>
					<tr>
						<th>포인트 사용</th>
						<td class="use_point">
							<span><input type="text" class="w70" value="10,000"> P</span>
							<label class="ml-20"><input type="checkbox" class="check_def"> <span>모두사용</span></label>
							<p class="mt-5">(사용가능 포인트 10,000 P)</p>
						</td>
					</tr>
					<tr>
						<th>E포인트 사용</th>
						<td><span class="disabled">5,000 P 이상부터 사용가능</span></td>
					</tr>
					<tr>
						<th>쿠폰할인</th>
						<td><span class="point-color">- ￦ 10,000</span></td>
					</tr>
					<tr>
						<th>배송비</th>
						<td>￦ 2,500</td>
					</tr>
					<tr>
						<th>실 결제금액</th>
						<td><strong class="point-color">￦ 8,000,000</strong></td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->
		<!-- //할인 및 결제정보 -->

		<!-- 주문고객 정보 -->
		<div class="order_table">
			<h3 class="cart_tit">주문고객 정보</h3>
			<table class="th-left">
				<colgroup>
					<col style="width:29.37%;">
					<col style="width:auto;">
				</colgroup>
				<tbody>
					<tr>
						<th><span class="required">주문자</span></th>
						<td><input type="text" class="w100-per" value="홍길동"></td>
					</tr>
					<tr>
						<th>이메일</th>
						<td>
							<div class="input_mail">
								<input type="text" value="hohoho"><span class="at">&#64;</span>
								<select class="select_line">
									<option value="">선택</option>
									<option value="" selected>naver.com</option>
									<option value=""></option>
								</select>
							</div>
							<input type="text" class="w100-per mt-5" placeholder="직접입력">
						</td>
					</tr>
					<tr>
						<th><span class="required">휴대전화</span></th>
						<td>
							<div class="input_tel">
								<select class="select_line">
									<option value="" selected>010</option>
									<option value=""></option>
									<option value=""></option>
								</select>
								<span class="dash"></span>
								<input type="tel" maxlength="4" value="1234">
								<span class="dash"></span>
								<input type="tel" maxlength="4" value="5678">
							</div>
						</td>
					</tr>
					<tr>
						<th>전화번호(선택)</th>
						<td>
							<div class="input_tel">
								<select class="select_line">
									<option value="">010</option>
									<option value=""></option>
									<option value=""></option>
								</select>
								<span class="dash"></span>
								<input type="tel" maxlength="4">
								<span class="dash"></span>
								<input type="tel" maxlength="4">
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->
		<!-- //주문고객 정보 -->

		<!-- 배송지 정보 -->
		<div class="order_table">
			<h3 class="cart_tit">배송지 정보</h3>
			<div class="table_top clear">
				<label><input type="checkbox" class="check_def"> <span>주문고객과 동일한 주소 사용</span></label>
				<div class="btn_area"><a href="javascript:;" class="btn_deli_site btn-basic">배송지목록</a></div>
			</div>
			<table class="th-left mt-5">
				<colgroup>
					<col style="width:29.37%;">
					<col style="width:auto;">
				</colgroup>
				<tbody>
					<tr>
						<th><span class="required">받는사람</span></th>
						<td>
							<input type="text" class="w100-per" placeholder="이름 입력">
							<div class="mt-5"><label><input type="checkbox" class="check_def"> <span>기본 배송지로 저장</span></label></div>
						</td>
					</tr>
					<tr>
						<th><span class="required">휴대전화</span></th>
						<td>
							<div class="input_tel">
								<select class="select_line">
									<option value="">010</option>
									<option value=""></option>
									<option value=""></option>
								</select>
								<span class="dash"></span>
								<input type="tel" maxlength="4">
								<span class="dash"></span>
								<input type="tel" maxlength="4">
							</div>
						</td>
					</tr>
					<tr>
						<th>전화번호(선택)</th>
						<td>
							<div class="input_tel">
								<select class="select_line">
									<option value="">010</option>
									<option value=""></option>
									<option value=""></option>
								</select>
								<span class="dash"></span>
								<input type="tel" maxlength="4">
								<span class="dash"></span>
								<input type="tel" maxlength="4">
							</div>
						</td>
					</tr>
					<tr>
						<th><span class="required">주소</span></th>
						<td>
							<div class="input_addr">
								<input type="text" placeholder="우편번호">
								<div class="btn_addr"><a href="javascript:;" class="btn-basic h-input">주소찾기</a></div>
							</div>
							<input type="text" class="w100-per mt-5" placeholder="기본주소">
							<input type="text" class="w100-per mt-5" placeholder="상세주소">
						</td>
					</tr>
					<tr>
						<th>배송 요청사항</th>
						<td>
							<select class="select_line w100-per">
								<option value="">직접입력</option>
								<option value=""></option>
								<option value=""></option>
							</select>
							<input type="text" class="w100-per mt-5">
						</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->
		<!-- //배송지 정보 -->

		<!-- 결제방식 선택 -->
		<div class="order_table">
			<h3 class="cart_tit">결제방식 선택</h3>
			<table class="th-left">
				<colgroup>
					<col style="width:29.37%;">
					<col style="width:auto;">
				</colgroup>
				<tbody>
					<tr>
						<th>신용카드</th>
						<td>
							<label><input type="radio" class="radio_def" name="payment" checked> <span>신용카드(일반)</span></label>
						</td>
					</tr>
					<tr>
						<th>현금결제</th>
						<td>
							<label><input type="radio" class="radio_def" name="payment"> <span>실시간 계좌이체</span></label>
							<label class="ml-10"><input type="radio" class="radio_def" name="payment"> <span>가상계좌</span></label>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="info">실행되는 보안 플러그인에 카드정보를 입력해주세요. 결제는 암호화 처리를 통해 안전합니다. 결제 후 재고가 없거나 본인이 요청이 있을 경우 배송전 결제를 취소할 수 있습니다. </td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->
		<!-- //결제방식 선택 -->

		<!-- 결제금액 -->
		<div class="calc_area">
			<h3 class="cart_tit">결제금액</h3>
			<div class="cart_calc">
				<ul>
					<li>
						<label>총 상품금액</label>
						<span>￦ 30,000,000</span>
					</li>
					<li>
						<label>배송비</label>
						<span>￦ 30,000</span>
					</li>
					<hr>
					<li>
						<label>포인트 사용</label>
						<span class="point-color">- ￦ 3,000</span>
					</li>
					<li>
						<label>E포인트 사용</label>
						<span class="point-color">- ￦ 30,000</span>
					</li>
					<li>
						<label>상품쿠폰 사용</label>
						<span class="point-color">- ￦ 0</span>
					</li>
					<li>
						<label>장바구니 쿠폰 사용</label>
						<span class="point-color">- ￦ 300</span>
					</li>
				</ul>
			</div>

			<div class="cart_calc">
				<ul>
					<li class="all_total">
						<label>실 결제금액</label>
						<span class="point-color">￦ 30,030,000</span>
					</li>
				</ul>
			</div>

			<div class="cart_calc">
				<h4 class="calc_tit">총 적립예정 포인트</h4>
				<ul>
					<li>
						<label>포인트</label>
						<span>10,000 P</span>
					</li>
					<li>
						<label>E포인트</label>
						<span>5,000 P</span>
					</li>
				</ul>
			</div>
		</div><!-- //.calc_area -->
		<!-- //결제금액 -->

		<div class="order_agree mt-15">
			<label><input type="checkbox" class="check_def"> <span>동의합니다. (전자상거래법 제 8조 제 2항)</span></label>
			<p class="mt-10">주문하실 상품,가격,배송정보,할인내역 등을 최종 확인하였으며, 구매에 동의하시겠습니까?</p>
		</div>
		
		<div class="btn_area mt-20 mr-10 ml-10">
			<ul>
				<li><a href="javascript:;" class="btn-point h-input">결제하기</a></li>
			</ul>
		</div>

	</section><!-- //.orderpage -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
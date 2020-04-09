<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>주문/결제</span>
		</h2>
		<div class="page_step">
			<ul class="clear">
				<li><span class="icon_order_step01"></span>장바구니</li>
				<li><span class="icon_order_step02"></span>주문하기</li>
				<li class="on"><span class="icon_order_step03"></span>주문완료</li>
			</ul>
		</div>
	</section><!-- //.page_local -->

	<section class="orderpage">
		<div class="result_msg">
			<p class="ment">결제가 취소되었습니다.</p>
		</div>

		<div class="list_cart">
			<!-- 주문상품 -->
			<div class="list_brand  with_deli_info">
				<h3 class="cart_tit">주문상품</h3>
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
										<p class="price">￦ 105,800</p>
										<div class="save point-color">적립예정 포인트 10,000 P</div>
										<span class="status_tag btn-line h-small">결제취소</span>
									</div>
								</div>
							</div>
						</div><!-- //.cart_wrap -->
						<div class="delibox">
							<h4 class="cart_tit">택배수령</h4>
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
										<div class="save point-color">적립예정 포인트 10,000 P</div>
										<span class="status_tag btn-line h-small">결제취소</span>
									</div>
								</div>
							</div>
						</div><!-- //.cart_wrap -->
						<div class="delibox">
							<h4 class="cart_tit">발송매장</h4>
							<div class="change_store">
								<span class="store_name">FAHRENHEIT 강남역점</span>
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
										<div class="save point-color">적립예정 포인트 10,000 P</div>
										<span class="status_tag btn-line h-small">결제취소</span>
									</div>
								</div>
							</div>
						</div><!-- //.cart_wrap -->
						<div class="delibox">
							<h4 class="cart_tit">픽업매장</h4>
							<div class="change_store">
								<span class="store_name">FAHRENHEIT 강남역점(2017.01.19)</span>
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
			<!-- //주문상품 -->
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
						<td><span class="point-color">- 10,000 P</span></td>
					</tr>
					<tr>
						<th>E포인트 사용</th>
						<td><span class="point-color">- 10,000 P</span></td>
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
					<tr>
						<th>결제방법</th>
						<td>신용카드(취소일자: 2017.01.16 16:00:57)</td>
					</tr>
					<tr>
						<th>취소사유</th>
						<td>카드한도초과</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->
		<!-- //할인 및 결제정보 -->

		<!-- 배송지 정보 -->
		<div class="order_table">
			<h3 class="cart_tit">배송지 정보</h3>
			<table class="th-left">
				<colgroup>
					<col style="width:29.37%;">
					<col style="width:auto;">
				</colgroup>
				<tbody>
					<tr>
						<th>받는사람</th>
						<td>홍길동</td>
					</tr>
					<tr>
						<th>휴대전화</th>
						<td>010-5212-4512</td>
					</tr>
					<tr>
						<th>전화번호(선택)</th>
						<td>02-1521-2242</td>
					</tr>
					<tr>
						<th>주소</th>
						<td>
							<p class="post">12345</p>
							<p class="mt-5">서울 강남구 강남대로 238 – 11</p>
						</td>
					</tr>
					<tr>
						<th>배송 요청사항</th>
						<td>배송 전 연락주세요.</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->
		<!-- //배송지 정보 -->

		<div class="btn_area mt-20 mr-10 ml-10">
			<ul class="ea2">
				<li><a href="javascript:;" class="btn-line h-input">쇼핑 계속하기</a></li>
				<li><a href="javascript:;" class="btn-point h-input">주문내역 확인하기</a></li>
			</ul>
		</div>

	</section><!-- //.orderpage -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
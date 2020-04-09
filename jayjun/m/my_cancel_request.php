<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
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
			<span>취소/교환/반품 신청</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="my_cancellist">
		<div class="check_period">
			<ul>
				<li class="on"><a href="javascript:;">1개월</a></li><!-- [D] 해당 조회기간일때 .on 클래스 추가 -->
				<li><a href="javascript:;">3개월</a></li>
				<li><a href="javascript:;">6개월</a></li>
				<li><a href="javascript:;">12개월</a></li>
			</ul>
		</div><!-- //.check_period -->

		<div class="list_myorder mt-15">
			<!-- 주문별 반복 -->
			<div class="with_deli_info">
				<h3 class="order_title">
					<span>주문번호: 20170119141335-78978646036</span>
					<a href="#">상세보기</a>
				</h3>
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
										<span class="status_tag btn-line h-small">반품신청</span><!-- [D] 관리자에서 [반품완료/교환완료] 전까지 모든 프로세스는 신청페이지에 노출됨 -->
									</div>
								</div>
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
										<span class="status_tag btn-line h-small">교환신청</span>
									</div>
								</div>
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
			</div>
			<!-- //주문별 반복 -->
			<!-- 주문별 반복 -->
			<div class="with_deli_info mt-20">
				<h3 class="order_title">
					<span>주문번호: 20170119141335-78978646036</span>
					<a href="#">상세보기</a>
				</h3>
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
										<span class="status_tag btn-line h-small">취소접수</span>
									</div>
								</div>
							</div>
						</div><!-- //.cart_wrap -->
						<div class="delibox">
							<h4 class="cart_tit">
								택배수령
								<div class="wrap_bubble today_shipping">
									<div class="btn_bubble"><button type="button" class="btn_help">?</button></div>
									<div class="pop_bubble">
										<div class="inner">
											<button type="button" class="btn_pop_close">닫기</button>
											<div class="container">
												<p>본사물류 또는 해당 브랜드 매장에서 택배로 고객님께 상품이 배송됩니다. <br>(주문 완료 후, 3~5일 이내 수령)</p>
											</div>
										</div>
									</div>
								</div><!-- //.wrap_bubble -->
							</h4>
						</div><!-- //.delibox -->
					</li>
					<!-- //상품 반복 -->
				</ul><!-- //.cart_goods -->
			</div>
			<!-- //주문별 반복 -->
		</div><!-- //.list_myorder -->

		<div class="list-paginate mt-15">
			<a href="#" class="prev-all disabled">처음</a><!-- [D] 버튼 비활성인 경우 .disabled 클래스 추가 -->
			<a href="#" class="prev disabled">이전</a>
			<a href="#" class="on">1</a>
			<a href="#">2</a>
			<a href="#">3</a>
			<a href="#">4</a>
			<a href="#">5</a>
			<a href="#">6</a>
			<a href="#" class="next">다음</a>
			<a href="#" class="next-all">끝</a>
		</div>

	</section><!-- //.my_cancellist -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
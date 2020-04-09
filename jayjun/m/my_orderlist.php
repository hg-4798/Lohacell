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
			<span>주문/배송조회</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="my_orderlist">
		<div class="check_period">
			<ul>
				<li class="on"><a href="javascript:;">1개월</a></li><!-- [D] 해당 조회기간일때 .on 클래스 추가 -->
				<li><a href="javascript:;">3개월</a></li>
				<li><a href="javascript:;">6개월</a></li>
				<li><a href="javascript:;">12개월</a></li>
			</ul>
		</div><!-- //.check_period -->

		<p class="info_msg">※ 취소, 교환, 반품은 주문상세보기 페이지에서 가능합니다.</p>

		<div class="list_myorder">
			<!-- 주문별 반복 -->
			<div class="with_deli_info">
				<h3 class="order_title">
					<span>주문번호: 20170119141335-78978646036</span>
					<a href="#">상세보기</a>
				</h3>
				<ul class="cart_goods">
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓 <strong>외 2건</strong></p>
										<p class="price">￦ 105,800</p>
										<span class="status_tag btn-point h-small">결제완료</span>
									</div>
								</div>
							</div>
						</div><!-- //.cart_wrap -->
					</li>
				</ul><!-- //.cart_goods -->
			</div>
			<!-- //주문별 반복 -->
			<!-- 주문별 반복 -->
			<div class="with_deli_info mt-15">
				<h3 class="order_title">
					<span>주문번호: 20170119141335-78978646036</span>
					<a href="#">상세보기</a>
				</h3>
				<ul class="cart_goods">
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓 베이직 시크 엘레강스 스타일의 업그레이드 완성 코트</p><!-- [D] 상품명은 최대 2줄까지 노출 -->
										<p class="price">￦ 105,800</p>
										<span class="status_tag btn-point h-small">배송중</span>
									</div>
								</div>
							</div>
						</div><!-- //.cart_wrap -->
					</li>
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

		<div class="attention mt-35">
			<h3 class="tit">유의사항</h3>
			<ul class="list">
				<li>[주문상세보기]를 클릭하시면 주문/취소/교환/반품을 하실 수 있습니다.</li>
				<li>결제 전 상태에서는 모든 주문건 취소가 가능하며, 출고 완료된 상품은 반품메뉴를 이용하시기 바랍니다.</li>
				<li>상품 일부만 취소/교환/반품을 원하시는 경우 1:1 문의 또는 고객센터(1544-0051)로 문의 부탁드립니다.</li>
				<li>배송처리 이후 14일이 경과되면 자동 구매확정 처리 되며 교환/반품이 불가능합니다. </li>
				<li>상품하자 또는 오배송으로 인한 교환/반품 신청은 1:1 문의 또는 고객센터(1544-0051)로 문의 부탁 드립니다.</li>
				<li>무통장입금 또는 가상계좌 결제주문의 경우, 환불금액 입금이 3-4일정도 소요됩니다. (영업일기준) </li>
			</ul>
		</div>

	</section><!-- //.my_orderlist -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
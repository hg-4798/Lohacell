<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">주문/배송조회</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				<ul class="order-flow clear">
					<li><i><img src="../static/img/icon/icon_my_order_ok.png" alt="주문접수"></i><p>01.주문접수</p></li>
					<li><i><img src="../static/img/icon/icon_my_payment_ok.png" alt="결제완료"></i><p>02.결제완료</p></li>
					<li><i><img src="../static/img/icon/icon_my_delivery_ready.png" alt="배송준비"></i><p>03.배송준비</p></li>
					<li><i><img src="../static/img/icon/icon_my_delivery_ing.png" alt="배송중"></i><p>04.배송중</p></li>
					<li><i><img src="../static/img/icon/icon_my_delivery_end.png" alt="배송완료"></i><p>05.배송완료</p></li>
				</ul>

				<section class="mt-50">
					
					<div class="clear">
						<div class="date-sort fl-r">
							<div class="type month">
								<p class="title">기간별 조회</p>
								<button type="button" class="on"><span>1개월</span></button>
								<button type="button"><span>3개월</span></button>
								<button type="button"><span>6개월</span></button>
								<button type="button"><span>12개월</span></button>
							</div>
							<div class="type calendar">
								<p class="title">일자별 조회</p>
								<div class="box">
									<input type="text" title="일자별 시작날짜" value="2017-01-25">
									<button type="button">달력 열기</button>
								</div>
								<span class="dash"></span>
								<div class="box">
									<input type="text" title="일자별 시작날짜" value="2017-01-25">
									<button type="button">달력 열기</button>
								</div>
							</div>
							<button type="button" class="btn-point"><span>검색</span></button>
						</div>
					</div>

					<header class="my-title">
						<h3 class="fz-0">주문 목록</h3>
						<div class="count">전체 <strong>235</strong></div>
						<div class="ord-no txt-toneB">※ 취소, 교환, 반품은 주문상세보기 페이지에서 가능합니다.</div>
					</header>
					<table class="th-top">
						<caption>주문 목록</caption>
						<colgroup>
							<col style="width:150px">
							<col style="width:auto">
							<col style="width:160px">
							<col style="width:120px">
						</colgroup>
						<thead>
							<tr>
								<th scope="col">주문번호</th>
								<th scope="col">주문상품</th>
								<th scope="col">실결제금액</th>
								<th scope="col">상태</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="my-order-nm">
									<strong>2017.01.09</strong><span>20170119141335-789765168</span>
									<a href="#" class="btn-line h-small mt-5">주문상세보기</a>
								</td>
								<td class="pl-40">
									<div class="goods-in-td">
										<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_02.jpg" alt="썸네일"></a></div>
										<div class="info">
											<p class="brand-nm">BESTI BELLI</p>
											<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
										</div>
									</div>
								</td>
								<td class="point-color fw-bold">\ 1,955,800</td>
								<td class="txt-toneA fz-13 fw-bold">결제완료</td>
							</tr>
							<tr>
								<td class="my-order-nm">
									<strong>2017.01.09</strong><span>20170119141335-789765168</span>
									<a href="#" class="btn-line h-small mt-5">주문상세보기</a>
								</td>
								<td class="pl-40">
									<div class="goods-in-td">
										<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_03.jpg" alt="썸네일"></a></div>
										<div class="info">
											<p class="brand-nm">BESTI BELLI</p>
											<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일 <strong>외 2건</strong></p>
										</div>
									</div>
								</td>
								<td class="point-color fw-bold">\ 1,955,800</td>
								<td class="txt-toneA fz-13 fw-bold">결제완료</td>
							</tr>
							<tr>
								<td class="my-order-nm">
									<strong>2017.01.09</strong><span>20170119141335-789765168</span>
									<a href="#" class="btn-line h-small mt-5">주문상세보기</a>
								</td>
								<td class="pl-40">
									<div class="goods-in-td">
										<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_06.jpg" alt="썸네일"></a></div>
										<div class="info">
											<p class="brand-nm">BESTI BELLI</p>
											<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
										</div>
									</div>
								</td>
								<td class="point-color fw-bold">\ 1,955,800</td>
								<td class="txt-toneA fz-13 fw-bold">결제완료</td>
							</tr>
						</tbody>
					</table>
					<div class="list-paginate mt-20">
						<a href="#" class="prev-all"></a>
						<a href="#" class="prev"></a>
						<a href="#" class="number on">1</a>
						<a href="#" class="number">2</a>
						<a href="#" class="number">3</a>
						<a href="#" class="number">4</a>
						<a href="#" class="number">5</a>
						<a href="#" class="number">6</a>
						<a href="#" class="number">7</a>
						<a href="#" class="number">8</a>
						<a href="#" class="number">9</a>
						<a href="#" class="number">10</a>
						<a href="#" class="next on"></a>
						<a href="#" class="next-all on"></a>
					</div>
					<dl class="attention-box mt-75">
						<dt>유의사항</dt>
						<dd>[주문상세보기]를 클릭하시면 주문/취소/교환/반품을 하실 수 있습니다.</dd>
						<dd>결제 전 상태에서는 모든 주문건 취소가 가능하며, 출고 완료된 상품은 반품메뉴를 이용하시기 바랍니다.</dd>
						<dd>상품 일부만 취소/교환/반품을 원하시는 경우 1:1 문의 또는 고객센터(1544-0051)로 문의 부탁드립니다.</dd>
						<dd>배송처리 이후 14일이 경과되면 자동 구매확정 처리 되며 교환/반품이 불가능합니다. </dd>
						<dd>상품하자 또는 오배송으로 인한 교환/반품 신청은 1:1 문의 또는 고객센터(1544-0051)로 문의 부탁드립니다.</dd>
						<dd>무통장입금 또는 가상계좌 결제주문의 경우, 환불금액 입금이 3-4일정도 소요됩니다. (영업일기준) </dd>
					</dl>
				</section><!-- //.lately-order -->

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
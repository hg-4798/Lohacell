<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">취소/교환/반품 현황</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				
				<section>
					<header class="my-title">
						<h3 class="fz-0">주문 목록</h3>
						<div class="count">전체 <strong>235</strong></div>
						<div class="date-sort clear">
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
					</header>
					<table class="th-top">
						<caption>주문 목록</caption>
						<colgroup>
							<col style="width:138px">
							<col style="width:auto">
							<col style="width:150px">
							<col style="width:150px">
							<col style="width:120px">
						</colgroup>
						<thead>
							<tr>
								<th scope="col">주문번호</th>
								<th scope="col" class="fz-0">주문상품</th>
								<th scope="col">실결제금액</th>
								<th scope="col">배송정보</th>
								<th scope="col">상태</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td rowspan="2" class="my-order-nm">
									<strong>2017.01.09</strong><span>20170119141335-789765168</span>
									<a href="#" class="btn-line h-small mt-5">주문상세보기</a>
								</td>
								<td class="pl-5">
									<div class="goods-in-td">
										<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_01.jpg" alt="썸네일"></a></div>
										<div class="info">
											<p class="brand-nm">BESTI BELLI</p>
											<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
											<p class="opt">품번: SLOAX2520 / 색상 : NAM  / 사이즈 55 / 3개</p>
										</div>
									</div>
								</td>
								<td rowspan="2" class="point-color fw-bold">\ 1,955,800</td>
								<td class="flexible-delivery">
									<strong class="txt-toneA">[당일배송]</strong><br>
									<strong class="txt-toneA">\36,000</strong>
									<div class="pt-5">VIKI 강남역점</div>
									<button class="btn-basic h-small mt-5 btn-infoStore" type="button"><span>매장안내</span></button>
								</td>
								<td class="txt-toneA fz-13 fw-bold">취소완료</td>
							</tr>
							<tr>
								<td class="pl-5">
									<div class="goods-in-td">
										<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_03.jpg" alt="썸네일"></a></div>
										<div class="info">
											<p class="brand-nm">BESTI BELLI</p>
											<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
											<p class="opt">품번: SLOAX2520 / 색상 : NAM  / 사이즈 55 / 3개</p>
										</div>
									</div>
								</td>
								<td class="flexible-delivery">
									<strong class="txt-toneA">[택배배송]</strong><br>
									<strong class="txt-toneA">\2,500</strong>
								</td>
								<td class="txt-toneA fz-13 fw-bold">반품완료</td>
							</tr>
							<tr>
								<td class="my-order-nm">
									<strong>2017.01.09</strong><span>20170119141335-789765168</span>
									<a href="#" class="btn-line h-small mt-5">주문상세보기</a>
								</td>
								<td class="pl-5">
									<div class="goods-in-td">
										<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_02.jpg" alt="썸네일"></a></div>
										<div class="info">
											<p class="brand-nm">BESTI BELLI</p>
											<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
											<p class="opt">품번: SLOAX2520 / 색상 : NAM  / 사이즈 55 / 3개</p>
										</div>
									</div>
								</td>
								<td class="point-color fw-bold">\ 1,955,800</td>
								<td class="flexible-delivery">
									<strong class="txt-toneA">[매장픽업]</strong>
									<div class="pt-5">VIKI 강남역점</div>
									<div class="pt-5">2017.01.23</div>
									<button class="btn-basic h-small mt-5 btn-infoStore" type="button"><span>매장안내</span></button>
								</td>
								<td class="txt-toneA fz-13 fw-bold">교환완료</td>
							</tr>
							<tr>
								<td class="my-order-nm">
									<strong>2017.01.09</strong><span>20170119141335-789765168</span>
									<a href="#" class="btn-line h-small mt-5">주문상세보기</a>
								</td>
								<td class="pl-5">
									<div class="goods-in-td">
										<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_07.jpg" alt="썸네일"></a></div>
										<div class="info">
											<p class="brand-nm">BESTI BELLI</p>
											<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
											<p class="opt">품번: SLOAX2520 / 색상 : NAM  / 사이즈 55 / 3개</p>
										</div>
									</div>
								</td>
								<td class="point-color fw-bold">\ 1,955,800</td>
								<td class="flexible-delivery">
									<strong class="txt-toneA">[택배발송]</strong><br>
									<div class="pt-5">무료</div>
								</td>
								<td class="txt-toneA fz-13 fw-bold">환불완료</td>
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
				</section><!-- //.lately-order -->

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">쿠폰</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				
				<div class="coupon-info">
					<dl>
						<dt>신원몰 쿠폰 안내</dt>
						<dd>- 장바구니 쿠폰은 주문서당 한 개의 쿠폰만 적용가능하며, 상품쿠폰은 중복적용이 가능합니다. </dd>
						<dd>- 유효기간이 만기된 쿠폰은 자동 소멸되며 재발행되지 않습니다.</dd>
						<dd>- 할인쿠폰의 할인금액이 상품의 판매가를 초과할 경우 사용이 불가능합니다.</dd>
						<dd>- 부분취소시 사용한 쿠폰 금액은 환불되지 않습니다.</dd>
					</dl>
					<fieldset class="coupon-reg">
						<legend>쿠폰 등록</legend>
						<label for="reg_coupon">쿠폰 등록하기</label>
						<input type="text" id="reg_coupon" title="쿠폰번호 첫번째 입력자리">
						<span>-</span>
						<input type="text" title="쿠폰번호 두번째 입력자리">
						<span>-</span>
						<input type="text" title="쿠폰번호 세번째 입력자리">
						<span>-</span>
						<input type="text" title="쿠폰번호 마지막 입력자리">
						<button class="btn-point" type="submit"><span>등록</span></button>
					</fieldset>
					<p class="att">※ 발급 받으신 쿠폰을 등록해주세요.</p>
				</div>

				<section class="mt-45">
					<header class="my-title mt-50">
						<h3 class="fz-0">쿠폰 내역</h3>
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
						<caption>통합포인트 목록</caption>
						<colgroup>
							<col style="width:105px">
							<col style="width:auto">
							<col style="width:115px">
							<col style="width:115px">
							<col style="width:210px">
							<col style="width:145px">
						</colgroup>
						<thead>
							<tr>
								<th scope="col">쿠폰번호</th>
								<th scope="col">쿠폰명</th>
								<th scope="col">사용혜택</th>
								<th scope="col">사용여부</th>
								<th scope="col">적용대상</th>
								<th scope="col">적용대상</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="txt-toneB">32132132</td>
								<td class="txt-toneA subject">생일축하 쿠폰</td>
								<td class="point-color fw-bold">10% 할인</td>
								<td class="txt-toneA">사용가능</td>
								<td class="txt-toneA">
									<div class="coupon-category">
										<span>카테고리[티셔츠] 외 2건</span>
										<ul class="category">
											<li>티셔츠</li>
											<li>남방</li>
										</ul>
									</div>
								</td>
								<td class="txt-toneB">2017.01.01 11시<br>~2017.02.01 23시</td>
							</tr>
							<tr>
								<td class="txt-toneB">32132132</td>
								<td class="txt-toneA subject">회원가입 쿠폰</td>
								<td class="point-color fw-bold">10% 할인</td>
								<td class="txt-toneA">사용가능</td>
								<td class="txt-toneA">
									<div class="coupon-category">
										<span>카테고리[티셔츠] 외 3건</span>
										<ul class="category">
											<li>티셔츠</li>
											<li>남방</li>
											<li>아웃터</li>
										</ul>
									</div>
								</td>
								<td class="txt-toneB">2017.01.01 11시<br>~2017.02.01 23시</td>
							</tr>
							<tr>
								<td class="txt-toneB">32132132</td>
								<td class="txt-toneA subject">기념일 쿠폰</td>
								<td class="point-color fw-bold">10% 할인</td>
								<td class="">사용불가</td>
								<td class="txt-toneA">
									<div class="coupon-category">
										<span>카테고리[티셔츠] 외 2건</span>
										<ul class="category">
											<li>티셔츠</li>
											<li>남방</li>
										</ul>
									</div>
								</td>
								<td class="txt-toneB">2017.01.01 11시<br>~2017.02.01 23시</td>
							</tr>
							<tr>
								<td class="txt-toneB">32132132</td>
								<td class="txt-toneA subject">전 상품 적용 10% 할인 쿠폰</td>
								<td class="point-color fw-bold">10% 할인</td>
								<td class="txt-toneA">사용가능</td>
								<td class="txt-toneA">
									<div class="coupon-category">
										<span>전체상품</span>
									</div>
								</td>
								<td class="txt-toneB">2017.01.01 11시<br>~2017.02.01 23시</td>
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
				</section>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
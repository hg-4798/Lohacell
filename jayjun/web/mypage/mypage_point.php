<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">포인트</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				
				<div class="point-info clear">
					<dl>
						<dt><img src="../static/img/icon/icon_my_grade.png" alt="회원등급">회원등급</dt>
						<dd class="fz-16">홍길동 님의 회원등급 <strong class="fz-20">BRONZE</strong></dd>
						<dd class="pt-5">등급업 필요 포인트 <strong>1,000P</strong><br>※ 온라인 회원등급은 통합포인트 기준</dd>
					</dl>
					<dl>
						<dt><img src="../static/img/icon/icon_my_point_big.png" alt="통합 포인트">통합 포인트</dt>
						<dd class="fz-20">현재 통합 포인트 <strong class="fz-22 point-color">2,000P</strong></dd>
						<dd class="pt-5">통합포인트: 오프라인 매장, 신원몰에서<br>모두 사용이 가능한 통합포인트</dd>
					</dl>
					<dl>
						<dt><img src="../static/img/icon/icon_my_epoint_big.png" alt="E통합 포인트">현재 E포인트</dt>
						<dd class="fz-20">현재 E포인트 <strong class="fz-22 point-color">2,000P</strong></dd>
						<dd class="pt-5">E포인트: 신원몰에서만 사용이 가능한<br>온라인 전용 포인트</dd>
					</dl>
				</div>

				<section class="mt-25" data-ui="TabMenu">
					<div class="tabs"> 
						<button type="button" data-content="menu" class="active"><span>통합포인트</span></button>
						<button type="button" data-content="menu"><span>E포인트</span></button>
					</div>
					<header class="my-title mt-50">
						<h3 class="fz-0">포인트 내역</h3>
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
					<div data-content="content" class="active">
						<table class="th-top">
							<caption>통합포인트 목록</caption>
							<colgroup>
								<col style="width:100px">
								<col style="width:auto">
								<col style="width:125px">
								<col style="width:125px">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">날짜</th>
									<th scope="col">상세내역</th>
									<th scope="col">적립포인트</th>
									<th scope="col">사용포인트</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="txt-toneB">2017.01.14</td>
									<td class="txt-toneA subject">로그인 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.13</td>
									<td class="txt-toneA subject">구매 리뷰 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.14</td>
									<td class="txt-toneA subject">로그인 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.13</td>
									<td class="txt-toneA subject">구매 리뷰 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.12</td>
									<td class="txt-toneA subject">포토 리뷰 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.14</td>
									<td class="txt-toneA subject">로그인 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.13</td>
									<td class="txt-toneA subject">구매 리뷰 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.12</td>
									<td class="txt-toneA subject">포토 리뷰 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.12</td>
									<td class="txt-toneA subject">포토 리뷰 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
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
					</div>
					<div data-content="content">
						<table class="th-top">
							<caption>E포인트 목록</caption>
							<colgroup>
								<col style="width:100px">
								<col style="width:auto">
								<col style="width:125px">
								<col style="width:125px">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">날짜</th>
									<th scope="col">상세내역</th>
									<th scope="col">적립포인트</th>
									<th scope="col">사용포인트</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="txt-toneB">2017.01.14</td>
									<td class="txt-toneA subject">로그인 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.13</td>
									<td class="txt-toneA subject">구매 리뷰 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.12</td>
									<td class="txt-toneA subject">포토 리뷰 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.14</td>
									<td class="txt-toneA subject">로그인 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.14</td>
									<td class="txt-toneA subject">로그인 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.13</td>
									<td class="txt-toneA subject">구매 리뷰 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.13</td>
									<td class="txt-toneA subject">구매 리뷰 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.12</td>
									<td class="txt-toneA subject">포토 리뷰 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.01.12</td>
									<td class="txt-toneA subject">포토 리뷰 포인트</td>
									<td class="txt-toneA">30</td>
									<td class="txt-toneB">0</td>
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
					</div>
				</section>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
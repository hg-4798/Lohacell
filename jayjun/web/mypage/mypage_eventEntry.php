<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">이벤트 참여현황</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				
				<section>
					<header class="my-title">
						<h3 class="fz-0">이벤트 참여현황</h3>
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
						<caption>이벤트 참여현황</caption>
						<colgroup>
							<col style="width:auto">
							<col style="width:200px">
							<col style="width:120px">
							<col style="width:120px">
						</colgroup>
						<thead>
							<tr>
								<th scope="col">이벤트명</th>
								<th scope="col">이벤트 기간</th>
								<th scope="col">발표일</th>
								<th scope="col">당첨결과</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="txt-toneA subject"><a href="">2017년 신년 이벤트 30% 할인</a></td>
								<td class="txt-toneB">2017.01.01 ~ 2017.02.01</td>
								<td class="txt-toneB">2017.02.02</td>
								<td class="txt-toneA">미발표</td>
							</tr>
							<tr>
								<td class="txt-toneA subject"><a href="">2017년 신년 이벤트 30% 할인</a></td>
								<td class="txt-toneB">2017.01.01 ~ 2017.02.01</td>
								<td class="txt-toneB">2017.02.02</td>
								<td class="txt-toneA">미발표</td>
							</tr>
							<tr>
								<td class="txt-toneA subject"><a href="">2017년 신년 이벤트 30% 할인</a></td>
								<td class="txt-toneB">2017.01.01 ~ 2017.02.01</td>
								<td class="txt-toneB">2017.02.02</td>
								<td class="">발표</td>
							</tr>
							<tr>
								<td class="txt-toneA subject"><a href="">2017년 신년 이벤트 30% 할인</a></td>
								<td class="txt-toneB">2017.01.01 ~ 2017.02.01</td>
								<td class="txt-toneB">2017.02.02</td>
								<td class="txt-toneA">미발표</td>
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
<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">1:1문의</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				
				<section data-ui="TabMenu">
					<div class="tabs top"> 
						<button type="button" data-content="menu" class="active"><span>답변대기</span></button>
						<button type="button" data-content="menu"><span>답변완료</span></button>
					</div>
					<header class="my-title mt-40">
						<h3 class="fz-0">1:1문의</h3>
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
						<table class="th-top table-toggle">
							<caption>답변대기 목록</caption>
							<colgroup>
								<col style="width:100px">
								<col style="width:120px">
								<col style="width:auto">
								<col style="width:100px">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">작성일</th>
									<th scope="col">상담유형</th>
									<th scope="col">제목</th>
									<th scope="col">상태</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="txt-toneB">2017.02.02</td>
									<td class="txt-toneA">구매관련</td>
									<td class="subject"><a href="javascript:;" class="menu fw-bold">쿠폰이 적용이 안되네요</a></td>
									<td class="txt-toneB">답변대기</td>
								</tr>
								<tr class="hide">
									<td class="reset" colspan="4">
										<div class="answer-box">
											<div class="question editor-output">
												<div class="btn">
													<button class="btn-basic" type="button"><span>수정</span></button>
													<button class="btn-line" type="button"><span>삭제</span></button>
												</div>
												<p>10% 쿠폰 있는데 적용이 안되요</p>
												<p>사용방법좀 알려주세요</p>
												<p><img src="../static/img/test/@loobook_thumb02.jpg" alt=""></p>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.02.02</td>
									<td class="txt-toneA">회원상담</td>
									<td class="subject"><a href="javascript:;" class="menu ellipsis w250 fw-bold">포인트 사용은 어떻게 하죠?</a></td>
									<td class="txt-toneB">답변대기</td>
								</tr>
								<tr class="hide">
									<td class="reset" colspan="4">
										<div class="answer-box">
											<div class="question editor-output">
												<div class="btn">
													<button class="btn-basic" type="button"><span>수정</span></button>
													<button class="btn-line" type="button"><span>삭제</span></button>
												</div>
												<p>포인트 써먹어야 하는데~!!</p>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="btn-withPainate">
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
							<button class="btn-point h-large w150 btn-my-qnaWrite" type="button"><span>문의하기</span></button>
						</div>
					</div>
					<div data-content="content">
						<table class="th-top table-toggle">
							<caption>답변완료 목록</caption>
							<colgroup>
								<col style="width:100px">
								<col style="width:120px">
								<col style="width:auto">
								<col style="width:100px">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">작성일</th>
									<th scope="col">상담유형</th>
									<th scope="col">제목</th>
									<th scope="col">상태</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="txt-toneB">2017.02.02</td>
									<td class="txt-toneA">구매관련</td>
									<td class="subject"><a href="javascript:;" class="menu fw-bold">쿠폰이 적용이 안되네요</a></td>
									<td class="txt-toneB">답변완료</td>
								</tr>
								<tr class="hide">
									<td class="reset" colspan="4">
										<div class="answer-box">
											<div class="question editor-output">
												<p>10% 쿠폰 있는데 적용이 안되요</p>
												<p>사용방법좀 알려주세요</p>
												<p><img src="../static/img/test/@loobook_thumb02.jpg" alt=""></p>
											</div>
											<div class="answer editor-output">
												<div class="answer-user"><span>관리자 <em>|</em> 2017.01.20</span></div>
												<p>안녕하세요 고객님.</p>
												<p>해당 쿠폰은 OOO카테고리에서 사용 가능합니다.</p>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.02.02</td>
									<td class="txt-toneA">회원상담</td>
									<td class="subject"><a href="javascript:;" class="menu ellipsis w250 fw-bold">포인트 사용은 어떻게 하죠?</a></td>
									<td class="txt-toneB">답변완료</td>
								</tr>
								<tr class="hide">
									<td class="reset" colspan="4">
										<div class="answer-box">
											<div class="question editor-output">
												<p>포인트 써먹어야 하는데~!!</p>
											</div>
											<div class="answer editor-output">
												<div class="answer-user"><span>관리자 <em>|</em> 2017.01.20</span></div>
												<p>안녕하세요 고객님.</p>
												<p>5만원 이상 구매시 사용 가능합니다.</p>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="btn-withPainate">
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
							<button class="btn-point h-large w150 btn-my-qnaWrite" type="button"><span>문의하기</span></button>
						</div>
					</div>
				</section>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
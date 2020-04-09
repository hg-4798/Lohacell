<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">상품리뷰</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				
				<div class="review-info clear">
					<div class="inner">리뷰 작성시<br><strong class="point-color">200P</strong> 지급</div>
					<div class="inner">포토 리뷰 작성시<br><strong class="point-color">500P</strong> 지급</div>
				</div>

				<section class="mt-25" data-ui="TabMenu">
					<div class="tabs"> 
						<button type="button" data-content="menu" class="active"><span>리뷰작성</span></button>
						<button type="button" data-content="menu"><span>완료리뷰</span></button>
					</div>
					<header class="my-title mt-40">
						<h3 class="fz-0">리뷰</h3>
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
					<div class="active" data-content="content">
						<table class="th-top">
							<caption>리뷰 작성</caption>
							<colgroup>
								<col style="width:100px">
								<col style="width:auto">
								<col style="width:135px">
								<col style="width:135px">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">주문날짜</th>
									<th scope="col">상품정보</th>
									<th scope="col">결제금액</th>
									<th scope="col">리뷰작성</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="txt-toneB">2017.02.02</td>
									<td class="pl-25">
										<div class="goods-in-td">
											<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_01.jpg" alt="썸네일"></a></div>
											<div class="info">
												<p class="brand-nm">BESTI BELLI</p>
												<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
												<p class="opt">색상 : NAM  / 사이즈 55</p>
											</div>
										</div>
									</td>
									<td class="txt-toneA fw-bold">10,000원</td>
									<td><div class="td-btnGroup"><button class="btn-basic h-small btn-reviewWrite" type="button"><span>작성하기</span></button></div></td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.02.02</td>
									<td class="pl-25">
										<div class="goods-in-td">
											<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_02.jpg" alt="썸네일"></a></div>
											<div class="info">
												<p class="brand-nm">BESTI BELLI</p>
												<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
												<p class="opt">색상 : NAM  / 사이즈 55</p>
											</div>
										</div>
									</td>
									<td class="txt-toneA fw-bold">10,000원</td>
									<td><div class="td-btnGroup"><button class="btn-basic h-small btn-reviewWrite" type="button"><span>작성하기</span></button></div></td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.02.02</td>
									<td class="pl-25">
										<div class="goods-in-td">
											<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_03.jpg" alt="썸네일"></a></div>
											<div class="info">
												<p class="brand-nm">BESTI BELLI</p>
												<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
												<p class="opt">색상 : NAM  / 사이즈 55</p>
											</div>
										</div>
									</td>
									<td class="txt-toneA fw-bold">10,000원</td>
									<td><div class="td-btnGroup"><button class="btn-basic h-small btn-reviewWrite" type="button"><span>작성하기</span></button></div></td>
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
						<table class="th-top table-toggle">
							<caption>완료리뷰</caption>
							<colgroup>
								<col style="width:100px">
								<col style="width:auto">
								<col style="width:300px">
								<col style="width:120px">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">주문날짜</th>
									<th scope="col">상품정보</th>
									<th scope="col">내용</th>
									<th scope="col">평가</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="txt-toneB">2017.02.02</td>
									<td class="pl-25">
										<div class="goods-in-td">
											<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_01.jpg" alt="썸네일"></a></div>
											<div class="info">
												<p class="brand-nm">BESTI BELLI</p>
												<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
												<p class="opt">색상 : NAM  / 사이즈 55</p>
											</div>
										</div>
									</td>
									<td class="subject"><a href="javascript:;" class="menu ellipsis w300">정말 마음에 듭니다.</a></td>
									<td class="review-rating">
										<!-- <img src="../static/img/icon/rating1.png" alt="5점 만점 중 1점">
										<img src="../static/img/icon/rating2.png" alt="5점 만점 중 2점">
										<img src="../static/img/icon/rating3.png" alt="5점 만점 중 3점">
										<img src="../static/img/icon/rating4.png" alt="5점 만점 중 4점"> -->
										<img src="../static/img/icon/rating5.png" alt="5점 만점 중 5점">
									</td>
								</tr>
								<tr class="hide">
									<td class="reset" colspan="4">
										<div class="board-answer editor-output">
											<div class="btn">
												<button class="btn-basic h-small w50"><span>수정</span></button>
												<button class="btn-line h-small w50"><span>삭제</span></button>
											</div>
											<p>
												키 <strong class="txt-toneA">160cm</strong>,
												몸무게 <strong class="txt-toneA">54kg</strong> 의 고객이
												<strong class="txt-toneA">L</strong>사이즈로 주문하였습니다.
											</p>
											<p>이번에 새로 구입했는데 정말 좋네요.</p>
											<p>선물로 강추네요~</p>
										</div>
									</td>
								</tr>
								<tr>
									<td class="txt-toneB">2017.02.02</td>
									<td class="pl-25">
										<div class="goods-in-td">
											<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_02.jpg" alt="썸네일"></a></div>
											<div class="info">
												<p class="brand-nm">BESTI BELLI</p>
												<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
												<p class="opt">색상 : NAM  / 사이즈 55</p>
											</div>
										</div>
									</td>
									<td class="subject"><a href="javascript:;" class="menu ellipsis w300">괜찮은 편이네요 <i class="icon-photo"></i></a></td>
									<td class="review-rating">
										<!-- <img src="../static/img/icon/rating1.png" alt="5점 만점 중 1점">
										<img src="../static/img/icon/rating2.png" alt="5점 만점 중 2점">
										<img src="../static/img/icon/rating3.png" alt="5점 만점 중 3점"> -->
										<img src="../static/img/icon/rating4.png" alt="5점 만점 중 4점">
										<!-- <img src="../static/img/icon/rating5.png" alt="5점 만점 중 5점"> -->
									</td>
								</tr>
								<tr class="hide">
									<td class="reset" colspan="4">
										<div class="board-answer editor-output">
											<div class="btn">
												<button class="btn-basic h-small w50"><span>수정</span></button>
												<button class="btn-line h-small w50"><span>삭제</span></button>
											</div>
											<p>
												키 <strong class="txt-toneA">160cm</strong>,
												몸무게 <strong class="txt-toneA">54kg</strong> 의 고객이
												<strong class="txt-toneA">L</strong>사이즈로 주문하였습니다.
											</p>
											<p>이번에 새로 구입했는데 정말 좋네요.</p>
											<p>선물로 강추네요~</p>
											<p></p>
											<p><img src="../static/img/test/@loobook_thumb02.jpg" alt=""></p>
										</div>
									</td>
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
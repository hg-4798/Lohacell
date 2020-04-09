<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">주문/배송조회</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				<div class="my-grade-summary clear">
					<div class="info-grade">
						<span class="fw-bold pr-5">홍길동</span>님의 회원등급 <strong>BRONZE</strong>
						<div class="link"><a href="#" class="btn-basic h-small">등급별 혜택보기 &gt;</a></div>
					</div>
					<div class="progress">
						<dl>
							<dt><i><img src="../static/img/icon/icon_my_coupon.png" alt="쿠폰"></i><span>내쿠폰</span></dt>
							<dd class="point-color">1</dd>
						</dl>
						<dl>
							<dt><i><img src="../static/img/icon/icon_my_point.png" alt="내쿠폰"></i><span>내쿠폰</span></dt>
							<dd class="point-color">21,500P</dd>
						</dl>
						<dl>
							<dt><i><img src="../static/img/icon/icon_my_epoint.png" alt="E-포인트"></i><span>E-포인트</span></dt>
							<dd class="point-color">12,500P</dd>
						</dl>
						<dl>
							<dt><i><img src="../static/img/icon/icon_my_delivery_ing.png" alt="배송중"></i><span>배송중</span></dt>
							<dd class="point-color">1</dd>
						</dl>
						<dl>
							<dt><i><img src="../static/img/icon/icon_my_delivery_end.png" alt="배송완료"></i><span>배송완료</span></dt>
							<dd class="point-color">2</dd>
						</dl>
						<dl>
							<dt><i><img src="../static/img/icon/icon_my_delivery_refund.png" alt="취소/교환/반품"></i><span>취소/교환/반품</span></dt>
							<dd class="point-color">3</dd>
						</dl>
					</div>
				</div><!-- //.my-grade-summary -->
				
				<section class="lately-order mt-60">
					<header class="my-title">
						<h3>최근 주문/배송조회</h3>
						<div class="ord-no txt-toneB">※ 취소, 교환, 반품은 주문상세보기 페이지에서 가능합니다.</div>
					</header>
					<table class="th-top">
						<caption>최근 주문 배송목록</caption>
						<colgroup>
							<col style="width:150px">
							<col style="width:auto">
							<col style="width:160px">
							<col style="width:140px">
						</colgroup>
						<thead>
							<tr>
								<th scope="col">주문번호</th>
								<th scope="col" class="fz-0">주문상품</th>
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
										<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_05.jpg" alt="썸네일"></a></div>
										<div class="info">
											<p class="brand-nm">BESTI BELLI</p>
											<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일 </p>
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
										<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_02.jpg" alt="썸네일"></a></div>
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
											<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일 <strong>외 2건</strong></p>
										</div>
									</div>
								</td>
								<td class="point-color fw-bold">\ 1,955,800</td>
								<td class="txt-toneA fz-13 fw-bold">결제완료</td>
							</tr>
						</tbody>
					</table>
				</section><!-- //.lately-order -->

				<section class="my-main-list mt-60">
					<header class="my-title"><h3>최근 본 상품</h3></header>
					<ul class="clear">
						<li>
							<div class="goods-item">
								<div class="thumb-img">
									<a href="#"><img src="../static/img/test/@goods_thumb300_01.jpg" alt="상품 썸네일"></a>
									<div class="layer">
										<div class="btn">
											<button type="button" class="btn-preview"><span><i class="icon-cart">장바구니</i></span></button>
											<button type="button"><span><i class="icon-like">좋아요</i></span><span>11</span></button> <!-- [D] 좋아요 선택시 .on 처리 -->
										</div>
										<div class="opt">
											<span>55</span>
											<span>66</span>
											<span>77</span>
										</div>
									</div>
								</div><!-- //.thumb-img -->
								<div class="price-box">
									<div class="brand-nm">BESTI BELLI</div>
									<div class="goods-nm">솔리드 심플 벨티드 자켓</div>
								</div>
							</div><!-- //.goods-item -->
						</li>
						<li>
							<div class="goods-item">
								<div class="thumb-img">
									<a href="#"><img src="../static/img/test/@goods_thumb300_02.jpg" alt="상품 썸네일"></a>
									<div class="layer">
										<div class="btn">
											<button type="button" class="btn-preview"><span><i class="icon-cart">장바구니</i></span></button>
											<button type="button"><span><i class="icon-like">좋아요</i></span><span>11</span></button> <!-- [D] 좋아요 선택시 .on 처리 -->
										</div>
										<div class="opt">
											<span>55</span>
											<span>66</span>
											<span>77</span>
										</div>
									</div>
								</div><!-- //.thumb-img -->
								<div class="price-box">
									<div class="brand-nm">BESTI BELLI</div>
									<div class="goods-nm">솔리드 심플 벨티드 자켓</div>
								</div>
							</div><!-- //.goods-item -->
						</li>
						<li>
							<div class="goods-item">
								<div class="thumb-img">
									<a href="#"><img src="../static/img/test/@goods_thumb300_03.jpg" alt="상품 썸네일"></a>
									<div class="layer">
										<div class="btn">
											<button type="button" class="btn-preview"><span><i class="icon-cart">장바구니</i></span></button>
											<button type="button"><span><i class="icon-like">좋아요</i></span><span>11</span></button> <!-- [D] 좋아요 선택시 .on 처리 -->
										</div>
										<div class="opt">
											<span>55</span>
											<span>66</span>
											<span>77</span>
										</div>
									</div>
								</div><!-- //.thumb-img -->
								<div class="price-box">
									<div class="brand-nm">BESTI BELLI</div>
									<div class="goods-nm">솔리드 심플 벨티드 자켓</div>
								</div>
							</div><!-- //.goods-item -->
						</li>
						<li>
							<div class="goods-item">
								<div class="thumb-img">
									<a href="#"><img src="../static/img/test/@goods_thumb300_04.jpg" alt="상품 썸네일"></a>
									<div class="layer">
										<div class="btn">
											<button type="button" class="btn-preview"><span><i class="icon-cart">장바구니</i></span></button>
											<button type="button"><span><i class="icon-like">좋아요</i></span><span>11</span></button> <!-- [D] 좋아요 선택시 .on 처리 -->
										</div>
										<div class="opt">
											<span>55</span>
											<span>66</span>
											<span>77</span>
										</div>
									</div>
								</div><!-- //.thumb-img -->
								<div class="price-box">
									<div class="brand-nm">BESTI BELLI</div>
									<div class="goods-nm">솔리드 심플 벨티드 자켓</div>
								</div>
							</div><!-- //.goods-item -->
						</li>
					</ul>
				</section><!-- //.lately-view -->

				<div class="coupon-qna mt-20 clear">
					<section class="coupon">
						<header class="my-title">
							<h3>쿠폰</h3>
							<a href="#" class="more">더보기</a>
						</header>
						<table class="th-top">
							<caption>쿠폰 요약</caption>
							<colgroup>
								<col style="width:auto">
								<col style="width:80px">
								<col style="width:80px">
								<col style="width:120px">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">쿠폰명</th>
									<th scope="col">사용혜택</th>
									<th scope="col">사용여부</th>
									<th scope="col">유효기간</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="subject pl-10">신규 회원가입 축하 10,000원 할인 쿠폰</td>
									<td class="point-color fw-700 fz-13">\10,000</td>
									<td class="txt-toneA">사용가능</td> <!-- [D] 사용가능시 .txt-toneA 추가 -->
									<td class="txt-toneB">2016.11.28 11시~<br>2017.12.31.23시</td>
								</tr>
								<tr>
									<td class="subject pl-10">전 상품 적용 10% 할인쿠폰</td>
									<td class="point-color fw-700 fz-13">10% 할인</td>
									<td>사용불가</td>
									<td class="txt-toneB">2016.11.28 11시~<br>2017.12.31.23시</td>
								</tr>
								<tr>
									<td class="subject pl-10">생일축하 10%할인쿠폰</td>
									<td class="point-color fw-700 fz-13">10% 할인</td>
									<td class="txt-toneA">사용가능</td>
									<td class="txt-toneB">2016.11.28 11시~<br>2017.12.31.23시</td>
								</tr>
							</tbody>
						</table>
					</section>
					<section class="qna">
						<header class="my-title">
							<h3>1:1문의</h3>
							<a href="#" class="more">더보기</a>
						</header>
						<table class="th-top">
							<caption>쿠폰 요약</caption>
							<colgroup>
								<col style="width:90px">
								<col style="width:auto">
								<col style="width:90px">
								<col style="width:90px">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">상담유형</th>
									<th scope="col">제목</th>
									<th scope="col">작성일</th>
									<th scope="col">상태</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="txt-toneA">회원상담</td>
									<td class="txt-toneA ta-l "><a href="#" class="ellipsis w200 fw-bold">가입시 인증이 오류가 납니다.</a></td>
									<td class="txt-toneB">2017.01.14</td>
									<td class="txt-toneA">답변대기</td> <!-- [D] 답변대기시 .txt-toneA 추가 -->
								</tr>
								<tr>
									<td class="txt-toneA">로그인</td>
									<td class="txt-toneA ta-l "><a href="#" class="ellipsis w200 fw-bold">간편 로그인이 안돼요</a></td>
									<td class="txt-toneB">2017.01.14</td>
									<td>답변완료</td>
								</tr>
								<tr>
									<td class="txt-toneA">구매관련</td>
									<td class="txt-toneA ta-l "><a href="#" class="ellipsis w200 fw-bold">이 상품 언제 재입고 되나요? 기다리기 힘들어욤</a></td>
									<td class="txt-toneB">2017.01.14</td>
									<td class="txt-toneA">답변대기</td> <!-- [D] 답변대기시 .txt-toneA 추가 -->
								</tr>
							</tbody>
						</table>
					</section>
				</div><!-- //.coupon-qna -->

				<section class="my-main-list mt-60">
					<header class="my-title"><h3>좋아요</h3></header>
					<ul class="clear">
						<li>
							<a class="like-item" href="#">
								<div class="like-count"><i class="icon-dark-like"></i><span>255</span></div>
								<figure>
									<img src="../static/img/test/@loobook_thumb03.jpg" alt="">
									<figcaption>
										<div class="type">BESTIBELLI</div>
										<div class="subject">시프트 플라운스 원피스</div>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a class="like-item" href="#">
								<div class="like-count"><i class="icon-dark-like"></i><span>255</span></div>
								<figure>
									<img src="../static/img/banner/catalog_view01.jpg" alt="">
									<figcaption>
										<div class="type">카탈로그</div>
										<div class="subject">2017 S/S신상 기획전</div>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a class="like-item" href="#">
								<div class="like-count"><i class="icon-dark-like"></i><span>255</span></div>
								<figure>
									<img src="../static/img/banner/lookbook_view01.jpg" alt="">
									<figcaption>
										<div class="type">룩북</div>
										<div class="subject">2017 S/S신상 기획전</div>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a class="like-item" href="#">
								<div class="like-count"><i class="icon-dark-like"></i><span>255</span></div>
								<figure>
									<img src="../static/img/banner/outlet_banner580.jpg" alt="">
									<figcaption>
										<div class="type">매거진</div>
										<div class="subject">여행을 좋아하는 당신 지금 겨울여행을 떠나라</div>
									</figcaption>
								</figure>
							</a>
						</li>
					</ul>
				</section><!-- //.my-like -->

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
<?php
$Dir="../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

include('./include/top.php');
include('./include/gnb.php');
?>

<!-- 내용 -->
<div id="page">
	<main id="content">
		
		<section class="page_local is-line">
			<h2 class="page_title">
				<a href="javascript:history.back();" class="prev">이전페이지</a>
				<span>PHOTO REVIEW</span>
			</h2>
		</section>

		<section class="review_wrp">
			<div class="best_review">
				<div class="main_tit">
					<h2>BEST REVIEW</h2>
					<p class="exp">i KNOW iONE 고객들의 생생 리뷰입니다</p>
				</div>
				<ul class="best_review_list">
					<li>
						<a href="">
							<figure class="post">
								<div class="img" style="background-image:url('/jayjun/web/static/img/test/@best_review01.jpg')"></div>
								<figcaption>
									<p class="subject">I TOUCH CHEEK BLOSSOM</p>
									<p class="text">힘없이 쳐지는 속눈썹에 힘과 탄력을 부여한다고 했는데, 써본지 1주일 정도 밖에 지나지 않았는데.</p>
									<p class="writer">iseeyou***</p>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="">
							<figure class="post">
								<div class="img" style="background-image:url('/jayjun/web/static/img/test/@best_review02.jpg')"></div>
								<figcaption>
									<p class="subject">I LOVE LIP STICK</p>
									<p class="text">솔직히 별로 기대안했는데 진짜 생각 이상입니다. 발색좋고 촉촉하니 정말 좋아요.</p>
									<p class="writer">friend***</p>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="">
							<figure class="post">
								<div class="img" style="background-image:url('/jayjun/web/static/img/test/@best_review03.jpg')"></div>
								<figcaption>
									<p class="subject">I NEED U CUSHION SPF50+</p>
									<p class="text">블러셔 사용하고 싶은데 능숙하지 못해서 진짜 은은하게 한듯안한듯한 자연스러운 컬러가 너무 맘에... </p>
									<p class="writer">bluemoon***</p>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="">
							<figure class="post">
								<div class="img" style="background-image:url('/jayjun/web/static/img/test/@best_review04.jpg')"></div>
								<figcaption>
									<p class="subject">I NEED GLOW SHIMMER BASE</p>
									<p class="text">클렌징밤 사러 갔다가 테스트만 해보고 결국 이녀석을 구매했는데 완전 인생템 될꺼 같아요.</p>
									<p class="writer">iknowlif***</p>
								</figcaption>
							</figure>
						</a>
					</li>
				</ul>
			</div>

			<div class="list_area">
				<form>
					<fieldset class="v-hidden">검색</fieldset>
					<div class="board_search">
						<select class="select_line" title="정렬 선택">
							<option value="">최신순</option>
						</select>
						<div class="input_addr">
							<input type="text" title="검색어 입력" placeholder="상품명을 입력해주세요.">
							<div class="btn_addr"><a href="javascript:;" class="btn-point h-input">검색</a></div>
						</div>
					</div>
				</form>
				<div class="board_msg"><strong>총 123건</strong>의 상품평이 작성되었습니다.</div>
				<ul class="pt_review_list">
					<li>
						<a href="javascript:;" class="btn_ptreview_view">
							<div class="post">
								<div class="img" style="background-image:url('/jayjun/web/static/img/test/@pt_review01.jpg')"></div>
								<div class="score">
									<img src="/jayjun/m/static/img/icon/rating_score4.png" alt="5점 만점중 4점">
								</div>
								<div class="subject">
									<p class="goods">I NEED GLOW SHIMMER BASE</p>
									<p class="title">아주 잘 사용 하고 있어요~ </p>
								</div>
								<div class="writer">
									<span class="date">2018.05.30</span>
									<span class="id">iloveio***</span>
								</div>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:;" class="btn_ptreview_view">
							<div class="post">
								<div class="img" style="background-image:url('/jayjun/web/static/img/test/@pt_review02.jpg')"></div>
								<div class="score">
									<img src="/jayjun/m/static/img/icon/rating_score4.png" alt="5점 만점중 4점">
								</div>
								<div class="subject">
									<p class="goods">I NEED GLOW SHIMMER BASE</p>
									<p class="title">제품명에서 알 수 있듯이 수분을 가득 피부에 충족시켜주는 워터 에센스 에센셜 토너~</p>
								</div>
								<div class="writer">
									<span class="date">2018.05.30</span>
									<span class="id">iloveio***</span>
								</div>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:;" class="btn_ptreview_view">
							<div class="post">
								<div class="img" style="background-image:url('/jayjun/web/static/img/test/@pt_review03.jpg')"></div>
								<div class="score">
									<img src="/jayjun/m/static/img/icon/rating_score4.png" alt="5점 만점중 4점">
								</div>
								<div class="subject">
									<p class="goods">I NEED GLOW SHIMMER BASE</p>
									<p class="title">인생템을 찾았어요! </p>
								</div>
								<div class="writer">
									<span class="date">2018.05.30</span>
									<span class="id">iloveio***</span>
								</div>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:;" class="btn_ptreview_view">
							<div class="post no-photo">
								<div class="score">
									<img src="/jayjun/m/static/img/icon/rating_score4.png" alt="5점 만점중 4점">
								</div>
								<div class="subject">
									<p class="goods">I NEED GLOW SHIMMER BASE</p>
									<p class="title">인생템을 찾았어요! </p>
								</div>
								<div class="writer">
									<span class="date">2018.05.30</span>
									<span class="id">iloveio***</span>
								</div>
							</div>
						</a>
					</li>
				</ul>
				<p class="att">※ 리뷰 게시판은 제이준코스메틱 쇼핑몰 회원이 구매한 제품에 대한 이용후기를 공유하는 게시판입니다. 리뷰 작성을 원하시면 <a href="">바로가기</a>를 눌러주세요. </p>
				<div class="list-paginate mt-20">
					<a href="#" class="prev-all">처음</a>
					<a href="#" class="prev">이전</a>
					<a href="#" class="on">1</a>
					<a href="#">2</a>
					<a href="#">3</a>
					<a href="#">4</a>
					<a href="#">5</a>
					<a href="#" class="next">다음</a>
					<a href="#" class="next-all">끝</a>
				</div>
			</div>
		</section><!-- //.review_wrp -->

	</main>
</div>
<!-- //내용 -->

<? include('./include/bottom.php'); ?>
<?php
include_once('outline/header_m.php');
$page_cate = '이벤트';
?>

<!-- 내용 -->
<main id="content" class="subpage">
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>프로모션</span>
		</h2>
		<div class="breadcrumb">
			<?php include_once('promotion_menu.php'); ?>
		</div>
	</section><!-- //.page_local -->

	<section class="promotion">

		<div class="topbanner with-btn-rolling">
			<ul class="slide">
				<li><a href="#"><img src="static/img/test/@event_slide01.jpg" alt="이벤트 이미지"></a></li>
				<li><a href="#"><img src="static/img/test/@event_slide01.jpg" alt="이벤트 이미지"></a></li>
				<li><a href="#"><img src="static/img/test/@event_slide01.jpg" alt="이벤트 이미지"></a></li>
				<li><a href="#"><img src="static/img/test/@event_slide01.jpg" alt="이벤트 이미지"></a></li>
				<li><a href="#"><img src="static/img/test/@event_slide01.jpg" alt="이벤트 이미지"></a></li>
				<li><a href="#"><img src="static/img/test/@event_slide01.jpg" alt="이벤트 이미지"></a></li>
				<li><a href="#"><img src="static/img/test/@event_slide01.jpg" alt="이벤트 이미지"></a></li>
			</ul>
		</div>
			
		<div class="event_tab tab_type1" data-ui="TabMenu">
			<div class="tab-menu clear">
				<a data-content="menu" class="active" title="선택됨">진행중 이벤트</a>
				<a data-content="menu">종료된 이벤트</a>
				<a data-content="menu">당첨자발표</a>
			</div>
			<!-- 진행중 이벤트 -->
			<div class="tab-content active" data-content="content">
				<ul class="event_list"><!-- [D] 15개 페이징 -->
					<li>
						<a href="#">
							<div class="img"><img src="static/img/test/@event_list01.jpg" alt="이벤트 이미지"></div>
							<div class="info">
								<p class="subject">GOOD BYE 2016 이벤트</p>
								<p class="period">2017.02.01~2017.02.28</p>
							</div>
						</a>
					</li>
					<li>
						<a href="#">
							<div class="img"><img src="static/img/test/@event_list02.jpg" alt="이벤트 이미지"></div>
							<div class="info">
								<p class="subject">BIG SALE 88% 이벤트</p>
								<p class="period">2017.02.01~2017.02.28</p>
							</div>
						</a>
					</li>
					<li>
						<a href="#">
							<div class="img"><img src="static/img/test/@event_list03.jpg" alt="이벤트 이미지"></div>
							<div class="info">
								<p class="subject">여성복 창고 대방출 2탄 빅세일</p>
								<p class="period">2017.02.01~2017.02.28</p>
							</div>
						</a>
					</li>
					<li>
						<a href="#">
							<div class="img"><img src="static/img/test/@event_list04.jpg" alt="이벤트 이미지"></div>
							<div class="info">
								<p class="subject">GOOD BYE 2016 이벤트</p>
								<p class="period">2017.02.01~2017.02.28</p>
							</div>
						</a>
					</li>
				</ul><!-- //.event_list -->
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
			</div>
			<!-- //진행중 이벤트 -->

			<!-- 종료된 이벤트 -->
			<div class="tab-content" data-content="content">
				<ul class="event_list"><!-- [D] 15개 페이징 -->
					<li>
						<a href="#">
							<div class="img"><img src="static/img/test/@event_list02.jpg" alt="이벤트 이미지"></div>
							<div class="info">
								<p class="subject">BIG SALE 88% 이벤트</p>
								<p class="period">2017.01.01~2017.01.31</p>
							</div>
						</a>
					</li>
					<li>
						<a href="#">
							<div class="img"><img src="static/img/test/@event_list01.jpg" alt="이벤트 이미지"></div>
							<div class="info">
								<p class="subject">GOOD BYE 2016 이벤트</p>
								<p class="period">2017.01.01~2017.01.31</p>
							</div>
						</a>
					</li>
					<li>
						<a href="#">
							<div class="img"><img src="static/img/test/@event_list03.jpg" alt="이벤트 이미지"></div>
							<div class="info">
								<p class="subject">여성복 창고 대방출 2탄 빅세일</p>
								<p class="period">2017.01.01~2017.01.31</p>
							</div>
						</a>
					</li>
					<li>
						<a href="#">
							<div class="img"><img src="static/img/test/@event_list04.jpg" alt="이벤트 이미지"></div>
							<div class="info">
								<p class="subject">GOOD BYE 2016 이벤트</p>
								<p class="period">2017.01.01~2017.01.31</p>
							</div>
						</a>
					</li>
				</ul><!-- //.event_list -->
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
			</div>
			<!-- //종료된 이벤트 -->

			<!-- 당첨자발표 -->
			<div class="tab-content" data-content="content">
				<ul class="event_win">
					<li>
						<a href="#">
							<p class="subject">2017년 신년 이벤트</p>
							<p class="period">기간 : 2017.01.20 00시 ~ 2017.02.01 23시</p>
							<p class="date">발표일 : 2017.02.10</p>
						</a>
					</li>
					<li>
						<a href="#">
							<p class="subject">2017년 신년 이벤트</p>
							<p class="period">기간 : 2017.01.20 00시 ~ 2017.02.01 23시</p>
							<p class="date">발표일 : 2017.02.10</p>
						</a>
					</li>
					<li>
						<a href="#">
							<p class="subject">2017년 신년 이벤트</p>
							<p class="period">기간 : 2017.01.20 00시 ~ 2017.02.01 23시</p>
							<p class="date">발표일 : 2017.02.10</p>
						</a>
					</li>
					<li>
						<a href="#">
							<p class="subject">2017년 신년 이벤트</p>
							<p class="period">기간 : 2017.01.20 00시 ~ 2017.02.01 23시</p>
							<p class="date">발표일 : 2017.02.10</p>
						</a>
					</li>
					<li>
						<a href="#">
							<p class="subject">2017년 신년 이벤트</p>
							<p class="period">기간 : 2017.01.20 00시 ~ 2017.02.01 23시</p>
							<p class="date">발표일 : 2017.02.10</p>
						</a>
					</li>
				</ul><!-- //.event_win -->
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
			</div>
			<!-- //당첨자발표 -->
		</div><!-- //.event_tab -->

	</section><!-- //.promotion -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
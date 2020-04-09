<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>이벤트 참여현황</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="mypage_event">
		<div class="check_period">
			<ul>
				<li class="on"><a href="javascript:;">1개월</a></li><!-- [D] 해당 조회기간일때 .on 클래스 추가 -->
				<li><a href="javascript:;">3개월</a></li>
				<li><a href="javascript:;">6개월</a></li>
				<li><a href="javascript:;">12개월</a></li>
			</ul>
		</div><!-- //.check_period -->

		<div class="list_point"><!-- [D] 5개 페이징 -->
			<ul>
				<li>
					<p class="point_name"><a href="#">2017년 신년 이벤트 <span class="date point-color">미발표</span></a></p><!-- [D] 이벤트명, 발표, 미발표 클릭시 해당 이벤트 페이지로 이동 -->
					<p class="light">기간 : 2017.01.20 00시 ~ 2017.02.01 23시</p>
					<p>발표일 : 2017.02.10</p>
				</li>
				<li>
					<p class="point_name"><a href="#">2017년 신년 이벤트 <span class="date point-color">미발표</span></a></p>
					<p class="light">기간 : 2017.01.20 00시 ~ 2017.02.01 23시</p>
					<p>발표일 : 2017.02.10</p>
				</li>
				<li>
					<p class="point_name"><a href="#">2017년 신년 이벤트 <span class="date">발표</span></a></p>
					<p class="light">기간 : 2017.01.20 00시 ~ 2017.02.01 23시</p>
					<p>발표일 : 2017.02.10</p>
				</li>
				<li>
					<p class="point_name"><a href="#">2017년 신년 이벤트 <span class="date">발표</span></a></p>
					<p class="light">기간 : 2017.01.20 00시 ~ 2017.02.01 23시</p>
					<p>발표일 : 2017.02.10</p>
				</li>
				<li>
					<p class="point_name"><a href="#">2017년 신년 이벤트 <span class="date">발표</span></a></p>
					<p class="light">기간 : 2017.01.20 00시 ~ 2017.02.01 23시</p>
					<p>발표일 : 2017.02.10</p>
				</li>
			</ul>
		</div><!-- //.list_point -->
		
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
		</div><!-- //.list-paginate -->
	</section><!-- //.mypage_event -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
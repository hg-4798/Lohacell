<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>포인트</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="mypage_point sub_bdtop">

		<div class="mypoint">
			<div class="lv_point">
				<p class="mylv"><strong>권영은</strong>님의 회원등급 <strong class="level">BRONZE</strong></p>
				<p class="msg">등급업 필요 포인트 <strong>1,000P</strong></p>
			</div>
			<div class="point_now mt-15">
				<ul class="clear">
					<li>
						<span class="icon">P</span>
						<p class="mt-5">현재 통합 포인트</p>
						<p class="point-color"><strong>2,000P</strong></p>
					</li>
					<li>
						<span class="icon">E</span>
						<p class="mt-5">현재 E포인트</p>
						<p class="point-color"><strong>2,000P</strong></p>
					</li>
				</ul>
			</div>
			<div class="point_info">
				<p class="notice">※ 온라인 회원등급은 통합포인트 기준</p>
				<ul>
					<li>통합포인트: 오프라인 매장, 신원몰에서 모두 사용이 가능한 통합포인트</li>
					<li>E포인트: 신원몰에서만 사용이 가능한 온라인 전용 포인트</li>
				</ul>
			</div>
		</div><!-- //.mypoint -->
		
		<div class="point_tab tab_type1" data-ui="TabMenu">
			<div class="tab-menu clear">
				<a data-content="menu" class="active" title="선택됨">통합포인트</a>
				<a data-content="menu">E포인트</a>
			</div>

			<!-- 통합포인트 -->
			<div class="tab-content active" data-content="content">
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
							<p class="point_name">로그인 포인트 <span class="date">2017.01.14</span></p>
							<p class="">적립 포인트 <span class="blk">10P</span> <span class="bar">|</span> 사용 포인트 <span class="blk">10P</span></p>
						</li>
						<li>
							<p class="point_name">로그인 포인트 <span class="date">2017.01.14</span></p>
							<p class="">적립 포인트 <span class="blk">10P</span> <span class="bar">|</span> 사용 포인트 <span class="blk">10P</span></p>
						</li>
						<li>
							<p class="point_name">로그인 포인트 <span class="date">2017.01.14</span></p>
							<p class="">적립 포인트 <span class="blk">10P</span> <span class="bar">|</span> 사용 포인트 <span class="blk">10P</span></p>
						</li>
						<li>
							<p class="point_name">로그인 포인트 <span class="date">2017.01.14</span></p>
							<p class="">적립 포인트 <span class="blk">10P</span> <span class="bar">|</span> 사용 포인트 <span class="blk">10P</span></p>
						</li>
						<li>
							<p class="point_name">로그인 포인트 <span class="date">2017.01.14</span></p>
							<p class="">적립 포인트 <span class="blk">10P</span> <span class="bar">|</span> 사용 포인트 <span class="blk">10P</span></p>
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
			</div>
			<!-- //통합포인트 -->

			<!-- E포인트 -->
			<div class="tab-content" data-content="content"><!-- [D] 통합포인트와 구성 동일 -->
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
							<p class="point_name">구매 적립 포인트 <span class="date">2017.01.14</span></p>
							<p class="">적립 포인트 <span class="blk">10P</span> <span class="bar">|</span> 사용 포인트 <span class="blk">10P</span></p>
						</li>
						<li>
							<p class="point_name">로그인 포인트 <span class="date">2017.01.14</span></p>
							<p class="">적립 포인트 <span class="blk">10P</span> <span class="bar">|</span> 사용 포인트 <span class="blk">10P</span></p>
						</li>
						<li>
							<p class="point_name">로그인 포인트 <span class="date">2017.01.14</span></p>
							<p class="">적립 포인트 <span class="blk">10P</span> <span class="bar">|</span> 사용 포인트 <span class="blk">10P</span></p>
						</li>
						<li>
							<p class="point_name">로그인 포인트 <span class="date">2017.01.14</span></p>
							<p class="">적립 포인트 <span class="blk">10P</span> <span class="bar">|</span> 사용 포인트 <span class="blk">10P</span></p>
						</li>
						<li>
							<p class="point_name">로그인 포인트 <span class="date">2017.01.14</span></p>
							<p class="">적립 포인트 <span class="blk">10P</span> <span class="bar">|</span> 사용 포인트 <span class="blk">10P</span></p>
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
			</div>
			<!-- //E포인트 -->
		</div><!-- //.point_tab -->

	</section><!-- //.mypage_point -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
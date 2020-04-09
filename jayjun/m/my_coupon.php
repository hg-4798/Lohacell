<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>쿠폰</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="mypage_coupon">

		<div class="coupon_regist">
			<p>발급 받으신 쿠폰을 등록해주세요.</p>
			<div class="input_addr mt-10">
				<input type="text" placeholder="16자리 숫자만 입력">
				<div class="btn_addr"><a href="javascript:;" class="btn-point h-input">쿠폰등록</a></div>
			</div>
		</div><!-- //.coupon_regist -->

		<div class="check_period mt-15">
			<ul>
				<li class="on"><a href="javascript:;">1개월</a></li><!-- [D] 해당 조회기간일때 .on 클래스 추가 -->
				<li><a href="javascript:;">3개월</a></li>
				<li><a href="javascript:;">6개월</a></li>
				<li><a href="javascript:;">12개월</a></li>
			</ul>
		</div><!-- //.check_period -->
		
		<div class="list_coupon"><!-- [D] 5개 페이징 -->
			<ul>
				<li>
					<div class="coupon_info">
						<div class="coupon_num">
							<span class="tit">쿠폰번호</span>
							<span>28011111</span>
							<span class="status point-color">사용가능</span>
						</div>
						<p class="period">2017.01.20 00시 ~ 2017.02.01 23시</p>
						<p class="name">회원가입 쿠폰</p>
						<button type="button" class="target">적용대상: 카테고리 [티셔츠] 외 1건</button>
					</div>
					<div class="target_more">
						<p>티셔츠</p>
						<p>블라우스</p>
					</div>
				</li>
				<li>
					<div class="coupon_info">
						<div class="coupon_num">
							<span class="tit">쿠폰번호</span>
							<span>28011111</span>
							<span class="status point-color">사용가능</span>
						</div>
						<p class="period">2017.01.20 00시 ~ 2017.02.01 23시</p>
						<p class="name">회원가입 쿠폰</p>
						<button type="button" class="target">적용대상: 카테고리 [티셔츠] 외 1건</button>
					</div>
					<div class="target_more">
						<p>티셔츠</p>
						<p>블라우스</p>
					</div>
				</li>
				<li>
					<div class="coupon_info">
						<div class="coupon_num">
							<span class="tit">쿠폰번호</span>
							<span>28011111</span>
							<span class="status">사용불가</span>
						</div>
						<p class="period">2017.01.20 00시 ~ 2017.02.01 23시</p>
						<p class="name">회원가입 쿠폰</p>
						<button type="button" class="target">적용대상: 카테고리 [티셔츠] 외 1건</button>
					</div>
					<div class="target_more">
						<p>티셔츠</p>
						<p>블라우스</p>
					</div>
				</li>
				<li>
					<div class="coupon_info">
						<div class="coupon_num">
							<span class="tit">쿠폰번호</span>
							<span>28011111</span>
							<span class="status">사용불가</span>
						</div>
						<p class="period">2017.01.20 00시 ~ 2017.02.01 23시</p>
						<p class="name">회원가입 쿠폰</p>
						<button type="button" class="target">적용대상: 카테고리 [티셔츠] 외 1건</button>
					</div>
					<div class="target_more">
						<p>티셔츠</p>
						<p>블라우스</p>
					</div>
				</li>
				<li>
					<div class="coupon_info">
						<div class="coupon_num">
							<span class="tit">쿠폰번호</span>
							<span>28011111</span>
							<span class="status">사용불가</span>
						</div>
						<p class="period">2017.01.20 00시 ~ 2017.02.01 23시</p>
						<p class="name">회원가입 쿠폰</p>
						<button type="button" class="target">적용대상: 카테고리 [티셔츠] 외 1건</button>
					</div>
					<div class="target_more">
						<p>티셔츠</p>
						<p>블라우스</p>
					</div>
				</li>
			</ul>
		</div><!-- //.list_coupon -->

		<div class="list-paginate mt-10">
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

	</section><!-- //.mypage_coupon -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
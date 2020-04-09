<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>회원가입</span>
		</h2>
		<div class="page_step join_step">
			<ul class="ea4 clear">
				<li><span class="icon_join_step01"></span>본인인증</li>
				<li><span class="icon_join_step02"></span>약관동의</li>
				<li><span class="icon_join_step03"></span>정보입력</li>
				<li class="on"><span class="icon_join_step04"></span>가입완료</li>
			</ul>
		</div>
	</section><!-- //.page_local -->

	<section class="joinpage">

		<div class="end_msg">
			<p>신원몰 회원가입이 완료되었습니다.</p>
			<div class="btn_area">
				<ul class="ea2">
					<li><a href="#" class="btn-line h-input">멤버쉽 안내</a></li>
					<li><a href="login.php" class="btn-point h-input">로그인</a></li>
				</ul>
			</div>
		</div>

		<div class="benefit">
			<h3>신원몰 회원이 누릴 수 있는 혜택!</h3>
			<div class="coupon">
				<div class="coupon_img">
					<div class="inner">
						<p><strong>10,000 P</strong></p>
						<p class="mt-5">회원 가입시</p>
					</div>
				</div><!-- //.coupon_img -->
				<div class="coupon_img ml-25">
					<div class="inner">
						<p><strong>10% 할인</strong></p>
						<p class="mt-5">회원 가입시</p>
					</div>
				</div><!-- //.coupon_img -->
			</div>
			<ul class="etc clear">
				<li>
					<div class="con">
						<div class="icon"><img src="static/img/icon/icon_bnf_event.png" alt=""></div>
						<div class="txt">
							<p>회원 대상 상시<br>이벤트 진행</p>
						</div>
					</div>
				</li>
				<li>
					<div class="con">
						<div class="icon"><img src="static/img/icon/icon_bnf_mem.png" alt=""></div>
						<div class="txt">
							<p>등급별 멤버쉽 운영</p>
						</div>
					</div>
				</li>
				<li>
					<div class="con">
						<div class="icon"><img src="static/img/icon/icon_bnf_point.png" alt=""></div>
						<div class="txt">
							<p>상품 구매 시 포인트 적립<br>및 사용 가능</p>
						</div>
					</div>
				</li>
				<li>
					<div class="con">
						<div class="icon"><img src="static/img/icon/icon_bnf_coupon.png" alt=""></div>
						<div class="txt">
							<p>다양한 쿠폰 증정</p>
						</div>
					</div>
				</li>
			</ul>
		</div>

	</section><!-- //.joinpage -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
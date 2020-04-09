<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="member-page">

		<article class="memberLogin-wrap">
			<header class="login-title"><h2>로그인</h2></header>
			<div class="frm-box mt-50 with-inner">
				<div class="inner pl-80 pr-80">
					<section>
						<header><h3 class="title">로그인</h3></header>
						<form class="login-reg mt-20">
							<fieldset>
								<legend>회원 로그인폼</legend>
								<input type="text" class="w100-per" title="아이디 입력자리" placeholder="아이디 입력">
								<input type="password" class="w100-per mt-10" title="비밀번호 입력자리" placeholder="비밀번호 입력">
								<div class="mt-10">
									<div class="checkbox">
										<input type="checkbox" id="save_id">
										<label for="save_id">아이디 저장</label>
									</div>
								</div>
								<div class="mt-10"><button class="btn-point w100-per h-large" type="submit"><span>로그인</span></button></div>
							</fieldset>
						</form>
					</section>
					<section class="mt-40">
						<header><h3 class="title">간편 로그인</h3></header>
						<ul class="easy-login mt-15 clear">
							<li><a href="#"><i class="icon-snsLogin-naver"></i>네이버</a></li>
							<li><a href="#"><i class="icon-snsLogin-katalk"></i>카카오톡</a></li>
							<li><a href="#"><i class="icon-snsLogin-facebook"></i>페이스북</a></li>
						</ul>
						<div class="login-link mt-20">
							<a href="#">아이디 찾기</a>
							<a href="#">비밀번호 찾기</a>
							<a href="#">비회원 주문조회</a>
						</div>
					</section>
				</div>
				<div class="inner pl-80 pr-80">
					<section class="join-benefit total">
						<h4 class="v-hidden">통합회원전환 안내</h4>
						<dl>
							<dt class="title">통합몰 회원전환</dt>
							<dd>신원 오프라인 매장의 회원이세요?</dd>
							<dd>신원통합몰 회원으로 전환시 <strong class="point-color">20,000 E포인트를</strong>즉시 증정합니다.</dd>
						</dl>
						<a href="#" class="btn-basic w100-per h-large">신원 통합회원 전환하기</a>
					</section>
					<section class="join-benefit mt-25">
						<h4 class="v-hidden">신원몰 회원시 헤택안내</h4>
						<dl class="clear">
							<dt class="title">신원몰 회원이 누릴 수 있는 혜택!</dt>
							<dd>- 회원 대상 상시 이벤트 진행</dd>
							<dd>- 등급별 멤버쉽 운영</dd>
							<dd>- 상품 구매 시 포인트 적립 및 사용 가능</dd>
							<dd>- 회원 가입시 10,000포인트 증정</dd>
							<dd>- 다양한 쿠폰 증정</dd>
							<dd>- 회원 가입시 10%할인 쿠폰 증정</dd>
						</dl>
						<a href="#" class="btn-point w100-per h-large mt-15">회원가입</a>
					</section>
					<section class="mt-30">
						<header><h3 class="title">간편 회원가입</h3></header>
						<ul class="easy-login mt-15 clear">
							<li><a href="#"><i class="icon-snsLogin-naver"></i>네이버</a></li>
							<li><a href="#"><i class="icon-snsLogin-katalk"></i>카카오톡</a></li>
							<li><a href="#"><i class="icon-snsLogin-facebook"></i>페이스북</a></li>
						</ul>
						<p class="mt-20 fz-13">※ 간편 회원가입 후 간편 로그인이 가능합니다.</p>
					</section>
				</div>
			</div>
		</article>

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.btack();" class="prev">이전페이지</a>
			<span>휴면계정 안내</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="wrap_inactive sub_bdtop">
		<div class="notice">
			<p class="tit">회원님의 계정은 현재 휴면 상태입니다.</p>
			<p class="txt">개인정보 보호를 위해 1년 이상 구매 또는 로그인 이력이<br> 없으신 회원님의 개인정보는 별도 보관 처리 됩니다. <br>로그인을 원하실 경우, 아래 본인 인증을 통해<br> 정상적인 서비스 이용이 가능 하십니다.</p>
		</div>

		<div class="inactive_certi">
			<div class="certification">
				<div class="icon certi_phone"><img src="static/img/icon/icon_certi_phone.png" alt="휴대폰 인증"></div>
				<div class="info mt-10">
					<p>휴대폰을 이용하여 본인인증을 진행합니다.</p>
					<a href="javascript:;" class="btn-point">휴대폰 인증</a>
				</div>
			</div>
			<div class="certification">
				<div class="icon certi_ipin"><img src="static/img/icon/icon_certi_ipin.png" alt="아이핀 인증"></div>
				<div class="info">
					<p>아이핀으로 인증하여 본인인증을 진행합니다.</p>
					<a href="javascript:;" class="btn-point">아이핀 인증</a>
				</div>
			</div>
		</div>

		<div class="info">
			<p class="tit">휴면계정이란?</p>
			<p class="txt">사업자의 서비스를 1년 이상 이용하지 않는 회원의 개인정보를 휴면 처리 (별도 분리 보관 또는 삭제) 하여 개인정보 유출 위험성을 최소화 하는 제도 입니다.</p>
		</div>
		
	</section><!-- //.loginpage -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
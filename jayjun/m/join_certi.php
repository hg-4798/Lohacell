<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage with_bg">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>회원가입</span>
		</h2>
		<div class="page_step join_step">
			<ul class="ea4 clear">
				<li class="on"><span class="icon_join_step01"></span>본인인증</li>
				<li><span class="icon_join_step02"></span>약관동의</li>
				<li><span class="icon_join_step03"></span>정보입력</li>
				<li><span class="icon_join_step04"></span>가입완료</li>
			</ul>
		</div>
	</section><!-- //.page_local -->

	<section class="joinpage">

		<div class="certi_notice">
			고객님의 개인정보 보호를 위해 본인인증을 해주세요.<br>휴대폰 인증 및 아이핀 인증이 가능합니다.
		</div>
		
		<div class="mt-25 pb-5">
			<div class="certification">
				<div class="icon certi_phone"><img src="static/img/icon/icon_certi_phone.png" alt="휴대폰 인증"></div>
				<div class="info">
					<p>본인명의의 휴대폰 번호로 인증하여 회원가입을 진행합니다. 타인명의/법인 휴대폰 회원님은 본인인증이 불가합니다.</p>
					<a href="javascript:;" class="btn-point">휴대폰 인증</a>
				</div>
			</div>
			<div class="certification">
				<div class="icon certi_ipin"><img src="static/img/icon/icon_certi_ipin.png" alt="아이핀 인증"></div>
				<div class="info">
					<p>아이핀으로 인증하여 회원가입을 진행합니다.</p>
					<a href="javascript:;" class="btn-point">아이핀 인증</a>
				</div>
			</div>
		</div>

	</section><!-- //.joinpage -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
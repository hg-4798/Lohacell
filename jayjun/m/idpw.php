<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage with_bg">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>아이디/비밀번호 찾기</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="loginpage sub_bdtop">
		
		<div class="idpw_tab tab_type1" data-ui="TabMenu">
			<div class="tab-menu clear">
				<a data-content="menu" class="active" title="선택됨">아이디 찾기</a>
				<a data-content="menu">비밀번호 찾기</a>
			</div>

			<!-- 아이디 찾기 -->
			<div class="tab-content active" data-content="content">
				<!-- 찾기 -->
				<div class="certification">
					<div class="icon certi_phone"><img src="static/img/icon/icon_certi_phone.png" alt="휴대폰 인증"></div>
					<div class="info">
						<p>본인명의의 휴대폰 번호로 가입여부 및 본인여부를 확인합니다. 타인명의/법인 휴대폰 회원님은 본인인증이 불가합니다.</p>
						<a href="javascript:;" class="btn-point">휴대폰 인증</a>
					</div>
				</div>
				<div class="certification">
					<div class="icon certi_ipin"><img src="static/img/icon/icon_certi_ipin.png" alt="아이핀 인증"></div>
					<div class="info">
						<p>회원가입시 아이핀으로 가입한 경우 본인여부 확인이 가능합니다.</p>
						<a href="javascript:;" class="btn-point">아이핀 인증</a>
					</div>
				</div>
				<!-- //찾기 -->
			</div>
			<!-- //아이디 찾기 -->

			<!-- 비밀번호 찾기 -->
			<div class="tab-content" data-content="content">
				<!-- 찾기 -->
				<div class="certification">
					<div class="icon certi_phone"><img src="static/img/icon/icon_certi_phone.png" alt="휴대폰 인증"></div>
					<div class="info">
						<p>본인명의의 휴대폰 번호로 가입여부 및 본인여부를 확인합니다. 타인명의/법인 휴대폰 회원님은 본인인증이 불가합니다.</p>
						<a href="javascript:;" class="btn-point">휴대폰 인증</a>
					</div>
				</div>
				<div class="certification">
					<div class="icon certi_ipin"><img src="static/img/icon/icon_certi_ipin.png" alt="아이핀 인증"></div>
					<div class="info">
						<p>회원가입시 아이핀으로 가입한 경우 본인여부 확인이 가능합니다.</p>
						<a href="javascript:;" class="btn-point">아이핀 인증</a>
					</div>
				</div>
				<!-- //찾기 -->
			</div>
			<!-- //비밀번호 찾기 -->
		</div>

	</section><!-- //.loginpage -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
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
				<!-- 결과 -->
				<div class="result result_id">
					<p class="msg">회원님의 아이디는 <strong class="point-color">fwohweo***</strong>입니다.</p>
					<a href="login.php" class="btn-point w100-per h-input">로그인</a>
				</div>
				<!-- //결과 -->
			</div>
			<!-- //아이디 찾기 -->

			<!-- 비밀번호 찾기 -->
			<div class="tab-content" data-content="content">
				<!-- 결과 -->
				<div class="result result_pw">
					<!-- <p class="msg">아이디 <strong class="point-color">fwohweo***</strong></p> -->
					<div class="login_area mt-15">
						<input type="text" class="w100-per" placeholder="아이디 입력">
						<input type="password" class="w100-per" placeholder="신규 비밀번호 (영문, 숫자 포함 8~20자리)">
						<input type="password" class="w100-per" placeholder="비밀번호 재입력">
						<a href="javascript:;" class="btn-point w100-per h-input">로그인</a>
					</div>
				</div>
				<!-- //결과 -->
			</div>
			<!-- //비밀번호 찾기 -->
		</div>

	</section><!-- //.loginpage -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
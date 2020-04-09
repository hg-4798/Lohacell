<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage with_bg">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>로그인</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="loginpage sub_bdtop">

		<div class="login_area">
			<input type="text" class="w100-per" placeholder="아이디 입력">
			<input type="password" class="w100-per" placeholder="비밀번호 입력">
			<label><input type="checkbox" class="check_def"> <span>아이디 저장</span></label>
			<a href="javascript:;" class="btn-point w100-per h-input">로그인</a>
			<a href="javascript:;" class="btn-line w100-per h-input mt-10">비회원 구매하기</a>
		</div><!-- //.login_area -->

		<div class="login_simple">
			<p class="tit">간편 로그인</p>
			<div class="btn_area">
				<ul class="ea3">
					<li><a href="javascript:;" class="btn-line"><img src="static/img/icon/icon_naver.png" alt="네이버"><span class="naver">네이버</span></a></li>
					<li><a href="javascript:;" class="btn-line"><img src="static/img/icon/icon_kakao.png" alt="카카오톡"><span class="kakao">카카오톡</span></a></li>
					<li><a href="javascript:;" class="btn-line"><img src="static/img/icon/icon_facebook.png" alt="페이스북"><span class="facebook">페이스북</span></a></li>
				</ul>
			</div>
		</div><!-- //.login_simple -->

		<div class="mem_menu">
			<ul>
				<li><a href="#">아이디 찾기</a></li>
				<li><a href="#">비밀번호 찾기</a></li>
				<li><a href="nonmember.php">비회원 주문조회</a></li>
			</ul>
		</div><!-- //.mem_menu -->

		<div class="join_yet">
			<p class="ment">아직 신원몰 회원이 아니신가요? <a href="#">신원몰 멤버쉽 안내</a></p>
			<a href="#" class="btn-point point2 w100-per h-input">회원가입</a>
		</div><!-- //.join_yet -->

		<div class="join_yet mt-30 pt-20">
			<p class="tit">통합회원 전환</p>
			<p class="ment">신원 오프라인 매장의 회원이세요?<br>신원 통합회원으로 전환시 <strong class="point-color">20,000 E포인트</strong>를 즉시 증정합니다.</p>
			<a href="#" class="btn-point w100-per h-input">신원 통합회원 전환하기</a>
		</div><!-- //.join_yet -->

	</section><!-- //.loginpage -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
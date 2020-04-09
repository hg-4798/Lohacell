<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage with_bg">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>비회원 주문조회</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="loginpage sub_bdtop">

		<div class="login_area">
			<input type="text" class="w100-per" placeholder="이름 입력">
			<input type="text" class="w100-per" placeholder="주문번호 입력">
			<a href="javascript:;" class="btn-point w100-per h-input">비회원 주문조회</a>
		</div><!-- //.login_area -->

		<div class="mem_menu">
			<ul>
				<li><a href="idpw.php">아이디 찾기</a></li>
				<li><a href="idpw.php">비밀번호 찾기</a></li>
				<li><a href="login.php">로그인</a></li>
			</ul>
		</div>

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
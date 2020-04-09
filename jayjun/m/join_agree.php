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
				<li><span class="icon_join_step01"></span>본인인증</li>
				<li class="on"><span class="icon_join_step02"></span>약관동의</li>
				<li><span class="icon_join_step03"></span>정보입력</li>
				<li><span class="icon_join_step04"></span>가입완료</li>
			</ul>
		</div>
	</section><!-- //.page_local -->

	<section class="joinpage join_form">

		<div class="agree_form">
			<h3 class="tit">신원몰 서비스 이용약관(필수)</h3>
			<textarea>서비스 이용약관 내용</textarea>
			<label><input type="checkbox" class="check_def"> <span>약관에 동의합니다.</span></label>
		</div><!-- //.agree_form -->

		<div class="agree_form mt-25">
			<h3 class="tit">개인정보취급방침(필수)</h3>
			<textarea>개인정보취급방침 내용</textarea>
			<label><input type="checkbox" class="check_def"> <span>개인정보취급방침에 동의합니다.</span></label>
		</div><!-- //.agree_form -->

		<div class="all_agree">
			<label><input type="checkbox" id="checkAll" class="check_def"> <span>사이트 이용을 위한 모든 약관에 동의합니다.</span></label>
			<div class="btn_area mt-20">
				<ul class="ea2">
					<li><a href="javascript:;" class="btn-line h-input">취소</a></li>
					<li><a href="javascript:;" class="btn-point h-input">다음</a></li>
				</ul>
			</div>
		</div>

	</section><!-- //.joinpage -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
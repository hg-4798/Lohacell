<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage with_bg">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>회원정보 수정</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="my_modify sub_bdtop">
		<div class="my_modify_pw">
			<p>본인 확인을 위해 비밀번호를 입력해주세요.</p>
			<input type="password" class="w100-per mt-25" placeholder="비밀번호 입력">
			<div class="btn_area mt-15">
				<ul>
					<li><a href="javascript:;" class="btn-point h-input">확인</a></li>
				</ul>
			</div>
		</div>
	</section><!-- //.my_modify -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
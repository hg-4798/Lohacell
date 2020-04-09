<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage with_bg">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>회원탈퇴</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="my_withdrawal sub_bdtop">
		<div class="attn">
			<p class="tit">신원몰 회원탈퇴 유의사항</p>
			<ul class="mt-5">
				<li>- 회원탈퇴시 적립된 포인트 및 쿠폰정보는 모두 소멸됩니다.</li>
				<li>- 회원탈퇴시 오프라인 전용 쿠폰 및 마일리지 역시 함께 삭제처리 됩니다</li>
				<li>- 동일 아이디로 재가입이 불가능합니다.</li>
			</ul>
		</div>

		<div class="my_modify_pw">
			<p>회원탈퇴를 위해 비밀번호를 입력해주세요.</p>
			<input type="password" class="w100-per mt-25" placeholder="비밀번호 입력">
			<input type="password" class="w100-per mt-5" placeholder="비밀번호 재입력">
			<div class="btn_area mt-15">
				<ul>
					<li><a href="javascript:;" class="btn-point h-input">확인</a></li>
				</ul>
			</div>
		</div>

	</section><!-- //.my_withdrawal -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">회원탈퇴</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				
				<div class="gray-box">
					<div class="member-out">
						<dl>
							<dt>신원몰 회원탈퇴 유의사항</dt>
							<dd>- 적립된 포인트 및 쿠폰정보는 모두 소멸됩니다.</dd>
							<dd>- 회원탈퇴시 오프라인 전용 쿠폰 및 마일리지 역시 함께 삭제처리 됩니다.</dd>
							<dd>- 동일 아이디로 재가입이 불가능합니다.</dd>
						</dl>
					</div>
					<fieldset class="pw-checkForm">
						<legend>회원탈퇴를 위한 비밀번호 확인</legend>
						<p class="att">회원탈퇴를 위해 비밀번호를 입력해주세요.</p>
						<input type="password" title="비밀번호 입력자리" placeholder="비밀번호 입력">
						<input type="password" title="비밀번호 재 입력자리" placeholder="비밀번호 재입력">
						<button class="btn-point w100-per h-large" type="submit"><span>확인</span></button>
					</fieldset>
				</div>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
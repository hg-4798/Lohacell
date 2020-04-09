<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">회원정보 수정</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				
				<div class="gray-box">
					<div class="inner-vm">
						<fieldset class="pw-checkForm">
							<legend>본인확인을 위한 비밀번호 입력</legend>
							<p class="att">본인 확인을 위해 비밀번호를 입력해주세요.</p>
							<input type="password" title="비밀번호 입력자리" placeholder="비밀번호 입력">
							<button class="btn-point w100-per h-large" type="submit"><span>확인</span></button>
						</fieldset>
					</div>
				</div>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
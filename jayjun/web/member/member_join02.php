<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="member-page">

		<article class="memberJoin-wrap">
			<header class="join-title">
				<h2>회원가입</h2>
				<ul class="flow clear">
					<li><div><i></i><span>STEP 1</span>본인인증</div></li>
					<li class="active"><div><i></i><span>STEP 2</span>약관동의</div></li>
					<li><div><i></i><span>STEP 3</span>정보입력</div></li>
					<li><div><i></i><span>STEP 4</span>가입완료</div></li>
				</ul>
			</header>
			<section class="inner-align join-agreement">
				<header class="sub-title">
					<h3>신원몰 서비스 약관동의</h3>
				</header>
				<div class="wrap">
					<h4 class="title">서비스 이용약관(필수)</h4>
					<div class="terms-box">인터넷 쇼핑몰 『 (주) 신원 사이버 몰』회원 약관</div>
					<div class="checkbox mt-15">
						<input type="checkbox" id="agreement-A">
						<label for="agreement-A">약관에 동의합니다.</label>
					</div>
				</div>
				<div class="wrap">
					<h4 class="title">개인정보취급방침(필수)</h4>
					<div class="terms-box">인터넷 쇼핑몰 『 (주) 신원 사이버 몰』회원 약관</div>
					<div class="checkbox mt-15">
						<input type="checkbox" id="agreement-B">
						<label for="agreement-B">개인정보취급방침에 동의합니다.</label>
					</div>
				</div>
				<div class="all-agree mt-15">
					<div class="checkbox">
						<input type="checkbox" id="agreement-All">
						<label for="agreement-All">사이트 이용을 위한 모든 약관에 동의합니다.</label>
					</div>
				</div>
				<div class="btnPlace mt-35">
					<a href="" class="btn-line h-large">취소</a>
					<a href="" class="btn-point h-large">다음</a>
				</div>
			</section>
			
		</article>

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
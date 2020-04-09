<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage with_bg">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>신원 통합회원 전환</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="joinpage mem_switch sub_bdtop">
		<p class="info_msg">기존 신원 브랜드의 오프라인 매장 회원님은<br> 손쉽게 신원 통합회원으로 전환이 가능합니다.</p>

		<div class="agree_form mt-20">
			<h3 class="tit">개인정보 제공 동의 약관</h3>
			<textarea>개인정보 제공 동의 약관 내용</textarea>
			<label><input type="checkbox" class="check_def"> <span>약관에 동의합니다.</span></label>
		</div>

		<div class="login_area">
			<input type="text" class="w100-per" placeholder="이름 입력">
			<input type="text" class="w100-per" placeholder="휴대폰 번호 입력">
			<a href="javascript:;" class="btn-point w100-per h-input">신원 오프라인 매장 회원정보 확인</a>
		</div>

		<hr class="line_basic mt-25">

		<div class="agree_form mt-35">
			<h3 class="tit">해당 브랜드</h3>
			<ul class="integrated_brand clear mt-5">
				<li><span><img src="static/img/common/logo_standard_besti.png" alt="BESTI BELLI"></span></li>
				<li><span><img src="static/img/common/logo_standard_viki.png" alt="VIKI"></span></li>
				<li><span><img src="static/img/common/logo_standard_si.png" alt="SI"></span></li>
				<li><span><img src="static/img/common/logo_standard_isabey.png" alt="ISABEY"></span></li>
				<li><span><img src="static/img/common/logo_standard_sieg.png" alt="SIEG"></span></li>
				<li><span><img src="static/img/common/logo_standard_siegf.png" alt="SIEG FAHRENHEIT"></span></li>
				<li><span><img src="static/img/common/logo_standard_vanhart.png" alt="VanHart di Albazar"></span></li>
			</ul>
		</div>
		
		<div class="agree_form mt-25">
			<h3 class="tit">신원 통합회원 전환시 받을 수 있는 혜택</h3>
			<ul class="switch_benefit clear mt-5">
				<li>
					<div class="con">
						<div class="icon"><img src="static/img/icon/icon_bnf_epoint.png" alt="포인트 아이콘"></div>
						<div class="txt">
							<p>20,000 E포인트</p>
						</div>
					</div>
				</li>
				<li>
					<div class="con">
						<div class="icon"><img src="static/img/icon/icon_bnf_coupon.png" alt="쿠폰 아이콘"></div>
						<div class="txt">
							<p>10% 할인 쿠폰</p>
						</div>
					</div>
				</li>
				<li>
					<div class="con">
						<div class="icon"><img src="static/img/icon/icon_bnf_epoint.png" alt="포인트 아이콘"></div>
						<div class="txt">
							<p>상품 구매시<br> 포인트 적립</p>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</section><!-- //.joinpage -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
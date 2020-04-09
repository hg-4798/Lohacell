<?php
$Dir="../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include_once($Dir."lib/shopdata.php");

include('./include/top.php');

include('./include/gnb.php');
?>

<!-- 내용 -->
<div id="page">
<main id="content" class="subpage">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>멤버십 등급/혜택안내</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="my_membership sub_bdtop">
		<!-- <div class="info_msg">
			<h3><?=$_data->shopname?> 통합 멤버십 안내</h3>
			<p class="txt"><?=$_data->shopname?> 통합 멤버십 회원이 되시면 온라인 공식몰과 오프라인 매장에서 상품 구매시 사용할 수 있는 다양한 쿠폰 및 포인트를 제공합니다.</p>
		</div> -->
		
		<div class="membership_bnf hide mt-30">
			<h3 class="tit">멤버십 적립율 안내</h3>
			<table class="th-top mt-10">
				<colgroup>
					<col style="width:36.25%;">
					<col style="width:auto;">
				</colgroup>
				<thead>
					<tr>
						<th colspan="2">상품 할인률에 따른 적립율(TAG가 기준)</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th rowspan="3" class="bdr ">
							<div class="brand_logo">
								<img src="../static/img/common/logo_brand_si.png" alt="SI">
								<img src="../static/img/common/logo_brand_viki.png" alt="VIKI">
								<img src="../static/img/common/logo_brand_besti.png" alt="BESTI BELLI">
								<img src="../static/img/common/logo_brand_isabey.png" alt="ISABEY">
								<img src="../static/img/common/logo_brand_sieg.png" alt="SIEG">
								<img src="../static/img/common/logo_brand_siegf.png" alt="SIEG FAHRENHEIT">
							</div>
						</th>
						<td>
							<strong>상품 할인률 0~19%</strong><br>
							기존 2% 적립 / ‘17년 변경 5% 적립
						</td>
					</tr>
					<tr>
						<td>
							<strong>상품 할인률 20~49%</strong><br>
							기존 2% 적립 / ‘17년 변경 3% 적립
						</td>
					</tr>
					<tr>
						<td class="">
							<strong>상품 할인률 50% 이상</strong><br>
							기존 2% 적립 / ‘17년 변경 1% 적립
						</td>
					</tr>
					<tr>
						<th rowspan="2" class="bdr">
							<div class="brand_logo"><img src="../static/img/common/logo_brand_vanhart.png" alt="VanHart di Albazar"></div>
						</th>
						<td>
							<strong>상품 할인률 0~19%</strong><br>
							기존 2% 적립 / ‘17년 변경 5% 적립
						</td>
					</tr>
					<tr>
						<td>
							<strong>상품 할인률 11% 이상</strong><br>
							기존 2% 적립 / ‘17년 변경 2% 적립
						</td>
					</tr>
				</tbody>
			</table>
			<ul class="ment">
				<li>※ 적립율은 프로모션 및 운영상의 이유로 변경 될 수 있습니다.</li>
				<li>※ 상품 구매 시 적립되는 포인트는 통합포인트입니다.</li>
			</ul>
		</div><!-- //.membership_bnf -->

		<div class="membership_bnf mt-30 ">
			<h3 class="tit">멤버십 등급 및 혜택</h3>
			<table class="th-top mt-10">
				<colgroup>
					<col style="width:25%;">
					<col style="width:20%;">
					<col style="width:auto;">
				</colgroup>
				<thead>
					<tr>
						<th class="bdr">등급</th>
						<th colspan="2">등급기준 및 혜택</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th rowspan="5" class="bdr "><img src="/jayjun/web/static/img/icon/icon_grade_vv.png" width="60" alt="VV 등급" ></th>
						<td colspan="2">50만원 이상 구매회원</td>
					</tr>
					<tr>
						<td class=" bg bdr">마일리지</td>
						<td class=" bg">7% 적립</td>
					</tr>
					<tr>
						<td class=" bg bdr">쿠폰</td>
						<td class=" bg">무료배송쿠폰<br>생일축하쿠폰 1매 [3,000원 할인]</td>
					</tr>
					<tr>
						<td class=" bg bdr">포인트</td>
						<td class=" bg">-</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<th rowspan="4" class="bdr "><img src="/jayjun/web/static/img/icon/icon_grade_v.png" width="60" alt="V 등급" ></th>
						<td colspan="2">20만원 이상 ~ 50만원 미만 구매 회원</td>
					</tr>
					<tr>
						<td class=" bg bdr">마일리지</td>
						<td class=" bg">5% 적립</td>
					</tr>
					<tr>
						<td class=" bg bdr">쿠폰</td>
						<td class=" bg">생일축하쿠폰 1매 [3,000원 할인]</td>
					</tr>
					<tr>
						<td class=" bg bdr">포인트</td>
						<td class=" bg">-</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<th rowspan="4" class="bdr "><img src="/jayjun/web/static/img/icon/icon_grade_f.png" width="60" alt="F 등급" ></th>
						<td colspan="2">6만원 이상 ~ 20만원 미만 구매 회원</td>
					</tr>
					<tr>
						<td class=" bg bdr">마일리지</td>
						<td class=" bg">3% 적립</td>
					</tr>
					<tr>
						<td class=" bg bdr">쿠폰</td>
						<td class=" bg">생일축하쿠폰 1매 [3,000원 할인]</td>
					</tr>
					<tr>
						<td class=" bg bdr">포인트</td>
						<td class=" bg">-</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<th rowspan="4" class="bdr "><img src="/jayjun/web/static/img/icon/icon_grade_n.png" width="60" alt="N 등급" ></th>
						<td colspan="2">신규가입 즉시 모든 회원</td>
					</tr>
					<tr>
						<td class=" bg bdr">마일리지</td>
						<td class=" bg">2% 적립</td>
					</tr>
					<tr>
						<td class=" bg bdr">쿠폰</td>
						<td class=" bg">-</td>
					</tr>
					<tr>
						<td class=" bg bdr">포인트</td>
						<td class=" bg">3,000원 지급 즉시 사용 가능</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.membership_bnf -->

		<div class="integrated_bnf mt-25">
			<h3>멤버십 유의사항</h3>
			<table class="th-left mt-10">
				<colgroup>
					<col style="width:25%;">
					<col style="width:auto;">
				</colgroup>
				<tbody>
					<tr>
						<th class="">공통</th>
						<td class="">
							<ul class="list">
								<li>멤버십 등급은 매월 1일 직전 1년의 실제 결제금액을 기준으로 자동 반영됩니다.</li>
								<li>적립금 결제금액 및 쿠폰 할인금액은 등급산정 금액에서 제외됩니다. </li>
								<li>멤버십 혜택은 가입 고객을 대상으로 구매 이력 확인 후 제공되므로 회원 가입 후 혜택을 받으실 수 있습니다.  </li>
							</ul>
						</td>
					</tr>
					<tr>
						<th class="">쿠폰</th>
						<td class="">
							<ul class="list">
								<li>생일축하쿠폰은 해당 월에 생일을 맞이하는 고객님들께 매월 1일 자동 발급됩니다. (영업일 기준, 휴무일의 경우 익일 발행)</li>
								<li>생일축하쿠폰은 발행일로부터 한달 이내 사용이 가능합니다. </li>
								<li>공식몰에서 발행된 쿠폰은 공식몰에서만 사용 가능하며, 직영 매장에서의 사용이 불가합니다.</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th class="">마일리지</th>
						<td class="">
							<ul class="list">
								<li>회원 혜택 및 행사 안내는 SMS 수신 동의 회원에 한해서 발송됩니다.</li>
								<li>2011.09.30 개인정보보호법 발효에 따라 휴대폰 번호가 중복된 경우 </li>
								<li>휴대폰 본인 인증을 통해 가입한 최근 신규 고객님께만 SMS가 발송됩니다. 휴대폰 번호가 변경된 경우 개인 정보 수정 후 SMS 수신이 가능합니다.</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th class="">고객<br>정보관리</th>
						<td class="">
							<ul class="list">
								<li>회원 혜택 및 행사 안내는 SMS 수신 동의 회원에 한해서 발송됩니다.</li>
								<li>2011.09.30 개인정보보호법 발효에 따라 휴대폰 번호가 중복된 경우 </li>
								<li>휴대폰 본인 인증을 통해 가입한 최근 신규 고객님께만 SMS가 발송됩니다. 휴대폰 번호가 변경된 경우 개인 정보 수정 후 SMS 수신이 가능합니다.</li>
							</ul>
						</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.integrated_bnf -->

		<!-- <div class="ml-10 mt-10"><a href="membership_terms.php" class="btn-basic">멤버십 약관 보기</a></div> -->
	</section><!-- //.my_membership -->

</main>
<!-- //내용 -->
</div>

<?
include('./include/bottom.php');
?>

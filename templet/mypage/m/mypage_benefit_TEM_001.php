<?php
$sql = "SELECT a.*, b.group_level, b.group_name, b.group_code, b.group_orderprice_s, b.group_orderprice_e, b.group_ordercnt_s, b.group_ordercnt_e FROM tblmember a left join tblmembergroup b on a.group_code = b.group_code WHERE a.id='".$_ShopInfo->getMemid()."' ";
$result=pmysql_query($sql,get_db_conn());
if($row=pmysql_fetch_object($result)) {
	$_mdata=$row;
	if($row->member_out=="Y") {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		alert_go('회원 아이디가 존재하지 않습니다.',$Dir.FrontDir."login.php");
	}

	if($row->authidkey!=$_ShopInfo->getAuthidkey()) {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		alert_go('처음부터 다시 시작하시기 바랍니다.',$Dir.FrontDir."login.php");
	}
}
$staff_type = $row->staff_type;
pmysql_free_result($result);

$mem_grade_code			= $_mdata->group_code;
$mem_grade_name			= $_mdata->group_name;

$mem_grade_img	= "../data/shopimages/grade/groupimg_".$mem_grade_code.".gif";
$mem_grade_text	= $mem_grade_name;
$reg_date	= substr($_mdata->date,0,4)."-".substr($_mdata->date,4,2)."-".substr($_mdata->date,6,2);

// 다음등급 AP포인트
list($next_level_point)=pmysql_fetch_array(pmysql_query("select group_ap_s from tblmembergroup WHERE group_level > '{$_mdata->group_level}' order by group_level asc limit 1"));

// 다음등급까지 남은 AP 포인트
$need_act_point=($_mdata->act_point >= $next_level_point)?'0':($next_level_point-$_mdata->act_point);
?>
<div id="page">
<main id="content" class="subpage">

    <section class="page_local">
        <h2 class="page_title">
            <a href="javascript:history.back();" class="prev">이전페이지</a>
            <span>멤버십 등급/혜택안내</span>
            <a href="/m/shop.php" class="home"></a>
        </h2>
    </section>
	
	<section class="my_membership sub_bdtop">
		<div class="mypage_main" style="border-top:none">
			<div class="box_level clear">
				<div class="level_name">
					<p><strong class="name">퍼블리셔</strong> 님의 <br>2018년 8월 멤버십 등급</p>
					<p><span class="txt_level">VVIP</span></p>
					<div class="mt-10">
						<span class="point-color">구매건수 : 10건</span> | 
						<span class="point-color">구매금액 : 320,000원</span><br>
						<div class="mt-5"><span class="point-color">집계기간 : 2017.08.01 ~ 2018.08.31</span></div>
					</div>
				</div>
				<div class="next">
					<em>다음 등급까지 남은 조건</em>
					<span>구매금액 : <strong>180,000</strong>원</span>
				</div>
			</div>
		</div>

		<div class="membership_bnf ">
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
	</section>

</main>
</div>

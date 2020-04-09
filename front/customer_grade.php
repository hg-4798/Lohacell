<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

$page_num       = $_POST[page_num];

// 멤버십 내용 불러오기
$sql = "SELECT etc_agreement3 FROM tbldesign ";
$result = pmysql_query($sql,get_db_conn());
if ($row=pmysql_fetch_object($result)) {
	$etc_agreement3 = ($row->etc_agreement3=="<P>&nbsp;</P>"?"":$row->etc_agreement3);
	$etc_agreement3 = str_replace('\\','',$etc_agreement3);
}
pmysql_free_result($result);

#####좌측 메뉴 class='on' 을 위한 페이지코드
//$page_code='csfaq';
$board = "membership";
$class_on['membership'] = " class='on'";

include('./include/top.php');
include('./include/gnb.php');

?>

<SCRIPT LANGUAGE="JavaScript">
<!--
function GoPage(block,gotopage) {
	document.idxform.block.value=block;
	document.idxform.gotopage.value=gotopage;
	document.idxform.submit();
}
function ViewNotice(num) {
	location.href="customer_notice_view.php?num="+num;
}

//-->
</SCRIPT>

<div id="contents">
	<div class="cs-page">

		<h2 class="page-title">멤버십 등급/혜택안내</h2>

		<div class="inner-align page-frm clear">

			<?	
				$lnb_flag = 5;
        		include ($Dir.MainDir."lnb.php");
        	?>
		<article class="cs-content member-grade membership">
				<?include_once($Dir."conf/config.point.new.php");?><!--20180206 이기연추가-->
				
				
				<ul class="membership-intro clear hide">
					<li>
						<i><img src="../static/img/icon/icon_membership01.png" alt=""></i>
						<!--strong class="point-color">5,000 E포인트 제공</strong--><!--20180206 이기연추가-->
						<strong class="point-color"><?=number_format($pointSet_new['agree_point']);?> E포인트 제공</strong>
						<p>회원가입 시 가입 축하 포인트 제공</p>
					</li>
					<li>
						<i><img src="../static/img/icon/icon_membership02.png" alt=""></i>
						<!--strong class="point-color">10,000 E포인트 제공</strong--><!--20180206 이기연추가-->
						<strong class="point-color"><?=number_format($pointSet_new['app_point']);?> E포인트 제공</strong>
						<p>앱 설치시 포인트 제공</p>
					</li>
					<li>
						<i><img src="../static/img/icon/icon_membership03.png" alt=""></i>
						<!--strong class="point-color">500 E포인트 제공</strong--><!--20180206 이기연추가-->
						<strong class="point-color"><?=number_format($pointSet_new['protext_up_point']);?> E포인트 제공</strong>
						<p>후기 작성 시 후기 포인트 제공</p>
					</li>
					<li>
						<i><img src="../static/img/icon/icon_membership04.png" alt=""></i>
						<div class="upper">'17년 신규 시행</div>
						<strong class="point-color">10% 할인 쿠폰 제공</strong>
						<p>생일축하 특별 쿠폰</p>
					</li>
					<li>
						<i><img src="../static/img/icon/icon_membership05.png" alt=""></i>
						<div class="upper">'17년 신규 시행</div>
						<strong class="point-color">통합 포인트 적립<br><span class="fz-14">(상품 할인율별 최대 5%)</span></strong>
						<p>상품 구매 시 구매 포인트 적립</p>
					</li>
					<li>
						<i><img src="../static/img/icon/icon_membership06.png" alt=""></i>
						<div class="upper">'17년 신규 시행</div>
						<!--strong class="point-color">5,000 E포인트 적립</strong--><!--20180206 이기연추가-->
						<strong class="point-color"><?=number_format($pointSet_new['membership_point']);?> E포인트 적립</strong>
						<p>오프라인 회원, 통합 멤버십 회원 전환 시</p>
						<p><strong>통합 멤버십 웰컴 포인트 </strong></p>
					</li>
				</ul>

				
				<!-- <div class="my-grade-summary clear">
					<div class="info-grade">
						<img class="grade-icon" src="" alt="등급 아이콘" >
						<span class="fw-bold pr-5 point-color">홍길동</span>님의 2018년 8월 멤버십 등급
						<strong>VVIP</strong>
						<p class="mt-5">구매건수 : 10건</p>
						<p>구매금액 : 320,000원</p>
						<p>집계기간 : 2018.07.01 ~ 2018.08.31</p>
					</div>
					<div class="next">
						<p>다음 등급까지 남은 조건</p>
						<span>구매금액 : <strong>180,000</strong>원</span>
					</div>
				</div> -->

				<div class="">
					<h4 class="fz-20">멤버십 등급 및 혜택</h4>
				</div>
				<table class="th-left mt-15 special-benefit">
					<caption>등급별 혜택안내</caption>
					<colgroup>
						<col style="width:20%">
						<col style="width:35%">
						<col style="width:15%">
						<col style="width:15%">
						<col style="width:15%">
					</colgroup>
					<thead>
						<tr>
							<th scope="col" rowspan="2">등급</th>
							<th scope="col" rowspan="2">등급기준</th>
							<th scope="col" colspan="3">혜택</th>
						</tr>
						<tr>
							<th scope="col">마일리지</th>
							<th scope="col">쿠폰</th>
							<th scope="col">포인트</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td rowspan="2"><img src="/jayjun/web/static/img/icon/icon_grade_vv.png" width="100" alt="VVIP 등급"></td>
							<td class="ta-l" rowspan="2">50만원 이상 구매회원</td>
							<td rowspan="2">7% 적립</td>
							<td >무료배송쿠폰</td>
							<td rowspan="2">-</td>
						</tr>
						<tr>
							<td class="ta-c">생일축하쿠폰 1매<br>[3,000원 할인]</td>
						</tr>
						<tr>
							<td><img src="/jayjun/web/static/img/icon/icon_grade_v.png" width="100" alt="VIP 등급"></td>
							<td class="ta-l">20만원 이상 ~ 50만원 미만 구매 회원</td>
							<td>5% 적립</td>
							<td >생일축하쿠폰 1매<br>[3,000원 할인]</td>
							<td>-</td>
						</tr>
						<tr>
							<td><img src="/jayjun/web/static/img/icon/icon_grade_f.png" width="100" alt="F 등급"></td>
							<td class="ta-l">6만원 이상 ~ 20만원 미만 구매 회원</td>
							<td>3% 적립</td>
							<td>생일축하쿠폰 1매<br>[3,000원 할인]</td>
							<td>-</td>
						</tr>
						<tr>
							<td><img src="/jayjun/web/static/img/icon/icon_grade_n.png" width="100" alt="N 등급"></td>
							<td class="ta-l">신규가입 즉시 모든 회원</td>
							<td>2% 적립</td>
							<td>-</td>
							<td>3,000원 지급<br>즉시 사용 가능</td>
						</tr>
					</tbody>
				</table>
				
				<div class="mt-45">
					<h4 class="fz-20">멤버십 유의사항</h4>
				</div>
				<table class="th-left mt-15 special-benefit">
					<caption>등급별 혜택안내</caption>
					<colgroup>
						<col style="width:50%">
						<col style="width:50%">
					</colgroup>
					<thead>
						<tr>
							<th scope="col">공통</th>
							<th scope="col">쿠폰</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="ta-l">
								<ul>
									<li>- 멤버십 등급은 매월 1일 직전 1년의 실제 결제금액을 기준으로 자동 반영됩니다.</li>
									<li>- 적립금 결제금액 및 쿠폰 할인금액은 등급산정 금액에서 제외됩니다. </li>
									<li>- 멤버십 혜택은 가입 고객을 대상으로 구매 이력 확인 후 제공되므로 회원 가입 후<br><span style="padding-left:10px"></span>혜택을 받으실 수 있습니다.  </li>
								</ul>
							</td>
							<td class="ta-l">
								<ul>
									<li>- 생일축하쿠폰은 해당 월에 생일을 맞이하는 고객님들께 매월 1일 자동 <br><span style="padding-left:10px"></span>발급됩니다. (영업일 기준, 휴무일의 경우 익일 발행)</li>
									<li>- 생일축하쿠폰은 발행일로부터 한달 이내 사용이 가능합니다. </li>
									<li>- 공식몰에서 발행된 쿠폰은 공식몰에서만 사용 가능하며, 직영 매장에서의 <br><span style="padding-left:10px"></span>사용이 불가합니다.</li>
								</ul>
							</td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<th scope="col">마일리지</th>
							<th scope="col">고객 정보관리</th>
						</tr>
						<tr>
							<td class="ta-l">
								<ul>
									<li>- 구매 적립 마일리지는 등급별 적립률에 따라 지급됩니다. </li>
									<li>- 구매 적립 마일리지는 배송 완료 7일 이후 가용 적립금으로 전환되어 <br><span style="padding-left:10px"></span>사용 가능합니다.</li>
								</ul>
							</td>
							<td class="ta-l">
								<ul>
									<li>- 회원 혜택 및 행사 안내는 SMS 수신 동의 회원에 한해서 발송됩니다.</li>
									<li>- 2011.09.30 개인정보보호법 발효에 따라 휴대폰 번호가 중복된 경우 </li>
									<li>- 휴대폰 본인 인증을 통해 가입한 최근 신규 고객님께만 SMS가 발송됩니다. <br><span style="padding-left:10px"></span>휴대폰 번호가 변경된 경우 개인 정보 수정 후 SMS 수신이 가능합니다.</li>
								</ul>
							</td>
						</tr>
					</tbody>
				</table>

				<!-- <div class="mt-45">
					<h4 class="fz-20">멤버십 약관</h4>
				</div>
				<div class="membership-terms">
					<?=$etc_agreement3 ?>
				</div> -->

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<!-- footer 시작 -->
<?php include('./include/bottom.php'); ?>


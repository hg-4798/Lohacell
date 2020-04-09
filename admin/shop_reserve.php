<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "sh-3";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$up_reserveuse=$_POST["up_reserveuse"];
$up_money=$_POST["up_money"];
$up_remoney=$_POST["up_remoney"];
$up_reprice=$_POST["up_reprice"];
$up_reserve_join=$_POST["up_reserve_join"];
$up_canuse=$_POST["up_canuse"];
$up_e_canuse=$_POST["up_e_canuse"];
$up_reserve_maxprice=$_POST["up_reserve_maxprice"];
$up_e_reserve_maxprice=$_POST["up_e_reserve_maxprice"];
$up_usecheck=$_POST["up_usecheck"];
$up_reservemoney=$_POST["up_reservemoney"];
$up_reservepercent=$_POST["up_reservepercent"];
$up_point_cut=$_POST["up_point_cut"];
$up_point_updown=$_POST["up_point_updown"];

$up_rcall_type=$_POST["up_rcall_type"];

$st_per=$_POST["st_per"];
$en_per=$_POST["en_per"];
$ins_per=$_POST["ins_per"];
$brand_idx=$_POST["brand_idx"];


if($up_usecheck==1) $reserve_limit=0;
else if($up_usecheck==2) $reserve_limit=$up_reservemoney;
else if($up_usecheck==3) $reserve_limit=-$up_reservepercent;
else $reserve_limit=0;

/* 포인트 지급설정*/
$agree_point=$_POST[agree_point]?$_POST[agree_point]:0;
$app_point=$_POST[app_point]?$_POST[app_point]:0;
$protext_down_point=$_POST[protext_down_point]?$_POST[protext_down_point]:0;
$protext_up_point=$_POST[protext_up_point]?$_POST[protext_up_point]:0;
$poto_point=$_POST[poto_point]?$_POST[poto_point]:0;
$over_point=$_POST[over_point]?$_POST[over_point]:0;
$proreview_point=$_POST[proreview_point]?$_POST[proreview_point]:0;
$mody_one_point=$_POST[mody_one_point]?$_POST[mody_one_point]:0;
$mody_two_point=$_POST[mody_two_point]?$_POST[mody_two_point]:0;
$mody_thr_point=$_POST[mody_thr_point]?$_POST[mody_thr_point]:0;
$membership_point=$_POST[membership_point]?$_POST[membership_point]:0;    //20180226 이기연 추가

if ($type=="up") {


	// 적립금 관련 설정 및 쿠폰 사용 여부, 적립금/쿠폰 동시 사용여부를 저장한다.
	if($up_rcall_type=="Y" && $up_money=="Y") $up_rcall_type="Y";
	else if($up_rcall_type=="N" && $up_money=="Y") $up_rcall_type="N";
	else if($up_rcall_type=="Y" && $up_money=="N") $up_rcall_type="M";
	else if($up_rcall_type=="N" && $up_money=="N") $up_rcall_type="T";

	if($up_remoney=="Y") $reserve_useadd=-1;
	else if($up_remoney=="U") $reserve_useadd=-2;
	else if($up_remoney=="A") $reserve_useadd=0;
	else $reserve_useadd = $up_reprice;

	if ($up_reserveuse == "N") {#적립금 사용하지 않음
		$sets = " reserve_join = 0, reserve_maxuse = -1, e_reserve_maxuse = '-1' ";
	} else {
		$sets = " reserve_join = '{$up_reserve_join}', reserve_maxuse = '{$up_canuse}', e_reserve_maxuse = '{$up_e_canuse}' ";
	}
	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "rcall_type		= '{$up_rcall_type}', ";
	$sql.= "reserve_limit	= '{$reserve_limit}', ";
	$sql.= "reserve_maxprice= '{$up_reserve_maxprice}', ";
	$sql.= "e_reserve_maxprice= '{$up_e_reserve_maxprice}', ";
	$sql.= "reserve_useadd	= '{$reserve_useadd}', ";
	$sql.= "point_cut	= '{$up_point_cut}', ";
	$sql.= "point_updown	= '{$up_point_updown}', ";
	$sql.= $sets;
	pmysql_query($sql,get_db_conn());

	
	foreach($brand_idx as $bi){
		pmysql_query("delete from tblproductbrand_point where bridx='".$bi."'");
		$br_count=count($st_per[$bi]);
		for($i=0;$i<$br_count;$i++){
			$start_per = $st_per[$bi][$i] ? $st_per[$bi][$i] : "0";
			$end_per = $en_per[$bi][$i] ? $en_per[$bi][$i] : "0";
			$insert_per = $ins_per[$bi][$i] ? $ins_per[$bi][$i] : "0";
			
			pmysql_query("insert into tblproductbrand_point (bridx, st_per, en_per, ins_per, point_date) values ('".$bi."','".$start_per."','".$end_per."','".$insert_per."','".date("YmdHis")."')");
		}
	}

	//로그인, 리뷰, 게시글 작성 시 적립금 적용기준을 파일로 저장한다.
    $f = fopen($Dir."conf/config.point.new.php","w");
	fwrite($f,"<?\n");
	fwrite($f,"\$pointSet_new['agree_point'] = '$agree_point'; \n");
	fwrite($f,"\$pointSet_new['app_point'] = '$app_point'; \n");
	fwrite($f,"\$pointSet_new['protext_down_point'] = '$protext_down_point'; \n");
	fwrite($f,"\$pointSet_new['protext_up_point'] = '$protext_up_point'; \n");
	fwrite($f,"\$pointSet_new['poto_point'] = '$poto_point'; \n");
	fwrite($f,"\$pointSet_new['over_point'] = '$over_point'; \n");
	fwrite($f,"\$pointSet_new['proreview_point'] = '$proreview_point'; \n");
	fwrite($f,"\$pointSet_new['mody_one_point'] = '$mody_one_point'; \n");
	fwrite($f,"\$pointSet_new['mody_two_point'] = '$mody_two_point'; \n");
	fwrite($f,"\$pointSet_new['mody_thr_point'] = '$mody_thr_point'; \n");
	fwrite($f,"\$pointSet_new['membership_point'] = '$membership_point'; \n");  //20180226 이기연 추가
	fwrite($f,"?>\n"); fclose($f); @chmod($Dir."conf/config.point.new.php",0777); DeleteCache("tblshopinfo.cache"); $onload="
<script>
	window.onload = function () {
		alert('포인트 관련 설정이 완료되었습니다.');
		location.href = 'shop_reserve.php';
	}
</script>\n"; $log_content = "## 적립금설정 ## - 사용여부 : $up_reserveuse, 가입적립금 : $up_reserve_join, 포인트 $up_canuse 이상 사용가능, E포인트
$up_e_canuse 이상 사용가능, 추가적립기준:$reserve_useadd, 적립금/쿠폰 동시사용여부 :$up_rcall_type"; ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
} $sql2 = "SELECT rcall_type,reserve_limit,reserve_maxprice,e_reserve_maxprice,reserve_useadd,reserve_maxuse,e_reserve_maxuse,reserve_join,coupon_ok,point_cut,point_updown
"; $sql2.= "FROM tblshopinfo "; $result = pmysql_query($sql2,get_db_conn()); if ($row = pmysql_fetch_object($result)) { $reserve_join
= $row->reserve_join; if ($row->reserve_maxuse ==-1) { $reserveuse = "N"; $canuse = 0; } else { $reserveuse = "Y"; $canuse
= abs($row->reserve_maxuse); } if ($row->e_reserve_maxuse ==-1) { $e_canuse = 0; } else { $e_canuse = abs($row->e_reserve_maxuse);
} if ($row->rcall_type=="Y") { $rcall_type = $row->rcall_type; $money="Y"; } else if ($row->rcall_type=="N") { $rcall_type
= $row->rcall_type; $money="Y"; } else if ($row->rcall_type=="M") { $rcall_type="Y"; $money="N"; } else { $rcall_type="N";
$money="N"; } $reserve_limit = $row->reserve_limit; $reserve_maxprice = $row->reserve_maxprice; $e_reserve_maxprice = $row->e_reserve_maxprice;
$coupon_ok = $row->coupon_ok; if($row->reserve_useadd==-1){ $remoney="Y"; $reprice="0"; }else if($row->reserve_useadd==-2){
$remoney="U"; $reprice="0"; }else if($row->reserve_useadd==0){ $remoney="A"; $reprice="0"; }else { $remoney="N"; $reprice=$row->reserve_useadd;
} } pmysql_free_result($result); ${"check_reserveuse".$reserveuse} = "checked"; ${"check_money".$money} = "checked"; ${"check_remoney".$remoney}
= "checked"; ${"check_rcall_type".$rcall_type} = "checked"; $checked["up_point_updown"][$row->point_updown] = "selected";
$checked["up_point_cut"][$row->point_cut] = "selected"; $brand_sql = "SELECT a.*, b.brandname, b.productcode_a, b.bridx,
b.staff_rate, b.coupon_useper FROM tblvenderinfo a JOIN tblproductbrand b ON a.vender = b.vender "; $brand_sql.= "ORDER BY
a.disabled ASC, a.vender DESC, lower(b.brandname) DESC "; $brand_result=pmysql_query($brand_sql); ?>

<?php 
include("header.php");
include_once($Dir."conf/config.point.new.php");
?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
	function CheckForm() {
		var form = document.form1;
		if (form.up_remoney[3].checked) {
			if (isNaN(form.up_reprice.value)) {
				alert('숫자만 입력하시기 바랍니다.');
				form.up_reprice.focus();
				return;
			}
			if (parseInt(form.up_reprice.value) <= 0) {
				alert('금액은 0원 이상 입력하셔야 합니다.');
				form.up_reprice.focus();
				return;
			}
		}

		form.type.value = "up";
		if (confirm("적용하시겠습니끼?")) {
			form.submit();
		}
	}

	function add(brand) {
		var row = "<tr>";
		row += "<td  style=\"border:0px;\">할인율 <input type=text name=\"st_per[" + brand +
			"][]\" value=\"\" size=10 class=\"input\">% ~ <input type=text name=\"en_per[" + brand +
			"][]\" value=\"\" size=10 class=\"input\">% 이하 <input type=text name=\"ins_per[" + brand +
			"][]\" value=\"\" size=10 class=\"input\">% 포인트 적립 &nbsp;&nbsp;&nbsp;<span style='cursor:pointer'><img src='images/btn_del.gif' align=absmiddle></span></td>";
		row += "</tr>";
		$("#table_" + brand).append(row);

	}

	$(function () {
		$(".table").on("click", "span", function () {
			$(this).closest("tr").remove();
		});
	});

	function del() {
		$(this).closest("tr").remove();
	}
</script>
<div class="admin_linemap">
	<div class="line">
		<p>현재위치 : 환경설정 &gt; 운영설정 &gt;
			<span>포인트 정책설정</span>
		</p>
	</div>
</div>
<table cellpadding="0" cellspacing="0" width="98%" style="table-layout:fixed">
	<tr>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
							<col width=240 id="menu_width"></col>
							<col width=10></col>
							<col width=></col>
							<tr>
								<td valign="top">
									<?php include("menu_shop.php"); ?>
								</td>

								<td></td>

								<td valign="top">
									<form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method=post>
										<input type=hidden name=type>
										<input type="hidden1" name="up_money" value="Y">
										<!-- 모든 결제수단에서 사용 가능 -->
										<input type="hidden1" name="up_remoney" value="U">
										<!-- 포인트 결제시 추가적립 설정 -->
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td height="8"></td>
											</tr>
											<tr>
												<td>
													<!-- 페이지 타이틀 -->
													<div class="title_depth3">포인트 정책설정</div>
												</td>
											</tr>


											<tr>
												<td>
													<!-- 소제목 -->
													<div class="title_depth3_sub">포인트 정책설정</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="table_style01">
														<table cellSpacing=0 cellPadding=0 width="100%" border=0>
															<colgroup>
																<col style="width:150px" />
																<col />
															</colgroup>
															<tr>
																<th>
																	<span>포인트 사용여부
																		<span class="req">*</span>
																	</span>
																</th>
																<td class="td_con1">
																	<div>
																		<label>
																			<input type="radio" id="idx_reserveuse1" name="up_reserveuse" value="Y" <?=$check_reserveuseY?> class="hj">
																			<span class="lbl">사용함
																				<span class="helper m-l-10">누적된 포인트를 구매 결제시 공제</span>
																			</span>
																		</label>
																		</label>
																	</div>
																	<div>
																		<label>
																			<input type="radio" id="idx_reserveuse1" name="up_reserveuse" value="N" <?=$check_reserveuseN?> class="hj">
																			<span class="lbl">사용안함
																				<span class="helper m-l-10">주문시에 사용가능한 누적 포인트 및 사용포인트 입력항목이 미표시</span>
																			</span>
																		</label>
																		</label>
																	</div>

																</td>
															</tr>
															<tr>
																<th>
																	<span>포인트/쿠폰 동시 사용여부
																		<span class="req">*</span>
																	</span>
																</th>
																<td>
																	<label>
																		<input type="radio" id="idx_rcall_type1" name="up_rcall_type" value="Y" <?=$check_rcall_typeY?> class="hj">
																		<span class="lbl">동시 사용</span>
																	</label>
																	<label>
																		<input type="radio" id="idx_rcall_type1" name="up_rcall_type" value="N" <?=$check_rcall_typeN?> class="hj">
																		<span class="lbl">동시 사용안함</span>
																	</label>
																</td>
															</tr>


														</table>
													</div>
												</td>
											</tr>
											<tr>
												<td style="padding-top:3pt;padding-bottom:3pt;">
													<!-- 도움말 -->
													<div class="sub_manual_wrap mb-5">
														<div class="title">
															<p>매뉴얼</p>
														</div>
														<ul class="help_list">
															<li>회원이 포인트 적용기준 이상이 되면 주문서에 자동으로 [포인트 입력창] 생성됩니다.</li>
															<li>추가적립은 고객이 포인트를 사용하여 구매 시 추가적립여부를 설정합니다.</li>
															<li>적립방식은 변경한 시점의 주문기준으로 적용됩니다.</li>
															<li>등록/수정하시면 하단에 [적용하기]버튼을 누르셔야 쇼핑몰에 적용됩니다.</li>
														</ul>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="table_style01">
														<table cellSpacing=0 cellPadding=0 width="100%" border=0>
															<tr>
																<th>
																	<span>포인트 절사
																		<span class="req">*</span>
																	</span>
																</th>
																<td class="td_con1">포인트를 %단위로 입력 시
																	<select name=up_point_cut class="" style="width:80px">
																		<option value="0" <?=$checked[ "up_point_cut"][ "0"]?>>0</option>
																		<option value="1" <?=$checked[ "up_point_cut"][ "1"]?>>1</option>
																		<option value="10" <?=$checked[ "up_point_cut"][ "10"]?>>10</option>
																		<option value="100" <?=$checked[ "up_point_cut"][ "100"]?>>100</option>
																	</select> 원 단위로
																	<select name=up_point_updown class="" style="width:100px">
																		<option value="D" <?=$checked[ "up_point_updown"][ "D"]?>>내림</option>
																		<option value="B" <?=$checked[ "up_point_updown"][ "B"]?>>반올림</option>
																		<option value="U" <?=$checked[ "up_point_updown"][ "U"]?>>올림</option>
																	</select> 하여 지급
																	<div class="helper m-t-5">예) 700원 7%로 적립금 49원일 경우, [10원 단위로 내림] 설정 시 적립금은 0원 / [1원 단위로 내림] 설정 시 적립금은 40원 지급</div>
																</td>
															</tr>
															<tr>
																<th>
																	<span>사용 가능한 누적 포인트
																		<span class="req">*</span>
																	</span>
																</th>
																<td class="td_con1">
																	<input type=text name=up_e_canuse value="<?=$e_canuse?$e_canuse:" 0 "?>" size=10 class="input"> 포인트 이상 적립된 경우에만 사용가능

																</td>
															</tr>
															<tr>
																<th>
																	<span>사용 가능한 상품 구매액</span>
																</th>
																<td class="td_con1">
																	<input type=text name="up_e_reserve_maxprice" value="<?=$e_reserve_maxprice?$e_reserve_maxprice:" 0 "?>" size=10 class="input"> 원 이상 구매 시 포인트 사용가능(배송비 제외)
																</td>
															</tr>
															<tr style="display:none;">
																<th>
																	<span>적립금 1회 사용한도</span>
																</th>
																<td class="td_con1">
																	<input type=radio name=up_usecheck value=1 <?=($reserve_limit==0? "checked": "")?>>누적포인트의 전체를 1회에 사용가능
																	<Br>
																	<input type=radio name=up_usecheck value=2 <?=($reserve_limit>0?"checked":"")?>>누적포인트의
																	<input type=text name=up_reservemoney value="<?=($reserve_limit>0?$reserve_limit:" 0 ")?>" size=10 class="input">포인트 까지 사용가능
																	<br>
																	<input type=radio name=up_usecheck value=3 <?=($reserve_limit<0? "checked": "")?>>상품구매액의
																	<input type=text name=up_reservepercent value="<?=($reserve_limit<0?str_replace(" - "," ",$reserve_limit):"0 ")?>" size=10
																	    maxlength=3 class="input">% 까지 사용가능(최대 100%까지 설정가능)
																</td>
															</tr>
														</table>
													</div>
												</td>
											</tr>

											<tr>
												<td style="padding-top:3pt; padding-bottom:3pt;">

													<!-- 도움말 -->
													<div class="sub_manual_wrap mb-5">
														<div class="title">
															<p>매뉴얼</p>
														</div>
														<ul class="help_list">
															<li>포인트 절사에서 적립금 단위와 방식을 초기상태로 유지할 경우,
																<b>소수점 이하 금액만 자동으로 내림하여 계산</b>됩니다. </li>
															<li>포인트 절사는 적립율에 따라 [원]으로 변경되는 금액에 대한 부분을 설정합니다.</li>
															<li>적립금 1회 사용한도에서 회원이 사용 가능한 누적포인트의 1회 사용한도를 포인트 또는 구매액에 따른 비율(%)로 설정하실 수 있습니다.</li>
															<li>등록/수정하시면 하단에 [적용하기]버튼을 누르셔야 쇼핑몰에 적용됩니다.</li>
														</ul>
													</div>
												</td>
											</tr>

											<tr>
												<td>
													<!-- 소제목 -->
													<div class="title_depth3_sub">포인트 지급 기준 설정</div>
												</td>
											</tr>

											<tr>
												<td>
													<div class="table_style01">
														<table cellSpacing=0 cellPadding=0 width="100%" border=0>
															<tr>
																<th>
																	<span>신규 회원가입시</span>
																</th>
																<td class="td_con1">
																	<input type=text name="agree_point" value="<?=$pointSet_new['agree_point']?>" size=10 class="input"> 포인트 지급
																</td>
															</tr>
															<tr>
																<th>
																	<span>추가정보 기입시</span>
																</th>
																<td class="td_con1">
																	<input type=text name="over_point" value="<?=$pointSet_new['over_point']?>" size=10 class="input"> 포인트 지급
																</td>
															</tr>
															<tr>
																<th>
																	<span>텍스트 상품평 작성시</span>
																</th>
																<td class="td_con1">
																	<div>100자 이하
																		<input type=text name="protext_down_point" value="<?=$pointSet_new['protext_down_point']?>" size=10 class="input"> 포인트 지급</div>
																	<div class="m-t-5">100자 이상
																		<input type=text name=protext_up_point value="<?=$pointSet_new['protext_up_point']?>" size=10 class="input"> 포인트 지급</div>
																</td>
															</tr>
															<tr>
																<th>
																	<span>포토 상품평 작성시</span>
																</th>
																<td class="td_con1">
																	<input type=text name="poto_point" value="<?=$pointSet_new['poto_point']?>" size=10 class="input"> 포인트 지급
																</td>
															</tr>

															<tr>
																<th>
																	<span>개별 상품평 3번째이내 작성시</span>
																</th>
																<td class="td_con1">
																	<input type=text name="proreview_point" value="<?=$pointSet_new['proreview_point']?>" size=10 class="input"> 포인트 지급
																</td>
															</tr>

														</table>
													</div>
												</td>
											</tr>

											<tr>
												<td style="padding-top:3pt; padding-bottom:3pt;">

													<!-- 도움말 -->
													<div class="sub_manual_wrap mb-5">
														<div class="title">
															<p>매뉴얼</p>
														</div>
														<ul class="help_list">
															<li>추가정보는 회원가입 & 마이페이지 정보수정에서 추가정보(신체 사이즈, 기타 등)를 말합니다.</li>
															<li>
																<b>[상품평 3번째 이내 작성]에는 관리자에서 등록한 리뷰도 순위에 포함됩니다.</b>
															</li>
															<li>사용하지 않을 항목은 입력하지 않거나, 0으로 입력 시 적용되지 않습니다. </li>
															<li>등록/수정하시면 하단에 [적용하기]버튼을 누르셔야 쇼핑몰에 적용됩니다.</li>
														</ul>
													</div>
												</td>
											</tr>


											<tr>
												<td>
													<!-- 소제목 -->
													<div class="title_depth3_sub">등급별 포인트 적립기준설정</div>
												</td>
											</tr>

											<tr>
												<td>
													<div class="table_style01">
														<table cellspacing="0" cellpadding="0" width="100%" border="0">
															<tbody>
																{@ range(1,10)}
																<tr>
																	<th>
																		<span>등급명</span>
																	</th>
																	<td class="td_con1">
																		<input type="text" name="agree_point" value="" size="10" class="input"> %
																	</td>
																</tr>
																{/}
																

															</tbody>
														</table>


													</div>
												</td>
											</tr>



											<tr>
												<td height=10></td>
											</tr>
											<tr>
												<td align="center">
													<a href="javascript:CheckForm();">
														<span class="btn-point">적용하기</span>
													</a>
												</td>
											</tr>
									</form>
								</td>
							</tr>
							</table>

					</td>
				</tr>
				</table>
		</td>
	</tr>
	</table>
	<?=$onload?>
		<?php 
include("copyright.php");
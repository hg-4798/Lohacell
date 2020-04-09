<?php
if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}
$imagepath=$Dir.DataDir."shopimages/etc/main_logo.gif";
$flashpath=$Dir.DataDir."shopimages/etc/main_logo.swf";

if (file_exists($imagepath)) {
	$mainimg="<img src=\"".$imagepath."\" border=\"0\" align=\"absmiddle\">";
} else {
	$mainimg="";
}
if (file_exists($flashpath)) {
	if (preg_match("/\[MAINFLASH_(\d{1,4})X(\d{1,4})\]/",$_data->shop_intro,$match)) {
		$width=$match[1];
		$height=$match[2];
	}
	$mainflash="<script>flash_show('".$flashpath."','".$width."','".$height."');</script>";
} else {
	$mainflash="";
}
$pattern=array("(\[DIR\])","(\[MAINIMG\])","/\[MAINFLASH_(\d{1,4})X(\d{1,4})\]/");
$replace=array($Dir,$mainimg,$mainflash);
$shop_intro=preg_replace($pattern,$replace,$_data->shop_intro);


//$mb_qry="select * from tblmainbannerimg order by banner_sort";

/*
if (stripos($shop_intro,"<table")!==false || strlen($mainflash)>0)
	$main_banner=$shop_intro;
else
	$main_banner=nl2br($shop_intro);
*/
?>
<style>
/** 달력 팝업 **/
.calendar_pop_wrap {position:relative; background-color:#FFF;}
.calendar_pop_wrap .calendar_con {position:absolute; top:0px; left:0px;width:247px; padding:10px; border:1px solid #b8b8b8; background-color:#FFF;}
.calendar_pop_wrap .calendar_con .month_select { text-align:center; background-color:#FFF; padding-bottom:10px;}
.calendar_pop_wrap .calendar_con .day {clear:both;border-left:1px solid #e4e4e4;}
.calendar_pop_wrap .calendar_con .day th {background:url('../admin/img/common/calendar_top_bg.gif') repeat-x; width:34px; font-size:11px; border-top:1px solid #9d9d9d;border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4; padding:6px 0px 4px;}
.calendar_pop_wrap .calendar_con .day th.sun {color:#ff0012;}
.calendar_pop_wrap .calendar_con .day td {border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4; background-color:#FFF; width:34px;  font-size:11px; text-align:center; font-family:tahoma;}
.calendar_pop_wrap .calendar_con .day td a {color:#35353f; display:block; padding:2px 0px;}
.calendar_pop_wrap .calendar_con .day td a:hover {font-weight:bold; color:#ff6000; text-decoration:none;}
.calendar_pop_wrap .calendar_con .day td.pre_month a {color:#fff; display:block; padding:3px 0px;}
.calendar_pop_wrap .calendar_con .day td.pre_month a:hover {text-decoration:none; color:#fff;}
.calendar_pop_wrap .calendar_con .day td.today {background-color:#52a3e7; }
.calendar_pop_wrap .calendar_con .day td.today a {color:#fff;}
.calendar_pop_wrap .calendar_con .close_btn {text-align:center; padding-top:10px;}
</style>

<?
		$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
		$result=pmysql_query($sql,get_db_conn());
		$delicomlist=array();
		while($row=pmysql_fetch_object($result)) {
			$delicomlist[$row->code]=$row;
		}
		pmysql_free_result($result);

		$s_curtime=strtotime("$s_year-$s_month-$s_day");
		$s_curdate=date("Ymd",$s_curtime);
		$e_curtime=strtotime("$e_year-$e_month-$e_day");
		$e_curdate=date("Ymd",$e_curtime)."999999999999";

		$sql = "SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, dc_price, reserve, deli_price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, order_conf,2 as ordertype ";
		$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= "ORDER BY ordercode DESC ";

		if(!$limitpage) $limitpage = '10';
		$paging = new Tem001_saveheels_Paging($sql, 10, $limitpage, 'GoPage', true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;

		#$result3=pmysql_query($sql,get_db_conn());
		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		//exdebug($sql);
		//exdebug("##");
 ?>


	
	<div class="containerBody sub-page">
	
		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li><a href="mypage.php">MY PAGE</a></li>
				<li class="on"><a>주문배송조회</a></li>
			</ul>
		</div>

		<!-- LNB -->
		<div class="left_lnb">
			<? include ($Dir.FrontDir."mypage_TEM01_left.php");?> 
			<!---->
		</div><!-- //LNB -->
		
		<!-- 내용 -->
		<div class="right_section mypage-content-wrap">
			
			<p class="order-list-flow"><img src="../static/img/common/mypage_order_flow.jpg" alt=""></p>
			<h4 class="mypage-title mt-30">주문배송조회</h4>
			<!-- 날짜 설정 -->
			<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
			<div class="date_find_wrap mt-5">
				<ul class="date_setting">
					<li class="title">기간별 조회</li>
					<li class="date">
						<?
							if(!$day_division) $day_division = '1MONTH';

						?>
						<?foreach($arrSearchDate as $kk => $vv){?>
							<?
								$dayClassName = "";
								if($day_division != $kk){
									$dayClassName = 'btn_white_s';
								}else{
									$dayClassName = 'btn_black_s';
								}
							?>
							<a href="Javascript:;" class="<?=$dayClassName?>" onClick = "GoSearch2('<?=$kk?>', this)"><?=$vv?></a>
						<?}?>
						
					</li>
					<li class="title">일자별 조회</li>
					<li class="date">
						<div class="input_bg"><input type="text" name="date1" id="" value="<?=$strDate1?>" readonly ></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> ~ 
						<div class="input_bg"><input type="text" name="date2" id="" value="<?=$strDate2?>" readonly ></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> &nbsp;&nbsp;
						<a href="javascript:CheckForm();" class="btn-dib-function"><span>SEARCH</span></a>
					</li>
					
				</ul>
			</div>
			</form><!-- //날짜 설정 -->
			
			<?
			/*
			while($row3=pmysql_fetch_object($result3)) {
					if ($row3->deli_gbn=="C"){ $cancle++;}
					else if ($row3->deli_gbn=="D"){ $cancle++;}
					else if ($row3->deli_gbn=="E") { $cancle++;}
					else if ($row3->deli_gbn=="X") { $delicnt++;}
					else if ($row3->deli_gbn=="Y")  { $step04++;}
					else if ($row3->deli_gbn=="N") {
						if (strlen($row3->bank_date)<12 && strstr("BOQ", $row3->paymethod[0])) { $step01++;}
						else if ($row3->pay_admin_proc=="C" && $row3->pay_flag=="0000") { $cancle++;}
						else if (strlen($row3->bank_date)>=12 || $row3->pay_flag=="0000") { $step02++;}
						else { $step01++;}
					} else if ($row3->deli_gbn=="S") {
						$step02++;
					} else if ($row3->deli_gbn=="R") {
						$refund++;
					} else if ($row3->deli_gbn=="H") {
						$change++;
					}
			}
			*/
			$step01=$step02=$step03=$step04=$cancle=$cancle1=$cancle2=$cancle3=$cancle4=$step05=$refund=$change=0; 
			$addPayQuery = " AND a.ordercode >= '".$s_curdate."' AND a.ordercode <= '".$e_curdate."' AND id='".$_ShopInfo->getMemid()."'";

			//미입금
			$step01=pmysql_num_rows(pmysql_query("SELECT * FROM tblorderinfo a WHERE a.deli_gbn='N' AND ((SUBSTR(a.paymethod,1,1) IN ('B','O','Q') AND (a.bank_date IS NULL OR a.bank_date='')) OR (SUBSTR(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag!='0000' AND a.pay_admin_proc='C'))".$addPayQuery));

			//입금확인
			$step02=pmysql_num_rows(pmysql_query("SELECT a.* FROM tblorderinfo a WHERE a.deli_gbn='N' AND ((SUBSTR(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=14) OR (SUBSTR(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_admin_proc!='C' AND a.pay_flag='0000'))".$addPayQuery));

			//배송준비
			$step03=pmysql_num_rows(pmysql_query("SELECT a.* FROM tblorderinfo a WHERE a.deli_gbn='S'".$addPayQuery));

			//배송중
			$step04=pmysql_num_rows(pmysql_query("SELECT a.* FROM tblorderinfo a WHERE a.deli_gbn='Y' AND receive_ok = '0' ".$addPayQuery));

			//배송완료
			$step05=pmysql_num_rows(pmysql_query("SELECT a.* FROM tblorderinfo a WHERE a.deli_gbn='Y' AND receive_ok = '1' ".$addPayQuery));

			//주문취소
			$cancle1=pmysql_num_rows(pmysql_query("SELECT a.* FROM tblorderinfo a WHERE a.deli_gbn='C'".$addPayQuery));

			//카드실패
			$cc_count=pmysql_num_rows(pmysql_query("SELECT * FROM tblorderinfo a WHERE a.deli_gbn='N' AND (SUBSTR(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag='N' AND a.pay_admin_proc='N')".$addPayQuery));

			//반송
			$refund=pmysql_num_rows(pmysql_query("SELECT a.* FROM tblorderinfo a WHERE a.deli_gbn='R'".$addPayQuery));

			//취소요청
			$cancle2=pmysql_num_rows(pmysql_query("SELECT a.* FROM tblorderinfo a WHERE a.deli_gbn='D'".$addPayQuery));

			//환불대기
			$cancle3=pmysql_num_rows(pmysql_query("SELECT a.* FROM tblorderinfo a WHERE a.deli_gbn='E'".$addPayQuery));

			//환불
			$cancle4=pmysql_num_rows(pmysql_query("SELECT a.* FROM tblorderinfo a WHERE a.deli_gbn !='C' and ((SUBSTR(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=9) OR (SUBSTR(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag='0000' AND a.pay_admin_proc='C'))".$addPayQuery));
			$cancle = $cancle1+$cancle2+$cancle3+$cancle4;

			?>
			<div class="orderlist_delivery hide">
				<p class="step01"><span><?=$step01?></span></p>
				<p class="step02"><span><?=$step02?></span></p>
				<p class="step03"><span><?=$step03?></span></p>
				<p class="step04"><span><?=$step04?></span></p>
				<p class="step05"><span><?=$step05?></span></p>
				<p class="cancle ta_r"><span class="point"><?=$cancle?></span>건</p>
				<p class="refund ta_r"><span class="point"><?=$refund?></span>건</p>
				<!--p class="change ta_r"><span class="dahong"><?=$change?></span>건</p-->
			</div>
			
			<!-- 주문/배송 -->
			<div class="order-list-wrap">
				
				
				<table class="th-top util top-line-none">
					<colgroup>
						<col style="width:200px" ><col style="width:145px" ><col style="width:80px" ><col style="width:auto" ><col style="width:130px" ><col style="width:120px" >
					</colgroup>
					<thead>
					<tr>
						<th>주문정보</th>
						<th colspan=2>상품정보</th>
						<th>주문금액</th>
						<th>진행상태</th>
						<th>요청사항</th>
					</tr>
					</thead>

<?		
		$cnt=0;
		while($row=pmysql_fetch_object($result)) {
		$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
			# 반송가능상품
			$redaliveryArray = array();
			$redelivery_sql = "SELECT redelivery_type,redelivery_date,redelivery_reason,deli_date FROM tblorderinfo ";
			$redelivery_sql.= "WHERE ordercode = '".$row->ordercode."' ";
			$redelivery_sql.= "AND deli_date != '' ";
			$redelivery_res = pmysql_query($redelivery_sql,get_db_conn());
			if($redelivery_row = pmysql_fetch_object($redelivery_res)){
				if(dateDiff(date("YmdHis"),$redelivery_row->deli_date) < 11){
					$redaliveryArray = $redelivery_row; 
				}
			}
			pmysql_free_result($redelivery_res);
			

		$ord_time=substr($row->ord_time,0,2).":".substr($row->ord_time,2,2).":".substr($row->ord_time,4,2);
?>
					<tr>
						<? $sql2 = "SELECT productcode, productname, MAX(deli_com) deli_com, MAX(deli_num) deli_num FROM tblorderproduct  ";
						$sql2 .= " WHERE ordercode='".$row->ordercode."' ";
						$sql2 .= " AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') AND option_type = 0 group by productcode, productname";
						$res22 = pmysql_query($sql2);
						$result2=pmysql_fetch_object($res22);
						$resultforcnt=pmysql_num_rows($res22);
						$deli_company = "";
						$deli_number = "";
						/*while($row2=pmysql_fetch_array($resultforcnt)){
							if(!$deli_company) $deli_company = $row2[deli_com];
							if(!$deli_number) $deli_number = $row2[deli_num];
						}*/
						if($deli_company=="") $deli_company = $result2->deli_com;
						if($deli_number=="") $deli_number = $result2->deli_num;
						//$imgsrc = getMaxImageForXn($result2->productcode);	
						$imgRes = pmysql_query( "SELECT tinyimage FROM tblproduct WHERE productcode = '{$result2->productcode}' ",get_db_conn() );
						$imgRow = pmysql_fetch_object( $imgRes );
						if( is_file( $Dir.DataDir.'shopimages/product/'.$imgRow->tinyimage ) ) {
							$imgsrc = $Dir.DataDir.'shopimages/product/'.$imgRow->tinyimage;
						} else {
							$imgsrc = '../images/no_img.gif';
						}
						pmysql_free_result($imgRes);
						?>
						<td><a href="javascript:OrderDetail('<?=$row->ordercode?>')"><?=$row->ordercode?></a></td>
						<td class="ta_l">
							<a href="javascript:OrderDetail('<?=$row->ordercode?>')">
								<img src="<?=$imgsrc?>" alt="" class='img-size-mypage' >
							</a>
						</td>
						<td class="ta_l">
							<a href="javascript:OrderDetail('<?=$row->ordercode?>')">
								<? 
									switch($resultforcnt){
										case 1 : 
											echo $result2->productname;
										break;
										default : $resultforcnt--;
											echo $result2->productname." 외 ".$resultforcnt."건";										
									}							
								?>
							</a>
							<!--div class="opt"><?=$result2->opt1_name?></div>
							<div class="opt"><?=$result2->opt2_name?></div-->
						</td>
						<td><b><?=number_format($row->price-$row->dc_price-$row->reserve+$row->deli_price)?>원</b></td>
						<td>
							<?
								if($row->deli_gbn=="Y" && $redaliveryArray->redelivery_type == "Y"){
									echo "<br>";
									echo "<font color='red'>[반송신청]</font>";
								} else {
									if ($row->deli_gbn=="C") echo "주문취소";
									else if ($row->deli_gbn=="D") echo "취소요청";
									else if ($row->deli_gbn=="E") echo "환불대기";
									else if ($row->deli_gbn=="X") echo "배송준비";
									else if ($row->deli_gbn=="Y") {
										if ($row->receive_ok == '1' && $row->order_conf == '0') echo "<span style='color:#ff7200;'>배송완료</span>";
										else if ($row->receive_ok == '1' && $row->order_conf == '1') echo "<span style='color:#ff7200;'>구매확정</span>";
										else echo "배송중";
									} else if ($row->deli_gbn=="N") {
										if (strlen($row->bank_date)<12 && strstr("BOQ", $row->paymethod[0])) echo "주문접수";
										else if ($row->pay_admin_proc=="C" && $row->pay_flag=="0000") echo "결제취소";
										else if (strlen($row->bank_date)>=12 || $row->pay_flag=="0000") echo "결제완료";
										else echo "결제확인중";
									} else if ($row->deli_gbn=="S") {
										echo "배송준비";
									} else if ($row->deli_gbn=="R") {
										echo "반송처리";
									} else if ($row->deli_gbn=="H") {
										echo "배송완료 [정산보류]";
									}
								}
							?>
						</td>
						<td>
							
							<?
								if($row->deli_gbn=="Y" && $row->order_conf == '0' ){ // 배송중일경우
							?>
								<?if($row->receive_ok == '0'){?><a href="javascript:;" class="btn_mypage_s deli_ok" ordercode = "<?=$row->ordercode?>">수령확인</a><?}?>
								<!-- 반송신청 위치 -->
								<?if($redaliveryArray->redelivery_type != "Y"){?><a href="javascript:;" class="btn_mypage_s CLS_redelivery" ordercode = "<?=$row->ordercode?>">반송신청</a><?}?>
								<?if($row->receive_ok == '0'){?><a href="javascript:;" class="btn_mypage_s CLS_delivery_tracking" urls = "<?=$delicomlist[$deli_company]->deli_url.$deli_number?>">배송추적</a><?}?>
								<?if($row->receive_ok == '1' ){?><a href="javascript:;" class="btn_mypage_s CLS_order_conf" ordercode = "<?=$row->ordercode?>">구매확정</a><?}?>
							<?
								}else if($row->deli_gbn=="N" &&((strstr("BOQ", $row->paymethod[0]) && !$row->bank_date) || (strstr("CPMV", $row->paymethod[0]) && $row->pay_flag!= '0000' && $row->pay_admin_proc== 'C'))){
							?>
								<a href="javascript:;" class="btn_mypage_s CLS_cancel_order" ordercode = "<?=$row->ordercode?>">주문취소</a>
							<?
								}else if($row->deli_gbn=="N" &&(strstr("C", $row->paymethod[0]) && $row->pay_flag== '0000' && $row->pay_admin_proc!= 'C')){
							?>
								<a href="javascript:;" class="btn_mypage_s CLS_cancel_order_pay" ordercode = "<?=$row->ordercode?>" sitecd="<?=$pgid_info[ID]?>" sitekey="<?=$pgid_info[KEY]?>">주문취소</a>
							<?
								}else{
									echo "-";
								}
							?>
							
						</td>
					</tr>
<?
	$cnt++;
	}
?>
				</table>
				<div class="paging mt_30"><?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?></div>
				<dl class="attention">
					<dt>유의사항</dt>
					<dd>주문번호를 클릭하시면 주문상세내역을 확인할 수 있습니다.</dd>
					<dd>주문이 완료 후, 재고부족 등으로 인해 품절취소가 될 수 있으니 이점 양해 부탁 드립니다.</dd>
					<dd>주문접수 상태에서는 속성 및 배송지 변경이 가능합니다.</dd>
					<dd>주문상태가 "배송 중"이 되면 상품에 대한 "배송조회"가 가능합니다. (도서/산간 제외, 일반지역 약 1~2일 정도 소요)</dd>
					<dd>주문하신 상품이 업체 직 배송 일 경우 상품별로 따로 배송될 수도 있사오니 양해 부탁 드립니다.</dd>
					<dd>결제완료 후 속성 및 배송지 변경 시 고객센터 및 고객상담으로 연락바랍니다.</dd>
					<div class="btn-place"><a href="#" class="btn-dib-function"><span>1:1 고객상담</span></a></div>
				</dl>
			</div><!-- //최근주문정보 -->

		</div><!-- 내용 -->
	</div>
<style type="text/css">
	.layer {display:none; position:fixed; _position:absolute; top:0; left:0; width:100%; height:100%; z-index:100;}
	.layer .bg {position:absolute; top:0; left:0; width:100%; height:100%; background:#000; opacity:.5; filter:alpha(opacity=50);}
	.layer .pop-layer {display:block;}

	.pop-layer {display:none; position: absolute; top: 50%; left: 50%; width: 410px; height:auto;  background-color:#fff; border: 2px solid #000; z-index: 10;}	
	.pop-layer .pop-container {padding: 20px 25px;}
	.pop-layer p.ctxt {color: #666; line-height: 25px;}
</style>
<div class="layer" style="display: none;">
	<div class="bg"></div>
	<div id="layer1" class="pop-layer">
		<div class="pop-container">
			<div class="pop-conts">
				<!--content //-->
				<h3 class="title mb_20">반송 신청</h3>
				<table class="th_left" cellpadding="0" cellspacing="0" border="0" width="100%">
					<colgroup>
						<col style="width:120px"><col style="width:auto">
					</colgroup>
					<tbody>
					<tr>
						<th>반송사유</th>
						<td>
							<textarea name="redelivery_reason_content" id="redelivery_reason_content" rows="10" style="width: 98%;resize: none;"></textarea>
							<input type="hidden" id="redelivery_ordercode" >
							<input type="hidden" id="redelivery_obj" >
						</td>
					</tr>
					</tbody>
				</table>
				<div class="ta_c mt_20">
					<a href="javascript:;" id="redelivery_reson_on" class="btn_D on">반송신청</a>
					<a href="javascript:;" id="redelivery_reson_close" class="btn_D cbtn">취소</a>
				</div>
				<!--// content-->
			</div>
		</div>
	</div>
</div>

<div id="create_openwin" style="display:none"></div>

<? include($Dir."admin/calendar_join.php");?>
</div>
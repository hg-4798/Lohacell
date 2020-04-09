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


$mb_qry="select * from tblmainbannerimg order by banner_sort";


if (stripos($shop_intro,"<table")!==false || strlen($mainflash)>0)
	$main_banner=$shop_intro;
else
	$main_banner=nl2br($shop_intro);

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


<body<?=($_data->layoutdata["MOUSEKEY"][0]=="Y"?" oncontextmenu=\"return false;\"":"")?><?=($_data->layoutdata["MOUSEKEY"][1]=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=($_data->layoutdata["MOUSEKEY"][2]=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>


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

/*
		$sql=" select sum(t_count) t_count from(";
		$sql.= "SELECT COUNT(*) as t_count FROM sales.tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= " union SELECT COUNT(*) as t_count FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= ") a";
		*/

		$sql = "SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, 1 as ordertype ";
		$sql.= "FROM sales.tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= " union SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, 2 as ordertype ";
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

	
	<div class="containerBody sub_skin">
	
		<!-- LNB -->
		<div class="left_lnb">
			<? include ($Dir.FrontDir."mypage_TEM01_left.php");?> 
			<!---->
		</div><!-- //LNB -->
		
		<!-- 내용 -->
		<div class="right_section">
			
			<h3 class="title mb_20">
				주문/배송 조회
				<p class="line_map"><a>홈</a> &gt; <a>주문정보</a>  &gt;  <a class="on">주문/배송 조회</a></p>
			</h3>
			
			<!-- 날짜 설정 -->
			<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
			<div class="date_find_wrap">
				<ul class="date_setting">
					<li class="title">조회</li>
					<li class="date">
						<div class="input_bg"><input type="text" name="date1" id="" value="<?=$strDate1?>" readonly /></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> ~ 
						<div class="input_bg"><input type="text" name="date2" id="" value="<?=$strDate2?>" readonly /></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> &nbsp;&nbsp;
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
						<a href="javascript:CheckForm();" class="btn_A small">조회</a>
					</li>
				</ul>
			</div><!-- //날짜 설정 -->
			</form>
			
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

			//발송준비
			$step03=pmysql_num_rows(pmysql_query("SELECT a.* FROM tblorderinfo a WHERE a.deli_gbn='S'".$addPayQuery));

			//발송중
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
			$cancle4=pmysql_num_rows(pmysql_query("SELECT a.* FROM tblorderinfo a WHERE ((SUBSTR(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=9) OR (SUBSTR(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag='0000' AND a.pay_admin_proc='C'))".$addPayQuery));
			$cancle = $cancle1+$cancle2+$cancle3+$cancle4;

			?>
			<div class="orderlist_delivery">
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
			<div class="table_wrap mt_30">
				<h3>주문/배송</h3>
				<div class="right_info">
					<div class="select_type open ta_l" style="width:100px; z-index:70">
						<span class="ctrl"><span class="arrow"></span></span>
						<button type="button" class="myValue"><?=$limitpage?>개씩 보기</button>
						<ul class="aList">
							<li><a href="javascript:;" class = 'CLS_limit_page' idx = '10'>10개씩 보기</a></li>
							<li><a href="javascript:;" class = 'CLS_limit_page' idx = '20'>20개씩 보기</a></li>
							<li><a href="javascript:;" class = 'CLS_limit_page' idx = '30'>30개씩 보기</a></li>
						</ul>
					</div>
				</div>
				<!-- 주문/배송 -->
		
				<table class="th_top">
					<colgroup>
						<col width="170" /><col width="85" /><col width="*px" /><col width="145" /><col width="100" /><col width="100" />
					</colgroup>
					<tr>
						<th>주문정보</th>
						<th colspan=2>상품정보</th>
						<th>주문금액</th>
						<th>진행상태</th>
						<th>요청사항</th>
					</tr>

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
						<? $sql2 = "SELECT productcode, productname, opt1_name, opt2_name, deli_com, deli_num FROM tblorderproduct  ";
						$sql2 .= " WHERE ordercode='".$row->ordercode."' ";
						$sql2 .= " AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%')";
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
						$imgsrc = getMaxImageForXn($result2->productcode);	
						?>
						<td><a href="javascript:OrderDetail('<?=$row->ordercode?>')"><?=$row->ordercode?></a></td>
						<td class="ta_l">
							<a href="javascript:OrderDetail('<?=$row->ordercode?>')">
								<img src="<?=$imgsrc?>" alt="" width="75" height="75"/>
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
							<div class="opt"><?=$result2->opt1_name?></div>
							<div class="opt"><?=$result2->opt2_name?></div>
						</td>
						<td><b><?=number_format($row->price)?>원</b></td>
						<td>
							<?
								if ($row->deli_gbn=="C") echo "주문취소";
								else if ($row->deli_gbn=="D") echo "취소요청";
								else if ($row->deli_gbn=="E") echo "환불대기";
								else if ($row->deli_gbn=="X") echo "발송준비";
								else if ($row->deli_gbn=="Y" && $row->receive_ok == '1') echo "<span style='color:#ff7200;'>발송완료</span>";
								else if ($row->deli_gbn=="Y") echo "배송중";
								else if ($row->deli_gbn=="N") {
									if (strlen($row->bank_date)<12 && strstr("BOQ", $row->paymethod[0])) echo "입금확인중";
									else if ($row->pay_admin_proc=="C" && $row->pay_flag=="0000") echo "결제취소";
									else if (strlen($row->bank_date)>=12 || $row->pay_flag=="0000") echo "발송준비";
									else echo "결제확인중";
								} else if ($row->deli_gbn=="S") {
									echo "발송준비";
								} else if ($row->deli_gbn=="R") {
									echo "반송처리";
								} else if ($row->deli_gbn=="H") {
									echo "발송완료 [정산보류]";
								}
								
								if($row->deli_gbn=="Y" && (isdev() && $redaliveryArray->redelivery_type == "Y")){
									echo "<br>";
									echo "<font color='red'>[반송신청]</font>";
								}
							?>
						</td>
						<td>
							
							<? 
								if($row->deli_gbn=="Y"){ 
							?>
								<?if($row->receive_ok == '0'){?><a href="javascript:;" class="btn_mypage_s deli_ok" ordercode = "<?=$row->ordercode?>">수령확인</a><?}?>
								<!-- 반송신청 위치 -->
								<?if($redaliveryArray->redelivery_type != "Y"){?><a href="javascript:;" class="btn_mypage_s CLS_redelivery" ordercode = "<?=$row->ordercode?>">반송신청</a><?}?>
								<a href="javascript:;" class="btn_mypage_s CLS_delivery_tracking" urls = "<?=$delicomlist[$deli_company]->deli_url.$deli_number?>">배송추적</a>
							<?
								}else if($row->deli_gbn=="N" &&((strstr("BOQ", $row->paymethod[0]) && !$row->bank_date) || (strstr("CPMV", $row->paymethod[0]) && $row->pay_flag!= '0000' && $row->pay_admin_proc== 'C'))){
							?>
								<a href="javascript:;" class="btn_mypage_s CLS_cancel_order" ordercode = "<?=$row->ordercode?>">주문취소</a>
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
			</div><!-- //최근주문정보 -->

			<!-- 주문단계안내 -->
			<div class="order_step_info_wrap hide">
				<h3>주문 단계 안내</h3>
				<div class="order_step">
					<dl class="step">
						<dt>주문접수</dt>
						<dd><span>입금전 상태입니다.<br />무통장 입금 주문 후 3일 이내 미입금시 자동 주문취소됩니다.</span></dd>
					</dl>
					<dl class="step">
						<dt>결제완료</dt>
						<dd><span>주문이 결제가 완료된 상태 또는 입금확인이 완료된 상태입니다.</span></dd>
					</dl>
					<dl class="step">
						<dt>배송준비중</dt>
						<dd><span>결제 완료된 주문의 상품을 발송 하기 위하여 상품을 준비하고 있는 단계 입니다.</span></dd>
					</dl>
					<dl class="step">
						<dt>배송중</dt>
						<dd><span>택배사로 상품이 전달되어 고객님께 상품을 전달하는 단계입니다.</span></dd>
					</dl>
					<dl class="step">
						<dt>배송완료</dt>
						<dd><span>고객님께서 상품을 수령하여 배송이 완료된 상태입니다.</span></dd>
					</dl>
				</div>
				<p class="ment">
					궁금하신 사항은 고객센터(02-398-8188) 및 1:1 상담게시판을 이용해 주세요. <a href="/front/mypage_personal.php" class="btn_black_m">1:1고객상담</a>
				</p>
			</div>
			<!-- //주문단계안내 -->

			<!-- 추천상품 -->
			<div class="recommend_product mb_50 hide">
				<h3>가장 많이 본 상품</h3>
				<!--a href="#" class="recommend_product_left left_off"></a>
				<a href="#" class="recommend_product_right"></a-->
				<div class="product_list" style="width:878px">
					<ul class="recommend_list">
						<?
							$sqlVcount = "SELECT * FROM tblproduct order by vcnt desc limit 4 offset 0;";
							$resultVcount=pmysql_query($sqlVcount,get_db_conn());
							while($row=pmysql_fetch_object($resultVcount)) {
								$sellPrice = 0;
								$optcode = substr($row->option1,5,4);
								$miniq = 1;
								if (ord($row->etctype)) {
									$etctemp = explode("",$row->etctype);
									for ($i=0;$i<count($etctemp);$i++) {
										if (strpos($etctemp[$i],"MINIQ=")===0)			$miniq=substr($etctemp[$i],6);
									}
								}
								
								if(strlen($dicker=dickerview($row->etctype,number_format($row->sellprice),1))>0){
									$sellPrice = $dicker;
								} else if(strlen($optcode)==0 && strlen($row->option_price)>0) {
									$sellPrice = $row->sellprice;
								} else if(strlen($optcode)>0) {
									$sellPrice = $row->sellprice;
								} else if(strlen($row->option_price)==0) {
									if($row->assembleuse=="Y") {
										$sellPrice = ($miniq>1?$miniq*$row->sellprice:$row->sellprice);
									} else {
										$sellPrice = $row->sellprice;
									}
								}
								if($row->consumerprice > $sellPrice){
									$strPrice = "<span class='original'>".number_format($row->consumerprice)."</span><span class='off'>".number_format($sellPrice)."원</span>";
								}else{
									$strPrice = "<span class='off'>".number_format($sellPrice)."원</span>";
								}
						?>
						<li>
							<a href="../front/productdetail.php?productcode=<?=$row->productcode?>">
								<?if (strlen($row->maximage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->maximage)) { ?>
									<img src="<?=$Dir.DataDir."shopimages/product/".urlencode($row->maximage)?>" width = '142'>
								<?} else if(strlen($row->maximage)>0 && file_exists($Dir.$row->maximage)){?>
									<img src="<?=$Dir.urlencode($row->maximage)?>" width = '142'>
								<?} else {?>
									<img src="<?=$Dir?>images/no_img.gif" border="0" align="center" width = '142'>
								<?}?>
								<div class="goods_info w140">
									<?=$row->productname?><br />
									<?=$strPrice?>
									<p><img src="../img/icon/icon_p.gif" alt="" /> <?=number_format($row->reserve)?></p>
								</div>
							</a>
						</li>
						<?
							}
						?>
					</ul>
				</div>
			</div><!-- //추천상품 -->

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
					<!--<tr>
						<th>반송사유</th>
						<td>
							<select name="out_reason" id="" class="type01">
									<option value="1">상품 품질 불만</option>
									<option value="2">기타1</option>
									<option value="3">기타2</option>
									<option value="4">기타3</option>
									<option value="5">기타4</option>
									<option value="6">기타5</option>
							</select>
						</td>
					</tr>-->
					<tr>
						<th>반송사유</th>
						<td>
							<textarea name="redelivery_reason_content" id="redelivery_reason_content" rows="10" style="width: 98%;resize: none;"></textarea>
							<input type="hidden" id="redelivery_ordercode" />
							<input type="hidden" id="redelivery_obj" />
						</td>
					</tr>
					</tbody>
				</table>
				<div class="ta_c mt_20">
					<a href="javascript:;" id="redelivery_reson_on" class="btn_D on">반송신청</a>
					<a href="javascript:;" id="redelivery_reson_close" class="btn_D">취소</a>
				</div>
				<!--// content-->
			</div>
		</div>
	</div>
</div>

<div id="create_openwin" style="display:none"></div>

<? include($Dir."admin/calendar_join.php");?>
</div>
</BODY>
</HTML>
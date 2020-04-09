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

$arpm=array("B"=>"무통장","V"=>"계좌이체","O"=>"가상계좌","Q"=>"가상계좌(매매보호)","C"=>"신용카드","P"=>"신용카드(매매보호)","M"=>"핸드폰");

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
		$sql.= "AND deli_gbn IN ('C','D','R','E') ";
		#취소,교환,반품 고정 이므로....
		#if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		#else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		#else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= " union SELECT COUNT(*) as t_count FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		$sql.= "AND deli_gbn IN ('C','D','R','E') ";
		#취소,교환,반품 고정 이므로....
		#if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		#else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		#else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= ") a";
		*/
		
		# 취소/교환/반품/환불
		$sql = "SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, dc_price, reserve, deli_price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, 1 as ordertype ";
		$sql.= "FROM sales.tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		$sql.= "AND (deli_gbn IN ('C','D','R','E') OR ((SUBSTR(paymethod,1,1) IN ('B','O','Q') AND LENGTH(bank_date)=9) OR (SUBSTR(paymethod,1,1) IN ('C','P','M','V') AND pay_flag='0000' AND pay_admin_proc='C')))";
		#관리자 주문 취소 리스트에서 조회하지 않는 항목
		#$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= " union SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, dc_price, reserve, deli_price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, 2 as ordertype ";
		$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		$sql.= "AND (deli_gbn IN ('C','D','R','E') OR ((SUBSTR(paymethod,1,1) IN ('B','O','Q') AND LENGTH(bank_date)=9) OR (SUBSTR(paymethod,1,1) IN ('C','P','M','V') AND pay_flag='0000' AND pay_admin_proc='C')))";
		#관리자 주문 취소 리스트에서 조회하지 않는 항목
		#$sql.= "AND (del_gbn='N' OR del_gbn='A') ";

		$sql.= " union SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, dc_price, reserve, deli_price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, 3 as ordertype ";
		$sql.= "FROM (select a.receive_ok,a.id, a.ordercode,(cancel_sum_price+n.refund) as price, '0' dc_price, '0' reserve, '0' deli_price, a.paymethod, a.pay_admin_proc, a.pay_flag, a.bank_date, a.deli_gbn, a.receipt_yn from tblorderinfo a join ( ";
		$sql.= "SELECT ordercode, SUM( ( price + option_price ) * option_quantity )AS cancel_sum_price from tblorderproductcancel group by ordercode) b on a.ordercode=b.ordercode ";
		$sql.= "left join tblorderproductcancel_fee n on a.ordercode=n.ordercode) as ctoi WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";

		$sql.= "ORDER BY ordercode DESC ";

		//echo $sql;
		
		if(!$limitpage) $limitpage = '10';

		$paging = new Tem001_saveheels_Paging($sql,10,$limitpage,'GoPage',true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;

		$result3=pmysql_query($sql,get_db_conn());
		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		
		//exdebug("##");
 ?>

	
	<div class="containerBody sub-page">
	
		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li><a href="mypage.php">MY PAGE</a></li>
				<li class="on"><a>주문취소교환반품</a></li>
			</ul>
		</div>

		<!-- LNB -->
		<div class="left_lnb">
			<? include ($Dir.FrontDir."mypage_TEM01_left.php");?> 
			<!---->
		</div><!-- //LNB -->
		
		<!-- 내용 -->
		<div class="right_section mypage-content-wrap">

			<div class="cancle-list-wrap">
			<div class="mypage-title">주문취소/교환/반품 대기<span>Total (00)</span></div>
			<!-- 날짜 설정 -->
			<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
			<div class="date_find_wrap">
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

			<table class="th-top util top-line-none">
				<colgroup>
					<col style="width:160px"><col style="width:100px"><col style="width:auto"><col style="width:94px"><col style="width:91px"><col style="width:91px"><col style="width:103px">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">주문정보</th>
						<th scope="col" colspan="2">상품정보/옵션</th>
						<th scope="col">환불금액</th>
						<th scope="col">결제정보</th>
						<th scope="col">접수일자</th>
						<th scope="col">진행단계</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td rowspan="2"><strong>0D201601194092194</strong><br>(2016-02-01)</td>
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
						<td rowspan="2"><strong>200,000</strong></td>
						<td rowspan="2">신용카드</td>
						<td rowspan="2">2016-02-01</td>
						<td rowspan="2">처리중</td>
					</tr>
					<tr>
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
					</tr>
					<tr>
						<td><strong>0D201601194092194</strong><br>(2016-02-01)</td>
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
						<td><strong>200,000</strong></td>
						<td>신용카드</td>
						<td>2016-02-01</td>
						<td>처리중</td>
					</tr>
				</tbody>
			</table>
			<div class="paging mt-20"><a href="#" class="">1</a></div>

			<div class="mypage-title mt-30">주문취소/교환/반품 내역<span>Total (00)</span></div>
			<!-- 날짜 설정 -->
			<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
			<div class="date_find_wrap">
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
			<table class="th-top util top-line-none">
				<colgroup>
					<col style="width:160px"><col style="width:100px"><col style="width:auto"><col style="width:94px"><col style="width:91px"><col style="width:91px"><col style="width:103px">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">주문정보</th>
						<th scope="col" colspan="2">상품정보/옵션</th>
						<th scope="col">환불금액</th>
						<th scope="col">결제정보</th>
						<th scope="col">접수일자</th>
						<th scope="col">진행단계</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong>0D201601194092194</strong><br>(2016-02-01)</td>
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
						<td>-</td>
						<td>-</td>
						<td>2016-02-01</td>
						<td><strong>취소완료</strong></td>
					</tr>
					<tr>
						<td><strong>0D201601194092194</strong><br>(2016-02-01)</td>
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
						<td><strong>200,000</strong></td>
						<td>신용카드</td>
						<td>2016-02-01</td>
						<td><strong>반품완료</strong></td>
					</tr>
					<tr>
						<td><strong>0D201601194092194</strong><br>(2016-02-01)</td>
						<td><img src="../data/shopimages/product/1447637447/1447637447_thum_85X85.jpg" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[브랜드명]</span><br>
							<span>LONG HANDMADE COAT MELANGE GREY</span><br>
							<span>사이즈 : 100 / 색상 : BEIGE / 수량 : 1개</span>
						</td>
						<td>-</td>
						<td>신용카드</td>
						<td>2016-02-01</td>
						<td><strong>교환완료</strong></td>
					</tr>
				</tbody>
			</table>
			<div class="paging mt-20"><a href="#" class="">1</a></div>
			
			<?
			$step01=0; $step02=0; $step03=0; $step04=0; $cancle=0; $refund=0; $change=0; 
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
			?>
			
			<!-- 취소/교환/반품 -->
			<div class="table_wrap mt_30 hide">
				<h3>취소/교환/반품</h3>
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
				<!-- 취소/교환/반품 -->
		
				<table class="th_top">
					<colgroup>
						<col width="170" /><col width="85" /><col width="*px" /><col width="145" /><col width="100" /><col width="100" />
					</colgroup>
					<tr>
						<th>주문정보</th>
						<th colspan=2>상품정보</th>
						<th>환불금액</th>
						<th>결제방법</th>
						<th>주문상태</th>
					</tr>

<?		
		$cnt=0;
		if($t_count){
			while($row=pmysql_fetch_object($result)) {
			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);

			$ord_time=substr($row->ord_time,0,2).":".substr($row->ord_time,2,2).":".substr($row->ord_time,4,2);
	?>
					<tr>
						<? 
						if ($row->ordertype == '3') $tblp	= "tblorderproductcancel";
						else  $tblp	= "tblorderproduct";
						$sql2 = "SELECT productcode, productname, MAX(deli_com) deli_com, MAX(deli_num) deli_num FROM {$tblp}  ";
						$sql2 .= " WHERE ordercode='".$row->ordercode."' ";
						$sql2 .= " AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') AND option_type = 0 group by productcode, productname";
						
						
						//$sql2 = "SELECT c.tinyimage, b.productname FROM tblorderinfo a, tblorderproduct b, tblproduct c WHERE a.ordercode='";
						//$sql2 .= $row->ordercode."' AND a.ordercode = b.ordercode AND b.productcode = c.productcode AND b.option_type = 0 ";
						$cnt2=0;
						$result2=pmysql_fetch_object(pmysql_query($sql2));
						$resultforcnt=pmysql_query($sql2);
						while($row2=pmysql_fetch_object($resultforcnt)){
							$cnt2++;
						}

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
								<img src="<?=$imgsrc?>" alt="" class='img-size-mypage' />
							</a>
						</td>
						<td class="ta_l">
							<a href="javascript:OrderDetail('<?=$row->ordercode?>')">
								<? 
									switch($cnt2){
										case "1" : 
											echo $result2->productname;
										break;
										default : $cnt2--;
											echo $result2->productname." 외 ".$cnt2."건";										
									}							
								?> 
							</a>
							<div class="opt"></div>
						</td>
						<td><b><?=number_format($row->price-$row->dc_price-$row->reserve+$row->deli_price)?>원</b></td>
						<td>
							<?=$arpm[$row->paymethod[0]]?>
						</td>
						<td>
							<?
								/*if ($row->deli_gbn=="C") echo "주문취소";
								else if ($row->deli_gbn=="D") echo "취소요청";
								else if ($row->deli_gbn=="E") echo "환불대기";
								else if ($row->deli_gbn=="X") echo "발송준비";
								else if ($row->deli_gbn=="Y" && $row->receive_ok == '1') echo "<span style='color:red;'>발송완료</span>";
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
								}*/

								if ($row->ordertype == '3') echo "부분취소";
								else echo "주문취소";
							?>
						</td>
				</tr>
	<?
		$cnt++;
		}//while
	}else{
	?>
					<tr>
						<td colspan="7">취소/교환/반품 내역이 없습니다.</td>
					</tr>
	<?php		
	}
?>
				</table>
				<div class="paging mt_30"><?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?></div>
			</div><!-- //최근주문정보 -->

			
		</div><!-- //.cancle-list-wrap -->
		</div><!-- 내용 -->

	</div>


<div id="create_openwin" style="display:none"></div>

<? include($Dir."admin/calendar_join.php");?>
</div>
</BODY>
</HTML>

















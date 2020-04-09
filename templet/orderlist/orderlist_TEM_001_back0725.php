<!-- start container -->
<div id="container">
<?
include ($Dir.FrontDir."mypage_TEM01_left.php");
?>
	<!-- start contents -->
	<div class="contents_side">
		<? include $Dir.FrontDir."mypage_menu.php";?>

<div class="my_order_detail">

             <div class="total_orderlist_title">
			 <span class="total_orderlist_titleimg"><img src="../image/mypage/order_order_title.gif" alt="주문&배송조회"></span>
			 <span class="recently_orderlist_titleimg"><a href="http://soap.soapschool.co.kr/shop/mypage/mypage_orderlist.php" target="_blank"><img src="<?=$Dir?>image/mypage/before.png" alt="이전주문내역" valign="top"/></a></span>
			 </div>

             <div class="total_orderlist_warp">
			 <!--
			 <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
			 	<tr>
					<td align=right>
						<span ><font size=2><B>최근 3개월 구매 내역 : <?=number_format($price_sum)?>원</b></font></span>
					</td>
				</tr>
			 </table>
			-->

             <div class="total_orderlist_bar">
			 <ul>
			 <li class="cell5"><img src="../image/mypage/order_detail_menu00.gif" alt="번호"></li>
			 <li class="cell15"><img src="../image/mypage/order_detail_menu01.gif" alt="주문일시" /></li>
			 <li class="cell15"><img src="../image/mypage/order_detail_menu02.gif" alt="주문번호" /></li>
			 <li class="cell10"><img src="../image/mypage/order_detail_menu07.gif" alt="도/소매"></li>
			 <li class="cell15"><img src="../image/mypage/order_detail_menu03.gif" alt="결제방법" /></li>
			 <li class="cell10"><img src="../image/mypage/order_detail_menu04.gif" alt="주문금액" /></li>
			 <li class="cell10"><img src="../image/mypage/order_detail_menu05.gif" alt="주문상태" /></li>
			 <li class="cell10"><img src="../image/mypage/order_detail_menu06.gif" alt="수령확인" /></li>
			 <li class="cell10"><img src="../image/mypage/order_detail_menu09.gif" alt="영수증신청" /></li>
			 </ul>
			 </div>
			 <div class="total_orderlist_list">
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

		$sql = "SELECT COUNT(*) as t_count FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$paging = new Tem001_Paging($sql,10,10,'GoPage',true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;

		$sql = "SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn ";
		$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= "ORDER BY ordercode DESC ";
		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		*/
		
		$sql=" select sum(t_count) t_count from(";
		$sql.= "SELECT COUNT(*) as t_count FROM sales.tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		//$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= " union SELECT COUNT(*) as t_count FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		//$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= ") a";
		
		$paging = new Tem001_Paging($sql,10,10,'GoPage',true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;

		$sql = "SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, 1 as ordertype ";
		$sql.= "FROM sales.tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		//$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= " union SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, 2 as ordertype ";
		$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		//$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
		if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
		else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
		else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
		$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
		$sql.= "ORDER BY ordercode DESC ";
		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=pmysql_fetch_object($result)) {
		$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);

		$ord_time=substr($row->ord_time,0,2).":".substr($row->ord_time,2,2).":".substr($row->ord_time,4,2);
?>


			 <ul>
			 <input type="hidden" value="<?=$row->ordercode?>" class="ordcode">
			 <li class="cell5"><?=$number?></li>
			 <li class="cell15"><?=$row->ord_date?> <?=$ord_time?></li>
			 <li class="cell15"><?if($row->ordertype=='2'){?><A HREF="javascript:OrderDetail('<?=$row->ordercode?>')"><?}?><?=$row->ordercode?></a></li>
			 <li class="cell10"><?=$row->ordertype=="1"?"도매":"소매";?></li>
			 <li class="cell15">
			 <?
				if (strstr("B", $row->paymethod[0])) echo "무통장입금";
				else if (strstr("V", $row->paymethod[0])) echo "실시간계좌이체";
				else if (strstr("O", $row->paymethod[0])) echo "가상계좌";
				else if (strstr("Q", $row->paymethod[0])) echo "가상계좌-<FONT COLOR=\"#FF0000\">매매보호</FONT>";
				else if (strstr("C", $row->paymethod[0])) echo "신용카드";
				else if (strstr("P", $row->paymethod[0])) echo "신용카드-<FONT COLOR=\"#FF0000\">매매보호</FONT>";
				else if (strstr("M", $row->paymethod[0])) echo "휴대폰";
			 ?>
			 </li>
			 <li class="cell10"><?=number_format($row->price)?></li>
			 <li class="cell10 fontBlue">
			 <?
				if ($row->deli_gbn=="C") echo "주문취소";
				else if ($row->deli_gbn=="D") echo "취소요청";
				else if ($row->deli_gbn=="E") echo "환불대기";
				else if ($row->deli_gbn=="X") echo "발송준비";
				else if ($row->deli_gbn=="Y") echo "<span style='color:red;'>발송완료</span>";
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
			 ?>
			 </li>
			 <li class="cell10">
			 <?
				 if($row->deli_gbn=="Y" && $row->ordertype=='2'){
					if($row->receive_ok!=1){
			 ?>
					<img src="../image/mypage/bt_confirm_recieve.gif" alt="수령확인"  class="deli_ok"/>
			<?
					}else{
						echo "확인완료";
					}
				 }else{
			 ?>
				&nbsp;
			 <?}?>
			 </li>
			 <li class="cell10"><?if($row->receipt_yn!="N" && $row->ordertype=='2'){?><A HREF="javascript:OrderDetail('<?=$row->ordercode?>')"><img src="../image/mypage/bt_receipt_request.gif" alt="영수증신청" /><?}?></a></li>
			 </ul>

<?
	$cnt++;
	}
?>
	<!--
			 <ul>
			  <li class="cell10">11</li>
			 <li class="cell15">2013-12-02 10:18:28</li>
			 <li class="cell15">1306977478497</li>
			 <li class="cell10">&nbsp;</li>
			 <li class="cell10">신용카드</li>
			 <li class="cell10">55,670</li>
			 <li class="cell10 fontRed">배송완료</li>
			 <li class="cell10"><a href="#"><img src="../image/mypage/bt_confirm_recieve.gif" alt="수령확인" /></a></li>
			 <li class="cell10"><a href="#"><img src="../image/mypage/bt_receipt_view.gif" alt="영수증보기" /></a></li>
			 </ul>
		-->
			 </div><!-- total_orderlist_list 끝 -->

			 </div><!-- total_orderlist_warp 끝 -->
			<div class="paging"><?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?></div><!-- paging 끝 -->


		</div><!-- my_order_detail 끝 -->


	</div><!-- //end contents -->
</div><!-- //end container -->


<!--footer start -->
<!--<? include "_footer.html" ; ?>-->
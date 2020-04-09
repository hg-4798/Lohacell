<script type="text/javascript">
	$(function(){
		$('a.pop_benefit').click(function(){
			$('div.pop_member_benefit').css('display','block');
		});
		$('a.close').click(function(){
			$('div.pop_member_benefit').css('display','none');
		});
	});
</script>


<?php
$subTop_flag = 3;
//include ($Dir.MainDir."sub_top.php");
?>
	<div class="containerBody sub_skin">
		<!-- LNB -->
		<div class="left_lnb">
			<? include  "mypage_TEM01_left.php";  ?>			
		</div><!-- //LNB -->
		
		<!-- 내용 -->
		<div class="right_section mb_80">
			
			<h3 class="title mb_20">
				MY PAGE
				<p class="line_map"><a>홈</a> &gt; <a class="on">마이페이지</a></p>
			</h3>


<?		
	##### 최근 주문 정보
	$sql = "SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, dc_price, reserve, deli_price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, 1 as ordertype ";
	$sql.= "FROM sales.tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
	if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
	else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
	else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
	$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
	$sql.= " union SELECT receive_ok,ordercode,cast(substr(ordercode,0,9) as date) as ord_date, substr(ordercode,9,6) as ord_time, price, dc_price, reserve, deli_price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, receipt_yn, 2 as ordertype ";
	$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
	if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
	else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
	else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
	$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
	$sql.= "ORDER BY ordercode DESC ";
	//echo $sql;
	if(!$limitpage) $limitpage = '10';
	$paging = new Tem001_saveheels_Paging($sql, 10, $limitpage, 'GoPage', true);
	$t_count = $paging->t_count;
	$gotopage = $paging->gotopage;

	$sql = $paging->getSql($sql);
	$result=pmysql_query($sql,get_db_conn());
?>
			<!-- 최근주문정보 -->
			<div class="table_wrap">
				<h3>최근주문정보 <span class="total">( Total : <strong><?=$t_count?></strong> )</span></h3>
				<p class="table_info"><a href="<?=$Dir.FrontDir?>mypage_orderlist.php">> 더보기</a></p>
				<table class="th_top">
					<colgroup>
						<col width="170" /><col width="85" /><col width="*px" /><col width="145" /><col width="100" />
					</colgroup>
					<tr>
						<th>주문정보</th>
						<th colspan=2>상품정보</th>
						<th>주문금액</th>
						<th>진행상태</th>
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
						<? $sql2 = "SELECT productcode, productname, MAX(deli_com) deli_com, MAX(deli_num) deli_num FROM tblorderproduct  ";
						$sql2 .= " WHERE ordercode='".$row->ordercode."' ";
						$sql2 .= " AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') AND option_type = 0 group by productcode, productname";
						$res22 = pmysql_query($sql2);
						$result2=pmysql_fetch_object($res22);
						$resultforcnt=pmysql_num_rows($res22);
						$deli_company = "";
						$deli_number = "";

						if($deli_company=="") $deli_company = $result2->deli_com;
						if($deli_number=="") $deli_number = $result2->deli_num;

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
									switch($resultforcnt){
										case 1 : 
											echo $result2->productname;
										break;
										default : $resultforcnt--;
											echo $result2->productname." 외 ".$resultforcnt."건";										
									}							
								?>
							</a>
						</td>
						<td><b><?=number_format($row->price-$row->dc_price-$row->reserve+$row->deli_price)?>원</b></td>
						<td>
							<?
								if ($row->deli_gbn=="C") echo "주문취소";
								else if ($row->deli_gbn=="D") echo "취소요청";
								else if ($row->deli_gbn=="E") echo "환불대기";
								else if ($row->deli_gbn=="X") echo "배송준비";
								else if ($row->deli_gbn=="Y") {
									if ($row->receive_ok == '1') echo "<span style='color:#ff7200;'>배송완료</span>";
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
								
								if($row->deli_gbn=="Y" && $redaliveryArray->redelivery_type == "Y"){
									echo "<br>";
									echo "<font color='red'>[반송신청]</font>";
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


		</div><!-- 내용 -->

	</div>

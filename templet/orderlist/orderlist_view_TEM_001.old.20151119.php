<!-- start container -->
<link rel="stylesheet" type="text/css" href="../css/style.css" />
<div id="container" >



<input type=hidden name=tempkey>
<input type=hidden name=ordercode>
<input type=hidden name=type>
<input type=hidden name=ordercodeid value="<?=$ordercodeid?>">
<input type=hidden name=ordername value="<?=$ordername?>">


	<?
	$subTop_flag = 3;
	//include ($Dir.MainDir."sub_top.php");
	?>
	<div class="containerBody sub_skin">

	<!-- LNB -->
		<div class="left_lnb">
			<? include ($Dir.FrontDir."mypage_TEM01_left.php");?> 
			<!---->
		</div><!-- //LNB -->
		
<?
$sql2 = "SELECT * FROM tblorderinfo WHERE ordercode = '".$_POST["ordercode"]."'";
$row2=pmysql_fetch_object(pmysql_query($sql2,get_db_conn()));
?>
		<!-- 내용 -->
		<div class="right_section mb_80">

			<h3 class="title mb_20 ">
				주문/배송 조회
				<p class="line_map"><a >홈</a> &gt; <a>주문정보</a>  &gt;  <a class="on">주문/배송 조회</a></p>
			</h3>

			<div class="message_wrap ">
				<h5 class="ta_c">주문번호  <?=$row2->ordercode?> 주문/배송 정보</h5>
				<p class="ta_c mt_10">주문일자 :<? echo " ".substr($row2->ordercode,0,4)."년 ".substr($row2->ordercode,4,2)."월 ".substr($row2->ordercode,6,2)."일"; ?>
				</p>
			</div>
			
			<!-- 주문상품 -->
			<div class="table_wrap mt_20">
				<h3>주문상품</h3>
				<table class="th_top">
					<colgroup>
						<col width="85" /><col width="*px" /><col width="145" /><col width="100" /><col width="100" />
					</colgroup>
					<tr>
						<th class="bg_none" colspan=2>상품정보</th>
						<th class="bg_none">수량</th>
						<th class="bg_none">주문금액</th>
						<th class="bg_none">진행상태</th>
					</tr>
	
<?php  
		$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
		$result=pmysql_query($sql,get_db_conn());
		$delicomlist=array();
		while($row=pmysql_fetch_object($result)) {
			$delicomlist[$row->code]=$row;
		}
		pmysql_free_result($result);

		foreach( $prData as $prKey=>$prVal ) {
			// 상품정보 세팅
			$prSql = "SELECT option1, option2, supply_subject, tinyimage  FROM tblproduct WHERE productcode = '".$prVal->productcode."'";
			$prRes = pmysql_query( $prSql, get_db_conn() );
			$prRow = pmysql_fetch_object( $prRes );
			$tmpOpt1 = explode( ',', $prRow->option1 );
			$tmpOpt2 = explode( ',', $prRow->option2 );
			$optPrice = 0;
			if( is_file( $Dir.DataDir.'shopimages/product/'.$prRow->tinyimage ) ){
				$file = $Dir.DataDir.'shopimages/product/'.$prRow->tinyimage;
			} else {
				$file = $Dir."/images/common/noimage.gif";
			}
?>
					<tr>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$prVal->productcode?>">
								<img src="<?=$file?>" border="0" class='img-size-mypage' >
							</a>
						</td>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$prVal->productcode?>"><?=$prVal->productname?></a>
<?php
			foreach( $optData[$prVal->productcode] as $optKey=>$optVal ) {
				$optStr = '';
				if( strpos( $prRow->supply_subject, $optVal->opt1_name ) === false ){
					$optStr = $tmpOpt1[0].' : '.$optVal->opt1_name;
					if( strlen( trim( $optVal->opt2_name ) ) > 0 ){
						$optStr.= ' , '.$tmpOpt2[0].' : '.$optVal->opt2_name;
					}
					$optStr.= ' ( '.$optVal->option_quantity.' 개 '.number_format( $optVal->option_price * $optVal->option_quantity ).' 원 )' ;
				} else {
					$optStr = $optVal->opt1_name.' : '.$optVal->opt2_name;
					$optStr.= ' ( '.$optVal->option_quantity.' 개 '.number_format( $optVal->option_price * $optVal->option_quantity ).' 원 )' ;
				}
				$optPrice += $optVal->option_price * $optVal->option_quantity;
?>
							<div class="opt">
								<?=$optStr?>
							</div>
<?php
			}
?>
						</td>
						<td><?=$prVal->quantity?>개</td>
						<td><b><?=number_format( ( $prVal->price * $prVal->quantity ) + $optPrice)?>원</b></td>
						<td>
<?php
				if ($prVal->deli_gbn=="C") echo "주문취소";
					else if ($prVal->deli_gbn=="D") echo "취소요청";
					else if ($prVal->deli_gbn=="E") echo "환불대기";
					else if ($prVal->deli_gbn=="X") echo "발송준비";
					else if ($prVal->deli_gbn=="Y") echo "<span style='color:#ff7200;'>발송완료</span>";
					else if ($prVal->deli_gbn=="N") {
						if (strlen($prVal->bank_date)<12 && strstr("BOQ", $prVal->paymethod[0])) echo "입금확인중";
						else if ($prVal->pay_admin_proc=="C" && $prVal->pay_flag=="0000") echo "결제취소";
						else if (strlen($prVal->bank_date)>=12 || $prVal->pay_flag=="0000") echo "발송준비";
						else echo "결제확인중";
					} else if ($prVal->deli_gbn=="S") {
						echo "발송준비";
					} else if ($prVal->deli_gbn=="R") {
						echo "반송처리";
					} else if ($prVal->deli_gbn=="H") {
						echo "발송완료 [정산보류]";
				}
?>
						</td>
					</tr>
<?php
			unset($tmpOpt1);
			unset($tmpOpt2);
			pmysql_free_result( $prRes );
		} 
?>
				</table>
			</div><!-- //주문상품 -->

			<!-- 결제정보 -->
			<div class="table_wrap mt_20">
				<h3>결제정보</h3>
				<table class="th_left" summary="결제정보">
					<colgroup>
						<col style="width:121px" /><col style="width:auto" /><col style="width:121px" /><col style="width:auto" />
					</colgroup>
					<tbody>
						<tr>
							<th scope="col">결제금액</th>
							<td class="text_only red"><b><?=number_format($_ord->price)?>원</b></td>
							<th scope="col">결제수단</th>
							<td class="text_only">
<?php
			switch($_ord->paymethod[0]){
				case "B" : echo "무통장입금";
						break;
				case "O" : echo "가상계좌";
						break;
				case "M" : echo "핸드폰 결제";
						break;
				case "P" : echo "매매보호 신용카드";
						break;		
				case "C" : echo "신용카드";
						break;
				case "V" : echo "실시간 계좌이체";
						break;
			}
?>
							</td>
						</tr>
<?php
			if( $_ord->reserve > 0 ){
?>
						<tr>
							<th scope="col">적립금 사용액</th>
							<td class="text_only red" colspan='3' ><b><?=number_format($_ord->reserve)?>원</b></td>
						</tr>
<?php
			}
?>
					</tbody>
				</table>
			</div><!-- //결제정보 -->

			<!-- 배송정보 -->
			<div class="table_wrap mt_20">
				<h3>배송정보</h3>
				<table class="th_left" summary="배송정보">
					<colgroup>
						<col style="width:121px" /><col style="width:auto" /><col style="width:121px" /><col style="width:auto" />
					</colgroup>
					<tbody>
						<tr>
							<th scope="col">결제금액</th>
							<td class="text_only red"><b><?=number_format($_ord->price)?>원</b></td>
							<th scope="col">결제수단</th>
							<td class="text_only">
<?php
			switch($_ord->paymethod[0]){
				case "B" : echo "무통장입금";
						break;
				case "O" : echo "가상계좌";
						break;
				case "M" : echo "핸드폰 결제";
						break;
				case "P" : echo "매매보호 신용카드";
						break;		
				case "C" : echo "신용카드";
						break;
				case "V" : echo "실시간 계좌이체";
						break;
			}
?>
							</td>
						</tr>
						<tr>
							<th scope="col">주소</th>
							<td class="text_only" colspan=2><?=$_ord->address?></td>
<?php
			if($_ord->deli_gbn == "N"){
?>
								<td class="ta_r"><!-- <a href="#" class="btn_mypage_s">배송지 변경</a> --></td>
<?php
			}else{ 
?>
								<td class="ta_r"></td>	
<?php
			} 
?>													
						</tr>
<?php
			if( strlen( trim( $_ord->overseas_code ) ) > 0 ) {
?>
						<tr>
							<th scope="col">통관번호</th>
							<td colspan='2' ><?=$_ord->overseas_code?></td>
							<td class="ta_r"></td>	
						</tr>
<?php
			}
?>
					</tbody>
				</table>
			</div><!-- //배송정보 -->

			<div class="ta_c mt_40"><a href="javascript:history.go(-1)" class="btn_D">뒤로</a></div>
			

		</div><!-- 내용 -->

	</div>


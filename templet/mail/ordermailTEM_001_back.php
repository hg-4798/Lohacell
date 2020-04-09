<!DOCTYPE html PUBtdC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=8;IE=EDGE">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>주문 완료</title>
</head>
<body>
<style type="text/css">
body {padding:0px; margin:0px;}
a , a:link , a:visited , a:active , a:hover , img {text-decoration:none; outline:0;border:none; color:#5e5e5e;}
</style>
<div style="width:684px; margin:0 auto; font-size:12px; color:#5e5e5e; font-family:dotum; text-align:left; border:1px solid #000">
<table width="684" cellpadding="0" cellspacing="0" border="0" align="center" >
	<tr>
		<td align="center">
<!-- 상단 -->
<table width="664" cellpadding="0" cellspacing="0" border="0" align="center" style="font-size:12px; color:#5e5e5e; font-family:dotum;">
	<tr><td colspan="2" height="15"></td></tr>
	<tr>
		<td align="left"><a href="http://<?=$shopurl?>" target="_blank"><img src="http://<?=$shopurl?>/img/auto_mail/logo.jpg" alt="로고" /></a></td>
		<td align="right" valign="bottom" style="font-family:tahoma; font-size:11px; color:#505050"><b><?=date("Y.m.d")?></b></td>
	</tr>
	<tr><td colspan="2" height="15"></td></tr>
	<tr><td colspan="2" height="2" bgcolor="#505050"></td></tr>
	<tr height="260">
		<td colspan="2" align="center"><img src="http://<?=$shopurl?>/img/auto_mail/ment_order_ok.jpg" alt="주문이 완료 되었습니다." /></td>
	</tr>
</table><!-- //상단 -->

<!-- 내용 -->
<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="font-size:12px; color:#5e5e5e; font-family:dotum;">
	<tr>
		<td align="left">
			<div><b><span style="color:#000">[NAME]</span> 고객님, 안녕하세요!</b></div>
			<div style="padding-top:5px">
			[CURDATE] 주문이 완료되었습니다.<br />
			[SHOP]에서 주문해주셔서 감사합니다.<br />
			입금 방법이 무통장입금의 경우 계좌번호를 메모하세요
			</div>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>

				<table  width="600" cellpadding="0" cellspacing="0" border="0" style="font-size:12px; color:#5e5e5e; font-family:dotum;">
					<caption style="font-size:14px;font-weight:bold; text-align:left; background-color:#fff">주문상세내역</caption>
					<colgroup>
						<col style="width:80px" /><col style="width:280px" /><col style="width:70px" /><col style="width:70px" /><col style="width:100px" />
					</colgroup>
					<tr><td colspan="5" height="2" bgcolor="#505050"></td></tr>
					<tr height="30" style="background-color:#fafafa;">
						<th colspan="2">상품명</th>
						<th>수량</th>
						<th>적립금</th>
						<th>주문금액</th>
					</tr>
					<tr><td colspan="5" height="1" bgcolor="#d5d5d5"></td></tr>



				<?php
					##### 주문 상세 내역 부분
					$ordprd_wrap = "";
					foreach($prdata as $v){
						$sql_pdetail = "SELECT productname, tinyimage FROM tblproduct ";
						$sql_pdetail.= "WHERE productcode = '".$v->productcode."' ";
						$res_pdetail = pmysql_query($sql_pdetail,get_db_conn());
						$_pdetail = pmysql_fetch_object($res_pdetail); 
				?>
					<tr>
						<td>
						<?php if($_pdetail->tinyimage){ ?>
							<a href="#">
								<img src="http://<?=$shopurl."/data/shopimages/product/".$_pdetail->tinyimage?>" alt="가로 세로 60px 사이즈 이미지" />
							</a>
						<?php } ?>
						</td>
						<td align="left">
							<a href="#">
							<?=$_pdetail->productname?> <br />
<?php
						$opSql = "SELECT opt1_name, opt2_name, quantity, reserve, price, option_quantity, option_price FROM tblorderproduct WHERE ordercode = '".$v->ordercode."' AND productcode = '".$v->productcode."' ";
						$opRes = pmysql_query( $opSql, get_db_conn() );
						$opQty = 0;
						$opReserve = 0;
						$opPrice = 0;

						while( $opRow = pmysql_fetch_object( $opRes ) ) {
?>
<?php 
							if($opRow->opt1_name){ 
?>
							<span style="font-size:11px; color:#a0a0a0"><?=$opRow->opt1_name?></span>
<?php 
							}
							if($opRow->opt2_name){ 
?>
							<br /><span style="font-size:11px; color:#a0a0a0"><?=$opRow->opt2_name?></span>
<?php 
							}
							$opQty += $opRow->quantity;
							$opReserve += $opRow->reserve * $opRow->quantity;
							$opPrice += ( $opRow->price + $opRow->option_price ) * $opRow->option_quantity;
						}
?>
							</a>
						</td>
						<td align="center"><?=$opQty?></td>
						<td align="center"><?=number_format($opReserve)?> 원</td>
						<td align="center"><b style="color:#9d544d"><?=number_format($opPrice)?></b>원</td>
					</tr>
				<?php
					}
				?>
					<tr><td colspan="5" height="1" bgcolor="#d5d5d5"></td></tr>
				</table>

		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>

				<?php
					##### 결제 방법
					$paymemt_type = "";
					if(strstr("VCPM", $_ord->paymethod[0])) {
						$arpm=array("V"=>"실시간계좌이체","C"=>"신용카드","P"=>"매매보호 - 신용카드", "M"=>"핸드폰");
						$paymemt_type = $arpm[$_ord->paymethod[0]];

						if ($_ord->pay_flag=="0000") {
							if(strstr("CP", $_ord->paymethod[0])) {
								$paymemt_type.="(승인번호 : {$_ord->pay_auth_no}) ";
							} else {
								$paymemt_type.="(결제가 <font color=blue>정상처리</font> 되었습니다.)";
							}
						} else if(ord($_ord->pay_flag))
							$paymemt_type.="(거래결과 : <font color=red><b><u>{$_ord->pay_data}</u></b></font>)\n";
						else
							$paymemt_type.="(<font color=red>(지불실패)</font>)";

						if (strstr("CPM", $_ord->paymethod[0]) && $_data->card_payfee>0){
							//$paymemt_type.="<br>&nbsp\n".$arpm[$_ord->paymethod[0]]." 결제시 현금 할인가 적용이 안됩니다.";
						}

					} else if (strstr("BOQ", $_ord->paymethod[0])) {
						if(strstr("B", $_ord->paymethod[0])) $paymemt_type.="무통장 입금 - <font color=#0054A6>{$_ord->pay_data}</font>&nbsp<br />(입금확인후 배송이 됩니다.)";
						else {
							if($_ord->pay_flag=="0000") $msg = "&nbsp(입금확인후 배송이 됩니다.)";
							if(strstr("O", $_ord->paymethod[0])) $paymemt_type.="가상계좌 : <font color=#0054A6>{$_ord->pay_data}</font> ".$msg;
							else if(strstr("Q", $_ord->paymethod[0])) $paymemt_type.="매매보호 - 가상계좌 : <font color=#0054A6>{$_ord->pay_data}</font> ".$msg;
						}
					}
				?>
				<table  width="600" cellpadding="0" cellspacing="0" border="0" style="font-size:12px; color:#5e5e5e; font-family:dotum;">
					<caption style="font-size:14px;font-weight:bold; text-align:left; background-color:#fff">주문자 정보</caption>
					<colgroup>
						<col style="width:130px" /><col style="width:auto" />
					</colgroup>
					<tr><td colspan="2" height="2" bgcolor="#505050"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>주문번호</b></td>
						<td style="text-indent:10px; color:#d31145; font-weight:bold"><?=$ordercode?></td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>주문일자</b></td>
						<td style="text-indent:10px"><?=$curdate?></td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>이름</b></td>
						<td style="text-indent:10px"><?=$_ord->sender_name?></td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>전화번호</b></td>
						<td style="text-indent:10px"><?=$_ord->sender_tel?></td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>이메일</b></td>
						<td style="text-indent:10px"><?=$email?></td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>결제방법</b></td>
						<td style="text-indent:10px"><?=$paymemt_type?></td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>잔여 적립금</b></td>
						<td style="text-indent:10px">2,300</td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
				</table>

		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>

			<?php
					#### 전화번호
					$receivertell_arr = array();
					if($_ord->receiver_tel1){
						$receivertell_arr[] = $_ord->receiver_tel1;
					}
					if($_ord->receiver_tel2){
						$receivertell_arr[] = $_ord->receiver_tel2;
					}
					$receivertell = implode(",",$receivertell_arr);
			?>
				<table  width="600" cellpadding="0" cellspacing="0" border="0" style="font-size:12px; color:#5e5e5e; font-family:dotum;">
					<caption style="font-size:14px;font-weight:bold; text-align:left; background-color:#fff">배송정보</caption>
					<colgroup>
						<col style="width:130px" /><col style="width:auto" />
					</colgroup>
					<tr><td colspan="2" height="2" bgcolor="#505050"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>주문일자</b></td>
						<td style="text-indent:10px"><?=$curdate?></td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>이름</b></td>
						<td style="text-indent:10px"><?=$_ord->receiver_name?></td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>전화번호</b></td>
						<td style="text-indent:10px"><?=$receivertell?></td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>주소</b></td>
						<td style="text-indent:10px"><?=$_ord->receiver_addr?></td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>주문메세지</b></td>
						<td style="text-indent:10px"><?=$_ord->order_msg?></td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="130">
						<td colspan="2" align="center">
							<a href="http://<?=$shopurl?>" target="_blank"><img src="http://<?=$shopurl?>/img/auto_mail/btn_go_site.gif" alt="바로가기" /></a>
						</td>
					</tr>
				</table>

		</td>
	</tr>
</table><!-- //내용 -->

<!-- 푸터 -->
<table width="664" cellpadding="0" cellspacing="0" border="0" align="center" style="font-size:12px; color:#5e5e5e; font-family:dotum;">
	<tr>
		<td style="padding-bottom:10px"><img src="http://<?=$shopurl?>/img/auto_mail/footer.jpg" alt="" usemap="#Map" border="0" /></td>
	</tr>
</table><!-- //푸터 -->
		</td>
	</tr>
</table>
</div>


<map name="Map" id="Map">
  <area shape="rect" coords="53,87,112,160" href="http://<?=$shopname?>" target="_blank" />
</map>
</body>
</html>
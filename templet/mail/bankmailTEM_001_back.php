<!DOCTYPE html PUBtdC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=8;IE=EDGE">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>입금확인</title>
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
		<td align="left"><a href="http://[URL]" target="_blank"><img src="http://[URL]/img/auto_mail/logo.jpg" alt="로고" /></a></td>
		<td align="right" valign="bottom" style="font-family:tahoma; font-size:11px; color:#505050"><b><?=date("Y.m.d")?></b></td>
	</tr>
	<tr><td colspan="2" height="15"></td></tr>
	<tr><td colspan="2" height="2" bgcolor="#505050"></td></tr>
	<tr height="260">
		<td colspan="2" align="center"><img src="http://[URL]/img/auto_mail/ment_order_ok.jpg" alt="입금확인이 되었습니다." /></td>
	</tr>
</table><!-- //상단 -->

<!-- 내용 -->
<table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="font-size:12px; color:#5e5e5e; font-family:dotum;">
	<tr>
		<td align="left">
			<div><b><span style="color:#000">[NAME]</span> 고객님, 안녕하세요!</b></div>
			<div style="padding-top:5px">
			[ORDERDATE] 주문에 대한 입금이 확인되었습니다.<br />
			물품확인 후 빠른시일내에 배송해 드리겠습니다.
			</div>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>

				<table  width="600" cellpadding="0" cellspacing="0" border="0" style="font-size:12px; color:#5e5e5e; font-family:dotum;">
					<colgroup>
						<col style="width:130px" /><col style="width:auto" />
					</colgroup>
					<tr><td colspan="2" height="2" bgcolor="#505050"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>주문번호</b></td>
						<td style="text-indent:10px">[ORDERCODE]</td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>주문일자</b></td>
						<td style="text-indent:10px">[ORDERDATE]</td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>이름</b></td>
						<td style="text-indent:10px">[NAME]</td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>결제방법</b></td>
						<td style="text-indent:10px">[PAYTYPE]</td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="30"  align="left">
						<td bgcolor="#fafafa" style="text-indent:10px"><b>주문금액</b></td>
						<td style="text-indent:10px"><b style="color:#d31145">[PRICE]</b>원</td>
					</tr>
					<tr><td colspan="2" height="1" bgcolor="#d5d5d5"></td></tr>
					<tr height="130"  align="left">
						<td colspan="2" align="center">
							<a href="http://[URL]" target="_blank"><img src="http://[URL]/img/auto_mail/btn_go_site.gif" alt="바로가기" /></a>
						</td>
					</tr>
				</table>

		</td>
	</tr>
</table><!-- //내용 -->

<!-- 푸터 -->
<table width="664" cellpadding="0" cellspacing="0" border="0" align="center" style="font-size:12px; color:#5e5e5e; font-family:dotum;">
	<tr>
		<td style="padding-bottom:10px"><img src="http://[URL]/img/auto_mail/footer.jpg" alt="푸터" usemap="#Map" border="0" /></td>
	</tr>
</table><!-- //푸터 -->
		</td>
	</tr>
</table>
</div>


<map name="Map" id="Map">
  <area shape="rect" coords="53,87,112,160" href="http://[URL]" target="_blank" />
</map>
</body>
</html>
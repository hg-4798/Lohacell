<?php
/********************************************************************* 
// 파 일 명		: joincertmailTEM_001.php
// 설     명		: 회원 인증 메일 HTML
// 상세설명	: 이메일 인증을 통한 회원가입시 보내는 HTML
// 작 성 자		: 2015.10.29 - 김재수
// 수 정 자		: 
// 
// 
*********************************************************************/ 
?>
<!DOCTYPE html PUBtdC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=8;IE=EDGE">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>회원 인증 메일</title>
</head>
<body>
<style type="text/css">
body {padding:0px; margin:0px;}
a , a:link , a:visited , a:active , a:hover , img {text-decoration:none; outline:0;border:none;}
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
		<td align="right" valign="bottom" style="font-family:tahoma; font-size:11px; color:#505050"><b>[CURDATE]</b></td>
	</tr>
	<tr><td colspan="2" height="15"></td></tr>
	<tr><td colspan="2" height="2" bgcolor="#505050"></td></tr>
</table><!-- //상단 -->

<!-- 내용 -->
<table width="664" cellpadding="0" cellspacing="0" border="0" align="center" style="font-size:12px; color:#5e5e5e; font-family:dotum;">
	<tr>
		<td align="center">
			<a href="http://[URL]/front/member_agree.php" target="_blank"><img src="http://www.allfstore.com/img/_temp/ac_mail.jpg" border="0" width="664"/></a>
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
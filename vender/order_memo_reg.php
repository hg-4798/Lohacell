<?php
#---------------------------------------------------------------
# 기본정보 설정파일을 가져온다.
#---------------------------------------------------------------
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/venderlib.php");
	include("access.php");

#---------------------------------------------------------------
# 넘어온 값들을 정리한다.
#---------------------------------------------------------------

$mode		= $_POST['mode'];
$ordercode	= $_POST['ordercode'];
$om_no		= $_POST['om_no'];
$memo_id	= $_POST['memo_id'];
$up_memo	=trim($_POST["up_memo"]);

if($mode=="insert_exe") {
	if(ord($up_memo)) {
		$sql = "INSERT INTO tblorder_memo(ordercode, memo, memo_id, memo_id_type, regdt) VALUES (
		'{$ordercode}', '{$up_memo}', '{$memo_id}', 'V', '".date("Y-m-d H:i:s")."')";
		pmysql_query($sql,get_db_conn());

		$header=getMailHeader('입점사:'.$_venderdata->brand_name,'');
		$email = "kim_jiyoung@deco.co.kr";
		//$email = "lee_sungkyung@deco.co.kr";
		//$email = "kotoring@commercelab.co.kr";
		if($productcode){
			$subject = $ordercode.'상품 메모가 등록되었습니다';
		}else{
			$subject = $ordercode.'주문 메모가 등록되었습니다';
		}
		$body ='메모 내용:<br>'.$up_memo;
		$body .='<br>작성자 :'.$_venderdata->brand_name;
		sendmail($email, $subject, $body, $header);

		echo "<script>alert('등록되었습니다.');parent.window.opener.location.reload();parent.window.close();</script>";
		exit;
	} else {		
		echo "<script>alert('메모를 입력해 주세요.');</script>";
		exit;
	}
} else if($mode=="update_exe") {
	if(ord($up_memo)) {
		$sql = "UPDATE tblorder_memo SET memo='{$up_memo}' WHERE om_no='{$om_no}' ";
		pmysql_query($sql,get_db_conn());	
		echo "<script>alert('수정되었습니다.');parent.window.opener.location.reload();parent.window.close();</script>";
		exit;
	} else {	
		echo "<script>alert('메모를 입력해 주세요.');</script>";
		exit;
	}
} else if($mode=="del_exe") {
	if(ord($om_no)) {
		$sql = "DELETE FROM tblorder_memo WHERE om_no='{$om_no}' ";
		pmysql_query($sql,get_db_conn());	
		echo "<script>alert('삭제되었습니다.');parent.location.reload();</script>";
		exit;
	} else {	
		echo "<script>alert('삭제할 메모번호가 없습니다.');</script>";
		exit;
	}
}

if($om_no){
	$sql ="select * from tblorder_memo where om_no = '".$om_no."'";
	$result = pmysql_query($sql);
	$data=pmysql_fetch($result);

	$memo_id = $data['memo_id'];
	$memo = $data['memo'];
}else{
	$memo_id = $_VenderInfo->getId();
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>주문 <?=$ordercode?>에 대한 메모 등록</title>
<link rel="stylesheet" href="style.css" type="text/css">
<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
document.onkeydown = CheckKeyPress;
document.onkeyup = CheckKeyPress;
function CheckKeyPress() {
	ekey = event.keyCode;

	if(ekey == 38 || ekey == 40 || ekey == 112 || ekey ==17 || ekey == 18 || ekey == 25 || ekey == 122 || ekey == 116) {
		event.keyCode = 0;
		return false;
	}
}

function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 16;
	var oHeight = document.all.table_body.clientHeight + 75;

	window.resizeTo(oWidth,oHeight);
}

function formSubmit() {
	if (document.form1.up_memo.value == '')
	{
		alert("메모를 입력해 주세요.");
		document.form1.up_memo.focus();
		return;
	}
	
	document.form1.target = "HiddenFrame";
	document.form1.submit();
}
//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">

<TABLE WIDTH="550" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed;" id=table_body>
<tr>
	<td>
	<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><img src="images/newtitle_icon.gif" border="0" width="29" height="31"></td>
			<td width="100%" background="images/member_mailallsend_imgbg.gif">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><b><font color="white">주문 <?=$ordercode?>에 대한 메모 등록</b></font></td>
			</tr>
			</table>
			</td>
			<td align="right"><img src="images/member_mailallsend_img2.gif" width="20" height="31" border="0"></td>
		</tr>
		</table>
		</TD>
	</TR>
	<TR>
		<TD height="10"></TD>
	</TR>
	<tr>
		<TD style="padding-left:4pt;padding-right:4pt;" valign="top">
		<table align="center" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td style="padding-top:2pt; padding-bottom:2pt;" class="font_size"><span style="letter-spacing:-0.5pt;">해당 주문에 대한 사항을 메모하세요.</td>
		</tr>
		<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
		<input type=hidden name=mode value="<?=$mode?>_exe">
		<input type=hidden name=ordercode value="<?=$ordercode?>">
		<input type=hidden name=om_no value="<?=$om_no?>">
		<input type="hidden" name="memo_id" value="<?=$memo_id?>">
		<tr>
			<td style="padding-top:2pt; padding-bottom:2pt;">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr align=center>
				<td><textarea name="up_memo" style="width:100%;height:200px" class="question_contents"><?=($memo)?></textarea></td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</TD>
	</tr>
	<tr>
		<td><hr size="1" align="center" color="#EBEBEB"></td>
	</tr>
	<TR>
		<TD align=center>
			<a href="javascript:formSubmit()"><img src = 'images/btn_modify.gif' border="0" vspace="2" border=0></a>
			<a href="javascript:window.close()"><img src="images/btn_close.gif" border="0" vspace="2" border=0></a>
		</TD>
	</TR>
	</form>
	</table>
	</td>
</tr>
</TABLE>

<IFRAME name="HiddenFrame" width=0 height=0 frameborder=0 scrolling="no" marginheight="0" marginwidth="0"></IFRAME>

<?=$onload?>
</body>
</html>

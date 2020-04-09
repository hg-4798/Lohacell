<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

if(ord($_ShopInfo->getId())==0){
	echo "<script>alert('정상적인 경로로 접근하시기 바랍니다.');window.close();</script>";
	exit;
}

$idx=$_POST["idx"];
$mode=$_POST["mode"];
$re_content=$_POST["re_content"];
$re_subject=$_POST["re_subject"];
$re_id = $_ShopInfo->id;

$sql = "SELECT * FROM tblpersonal WHERE idx='{$idx}' ";
$result=pmysql_query($sql,get_db_conn());
$data=pmysql_fetch_object($result);
pmysql_free_result($result);
if(!$data) {
	echo "<script>alert(\"해당 게시물이 존재하지 않습니다.\");window.close();</script>";
	exit;
}

list($productname)=pmysql_fetch("SELECT productname FROM tblproduct WHERE productcode = '".$data->productcode."'");

if(ord($data->email)==0) $data->email="메일 입력이 안되었습니다.";
if(strlen($data->re_date)==14) $data->reply="<img src=\"images/icon_finish.gif\" border=\"0\">";
else $data->reply="<img src=\"images/icon_nofinish.gif\" border=\"0\">";

if($mode=="update" && ord($re_content)) {

    $re_content = pg_escape_string($re_content);
	$sql = "UPDATE tblpersonal SET ";
	$sql.= "re_date			= '".date("YmdHis")."', ";
	$sql.= "re_subject		= '{$re_subject}', ";
	$sql.= "re_id				= '{$re_id}', ";
	$sql.= "re_content	= '{$re_content}', ";
	$sql.= "re_writer		= '{$_ShopInfo->name}' ";
	$sql.= "WHERE idx='{$idx}' ";
	pmysql_query($sql,get_db_conn());

	$temp_phone = '';
	$temp_name = '';
	
	if(ord($data->email) && $data->chk_mail == 'Y') {
		$info_email=$_shopdata->info_email;
		$shopname=$_shopdata->shopname;

		//오늘 날짜
		$curdate = date( "Y.m.d" );
		$curdate2 = date( "Y-m-d H:i:s" );
		
		//작성일
		$personaldate = substr($data->date,0,4).".".substr($data->date,4,2).".".substr($data->date,6,2);

		$shopurl=$_ShopInfo->getShopurl();
		$temp_phone = $data->mobile;
		$temp_name = $data->name;
	}

    //1:1문의 답변메일 발송
    if($data->email !="@"){
        $mail = new MAIL;
        $mail->send_mail('inquiry_answer', $idx);
    }

    //1:1문의 답변  sms 발송
    if(strlen($data->mobile) >= 12) {
        $sms = new SMS;
        $sms->send_sms('MEMBER_001', $idx);
    }
	
	// 1:1 알림톡
	//$alim = new ALIM_TALK();
	// 알림톡 삽입 2017-05-04
	//$alim->makeAlimTalkSearchData('', 'SCC06',$data->HP);		// 1:1 문의
	//exit();	
	if( !pmysql_error() ){
		echo "<script>alert(\"해당 게시글에 대한 답변이 완료되었습니다.\");opener.location.reload();window.close();</script>";
		exit;
	}else{
		alert_go('오류가 발생하였습니다.', $_SERVER['REQUEST_URI']);
	}
} elseif ($mode=="delete") {
	$sql = "DELETE FROM tblpersonal WHERE idx='{$idx}' ";
	pmysql_query($sql,get_db_conn());
	echo "<script>alert(\"해당 게시글을 삭제하였습니다.\");opener.location.reload();window.close();</script>";
	exit;
}
?><html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=utf-8'>
<title>1:1 고객 게시판</title>
    <link rel="stylesheet" href="/admin/static/css/admin.css" type="text/css">
    <link rel="stylesheet" href="/admin/static/css/style.css" type="text/css">
<script type="text/javascript" src="/third_party/SE2/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">

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
	var oWidth = 820;
	var oHeight = 680;

	window.resizeTo(oWidth,oHeight);
}

function CheckForm(form) {
	if(form.re_content.length==0) {
		alert("답변 내용을 입력하세요.");
		form.re_content.focus();
		return;
	}

	form.mode.value="update";
	form.submit();
}

function CheckDelete() {
	if(confirm("해당 게시글을 삭제하시겠습니까?")) {
		document.form1.mode.value="delete";
		document.form1.submit();
	}
}

</SCRIPT>
</head>
<meta http-equiv='Content-Type' content='text/html;charset=utf-8'>
<title>1:1 고객 게시판 문의내용 및 답변하기</title>

<div class="pop_top_title"><p>1:1 고객 게시판 문의내용 및 답변하기</p></div>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 style="overflow-x:hidden;" onLoad="PageResize();">

<TABLE>
<TR>
	<TD style="padding:6pt;">
	<table cellpadding="0" cellspacing="0" width="780px">
	<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
	<input type=hidden name=mode>
	<input type=hidden name=idx value="<?=$idx?>">
	<tr>
		<td width="100%">
        <div class="table_style01">
		<TABLE cellpadding="0" cellspacing="0" width="">
		<col width = '20%'><col width = '*%'>
		<TR>
			<th><span>회원명</span></th>
			<TD class="td_con1"><B><span class="font_blue"><a href="javascript:;" onClick="javascript:CrmView('<?=$data->id?>');"><?=$data->name?></B><?php if($data->id){ echo "(".$data->id.")"; }?></span></a></TD>
		</TR>
		<TR>
			<th><span>제목</span></th>
			<TD class="td_con1"><?=$data->subject?></TD>
		</TR>
		<?if($productname){?>
		<TR>
			<th><span>문의상품</span></th>
			<TD class="td_con1"><?=$productname?></TD>
		</TR>
		<?}?>
		<TR>
			<th><span>메일</span></th>
			<TD class="td_con1"><a href="mailto:<?=$data->email?>"><?=$data->email?></a></TD>
		</TR>
		<!--  
		<tr>
			<th><span>답변타입</span></th>
			<TD class="td_con1">휴대폰 : <?=$data->chk_sms?>  이메일 : <?=$data->chk_mail?></TD>
		</tr>
		-->
		<tr>
			<th><span>답변여부</span></th>
			<TD class="td_con1"><?=$data->reply?>
        <?
        if(strlen($data->re_date)==14) {
        ?>
                <br> <span>답변자 : <?=$data->re_id." (".$data->re_writer.")"?></span>
        <?
        }
        ?>
            </TD>
		</tr>
		<tr>
			<th><span>내용</span></th>
			<TD class="td_con1"><?=nl2br($data->content)?></TD>
		</tr>
		<tr>
			<th><span>답변 제목</span></th>
			<TD class="td_con1">
				<p align="left"><INPUT wrap=off  maxLength=200 size=70 name=re_subject value="<?=$data->re_subject?>" style="width:100%" class="input">
			</TD>
		</tr>
		<tr>
			<th><span>답변 내용</span></th>
			<TD class="td_con1"><TEXTAREA style="width:95%;height:205" id="ir1" name=re_content class="textarea"><?=$data->re_content?></TEXTAREA></TD>
		</tr>
		<tr>
			<th><span>첨부파일</span></th>
			<TD class="td_con1">
			<?
			if ($data->up_filename) {
				echo "<img src='".$Dir.DataDir."shopimages/personal/".$data->up_filename."' style='max-width:430px;'>";
			} else {
				echo "첨부파일 없음";
			}

			?>
			</TD>
		</tr>
		</TABLE>
        </div>
		</td>
	</tr>
	<tr>
		<td width="100%" align="center">
		<a href="javascript:CheckForm(document.form1);"><img src="images/btn_write1.gif" border="0" vspace="10" border=0></a>
		<a href="javascript:CheckDelete();"><img src="images/btn_dela.gif"  border="0" vspace="10" border=0 hspace="2"></a>
		<a href="javascript:window.close()"><img src="images/btn_closea.gif" border="0" vspace="10" border=0 hspace="0"></a>
		</td>
	</tr>
	</form>
	</table>
	</TD>
</TR>
</TABLE>

<form name=crmview method="post" action="crm_view.php">
<input type=hidden name=id>
</form>

</body>
</html>

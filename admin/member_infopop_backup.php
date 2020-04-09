<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

$id=$_POST["id"];
$mode=$_POST["mode"];

if(ord($_ShopInfo->getId())==0 || ord($id)==0){
	echo "<script>window.close();</script>";
	exit;
}

$recommand_type=$_shopdata->recommand_type;
$member_addform=$_shopdata->member_addform;

$sql = "SELECT * FROM tblmember WHERE id='{$id}' ";
$result=pmysql_query($sql,get_db_conn());
if($row=pmysql_fetch_object($result)) {
	if($row->member_out=="Y") {
		echo "<script>window.close();</script>";
		exit;
	}

	if($mode!="modify") {
		$name=$row->name;
		if($_shopdata->resno_type!="N") {
			$resno1=substr($row->resno,0,6);
			$resno2=substr($row->resno,6,7);
		}
		$email=$row->email;
		$home_tel=$row->home_tel;
		$home_post1=substr($row->home_post,0,3);
		$home_post2=substr($row->home_post,3,3);
		$home_addr=$row->home_addr;
		$home_addr_temp=explode("=",$home_addr);
		$home_addr1=$home_addr_temp[0];
		$home_addr2=$home_addr_temp[1];
		$mobile=$row->mobile;
		$office_post1=substr($row->office_post,0,3);
		$office_post2=substr($row->office_post,3,3);
		$office_addr=$row->office_addr;
		$office_addr_temp=explode("=",$office_addr);
		$office_addr1=$office_addr_temp[0];
		$office_addr2=$office_addr_temp[1];
		$etc=explode("=",$row->etcdata);

		if($row->news_yn=="Y") {
			$news_mail_yn="Y";
			$news_sms_yn="Y";
		} else if($row->news_yn=="M") {
			$news_mail_yn="Y";
			$news_sms_yn="N";
		} else if($row->news_yn=="S") {
			$news_mail_yn="N";
			$news_sms_yn="Y";
		} else if($row->news_yn=="N") {
			$news_mail_yn="N";
			$news_sms_yn="N";
		}
	} else {
		$name=$row->name;
		if($_shopdata->resno_type=="M") {
			$resno1=trim($_POST["resno1"]);
			$resno2=trim($_POST["resno2"]);
		} else if($_shopdata->resno_type=="Y") {
			$resno1=substr($row->resno,0,6);
			$resno2=substr($row->resno,6,7);
		}
		$email=trim($_POST["email"]);
		$news_mail_yn=$_POST["news_mail_yn"];
		$news_sms_yn=$_POST["news_sms_yn"];
		$home_tel=trim($_POST["home_tel"]);
		$home_post1=trim($_POST["home_post1"]);
		$home_post2=trim($_POST["home_post2"]);
		$home_addr1=trim($_POST["home_addr1"]);
		$home_addr2=trim($_POST["home_addr2"]);
		$mobile=trim($_POST["mobile"]);
		$office_post1=trim($_POST["office_post1"]);
		$office_post2=trim($_POST["office_post2"]);
		$office_addr1=trim($_POST["office_addr1"]);
		$office_addr2=trim($_POST["office_addr2"]);
		$rec_id=trim($_POST["rec_id"]);
		$etc=$_POST["etc"];
	}
	$rec_id=$row->rec_id;
	if(ord($rec_id)==0) {
		$str_rec="추천인 없음";
	} else {
		$str_rec=$rec_id;
	}
	if($recommand_type=="Y") {
		$sql = "SELECT rec_cnt FROM tblrecommendmanager ";
		$sql.= "WHERE rec_id='{$id}' ";
		$result2= pmysql_query($sql,get_db_conn());
		if($row2=pmysql_fetch_object($result2)) {
			$str_rec.=" <b><font color=#3A3A3A> {$row2->rec_cnt}명이 당신을 추천하셨습니다.</font></b>";
		}
		pmysql_free_result($result2);
	}
} else {
	echo "<script>window.close();</script>";
	exit;
}
pmysql_free_result($result);

$straddform='';
$scriptform='';
$stretc='';
if(ord($member_addform)) {
	$straddform.="<tr>\n";
	$straddform.="	<TD height=\"30\" colspan=2 align=center><B>추가정보를 입력하세요.</B></td>\n";
	$straddform.="</tr>\n";
	$straddform.="<tr>\n";
	$straddform.="	<TD colspan=\"2\" width=\"100%\" background=\"images/table_con_line.gif\"><img src=\"images/table_con_line.gif\" width=\"4\" height=\"1\" border=\"0\"></TD>\n";
	$straddform.="</tr>\n";

	$fieldarray=explode("=",$member_addform);
	$num=sizeof($fieldarray)/3;
	for($i=0;$i<$num;$i++) {
		if (substr($fieldarray[$i*3],-1,1)=="^") {
			$fieldarray[$i*3]="<img src=\"images/icon_point2.gif\" border=\"0\">".substr($fieldarray[$i*3],0,strlen($fieldarray[$i*3])-1);
			$field_check[$i]="OK";
		}

		$stretc.="<tr>\n";
		$stretc.="	<TD class=\"table_cell\" width=\"140\">".$fieldarray[$i*3]."</td>\n";

		$etcfield[$i]="<input type=text name=\"etc[{$i}]\" value=\"{$etc[$i]}\" size=\"".$fieldarray[$i*3+1]."\" maxlength=\"".$fieldarray[$i*3+2]."\" id=\"etc_{$i}\" class=\"input\">";

		$stretc.="	<TD class=\"td_con1\" width=\"360\">{$etcfield[$i]}</TD>\n";
		$stretc.="</tr>\n";
		$stretc.="<tr>\n";
		$stretc.="	<TD colspan=\"2\" width=\"100%\" background=\"images/table_con_line.gif\"><img src=\"images/table_con_line.gif\" width=\"4\" height=\"1\" border=\"0\"></TD>\n";
		$stretc.="</tr>\n";
		
		if ($field_check[$i]=="OK") {
			$scriptform.="try {\n";
			$scriptform.="	if (document.getElementById('etc_{$i}').value==0) {\n";
			$scriptform.="		alert('필수입력사항을 입력하세요.');\n";
			$scriptform.="		document.getElementById('etc_{$i}').focus();\n";
			$scriptform.="		return;\n";
			$scriptform.="	}\n";
			$scriptform.="} catch (e) {}\n";
		}
	}
	$straddform.=$stretc;
}

if($mode=="modify") {
	$onload="";
	for($i=0;$i<10;$i++) {
		if(strpos($etc[$i],"=")) {
			$onload="<script>alert('추가정보에 입력할 수 없는 문자가 포함되었습니다.');</script>";
			break;
		}
		if($i!=0) {
			$etcdata=$etcdata."=";
		}
		$etcdata=$etcdata.$etc[$i];
	}

	if(ord($onload)) {

	} elseif(ord(trim($email))==0) {
		$onload="<script>alert(\"이메일을 입력하세요.\");</script>";
	} elseif(!ismail($email)) {
		$onload="<script>alert(\"이메일 입력이 잘못되었습니다.\");</script>";
	} elseif(ord(trim($home_tel))==0) {
		$onload="<script>alert(\"집전화를 입력하세요.\");</script>";
	} else {
		if(!$onload) {
			$home_post=$home_post1.$home_post2;
			$office_post=$office_post1.$office_post2;
			if($news_mail_yn=="Y" && $news_sms_yn=="Y") {
				$news_yn="Y";
			} elseif($news_mail_yn=="Y") {
				$news_yn="M";
			} elseif($news_sms_yn=="Y") {
				$news_yn="S";
			} else {
				$news_yn="N";
			}

			$home_addr=$home_addr1."=".$home_addr2;
			$office_addr="";
			if(strlen($office_post)==6) $office_addr=$office_addr1."=".$office_addr2;

			$sql = "UPDATE tblmember SET ";
			$sql.= "email		= '{$email}', ";
			$sql.= "news_yn		= '{$news_yn}', ";
			$sql.= "home_post	= '{$home_post}', ";
			$sql.= "home_addr	= '{$home_addr}', ";
			$sql.= "home_tel	= '{$home_tel}', ";
			$sql.= "mobile		= '{$mobile}', ";
			$sql.= "office_post	= '{$office_post}', ";
			$sql.= "office_addr	= '{$office_addr}', ";
			$sql.= "office_tel	= '{$office_tel}', ";
			$sql.= "etcdata		= '{$etcdata}' ";
			$sql.= "WHERE id='{$id}' ";
			$update=pmysql_query($sql,get_db_conn());
			alert_go("{$id} 회원님의 개인정보 수정이 완료되었습니다.\\n\\n감사합니다.",'c');
		}
	}
}

?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=utf-8'>
<title>회원정보</title>
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
	var oWidth = 650;
	var oHeight = 530;

	window.resizeTo(oWidth,oHeight);
}

function CheckForm() {
	form=document.form1;
	if(form.email.value.length==0) {
		alert("이메일을 입력하세요."); form.email.focus(); return;
	}
	if(!IsMailCheck(form.email.value)) {
		alert("이메일 형식이 맞지않습니다.\n\n확인하신 후 다시 입력하세요."); form.email.focus(); return;
	}
	if(form.home_tel.value.length==0) {
		alert("집전화번호를 입력하세요."); form.home_tel.focus(); return;
	}
	if(form.home_post1.value.length==0 || form.home_addr1.value.length==0) {
		alert("집주소를 입력하세요.");
		return;
	}
	if(form.home_addr2.value.length==0) {
		alert("집주소의 상세주소를 입력하세요."); form.home_addr2.focus(); return;
	}

<?=$scriptform?>

	if(confirm("<?=$id?> 회원님의 개인정보를 수정하시겠습니까?")) {
		form.mode.value="modify";
		form.submit();
	}
}

function f_addr_search(form,post,addr,gbn) {
	window.open("<?=$Dir.FrontDir?>addr_search.php?form="+form+"&post="+post+"&addr="+addr+"&gbn="+gbn,"f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");		
}

//-->
</SCRIPT>
</head>
<link rel="styleSheet" href="/css/admin.css" type="text/css">
<div class="pop_top_title"><p>회원정보</p></div>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 style="overflow-x:hidden;" onLoad="PageResize();">
<form name=form1 method=post action="<?=$_SERVER['PHP_SELF']?>">
<input type=hidden name=mode>
<input type=hidden name=id value="<?=$id?>">
<tr>
	<TD style="padding:10pt;">
	<div class="table_style01">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TR>
		<th><span>이름</span></th>
		<TD class="td_con1"><b><span class="font_orange"><?=$name?></span></b></TD>
	</TR>
	<TR>
		<th><span>아이디</span></th>
		<TD class="td_con1"><b><?=$id?></b></TD>
	</TR>
	<?php if($_shopdata->resno_type!="N"){?>
	<TR>
		<th><span>주민등록번호</span></th>
		<TD class="td_con1"><?=$resno1?> - <?=str_repeat("*",strlen($resno2))?></TD>
	</TR>
	<?php }?>
	<TR>
		<th><span>이메일</span></th>
		<TD class="td_con1"><input type=text name=email value="<?=$email?>" maxlength=100 style="width:100%" class="input"></TD>
	</TR>
	<TR>
		<th><span>메일정보 수신여부</span></th>
		<TD class="td_con1"><input type=radio id="idx_news_mail_yn0" name=news_mail_yn value="Y" <?php if($news_mail_yn=="Y")echo"checked";?>><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_news_mail_yn0>수신함</label> <input type=radio id="idx_news_mail_yn1" name=news_mail_yn value="N" <?php if($news_mail_yn=="N")echo"checked";?>><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_news_mail_yn1>수신안함</label></TD>
	</TR>
	<TR>
		<th><span>SMS정보 수신여부</span></th>
		<TD class="td_con1"><input type=radio id="idx_news_sms_yn0" name=news_sms_yn value="Y" <?php if($news_sms_yn=="Y")echo"checked";?>><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_news_sms_yn0>수신함</label> <input type=radio id="idx_news_sms_yn1" name=news_sms_yn value="N" <?php if($news_sms_yn=="N")echo"checked";?>><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_news_sms_yn1>수신안함</label></TD>
	</TR>
	<tr>
		<th><span>집전화</span></th>
		<TD class="td_con1"><input type=text name=home_tel value="<?=$home_tel?>" maxlength=15 style="width:120" class="input"></TD>
	</tr>
	<tr>
		<th><span>집주소</span></th>
		<TD class="td_con1">
        <div class="table_none">
		<table cellpadding="1" cellspacing="0" width="100%">
		<col width=100></col>
		<col width=></col>
		<tr>
			<td><input type=text name=home_post1 value="<?=$home_post1?>" style="width:30" readonly class="input"> - <input type=text name=home_post2 value="<?=$home_post2?>" style="width:30" readonly class="input"></td>
			<td><A class=board_list hideFocus style="selector-dummy: true" onfocus=this.blur(); href="javascript:f_addr_search('form1','home_post','home_addr1',2);"><IMG src="images/icon_addra.gif" border=0 ></A></td>
		</tr>
		<tr>
			<td colspan="2"><input type=text name=home_addr1 value="<?=$home_addr1?>" maxlength=100 readonly class="input" style="width:100%"></td>
		</tr>
		<tr>
			<td colspan="2"><input type=text name=home_addr2 value="<?=$home_addr2?>" maxlength=100 class="input" style="width:100%"></td>
		</tr>
		</table>
        </div>
		</TD>
	</tr>
	<tr>
		<th><span>비상전화(휴대폰)</span></th>
		<TD class="td_con1"><input type=text name=mobile value="<?=$mobile?>" maxlength=15 style="width:120" class="input"></TD>
	</tr>
	<tr>
		<th><span>회사주소</span></th>
		<TD class="td_con1">
        <div class="table_none">
		<table cellpadding="1" cellspacing="0" width="100%">
		<col width=100></col>
		<col width=></col>
		<tr>
			<td><input type=text name=office_post1 value="<?=$office_post1?>" style="width:30" readonly class="input"> - <input type=text name=office_post2 value="<?=$office_post2?>" style="width:30" readonly class="input"></td>
			<td><A class=board_list hideFocus style="selector-dummy: true" onfocus=this.blur(); href="javascript:f_addr_search('form1','office_post','office_addr1',2);"><IMG src="images/icon_addra.gif" border=0 ></A></td>
		</tr>
		<tr>
			<td colspan="2"><input type=text name=office_addr1 value="<?=$office_addr1?>" maxlength=100 readonly class="input" style="width:100%"></td>
		</tr>
		<tr>
			<td colspan="2"><input type=text name=office_addr2 value="<?=$office_addr2?>" maxlength=100 class="input" style="width:100%"></td>
		</tr>
		</table>
        </div>
		</TD>
	</tr>
	<?php if($recommand_type=="Y") {?>
	<tr>
		<th><span>추천회원ID</span></th>
		<TD class="td_con1" width="360"><?=$str_rec?></TD>
	</tr>
	<?php }?>
	<?php
	if(ord($straddform)) {
		echo $straddform;
	}
	?>
	</TABLE>
    </div>
	</TD>
</tr>
<TR>
	<TD align="center"><a href="javascript:CheckForm();"><center><img src="images/btn_ok1.gif" border="0" vspace="0" border=0></a>&nbsp;&nbsp;<a href="javascript:window.close();"><img src="images/btn_close.gif" border="0" vspace="0" border=0 hspace="2"></a></center></TD>
</TR>
</form>
</TABLE>
<?=$onload?>
</body>
</html>

<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "me-2";
$MenuCode = "member";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################

$max=10;

$type=$_POST["type"];
$mode=$_POST["mode"];
$group_code=$_POST["group_code"];

$group_name=$_POST["group_name"];
$group_description=$_POST["group_description"];
$group_payment=$_POST["group_payment"];

$group_type=$_POST["group_type"];

$group_usemoney_R=$_POST["group_usemoney_R"];
$group_addmoney_R=$_POST["group_addmoney_R"];
$group_salerate_R=$_POST["group_salerate_R"];

$group_usemoney_S=$_POST["group_usemoney_S"];
$group_addmoney_S=$_POST["group_addmoney_S"];
$group_salerate_S=$_POST["group_salerate_S"];

$groupimg=$_FILES["groupimg"];

$reg_group=$_POST["reg_group"];

$imagepath=$Dir.DataDir."shopimages/etc/";

if ($type=="insert" || $type=="modify") {
	if ($group_type=="R" && strlen($group_addmoney_R)!=0) {
		$group_view="W";
		$group_addmoney=$group_addmoney_R;
		$group_usemoney=$group_usemoney_R;
	} else if ($group_type=="R" && strlen($group_salerate_R)!=0) {
		$group_view="P";
		$group_addmoney=$group_salerate_R;
		$group_usemoney=$group_usemoney_R;
	} else if ($group_type=="S" && strlen($group_addmoney_S)!=0) {
		$group_view="W";
		$group_addmoney=$group_addmoney_S;
		$group_usemoney=$group_usemoney_S;
	} else if ($group_type=="S" && strlen($group_salerate_S)!=0) {
		$group_view="P";
		$group_addmoney=$group_salerate_S;
		$group_usemoney=$group_usemoney_S;
	} else if($group_type=="M") {
		$group_view="X";
		$group_addmoney=0;
		$group_usemoney=0;
	}
	if(ord($group_usemoney)==0) $group_usemoney=0;
}

if(ord($reg_group)==0 && $type!="reg_group"){
	$reg_group=$_shopdata->group_code;
}

if ($type=="insert") {
	$sql = "SELECT CAST(MAX(SUBSTR(group_code,3,2)) AS int)+1 as cnt, COUNT(*) as count FROM tblmembergroup ";
	$result = pmysql_query($sql,get_db_conn());
	if($row=pmysql_fetch_object($result)){
		if($row->count>=$max){
			pmysql_free_result($result);
			alert_go("등급은 최대 {$max}개 까지만 등록가능합니다.",-1);
		}else {
			$cnt=sprintf('%02d',$row->cnt);
			$count=$row->count;
		}
		pmysql_free_result($result);
	}
	if(ord($cnt)==0 || $count==0) $cnt="01";

	$group_code=$group_type.$group_view.$cnt;

	if (ord($groupimg["name"])) {
		 $ext = strtolower(pathinfo($groupimg['name'],PATHINFO_EXTENSION));
		if ($ext!="gif") {
			alert_go('등급이미지는 gif파일만 등록이 가능합니다.',-1);
		} else if ($groupimg["size"]==0 || $groupimg["size"] > 153600) {
			alert_go("정상적인 파일이 아니거나 파일 용량이 너무 큽니다.\\n\\n다시 확인 후 등록하시기 바랍니다.",-1);
		}
		$uploaded_img="groupimg_{$group_code}.gif";
		move_uploaded_file ($groupimg["tmp_name"], $imagepath.$uploaded_img);
		chmod($imagepath.$uploaded_img,0666);
	}

	$sql = "INSERT INTO tblmembergroup(
	group_code	 ,
	group_name	 ,
	group_description,
	group_payment	 ,
	group_usemoney	 ,
	group_addmoney) VALUES (
	'{$group_code}', 
	'{$group_name}', 
	'{$group_description}', 
	'{$group_payment}', 
	'{$group_usemoney}', 
	'{$group_addmoney}')";
	pmysql_query($sql,get_db_conn());
	$onload="<script>window.onload=function(){ alert('회원등급 등록이 완료되었습니다.');}</script>";

	$log_content = "## 회원등급생성 - $group_code $group_payment $group_name $group_usemoney $group_addmoney";
	ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);

} else if ($type=="modify" && $mode=="result" && strlen($group_code)==4) {
	$group_code2=$group_type.$group_view.substr($group_code,2,2);
	if (ord($groupimg["name"])) {
		if (strtolower(pathinfo($groupimg['name'],PATHINFO_EXTENSION))!="gif") {
			alert_go('등급이미지는 gif파일만 등록이 가능합니다.',-1);
		} else if ($groupimg["size"]==0 || $groupimg["size"] > 153600) {
			alert_go("정상적인 파일이 아니거나 파일 용량이 너무 큽니다.\\n\\n다시 확인 후 등록하시기 바랍니다.",-1);
		}
		if (file_exists($imagepath."groupimg_{$group_code}.gif")) {
			unlink ($imagepath."groupimg_{$group_code}.gif");
		}
		$uploaded_img="groupimg_{$group_code2}.gif";
		move_uploaded_file ($groupimg["tmp_name"], $imagepath.$uploaded_img);
		chmod($imagepath.$uploaded_img,0666);
	}
	$sql = "UPDATE tblmembergroup SET ";
	$sql.= "group_code		= '{$group_code2}', ";
	$sql.= "group_name		= '{$group_name}', ";
	$sql.= "group_description='{$group_description}', ";
	$sql.= "group_payment	= '{$group_payment}', ";
	$sql.= "group_usemoney	= '{$group_usemoney}', ";
	$sql.= "group_addmoney	= '{$group_addmoney}' ";
	$sql.= "WHERE group_code = '{$group_code}' ";
	pmysql_query($sql,get_db_conn());

	$log_content = "## 회원등급변경 - $group_code $group_payment $group_name $group_usemoney $group_addmoney";
	ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);

	if ($group_code!=$group_code2) {
		$sql = "UPDATE tblmember SET group_code = '{$group_code2}' ";
		$sql.= "WHERE group_code = '{$group_code}' ";
		pmysql_query($sql,get_db_conn());

		$sql = "UPDATE tblproductcode SET group_code = '{$group_code2}' ";
		$sql.= "WHERE group_code = '{$group_code}' ";
		pmysql_query($sql,get_db_conn());

		$sql = "UPDATE tblboardadmin SET group_code = '{$group_code2}' ";
		$sql.= "WHERE group_code = '{$group_code}' ";
		pmysql_query($sql,get_db_conn());

		$sql = "UPDATE tblproductgroupcode SET group_code = '{$group_code2}' ";
		$sql.= "WHERE group_code = '{$group_code}' ";
		pmysql_query($sql,get_db_conn());
	}

	$onload="<script>window.onload=function(){ alert('회원등급 수정이 완료되었습니다.');}</script>";
	$type='';
	$mode='';
	$group_code='';
} else if ($type=="delete" && strlen($group_code)==4) {
	$sql = "DELETE FROM tblmembergroup WHERE group_code = '{$group_code}' ";
	pmysql_query($sql,get_db_conn());
	$sql = "DELETE FROM tblproductgroupcode WHERE group_code = '{$group_code}' ";
	pmysql_query($sql,get_db_conn());
	$sql = "UPDATE tblmember SET group_code='' WHERE group_code = '{$group_code}' ";
	pmysql_query($sql,get_db_conn());
	if($reg_group==$group_code){
		$sql = "UPDATE tblshopinfo SET group_code=NULL ";
		pmysql_query($sql,get_db_conn());
		DeleteCache("tblshopinfo.cache");
	}
	if (file_exists($imagepath."groupimg_{$group_code}.gif")) {
		unlink ($imagepath."groupimg_{$group_code}.gif");
	}

	$sql = "SELECT productcode FROM tblproductgroupcode GROUP BY productcode ";
	$result=pmysql_query($sql,get_db_conn());
	while($row=pmysql_fetch_object($result)) {
		$group_check_code[]=$row->productcode;
	}
	pmysql_free_result($result);

	if(count($group_check_code)>0) {
		$sql = "UPDATE tblproduct SET group_check='N' ";
		$sql.= "WHERE group_check='Y' ";
		$sql.= "AND productcode NOT IN ('".implode("','", $group_check_code)."') ";
		pmysql_query($sql,get_db_conn());
	}

	$onload="<script>window.onload=function(){ alert('해당 등급 삭제가 완료되었습니다.');}</script>";
	$type='';
	$group_code='';
} else if ($type=="imgdel" && strlen($group_code)==4) {
	unlink ($imagepath."groupimg_{$group_code}.gif");
	$onload="<script>window.onload=function(){ alert('해당등급 이미지 삭제가 완료되었습니다.');}</script>";
	$type='';
	$group_code='';
} else if ($type=="reg_group") {
	$sql = "UPDATE tblshopinfo SET ";
	if(ord($reg_group)==0) $sql.= "group_code = NULL ";
	else $sql.= "group_code = '{$reg_group}' ";
	pmysql_query($sql,get_db_conn());
	$onload="<script>window.onload=function(){ alert('신규 회원 가입시의 회원등급 등록이 완료되었습니다.');}</script>";
	$type='';
	DeleteCache("tblshopinfo.cache");
}

if(ord($type)==0) $type="insert";

?>

<?php include("header.php"); ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(type) {
	if (document.form1.group_name.value.length==0) {
		alert("등급명을 입력하세요");
		document.form1.group_name.focus();
		return;
	}
	if (document.form1.group_type[0].checked==false && document.form1.group_type[1].checked==false && document.form1.group_type[2].checked==false) {
		alert("등급속성을 선택하세요");
		document.form1.group_type[0].focus();
		return;
	}
	if (document.form1.group_type[0].checked) {
		if (document.form1.group_addmoney_R.value.length==0 && document.form1.group_salerate_R.value.length==0) {
			alert("추가적립 속성의 추가적립금을 입력하세요.");
			document.form1.group_addmoney_R.focus();
			return;
		}
		if (document.form1.group_addmoney_R.value.length!=0 && document.form1.group_salerate_R.value.length!=0) {
			alert("추가적립 방법은 둘중 하나만 입력하세요.");
			document.form1.group_addmoney_R.focus();
			return;
		}
		if(isNaN(document.form1.group_usemoney_R.value)){
			alert("숫자만 입력하시기 바랍니다.");
			document.form1.group_usemoney_R.focus();
			return;
		}
		if ((document.form1.group_addmoney_R.value.length!=0 && (isNaN(document.form1.group_addmoney_R.value)))
		   || (document.form1.group_salerate_R.value.length!=0 && (isNaN(document.form1.group_salerate_R.value)))) {
			alert("숫자만 입력하시기 바랍니다.");
			document.form1.group_addmoney_R.focus();
			return;
		}
	}
	if (document.form1.group_type[1].checked) {
		if (document.form1.group_addmoney_S.value.length==0 && document.form1.group_salerate_S.value.length==0) {
			alert("추가할인 속성의 추가할인 금액을 입력하세요.");
			document.form1.group_addmoney_S.focus();
			return;
		}
		if (document.form1.group_addmoney_S.value.length!=0 && document.form1.group_salerate_S.value.length!=0) {
			alert("추가할인 방법은 둘중 하나만 선택하세요.");
			document.form1.group_addmoney_S.focus();
			return;
		}
		if (isNaN(document.form1.group_usemoney_S.value)) {
			alert("숫자만 입력하시기 바랍니다.");
			document.form1.group_usemoney_S.focus();
			return;
		}
		if ((document.form1.group_addmoney_S.value.length!=0 && (isNaN(document.form1.group_addmoney_S.value)))
		   || (document.form1.group_salerate_S.value.length!=0 && (isNaN(document.form1.group_salerate_S.value)))) {
			alert("숫자만 입력하시기 바랍니다.");
			document.form1.group_addmoney_S.focus();
			return;
		}
	}
	if(type=="modify") {
		document.form1.mode.value="result";
	}
	document.form1.type.value=type;
	document.form1.submit();
}

function ChangeGroupType(val){
	arr_type = new Array("R","S","M");
	for(i=0;i<document.form1.group_type.length;i++){
		if (document.form1.group_type[i].value==val) {
			document.form1.group_type[i].checked=true;
			if (document.form1.group_type[i].value!="M") {
				document.form1["group_usemoney_"+arr_type[i]].disabled=false;
				document.form1["group_usemoney_"+arr_type[i]].style.background='#FFFFFF';
				document.form1["group_addmoney_"+arr_type[i]].disabled=false;
				document.form1["group_addmoney_"+arr_type[i]].style.background='#FFFFFF';
				document.form1["group_salerate_"+arr_type[i]].disabled=false;
				document.form1["group_salerate_"+arr_type[i]].style.background='#FFFFFF';
			}
		} else {
			document.form1.group_type[i].checked=false;
			if (document.form1.group_type[i].value!="M") {
				document.form1["group_usemoney_"+arr_type[i]].disabled=true;
				document.form1["group_usemoney_"+arr_type[i]].style.background='#EFEFEF';
				document.form1["group_addmoney_"+arr_type[i]].disabled=true;
				document.form1["group_addmoney_"+arr_type[i]].style.background='#EFEFEF';
				document.form1["group_salerate_"+arr_type[i]].disabled=true;
				document.form1["group_salerate_"+arr_type[i]].style.background='#EFEFEF';
			}
		}
	}
}

function GroupSend(type,code) {
	if (type=="delete") {
		if (!confirm("해당 등급을 삭제하시겠습니까?")) {
			return;
		}
	}
	if (type=="imgdel") {
		if (!confirm("해당 등급 이미지를 삭제하시겠습니까?")) {
			return;
		}
	}
	document.form2.type.value=type;
	document.form2.group_code.value=code;
	document.form2.submit();
}
</script>
<div class="admin_linemap"><div class="line"><p>현재위치 : 회원관리 &gt; 회원등급설정 &gt;<span>회원등급 등록/수정/삭제</span></p></div></div>
<table cellpadding="0" cellspacing="0" width="98%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
		<col width=240 id="menu_width"></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top">
			<?php include("menu_member.php"); ?>
			</td>
			<td></td>
			<td valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
					<!-- 페이지 타이틀 -->
					<div class="title_depth3">회원등급 등록/수정/삭제</div>
					<!-- 소제목 -->
					<div class="title_depth3_sub"><span>회원등급 신규등록/수정/삭제를 하실 수 있으며 등급별 권한설정이 가능합니다.</span></div>
                </td>
            </tr>
            <tr>
            	<td>
					<!-- 소제목 -->
					<div class="title_depth3_sub">회원가입시 등급 지정<span>신규회원가입시 선택된 등급으로 자동 가입됩니다.</span></div>
				</td>
			</tr>
			<form name=form3 action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<input type=hidden name=type value="reg_group">
			<tr>
				<td>
				<div class="table_style01">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<th><span>신규회원 가입시</span></th>
					<TD><select name=reg_group style="width:350px" class="select">
						<option value="">선택등급 없음
<?php
						$sql = "SELECT group_code,group_name FROM tblmembergroup ";
						$result = pmysql_query($sql,get_db_conn());
						while($row = pmysql_fetch_object($result)){
							echo "<option value=\"{$row->group_code}\"";
							if($reg_group==$row->group_code) echo " selected";
							echo ">{$row->group_name}</option>\n";
						}
?>
						</select> 에 자동으로 가입됩니다. 
					</TD>
				</TR>
				</TABLE>
				</div>
				</td>
			</tr>
            <tr><td height="10"></td></tr>
			<tr>
				<td align=center><a href="javascript:document.form3.submit()"><img src="images/botteon_save.gif" border="0" vspace="5"></a></td>
			</tr>
			</form>
			<tr>
				<td>
					<!-- 소제목 -->
					<div class="title_depth3_sub">등록된 회원등급 목록</div>
				</td>
			</tr>
			<tr>
				<td>
				<div class="table_style02">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<colgroup>
					<col width="30" />
                    <col width="100" />
                    <col width="" />
                    <col width="100" />
                    <col width="100" />
                    <col width="100" />
                    <col width="60" />
                    <col width="60" />
				</colgroup>
				<TR>
					<th>No</th>
					<th>등급명</th>
					<th>등급설명</th>
					<th>추가적립</th>
					<th>추가할인</th>
					<th>회원수</th>
					<th>수정</th>
					<th>삭제</th>
				</TR>
<?php
				$sql = "SELECT COUNT(*) as cnt, group_code FROM tblmember ";
				$sql.= "WHERE group_code != '' GROUP BY group_code ";
				$result=pmysql_query($sql,get_db_conn());
				while($row=pmysql_fetch_object($result)) {
					$cnt[$row->group_code]=$row->cnt;
				}
				pmysql_free_result($result);

				$sql = "SELECT * FROM tblmembergroup ";
				$result = pmysql_query($sql,get_db_conn());
				$i=0;
				while($row=pmysql_fetch_object($result)) {
					$i++;
					$group_type=$row->group_code[0];
					$group_view=$row->group_code[1];
					echo "<tr>\n";
					echo "	<TD>{$i}</td>\n";
					echo "	<TD><span class=\"font_orange\"><b>{$row->group_name}</b></span></TD>\n";
					echo "	<TD><NOBR>&nbsp;{$row->group_description}</NOBR></TD>\n";
					if($group_type=="R") {
						echo "	<TD><B><span class=\"font_orange\">".number_format($row->group_addmoney).($group_view=="P"?"배":"원")."</span></B></TD>\n";
					} else {
						echo "	<TD><B><span class=\"font_orange\">X</span></B></TD>\n";
					}
					if($group_type=="S") {
						echo "	<TD><span class=\"font_orange\"><b>".number_format($row->group_addmoney).($group_view=="P"?"%":"원")."</b></span></TD>\n";
					} else {
						echo "	<TD><span class=\"font_orange\"><b>X</b></span></TD>\n";
					}
					echo "	<TD>".number_format($cnt[$row->group_code])."명</td>\n";
					echo "	<TD><a href=\"javascript:GroupSend('modify','{$row->group_code}');\"><img src=\"images/btn_edit.gif\" border=\"0\"></a></td>\n";
					echo "	<TD><a href=\"javascript:GroupSend('delete','{$row->group_code}');\"><img src=\"images/btn_del.gif\" border=\"0\"></a></td>\n";
					echo "</tr>\n";
					
					$group_type='';
					$group_view='';
				}
				pmysql_free_result($result);
				if ($i==0) {
					echo "<tr><td colspan=\"8\" align=\"center\">등록된 회원등급이 없습니다.</td></tr>";
				}
?>
				</TABLE>
				</div>
				</td>
			</tr>
			<tr>
				<td>
					<!-- 소제목 -->
					<div class="title_depth3_sub">회원등급 등록/수정</div>
				</td>
			</tr>
<?php
			if($type=="modify" && strlen($group_code)==4) {
				$sql = "SELECT * FROM tblmembergroup WHERE group_code = '{$group_code}' ";
				$result = pmysql_query($sql,get_db_conn());
				if($row=pmysql_fetch_object($result)) {
					$group_name=$row->group_name;
					$group_description=$row->group_description;
					$group_payment=$row->group_payment;
					$group_type=$row->group_code[0];
					$group_view=$row->group_code[1];
					$group_usemoney=$row->group_usemoney;
					$group_addmoney=$row->group_addmoney;
				}
				pmysql_free_result($result);
			} else {
				$group_name='';
				$group_description='';
				$group_payment = "N";
				$group_type='';
				$group_view='';
				$group_usemoney='';
				$group_addmoney='';
			}
?>
			<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<input type=hidden name=mode>
			<input type=hidden name=group_code value="<?=$group_code?>">
			<tr>
				<td>
				<div class="table_style01">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=139>
				<col width=>
				<TR>
					<th><span>등급명</span></th>
					<TD><input type=text name=group_name value="<?=$group_name?>" maxlength=30 style="width:200px;" class="input"></TD>
				</TR>
				<TR>
					<th><span>등급설명</span></th>
					<TD><input type=text name=group_description value="<?=$group_description?>" maxlength=100 style="width:450" class="input">120자 이내</TD>
				</TR>
				<TR>
					<th><span>결제조건</span></th>
					<TD>
					<input type=radio id="idx_group_payment1" name=group_payment value="N" <?php if($group_payment=="N")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_group_payment1>현금/카드</label> &nbsp;&nbsp;&nbsp;
					<input type=radio id="idx_group_payment2" name=group_payment value="B" <?php if($group_payment=="B")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_group_payment2>현금결제만</label> &nbsp;&nbsp;&nbsp;
					<input type=radio id="idx_group_payment3" name=group_payment value="C" <?php if($group_payment=="C")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_group_payment3>카드결제만</label></TD>
				</TR>
				<TR>
					<th><span>등급속성</span></th>
					<TD>
					<div class="table_none">
					<table cellpadding="0" cellspacing="0" width="99%">
					<col width=87></col>
					<col width=></col>
					<tr>
						<td><input type=checkbox id="idx_group_type1" name=group_type value="R" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?php if($group_type=="R") echo "checked"?> onclick="ChangeGroupType(this.value)"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_group_type1><span class="font_orange"><B>추가적립</B></span></label></td>
						<td>회원이 <input type=text name=group_usemoney_R size=8 maxlength=8 value="<?php if($group_type=="R") echo $group_usemoney?>" style="text-align:right" class="input">원 이상 구매시,  <input type=text name=group_addmoney_R size=8 maxlength=8 value="<?php if($group_type=="R" && $group_view=="W") echo $group_addmoney?>" style="text-align:right" class="input"><B><span class="font_orange">원</span></B>또는 <input type=text name=group_salerate_R size=8 maxlength=8 value="<?php if($group_type=="R" && $group_view=="P") echo $group_addmoney?>" style="text-align:right" class="input"><B><span class="font_orange">배</span></B>를 추가 적립합니다.</td>
					</tr>
					<tr>
						<td><input type=checkbox id="idx_group_type2" name=group_type value="S" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?php if($group_type=="S") echo "checked"?> onclick="ChangeGroupType(this.value)"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_group_type2><span class="font_orange"><B>추가할인</B></span></label></td>
						<td>회원이 <input type=text name=group_usemoney_S size=8 maxlength=8 value="<?php if($group_type=="S") echo $group_usemoney?>" style="text-align:right" class="input">원 이상 구매시,  <input type=text name=group_addmoney_S size=8 maxlength=8 value="<?php if($group_type=="S" && $group_view=="W") echo $group_addmoney?>" style="text-align:right" class="input"><B><span class="font_orange">원</span></B>또는 <input type=text name=group_salerate_S size=8 maxlength=8 value="<?php if($group_type=="S" && $group_view=="P") echo $group_addmoney?>" style="text-align:right" class="input"><B><span class="font_orange">%</span></B>를 추가 할인합니다.</td>
					</tr>
					</table>
					</div>
					</TD>
				</TR>
				<TR>
					<th><span>메일링</span></th>
					<TD><input type=checkbox id="idx_group_type3" name=group_type value="M" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?php if($group_type=="M") echo "checked"?> onclick="ChangeGroupType(this.value)"> <label style='cursor:hand;TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_group_type3>등급속성없이, 해당등급에 <B><span class="font_orange">단순 메일링(SMS)</span></B>만 합니다.</LABEL></TD>
				</TR>
				<TR>
					<th><span>등급이미지</span></th>
					<TD class="td_con1">
					<input type=file name=groupimg style="width:100%;"><br />		
					* 권장크기 : 80*40 픽셀 [가로*세로])<br><span class="font_orange">* 150KB 이하의 GIF(gif)이미지만 가능합니다.</span>
					<?php if(file_exists($imagepath."groupimg_{$group_code}.gif")){?>
					<BR><BR><img src="<?=$imagepath?>groupimg_<?=$group_code?>.gif" align=absmiddle>&nbsp;&nbsp; | &nbsp;&nbsp;<A HREF="javascript:GroupSend('imgdel','<?=$group_code?>');"><img src="images/icon_del1.gif" border=0 align=absmiddle></A>
					<?php }?></TD>
				</TR>
				</TABLE>
				</div>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<?php if($type=="insert"){?>
			<tr>
				<td align=center><a href="javascript:CheckForm('<?=$type?>');"><img src="images/botteon_make.gif" border="0" vspace="3"></a></td>
			</tr>
			<?php }else if($type=="modify"){?>
			<tr>
				<td align=center><a href="javascript:CheckForm('<?=$type?>');"><img src="images/btn_badd2.gif" border="0" vspace="3"></a></td>
			</tr>
			<?php }?>
			</form>
			<form name=form2 action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=group_code>
			</form>
			<tr>
				<td height="20">&nbsp;</td>
			</tr>
			<tr>
				<td>
				<div class="sub_manual_wrap">
					<div class="title"><p>매뉴얼</p></div>
						<dl>
							<dt><span>회원등급 기본정보 관리</span></dt>
							<dd>
							- 회원등급 설정은 가격정책 또는 할인율 진행시 용이하게 운영할 수 있습니다.<br>
							- 회원등급에 따라 결제방법을(현금/카드, 현금결제만, 카드결제만) 선택할 수 있습니다. 가급적 현금/카드 결제를 권장합니다.<br>
							- 배수로 추가적립 설정시 기본적립금에 배수가 적용됩니다. 예) 3배 추가적립 -  200원의 경우 600원 적립<br>
							- 추가 적립금은 배송완료 후 자동 적립됩니다.<br>
							- %로 추가할인 설정시 10원 단위는 자동 절삭됩니다. 예) 4,360원의 경우 4,300원을 할인합니다.
							</dd>
								
						</dl>
					</div>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?php if($type=="modify") echo "<script>ChangeGroupType('{$group_type}')</script>";?>
<?=$onload?>
<?php 
include("copyright.php");

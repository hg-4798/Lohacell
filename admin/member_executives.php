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

?>

<?php include("header.php"); ?>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">

function CheckForm() {
	form=document.form1;
	
	if(!form.mem_id.value){
		alert("ID를 입력하여주십시요.");
		form.mem_id.focus();
		return false;
	}else if(!form.chn_mem_id.value){
		alert("임직원 ID를 입력하여주십시요.");
		form.chn_mem_id.focus();
		return false;
	}
	form.submit();
}


</script>

<!-- 라인맵 -->
<div class="admin_linemap"><div class="line"><p>현재위치 : 회원관리 &gt; 회원등급설정 &gt;<span>임직원 수정</span></p></div></div>

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
			<table width="100%" cellpadding="0" cellspacing="0">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
					<!-- 페이지 타이틀 -->
					<div class="title_depth3">임직원 수정</div>
				</td>
			</tr>
			
			<tr><td height="3"></td></tr>
			<form name=form1 action="member_executives_indb.php" method=post>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
					<div class="table_style01">
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
                        <th><span>기존 ID</span></th>
						<td class="td_con1"><input type=text name="mem_id" value="" size="60" maxlength="50" onKeyDown="chkFieldMaxLen(50)" class="input"></td>
					</tr>
					<tr>
                        <th><span>임직원 ID</span></th>
						<td class="td_con1"><input type=text name="chn_mem_id" value="" size="60" maxlength="50" onKeyDown="chkFieldMaxLen(50)" class="input"></td>
					</tr>
					
				</table>
                </div>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" border="0"></a></td>
			</tr>
			<tr><td height="20"></td></tr>
			</form>
			
			<tr>
				<td>

					

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
<?=$onload?>
<?php 
include("copyright.php");

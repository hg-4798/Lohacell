<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$body=$_POST["body"];

if($type=="update" && ord($body)) {
	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='surveyview' ";
	$result=pmysql_query($sql,get_db_conn());
	$row=pmysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT INTO tbldesignnewpage(type,subject,body) VALUES (
		'surveyview', 
		'투표결과 화면 디자인', 
		'{$body}')";
		pmysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET 
		body		= '{$body}' 
		WHERE type='surveyview' ";
		pmysql_query($sql,get_db_conn());
	}
	pmysql_free_result($result);
	$onload="<script>window.onload=function(){ alert(\"투표결과 화면 디자인 수정이 완료되었습니다.\"); }</script>";
} elseif($type=="delete") {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='surveyview' ";
	pmysql_query($sql,get_db_conn());
	$onload="<script>window.onload=function(){ alert(\"투표결과 화면 디자인 삭제가 완료되었습니다.\"); }</script>";
} elseif($type=="clear") {
	$body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='surveyview' ";
	$result=pmysql_query($sql,get_db_conn());
	if($row=pmysql_fetch_object($result)) {
		$body=$row->body;
	}
	pmysql_free_result($result);
}

if($type!="clear") {
	$body="";
	$sql = "SELECT body FROM tbldesignnewpage WHERE type='surveyview' ";
	$result = pmysql_query($sql,get_db_conn());
	if($row=pmysql_fetch_object($result)) {
		$body=$row->body;
	}
	pmysql_free_result($result);
}
include("header.php"); 
?>
<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(type) {
	if(type=="update") {
		if(document.form1.body.value.length==0) {
			alert("투표결과 화면 디자인 내용을 입력하세요.");
			document.form1.body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("투표결과 화면 디자인을 삭제하시겠습니까?")) {
			document.form1.type.value=type;
			document.form1.submit();
		}
	} else if(type=="clear") {
		alert("기본값 복원 후 [적용하기]를 클릭하세요. 클릭 후 페이지에 적용됩니다.");
		document.form1.type.value=type;
		document.form1.submit();
	}
}

//-->
</SCRIPT>
<div class="admin_linemap"><div class="line"><p>현재위치 : 디자인관리 &gt; 개별디자인-페이지 본문 &gt;<span>투표결과 화면 꾸미기</span></p></div></div>

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
			<?php include("menu_design.php"); ?>
			</td>
			<td></td>
			<td valign="top">
			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr><td height="8">
            </td></tr>
			<tr>
				<td>
                <!-- 페이지 타이틀 -->
				<div class="title_depth3">투표결과 화면 꾸미기</div>
				<div class="title_dqpth3_sub"><span>투표결과 화면 디자인을 자유롭게 디자인 하실 수 있습니다.</span></div>
             	</td>
            </tr>
            <tr>
            	<td>
				<!-- 소제목 -->
				<div class="title_depth3_sub">투표결과 화면 개별디자인</div>            
				</td>
			</tr>
			<tr>
                <td>
                    <div class="help_info01_wrap">
                        <ul>
                            <li>1) 매뉴얼의 매크로명령어를 참조하여 디자인 하세요.</li>
                            <li>2) [기본값복원]+[적용하기], [삭제하기]하면 기본디자인으로 변경됩니다.</li>
                        </ul>
                    </div>
                </td>
			</tr>
			<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td style="padding-top:2px;"><textarea name=body style="WIDTH: 100%; HEIGHT: 600px" class="textarea"><?=$body?></textarea></td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" border="0" hspace="0"></a></td>
			</tr>
			</form>
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<!-- 매뉴얼 -->
				<div class="sub_manual_wrap">
					<div class="title"><p>매뉴얼</p></div>
					<dl>
					<dt><span class="point_c1">투표결과 화면 매크로명령어</span><span>(해당 매크로명령어는 다른 페이지 디자인 작업시 사용이 불가능함)</span></dt>
					<dd>
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SURVEYTITLE]</td>
							<td class=td_con1 style="padding-left:5;">
							해당 투표 제목
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SURVEYDATE]</td>
							<td class=td_con1 style="padding-left:5;">
							투표 시작일
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SURVEYTOT]</td>
							<td class=td_con1 style="padding-left:5;">
							투표 참여인원수
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SURVEYRESULT]</td>
							<td class=td_con1 style="padding-left:5;">
							투표 결과
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SURVEYREVIEW]</td>
							<td class=td_con1 style="padding-left:5;">
							해당 투표에 대한 의견 및 의견쓰기 폼
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SURVEYLIST]</td>
							<td class=td_con1 style="padding-left:5;">
							투표 리스트 보기 <FONT class=font_blue>(예:&lt;a href=[SURVEYLIST]>리스트&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SURVEYCLOSE]</td>
							<td class=td_con1 style="padding-left:5;">
							투표 창 닫기 <FONT class=font_blue>(예:&lt;a href=[SURVEYCLOSE]>닫기&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						</table>
					</dd>
					</dl>
					<dl>
						<dt><span>나모,드림위버등의 에디터로 작성시 이미지경로등 작업내용이 틀려질 수 있으니 주의하세요!</span></dt>
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
<?=$onload?>
<?php 
include("copyright.php");
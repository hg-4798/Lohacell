<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");
include("calendar.php");

####################### 페이지 접근권한 check ###############
$PageCode = "ma-2";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################

$maxcnt=10;
$eventpopup = array("tem_001","001","002","003","004");



?>

<?php include("header.php"); ?>

<script type="text/javascript" src="lib.js.php"></script>


<script language="JavaScript">
	_editor_url = "htmlarea/";

	var eventpopupcnt = <?=count($eventpopup)?>;




	function ModeSend(type, num) {
	    if (type == "delete") {
			if (!confirm("해당 팝업창을 삭제하시겠습니까?")) {
				return;
			}
		}
		document.form1.type.value = type;
		document.form1.num.value = num;
        document.form1.action = "market_eventpopup.write.php";
		document.form1.submit();
	}

	function ChangeDesign(tmp) {
		tmp = tmp + eventpopupcnt;
		document.form1["design"][tmp].checked = true;
	}

</script>
<div class="content-wrap">

	<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
		<input type=hidden name=type>
		<input type=hidden name=num value="<?=$num?>">
		<input type=hidden name=htmlmode value='wysiwyg'>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">

			<tr>
				<td>
					<!-- 페이지 타이틀 -->
					<div class="title_depth3">팝업 이벤트 관리</div>
					<!-- 소제목 -->
					<div class="title_depth3_sub">
						<span>이벤트, 긴급공지시 메인페이지 팝업창을 통해 고객에게 이벤트 내용을 알릴 수 있습니다.</span>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<!-- 소제목 -->
					<div class="title_depth3_sub">팝업창 목록</div>
				</td>
			</tr>
			<tr>
				<td style="padding-top:3pt; padding-bottom:3pt;">
					<!-- 도움말 -->
					<div class="help_info01_wrap">
						<ul>
							<li>1) &quot;초기화&quot; 버튼 클릭시 제휴사를 통한 방문 접속자가 &quot;0&quot;으로 초기화 됩니다.</li>
							<li>2) &quot;주문조회&quot; 버튼 클릭시 제휴사를 통하여 방문한 고객의 주문조회를 하실 수 있습니다.</li>
						</ul>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="table_style02">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<col width=50>
							<col width=>
							<col width=75>
							<col width=75>
							<col width=70>
							<col width=70>
							<col width="60">
							<col width=60>
							<col width=60>
							<TR align=center>
								<th>No</th>
								<th>이벤트 공지창 상단 제목</th>
								<th>시작일</th>
								<th>마감일</th>
								<th>팝업창타입</th>
								<th>등록일</th>
								<th>모바일</th>
								<th>수정</th>
								<th>삭제</th>
							</TR>
							<?php
				$colspan=8;
				$sql = "SELECT num, start_date, end_date, reg_date, frame_type, is_mobile, title FROM tbleventpopup ";
				$sql.= "ORDER BY num DESC ";
				$result = pmysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=pmysql_fetch_object($result)) {
					$cnt++;
					$date1 = substr($row->start_date,0,4).".".substr($row->start_date,4,2).".".substr($row->start_date,6,2);
					$date2 = substr($row->end_date,0,4).".".substr($row->end_date,4,2).".".substr($row->end_date,6,2);
					$reg_date = substr($row->reg_date,0,4).".".substr($row->reg_date,4,2).".".substr($row->reg_date,6,2);
					$on_mobile = $row->is_mobile;
					if($row->frame_type==0) $frame_type_name = "<img src=\"images/icon_type3.gif\" border=\"0\">";
					else if($row->frame_type==1)	$frame_type_name = "<img src=\"images/icon_type2.gif\" border=\"0\">";
					else if($row->frame_type==2)	$frame_type_name = "<img src=\"images/icon_type1.gif\" border=\"0\">";
					else if($row->frame_type==3)	$frame_type_name = "<img src=\"images/icon_type4.gif\" border=\"0\">";
					echo "<TR>\n";
					echo "	<TD>{$cnt}</TD>\n";
					echo "	<TD><div class=\"ta_l\">{$row->title}</div></TD>\n";
					echo "	<TD>{$date1}</TD>\n";
					echo "	<TD>{$date2}</TD>\n";
					echo "	<TD>{$frame_type_name}</TD>\n";
					echo "	<TD>{$reg_date}</TD>\n";
					echo "	<TD>{$on_mobile}</TD>\n";
					echo "	<TD><a href=\"javascript:ModeSend('modify','{$row->num}');\"><img src=\"images/btn_edit.gif\" border=\"0\"></a></TD>\n";
					echo "	<TD><a href=\"javascript:ModeSend('delete','{$row->num}');\"><img src=\"images/btn_del.gif\" border=\"0\"></a></TD>\n";
					echo "</TR>\n";
				}
				pmysql_free_result($result);
				if ($cnt==0) {
					echo "<TR><TD colspan={$colspan} align=center>등록된 팝업창이 없습니다.</TD></TR>";
				}
?>

						</TABLE>
					</div>
				</td>
			</tr>
            <tr>
                <td align="right">
                    <button type="button" id="btn_register" class="btn-point" onclick="popupWrite()">등록</button>
                </td>
            </tr>
			<tr>
				<td height="20">&nbsp;</td>
			</tr>
			<tr>
				<td>
					<!-- 매뉴얼 -->
					<div class="sub_manual_wrap">
						<div class="title">
							<p>매뉴얼</p>
						</div>

						<dl>
							<dt>
								<span>팝업창 사용가이드</span>
							</dt>
							<dd>
								- 팝업창은 최대 10개 까지 등록 가능합니다.
								<br>- 팝업창 종류중 &quot;레이어 타입&quot; 팝업창은 1개만 등록 가능합니다.
								<br>- 팝업창 크기는 340*400을 권장합니다. 이보다 크거나 작을 경우 디자인 템플릿과 정확히 맞지 않을 수 있습니다.
								<br>- 웹편집기 (드림위버, 나모웹에디터 등)로 작성 후 붙혀넣기로 할때는 이미지 경로에 유의하시기 바랍니다.
								<br>- 제목에는 가급적 HTML코드를 사용하지 마세요.
								<br>- 팝업창 위치는 다중 팝업창을 띄우는 경우 창 위치가 겹치지 않도록 위치를 각각 조절하시기 바랍니다.
								<br>
							</dd>

						</dl>
						<dl>
							<dt>
								<span>팝업창 하단 닫기 부분 입력폼</span>
							</dt>
							<dd>
								<TABLE cellSpacing=0 cellPadding=0 width="95%" border=0>
									<TR>
										<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;"
										    noWrap align=right width=150 bgColor=#f0f0f0 height="27">[CHECK]</TD>
										<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" width="100%">체크박스를 표시하는 태그입니다.</TD>
									</TR>
									<TR>
										<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;"
										    noWrap align=right width=150 bgColor=#f0f0f0 height="27">[CLOSE]</TD>
										<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;"
										    width="100%">팝업창을 닫는 태그입니다. 예) 창 닫기 &lt;a href=[CLOSE]&gt;[닫기]&lt;/a&gt;</TD>
									</TR>
								</TABLE>
							</dd>
						</dl>

					</div>
				</td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript">

    function popupWrite() {
        location.href="market_eventpopup.write.php";
    }

</script>

<?=$onload?>
	<?php
include("copyright.php");
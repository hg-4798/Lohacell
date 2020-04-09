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
/*$eventpopup = array("U","001","002","003","004");*/

$type=$_POST["type"];
$num=$_POST["num"];
$start_date=$_POST["start_date"];
$end_date=$_POST["end_date"];
$design=$_POST["design"];
$x_to=$_POST["x_to"];
$y_to=$_POST["y_to"];
$x_size=$_POST["x_size"]?:0;
$y_size=$_POST["y_size"]?:0;
$scroll_yn=$_POST["scroll_yn"];
$frame_type=$_POST["frame_type"];
$cookietime=$_POST["cookietime"];
$title=$_POST["title"];
$content=$_POST["content"];
$is_mobile =$_POST["is_mobile"];
$mobile_display = $_POST["mobile_display"];



$in_start = str_replace("-","",$start_date);
$in_end = str_replace("-","",$end_date);
if($type=="insert") {

			$sql = "INSERT INTO tbleventpopup(
			start_date	,
			end_date	,
			reg_date	,
			design		,
			x_size		,
			y_size		,
			x_to		,
			y_to		,
			scroll_yn	,
			cookietime	,
			title		,
			is_mobile   ,
			mobile_display,
			content) VALUES (
			'{$in_start}', 
			'{$in_end}', 
			'".date("YmdHis")."', 
			'tem_001', 
			'{$x_size}', 
			'{$y_size}', 
			'{$x_to}', 
			'{$y_to}', 
			'{$scroll_yn}', 
			'{$cookietime}', 
			'{$title}',
			'{$is_mobile}',
			'{$mobile_display}',
			'{$content}')";
			//echo $sql;exit;
			pmysql_query($sql,get_db_conn());
			alert_go('팝업창 등록이 완료되었습니다.',"/admin/market_eventpopup.php");
    } else if (($type=="modify_result" || $type=="modify") && ord($num)) {
	$sql = "SELECT * FROM tbleventpopup WHERE num = '{$num}' ";
	$result = pmysql_query($sql,get_db_conn());
	if($row=pmysql_fetch_object($result)) {
		pmysql_free_result($result);
		if($type=="modify") {
			$start_date=substr($row->start_date,0,4)."-".substr($row->start_date,4,2)."-".substr($row->start_date,6,2);
			$end_date=substr($row->end_date,0,4)."-".substr($row->end_date,4,2)."-".substr($row->end_date,6,2);
			$design=$row->design;
			$x_size=$row->x_size;
			$y_size=$row->y_size;
			$x_to=$row->x_to;
			$y_to=$row->y_to;
			$scroll_yn=$row->scroll_yn;
			$cookietime=$row->cookietime;
			$title=$row->title;
			$content=$row->content;
			$is_mobile=$row->is_mobile;
			$mobile_display=$row->mobile_display;
		} else if($type=="modify_result") {

				$sql = "UPDATE tbleventpopup SET ";
				$sql.= "start_date	= '{$in_start}', ";
				$sql.= "end_date	= '{$in_end}', ";
				$sql.= "design		= 'tem_001', ";
				$sql.= "x_size		= '{$x_size}', ";
				$sql.= "y_size		= '{$y_size}', ";
				$sql.= "x_to		= '{$x_to}', ";
				$sql.= "y_to		= '{$y_to}', ";
				$sql.= "scroll_yn	= '{$scroll_yn}', ";
				$sql.= "cookietime	= '{$cookietime}', ";
				$sql.= "title		= '{$title}', ";
                $sql.= "is_mobile		= '{$is_mobile}', ";
                $sql.= "mobile_display		= '{$mobile_display}', ";
				$sql.= "content		= '{$content}' ";
				$sql.= "WHERE num = '{$num}' ";
				pmysql_query($sql,get_db_conn());
				$onload="<script>window.onload=function(){ alert('팝업창 수정이 완료되었습니다.'); }</script>";

			//}
		}
	} else {
		pmysql_free_result($result);
		$onload="<script>window.onload=function(){ alert('수정하려는 팝업창 정보가 존재하지 않습니다.'); }</script>";
	}
} else if ($type=="delete" && ord($num)) {
	$sql = "SELECT * FROM tbleventpopup WHERE num = '{$num}' ";
	$result = pmysql_query($sql,get_db_conn());
	$rows=pmysql_num_rows($result);
	pmysql_free_result($result);

	if($rows>0) {
		$sql = "DELETE FROM tbleventpopup WHERE num = '{$num}' ";
		pmysql_query($sql,get_db_conn());
		alert_go('해당 팝업창을 삭제하였습니다.',"/admin/market_eventpopup.php");

	}
}

if(ord($start_date)==0) $start_date=date("Y-m-d");
if(ord($end_date)==0) $end_date=date("Y-m-d");

if(ord($type)==0) $type="insert";
$type_name="images/botteon_save.gif";
if($type=="modify" || $type=="modify_result") $type_name="images/btn_edit2.gif";
?>

<?php include("header.php"); ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="/third_party/SE2/js/HuskyEZCreator.js" charset="utf-8"></script>

<script language="JavaScript">
	_editor_url = "htmlarea/";

	var eventpopupcnt = <?=count($eventpopup)?>;

	function ChangeEditer(mode, obj) {
		if (mode == form1.htmlmode.value) {
			return;
		} else {
			obj.checked = true;
			editor_setmode('content', mode);
		}
		form1.htmlmode.value = mode;
	}

	function CheckForm(type) {

		if (document.form1.x_to.value.length == 0 || document.form1.y_to.value.length == 0) {
            alert("팝업창 위치 설정을 하세요.");
            document.form1.x_to.focus();
            return;
        }
        if (!IsNumeric(document.form1.x_to.value)) {
            alert("팝업창 위치 설정값은 숫자만 입력 가능합니다.");
            document.form1.x_to.focus();
            return;
        }
        if (!IsNumeric(document.form1.y_to.value)) {
            alert("팝업창 위치 설정값은 숫자만 입력 가능합니다.");
            document.form1.y_to.focus();
            return;
        }

        if (document.form1.title.value.length == 0) {
			alert("팝업창 제목을 입력하세요.");
			document.form1.title.focus();
			return;
		}

		var sHTML = oEditors.getById["ir1"].getIR();
		document.form1.content.value = sHTML;
		if (document.form1.content.value.length == 0) {
			alert("팝업창 내용을 입력하세요.");
			document.form1.content.focus();
			return;
		}
		if (type == "modify" || type == "modify_result") {
			if (!confirm("해당 팝업창을 수정하시겠습니까?")) {
				return;
			}
			document.form1.type.value = "modify_result";
		} else {
			document.form1.type.value = "insert";
		}
		document.form1.submit();
	}

	function ModeSend(type, num) {
		if (type == "delete") {
			if (!confirm("해당 팝업창을 삭제하시겠습니까?")) {
				return;
			}
		}
		document.form1.type.value = type;
		document.form1.num.value = num;
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
					<div class="title_depth3">팝업창 <?=($type=="insert")?"등록":"수정" ;?></div>
					<!-- 소제목 -->
					<div class="title_depth3_sub">
						<span>이벤트, 긴급공지시 메인페이지 팝업창을 통해 고객에게 이벤트 내용을 알릴 수 있습니다.</span>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="table_style01">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<tr>
								<th>
									<span>모바일용 팝업창</span>
								</th>
								<TD class="td_con1">
									<label>
                                        <input type="radio" name="is_mobile" value="ALL" class="hj" <? if($is_mobile=="ALL" || $is_mobile==""){ echo "checked";}?>>
                                        <span class="lbl">전체</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="is_mobile" value="P" class="hj" <? if($is_mobile=="P"){ echo "checked";}?>>
                                        <span class="lbl">PC</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="is_mobile" value="M" class="hj" <? if($is_mobile=="M"){ echo "checked";}?>>
                                        <span class="lbl">MOBILE</span>
                                    </label>
								</TD>
							</tr>
							<TR>
								<th>
									<span>공지 기간</span>
								</th>
								<TD class="td_con1">
									<INPUT style="TEXT-ALIGN: center" onfocus=this.blur(); onclick=Calendar(event) size=15 name=start_date value="<?=$start_date?>"
									    class="input">부터
									<INPUT style="TEXT-ALIGN: center" onfocus=this.blur(); onclick=Calendar(event) size=15 name=end_date value="<?=$end_date?>"
									    class="input">까지&nbsp;&nbsp;
									<span class="font_orange">＊해당 기간 내에만 팝업창이 뜹니다.</span>
								</TD>
							</TR>
							<TR class='CLS_PcPopUp'>
								<th>
									<span>팝업창 위치 설정</span>
								</th>
								<TD class="td_con1">왼쪽에서
									<INPUT onkeyup="return strnumkeyup(this);" style="PADDING-LEFT: 5px" size=5 name=x_to value="<?=$x_to?>" class="input">픽셀 이동 후, 위쪽에서
									<INPUT onkeyup="return strnumkeyup(this);" style="PADDING-LEFT: 5px" size=5 name=y_to value="<?=$y_to?>"
									    class="input">픽셀 아래로 이동합니다.</TD>
							</TR>
							<TR class='CLS_PcPopUp' style="display:none;">
								<th>
									<span>팝업창 크기 설정</span>
								</th>
								<TD class="td_con1">
									가로:
									<INPUT onkeyup="return strnumkeyup(this);" style="PADDING-LEFT: 5px" size=5 name=x_size value="<?=$x_size?>"
									    class="input">픽셀, &nbsp; 세로:
									<INPUT onkeyup="return strnumkeyup(this);" style="PADDING-LEFT: 5px" size=5 name=y_size value="<?=$y_size?>"
									    class="input">픽셀</TD>
							</TR>
							<tr class='CLS_PcPopUp'>
								<th>
									<span>팝업창 재표시 기간</span>
								</th>
								<TD class="td_con1">
                                    <select name="cookietime" style="width:200px;">
                                        <option value="1" <?if($cookietime=="1"){echo "selected";}?>>하루동안 열리지 않음</option>
                                        <option value="2" <?if($cookietime=="2"){echo "selected";}?>>다시 열지 않음</option>
                                     </select>
								</TD>
							</tr>
							<tr>
								<th>
									<span>팝업창 제목</span>
								</th>
								<TD class="td_con1">
									<INPUT style="WIDTH: 100%" name=title value="<?=$title?>" class="input">
								</TD>
							</tr>
					</div>
                </TD>
			</tr>
					</TABLE>
				</td>
			</tr>
			<tr>
				<td class="bd_editer">
					<table cellpadding="0" cellspacing="0" width="100%">

						<tr>
							<td width="100%">
								<TEXTAREA style="DISPLAY: yes; WIDTH: 100%" name=content rows="17" id="ir1" wrap=off>
									<?=$content?>
								</TEXTAREA>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="pt_20" align=center>
					<a href="javascript:CheckForm('<?=$type?>');">
                        <?if($type=="insert"){?>
                        <button type="button" id="btn_register" class="btn-point">등록</button>
                        <?}else{?>
                        <button type="button" id="btn_register" class="btn-point">수정</button>
                        <?}?>
					</a>
                    <button type="button" id="btn_register" class="btn-basic" onclick="goList()">목록</button>
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
	var oEditors = [];

nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "ir1",
	sSkinURI: "/third_party/SE2/SmartEditor2Skin.html",
	htParams : {
		bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
		bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
		bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
		//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
		fOnBeforeUnload : function(){
		}
	}, 
	fOnAppLoad : function(){
	},
	fCreator: "createSEditor2"
});
    function goList() {
            location.href="market_eventpopup.php";
    }

</script>
<?=$onload?>
	<?php
include("copyright.php");
?>
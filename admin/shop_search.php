<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "sh-3";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################

// 저장
$type						= $_POST["type"];
$up_bestkeyword		= $_POST["up_bestkeyword"];
$up_keyword			= $_POST["up_keyword"];
$up_defaultkeyword	= $_POST["up_defaultkeyword"];

if($type=="up") {
	if(ord($up_bestkeyword)==0) $up_bestkeyword="Y";

	$search_info="";
	$search_info.="BESTKEYWORD={$up_bestkeyword}=";
	$search_info.="KEYWORD=".$up_keyword;

	$sql="UPDATE tblshopinfo SET search_info='{$search_info}', search_default_keyword='".$up_defaultkeyword."'";
	pmysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>window.onload=function(){alert('상품검색 관련 기능 설정이 완료되었습니다.'); }</script>";
}

// 조회 
$sql = "SELECT search_info, search_default_keyword FROM tblshopinfo";
$result=pmysql_query($sql,get_db_conn());
if($data=pmysql_fetch_object($result)) {

}
pmysql_free_result($result);

$search_info			=$data->search_info;

$bestkeyword		= "";
$keyword			= "";											// 인기검색어
$defaultkeyword 	= $data-> search_default_keyword;	// 기본검색

if(ord($search_info)) {
	$temp=explode("=",$search_info);
	$cnt = count($temp);
	for ($i=0;$i<$cnt;$i++) {
		if (substr($temp[$i],0,12)=="BESTKEYWORD=") $bestkeyword=substr($temp[$i],12);	#인기검색어기능 사용여부(Y/N)
		else if (substr($temp[$i],0,8)=="KEYWORD=") $keyword=substr($temp[$i],8);	#인기검색어 수동등록 리스트
	}
}
if(ord($bestkeyword)==0) $bestkeyword="Y";
${"check_bestkeyword".$bestkeyword}="checked";

?>

<?php include("header.php"); ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
	var IE = false;
	if (window.navigator.appName.indexOf("Explorer") != -1) {
		IE = true;
	}

	function CheckForm() {
		if (document.form1.up_bestkeyword[0].checked && document.form1.up_keyword.value.length == 0) {
			alert("인기검색어를 입력하세요.");
			document.form1.up_keyword.focus();
			return;
		}
		if (confirm("상품검색 관련 기능설정을 하시겠습니까?")) {
			document.form1.type.value = "up";
			document.form1.submit();
		}
	}

	var restrictedSearchChars = /[\x25\x26\x2b\x3c\x3e\x3f\x2f\x5c\x27\x22\x3d\x20]|(\x5c\x6e)/g;

	function validSearch(searchObj, e) {
		var searchVal = searchObj.value;
		var commacnt = 0;
		var key = window.event ? e.keyCode : e.which;

		if (searchVal.charAt(searchVal.length - 1) == ',' && (key == 44 || key == 32))
			return false;
		for (var i = 0; i < searchVal.length; i++) {
			if (searchVal.charAt(i) == ',') {
				commacnt++;
			}
			if (commacnt >= 9) {
				alert("태그는 최대 10개까지 입력할 수 있습니다.");
				return false;
			}
		}

		if (key != 0x2C && (key > 32 && key < 48) || (key > 57 && key < 65) || (key > 90 && key < 97))
			return false;
	}

	function check_searchvalidate(aEvent, input) {
		var keynum;
		if (typeof aEvent == "undefined") aEvent = window.event;
		if (IE) {
			keynum = aEvent.keyCode;
		} else {
			keynum = aEvent.which;
		}
		var ret = input.value;
		if (ret.match(restrictedSearchChars) != null) {
			ret = ret.replace(restrictedSearchChars, "");
			input.value = ret;
		}
		//콤마가 연속으로 있으면 하나로 만든다.
		re = /[\x2c][\x2c]+/g;
		if (ret.match(re) != null) {
			ret = ret.replace(re, ",");
			input.value = ret;
		}
	}

	function check_searchsvalidate(input) {
		input.value = validateSearchString(input.value);

		//중복되는 태그 제거
		input.value = eliminateDuplicate(input.value);

		var searchcount = input.value.split(",").length;
		//태그 수 제한
		if (searchcount > 10) {
			alert("인기검색어는 최대 10개 까지 입력이 가능합니다.");
			input.value = absoluteSearchString(input.value, 10);
			input.focus();
			return;
		}

		//태그의 길이 제한
		var bvalidate;
		var searchmaxlength = 100;
		bvalidate = isValidateSearchLength(input.value, searchmaxlength);
		if (!bvalidate) {
			alert("인기검색어는 100자 이상 입력할 수 없습니다.");
			input.focus();
			return;
		}
	}

	function absoluteSearchString(searchstring, maxcnt) {
		var valisearchs = validateSearchString(searchstring);
		var arraysearch = valisearchs.split(",");
		var searchnames = "";
		var absolutecnt = arraysearch.length;
		if (absolutecnt > maxcnt)
			absolutecnt = maxcnt;

		for (var i = 0; i < absolutecnt; i++) {
			searchnames = searchnames + arraysearch[i] + ",";
		}
		searchnames = validateSearchString(searchnames);
		searchnames = searchnames.substring(0, searchnames.length - 1);
		return searchnames;
	}

	function validateSearchString(searchstring) {
		var ret = searchstring.replace(restrictedSearchChars, "");

		//콤마가 연속으로 있으면 하나로 만든다.
		re = /[\x2c]+/g;
		return ret.replace(re, ",");
	}

	function eliminateDuplicate(searchstring) {
		var valisearchs = validateSearchString(searchstring);
		var arraysearch = valisearchs.split(",");
		var searchnames = "";
		for (var i = 0; i < arraysearch.length; i++) {
			for (var j = 0; j < i; j++) {
				//이미 존재 하는 태그라면 없앰.
				if (arraysearch[j] == arraysearch[i]) {
					arraysearch[i] = "";
				}
			}

			searchnames = searchnames + arraysearch[i] + ",";
		}
		searchnames = validateSearchString(searchnames);
		searchnames = searchnames.substring(0, searchnames.length - 1);
		return searchnames;
	}

	function isValidateSearchLength(searchstring, maxlen) {
		var arraysearch = searchstring.split(",");
		for (var i = 0; i < arraysearch.length; i++) {
			if (arraysearch[i].length > maxlen) {
				return false;
			}
		}
		return true;
	}
</script>

<div class="content-wrap">
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td>
				<!-- 페이지 타이틀 -->
				<div class="title_depth3">상품검색 관련 기능설정
					<span>상품검색의 인기검색어 관련 기능을 설정하실 수 있습니다.</span>
				</div>
			</td>
		</tr>
		<!-- 기본 검색어 -->
		<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
					<!-- 소제목 -->
					<div class="title_depth3_sub">기본검색어 기능 설정</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="table_style01">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<th>
									<span>기본검색어 입력</span>
								</th>
								<TD class="td_con1">
									<input type=text name=up_defaultkeyword value="<?=$defaultkeyword?>" size=100 onkeyup="check_searchvalidate(event, this);"
									    onblur="check_searchsvalidate(this);" class="input">
									<br>
								</TD>
							</TR>
						</TABLE>
					</div>
				</td>
			</tr>

			<!-- 인기 검색어 -->
			<tr>
				<td>
					<!-- 소제목 -->
					<div class="title_depth3_sub">인기검색어 기능 설정</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="table_style01">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR style="display: none;">
								<th>
									<span>인기검색어 사용여부 선택</span>
								</th>
								<TD class="td_con1">
									<input type=radio id="idx_bestkeyword1" name=up_bestkeyword value="Y" <?=$check_bestkeywordY?>>
									<label style='cursor: hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bestkeyword1>인기검색어 기능 사용</label>&nbsp;&nbsp;&nbsp;
									<input type=radio id="idx_bestkeyword2" name=up_bestkeyword value="N" <?=$check_bestkeywordN?>>
									<label style='cursor: hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bestkeyword2>인기검색어 기능 미사용</label>
									<br>
									<span class=font_blue>* 인기검색어는 쇼핑몰의 특정 상품을 노출시키고자 할 경우 유용하게 사용됩니다.</span>
								</TD>
							</TR>
							<TR>
								<th>
									<span>노출 인기검색어 입력</span>
								</th>
								<TD class="td_con1">
									<input type=text name=up_keyword value="<?=$keyword?>" size=100 onkeyup="check_searchvalidate(event, this);" onblur="check_searchsvalidate(this);"
									    class="input">

									<div class="font_blue m-t-5">* 인기검색어 기능을 사용할 경우에만 적용됩니다. (콤마","로 구분하여 등록하세요)</div>
								</TD>
							</TR>
						</TABLE>
					</div>
				</td>
			</tr>

			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center">
					<a href="javascript:CheckForm();">
						<span class="btn-point">적용하기</span>
					</a>
				</td>
			</tr>
	</table>
	</form>
	<div class="sub_manual_wrap">
		<div class="title">
			<p>매뉴얼</p>
		</div>
		<ul class="help_list">
			<li>기본검색어는 검색Bar에 노출되며, 인기검색어는 검색화면에서 [추천 검색어]에 노출됩니다. </li>
			<li>검색어 클릭 시 해당 검색어로 검색됩니다.</li>
		</ul>
	</div>
</div>
<?php 
include("copyright.php");
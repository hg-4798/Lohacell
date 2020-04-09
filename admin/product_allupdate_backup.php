<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "pr-4";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################

$searchtype=0;
$code=$_POST["code"];
if(strlen($code)==12) {
	list($code_a,$code_b,$code_c,$code_d) = sscanf($code,'%3s%3s%3s%3s');	
}

include("header.php"); 
?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="codeinit.js.php"></script>
<script language="JavaScript">
function CodeProcessFun(_code) {
	if(_code=="out" || _code.length==0 || _code=="000000000000") {
		document.all["code_top"].style.background="#dddddd";
		selcode="";
		seltype="";

		if(_code!="out") {
			BodyInit('');
		} else {
			_code="";
		}
	} else {
		document.all["code_top"].style.background="#ffffff";
		BodyInit(_code);
	}

	if(selcode.length==12 && selcode!="000000000000" && seltype.indexOf("X")!=-1) {
		document.form2.mode.value="";
		document.form2.code.value=selcode;
		document.form2.target="ListFrame";
		document.form2.action="product_allupdate.list.php";
		document.form2.submit();
	} else {
		document.form2.mode.value="";
		document.form2.code.value="";
		document.form2.target="ListFrame";
		document.form2.action="product_allupdate.list.php";
		document.form2.submit();
	}
}

var allopen=false;
function AllOpen() {
	display="show";
	open1="open";
	if(allopen) {
		display="none";
		open1="close";
		allopen=false;
	} else {
		allopen=true;
	}
	for(i=0;i<all_list.length;i++) {
		if(display=="none" && all_list[i].code_a==selcode.substring(0,3)) {
			all_list[i].selected=true;
			selcode=all_list[i].code_a+all_list[i].code_b+all_list[i].code_c+all_list[i].code_d;
			seltype=all_list[i].type;
		}
		all_list[i].display=display;
		all_list[i].open=open1;
		for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
			if(display=="none") {
				all_list[i].ArrCodeB[ii].selected=false;
			}
			all_list[i].ArrCodeB[ii].display=display;
			all_list[i].ArrCodeB[ii].open=open1;
			for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
				if(display=="none") {
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
				}
				all_list[i].ArrCodeB[ii].ArrCodeC[iii].display=display;
				all_list[i].ArrCodeB[ii].ArrCodeC[iii].open=open1;
				for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
					if(display=="none") {
						all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
					}
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display=display;
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].open=open1;
				}
			}
		}
	}
	BodyInit('');
}

function ViewLayer(layer) {
	if(layer=="layer2") {
		document.all["contentDiv"].disabled=true;
		document.all["hide_code_div"].style.display="none";
		document.all["hide_code_div2"].style.display="";
		document.all["layer2"].style.display="";
	} else {
		document.all["contentDiv"].disabled=false;
		document.all["hide_code_div"].style.display="";
		document.all["hide_code_div2"].style.display="none";
		document.all["layer2"].style.display="none";
	}
}

function CheckSearch() {
	document.form1.mode.value = "";
	document.form1.code.value = "";
	if (document.form1.keyword.value.length<2) {
		if(document.form1.keyword.value.length==0) alert("검색어를 입력하세요.");
		else alert("검색어는 2글자 이상 입력하셔야 합니다."); 
		document.form1.keyword.focus();
		return;
	} else {
		document.form1.target="ListFrame";
		document.form1.action="product_allupdate.list.php";
		document.form1.submit();
	}
}

function CheckKeyPress(){
	ekey=event.keyCode;
	if (ekey==13) {
		CheckSearch();
	}
}

var divLeft=0;
var defaultLeft=0;
var timeOffset=0;
var setTObj;
var divName="";
var zValue=0;

function divMove()
{
	divLeft+=timeOffset;
		
	if(divLeft >= defaultLeft)
	{
		divLeft=defaultLeft;
		divName.style.left=divLeft;
		divName.style.zIndex = zValue;
		clearTimeout(setTObj);
		setTObj="";
	}
	else
	{
		timeOffset+=20;
		divName.style.left=divLeft;
		setTObj=setTimeout('divMove();',5);
	}
}

function divAction(arg1,arg2)
{
	if(zValue != arg2 && !setTObj)
	{
		defaultLeft = arg1.offsetLeft;
		divLeft = defaultLeft;
		zValue = arg2;
		divName = arg1;
		if(defaultLeft>0)
			timeOffset = -70;
		divMove();
	}
}
</script>

<STYLE type=text/css>
	#menuBar {}
	#contentDiv {WIDTH: 200;HEIGHT: 315;}
</STYLE>

<table cellpadding="0" cellspacing="0" width="980" style="table-layout:fixed">
<tr>
	<td width=10></td>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td height="29">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td height="28" class="link" align="right"><img src="images/top_link_house.gif" border="0" valign="absmiddle">현재위치 : 상품관리 &gt; 상품 일괄관리 &gt; <span class="2depth_select">상품 일괄 간편수정</span></td>
		</tr>
		<tr>
			<td><img src="images/top_link_line.gif" width="100%" height="1" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
		<col width=190></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top" background="images/left_bg.gif" style="padding-top:15">
			<?php include("menu_product.php"); ?>
			</td>

			<td></td>

			<td valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_allupdate_title.gif" WIDTH="208" HEIGHT=32 ALT=""></TD>
					<TD width="100%" background="images/title_bg.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">쇼핑몰에 등록된 상품의 가격을 포함한 적립금,수량 등을 일괄 수정할 수 있습니다.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=20></td></tr>
			<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<input type=hidden name=mode value="<?=$mode?>">
			<input type=hidden name=code>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="750" bgcolor="#0099CC" style="padding:6pt;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td width="100%">
						<TABLE cellSpacing=0 cellPadding="0" width="100%" border=0>
						<TR>
							<TD class="table_cell" width="214"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b>상품보기 선택</b></TD>
							<TD class="td_con1"><input type=radio id="idx_searchtype1" name=searchtype value="0" onclick="ViewLayer('layer1')" <?php if($searchtype=="0") echo "checked";?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype1>카테고리별 상품 보기</label>&nbsp;&nbsp;&nbsp;<input type=radio id="idx_searchtype2" name=searchtype value="1" onclick="ViewLayer('layer2')" <?php if($searchtype=="1") echo "checked";?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype2>검색으로 상품 보기</label></TD>
						</TR>
						</table>
						<div id=layer2 style="margin-left:0;display:hide; display:<?=($searchtype=="1"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
						<TABLE cellSpacing=0 cellPadding="0" width="100%" border=0>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="214"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b>상품명 입력</b></TD>
							<TD class="td_con1">
							<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="99%"><input type=text name=keyword size=50 value="<?=$keyword?>" onKeyDown="CheckKeyPress()" class="input" style=width:100%></td>
								<td width="1%" align=right><a href="javascript:CheckSearch();"><img src="images/btn_search2.gif" width="50" height="25" border="0" align=absmiddle hspace="2"></a></td>
							</tr>
							</table>
							</TD>
						</TR>
						</TABLE>
						</div>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%" height="594">
				<tr>
					<td valign="top">
					<DIV onmouseover="divAction(this,2);" id="cateidx" style="position:absolute;z-index:0;width:242px;bgcolor:#FFFFFF;">
					<table cellpadding="0" cellspacing="0" width="100%" height="554">
					<tr>
						<td width="232" height="100%" valign="top" background="images/category_boxbg.gif">
						<table cellpadding="0" cellspacing="0" width="242" height="100%">
						<tr>
							<td bgcolor="#FFFFFF"><IMG SRC="images/product_totoacategory_title.gif" border="0"></td>
						</tr>
						<tr>
							<td><IMG SRC="images/category_box1.gif" border="0"></td>
						</tr>
						<tr>
							<td bgcolor="#0F8FCB" style="padding:2;padding-left:4">
							<button title="전체 트리확장" id="btn_treeall" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="AllOpen();"><IMG SRC="images/category_btn1.gif" WIDTH=22 HEIGHT=23 border="0"></button>
							</td>
						</tr>
						<tr>
							<td bgcolor="#0F8FCB" style="padding-top:4pt; padding-bottom:6pt;"></td>
						</tr>
						<tr>
							<td><IMG SRC="images/category_box2.gif" border="0"></td>
						</tr>
						<tr>
							<td width="100%" height="100%" align=center valign=top style="padding-left:5px;padding-right:5px;">
							<div id=hide_code_div style="width=99%;height:100%;">
							<DIV class=MsgrScroller id=contentDiv style="width=99%;height:100%;OVERFLOW-x: auto; OVERFLOW-y: auto; z-index:1" oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
							<DIV id=bodyList>
							<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%" bgcolor="#FFFFFF">
							<tr>
								<td height=18><IMG SRC="images/directory_root.gif" border=0 align=absmiddle> <span id="code_top" style="cursor:default;" onmouseover="this.className='link_over'" onmouseout="this.className='link_out'" onclick="ChangeSelect('out');">최상위 카테고리</span></td>
							</tr>
							<tr>
								<!-- 상품카테고리 목록 -->
								<td id="code_list" nowrap valign=top></td>
								<!-- 상품카테고리 목록 끝 -->
							</tr>
							</table>
							</DIV>
							</DIV>
							</div>
							<div id=hide_code_div2 style="width=99%;height:100%;display:none;">
							<DIV class=MsgrScroller id=contentDiv2 style="width=99%;height:100%;OVERFLOW-x: auto; OVERFLOW-y: auto; background:#f4f4f4" oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" disabled>
							<DIV id=bodyList2>
							<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%" bgcolor="#FFFFFF">
							<tr>
								<td nowrap align=center>카테고리별 상품보기에서만<br>카테고리정보를 보실 수 있습니다.</td>
							</tr>
							</table>
							</DIV>
							</DIV>
							</div>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td><IMG SRC="images/category_boxdown.gif" border="0"></td>
					</tr>
					</table>
					</div>
					</td>
					<td style="padding-left:84px;"></td>
					<td width="100%" valign="top" height="100%" onmouseover="divAction(document.getElementById('cateidx'),0);"><DIV style="position:relative;z-index:1;width:100%;height:100%;bgcolor:#FFFFFF;"><IFRAME name="ListFrame" src="product_allupdate.list.php" width=100% height=594 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME></div></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<IFRAME name="HiddenFrame" src="<?=$Dir?>blank.php" width=0 height=0 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME>
			</form>
			<form name=form2 action="" method=post>
			<input type=hidden name=mode>
			<input type=hidden name=code>
			</form>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">상품 일괄 간편수정 주의사항</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- 시중가, 구입가, 판매가, 적립금, 수량 입력시 콤마(,)는 입력하지 마세요.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">상품 일괄 간편수정 방법</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">① 상품보기 선택에 따라 카테고리 선택 또는 상품명으로 검색합니다.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">② 출력된 상품들 중 수정을 원하는 상품만 입력내용을 수정합니다.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">③ 수정이 완료 됐으며 [적용하기] 버튼을 클릭합니다.</td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
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
<?php
$sql = "SELECT * FROM tblproductcode WHERE type!='T' AND type!='TX' AND type!='TM' AND type!='TMX' ";
$sql.= "ORDER BY sequence DESC ";
include("codeinit.php");
include("copyright.php");

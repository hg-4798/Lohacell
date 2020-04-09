<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");
include_once($Dir."lib/venderlib.php");
include("access.php");

$mode=$_POST["mode"];
if($mode=="update") {
	$productcode=$_POST["productcode"];
	$num=$_POST["num"];
	$memo=$_POST["memo"];
	
	$sql = "SELECT a.content FROM tblproductreview a, tblproduct b ";
	$sql.= "WHERE a.productcode='".$productcode."' AND a.num='".$num."' ";
	$sql.= "AND a.productcode=b.productcode AND b.vender='".$_VenderInfo->getVidx()."' ";
	$result=pmysql_query($sql,get_db_conn());
	if($row=pmysql_fetch_object($result)) {
		pmysql_free_result($result);
		$content=explode("=",$row->content);
		$up_content=$content[0];
		if(strlen($memo)>0) {
			$up_content.="=".$memo;
		}
		$sql = "UPDATE tblproductreview SET content='".$up_content."' ";
		$sql.= "WHERE productcode='".$productcode."' AND num='".$num."' ";
		if(pmysql_query($sql,get_db_conn())) {
			echo "<html></head><body onload=\"alert('요청하신 작업이 성공하였습니다.');parent.document.pageForm.submit()\"></body></html>";exit;
		} else {
			echo "<html></head><body onload=\"alert('요청하신 작업중 오류가 발생하였습니다.')\"></body></html>";exit;
		}
	} else {
		pmysql_free_result($result);
		echo "<html></head><body onload=\"alert('해당 데이터가 존재하지 않습니다.')\"></body></html>";exit;
	}
}

$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*14));
$period[3] = date("Y-m-d",strtotime('-1 month'));

$code=$_POST["code"];
$s_check=$_POST["s_check"];
$search=$_POST["search"];
$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$vperiod=(int)$_POST["vperiod"];

$search_start=$search_start?$search_start:$period[0];
$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

${"check_vperiod".$vperiod} = "checked";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (strtotime($search_end)-strtotime($search_start))/86400;
if ($termday>367) {
	echo "<script>alert('검색기간은 1년을 초과할 수 없습니다.');location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
}

$qry = "WHERE a.productcode=b.productcode AND b.vender='".$_VenderInfo->getVidx()."' ";
if(strlen($code)>=3) {
	$qry.= "AND b.productcode LIKE '".$code."%' ";
}
if(substr($search_s,0,8)==substr($search_e,0,8)) {
	$qry.= "AND a.date LIKE '".substr($search_s,0,8)."%' ";
} else {
	$qry.= "AND a.date>='".$search_s."' AND a.date <='".$search_e."' ";
}
//$qry.= "AND a.display='Y' ";
$qry.= "AND b.display='Y' ";
if(strlen($search)>0) {
	if($s_check=="t") $qry.= "AND a.content LIKE '%".$search."%' ";
	else if($s_check=="n") $qry.= "AND a.name='".$search."' ";
}

include("header.php"); 
?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">
function ACodeSendIt(code) {
	document.sForm.code.value=code;
	murl = "order.ctgr.php?code="+code;
	BCodeCtgr.location.href = murl;
}

function OnChangePeriod(val) {
	var pForm = document.sForm;
	var period = new Array(7);
	period[0] = "<?=$period[0]?>";
	period[1] = "<?=$period[1]?>";
	period[2] = "<?=$period[2]?>";
	period[3] = "<?=$period[3]?>";

	pForm.search_start.value = period[val];
	pForm.search_end.value = period[0];
}

function searchForm() {
	document.sForm.submit();
}

function GoPage(block,gotopage) {
	document.pageForm.block.value=block;
	document.pageForm.gotopage.value=gotopage;
	document.pageForm.submit();
}

var old_menu="";
function view(submenu){
	if(old_menu!=submenu) {
		if(old_menu!="") old_menu.style.display = 'none';
		submenu.style.display="block";
		old_menu=submenu;
	} else {
		submenu.style.display="none";
		old_menu="";
	}
}

function formSubmit(form) {
	form.mode.value="update";
	form.target="processFrame";
	form.submit();
}

</script>

<table border=0 cellpadding=0 cellspacing=0 width=1000 style="table-layout:fixed">
<col width=175></col>
<col width=5></col>
<col width=740></col>
<col width=80></col>
<tr>
	<td width=175 valign=top nowrap><? include ("menu.php"); ?></td>
	<td width=5 nowrap></td>
	<td valign=top>

	<table width="100%"  border="0" cellpadding="1" cellspacing="0" bgcolor="#D0D1D0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" style="border:3px solid #EEEEEE" bgcolor="#ffffff">
		<tr>
			<td style="padding:10">
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<tr>
				<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=165></col>
				<col width=></col>
				<tr>
					<td height=29 align=center background="images/tab_menubg.gif">
					<FONT COLOR="#ffffff"><B>상품 사용후기 관리</B></FONT>
					</td>
					<td></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height=2 bgcolor=red></td></tr>
			<tr>
				<td bgcolor=#FBF5F7>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=10></col>
				<col width=></col>
				<col width=10></col>
				<tr>
					<td colspan=3 style="padding:15,15,5,15">
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td style="padding-bottom:5"><img src="images/icon_boxdot.gif" border=0 align=absmiddle> <B>상품 사용후기 관리</B></td>
					</tr>
					<tr>
						<td style="padding-left:5;color:#7F7F7F"><img src="images/icon_dot02.gif" border=0> 입점사가 등록한 상품에 대해서만 사용후기 게시물을 확인할 수 있습니다.</td>
					</tr>
					<tr>
						<td style="padding-left:5;color:#7F7F7F"><img src="images/icon_dot02.gif" border=0> 입점사는 등록된 사용후기 게시물의 관리[답변/수정/삭제]를 할 수 있습니다.</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td><img src="images/tab_boxleft.gif" border=0></td>
					<td></td>
					<td><img src="images/tab_boxright.gif" border=0></td>
				</tr>
				</table>
				</td>
			</tr>

			<!-- 처리할 본문 위치 시작 -->
			<tr><td height=0></td></tr>
			<tr>
				<td style="padding:15">

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<form name=sForm action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=code value="<?=$code?>">
				<tr>
					<td valign=top bgcolor=D4D4D4 style=padding:1>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td valign=top bgcolor=F0F0F0 style=padding:10>
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
						<col width=></col>
						<col width=130></col>
						<tr>
							<td>
							<U>접수일</U>&nbsp; <input type=text name=search_start value="<?=$search_start?>" size=10 onfocus="this.blur();" OnClick="Calendar(this)" style="text-align:center;font-size:8pt"> ~ <input type=text name=search_end value="<?=$search_end?>" size=10 onfocus="this.blur();" OnClick="Calendar(this)" style="text-align:center;font-size:8pt">
							&nbsp;
							<img src=images/btn_today01.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(0)">
							<img src=images/btn_day07.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(1)">
							<img src=images/btn_day14.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(2)">
							<img src=images/btn_day30.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(3)">
							&nbsp;&nbsp;&nbsp;&nbsp;
							<U>분류</U>&nbsp;
							<select name="code1" style=width:130; onchange="ACodeSendIt(this.options[this.selectedIndex].value)">
							<option value="">--- 선택하세요 ---</option>
<?
							$sql = "SELECT SUBSTR(productcode,1,3) as prcode FROM tblproduct ";
							$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
							$sql.= "GROUP BY prcode ";
							$result=pmysql_query($sql,get_db_conn());
							$codes="";
							while($row=pmysql_fetch_object($result)) {
								$codes.=$row->prcode.",";
							}
							pmysql_free_result($result);
							if(strlen($codes)>0) {
								$codes=rtrim($codes,',');
								$prcodelist=str_replace(',','\',\'',$codes);
							}
							if(strlen($prcodelist)>0) {
								$sql = "SELECT code_a,code_b,code_c,code_d,code_name FROM tblproductcode ";
								$sql.= "WHERE code_a IN ('".$prcodelist."') AND code_b='000' AND code_c='000' ";
								$sql.= "AND code_d='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
								$result=pmysql_query($sql,get_db_conn());
								while($row=pmysql_fetch_object($result)) {
									echo "<option value=\"".$row->code_a."\"";
									if($row->code_a==substr($code,0,3)) echo " selected";
									echo ">".$row->code_name."</option>\n";
								}
								pmysql_free_result($result);
							}
?>
							</select>
							</td>
							<td><iframe name="BCodeCtgr" src="order.ctgr.php?code=<?=substr($code,0,3)?>&select_code=<?=$code?>" width="130" height="21" scrolling=no frameborder=no></iframe></td>
						</tr>
						<tr><td colspan=2 height=8></td></tr>
						<tr>
							<td colspan=2>
							<U>검색어</U>&nbsp;
							<select name=s_check style="font-size:8pt">
							<option value="n" <?if($s_check=="n")echo"selected";?>>작성자</option>
							<option value="t" <?if($s_check=="t")echo"selected";?>>제목+내용</option>
							</select>
							<input type=text name=search value="<?=$search?>" size=30>
							<A HREF="javascript:searchForm()"><img src=images/btn_inquery03.gif border=0 align=absmiddle></A>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</form>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<tr><td height=20></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
				<col width=220></col>
				<col width=></col>
				<col width=70></col>
				<col width=60></col>
				<col width=75></col>
				<tr height=28 align=center bgcolor=F5F5F5>
					<td><B>상품명</B></td>
					<td><B>상품평</B></td>
					<td><B>글쓴이</B></td>
					<td><B>답변</B></td>
					<td><B>등록일</B></td>
				</tr>
<?php
				$sql = "SELECT COUNT(*) as t_count FROM tblproductreview a, tblproduct b ".$qry." ";
				$paging = new Paging($sql,10,10);
				$t_count = $paging->t_count;
				$gotopage = $paging->gotopage;

				$sql = "SELECT a.*, b.productname,b.selfcode FROM tblproductreview a, tblproduct b ".$qry." ";
				$sql.= "ORDER BY a.date DESC ";
				$sql = $paging->getSql($sql);
				$result=pmysql_query($sql,get_db_conn());
				$i=0;
				while($row=pmysql_fetch_object($result)) {
					$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
					$date=substr($row->date,0,4)."-".substr($row->date,4,2)."-".substr($row->date,6,2);
					$content=explode("=",$row->content);

					echo "<tr align=center valign=top bgcolor=FFFFFF>\n";
					echo "	<td width=100% nowrap style=padding-top:5;padding-left:3 align=left>\n";
					echo "	<span style='width:97%;overflow:hidden;text-overflow:ellipsis;'>\n";
					echo "	<a href=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" target=_blank>".$row->productname.($row->selfcode?"-".$row->selfcode:"")."</a>\n";
					echo "	</span>\n";
					echo "	</td>\n";
					echo "	<td colspan=4>\n";
					echo "	<table width=100% border=0 cellspacing=0 cellpadding=0 style=\"table-layout:fixed\">\n";
					echo "	<col width=></col><col width=1></col><col width=70></col><col width=1></col><col width=60></col><col width=1></col><col width=75></col>\n";
					echo "	<tr height=28 align=center>\n";
					echo "		<td width=100% nowrap align=left style=padding-left:10>\n";
					echo "		<span style='width:97%;overflow:hidden;text-overflow:ellipsis;'>\n";
					echo "		<font onmouseout=\"this.style.textDecorationUnderline=false; this.style.color='#595959';\" onmouseover=\"this.style.textDecorationUnderline=true; this.style.color='#FF0000';\" style=cursor:hand onclick=view(sub".$i.")>\n";
					echo $content[0];
					echo "		</font>\n";
					echo "		</span>\n";
					echo "		</td>\n";
					echo "		<td bgcolor=E7E7E7></td>\n";
					echo "		<td>".$row->name."</td>\n";
					echo "		<td bgcolor=E7E7E7></td>\n";
					if(strlen($content[1])>0) {
						echo "		<td style=\"color:#5C72E2\"><b>답변</b></td>\n";
					} else {
						echo "		<td style=\"color:red\"><b>미답변</b></td>\n";
					}
					echo "		<td bgcolor=E7E7E7></td>\n";
					echo "		<td>".$date."</td>\n";
					echo "	</tr>\n";
					echo "	<tr id=sub".$i." style=display:none>\n";
					echo "		<td colspan=7 valign=top>\n";
					echo "		<table width=100% border=0 cellspacing=0 cellpadding=0>\n";
					echo "		<tr><td height=1 colspan=3 bgcolor=E7E7E7></td></tr>\n";
					echo "		<tr valign=top style=padding:5,26>\n";
					echo "			<td colspan=2>".nl2br($content[0])."</td>\n";
					echo "			<td align=right></td>\n";
					echo "		</tr>\n";
					echo "		<tr><td height=1 colspan=3 bgcolor=E7E7E7></td></tr>\n";
					echo "		<form name=subform".$i." method=post>\n";
					echo "		<input type=hidden name=mode>\n";
					echo "		<input type=hidden name=productcode value=\"".$row->productcode."\">\n";
					echo "		<input type=hidden name=num value=\"".$row->num."\">\n";
					echo "		<tr valign=top>\n";
					echo "			<td width=16% rowspan=3 bgcolor=FEFCE2 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:5,10><B>답변</B></td>\n";
					echo "			<td colspan=2 style=padding:4,10>";
					if(strlen($content[1])>0) {
						echo nl2br($content[1]);
					} else {
						echo "<textarea name=\"memo\" rows=5 cols=\"\" style=width:100% class=txt maxbyte=1000 required></textarea>\n";
					}
					echo "			</td>\n";
					echo "		</tr>\n";
					if(strlen($content[1])==0) {
						echo "		<tr>\n";
						echo "			<td align=right colspan=2><img src=images/btn_confirm03.gif border=0 align=absmiddle style=cursor:hand onclick=formSubmit(document.subform".$i.")> &nbsp;</td>\n";
						echo "		</tr>\n";
					} else {
						echo "		<tr><td height=1 colspan=3></td></tr>\n";
						echo "		<tr><td height=1 colspan=3></td></tr>\n";
						echo "		<tr><td height=1 colspan=3 bgcolor=E7E7E7></td></tr>\n";
						echo "		<tr><td height=5 colspan=3></td></tr>\n";
						echo "		<tr>\n";
						echo "			<td align=right colspan=3><img src=images/btn_modify03.gif border=0 align=absmiddle style=cursor:hand onclick=view(modify".$i.")> &nbsp;</td>\n";
						echo "		</tr>\n";
					}
					echo "		</form>\n";
					echo "		<tr><td height=10 colspan=2></td></tr>\n";
					echo "		</table>\n";
					echo "		</td>\n";
					echo "	</tr>\n";
					echo "	<tr id=modify".$i." style=display:none>\n";
					echo "		<td colspan=7 valign=top>\n";
					echo "		<table width=100% border=0 cellspacing=0 cellpadding=0>\n";
					echo "		<tr><td height=1 colspan=3 bgcolor=E7E7E7></td></tr>\n";
					echo "		<tr valign=top style=padding:5,26>\n";
					echo "			<td colspan=2>".nl2br($content[0])."</td>\n";
					echo "			<td align=right></td>\n";
					echo "		</tr>\n";
					echo "		<tr><td height=1 colspan=3 bgcolor=E7E7E7></td></tr>\n";
					echo "		<form name=modform".$i." method=post>\n";
					echo "		<input type=hidden name=mode>\n";
					echo "		<input type=hidden name=productcode value=\"".$row->productcode."\">\n";
					echo "		<input type=hidden name=num value=\"".$row->num."\">\n";
					echo "		<tr valign=top>\n";
					echo "			<td width=16% rowspan=3 bgcolor=FEFCE2 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:5,10><B>답변</B></td>\n";
					echo "			<td colspan=2 style=padding:4,10>";
					echo "			<textarea name=\"memo\" rows=5 cols=\"\" style=width:100% class=txt maxbyte=1000 required>".$content[1]."</textarea>\n";
					echo "			</td>\n";
					echo "		</tr>\n";
					echo "		<tr>\n";
					echo "			<td align=right colspan=2><img src=images/btn_confirm03.gif border=0 align=absmiddle style=cursor:hand onclick=formSubmit(document.modform".$i.")> &nbsp;</td>\n";
					echo "		</tr>\n";
					echo "		</form>\n";
					echo "		<tr><td height=10 colspan=2></td></tr>\n";
					echo "		</table>\n";
					echo "		</td>\n";
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "	</td>\n";
					echo "</tr>\n";
					$i++;
				}
				pmysql_free_result($result);
				if($i==0) {
					echo "<tr height=28 bgcolor=#FFFFFF><td colspan=5 align=center>조회된 내용이 없습니다.</td></tr>\n";
				} else if($i>0) {
					$pageing=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page;
				}
?>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td align=center style="padding-top:10"><?=$pageing?></td>
				</tr>
				</table>

				</td>
			</tr>
			<!-- 처리할 본문 위치 끝 -->

			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>

<form name=pageForm method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=code value="<?=$code?>">
<input type=hidden name=search_start value="<?=$search_start?>">
<input type=hidden name=search_end value="<?=$search_end?>">
<input type=hidden name=s_check value="<?=$s_check?>">
<input type=hidden name=search value="<?=$search?>">
<input type=hidden name=block value="<?=$block?>">
<input type=hidden name=gotopage value="<?=$gotopage?>">
</form>

</table>

<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

<?=$onload?>

<?php include("copyright.php"); ?>

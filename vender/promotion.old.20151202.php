<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include("access.php");
//include_once($Dir."lib/paging.php");
//include("calendar.php");


$display_type = $_POST['display_type'];
$keyword = $_POST['keyword'];
$where[]="vender='".$_VenderInfo->getVidx()."' ";
if($keyword){
	$where[]="lower(title) like lower('%".$keyword."%') ";
}
if($display_type != 'ALL' && $display_type!=""){
	$where[]="display_type = '".$display_type."' ";
}

if(count($where)>0){
$where=" where ".implode(' and ',$where);
}

$selected[skey][$_POST['skey']]='selected';

$imagepath = $cfg_img_path[timesale];


$sql = "select * from tblpromo ".$where." order by idx desc";
$res = pmysql_query($sql,get_db_conn());
$total = pmysql_num_rows($res);
$paging = new Paging((int)$total,10,20);
$gotopage = $paging->gotopage;
$sql = $paging->getSql($sql);
$res = pmysql_query($sql,get_db_conn());


include("header.php"); 
?>


<script src="../js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">

function event_pop(mode,idx){

	blockval=$('#block').val();
	gotoval=$('#gotopage').val();

	document.location.href="promotion_reg.php?mode="+mode+"&pidx="+idx;
	//window.open( "promotion_reg.php?mode="+mode+"&pidx="+idx, "기획전 관리", "" );
}

function event_ins(mode,idx,seq){
	if(mode=='del'){
		if(confirm('삭제하시겠습니까?')){
			document.location.href="promotion_reg.php?mode="+mode+"&pidx="+idx+"&seq="+seq;
			//window.open( "promotion_reg.php?mode="+mode+"&pidx="+idx+"&seq="+seq, "기획전 관리", "" );
		}
	}else{
		document.location.href="promotion_reg.php?mode="+mode+"&pidx="+idx;
		//window.open( "promotion_reg.php?mode="+mode+"&pidx="+idx, "기획전 관리", "" );
	}
}

function evnet_reg(idx){
	blockval=$('#block').val();
	gotoval=$('#gotopage').val();

	document.location.href="promotion_product.php?pidx="+idx;
	//window.open( "promotion_product.php?pidx="+idx, "기획전 관리", "" );
}
function GoPage(block,gotopage) {
	document.form1.block.value=block;
	document.form1.gotopage.value=gotopage;
	document.form1.submit();
}

</script>
<table cellpadding="0" cellspacing="0" width="100" style="table-layout:fixed">
<col width=175></col>
<col width=5></col>
<col width=740></col>
<col width=80></col>
<tr>
	<td width=175 valign=top nowrap><?php include("menu.php"); ?></td>
	<td width=5 nowrap></td>
	<td valign=top>
	<table width="100%" cellpadding="1" cellspacing="0" bgcolor="#D0D1D0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" style="border:3px solid #EEEEEE" bgcolor="#ffffff">
		<tr>
			<td>
				<form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method=post>
				<input type="hidden" id="type" name="type" />
				<input type="hidden" id="num" name="num" value="<?=$num?>" />
				<input type="hidden" id="htmlmode" name="htmlmode" value='wysiwyg' />
				<input type="hidden" id="block" name="block" value="<?=$_REQUEST['block']?>" />
				<input type="hidden" id="gotopage" name="gotopage" value="<?=$gotopage?>" />
				<input type="hidden" id="board" name="board" value=<?=$board?> />
				<tr>
					<td height="8">
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<tr>
								<td>
								<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
								<col width=165></col>
								<col width=></col>
								<tr>
									<td height=29 align=center background="../admin/images/tab_menubg.gif">
									<FONT COLOR="#ffffff"><B>기획전 관리</B></FONT>
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
										<td style="padding-bottom:5"><img src="images/icon_boxdot.gif" border=0 align=absmiddle> <B>기획전 관리</B></td>
									</tr>
									<!--
									<tr>
										<td style="padding-left:5;color:#7F7F7F"><img src="images/icon_dot02.gif" border=0> </td>
									</tr>
									-->
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
				

				<tr>
					<td>
					<!-- <div class="table_style02"> -->
						<TABLE cellSpacing=1 cellPadding=0 width="100%" border=0 bgcolor="E7E7E7" style='table_layout:fixed'>
						<col width=50><col width=200><col width=200><col width=200><col width=100>
						<!-- <col width=100> --><!-- <col width=100> --><col width=80><col width=80><col width=80>
						<tr height="32" align="center" bgcolor="F5F5F5">
							<td align="center" style="font-size:8pt"><b>No</b></td>
							<td align="center" style="font-size:8pt"><b>타이틀</b></td>
							<td align="center" style="font-size:8pt"><b>기간</b></td>
							<td align="center" style="font-size:8pt"><b>미리보기</b></td>
							<td align="center" style="font-size:8pt"><b>진열상태</b></td>
							<td align="center" style="font-size:8pt"><b>상품 등록</b></td>
							<td align="center" style="font-size:8pt"><b>수정</b></td>
							<td align="center" style="font-size:8pt"><b>삭제</b></td>
						</tr>
					<?
					while($row=pmysql_fetch_object($res)) {
						$cnt++;
					?>
						<TR height="28" bgcolor="#FFFFFF">
							<TD style='text-align: center;' ><?=$cnt?></TD>
							<TD style="text-align:left;"><?=$row->title?></TD>
							<TD style='text-align: center;'><?=$row->start_date?>&nbsp;~&nbsp;<?=$row->end_date?></TD>
							<TD style='text-align: center;'>
							<?
								switch($row->display_type){
									case 'S' : echo "/front/promotions.php?pidx=".$row->idx; break;
									case 'M' : echo "/m/promotions.php?pidx=".$row->idx; break;
									case 'D' : echo "/m/promotions.php?pidx=".$row->idx; break;
									case 'B' : echo "/fitflop_m/promotions.php?pidx=".$row->idx; break;
									case 'C' : echo "/fitflop_m/promotions.php?pidx=".$row->idx; break;
									default :?><a target="_balnk" href="/front/promotion.php?pidx=<?=$row->idx?>"> <?echo "/front/promotion.php?pidx=".$row->idx;?></a> <?break;
								}
							?>
							</TD>
							<td style='text-align: center;'><?
								switch($row->display_type){
									case 'A' : echo "ALL"; break;
									case 'P' : echo "PC"; break;
									case 'M' : echo "모바일"; break;
									case 'N' : echo "보류"; break;
									case 'S' : echo "PC 비전시"; break;
									case 'D' : echo "모바일 비전시"; break;
									case 'B' : echo "fitflop 모바일만"; break;
									case 'C' : echo "fitflop 모바일 비전시"; break;
								}
							?></td>
							<!-- <TD><?=$row->no_coupon?></TD> -->
							<!-- <TD><?=$row->rdate?></TD> -->
							<TD style='text-align: center;'><a href="javascript:evnet_reg(<?=$row->idx?>);"><img src="<?=$Dir."admin/"?>images/btn_add2.gif" border="0"></a></TD>
							<TD style='text-align: center;'><a href="javascript:event_pop('mod','<?=$row->idx?>');"><img src="<?=$Dir."admin/"?>images/btn_edit.gif" border="0"></a></TD>
							<TD style='text-align: center;'><a href="javascript:event_ins('del','<?=$row->idx?>','<?=$row->display_seq?>');"><img src="<?=$Dir."admin/"?>images/btn_del.gif" border="0"></a></TD>
						</TR>
					<?
					}
					pmysql_free_result($res);
					if ($cnt==0) {
						echo "<TR height=\"28\" bgcolor=\"#FFFFFF\"><TD colspan=10 align=center>등록된 목록이 없습니다.</TD></TR>";
					}
			?>

					</TABLE>

					 <div class="list_search" style="width:100%;text-align:right;padding-top:20px">

						<select class="option" name="skey">
							<option value="title" <?=$selected['skey']['title']?>>타이틀</option>
						</select>
						<input type="text" class="bar" name="sword" value="<?=$_POST['sword']?>"/>
						<input type="image" src="../admin/images/btn_search_com.gif" style="vertical-align:middle">
					 </div>
					<!-- </div> -->
			<?

								echo "<div id=\"page_navi01\" style=\"height:'40px'\">";
								echo "<div class=\"page_navi\">";
								echo "<ul>";
								echo "	".$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page;
								echo "</ul>";
								echo "</div>";
								echo "</div>";
			?>

					</td>
				</tr>
				<tr>
					<td><div style="text-align:center;padding-bottom:40px;"><img src="../admin/images/btn_confirm_com.gif" onclick="javascript:event_pop('ins');"/></div></td>
				</tr>
				<tr><td height="50"></td></tr>
				</form>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
	
<script language="javascript">

</script>
<?=$onload?>
<?php
include("copyright.php");

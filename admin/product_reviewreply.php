<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
$layout = 'inc';
include("./header.php");
if(ord($_ShopInfo->getId())==0){
	echo "<script>alert('정상적인 경로로 접근하시기 바랍니다.');window.close();</script>";
	exit;
}

$productcode=$_POST["productcode"];
$date=$_POST["date"];

if (ord($productcode)==0 || ord($date)==0) {
	echo "<script>window.close();</script>";
	exit;
}

$mode=$_POST["mode"];
$mem_id=$_POST["mem_id"];
$subject=$_POST["subject"];
$content1=$_POST["content1"];
$content2=$_POST["content2"];
$option_code=$_POST["option_code"];
$best_type=$_POST["best_type"]?$_POST["best_type"]:"0";
$old_best_type=$_POST["old_best_type"]?$_POST["old_best_type"]:"0";
$comment_arr = array();
if ($mode=="up") {
	if(ord($content2)) $content = $content1."=".$content2;
	else $content = $content1;

	$sql = "UPDATE tblproductreview SET ";
	$sql.= "subject = '{$subject}' ";
	$sql.= ", content = '{$content}' ";
	$sql.= ", best_type = '{$best_type}' ";
	$sql.= ", option_code = '{$option_code}' ";
	$sql.= "WHERE productcode='{$productcode}' AND date='{$date}'";
	pmysql_query($sql,get_db_conn());

	$uploadFile = '';
	$uploadFile1 = '';
	$up_rfile = '';
	$up_rfile1 = '';
	$rv_type = 0;
	//이미지 첨부
	//pre($_FILES);exit;
	if($_FILES['upfile1']) {
		$dir = DIRECTORY_SEPARATOR.ImageDir.'review/'.date('Ym').'/';
		$handle = new upload($_FILES['upfile1']);
		if ($handle->uploaded) {
			//$handle->file_name_body_add = '_'.date('YmdHis');
			$handle->file_new_name_body = $productcode.'_'.date('YmdHis');
			$file_rs = $handle->process(DOC_ROOT.$dir);


			if ($handle->processed) {
				$path = $dir.$handle->file_dst_name;
				$uploadFile = $path;
				$rv_type = 1;
				$up_rfile = $handle->file_src_name;
				@unlink(DOC_ROOT.$_POST['old_upfile1']);
				$handle->clean();
				$file1 = ",upfile='{$uploadFile}'";
			} else {
			}
		}
	}
	if($_FILES['upfile2']) {
		$dir = DIRECTORY_SEPARATOR.ImageDir.'review/'.date('Ym').'/';
		$handle = new upload($_FILES['upfile2']);
		if ($handle->uploaded) {
			$handle->file_new_name_body = $productcode.'_'.date('YmdHis');
			$file_rs = $handle->process(DOC_ROOT.$dir);

			if ($handle->processed) {
				$path = $dir.$handle->file_dst_name;
				$uploadFile1 = $path;
				$rv_type = 1;
				$up_rfile1 = $handle->file_src_name;
				@unlink(DOC_ROOT.$_POST['old_upfile2']);
				if(!empty($up_rfile)){
					$up_rfile = $up_rfile."||".$up_rfile1;
				}
				$file2 = ",upfile2='{$uploadFile1}'";
				$handle->clean();
			} else {
			}
		}
	}
	if($rv_type == 1){
		$update_sql = "UPDATE tblproductreview SET up_rfile='{$up_rfile}' {$file1} {$file2}, type = '{$rv_type}' WHERE productcode='{$productcode}' AND date='{$date}'";
		pmysql_query($update_sql,get_db_conn());
	}

	include_once($Dir."conf/config.ap_point.php");			// 활동포인트 지급 정보
	$ap_best_cnt		= $pointSet['best']['count']; 
	$ap_best_point	= $pointSet['best']['point']; 
	if ($old_best_type == '0' && $best_type=='1') { // 선정시

		$ap_inday = date("Ymd");

		// 오늘 로그인시 적립받은 갯수를 체크한다.
		list($b_cnt)=pmysql_fetch_array(pmysql_query("select COUNT(*) b_cnt from tblpoint_act WHERE rel_flag='@bestreview_in_point' and regdt >= '{$ap_inday}000000' AND regdt <= '{$ap_inday}999999' AND mem_id = '".$mem_id."' "));
		if ($b_cnt < $ap_best_cnt) { // 적립시 적립받은 갯수가 설정수보다 작으면
			insert_point_act($mem_id, $ap_best_point, '베스트 리뷰선정 포인트', '@bestreview_in_point', 'admin_'.date("YmdHis"), $date."-".date("YmdHis"), 0);
		}
	}
	
	if ($old_best_type == '1' && $best_type=='0') {// 선정 취소시
		insert_point_act($mem_id, $ap_best_point * -1, '베스트 리뷰선정 취소 포인트 환원', '@bestreview_out_point', 'admin_'.date("YmdHis"), $date."-".date("YmdHis"), 0);
	}

	echo "<script> alert ('해당 상품리뷰 정보가 저장되었습니다.');window.opener.location.reload();self.close();</script>\n";
	exit;
}

$sql = "SELECT * FROM tblproductreview WHERE productcode = '{$productcode}' AND date = '{$date}' ";
$result = pmysql_query($sql,get_db_conn());
if ($row = pmysql_fetch_object($result)) {
	$reviewcontent = explode("=",$row->content);
	$comment_sql = "SELECT no, id, name, content, regdt FROM tblproductreview_comment ";
	$comment_sql.= "WHERE pnum ='".$row->num."' ";
	$comment_sql.= "ORDER BY regdt DESC, no DESC ";
	$comment_res = pmysql_query( $comment_sql, get_db_conn() );
	while( $comment_row = pmysql_fetch_object( $comment_res ) ){
		$comment_arr[] = $comment_row;
	}
	$Product = NEW PRODUCT;
	$row->option = $Product->getProductOption($productcode);
} else {
	echo "<script>window.close();</script>";
	exit;
}


// 업로드 이미지 정보
$arrUpFile = array();

if ( !empty($row->upfile) ) { array_push($arrUpFile, $row->upfile); }
if ( !empty($row->upfile2) ) { array_push($arrUpFile, $row->upfile2); }
if ( !empty($row->upfile3) ) { array_push($arrUpFile, $row->upfile3); }
if ( !empty($row->upfile4) ) { array_push($arrUpFile, $row->upfile4); }

?>

<html>
<head>
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
	var oWidth = 600;
	if (document.all.table_body.clientHeight > 600)
	{
		var oHeight = 600;
	} else {
		var oHeight = document.all.table_body.clientHeight + 120;
	}

	window.resizeTo(oWidth,oHeight);
}

function CheckForm() {
	if (confirm("해당 상품리뷰를 현재 정보로 저장 하시겠습니까?")) {
		document.form1.submit();
	}
}

//-->
</SCRIPT>
<script type="text/javascript">
	function readURL(input) {
		//console.log($(input).siblings());
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$(input).siblings().attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	function allowDrop(ev) {
		ev.preventdefault();
	}

	function drop(ev) {
		ev.preventdefault();
	}
</script>
</head>

<title>상품리뷰 수정/답변</title>
<div class="pop_top_title"><p>상품리뷰 수정<!--/답변--></p></div>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 style="overflow-x:hidden;" onLoad="PageResize();">
<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed;" id="table_body">
<TR>
	<TD background="images/member_zipsearch_bg.gif">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="18"></td>
		<td></td>
		<td width="18" height=10></td>
	</tr>
	<tr>
		<td width="18">&nbsp;</td>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="100%">
            <div class="table_style01">
			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
			<form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
			<input type=hidden name=mode value="up">
			<input type=hidden name=productcode value="<?=$productcode?>">
			<input type=hidden name=date value="<?=$date?>">
			<input type=hidden name=old_best_type value="<?=$row->best_type?>">
			<input type=hidden name=mem_id value="<?=$row->id?>">
			<TR>
				<th><span>이름</span></th>
				<TD class="td_con1"><B><?=$row->name?></B></TD>
			</TR>
			<TR>
				<th><span>평점</span></th>
				<TD class="td_con1"><SPAN class="font_orange"><B>
				<?php
				for($i=1;$i<=$row->marks;$i++) {
					echo "★";
				}
				?></B></SPAN></TD>
			</TR>
			<tr>
				<th><span>베스트</span></th>
				<TD class="td_con1">
					<input type="checkbox" value="1" name="best_type" <?if($row->best_type){echo "checked";}?>>선택					
				</td>
			</tr>
			<tr>
				<th><span>상품 옵션</span></th>
				<TD class="td_con1">
					<select name="option_code" style="height:32px;vertical-align:middle;" class="select ">
						<? foreach ($row->option AS $k => $v){?>
							<option value="<?= $v['option_num']?>" <? if($row->option_code == $v['option_num']) echo "selected";?> ><?= $v['option_name']?></option>
						<? } ?>
					</select>
				</td>
			</tr>
			<TR>
				<th><span>제목</span></th>
				<TD class="td_con1"><input name="subject" value="<?=$row->subject?>" style="width:90%;"></TD>
			</TR>
			<TR>
				<th><span>내용</span></th>
				<TD class="td_con1"><textarea name="content1" style="width:100%;height:120;word-break:break-all;" class="textarea"><?=$reviewcontent[0]?></textarea></TD>
			</TR>
			<TR style="display:none;">
				<th><span>답변</span></th>
				<TD class="td_con1"><textarea name="content2" style="width:100%;height:120;word-break:break-all;" class="textarea"><?=$reviewcontent[1]?></textarea></TD>
			</TR>
			<TR>
				<th><span>첨부파일1</span></th>
				<TD class="td_con1">
					<div id="file_area" ondrop="drop(ev);" ondragover="allowDrop(ev);">
						<img id="blah" src="<?=$row->upfile?>" alt="your image" onError="this.src='/admin/images/no_img.gif'" height="80px">
						<br>
						<input type='file' name="upfile1" id="img_file" onchange="readURL(this);" />
					</div>
			</td>
			</TR>
				<TR>
					<th><span>첨부파일2</span></th>
					<TD class="td_con1">
						<div id="file_area1" ondrop="drop(ev);" ondragover="allowDrop(ev);">
							<img id="blah1" src="<?=$row->upfile2?>" alt="your image" onError="this.src='/admin/images/no_img.gif'" height="80px">
							<br>
							<input type='file' name="upfile2" id="img_file1" onchange="readURL(this);" />
						</div>
					</td>
				</TR>
<?php
if( count( $comment_arr ) > 0 ){
?>
			<TR>
				<th><span>댓글</span></th>
				<td>
					<div>
						<table>
							<tr>
								<th style='width: 120px !important;'>ID</th>
								<th width='' >내용</th>
							</tr>
<?php
	foreach( $comment_arr as $commentKey=>$commentVal ) {
?>
							<tr>
								<td style='width: 120px !important;' ><?=$commentVal->id?></td>
								<td width='' ><?=$commentVal->content?></td>
							</tr>
<?php
	}
?>
						</table>
					</div>
				</td>
			</TR>
<?php
}
?>
			</TABLE>
			</td>
		</tr>
		</table>
		</td>
		<td width="18">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" height="10"></td>
	</tr>
	<tr>
		<td width="18">&nbsp;</td>
		<td align="center"><a href="javascript:CheckForm();"><img src="images/btn_save.gif" border="0" vspace="5" border=0></a>&nbsp;&nbsp;<a href="javascript:window.close();"><img src="images/btn_close.gif"  border="0" vspace="5" border=0 hspace="2"></a></td>
		<td width="18">&nbsp;</td>
	</tr>
	
	</form>
	</table>
	</TD>
</TR>
</TABLE>
</body>
</html>


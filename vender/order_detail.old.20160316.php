<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");

$isaccesspass=true;
include("access.php");

$ordercode=$_POST["ordercode"];

if($ordercode==NULL) {
	echo "<script>alert('잘못된 접근입니다.');window.close();</script>";
	exit;
}

$sql = "SELECT a.*,  b.sumprice, b.sumreserve, b.sumdeliprice, b.sumdcprice ";
$sql.= "FROM tblorderinfo a, (select ordercode, SUM((price+option_price)*option_quantity) as sumprice, SUM(coupon_price+use_point) as sumdcprice, SUM(reserve) as sumreserve, SUM(deli_price) as sumdeliprice from tblorderproduct where vender='".$_VenderInfo->getVidx()."' GROUP BY ordercode) b WHERE a.ordercode='".$ordercode."' AND a.ordercode=b.ordercode ";
$sql.= " ";
//echo $sql;
//exit;
$result=pmysql_query($sql,get_db_conn());
$_ord=pmysql_fetch_object($result);
pmysql_free_result($result);
if(!$_ord) {
	echo "<script>alert(\"해당 주문내역이 존재하지 않습니다.\");window.close();</script>";
	exit;
}

$mode=$_POST["mode"];
$prcodes=$_POST["prcodes"];
$deli_gbn=$_POST["deli_gbn"];
if($mode=="deligbnup" && strlen($prcodes)>0 && strstr("NSY",$deli_gbn) && strstr("NXS",$_ord->deli_gbn)) {	//처리상태 변경
	$prcodes=rtrim($prcodes,',');
	$prlist=str_replace(',','\',\'',$prcodes);
	$sql = "UPDATE tblorderproduct SET deli_gbn='".$deli_gbn."', ";
	if($deli_gbn=="Y") $sql.= "deli_date='".date("YmdHis")."' ";
	else $sql.= "deli_date=NULL ";
	$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
	$sql.= "AND ordercode='".$ordercode."' AND productcode IN ('".$prlist."') ";
	$sql.= "AND NOT (productcode LIKE '999999%') ";
	if(pmysql_query($sql,get_db_conn())) {
		if($_ord->deli_gbn!=$deli_gbn) {
			$rescode=getDeligbn_detail($ordercode,$deli_gbn);
			if(strlen($rescode)>0) {
				$_ord->deli_gbn=$rescode;
			}
		}
		
		$upinfo	= "N";

		if ($deli_gbn == "N") {// 미처리 변경시
			list($cntprcodes)=pmysql_fetch("SELECT count(*) from tblorderproduct WHERE ordercode = '{$ordercode}' AND deli_gbn != '".$deli_gbn."' ");
			if ($cntprcodes == 0) {//상품모두 같은 처리상태일 경우
				$upinfo	= "Y";
				$qry	= " AND deli_gbn != 'Y' ";
			}
		} else if($deli_gbn=="S") {// 발송준비 변경시
			list($cntprcodes)=pmysql_fetch("SELECT count(*) from tblorderproduct WHERE ordercode = '{$ordercode}' AND deli_gbn = '".$deli_gbn."' ");
			if ($cntprcodes > 0) {//상품중 하나라도 있을경우
				$upinfo	= "Y";
				$qry	= " AND deli_gbn != 'Y' ";
			}
		} else if($deli_gbn=="Y") {// 배송중 변경시
			list($cntprcodes)=pmysql_fetch("SELECT count(*) from tblorderproduct WHERE ordercode = '{$ordercode}' AND deli_gbn = '".$deli_gbn."' ");
			if ($cntprcodes > 0) {//상품중 하나라도 있을경우
				$upinfo	= "Y";
				$qry	= "";
			}
		}

		if ($upinfo == "Y") {
			$sql = "UPDATE tblorderinfo SET deli_gbn='".$deli_gbn."', ";
			if($deli_gbn=="Y") $sql.= "deli_date='".date("YmdHis")."' ";
			else $sql.= "deli_date=NULL ";
			$sql.= "WHERE ordercode='".$ordercode."' AND deli_gbn != '".$deli_gbn."' {$qry} ";
			pmysql_query($sql); 
		}

		$sql = "SELECT a.*,  b.sumprice, b.sumreserve, b.sumdeliprice, b.sumdcprice ";
		$sql.= "FROM tblorderinfo a, (select ordercode, SUM((price+option_price)*option_quantity) as sumprice, SUM(coupon_price+use_point) as sumdcprice, SUM(reserve) as sumreserve, SUM(deli_price) as sumdeliprice from tblorderproduct where vender='".$_VenderInfo->getVidx()."' GROUP BY ordercode) b WHERE a.ordercode='".$ordercode."' AND a.ordercode=b.ordercode ";

		$result=pmysql_query($sql,get_db_conn());
		$_ord=pmysql_fetch_object($result);
		pmysql_free_result($result);

		$onload="<script>alert('요청하신 작업이 완료되었습니다.');</script>";
	} else {
		$onload="<script>alert('요청하신 작업중 오류가 발생하였습니다.');</script>";
	}
} else if($mode=="deliinfoup" && strlen($prcodes)>0) {	//배송정보 변경
	$deliinfo=rtrim($prcodes,'|');
	$ardeli=explode("|",$deliinfo);
	$qryErr = 0;
	for($i=0;$i<count($ardeli);$i++) {
		$idx=$deli_com=$deli_num="";
		$prinfo=explode(",",$ardeli[$i]);
		for($j=0;$j<count($prinfo);$j++) {
			if (strpos($prinfo[$j],"IDXS=")===0) $idx=substr($prinfo[$j],5);
			else if (strpos($prinfo[$j],"DELI_COM=")===0) $deli_com=substr($prinfo[$j],9);
			else if (strpos($prinfo[$j],"DELI_NUM=")===0) $deli_num=substr($prinfo[$j],9);
		}
		
		if ($deli_com != '' && $deli_num !='') {
			$sql = "UPDATE tblorderproduct SET deli_com='{$deli_com}', deli_num='{$deli_num}', deli_gbn = 'Y', deli_date='".date("YmdHis")."' ";
			$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
			$sql.= "AND ordercode='{$ordercode}' AND idx='{$idx}' ";
			$sql.= "AND op_step < 41 ";
			pmysql_query($sql,get_db_conn());
			if( !pmysql_error() ){
				// 신규상태 변경 추가 - (2016.02.12 - 김재수 추가)
				orderProductStepUpdate($exe_id, $ordercode, $idx, '3'); // 배송중
			} else {
				$qryErr++;
			}
		} else {
			$qryErr++;
		}
	}
	if( $qryErr == 0 ){
		$sql = "UPDATE tblorderinfo SET deli_gbn = 'Y', deli_date='".date("YmdHis")."' ";
		$sql.= "WHERE ordercode='{$ordercode}' ";
		//echo $sql;
		pmysql_query($sql,get_db_conn());
		
		// 신규상태 변경 추가
		orderStepUpdate($exe_id, $ordercode, '3', '0' ); // 배송중
	}

	$onload="<script>alert('요청하신 작업이 완료되었습니다.');</script>";
} else if($mode=="returninfoup" && strlen($prcodes)>0 && $_ord->deli_gbn=="Y") {	//반송처리
	$prcodes=rtrim($prcodes,',');
	$prlist=str_replace(',','\',\'',$prcodes);
	$sql = "UPDATE tblorderproduct SET deli_gbn='R' ";
	$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
	$sql.= "AND ordercode='".$ordercode."' AND productcode IN ('".$prlist."') ";
	$sql.= "AND NOT (productcode LIKE '999999%') ";
	if(pmysql_query($sql,get_db_conn())) {
		list($cntprcodes)=pmysql_fetch("SELECT count(*) from tblorderproduct WHERE ordercode='{$ordercode}' AND deli_gbn != 'R' ");
		if ($cntprcodes == 0) {//상품모두 같은 처리상태일 경우
			$sql = "UPDATE tblorderinfo SET deli_gbn='".$deli_gbn."' ";
			$sql.= "WHERE ordercode='".$ordercode."' AND deli_gbn != 'R' ";
			pmysql_query($sql); 
		}
		$onload="<script>alert('요청하신 작업이 완료되었습니다.');</script>";
	} else {
		$onload="<script>alert('요청하신 작업중 오류가 발생하였습니다.');</script>";
	}
}

$arpm=array("B"=>"무통장","V"=>"계좌이체","O"=>"가상계좌","Q"=>"가상계좌(매매보호)","C"=>"신용카드",/*"P"=>"신용카드(매매보호)",*/"M"=>"핸드폰");

$pmethod="";
$presult="";
$prescd="N";
if(strstr("B", $_ord->paymethod[0])) {	//무통장
	$pmethod="무통장";
	if (strlen($_ord->bank_date)==9 && $_ord->bank_date[8]=="X") $presult="<font color=005000> 환불</font>";
	else if (strlen($_ord->bank_date)>0) {
		$presult="<font color=004000>입금완료</font>";
		$prescd="Y";
	} else {
		$presult="입금대기";
	}
} else if(strstr("V", $_ord->paymethod[0])) {	//계좌이체
	$pmethod="계좌이체";
	if (strcmp($_ord->pay_flag,"0000")!=0) $presult="<font color=#757575>결제실패</font>";
	else if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="C") $presult="<font color=005000>환불</font>";
	else if ($_ord->pay_flag=="0000") {
		$presult="<font color=0000a0>결제완료</font>";
		$prescd="Y";
	}
} else if(strstr("M", $_ord->paymethod[0])) {	//핸드폰
	$pmethod="핸드폰";
	if (strcmp($_ord->pay_flag,"0000")!=0) $presult="<font color=#757575>결제실패</font>";
	else if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="C") $presult="<font color=005000> 취소완료</font>";
	else if ($_ord->pay_flag=="0000") {
		$presult="<font color=0000a0>결제완료</font>";
		$prescd="Y";
	}
} else if(strstr("OQ", $_ord->paymethod[0])) {	//가상계좌
	$pmethod="가상계좌";
	if (strcmp($_ord->pay_flag,"0000")!=0) $presult="<font color=#757575>주문실패</font>";
	else if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="C") $presult="<font color=005000>환불</font>";
	else if ($_ord->pay_flag=="0000" && strlen($_ord->bank_date)==0) $presult="<font color=red>미입금</font>";
	else if ($_ord->pay_flag=="0000" && strlen($_ord->bank_date)>0) {
		$presult="<font color=0000a0>입금완료</font>";
		$prescd="Y";
	}
} else {
	$pmethod="신용카드";
	if (strcmp($_ord->pay_flag,"0000")!=0) $presult="<font color=#757575>카드실패</font>";
	else if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="N") $presult="<font color=red>카드승인</font>";
	else if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="Y") {
		$presult="<font color=0000a0>결제완료</font>";
		$prescd="Y";
	}
	else if ($_ord->pay_flag=="0000" && $_ord->pay_admin_proc=="C") $presult="<font color=005000>취소완료</font>";
}
?>
<html>
<head>
<title>관리자 페이지</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-KR">
<script type="text/javascript" src="lib.js.php"></script>
<link rel="stylesheet" href="style.css">
<script language=Javascript>
window.resizeTo(1000,700);

function ProductInfo(code,prcode,popup) {
	document.form_reg.code.value=code;
	document.form_reg.prcode.value=prcode;
	document.form_reg.popup.value=popup;
	if (popup=="YES") {
		document.form_reg.action="product_register.add.php";
		document.form_reg.target="register";
		window.open("about:blank","register","width=820,height=700,scrollbars=yes,status=no");
	} else {
		document.form_reg.action="product_register.php";
		document.form_reg.target="";
	}
	document.form_reg.submit();
}
function ProductMouseOver(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.primage"+cnt);
	obj._tid = setTimeout("ProductViewImage(WinObj)",200);
}
function ProductViewImage(WinObj) {
	WinObj.style.visibility = "visible";
}
function ProductMouseOut(Obj) {
	obj = event.srcElement;
	Obj = document.getElementById(Obj);
	Obj.style.visibility = "hidden";
	clearTimeout(obj._tid);
}

function DeliSearch(deli_url){
	window.open(deli_url,"배송추적","");
}

function MemoMouseOver(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.memo"+cnt);
	obj._tid = setTimeout("MemoView(WinObj)",200);
}
function MemoView(WinObj) {
	WinObj.style.visibility = "visible";
}
function MemoMouseOut(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.memo"+cnt);
	WinObj.style.visibility = "hidden";
	clearTimeout(obj._tid);
}

function CheckAll(){
   chkval=document.form2.allcheck.checked;
   cnt=document.form2.tot.value;
   for(i=1;i<=cnt;i++){
      document.form2.chkprcode[i].checked=chkval;
   }
}

<?if(strstr("NXS",$_ord->deli_gbn) && $_ord->pay_admin_proc!="C" && $prescd=="Y") {?>
function changeDeli(obj) {
	if(document.form2.tot.value==0) {
		alert("배송 상품이 존재하지 않습니다.");
		return;
	}
	deli_gbn=obj.value;
	document.form2.prcodes.value="";
	for(i=1;i<document.form2.chkprcode.length;i++) {
		if(document.form2.chkprcode[i].checked) {
			document.form2.prcodes.value+=document.form2.chkprcode[i].value+",";
		}
	}
	if(document.form2.prcodes.value.length==0) {
		alert("선택하신 상품이 없습니다.");
		obj.selectedIndex=0;
		return;
	}
	if(deli_gbn.length>0) {
		delistr="";
		if(deli_gbn=="N") delistr="[미처리]";
		else if(deli_gbn=="S") delistr="[발송준비]";
		else if(deli_gbn=="Y") delistr="[배송]";
		if(confirm("선택된 상품의 처리상태를 "+delistr+" 상태로 변경하시겠습니까?")) {
			document.form2.mode.value="deligbnup";
			document.form2.submit();
		} else {
			document.form2.prcodes.value="";
			obj.selectedIndex=0;
		}
	} else {
		document.form2.prcodes.value="";
		obj.selectedIndex=0;
	}
}
<?}?>

function GoPrdinfo(prcode,target) {
	document.form3.target="";
	document.form3.prcode.value=prcode;
	if(target.length>0) {
		document.form3.target=target;
	}
	document.form3.submit();
}

function changeDeliinfo() {
	if(document.form2.tot.value==0) {
		alert("배송 상품이 존재하지 않습니다.");
		return;
	}
	document.form2.prcodes.value="";
	for(i=1;i<document.form2.chkprcode.length;i++) {
		if(document.form2.chkprcode[i].checked) {
			document.form2.prcodes.value+="IDXS="+document.form2.idxs[i].value+",DELI_COM="+document.form2.deli_com[i].value+",DELI_NUM="+document.form2.deli_num[i].value+"|";
		}
	}
	if(document.form2.prcodes.value.length==0) {
		alert("선택하신 상품이 없습니다.");
		return;
	}
	if(confirm("선택된 상품의 배송업체/송장번호를 수정(등록)합니다.\n\n정말로 적용하시겠습니까?")) {
		document.form2.mode.value="deliinfoup";
		document.form2.submit();
	}
}

function changeReturninfo() {
	if(document.form2.tot.value==0) {
		alert("배송 상품이 존재하지 않습니다.");
		return;
	}
	document.form2.prcodes.value="";
	for(i=1;i<document.form2.chkprcode.length;i++) {
		if(document.form2.chkprcode[i].checked) {
			document.form2.prcodes.value+=document.form2.chkprcode[i].value+",";
		}
	}
	if(document.form2.prcodes.value.length==0) {
		alert("선택하신 상품이 없습니다.");
		return;
	}
	if(confirm("선택된 상품을 반송처리 상태로 변경 하시겠습니까?")) {
		document.form2.mode.value="returninfoup";
		document.form2.submit();
	}
}
</script>
</head>
<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0>
<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
<tr>
	<td style="padding:10px">
	<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
	<tr>
		<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>주문내역</B> <font style="font-size:8pt;color:#2A97A7">(해당 주문서의 결제내역입니다.)</font></td>
	</tr>
	<tr><td height=2></td></tr>
	<tr><td height=1 bgcolor=red></td></tr>
	<tr>
		<td>
		<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
		<col width=120></col>
		<col width=></col>
		<col width=80></col>
		<col width=85></col>
		<col width=80></col>
		<col width=80></col>
		<col width=80></col>
		<col width=80></col>
		<col width=95></col>
		<tr height=32 align=center bgcolor=#FEFCDA>
			<td>주문일자</td>
			<td>주문코드</td>
			<td>결제방법</td>
			<td>결제상태</td>
			<td>총 판매액</td>
			<td>총 할인액</td>
			<td>총 배송비</td>
			<td>총 합계</td>
			<td>처리상태</td>
		</tr>
		<tr height=32 bgcolor=#FFFFFF style="padding:4">
			<td align=center style="font-size:8pt;line-height:9pt"><?=substr($_ord->ordercode,0,4)."/".substr($_ord->ordercode,4,2)."/".substr($_ord->ordercode,6,2)." (".substr($_ord->ordercode,8,2).":".substr($_ord->ordercode,8,2).")"?></td>
			<td align=center style="font-size:8pt"><?=$_ord->ordercode?></td>
			<td align=center><?=$pmethod?></td>
			<td align=center><?=$presult?></td>
			<td align=right style="padding-right:5"><?=number_format($_ord->sumprice)?></td>
			<td align=right style="padding-right:5"><?=($_ord->sumdcprice>0?"-":"").number_format($_ord->sumdcprice)?></td>
			<td align=right style="padding-right:5"><?=($_ord->sumdeliprice>0?"+":"").number_format($_ord->sumdeliprice)?></td>
			<td align=right style="padding-right:5"><B><?=number_format($_ord->sumprice-$_ord->sumdcprice+$_ord->sumdeliprice)?></B></td>
			<td align=center>
<?
			/*switch($_ord->deli_gbn) {
				case 'S': echo "발송준비";  break;
				case 'X': echo "배송요청";  break;
				case 'Y': echo "배송";  break;
				case 'D': echo "<font color=blue>취소요청</font>";  break;
				case 'N': echo "미처리";  break;
				case 'E': echo "<font color=red>환불대기</font>";  break;
				case 'C': echo "<font color=red>주문취소</font>";  break;
				case 'R': echo "반송";  break;
				case 'H': echo "배송(<font color=red>정산보류</font>)";  break;
			}
			if($row2->deli_gbn=="D" && strlen($row2->deli_date)==14) echo " (배송)";*/
			echo $o_step[$_ord->oi_step1][$_ord->oi_step2];
?>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr><td height=20></td></tr>
	<tr>
		<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>주문상품 정보 처리</B> <font style="font-size:8pt;color:#2A97A7">(배송처리 전에 주문 상품의 옵션사항 및 수량을 잘 확인하셔서 배송하시기 바랍니다.)</font></td>
	</tr>
	<tr><td height=2></td></tr>
	<tr><td height=1 bgcolor=red></td></tr>

	<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=mode>
	<input type=hidden name=ordercode value="<?=$ordercode?>">
	<input type=hidden name=prcodes>

	<tr>
		<td>
		<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
		<col width=20></col>
		<col width=></col>
		<col width=160></col>
		<col width=35></col>
		<col width=62></col>
		<col width=65></col>
		<col width=35></col>
		<col width=86></col>
		<col width=155></col>
		<tr height=28 align=center bgcolor=F5F5F5>
			<input type=hidden name=chkprcode>
			<input type=hidden name=idxs>
			<input type=hidden name=deli_com>
			<input type=hidden name=deli_num>
			<td><input type=checkbox name=allcheck onclick="CheckAll()"></td>
			<td>상품명</td>
			<td>옵션</td>
			<td>수량</td>
			<td>가격</td>
			<td>처리상태</td>
			<td>메모</td>
			<td>배송업체</td>
			<td>송장번호</td>
		</tr>
<?
		$sql = "SELECT idx, productcode, vender, productname, addcode, selfcode, deli_com, deli_num, price, ";
		$sql.= "( price * option_quantity ) AS sum_def_price, ";
		$sql.= "( option_price * option_quantity ) AS sum_opt_price, ";
		$sql.= "( ( price + option_price ) * option_quantity ) AS sum_price, ";
		$sql.= "quantity AS sum_qnt, opt1_name, opt2_name, option_quantity, option_price, option_price_text, ";
		$sql.= "coupon_price, (reserve * option_quantity) AS sum_reserve, (use_point * option_quantity) AS sum_use_reserve, receive_ok, ";
		$sql.= "redelivery_type, redelivery_date, redelivery_reason, ";
		$sql.= "deli_gbn,deli_price, use_point, receive_ok, op_step, oc_no, opt1_change, opt2_change, text_opt_subject, text_opt_content, option_price_text, text_opt_subject_change, text_opt_content_change, option_price_text_change ";
		$sql.= "FROM tblorderproduct WHERE vender='".$_VenderInfo->getVidx()."' AND ordercode ='".$_ord->ordercode."' order by vender, idx";
		$result=pmysql_query($sql,get_db_conn());
		$cnt=0;
		$etcdata=array();
		while($row=pmysql_fetch_object($result)) {
			//if (substr($row->productcode,0,3)=="999" || substr($row->productcode,0,3)=="COU") {
				$etcdata[]=$row;
				//continue;
			//}
			
			echo "<tr bgcolor=#FFFFFF>\n";
			echo "	<td align=center><input type=checkbox name=chkprcode value=\"".$row->productcode."\"><input type=hidden name=idxs value=\"".$row->idx."\"></td>\n";
			echo "	<td style=\"padding:3px;font-size:8pt;line-height:10pt\">";

			if(file_exists($Dir.DataDir."shopimages/product/".$row->productcode."3.gif")) $file=$row->productcode."3.gif";
			else if(file_exists($Dir.DataDir."shopimages/product/".$row->productcode."3.jpg")) $file=$row->productcode."3.jpg";
			else $file="NO";
			
			if($file!="NO") {
				echo "	".(strlen($row->selfcode)?"진열코드 : ".$row->selfcode."<br>":"")."<span onMouseOver='ProductMouseOver($cnt)' onMouseOut=\"ProductMouseOut('primage".$cnt."');\">".$row->productname."<a href=\"JavaScript:GoPrdinfo('".$row->productcode."','_blank')\"> <img src=images/newwindow.gif align=absmiddle border=0></a>";
				if(strlen($optvalue)>0) echo "<br><font color=red>옵션사항 : ".$optvalue."</font>";
				if(strlen($row->addcode)>0) echo "<br><font color=red>특수표시 : ".$row->addcode."</font>";
				echo "	</span>\n";
				echo "	<div id=primage".$cnt." style=\"position:absolute; z-index:100; visibility:hidden;\">\n";
				echo "	<table border=0 cellspacing=1 cellpadding=0 bgcolor=#000000 width=170>\n";
				echo "	<tr bgcolor=#FFFFFF>\n";
				echo "		<td align=center width=100% height=150><img name=bigimgs src=\"".$Dir.DataDir."shopimages/product/".$file."\"></td>\n";
				echo "	</tr>\n";
				echo "	<tr bgcolor=#FFFFFF>\n";
				echo "		<td height=54 bgcolor=#f5f5f5><table border=0><tr><td style=\"line-height:12pt\">예전 주문서,삭제/이동 상품은 이미지가 일치하지 않을수 있으니 <font color=red>주의하여 배송</font>바랍니다.</td></tr></table></td>\n";
				echo "	</tr>\n";
				echo "	</table>\n";
				echo "	</div>\n";
			} else {
				echo "".(strlen($row->selfcode)?"진열코드 : ".$row->selfcode."<br>":"").$row->productname."";
				if(strlen($optvalue)>0) echo "<br><font color=red>옵션사항 : ".$optvalue."</font>";
				if(strlen($row->addcode)>0) echo "<br><font color=red>특수표시 : ".$row->addcode."</font>";
				if($row->option_type == '1' ) echo "<br><font color=red> 추가옵션 </font>";
			}

			echo "	</td>\n";
			echo "	<td style=\"padding:3px;font-size:8pt;line-height:10pt\">";

			# 상품 옵션 정보 저장 및 출력

			$opt_name	= "";
			if( strlen( trim( $row->opt1_name ) ) > 0 ) {
				$opt1_name_arr	= explode("@#", $row->opt1_name);
				$opt2_name_arr	= explode(chr(30), $row->opt2_name);
				$s_cnt	= 0;
				for($s=0;$s < sizeof($opt1_name_arr);$s++) {
					if ($opt2_name_arr[$s]) {
						if ($s_cnt > 0) $opt_name	.= " / ";
						$opt_name	.= $opt1_name_arr[$s].' : '.$opt2_name_arr[$s];
						$s_cnt++;
					}
				}
				//echo "<br>".$opt_name;
			}
			
			if( strlen( trim( $row->text_opt_subject ) ) > 0 ) {
				$text_opt_subject_arr	= explode("@#", $row->text_opt_subject);
				$text_opt_content_arr	= explode("@#", $row->text_opt_content);

				for($s=0;$s < sizeof($text_opt_subject_arr);$s++) {
					if ($text_opt_content_arr[$s]) {
						if ($opt_name != '') $opt_name	.= " / ";
						$opt_name	.= $text_opt_subject_arr[$s].' : '.$text_opt_content_arr[$s];
					}
				}
			}
			
			if (($row->option_price * $row->option_quantity) > 0 ) $opt_name .= " (+".$row->option_price * $row->option_quantity."원)";

			if ($opt_name) echo $opt_name;

			$tmpOpPrice += $row->option_price * $row->option_quantity;

			echo "	</td>\n";
			echo "	<td align=center style=\"font-size:8pt\">".$row->option_quantity."</td>\n";
			echo "	<td align=right style=\"padding:3px;font-size:8pt\">".number_format( $row->sum_price)."</td>\n";
			echo "	<td align=center style=\"font-size:8pt\">";
			/*switch($row->deli_gbn) {
				case 'S': echo "발송준비";  break;
				case 'X': echo "배송요청";  break;
				case 'Y': echo "배송";  break;
				case 'D': echo "<font color=blue>취소요청</font>";  break;
				case 'N': echo "미처리";  break;
				case 'E': echo "<font color=red>환불대기</font>";  break;
				case 'C': echo "<font color=red>주문취소</font>";  break;
				case 'R': echo "<font color=red>반송</font>";  break;
				case 'H': echo "배송중(<font color=red>정산보류</font>)";  break;
			}
			if($row->deli_gbn=="D" && strlen($row->deli_date)==14) echo " (배송)";*/
			echo $op_step[$row->op_step];
			echo "	</td>\n";
			if(strlen($row->order_prmsg)>0) {
				echo "	<td align=center style=\"font-size:8pt;color:red\"><a style=\"cursor:hand;\" onMouseOver='MemoMouseOver($cnt)' onMouseOut=\"MemoMouseOut($cnt);\">메모</a>";
				echo "	<div id=memo".$cnt." style=\"left:160px;top:110px;position:absolute; z-index:100; visibility:hidden;\">\n";
				echo "	<table width=400 border=0 cellspacing=0 cellpadding=0 bgcolor=#A47917>\n";
				echo "	<tr>\n";
				echo "		<td style=\"padding:5px;line-height:12pt\"><font color=#FFFFFF>".nl2br(strip_tags($row->order_prmsg))."</td>\n";
				echo "	</tr>";
				echo "	</table>\n";
				echo "	</div>\n";
				echo "	</td>\n";
			} else {
				echo "	<td align=center style=\"font-size:8pt\">-</td>\n";
			}
			echo "	<td align=center>";
			echo "	<select name=deli_com style=\"width:80;font-size:8pt\">\n";
			echo "	<option value=\"\">없음</option>\n";
			$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
			$result2=pmysql_query($sql,get_db_conn());
			$deli_url="";
			$trans_num="";
			$company_name="";
			while($row2=pmysql_fetch_object($result2)) {
				echo "		<option value=\"".$row2->code."\"";
				if($row->deli_com>0 && $row->deli_com==$row2->code) {
					echo " selected";
					$deli_url=$row2->deli_url;
					$trans_num=$row2->trans_num;
					$company_name=$row2->company_name;
				}
				echo ">".$row2->company_name."</option>\n";
			}
			pmysql_free_result($result2);
			echo "	</select>\n";
			echo "	</td>\n";
			echo "	<td style=\"padding:3\">";
			echo "	<input type=text name=deli_num value=\"".$row->deli_num."\" size=20 maxlength=20 style=\"font-size:8pt\" onkeyup=\"strnumkeyup(this)\"><img width=2 height=0>";
			if(strlen($row->deli_num)>0 && strlen($deli_url)>0) {
				if(strlen($trans_num)>0) {
					$arrtransnum=explode(",",$trans_num);
					$pattern=array("[1]","[2]","[3]","[4]");
					$replace=array(substr($row->deli_num,0,$arrtransnum[0]),substr($row->deli_num,$arrtransnum[0],$arrtransnum[1]),substr($row->deli_num,$arrtransnum[0]+$arrtransnum[1],$arrtransnum[2]),substr($row->deli_num,$arrtransnum[0]+$arrtransnum[1]+$arrtransnum[2],$arrtransnum[3]));
					$deli_url=str_replace($pattern,$replace,$deli_url);
				} else {
					$deli_url.=$row->deli_num;
				}
				echo "<input type=button value='추적' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:18px;width:30\" onclick=\"DeliSearch('".$deli_url."')\">";
			} else {
				echo "<input type=button value='추적' style=\"cursor:hand;color:#FFFFFF;border-color:#666666;background-color:#666666;font-size:8pt;font-family:Tahoma;height:18px;width:30\">";
			}

			echo "	</td>\n";
			echo "</tr>\n";
			$cnt++;
		}
		pmysql_free_result($result);
?>
		<input type=hidden name=tot value="<?=$cnt?>">

		</table>
		</td>
	</tr>
	<tr><td height=5></td></tr>
	<tr>
		<td>
		<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
		<tr>
			<td style="padding-left:2">
			<? if($_ord->oi_step1 > 1 && $_ord->oi_step2 == '0') { ?><A HREF="javascript:changeDeliinfo()"><img src=images/btn_deliinfomodify.gif border=0 align=absmiddle></A><? } ?>
			</td>
		</tr>
		</table>
		</td>
	</tr>

	</form>

	<tr><td height=20></td></tr>
	<tr>
		<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>쿠폰/사용적립금/개별배송비 내역</B></td>
	</tr>
	<tr><td height=2></td></tr>
	<tr><td height=1 bgcolor=red></td></tr>
	<tr>
		<td>
		<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
		<col width=100></col>
		<col width=350></col>
		<col width=70></col>
		<col width=></col>
		<tr height=28 align=center bgcolor=F5F5F5>
			<td>항목</td>
			<td>내용</td>
			<td>금액</td>
			<td>해당 상품명</td>
		</tr>
<?

		if(count($etcdata)>0) {
			$cud_cnt	=0;
			for($i=0;$i<count($etcdata);$i++) {

				# 상품 옵션 정보 저장 및 출력

				$opt_name	= "";
				if( strlen( trim( $etcdata[$i]->opt1_name ) ) > 0 ) {
					$opt1_name_arr	= explode("@#", $etcdata[$i]->opt1_name);
					$opt2_name_arr	= explode(chr(30), $etcdata[$i]->opt2_name);
					$s_cnt	= 0;
					for($s=0;$s < sizeof($opt1_name_arr);$s++) {
						if ($opt2_name_arr[$s]) {
							if ($s_cnt > 0) $opt_name	.= " / ";
							$opt_name	.= $opt1_name_arr[$s].' : '.$opt2_name_arr[$s];
							$s_cnt++;
						}
					}
					//echo "<br>".$opt_name;
				}
				
				if( strlen( trim( $etcdata[$i]->text_opt_subject ) ) > 0 ) {
					$text_opt_subject_arr	= explode("@#", $etcdata[$i]->text_opt_subject);
					$text_opt_content_arr	= explode("@#", $etcdata[$i]->text_opt_content);

					for($s=0;$s < sizeof($text_opt_subject_arr);$s++) {
						if ($text_opt_content_arr[$s]) {
							if ($opt_name != '') $opt_name	.= " / ";
							$opt_name	.= $text_opt_subject_arr[$s].' : '.$text_opt_content_arr[$s];
						}
					}
				}
				
				if ($opt_name) $opt_name = "<br>".$opt_name;

				# 쿠폰
				$couponSql = "SELECT op.productname, op.opt1_name, op.opt2_name, op.text_opt_subject, op.text_opt_content, ci.coupon_name, co.dc_price, ci.coupon_type FROM tblcouponinfo ci ";
				$couponSql.= "JOIN tblcoupon_order co ON co.coupon_code = ci.coupon_code ";
				$couponSql.= "left join tblorderproduct op ON op.idx = co.op_idx ";
				$couponSql.= "WHERE co.ordercode = '".$ordercode."' AND co.op_idx='".$etcdata[$i]->idx."' order by op.vender, op.idx";

				//echo $couponSql;

				$couponRes =  pmysql_query( $couponSql, get_db_conn() );
				$couponTotal	= pmysql_num_rows($couponRes);

				if ($couponTotal > 0) {
					while( $couponRow = pmysql_fetch_object( $couponRes ) ) {				#쿠폰
						echo "<tr bgcolor=#FFFFFF>\n";
						echo "	<td align=center style=\"padding:7px 5px;font-size:8pt;line-height:10pt\">쿠폰</td>\n";
						echo "	<td style=\"padding:7px 5px;font-size:8pt;line-height:10pt\">".$couponRow->coupon_name."</td>\n";
						echo "	<td align=right style=\"padding:7px 5px;font-size:8pt;line-height:10pt\">".number_format($couponRow->dc_price)."</td>\n";
						echo "	<td style=\"padding:7px 5px;font-size:8pt;line-height:10pt\">".$etcdata[$i]->productname.$opt_name."</td>\n";
						echo "</tr>\n";
						$cud_cnt++;
					}
				}
				
				//var_dump ($etcdata[$i]);

				if($etcdata[$i]->use_point > 0) {				#사용적립금
					echo "<tr bgcolor=#FFFFFF>\n";
					echo "	<td align=center style=\"padding:7px 5px;font-size:8pt;line-height:10pt\">사용적립금</td>\n";
					echo "	<td style=\"padding:7px 5px;font-size:8pt;line-height:10pt\">-</td>\n";
					echo "	<td align=right style=\"padding:7px 5px;font-size:8pt;line-height:10pt\">".number_format($etcdata[$i]->use_point)."</td>\n";
					echo "	<td style=\"padding:7px 5px;font-size:8pt;line-height:10pt\">".$etcdata[$i]->productname.$opt_name."</td>\n";
					echo "</tr>\n";
					$cud_cnt++;
				}
				
				if($etcdata[$i]->deli_price > 0) {						#개별배송비
					echo "<tr bgcolor=#FFFFFF>\n";
					echo "	<td align=center style=\"padding:5px;font-size:8pt;line-height:10pt\">개별배송비</td>\n";
					echo "	<td style=\"padding:7px 5px;font-size:8pt;line-height:10pt\">-</td>\n";
					echo "	<td align=right style=\"padding:7px 5px;font-size:8pt;line-height:10pt\">".number_format($etcdata[$i]->deli_price)."</td>\n";
					echo "	<td style=\"padding:7px 5px;font-size:8pt;line-height:10pt\">".$etcdata[$i]->productname.$opt_name."</td>\n";
					echo "</tr>\n";
					$cud_cnt++;
				}
			}
		}

		if($cud_cnt == 0) {
			echo "<tr bgcolor=#FFFFFF><td colspan=4 align=center height=28>쿠폰/사용적립금/개별배송비 내역이 없습니다.</td></tr>";
		}
?>
		</table>
		</td>
	</tr>
	<tr><td height=20></td></tr>
	<tr>
		<td>
		<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
		<col width=></col>
		<col width=15></col>
		<col width=></col>
		<tr>
			<td valign=top>
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<tr>
				<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>주문자 정보</B></td>
			</tr>
			<tr><td height=2></td></tr>
			<tr><td height=1 bgcolor=red></td></tr>
			<tr>
				<td>
				<table border=0 cellpadding=0 cellspacing=1 bgcolor=E7E7E7 width=100% style="table-layout:fixed">
				<col width=80></col>
				<col width=></col>
				<tr>
					<td bgcolor=#F5F5F5 style="padding:5px 10px">성명(ID)</td>
					<td bgcolor=#FFFFFF style="padding:5px 10px;;font-size:8pt">
<?
					echo $_ord->sender_name;
					if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
						echo " (".$_ord->id.") ";
					} else {
						echo " (비회원)";
					}
?>
					</td>
				</tr>
				<tr>
					<td bgcolor=#F5F5F5 style="padding:5px 10px">연락처</td>
					<td bgcolor=#FFFFFF style="padding:5px 10px;;font-size:8pt"><?=$_ord->sender_tel?></td>
				</tr>
				<tr>
					<td bgcolor=#F5F5F5 style="padding:5px 10px">이메일</td>
					<td bgcolor=#FFFFFF style="padding:5px 10px;font-size:8pt"><?=$_ord->sender_email?></td>
				</tr>
				<tr>
					<td bgcolor=#F5F5F5 style="padding:5px 10px">요청사항</td>
					<td bgcolor=#FFFFFF style="padding:5px 10px;;font-size:8pt">
<?
					$message=explode("[MEMO]",$_ord->order_msg);
					$message[0]=str_replace("\"","&quot;",$message[0]);
					$message[0]=str_replace("\"","",$message[0]);
					$ordmsg=explode("\r\n",$message[0]);

					echo $ordmsg[0];
?>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			</td>

			<td>&nbsp;</td>

			<td valign=top>
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<tr>
				<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>수령인 정보</B></td>
			</tr>
			<tr><td height=2></td></tr>
			<tr><td height=2 bgcolor=red></td></tr>
			<tr>
				<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<tr>
					<td style="border-left-width:2px;border-bottom-width:2px;border-right-width:2px; border-left-color:red;border-bottom-color:red;border-right-color:red; border-left-style:solid;border-bottom-style:solid;border-right-style:solid;">
					<table border=0 cellpadding=0 cellspacing=1 bgcolor=E7E7E7 width=100% style="table-layout:fixed">
					<col width=80></col>
					<col width=></col>
					<tr>
						<td bgcolor=#F5F5F5 style="padding:5px 10px">성명</td>
						<td bgcolor=#FFFFFF style="padding:5px 10px;font-weight:bold"><?=$_ord->receiver_name?></td>
					</tr>
					<tr>
						<td bgcolor=#F5F5F5 style="padding:5px 10px">연락처</td>
						<td bgcolor=#FFFFFF style="padding:5px 10px;font-weight:bold">
						<?=$_ord->receiver_tel1.(strlen($_ord->receiver_tel2)>0?" , ".$_ord->receiver_tel2:"")?>
						</td>
					</tr>
					<tr height=56>
						<td bgcolor=#F5F5F5 style="padding:5px 10px">주소</td>
						<td bgcolor=#FFFFFF style="padding:5px 10px; line-height:12pt;font-weight:bold">
<?
						$address = str_replace("\n"," ",trim($_ord->receiver_addr));
						$address = str_replace("\r"," ",$address);
						$address = str_replace("↑=↑"," ",$address);
						$pos=strpos($address,"주소");
						if ($pos>0) {
							$post = trim(substr($address,0,$pos));
							$address = substr($address,$pos+7);
						}
						$post = str_replace("우편번호 : ","",$post);
						$arpost = explode("-",$post);

						echo "[".$arpost[0]."] ".$address;
?>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr><td colspan=3 height=20></td></tr>
		<tr>
			<td colspan=3 valign=top>
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<tr>
				<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>주문 메모</B> <font style="font-size:8pt;color:#2A97A7">(쇼핑몰 운영자가 남긴 주문 메모입니다.)</font></td>
			</tr>
			<tr><td height=2></td></tr>
			<tr><td height=1 bgcolor=red></td></tr>
			<tr>
				<td>
	
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
<?
$sqlMemo = "SELECT * FROM tblorder_memo WHERE ordercode = '".$_ord->ordercode."' order by om_no desc";
$resultMemo=pmysql_query($sqlMemo, get_db_conn());

while($rowMemo=pmysql_fetch_object($resultMemo)) {
	echo "		<tr>\n";
	echo "			<td style='padding:5px;font-size:11px;border-bottom:1px dotted #e3e3e3'>\n";
	echo "				<b>".$rowMemo->memo_id."</b> [".$rowMemo->regdt."]\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td style='padding:5px;font-size:11px;border-bottom:1px solid #b9b9b9'>\n";
	echo nl2br($rowMemo->memo);
	echo "			</td>\n";
	echo "		</tr>\n";
}
?>
				</table>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr style="display:none;"><td colspan=3 height=20></td></tr>
		<tr style="display:none;">
			<td colspan=3 valign=top>
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<tr>
				<td><img src=images/icon_dot03.gif border=0 align=absmiddle> <B>주문관련 메모</B> <font style="font-size:8pt;color:#2A97A7">(쇼핑몰 운영자 남긴 주문관련 메모입니다.)</font></td>
			</tr>
			<tr><td height=2></td></tr>
			<tr><td height=1 bgcolor=red></td></tr>
			<tr>
				<td>
				<textarea style="width:100%; height:50; font-size:8pt" readonly><?=$message[1]?></textarea>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr><td height=30></td></tr>
	<tr>
		<td align=center><A HREF="javascript:window.close()"><img src=images/btn_close03.gif border=0></A></td>
	</tr>
	<tr><td height=10></td></tr>
	</table>
	</td>
</tr>

<form name=form3 method=post action="product_prdmodify.php">
<input type=hidden name=prcode>
</form>

</table>

<?=$onload?>

</body>
</html>

<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include("access.php");

$paystate=$_POST["paystate"];
$deli_gbn=$_POST["deli_gbn"];
$s_check=$_POST["s_check"];
$search=$_POST["search"];

$CurrentTime = time();

$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (strtotime($search_end)-strtotime($search_start))/86400;
if ($termday>31) {
	echo "<script>alert('주문서 주소 다운로드 기간은 1달을 초과할 수 없습니다.');</script>";
	exit;
}

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=order_excel_".date("Ymd",$CurrentTime).".xls"); 
Header("Pragma: no-cache"); 
Header("Expires: 0");
Header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
Header("Content-Description: PHP4 Generated Data");

$arpm=array("B"=>"무통장","V"=>"계좌이체","O"=>"가상계좌","Q"=>"가상계좌(매매보호)","C"=>"신용카드","P"=>"신용카드(매매보호)","M"=>"핸드폰");

if(strlen($search)>0) {
	if($s_check=="cd") $qty= "AND a.ordercode='".$search."' ";
	else if($s_check=="pn") $b_qty= "AND productname LIKE '".$search."%' ";
	else if($s_check=="mn") $qty= "AND a.sender_name='".$search."' ";
	else if($s_check=="mi") $qty= "AND a.id='".$search."' ";
	else if($s_check=="cn") $qty= "AND a.id='".$search."X' ";
}

$sql = "SELECT a.ordercode,a.id,a.paymethod, ";
$sql.= "a.pay_data,a.bank_date,a.pay_flag,a.pay_admin_proc, ";
$sql.= "a.sender_name,a.sender_email,a.sender_tel,a.receiver_name,a.receiver_tel1,a.receiver_tel2, ";
$sql.= "a.receiver_addr,a.order_msg,a.del_gbn, ";
$sql.= "b.sumprice,b.sumreserve ";
$sql.= "FROM tblorderinfo a, (select ordercode, SUM((price+option_price)*option_quantity) as sumprice, SUM(reserve) as sumreserve, SUM(deli_price) as sumdeliprice from tblorderproduct where ";
$sql.= "vender='".$_VenderInfo->getVidx()."' ";
$sql.= $qty2;
$sql.= "AND NOT (productcode LIKE '999999%') ";
if(strlen($deli_gbn)>0)	$sql.= "AND deli_gbn='".$deli_gbn."' ";
$sql.= "GROUP BY ordercode) b ";
$sql.= "WHERE a.ordercode=b.ordercode ";

$sql.= $qty;

if($paystate=="Y") {		//입금
	$sql.= "AND ((SUBSTR(a.paymethod,1,1) IN ('B','V','O','Q') AND LENGTH(a.bank_date)=14) OR (SUBSTR(a.paymethod,1,1) IN ('C','P','M') AND a.pay_admin_proc!='C' AND a.pay_flag='0000')) ";
} else if($paystate=="B") {	//미입금
	$sql.= "AND ((SUBSTR(a.paymethod,1,1) IN ('B','V','O','Q') AND (a.bank_date IS NULL OR a.bank_date='')) OR (SUBSTR(a.paymethod,1,1) IN ('C','P','M') AND a.pay_flag!='0000' AND a.pay_admin_proc='C')) ";
} else if($paystate=="C") {	//환불
	$sql.= "AND ((SUBSTR(a.paymethod,1,1) IN ('B','V','O','Q') AND LENGTH(a.bank_date)=9) OR (SUBSTR(a.paymethod,1,1) IN ('C','P','M') AND a.pay_flag='0000' AND a.pay_admin_proc='C')) ";
}

if(substr($search_s,0,8)==substr($search_e,0,8)) {
	$sql.= "AND a.ordercode LIKE '".substr($search_s,0,8)."%' ";
} else {
	$sql.= "AND a.ordercode>='".$search_s."' AND a.ordercode <='".$search_e."' ";
}

$sql.= "ORDER BY a.ordercode DESC ";
//echo $sql;
$result = pmysql_query($sql,get_db_conn());
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
<table border="1">
    <tr align="center">
		<th>주문일</th>
		<th>ID/주문번호</th>
		<th>결제상태</th>
		<th>결제금액</th>
		<th>보내는사람</th>
		<th>E-mail</th>
		<th>전화번호</th>
		<th>받는사람</th>
		<th>전화번호</th>
		<th>비상전화번호</th>
		<th>우편번호</th>
		<th>주소</th>
    </tr>
<?
while($row=pmysql_fetch_object($result)) {
	if(substr($row->ordercode,20)=="X") {	//비회원
		$strid = substr($row->id,1,6);
	} else {	//회원
		$strid = $row->id;
	}
	$date = substr($row->ordercode,0,4)."-".substr($row->ordercode,4,2)."-".substr($row->ordercode,6,2)." ".substr($row->ordercode,8,2).":".substr($row->ordercode,10,2).":".substr($row->ordercode,12,2);
	//echo $arpm[$row->paymethod[0]];
	if(strstr("B", $row->paymethod[0])) {	//무통장
		if (strlen($row->bank_date)==9 && $row->bank_date[8]=="X") $pay="환불";
		else if (strlen($row->bank_date)>0) $pay="입금완료";
		else $pay="미입금";
	} else if(strstr("V", $row->paymethod[0])) {	//계좌이체
		if (strcmp($row->pay_flag,"0000")!=0) $pay="결제실패";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") $pay="환불";
		else if ($row->pay_flag=="0000") $pay="결제완료";
	} else if(strstr("M", $row->paymethod[0])) {	//핸드폰
		if (strcmp($row->pay_flag,"0000")!=0) $pay="결제실패";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") $pay="취소완료";
		else if ($row->pay_flag=="0000") $pay="결제완료";
	} else if(strstr("OQ", $row->paymethod[0])) {	//가상계좌
		if (strcmp($row->pay_flag,"0000")!=0) $pay="주문실패";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") $pay="환불";
		else if ($row->pay_flag=="0000" && strlen($row->bank_date)==0) $pay="미입금";
		else if ($row->pay_flag=="0000" && strlen($row->bank_date)>0) $pay="입금완료";
	} else {
		if (strcmp($row->pay_flag,"0000")!=0) $pay="카드실패";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="N") $pay="카드승인";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="Y") $pay="결제완료";
		else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") $pay="취소완료";
	}
	$price				= $row->sumprice;
	$sender_name	= $row->sender_name;
	$sender_email	= $row->sender_email;
	$sender_tel		= $row->sender_tel;
	$receiver_name	= $row->receiver_name;
	$receiver_tel1		= $row->receiver_tel1;
	$receiver_tel2		= $row->receiver_tel2;


	$row->receiver_addr=str_replace("\r\n","",$row->receiver_addr);
	$row->receiver_addr=str_replace("\n","",$row->receiver_addr);
	$row->receiver_addr = str_replace("↑=↑"," ",$row->receiver_addr);
	$row->receiver_addr = str_replace("우편번호 : "," ",$row->receiver_addr);

	$receiver_addr=explode("주소 : ",$row->receiver_addr);
	$post2 = $receiver_addr[0];
	$addr = $receiver_addr[1]; 
?>
    <tr>	
		<td align="center"><?=$date?></td>
		<td><?=$strid?></td>
		<td><?=$pay?></td>
		<td><?=$price?></td>
		<td><?=$sender_name?></td>
		<td><?=$sender_email?></td>
		<td><?=$sender_tel?></td>
		<td><?=$receiver_name?></td>
		<td><?=$receiver_tel1?></td>
		<td><?=$receiver_tel2?></td>
		<td><?=$post2?></td>
		<td><?=$addr?></td>
    </tr>

<?
}
?>
</table>
</body>
</html>
<?
pmysql_free_result($result);
?>
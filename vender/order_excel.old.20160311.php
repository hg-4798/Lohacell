<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include("access.php");

$ordercodes=rtrim($_POST["ordercodes"],',');
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
	echo "<script>alert('주문서 EXCEL 다운로드 기간은 1달을 초과할 수 없습니다.');</script>";
	exit;
}

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=order_excel_".date("Ymd",$CurrentTime).".xls"); 
Header("Pragma: no-cache"); 
Header("Expires: 0");
Header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
Header("Content-Description: PHP4 Generated Data");

if(strlen($ordercodes)>0) $ordercodes="'".str_replace(",","','",$ordercodes)."'";

$sql = "SELECT * FROM tblorderoption ";
if(strlen($ordercodes)>0) 
	$sql.= "WHERE ordercode IN (".$ordercodes.") ";
else
	$sql.= "WHERE ordercode >= '".$search_s."' AND ordercode <= '".$search_e."' ";
$result = pmysql_query($sql,get_db_conn());
while($row = pmysql_fetch_object($result)) {
	$optionkey=$row->ordercode.$row->productcode.$row->opt_idx;
	$addoption[$optionkey]=$row->opt_name;
}
pmysql_free_result($result);

$sql = "SELECT a.ordercode,a.id,a.paymethod, ";
$sql.= "a.pay_data,a.bank_date,a.pay_flag,a.pay_admin_proc, ";
$sql.= "a.sender_name,a.sender_email,a.sender_tel,a.receiver_name,a.receiver_tel1,a.receiver_tel2, ";
$sql.= "a.receiver_addr,a.order_msg,a.del_gbn, b.deli_gbn as prdt_deli_gbn,b.deli_date as prdt_deli_date, ";
$sql.= "b.deli_num,b.productcode,b.productname,b.opt1_name,b.opt2_name, ";
$sql.= "b.addcode,b.quantity,b.price,b.option_price,b.option_quantity,b.reserve,b.date,b.order_prmsg ";
$sql.= "FROM tblorderinfo a, tblorderproduct b ";
$sql.= "WHERE a.ordercode=b.ordercode ";

if(strlen($ordercodes)>0)
	$sql.= "AND a.ordercode IN (".$ordercodes.") ";
else
	$sql.= "AND a.ordercode >= '".$search_s."' AND a.ordercode <= '".$search_e."' ";

$sql.= "AND b.vender='".$_VenderInfo->getVidx()."' ";
$sql.= "AND NOT (b.productcode LIKE '999999%') ";
if (strlen($deli_gbn)>0) $sql.= "AND b.deli_gbn = '".$deli_gbn."' ";

if($paystate=="Y") {		//입금
	$sql.= "AND ((SUBSTR(a.paymethod,1,1) IN ('B','V','O','Q') AND LENGTH(a.bank_date)=14) OR (SUBSTR(a.paymethod,1,1) IN ('C','P','M') AND a.pay_admin_proc!='C' AND a.pay_flag='0000')) ";
} else if($paystate=="B") {	//미입금
	$sql.= "AND ((SUBSTR(a.paymethod,1,1) IN ('B','V','O','Q') AND (a.bank_date IS NULL OR a.bank_date='')) OR (SUBSTR(a.paymethod,1,1) IN ('C','P','M') AND a.pay_flag!='0000' AND a.pay_admin_proc='C')) ";
} else if($paystate=="C") {	//환불
	$sql.= "AND ((SUBSTR(a.paymethod,1,1) IN ('B','V','O','Q') AND LENGTH(a.bank_date)=9) OR (SUBSTR(a.paymethod,1,1) IN ('C','P','M') AND a.pay_flag='0000' AND a.pay_admin_proc='C')) ";
}
if(strlen($search)>0) {
	if($s_check=="cd") $sql.= "AND a.ordercode='".$search."' ";
	else if($s_check=="pn") $sql.= "AND b.productname LIKE '".$search."%' ";
	else if($s_check=="mn") $sql.= "AND a.sender_name='".$search."' ";
	else if($s_check=="mi") $sql.= "AND a.id='".$search."' ";
	else if($s_check=="cn") $sql.= "AND a.id='".$search."X' ";
}
$sql.= "ORDER BY a.ordercode DESC ";
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
		<th>일자</th>
		<th>주문자</th>
		<th>주문자 전화(XX-XXXX-XXXX)</th>
		<th>이메일</th>
		<th>주문ID/주문번호</th>
		<th>결제상태</th>
		<th>받는사람</th>
		<th>전화번호(XX-XXXX-XXXX)</th>
		<th>비상전화(XX-XXXX-XXXX)</th>
		<th>우편번호(XXX-XXX)</th>
		<th>주소</th>
		<th>전달사항</th>
		<th>상품명</th>
		<th>옵션(특징포함)</th>
		<th>갯수</th>
		<th>상품가격</th>
		<th>입금일</th>
		<th>주문관련메모(관리자)</th>
		<th>고객알리미</th>
		<th>상품별 송장번호</th>
		<th>거래번호</th>
		<th>상품코드</th>
		<th>옵션</th>
		<th>특징</th>
		<th>상품별 처리여부</th>
		<th>상품별 주문메세지</th>
		<th>상품별 배송일</th>
    </tr>
<?

while ($row=pmysql_fetch_object($result)) {
	$ordercode=$row->ordercode;
	$temp=$row->ordercode;
	$date = substr($row->ordercode, 0, 12);
	$date = substr($date,0,4)."/".substr($date,4,2)."/".substr($date,6,2);   //날짜 형식 수정  
	$orderdate = str_replace("/","-",$date)." (".substr($row->ordercode,8,2).":".substr($row->ordercode,10,2).":".substr($row->ordercode,12,2).")";
	$sender_name=$row->sender_name;
	$pay_data=$row->pay_data;
	$sender_email=$row->sender_email;

	if($row->ordercode[20]=="X") {	//비회원
		$idnum = substr($row->id,1,6);
	} else {	//회원
		$idnum = $row->id;
	}

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

	switch($row->prdt_deli_gbn) {
		case 'S': $prdt_deli_gbn="발송준비";  break;
		case 'X': $prdt_deli_gbn="배송요청";  break;
		case 'Y': $prdt_deli_gbn="배송";  break;
		case 'D': $prdt_deli_gbn="취소요청";  break;
		case 'N': $prdt_deli_gbn="미처리";  break;
		case 'E': $prdt_deli_gbn="환불대기";  break;
		case 'C': $prdt_deli_gbn="주문취소";  break;
		case 'R': $prdt_deli_gbn="반송";  break;
		case 'H': $prdt_deli_gbn="배송(정산보류)";  break;
	}
	$sender_telnum=check_num($row->sender_tel);
	$receiver_tel1num=check_num($row->receiver_tel1);
	$receiver_tel2num=check_num($row->receiver_tel2);
	$receiver_tel = $receiver_tel1num." ".$receiver_tel2num;
	$sender_tel = replace_tel($sender_telnum);
	$receiver_tel1 = replace_tel($receiver_tel1num);
	$receiver_tel2 = replace_tel($receiver_tel2num);
	$sender_telnum="=\"".$sender_telnum."\""; 
	$receiver_tel1num="=\"".$receiver_tel1num."\""; 
	$receiver_tel2num="=\"".$receiver_tel2num."\""; 
	$receiver_name=$row->receiver_name;
	$bank_date="=\"".($row->paymethod=="B"?$row->bank_date:substr($row->ordercode,0,14))."\"";

	$row->receiver_addr=str_replace("\r\n","",$row->receiver_addr);
	$row->receiver_addr=str_replace("\n","",$row->receiver_addr);
	$row->receiver_addr = str_replace("↑=↑"," ",$row->receiver_addr);
	$row->receiver_addr = str_replace("우편번호 : "," ",$row->receiver_addr);
	$receiver_addr=explode("주소 : ",$row->receiver_addr);
	$post2 = $receiver_addr[0];
	$addr = $receiver_addr[1]; 

	$mess=explode("[MEMO]",$row->order_msg);
	$message=str_replace($pattern,$replacement,strip_tags($mess[0]));
	$messnotag=str_replace($pattern,$replacement,$mess[0]);
	$adminmemo=str_replace($pattern,$replacement,$mess[1]);
	$usermemo=str_replace($pattern,$replacement,$mess[2]);
	$quantity=$row->option_quantity;
	$product1=str_replace(",","",$row->productname);
	$product=strip_tags($product1);
	$productcode="=\"".$row->productcode."\"";
	$price=$row->price + $row->option_price;
	$reserve=$row->reserve;
	$option=$option2=$addcode="";
	if (strlen($row->addcode)>0) $addcode=$row->addcode;
	$row->opt1_name	= str_replace("::"," : ",$row->opt1_name);
	$row->opt2_name	= str_replace("::"," : ",$row->opt2_name);
	if (strlen($row->opt1_name)>0) {
		if(strpos($row->opt1_name,"[OPTG")===0) {
			$key=$row->ordercode.$row->productcode.$row->opt1_name;
			$option.=$addoption[$key];
		} else {
			$option.=(strlen($option)==0?"":"-").$row->opt1_name;
		}
	}
	if (strlen($row->opt2_name)>0) $option.="<br>".(strlen($option)==0?"":"-").$row->opt2_name;
	$option2=$addcode.$option;
	$productname=$product."-".$quantity.(strlen($option2)==0?"":"-".$option2);
	$productname2=$productname;
	$deli_num=$row->deli_num;
	$prdt_message=str_replace($pattern,$replacement,strip_tags($row->order_prmsg));
	$prdt_deli_date="=\"".$row->prdt_deli_date."\"";
?>
    <tr>	
		<td align="center"><?=$date?></td>
		<td><?=$sender_name?></td>
		<td><?=$sender_tel?></td>
		<td><?=$sender_email?></td>
		<td><?=$idnum?></td>
		<td><?=$pay?></td>
		<td><?=$receiver_name?></td>
		<td><?=$receiver_tel1?></td>
		<td><?=$receiver_tel2?></td>
		<td><?=$post2?></td>
		<td><?=$addr?></td>
		<td><?=$message?></td>
		<td><?=$product?></td>
		<td><?=$option2?></td>
		<td><?=$quantity?></td>
		<td><?=$price?></td>
		<td><?=$bank_date?></td>
		<td><?=$adminmemo?></td>
		<td><?=$usermemo?></td>
		<td><?=$deli_num?></td>
		<td><?=$ordercode?></td>
		<td><?=$productcode?></td>
		<td><?=$option?></td>
		<td><?=$addcode?></td>
		<td><?=$prdt_deli_gbn?></td>
		<td><?=$prdt_message?></td>
		<td><?=$prdt_deli_date?></td>
    </tr>
<?
}
?>
</table>
</body>
</html>
<?
pmysql_free_result($result);

function doubleQuote($str) {
	return str_replace('"', '""', $str);
}
?>
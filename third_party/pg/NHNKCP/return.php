<?php

/**
 * 가상계좌 입금통보
 */

$Dir = "../../../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");



// 01. 공통 통보 페이지 설명(필독!!) KCP 관리자페이지(admin.kcp.co.kr)에 로그인 -> [쇼핑몰 관리] -> [정보변경] -> [공통 URL 정보] -> [공통 URL 변경 후]

$valid_ip = array('210.122.73.58','203.238.36.173','203.238.36.178');
if(!in_array($_SERVER['REMOTE_ADDR'], $valid_ip)) {
	exit;
}

$Order = new ORDER;

// 02. 공통 통보 데이터 받기
$site_cd	  = $_POST["site_cd"];				 // 사이트 코드
$tno		  = $_POST["tno"];				 // KCP 거래번호
$order_num	 = $_POST["order_no"];				 // 주문번호
$tx_cd		= $_POST["tx_cd"];				 // 업무처리 구분 코드
$tx_tm		= $_POST["tx_tm"];				 // 업무처리 완료 시간
/* = -------------------------------------------------------------------------- = */
$ipgm_name	= "";									// 주문자명
$remitter	 = "";									// 입금자명
$ipgm_mnyx	= "";									// 입금 금액
$bank_code	= "";									// 은행코드
$account	  = "";									// 가상계좌 입금계좌번호
$op_cd		= "";									// 처리구분 코드
$noti_id	  = "";									// 통보 아이디
$cash_a_no	= "";									// 현금영수증 승인번호
$cash_a_dt	= "";									// 현금영수증 승인시간
$cash_no	  = "";									// 현금영수증 거래번호

$Order->log_file($_POST);
//02-1. 가상계좌 입금 통보 데이터 받기

if ( $tx_cd == "TX00" ) {
	$ipgm_name = $_POST[ "ipgm_name" ];				// 주문자명
	$remitter  = $_POST[ "remitter"  ];				// 입금자명
	$ipgm_mnyx = $_POST[ "ipgm_mnyx" ];				// 입금금액
	$bank_code = $_POST[ "bank_code" ];				// 은행코드
	$account   = $_POST[ "account"   ];				// 가상계좌 입금계좌번호
	$op_cd	 = $_POST[ "op_cd"	 ];				// 처리구분 코드
	$noti_id   = $_POST[ "noti_id"   ];				// 통보 아이디
	$cash_a_no = $_POST[ "cash_a_no" ];				// 현금영수증 승인번호
	$cash_a_dt = $_POST[ "cash_a_dt" ];				// 현금영수증 승인시간
	$cash_no   = $_POST[ "cash_no"   ];				// 현금영수증 거래번호
}

// 03. 공통 통보 결과를 업체 자체적으로 DB 처리 (DB update 성공시 result : 0000)
if ( $tx_cd == "TX00" ) {
	//상품테이블 결제완료 처리
	
	$payment_info = $Order->getPaymentRow($order_num);

	//금액비교처리
	if($payment_info['amount'] != $ipgm_mnyx) {
		result_print('9999');
		
	}
	
	$rs = $Order->setPaid($order_num,'가상계좌 > 자동입금처리');
	if($rs) {
		//지불테이블 정보 업데이트
		$res_info_arr = array(); //unserialize($payment_info['res_info']);
		$res_info_arr['cash_no'] = $cash_no;
		$res_info_arr['cash_a_no'] = $cash_a_no;
		$res_info_arr['cash_a_dt'] = $cash_a_dt;
		$res_info_arr['op_cd'] = $op_cd; // 처리구분 코드
		$res_info_arr['remitter'] = iconv('euc-kr','utf-8',$remitter);// 입금자명
		$res_info_arr['ipgm_mnyx'] = $ipgm_mnyx; // 입금 금액

		$return_info = serialize($res_info_arr);

		$tbl_payment = $Order->tbls['order_payment'];
		$sql = sqlUpdate(array('tx_cd'=>$tx_cd, 'return_info'=>$return_info, 'date_update'=>NOW), $tbl_payment, array('idx'=>$payment_info['idx']));
		$rs_payment = $Order->adodb->Execute($sql);
		
		if(!$rs_payment) result_print('9999');
		else  {
			//상품별 처리상태 업데이트
			result_print('0000');
		}

	}
	else {
		result_print('9999');
	}
}
else if($tx_cd == 'TX01') { //가상계좌 환불 통보 데이터 DB 처리 작업 부분
	$payment_info = $Order->getPaymentRow($order_num);
	$tbl_payment = $Order->tbls['order_payment'];
	$sql = sqlUpdate(array('tx_cd'=>$tx_cd, 'date_update'=>NOW), $tbl_payment, array('idx'=>$payment_info['idx']));
	$rs_payment = $Order->adodb->Execute($sql);
	if($rs_payment) {
		result_print('0000');
	}
	else {
		result_print('9999');
	}
}
else if($tx_cd == 'TX02') { //구매확인통보
	
	$payment_info = $Order->getPaymentRow($order_num);
	$tbl_payment = $Order->tbls['order_payment'];
	$sql = sqlUpdate(array('tx_cd'=>$tx_cd, 'date_update'=>NOW), $tbl_payment, array('idx'=>$payment_info['idx']));
	$rs_payment = $Order->adodb->Execute($sql);
	if($rs_payment) {
		$tbl_op = $Order->tbls['order_product'];

		$Order->log_file("SELECT array_to_string(array_agg(idx::int),',') AS idxs FROM {$tbl_op} WHERE order_num='{$order_num}'");
		

		$order_product_idx = $Order->adodb->getOne("SELECT array_to_string(array_agg(idx::int),',') AS idxs FROM {$tbl_op} WHERE order_num='{$order_num}'");
		$Order->log_file($order_product_idx);

		$rs_change = $Order->changeOrderStatus($order_product_idx, '5', $log='사용자 구매확인(배송완료)처리');

		result_print('0000');
	}
	else {
		result_print('9999');
	}

}
else if($tx_cd == 'TX03') { //배송시작통보
	$payment_info = $Order->getPaymentRow($order_num);
	$tbl_payment = $Order->tbls['order_payment'];
	$sql = sqlUpdate(array('tx_cd'=>$tx_cd, 'date_update'=>NOW), $tbl_payment, array('idx'=>$payment_info['idx']));
	$rs_payment = $Order->adodb->Execute($sql);
	if($rs_payment) {
		result_print('0000');
	}
	else {
		result_print('9999');
	}
}
else if($tx_cd == 'TX04') { //정산오류통보
	$payment_info = $Order->getPaymentRow($order_num);
	$tbl_payment = $Order->tbls['order_payment'];
	$sql = sqlUpdate(array('tx_cd'=>$tx_cd, 'date_update'=>NOW), $tbl_payment, array('idx'=>$payment_info['idx']));
	$rs_payment = $Order->adodb->Execute($sql);
	if($rs_payment) {
		result_print('0000');
	}
	else {
		result_print('9999');
	}
}
else if($tx_cd == 'TX05') { //즉시취소 통보
	$payment_info = $Order->getPaymentRow($order_num);
	$tbl_payment = $Order->tbls['order_payment'];
	$sql = sqlUpdate(array('tx_cd'=>$tx_cd, 'date_update'=>NOW), $tbl_payment, array('idx'=>$payment_info['idx']));
	$rs_payment = $Order->adodb->Execute($sql);
	if($rs_payment) {
		result_print('0000');
	}
	else {
		result_print('9999');
	}
}
else if($tx_cd == 'TX06') { //취소통보
	$payment_info = $Order->getPaymentRow($order_num);
	$tbl_payment = $Order->tbls['order_payment'];
	$sql = sqlUpdate(array('tx_cd'=>$tx_cd, 'date_update'=>NOW), $tbl_payment, array('idx'=>$payment_info['idx']));
	$rs_payment = $Order->adodb->Execute($sql);
	if($rs_payment) {
		result_print('0000');
	}
	else {
		result_print('9999');
	}
}
else if($tx_cd == 'TX07') { //발급계좌해지 통보
	$payment_info = $Order->getPaymentRow($order_num);
	$tbl_payment = $Order->tbls['order_payment'];
	$sql = sqlUpdate(array('tx_cd'=>$tx_cd,'date_update'=>NOW), $tbl_payment, array('idx'=>$payment_info['idx']));
	$rs_payment = $Order->adodb->Execute($sql);
	if($rs_payment) {
		result_print('0000');
	}
	else {
		result_print('9999');
	}
}
else {
	result_print('9999');
}



function result_print($result) {
	global $Order;
	$data = $_POST;
	$data['result'] = $result;
	$Order->log_file($data); //가상계좌 처리로그

	echo "<html><body><form><input type='hidden' name='result' value='{$result}'></form></body></html>";
	exit;
}

?>

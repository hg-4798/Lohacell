<?php
/**
 * 주문상세정보
 */
$Dir = '../../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("../access.php");

####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}
#########################################################
$layout = "inc";
include("../header.php");
$order_num = $_GET['orn'];
$Product = new PRODUCT;
$Order = new ORDER;
$Order->setPlace('admin');

$tbl_basic = $Order->tbls['order'];
$tbl_product = $Order->tbls['order_product'];
$tbl_payment = $Order->tbls['order_payment'];

//주문서정보
$order_basic  = $Order->adodb->getRow("SELECT * FROM {$tbl_basic} WHERE order_num='{$order_num}'");
$order_basic['buyer_email_arr'] = explode('@', $order_basic['buyer_email']);
$order_basic['buyer_mobile_arr'] = explode('-', $order_basic['buyer_mobile']);
$order_basic['buyer_tel_arr'] = explode('-', $order_basic['buyer_tel']);
$order_basic['receiver_mobile_arr'] = explode('-', $order_basic['receiver_mobile']);
$order_basic['receiver_tel_arr'] = explode('-', $order_basic['receiver_tel']);

//택배사
$deli_company = $Order->getDeliveryCompanyPair();

//결제정보
$order_payment = $Order->getPaymentRow($order_num);
$payment_info = $Order->setPayInfo($order_payment['pay_method'], $order_payment['res_info'], 'all');
$order_payment['set'] = $payment_info;
// pre($order_payment);

//주문상품정보
$order_product = $Order->getProductGroup("order_num='{$order_num}' AND order_status>0 AND cs_type='0' AND cs_status='0'");
// pre($order_product);

//상태별 카운팅
$status_rs = $Order->adodb->getArray("SELECT order_status, cs_type, cs_status, COUNT(*) AS cnt FROM tblorder_product WHERE order_num='{$order_num}' GROUP BY order_status, cs_type, cs_status");
$cnt_status = array();
foreach($status_rs as $v) {
	if($v['cs_type'] == '0') {
		$status_id = 'status_'.$v['order_status'].$v['cs_type'];
		$cnt_status[$status_id] = $v['cnt'];
	}
	else {
		if($v['cs_status'] !=' 0') {
			$status_id = 'status_'.$v['cs_type'];
			$cnt_status[$status_id] += $v['cnt'];
		}
		
	}
}

$enable_status = array(); //변경가능한상태

//취소상품
$cancel_product = $Order->getProductGroup("order_num='{$order_num}' AND order_status>0 AND cs_type='C'",',cs_idx');
if(is_array($cancel_product))  {
	foreach($cancel_product as $k=>$v) {
		//취소정보
		$cancel_info = $Order->getCancelRow($v['cs_idx']);
		$cancel_product[$k]['cancel_info'] = $cancel_info;
	}
}

//반품상품
// $return_product = $Order->getProductList($order_num, "order_status>2 AND cs_type='R'");
$return_list = $Order->getProductAll("order_num='{$order_num}' AND  order_status>2 AND cs_type='R'");
// $return_list = $return_product['list'];
if(is_array($return_list))  {
	foreach($return_list as $k=>$v) {
		$return_info = $Order->getReturnInfo($v['idx']);//반품접수정보
		$return_list[$k]['return_info'] = $return_info;
	}
}

//교환상품
$exchange_list = $Order->getProductAll("order_num='{$order_num}' AND order_status>2 AND cs_type='E' AND cs_status>0");
$exchange_control = false;
$tbl_ep = $Order->tbls['order_exchange_product'];
if(is_array($exchange_list))  {
	foreach($exchange_list as $k=>&$v) {
		//택배사정보
		$v['delivery_company_name'] = $deli_company[$v['delivery_company']]['company_name'];

		if($v['cs_status'] == '4') { //교환승인완료인경우 
			$v['rowspan']='2';
		}
		else $v['rowspan']='1';
		
		$exchange_info = $Order->getExchangeInfo($v['idx']);

		$v['reason'] = $exchange_info['reason'];
		$v['reason_etc'] = $exchange_info['reason_etc'];
		$v['date_status_1'] = $exchange_info['date_status_1'];
		$v['date_status_4'] = $exchange_info['date_status_4'];

		if($v['cs_status'] == 4) { //교환승인
			$exchange_control = true;
			//교환상품
			$ep = $Order->getProductRow("cs_product_idx='".$v['idx']."'");
			$ep['option']  = $Product->getOptionRow($ep['option_code'],'option_name, (option_quantity-option_quantity_sales) AS stock');
			$ep['step'] = $Order->getStep($ep);
			$ep['delivery_company_name'] = $deli_company[$ep['delivery_company']]['company_name'];

			$v['exchange'] = $ep;

		}

		$exchange_list[$k]['exchange_info'] = $v;
	}
}

//에스크로 안내메시지
if($order_payment['escrow_yn'] == 'Y'){
	if($cnt_status['status_40'] > 0) {
		$escrow_msg = '에스크로 결제시 배송중 단계에서 반품이 불가합니다.';
	}
	else {
		$escrow_msg = '에스크로 결제시 개별 상태변경이 불가합니다.';
	}
}
else $escrow_msg = '';

//사은품정보
$gift = $Order->getGiftList($order_num);



//메모
$order_memo = $Order->getMemoRow($order_num);

$assign = array(
	'cnt'=>array(
		'status'=>$cnt_status
	),
	'cfg'=>array(
		'deli_company'=>$deli_company,
		'escrow_msg'=>$escrow_msg
	),
	'enable'=>array(
		'status'=>$enable_status,
		'exchange'=>$exchange_control
	),
	'setting'=>array(
		'email_chk'=>in_array($order_basic['buyer_email'][1],$email_domain_arr),
	),
	'gift'=>$gift, //사은품정보
	'payment'=>$order_payment,
	'basic'=>$order_basic,
	'product'=>$order_product,
	'cancel'=>$cancel_product,
	'return'=>$return_list,
	'exchange'=>$exchange_list,
	'memo'=>$order_memo
);

_render("order/order_view.html", $assign, 'admin/template');

include("../copyright.php");
exit;

?>
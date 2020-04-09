<?php
/**
 * 반품신청
 */
$Dir = '../../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$layout = "inc";
include("../header.php");
$order_num = $_GET['orn'];
$Order = new ORDER;
$Order->setPlace('admin');


$tbl_basic = $Order->tbls['order'];
$tbl_product = $Order->tbls['order_product'];
$tbl_payment = $Order->tbls['order_payment'];

//주문서정보
$order_basic  = $Order->adodb->getRow("SELECT * FROM {$tbl_basic} WHERE order_num='{$order_num}'");

$order_payment = $Order->getPaymentRow($order_num);

//반품가능 주문상품
$mileage_expect = 0;
$order_product = $Order->getProductGroup("order_num='{$order_num}' AND order_status>2 AND cs_type='0'");
if(!$order_product || ($order_payment['escrow_yn']=='Y' && !in_array($order_payment['tx_cd'], array('TX02')))) { //에스크로 결제시 구매확인 통보(TX02) 상태가 아닌경우 반품신청불가
	alert_go('반품 가능한 상품이 없습니다.','c');
}

//todo 에스크로 체크

foreach($order_product as $row) {	
	$mileage_expect+=$row['mileage_expect'];
}

$order_basic['mileage_expect'] = $mileage_expect;
$order_basic['payment'] = $Order->getPaymentRow($order_num); //결제정보

$assign = array(
	'cfg'=>array(
		'batch'=>($order_basic['payment']['escrow_yn']=='Y')?true:false
	),
	'basic'=>$order_basic,
	'product'=>$order_product
);

_render("order/return.html", $assign, 'admin/template');

include("../copyright.php");

?>
<?php
/**
 * 환불신청
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
$order_basic['buyer_email_arr'] = explode('@', $order_basic['buyer_email']);
$order_basic['buyer_mobile_arr'] = explode('-', $order_basic['buyer_mobile']);
$order_basic['buyer_tel_arr'] = explode('-', $order_basic['buyer_tel']);
$order_basic['receiver_mobile_arr'] = explode('-', $order_basic['receiver_mobile']);
$order_basic['receiver_tel_arr'] = explode('-', $order_basic['receiver_tel']);


//취소가능 주문상품
$mileage_expect = 0;
$order_product = $Order->getProductGroup("order_num='{$order_num}' AND order_status IN(2,3) AND cs_type='0'");
if(!$order_product) {
	alert_go('환불가능한 상품이 없습니다.','c');
}
foreach($order_product as $row) {
	$mileage_expect+=$row['mileage_expect'];
}

$order_basic['mileage_expect'] = $mileage_expect;

$order_basic['payment'] = $Order->getPaymentRow($order_num);

$assign = array(
	'cfg'=>array(
		'batch'=>($order_basic['payment']['escrow_yn']=='Y')?true:false
	),
	'basic'=>$order_basic,
	'product'=>$order_product
);

_render("order/refund.html", $assign, 'admin/template');

include("../copyright.php");

?>
<?php
/**
 * 교환신청
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

//교환가능 주문상품 - 배송중~배송완료
$mileage_expect = 0;
$order_product = $Order->getProductGroup("order_num='{$order_num}' AND order_status IN(4,5) AND cs_type='0'");
if(!$order_product) {
	alert_go('교환 가능한 상품이 없습니다.','c');
}
foreach($order_product as $row) {
	
	$mileage_expect+=$row['mileage_expect'];
}

$order_basic['mileage_expect'] = $mileage_expect;


$assign = array(
	'basic'=>$order_basic,
	'product'=>$order_product
);

_render("order/exchange.html", $assign, 'admin/template');

include("../copyright.php");

?>
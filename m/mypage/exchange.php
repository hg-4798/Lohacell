<?php
/**
 * 교환 배송비 확인
 */

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");



$Order = new ORDER;
$order_num = $_POST['order_num']; //주문번호
$order_basic  = $Order->getBasicRow($order_num);

//배송비 설정
$Product = new PRODUCT;
$cfg_delivery = $Product->getDeilvery(); //배송비설정

//지역별 배송비 구하기
$local_deliprice = $Order->getLocalDeliveryFee($_POST['receiver_zipcode']);
if($local_deliprice>0){
	$calc_deliprice = $local_deliprice;
}else{
	$calc_deliprice = $cfg_delivery['deli_basefee'];
}

$product = array_arrange($_POST['product']); //선택상품정보
$charger = 'user'; //기본 고객귀책, 판매자귀책이 우선한다
foreach($product as $v) {
	list($reason, $c) = explode('|', $v['reason']);
	if($c == 'seller') $charger = 'seller';
}


//배송비
$paid_delivery = $order_basic['pay_delivery'] - $order_basic['coupon_delivery_discount'];
if($charger == 'user') { //구매자 귀책사유
	$pay_delivery = $calc_deliprice * 2;
}
else {
	$pay_delivery = '0';
}

$assign = array(
	'basic'=>$order_basic,
	'pay_delivery'=>$pay_delivery
);

_render('mypage/exchange.html', $assign, DIR_M.'/template');
?>

<?php
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');


$Order = new ORDER;
$Review = new Review();
$order_num = $Order->Dectypt_AES128CBC($_GET['oid']);

$order_basic  = $Order->getBasicRow($order_num);
$order_basic['order_status_max'] = $Order->adodb->getOne("SELECT MAX(order_status) AS status_max FROM ".$Order->tbls['order_product']." WHERE order_num='{$order_num}'");
$order_basic['oid'] = $_GET['oid'];
if(!$order_basic) {
    alert_go('잘못된 경로로 접근하였습니다#1.','/');
}

$valid = $Order->checkAuth($order_basic);
if(!$valid) {
    //alert_go('잘못된 경로로 접근하였습니다#2.','/');
}

$order_product = $Order->getProductGroup("order_num='{$order_num}'"); //주문상품
$product = array();
foreach ($order_product AS $k=>$v){
	$review_auth = $Review->getAuth($v['productcode'],$v['option_code'],$order_num);
	if(is_array($review_auth)) $v['review']['idx'] = $review_auth[$v['option_code']]['idx'];
	else $v['review']['idx'] = $review_auth;

	$product[] = $v;
}
$order_payment = $Order->getPaymentRow($order_num); //결제정보
$pay_info = $Order->setPayInfo($order_payment['pay_method'], $order_payment['res_info']);
$order_payment['detail'] = $pay_info;

$assign = array(
	'basic'=>$order_basic,
	'product'=>$product,
	'total'=>count($product),
	'payment'=>$order_payment
);

_render('mypage/order_view.html', $assign, DIR_M.'/template');

include('./include/bottom.php');


exit;

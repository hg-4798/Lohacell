<?php
/**
 * 주문내역 상세보기
 * @author hjlee
 */
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

$Order = new ORDER;
$order_num = $Order->Dectypt_AES128CBC($_GET['oid']);

$order_basic  = $Order->getBasicRow($order_num);
if(!$order_basic) {
    alert_go('잘못된 경로로 접근하였습니다#1.','/');
}

$valid = $Order->checkAuth($order_basic);
if(!$valid) {
    //alert_go('잘못된 경로로 접근하였습니다#2.','/');
}

$order_product = $Order->getOrderProductList($order_num,"cs_type='0' AND order_status='5'"); //주문상품
//pre($order_product);
$order_payment = $Order->getPaymentRow($order_num); //결제정보
$pay_info = $Order->setPayInfo($order_payment['pay_method'], $order_payment['res_info']);
$order_payment['detail'] = $pay_info;
//회원정보
if(MEMID) {
	$Member = new MEMBER;
	$member_info = $Member->getMemberRow(MEMID);
}
else $member_info = array();

$assign = array(
	'basic'=>$order_basic,
	'product'=>$order_product,
	'total'=>count($order_product),
	'payment'=>$order_payment,
	'member'=>$member_info
);



// pre($order_product);
_render('mypage/exchange_list.html', $assign);

include('./include/bottom.php');

exit;
?>

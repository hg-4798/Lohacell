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
$force_batch = false; //전체반품강제여부

$order_basic  = $Order->getBasicRow($order_num);
if(!$order_basic) {
	alert_go('잘못된 경로로 접근하였습니다#1.','/');
}

$valid = $Order->checkAuth($order_basic);
if(!$valid) {
	alert_go('잘못된 경로로 접근하였습니다#2.','/');
}

//반품가능상품 유무체크
$cnt = $Order->countProduct("order_num='{$order_num}' AND order_status>4 AND cs_type='0'");
if($cnt < 1) {
	alert_go('반품 가능한 상품이 없습니다.','/');
}

$order_product = $Order->getProductGroup("order_num='{$order_num}' AND order_status>4 AND cs_type='0' "); //주문상품

$order_payment = $Order->getPaymentRow($order_num); //결제정보
$pay_info = $Order->setPayInfo($order_payment['pay_method'], $order_payment['res_info']);
$order_payment['detail'] = $pay_info;

//사은품정보
$gift = $Order->getGiftList($order_num);
if($gift) $force_batch = true;

//장바구니쿠폰사용여부
if($order_basic['coupon_basket_discount'] > 0) $force_batch = true;


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
	'gift'=>$gift,
	'member'=>$member_info,
	'batch'=>$force_batch
);

//pre($order_basic);
_render('mypage/refund_list.html', $assign);

include('./include/bottom.php');

exit;
?>

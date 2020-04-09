<?php
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');


$Order = new ORDER;
$order_num = $Order->Dectypt_AES128CBC($_GET['oid']);

$order_basic  = $Order->getBasicRow($order_num);
$order_basic['oid'] = $_GET['oid'];
if(!$order_basic) {
    alert_go('잘못된 경로로 접근하였습니다#1.','/');
}

$valid = $Order->checkAuth($order_basic);
if(!$valid) {
    //alert_go('잘못된 경로로 접근하였습니다#2.','/');
}

$order_product = $Order->getProductGroup("order_num='{$order_num}'"); //주문상품

$order_payment = $Order->getPaymentRow($order_num); //결제정보
$pay_info = $Order->setPayInfo($order_payment['pay_method'], $order_payment['res_info']);
$order_payment['detail'] = $pay_info;
$oder_giftlist = $Order->getGiftList($order_num);

//환불가능 마일리지
$refund_mileage = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='mileage' AND etc_limit >= NOW()");

//환불가능 포인트
$refund_point = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='point' AND etc_limit >= NOW()");

//환불예정금액 = (총상품금액 + 배송비) - 마일리지복원 - 포인트복원 - 상품쿠폰사용액 - 장바구니쿠폰사용액 - 무료배송쿠폰사용액
$refund_total = $order_basic['pay_pg'];

//환불계좌정보
if($order_basic['member_id']) {
	$Member = new MEMBER;
	$info = $Member->getMemberRow($order_basic['member_id'],'bank_code, account_num, depositor');
	$bank = array(
		'bank_code'=>$info['bank_code'],
		'account'=>$info['account_num'],
		'depositor'=>$info['depositor']
	);
}
else {
	//@todo 비회원 환불계좌정보 pre($order_basic);
}


$assign = array(
	'basic'=>$order_basic,
	'product'=>$order_product,
	'total'=>count($order_product),
	'payment'=>$order_payment,
	'gift_list'=>$oder_giftlist,
	'gift_total'=>count($oder_giftlist),
	'refund'=>array(
		'total'=>$refund_total,
		'mileage'=>$refund_mileage,
		'point'=>$refund_point
	),
	'bank'=>$bank
);

_render('mypage/cancel_list.html', $assign, DIR_M.'/template');

include('./include/bottom.php');

exit;

?>

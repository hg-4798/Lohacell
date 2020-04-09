<?php
/**
 * 마이페이지 반품신청
 * 반품금액 확인창
 */

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");



$Order = new ORDER;
$order_num = $_POST['order_num']; //주문번호
$order_basic  = $Order->getBasicRow($order_num);

//상품금액계산
$tbl_op = $Order->tbls['order_product'];

$product = array_arrange($_POST['product']); //선택상품정보

$refund_product = 0;//환불상품총액
$cancel_mileage = 0;//적립취소마일리지
$charger = 'user'; //기본 고객귀책, 판매자귀책이 우선한다
foreach($product as $v) {
	$product_rs = $Order->adodb->Execute("SELECT * FROM {$tbl_op} WHERE idx IN(".$v['checked'].") ORDER BY coupon_issue_no ASC OFFSET 0 LIMIT ".$v['count']); //쿠폰사용안한 구매목록부터 반품

	list($reason, $c) = explode('|', $v['reason']);
	if($c == 'seller') $charger = 'seller';
	while($row = $product_rs->FetchRow()){
		$refund_product += $row['price_end']; //취소할 삼품총금액
		$cancel_mileage += $row['mileage_expect'];
		
	}
}

//환불가능 마일리지
$refund_mileage = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='mileage' AND etc_limit >= NOW()");
if($refund_product < $refund_mileage) $refund_mileage = $refund_product;

//환불가능 포인트
$refund_point = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='point' AND etc_limit >= NOW()");
if($refund_product < $refund_point) $refund_point = $refund_product;


//배송비 설정
$Product = new PRODUCT;
$cfg_delivery = $Product->getDeilvery(); //배송비설정

$remain_total = $order_basic['pay_total'] - $refund_product;

//지역별 배송비 구하기
$local_deliprice = $Order->getLocalDeliveryFee($_POST['receiver_zipcode']);
if($local_deliprice>0){
	$calc_deliprice = $local_deliprice;
}else{
	$calc_deliprice = $cfg_delivery['deli_basefee'];
}

//배송비
$paid_delivery = $order_basic['pay_delivery'] - $order_basic['coupon_delivery_discount'];
if($charger == 'user') { //구매자 귀책사유
	if($paid_delivery > 0) {
		$pay_delivery = $calc_deliprice*2;
	}
	else {
		$deli_miniprice = ($order_basic['pr_type']=='1')?$cfg_delivery['deli_miniprice']:$cfg_delivery['deli_miniprice_staff'];;
		if($remain_total >= $deli_miniprice) {
			$pay_delivery = $calc_deliprice;
		}
		else {
			$pay_delivery = $calc_deliprice * 2;
		}
	}
}
else {
	$pay_delivery = '0';
}

$refund_coupon = $order_basic['coupon_basket_discount'] + $order_basic['coupon_product_discount'];
$refund_end = $refund_product - $pay_delivery - $refund_mileage - $refund_point - $refund_coupon; //환불예정금액  = 환불상품금액 - 복원마일리지 - 복원포인트 - 배송비 - 쿠폰할인액


$assign = array(
	'basic'=>$order_basic,
	'refund'=>array(
		'product'=>$refund_product,
		'point'=>$refund_point,
		'mileage'=>$refund_mileage,
		'end'=>$refund_end
	),
	'pay_delivery'=>$pay_delivery,
	'cancel'=>array(
		'mileage'=>$cancel_mileage
	)
);

_render('mypage/refund.html', $assign, DIR_M.'/template');

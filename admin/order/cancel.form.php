<?php
/**
 * 주문취소
 */
$Dir = '../../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Order = new ORDER;

$order_num = $_POST['order_num'];
$tbl_basic = $Order->tbls['order'];
$tbl_product = $Order->tbls['order_product'];
$tbl_payment = $Order->tbls['order_payment'];

//주문서정보
$order_basic  = $Order->adodb->getRow("SELECT * FROM {$tbl_basic} WHERE order_num='{$order_num}'");

$checked = array_arrange($_POST['checked']);

$price_cancel = 0;
$coupon = array();

foreach($checked as $v) {
	$rs = $Order->adodb->Execute("SELECT idx, price_end, coupon_issue_no FROM {$tbl_product} WHERE idx IN (".$v['product'].") AND order_status=1");
	$idx=0;
	while($row = $rs->FetchRow()) {
		$coupon[$row['idx']] = $row['coupon_issue_no'];
		if($idx < $v['count']) $price_cancel+=$row['price_end'];
		$idx++;
	}

}

//상품쿠폰정보
$coupon_produt = array();
if(is_array($coupon)) {
	
	$Coupon = new COUPON;
	foreach($coupon as $k=>$coupon_no) {
		if(!$coupon_no) continue;
		$coupon_row = $Coupon->getIssueCouponRow($coupon_no);
		$coupon_produt[$k] = $coupon_row;
	}
}

//장바구니쿠폰정보
if($order_basic['coupon_basket'] > 0) {
	$coupon_row = $Coupon->getIssueCouponRow($order_basic['coupon_basket']);
	$coupon_basket = array(
		'ci_no'=>$order_basic['coupon_basket'],
		'discount'=>$order_basic['coupon_basket_discount'],
		'info'=>$coupon_row
	);
}


//무료배송쿠폰정보
if($order_basic['coupon_delivery'] > 0) {
	$coupon_row = $Coupon->getIssueCouponRow($order_basic['coupon_delivery']);
	$coupon_delivery = array(
		'ci_no'=>$order_basic['coupon_delivery'],
		'discount'=>$order_basic['coupon_delivery_discount'],
		'info'=>$coupon_row
	);
}

$order_basic['pay_delivery_end'] = $order_basic['pay_delivery'] - $order_basic['coupon_delivery_discount'];

//환불가능 마일리지
$refund_mileage = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='mileage' AND etc_limit >= NOW()");

//환불가능 포인트
$refund_point = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='point' AND etc_limit >= NOW()");

$assign = array(
	'checked'=>serialize($checked),
	'refund'=>array(
		'total'=>$price_cancel,
		'mileage'=>$refund_mileage,
		'point'=>$refund_point
	),
	'coupon'=>array(
		'product'=>$coupon_produt, //상품별쿠폰
		'basket'=>$coupon_basket, //장바구니쿠폰
		'delivery'=>$coupon_delivery //무료배송쿠폰
	),
	'basic'=>$order_basic
);

_render("order/cancel.form.html", $assign, 'admin/template');
?>
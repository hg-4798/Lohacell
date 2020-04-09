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
$payment = $Order->getPaymentRow($order_num);
$payment['detail'] = $Order->setPayInfo($order_basic['pg_paymethod'], $payment['res_info'], 'all'); //결제수단정보
$order_basic['pg_paymethod_txt'] = $payment['detail']['name'];

$checked = array_arrange($_POST['checked']);

$price_refund = 0;
$coupon = array();
$refund_list = array(); //환불대상 상품pk
$discount_coupon = 0; //상품쿠폰할인액
foreach($checked as $v) {
	$rs = $Order->adodb->Execute("SELECT idx, price_end, coupon_issue_no, coupon_discount FROM {$tbl_product} WHERE idx IN (".$v['product'].") AND order_status IN (2,3) ORDER BY coupon_discount ASC");
	$idx=0;
	while($row = $rs->FetchRow()) {
		if($idx >= $v['count'])  break;
		$price_product+=$row['price_end'];//총 환불금액
		$refund_list[] = $row['idx']; //환불할 상품pk
		if($row['coupon_issue_no']>0) {
			$coupon[] = array(
				'ci_no'=>$row['coupon_issue_no'], //상품쿠폰
				'discount'=>$row['coupon_discount'] //상품할인액
			); 
			$discount_coupon+=$row['coupon_discount'];
		}
		$idx++;
	}
}


//상품쿠폰할인액 제외
$price_total = $price_product-$discount_coupon;

//장바구니 쿠폰할인액 제외
$price_total = $price_total-$order_basic['coupon_basket_discount'];


//상품쿠폰정보
$coupon_product = array();
if(is_array($coupon)) {
	
	$Coupon = new COUPON;
	foreach($coupon as $k=>$v) {
		if(!$v['ci_no']) continue;
		$coupon_row = $Coupon->getIssueCouponRow($v['ci_no']);
		$coupon_row['discount'] = $v['discount'];
		$coupon_product[$k] = $coupon_row;
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


//마일리지
$expire_mileage = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='mileage' AND etc_limit < NOW()"); //소멸마일리지
$restore_mileage = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='mileage' AND etc_limit >= NOW()"); //환불가능마일리지
$refund_mileage = $restore_mileage+$expire_mileage;
if($price_total < $refund_mileage) {
	$refund_mileage = $price_total;
}

//포인트
$expire_point = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='point' AND restore_yn='N' AND etc_limit < NOW()"); //소멸포인트
$restore_point = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='point' AND restore_yn='N' AND etc_limit >= NOW()"); //환불가능포인트
$refund_point = $restore_point+$expire_point;
if($price_total < $refund_point) {
	$refund_point = $price_total;
}

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
	//pre($order_basic);
}

$refund_pg = $price_total-$refund_mileage-$refund_point;

//배송비 환불여부

if(($order_basic['pay_total']-$refund_pg) == $order_basic['pay_delivery_end']) {
	$refund_delivery = $order_basic['pay_delivery_end'];
}
else $refund_delivery = 0;


$assign = array(
	'checked'=>implode(',',$refund_list),
	'refund'=>array(
		'total'=>$price_total,
		'product'=>$price_product,
		'pg'=>$refund_pg,
		'mileage'=>$refund_mileage,
		'point'=>$refund_point,
		'delivery'=>$refund_delivery
	),
	'discount'=>$discount_coupon,
	'coupon'=>array(
		'product'=>$coupon_product, //상품별쿠폰
		'basket'=>$coupon_basket, //장바구니쿠폰
		'delivery'=>$coupon_delivery //무료배송쿠폰
	),
	'mileage'=>array(
		'expire'=>$expire_mileage,
		'restore'=>$restore_mileage
	),
	'point'=>array(
		'expire'=>$expire_point,
		'restore'=>$restore_point
	),
	'payment'=>$payment,
	'basic'=>$order_basic,
	'bank'=>$bank,
);

// pre($assign['refund']);
/*
$assign = array(
	'checked'=>implode(',',$refund_list),
	'refund'=>array(
		'total'=>$price_refund
	),
	'bank'=>$bank,
	'basic'=>$order_basic
);
*/
_render("order/refund.form.html", $assign, 'admin/template');
?>
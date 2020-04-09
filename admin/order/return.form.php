<?php
/**
 * 반품접수
 * 관리자 반품처리는 결제완료 이상인 상품모두 가능
 */
$Dir = '../../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Order = new ORDER;
$Product = new PRODUCT;

$order_num = $_POST['order_num'];
$tbl_basic = $Order->tbls['order'];
$tbl_op = $Order->tbls['order_product'];
$tbl_payment = $Order->tbls['order_payment'];

//주문서정보
$order_basic  = $Order->adodb->getRow("SELECT * FROM {$tbl_basic} WHERE order_num='{$order_num}'");
$payment = $Order->getPaymentRow($order_num);
$payment['detail'] = $Order->setPayInfo($order_basic['pg_paymethod'], $payment['res_info'], 'all'); //결제수단정보
$order_basic['pg_paymethod_txt'] = $payment['detail']['name'];
$order_basic['receiver_mobile_arr'] = explode('-',$order_basic['receiver_mobile']);
$order_basic['receiver_tel_arr'] = explode('-',$order_basic['receiver_tel']);

$checked = array_arrange($_POST['checked']);

$price_return = 0;
$coupon = array();
$return_list = array(); //환불대상 상품pk
$discount_coupon = 0; //상품쿠폰할인액
$product_list = array();
foreach($checked as $v) {
	$rs = $Order->adodb->Execute("SELECT idx, price_end, productcode, coupon_issue_no, coupon_discount, option_type, option_code FROM {$tbl_op} WHERE idx IN (".$v['product'].") AND order_status>2 ORDER BY coupon_discount ASC");
	$idx=0;
	while($row = $rs->FetchRow()) {
		if($idx >= $v['count'])  break;

		//반품 상품정보
		if($row['option_type'] == 'option') {
			$p_row = $Product->getRowSimple($row['productcode'], false,'productname');
			$p_row['option'] = $Product->getOptionRow($row['option_code'], 'option_name');
		}
		else {
			$p_row = $Product->getRowSimple($row['option_code']);
		}
		$product_list[$row['idx']] = $p_row;

		$price_product+=$row['price_end'];//총 환불금액
		$return_list[] = $row['idx']; //환불할 상품pk
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

// pre($product_list);

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


//환불가능 마일리지
$return_mileage = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='mileage' AND etc_limit >= NOW()");
if($price_total < $return_mileage) {
	$return_mileage = $price_total;
}

//환불가능 포인트
$return_point = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='point' AND etc_limit >= NOW()");
if($price_total < $return_point) {
	$return_point = $price_total;
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

$return_pg = $price_total-$return_mileage-$return_point;


//사은품정보
$gift = $Order->getGiftList($order_num);

//기본배송비 정보
$cfg_delivery = $Product->getDeilvery(); //배송비설정

//지역별 배송비 구하기
$local_deliprice = $Order->getLocalDeliveryFee($order_basic['receiver_zipcode']);
if($local_deliprice>0){
	$calc_deliprice = $local_deliprice;
}else{
	$calc_deliprice = $cfg_delivery['deli_basefee'];
}

$assign = array(
	'checked'=>implode(',',$return_list),
	'product'=>$product_list,
	'return'=>array(
		'total'=>$price_total,
		'product'=>$price_product,
		'pg'=>$return_pg,
		'mileage'=>$return_mileage,
		'point'=>$return_point
	),
	'basic'=>$order_basic,
	'payment'=>$payment,
	'gift'=>$gift,
	'deli_basefee'=>$cfg_delivery['deli_basefee'],
	'calc_deliprice' => $calc_deliprice
);


_render("order/return.form.html", $assign, 'admin/template');
?>
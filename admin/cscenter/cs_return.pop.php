<?php
/**
 * 반품처리
 */
$Dir = '../../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$layout = "inc";
include("../header.php");
$cs_idx = $_GET['csidx'];

$Product = new PRODUCT;
$Order = new ORDER;
$Order->setPlace('admin');


$tbl_basic = $Order->tbls['order'];
$tbl_p = $Order->tbls['product'];
$tbl_op = $Order->tbls['order_product'];
$tbl_orb = $Order->tbls['order_return'];
$tbl_orp = $Order->tbls['order_return_product'];


//반품요청서정보
$cs_basic  = $Order->adodb->getRow("SELECT * FROM {$tbl_orb} WHERE idx='{$cs_idx}'");
$cs_basic['receiver_mobile_arr'] = explode('-',$cs_basic['receiver_mobile']);
$cs_basic['receiver_tel_arr'] = explode('-',$cs_basic['receiver_tel']);
$order_num = $cs_basic['order_num'];

// pre($cs_basic);

//기본주문서정보
$order_basic  = $Order->adodb->getRow("SELECT * FROM {$tbl_basic} WHERE order_num='{$order_num}'");
$order_payment = $Order->getPaymentRow($order_num);
$order_payment['info'] = $Order->setPayInfo($order_basic['pg_paymethod'], $order_payment['res_info'], 'all'); //결제수단정보




//환불테이블 정보
$refund_basic = $Order->getRefundRow($cs_basic['refund_idx']);
$cs_basic['refund_info'] = $refund_basic;
// pre($refund_basic);


// pre($cs_basic);
//반품상품목록
$sql = "SELECT orp.*, p.productname, p.productcode, p.tinyimage, p.pr_type, op.idx AS op_idx, op.price_end, op.option_type, op.option_code, op.order_status, op.cs_type, op.cs_status, op.cs_flag, op.coupon_issue_no, op.coupon_discount FROM  {$tbl_orp} AS orp LEFT JOIN {$tbl_op} AS op ON(orp.order_product_idx=op.idx) LEFT JOIN {$tbl_p} AS p ON(op.productcode = p.productcode) WHERE orp.return_idx='{$cs_idx}'";

// echo $sql;
$rs = $Order->adodb->Execute($sql);
$cnt = array(
	'active'=>'0'
);

$return_product = 0; //반품신청 상품총액
$refund_list = array();
$coupon = array();
$delivery_charger = 'buyer';//



while($row = $rs->FetchRow()) {
	//기존주문옵션
	$option = $Product->getOptionRow($row['option_code'],'option_num, option_name, (option_quantity-option_quantity_sales) AS quantity_remain'); 
	$row['option'] = $option;


	//상태값
	$status = array(
		'order_status'=>$row['order_status'],
		'cs_type'=>$row['cs_type'],
		'cs_status'=>$row['cs_status'],
		'cs_flag'=>$row['cs_flag']
	);
	$row['step'] = $Order->getStep($status);

	//단계별 개수
	if($row['cs_flag']!= 'WD') {
		if($row['cs_status']!='4') $cnt['active']++;
		$cnt['status_'.$row['cs_status']]++;
	}
	if($row['cs_flag']) $cnt['flag_'.$row['cs_flag']]++;

	
	//반품상품총액
	$return_product += $row['price_end'];

	//환불대상상품
	if($row['cs_type'].$row['cs_status'].$row['cs_flag'] == 'R3RR') {
		// pre($row);
		$refund_list[] = $row['op_idx'];

		//쿠폰사용정보
		if($row['coupon_issue_no']>0) {
			$coupon[] = array(
				'ci_no'=>$row['coupon_issue_no'], //상품쿠폰
				'discount'=>$row['coupon_discount'] //상품할인액
			); 
			$discount_coupon+=$row['coupon_discount'];
		}

		//환불금액
		$price_product += $row['price_end'];

		if($row['reason_charger'] == 'seller') $delivery_charger = 'seller';

	}

	$cs_product[] = $row;
}

//상품쿠폰할인액 제외
$price_total = $price_product-$discount_coupon;

$order_basic['pay_delivery_end'] = $order_basic['pay_delivery'] - $order_basic['coupon_delivery_discount']; //지불배송비

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

	$price_total-=$order_basic['coupon_basket_discount'];
}

// echo $price_total;


//무료배송쿠폰정보
if($order_basic['coupon_delivery'] > 0) {
	$coupon_row = $Coupon->getIssueCouponRow($order_basic['coupon_delivery']);
	$coupon_delivery = array(
		'ci_no'=>$order_basic['coupon_delivery'],
		'discount'=>$order_basic['coupon_delivery_discount'],
		'info'=>$coupon_row
	);
}

//환불가능 마일리지
$refund_mileage = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='mileage' AND etc_limit >= NOW()");
if($price_total < $refund_mileage) {
	$refund_mileage = $price_total;
}

//환불가능 포인트
$refund_point = $Order->adodb->getOne("SELECT COALESCE(SUM(etc_price),0) FROM tblorder_payment_etc WHERE order_num = '{$order_num}' AND etc_type='point' AND etc_limit >= NOW()");
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

//추천배송비계산-S
$cfg_delivery = $Product->getDeilvery(); //배송비설정
$remain_total = $order_basic['sum_end'] - $price_product; //반품처리후 잔여 주문총액

//배송비
$paid_delivery = $order_basic['pay_delivery'] - $order_basic['coupon_delivery_discount'];

//지역별 배송비 구하기
$local_deliprice = $Order->getLocalDeliveryFee($cs_basic['receiver_zipcode']);
if($local_deliprice>0){
	$calc_deliprice = $local_deliprice;
}else{
	$calc_deliprice = $cfg_delivery['deli_basefee'];
}


if($delivery_charger == 'buyer') { //구매자 귀책사유
	
	if($paid_delivery > 0) {
		$pay_delivery = $calc_deliprice*2;
	}
	else {
		$deli_miniprice = ($order_basic['pr_type']=='1')?$cfg_delivery['deli_miniprice']:$cfg_delivery['deli_miniprice_staff'];;
		if($remain_total >= $deli_miniprice) {
			$pay_delivery = $calc_deliprice;
		}
		else {
			$pay_delivery = $calc_deliprice*2;
		}
	}
}
else {
	$pay_delivery = '0';
}

if($pay_delivery == 0) { //판매자 귀책사유
	// echo 'remain_total : '.$remain_total;
	if($remain_total == 0) { //반품후 잔여 주문총액이 0인경우
		$pay_delivery = $paid_delivery*-1;
	}

}
//추천배송비계산-E

if($remain_total == 0) {
	$price_total+=$paid_delivery;
}


//에스크로 취소 변수정의 - 부분취소 관련체크는 hub_escrow에서 판단함
if($order_payment['escrow_yn'] == 'Y') {
	
	switch($order_payment['pay_method']) {
		case 'card': //카드
			$escrow_mod_type = 'STE9_C';
			break;
		case 'vcnt':
			$escrow_mod_type = 'STE9_V';
			break;
		case 'acnt':
			$escrow_mod_type = 'STE9_A';
			break;
	}
}


$assign = array(
	'cnt'=>$cnt,
	'cs'=>$cs_basic,
	'basic'=>$order_basic,
	'cfg'=>array(
		'batch'=>$batch,
	),
	'escrow'=>array(
		'mod_type'=>$escrow_mod_type
	),
	'payment'=>$order_payment,
	'price'=>array(
		'return_product'=>$return_product
	),
	'delivery'=>array(
		'pay'=>$pay_delivery, //반품배송비
		'charger'=>$delivery_charger //반품배송비 책임
	),
	'refund'=>array(
		'total'=>$price_total,
		'product'=>$price_product,
		'pg'=>$refund_pg,
		'mileage'=>$refund_mileage,
		'point'=>$refund_point,
		'remain'=>$remain_total
	),
	'coupon'=>array(
		'product'=>$coupon_product, //상품별쿠폰
		'basket'=>$coupon_basket, //장바구니쿠폰
		'delivery'=>$coupon_delivery //무료배송쿠폰
	),
	'refund_list'=>implode(',',$refund_list),
	'product'=>$cs_product
);

// pre($assign['refund']);
_render("cscenter/cs_return.pop.html", $assign, 'admin/template');

include("../copyright.php");

?>
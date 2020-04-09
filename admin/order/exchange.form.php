<?php
/**
 * 교환접수
 * 관리자 교환접수 가능단계 (배송중~배송완료)
 * 주문완료~결제완료 단계 교환은 옵션 변경프로세스로 처리함
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
$payment = $Order->setPayInfo($order_basic['pg_paymethod'], array(), 'all'); //결제수단정보
$order_basic['pg_paymethod_txt'] = $payment['name'];
$order_basic['receiver_mobile_arr'] = explode('-',$order_basic['receiver_mobile']);
$order_basic['receiver_tel_arr'] = explode('-',$order_basic['receiver_tel']);


$checked = array_arrange($_POST['checked']);


$price_return = 0;
$coupon = array();
$return_list = array(); //교환대상 상품pk
$discount_coupon = 0; //상품쿠폰할인액
$product_list = array();
foreach($checked as $v) {
	$rs = $Order->adodb->Execute("SELECT idx, price_end, productcode, coupon_issue_no, coupon_discount, option_type, option_code FROM {$tbl_op} WHERE idx IN (".$v['product'].") AND order_status>2 ORDER BY coupon_discount ASC");
	$idx=0;
	while($row = $rs->FetchRow()) {
		if($idx >= $v['count'])  break;

		//교환 상품정보
		if($row['option_type'] == 'option') {
			$p_row = $Product->getProductDetail($row['productcode']);
			$p_row['option'] = $Product->getOptionRow($row['option_code'], 'option_name');
		}
		else {
			$p_row = $Product->getProductDetail($row['option_code']);
		}
		$product_list[$row['idx']] = $p_row;

		$idx++;
	}
}


//기본배송비 정보
$cfg_delivery = $Product->getDeilvery(); //배송비설정
// pre($cfg_delivery);

//지역별 배송비 구하기
$local_deliprice = $Order->getLocalDeliveryFee($order_basic['receiver_zipcode']);
if($local_deliprice>0){
	$calc_deliprice = $local_deliprice;
}else{
	$calc_deliprice = $cfg_delivery['deli_basefee'];
}


$assign = array(
	'cfg'=>array(
		'deli'=>$cfg_delivery
	),
	'checked'=>implode(',',$return_list),
	'product'=>$product_list,
	'return'=>array(
		'total'=>$price_total,
		'product'=>$price_product,
		'mileage'=>$return_mileage,
		'point'=>$return_point
	),
	'basic'=>$order_basic,
	'gift'=>$gift,
	'calc_deliprice' => $calc_deliprice
);


_render("order/exchange.form.html", $assign, 'admin/template');
?>
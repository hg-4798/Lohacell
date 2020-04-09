<?php
/**
 * 주문서
 */

if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

$Product = new PRODUCT;
$Order = new ORDER; //주문클래스
$order_num_temp = $_GET['toid'];
if(!$order_num_temp) {
	alert_go('잘못된 경로로 접근하셨습니다.','/');
}
else {
	$order_num_temp = $Order->Dectypt_AES128CBC($order_num_temp);
}

$pr_type = ($_GET['pr_type'])?$_GET['pr_type']:'1';
$basket_group_no = $Order->getGroupNo($order_num_temp);

$where = "pr_type='{$pr_type}' AND group_no IN (".$basket_group_no.")";

//주문상품
$rs = $Order->getBasket($pr_type, $where, $basket_group_no);

if(!$rs['list']) {
	alert_go('유효하지 않은 주문서입니다.','/front/basket.php');
}

//주문서유효시간체크(분)
if(!$Order->validOrder()) {
	alert_go('주문서 유효기간이 만료되었습니다.','/front/basket.php');
}

$first = array_shift(array_values($rs['list']));
$order_title = $first['productname'];

//주문상품수(옵션중복포함)
$order_count = $Order->getTempCount($order_num_temp);
if($order_count>1) {
	$order_title.=" 포함 ".$order_count."건";
}

//사은품정보
$Gift = new GIFT;
$gift_list = $Gift->getGiftValid($rs['total']['price_end']);

//배송지정보 - 마지막주문정보
$last_order = $Order->getBasicList("order_status!='0'",1);


//쿠폰정보(사용가능한 쿠폰개수)
if(MEMID) {
	$Coupon = new COUPON;
	// 적용가능한 상품쿠폰
	$coupon_usable_product = array();;
	foreach($rs['list'] as $row) {
		$coupon_product = $Coupon->getUsableCoupon($row['productcode'], MEMID);
		$coupon_usable_product = array_merge($coupon_usable_product, array_column($coupon_product,'ci_no'));
	}
	$coupon_count['product'] = count(array_unique($coupon_usable_product)); //사용가능한 상품쿠폰개수

	// 적용가능한 장바구니쿠폰
	$coupon_bakset = $Coupon->getUsableBasketCoupon(MEMID, $rs['total']['price_end']);
	$coupon_count['basket'] = count($coupon_bakset);

	// 적용가능한 무료배송쿠폰
	$coupon_delivery = $Coupon->getUsableDeliveryCoupon(MEMID, $rs['total']['price_end']);
	$coupon_count['delivery'] = count($coupon_delivery);
}


//회원정보
if(MEMID) {
	$Member = new MEMBER;
	$member_info = $Member->getMemberRow(MEMID);
}
else $member_info = array();


//PG설정세팅
if(PG == 'NHNKCP') {
	include(DOC_ROOT.'/../_pg/NHNKCP/cfg/conf.php'); //변수설정파일
	$pg_js_url = $g_conf_js_url;
}


//배송비
$delivery = $Product->getDeilvery(); //배송비설정


//포인트 설정
if(MEMID) {
	$cfg_point = $Product->getConfig('point','section');
	$cfg_point['payable'] = 'Y'; //주문시 결제가능여부
	if($rs['total']['price_end'] < $cfg_point['usable_min_buy']) { //마일리지 결제가능여부 - 주문금액제한체크
		$cfg_point['payable'] = 'N';
	}

	if($member_info['act_point'] < $cfg_point['usable_min_accrue']) { //마일리지 결제가능여부 - 보유마일리지 제한
		$cfg_point['payable'] = 'N';
	}

	//최대사용 포인트(주문금액의 최대 99퍼센트 사용가능)
	$payable_max = $rs['total']['price_end']*$cfg_point['usable_max']/100;
	if ($member_info['act_point'] < $payable_max) $payable_max = $member_info['act_point'];
	$cfg_point['payable_max'] = $payable_max;
}
else {
	$cfg_point  = array(
		'is_use'=>'N',
		'payable'=>'N',
		'payable_max'=>0
	);
}



//마일리지 설정
if(MEMID) {
	$cfg_mileage = $Product->getConfig('mileage','section');
	
	$cfg_mileage['payable'] = 'Y'; //주문시 결제가능여부
	if($rs['total']['price_end'] < $cfg_mileage['usable_min_buy']) { //마일리지 결제가능여부 - 주문금액제한체크
		$cfg_mileage['payable'] = 'N';
	}

	if($member_info['reserve'] < $cfg_mileage['usable_min_accrue']) { //마일리지 결제가능여부 - 보유마일리지 제한
		$cfg_mileage['payable'] = 'N';
	}

	//최대사용마일리지
	$payable_max = $rs['total']['price_end']*$cfg_mileage['usable_max']/100;
	if ($member_info['reserve'] < $payable_max) $payable_max = $member_info['reserve'];
	$cfg_mileage['payable_max'] = $payable_max;

}
else {
	$cfg_mileage  = array(
		'is_use'=>'N',
		'payable'=>'N',
		'payable_max'=>'0'
	);
}


if($_GET['pr_type'] == '3') {
	$miniprice = $delivery['deli_miniprice_staff'];
}
else {
	$miniprice = $delivery['deli_miniprice'];
}


$assign = array(
	'toid'=>$order_num_temp,
	'cfg'=>array(
		'receiver_memo'=>$Order->_conts['receiver_memo'],
		'delivery'=>array(
			'basefee'=>$delivery['deli_basefee'],
			'miniprice'=>$miniprice
		),
		'point'=>$cfg_point,
		'mileage'=>$cfg_mileage
	),
	'count'=>array(
		'total'=>$order_count,
		'coupon'=>$coupon_count
	),
	'member'=>$member_info,
	'delivery'=>array(
	),
	'pg'=>array(
		'js_url'=>$pg_js_url
	),
	'last_order'=>$last_order,
	'order_title'=>$order_title,
	'gift'=>$gift_list,
	'list'=>$rs['list'],
	'total'=>$rs['total']
);

_render('order/order.html', $assign);

include('./include/bottom.php');
?>

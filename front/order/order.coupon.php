<?php
/**
 * 주문서 쿠폰적용
 * @author hjlee
 */

$Dir="../../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Order = new ORDER;
$Coupon = new COUPON;
$Product = new PRODUCT;

$order_num_temp = $_POST['toid'];
if(!$order_num_temp) {
	alert_go('잘못된 경로로 접근하셨습니다.','/');
}
else {
	$order_num_temp = $Order->Dectypt_AES128CBC($order_num_temp);
}

$id = $_POST['id'];
if($id == 'btn_coupon_delivery') {//배송비쿠폰
	$tbl = $Order->tbls['order_temp'];
	$total_price = $Order->adodb->getOne("SELECT SUM(price_end) FROM {$tbl} WHERE order_num_temp='{$order_num_temp}'"); //총주문금액
	
	$cfg_delivery = $Product->getDeilvery(); //배송비설정
	if($total_price < $cfg_delivery['deli_miniprice']) {
		
		$delivery_fee = $cfg_delivery['deli_basefee'];
		$coupon_delivery =  $Coupon->getUsableDeliveryCoupon(MEMID, $total_price); //배송비쿠폰
		foreach($coupon_delivery as &$row) {
			$row['discount'] = $delivery_fee;
		}
	}
	else $coupon_delivery = array();

	$assign = array(
		'order_num_temp'=>$order_num_temp,
		'coupon'=>array(
			'delivery'=>$coupon_delivery
		),
		'price'=>array(
			'total'=>$total_price
		)
	);

	$tmpl  = "order/order.coupon_delivery.html";
}
else {
	//상품쿠폰
	$temp_list = $Order->getTempList($order_num_temp);
	$list = array();
	$coupon_list = array();
	$total_price = 0;
	foreach($temp_list as $row) {
		
		if($row['option_type'] == 'product') continue; //추가구매상품인경우 쿠폰사용불가
		
		$group_no = $row['basket_group_no'];
		if(!array_key_exists($group_no, $list)) {
			//상품정보
			$product_info = $Product->getRowSimple($row['productcode']);
			$list[$group_no] = $product_info;
		}

		$child = $Product->getOptionRow($row['option_code']);
		$child['temp_no'] = $row['no'];

		if(!array_key_exists($row['productcode'], $coupon_list)) {
			$coupon_info = $Coupon->getUsableCoupon($row['productcode'], MEMID);
			$coupon_list[$row['productcode']] = $coupon_info;
		}

		

		$child['coupon'] = $coupon_list[$row['productcode']];
		$list[$group_no]['children'][] = $child;

		$total_price+=$product_info['endprice'];
	}

	//장바구니쿠폰 - 장바구니할인기준은 전체상품포함(일반상품+추가구매상품)
	$basket_total = $Order->getTempSum($order_num_temp);
	$coupon_basket=  $Coupon->getUsableBasketCoupon(MEMID, $basket_total);

	$assign = array(
		'id'=>$_POST['id'],
		'order_num_temp'=>$order_num_temp,
		'list'=>$list,
		'coupon'=>array(
			'delivery'=>$coupon_delivery,
			'basket'=>$coupon_basket
		),
		'price'=>array(
			'total'=>$total_price
		)
	);

	$tmpl  = "order/order.coupon.html";
}

// pre($list);




//렌더링
_render($tmpl, $assign);

?>
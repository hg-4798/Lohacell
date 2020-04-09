<?php

$Dir = '../../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Order = new ORDER;
$order_num = $_POST['order_num'];
$list_type = $_POST['list_type'];

//택배사정보
$deli_company = $Order->getDeliveryCompanyPair();

//주문상품정보
$group_by =  ($list_type == 'each')?',idx':'';
$order_product = $Order->getProductGroup("order_num='{$order_num}' AND order_status>0 AND cs_type IN('0') AND cs_status='0' ", $group_by);
$payment_info = $Order->getPaymentRow($order_num);

$last_status = 0;
if($order_product){
	$tool = array();
	$enable_status = array();
	foreach($order_product as $row) {
		switch($row['order_status']) {
			case '1': //주문완료
				
				array_push($enable_status,'2');
				$tool[] = 'cancel';
				break;
			case '2': //결제완료
				array_push($enable_status,'3','4');
				$tool[] = 'refund';
				break;
			case '3': //배송준비중
				array_push($enable_status,'2','3','4');
				$tool[] = 'refund';
				break;
			case '4': //배송중
				if($payment_info['escrow_yn']=='Y') {
					array_push($enable_status,'5','6');
				}
				else {
					array_push($enable_status,'2','3','4','5','6');
					$tool[] = 'return';
				}
				
				$tool[] = 'exchange';
				break;
			case '5': //배송완료
				if($payment_info['escrow_yn']=='Y') {
					array_push($enable_status,'6');
				}
				else {
					array_push($enable_status,'2','3','4','5','6');
				}
				
				
				$tool[] = 'return';
				$tool[] = 'exchange';
				break;
			case '6': //구매확정
				// array_push($enable_status,'2','3','4','5','6');
				
				break;
		}

		if($row['order_status'] > $last_status) $last_status = $row['order_status'];
	}

	$tool = array_unique($tool);
	$enable_status = array_unique($enable_status);
}


if($payment_info['escrow_yn'] =='Y' || $last_status == '1') {
	$batch = true;
}
else $batch = false;



// pre($enable_status);
$assign = array(
	'order_num'=>$order_num,
	'rowspan'=>count($order_product),
	'product'=>$order_product,
	'tool'=>$tool,
	'enable_status'=>$enable_status,
	'cfg'=>array(
		'batch'=>$batch,
		'deli_company'=>$deli_company
	)
	
);
_render("order/order_view.product.html", $assign, 'admin/template');
?>
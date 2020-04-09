<?php
/**
 * 주문건별조회 리스트
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


parse_str($_POST['search'], $search);
// pre($search);

$Order = NEW ORDER;

//날짜별 주문내역 검색
$tbl_op = 'tblorder_product';
$tbl_ob = 'tblorder_basic';
$tbl_p = 'tblproduct';
$tbl_pay_etc= 'tblorder_payment_etc';
$tbl_pay = $Order->tbls['order_payment'];


$start_day = strtotime($search['date_s']);
$end_day = strtotime($search['date_e']);

$diff = ceil(($end_day-$start_day)/86400)+1;
if($diff>180) {
	exit;
};

for($i=$start_day;$i<=$end_day;$i+=86400) {
	$k = date('Y-m-d', $i);
	$date_list[$k] = array();
}





$sql  = "SELECT ob.* FROM {$tbl_ob} AS ob WHERE ob.date_insert BETWEEN '{$search[date_s]} 00:00:00' AND '{$search[date_e]} 23:59:59' AND ob.order_status=2  ORDER BY idx ASC";
// echo $sql;
$rs = $Order->adodb->Execute($sql);
$list = array();
$total = array(
	'buy'=>array(
		'count_order'=>0,
		'count_product'=>0,
		'product'=>0,
		'coupon'=>0,
		'point'=>0,
		'mileage'=>0,
		'delivery'=>0,
		'pg'=>0
	),
	'refund'=>array(
		'count_order'=>0,
		'count_product'=>0,
		'product'=>0,
		'coupon'=>0,
		'point'=>0,
		'mileage'=>0,
		'delivery'=>0,
		'pg'=>0
	),
	'sales'=>array(
		'count_order'=>0,
		'count_product'=>0,
		'product'=>0,
		'coupon'=>0,
		'point'=>0,
		'mileage'=>0,
		'delivery'=>0,
		'pg'=>0
	)
);

while($row = $rs->FetchRow()) {
	$date_pay = substr($row['date_insert'],0,10);
	
	$order_num = $row['order_num'];

	//결제수단
	$payinfo = $Order->setPayInfo($row['pg_paymethod'], array(), 'all');
	$row['paymethod_txt'] = $payinfo['name'];


	$sum = $Order->adodb->getArray("SELECT CONCAT(cs_type, cs_status) AS cs, SUM(price_end) AS price_end, COUNT(*) AS cnt FROM {$tbl_op} WHERE order_num='{$order_num}' AND CONCAT(cs_type, cs_status) NOT IN ('E4') GROUP BY cs"); //상품별금액
	if(is_array($sum)) {
		$count_product_sales = 0;
		$count_product = 0;
		$buy_product = 0;
		$sales_product = 0;
		foreach($sum as $v) {
			$buy_product += $v['price_end'];
			$count_product += $v['cnt'];
			switch($v['cs']) {
				case 'R4':
				case 'C4':
				break;
				default:
					$sales_product+=$v['price_end'];
					$count_product_sales+=$v['cnt'];
				break;
			}
		}
	}
	$pay_etc = $Order->adodb->getAssoc("SELECT etc_type, SUM(etc_price_origin) FROM {$tbl_pay_etc} WHERE order_num='{$order_num}' AND (etc_type_detail!='D' OR etc_type_detail IS NULL) GROUP BY etc_type"); //사용마일리지 및 포인트
	
	$pay = $Order->adodb->getRow("SELECT amount, amount_delivery FROM {$tbl_pay} WHERE order_num='{$order_num}'"); //실결제 금액
	
	$buy = array(
		'count_order'=>1,
		'count_product'=>$count_product,
		'product'=>$buy_product,
		'coupon'=>$pay_etc['coupon'],
		'point'=>$pay_etc['point'],
		'mileage'=>$pay_etc['mileage'],
		'delivery'=>$pay['amount_delivery'],
		'pg'=>$pay['amount']
	);

	$sales = array(
		'count_order'=>($row['sum_end']>0)?1:0,
		'count_product'=>$count_product_sales,
		'product'=>$sales_product,
		'coupon'=>($row['coupon_basket_discount']+$row['coupon_product_discount']),
		'point'=>$row['use_point'],
		'mileage'=>$row['use_mileage'],
		'delivery'=>$row['pay_delivery']-$row['coupon_delivery_discount'],
		'pg'=>$row['pay_pg']
	);


	$refund = array(
		'count_order'=>$buy['count_order']-$sales['count_order'],
		'count_product'=>$buy['count_product']-$sales['count_product'],
		'product'=>$buy['product']-$sales['product'],
		'coupon'=>$buy['coupon']-$sales['coupon'],
		'point'=>$buy['point']-$sales['point'],
		'mileage'=>$buy['mileage']-$sales['mileage'],
		'delivery'=>$buy['delivery']-$sales['delivery'],
		'pg'=>$buy['pg']-$sales['pg']
	);

	//pre($refund);
	//pre($refund);


	$total['buy']['count_order']+=$buy['count_order'];
	$total['buy']['count_product']+=$buy['count_product'];
	$total['buy']['product']+=$buy['product'];
	$total['buy']['coupon']+=$buy['coupon'];
	$total['buy']['point']+=$buy['point'];
	$total['buy']['mileage']+=$buy['mileage'];
	$total['buy']['delivery']+=$buy['delivery'];
	$total['buy']['pg']+=$buy['pg'];

	$total['refund']['count_order']+=$refund['count_order'];
	$total['refund']['count_product']+=$refund['count_product'];
	$total['refund']['product']+=$refund['product'];
	$total['refund']['coupon']+=$refund['coupon'];
	$total['refund']['point']+=$refund['point'];
	$total['refund']['mileage']+=$refund['mileage'];
	$total['refund']['delivery']+=$refund['delivery'];
	$total['refund']['pg']+=$refund['pg'];

	$total['sales']['count_order']+=$sales['count_order'];
	$total['sales']['count_product']+=$sales['count_product'];
	$total['sales']['product']+=$sales['product'];
	$total['sales']['coupon']+=$sales['coupon'];
	$total['sales']['point']+=$sales['point'];
	$total['sales']['mileage']+=$sales['mileage'];
	$total['sales']['delivery']+=$sales['delivery'];
	$total['sales']['pg']+=$sales['pg'];


	if(array_key_exists($date_pay, $list) === true) {
		if(is_array($buy)) {
			foreach($buy as $k=>$v) {
				$list[$date_pay]['buy'][$k] += $v;
			}
		}

		if(is_array($refund)) {
			foreach($refund as $k=>$v) {
				$list[$date_pay]['refund'][$k] += $v;
			}
		}

		if(is_array($sales)) {
			foreach($sales as $k=>$v) {
				$list[$date_pay]['sales'][$k] += $v;
			}
		}
		
	}
	else {
		
		$list[$date_pay]['buy'] = $buy;
		$list[$date_pay]['refund'] = $refund;
		$list[$date_pay]['sales'] = $sales;
	}
	//$list[] = $row;
}

$list = array_merge($date_list, $list);



$assign = array(
	'count'=>$count,
	'list'=>$list,
	'total'=>$total
);

_render("sales/price_day.inner.html", $assign, 'admin/template');


?>
<?php
/**
 * 매출통계 > 결제수단별
 * @author 이혜진
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

$list = array(
	'card'=>array(),
	'vcnt'=>array(),
	'acnt'=>array()
);

$sql  = "SELECT ob.* FROM {$tbl_ob} AS ob WHERE ob.date_insert BETWEEN '{$search[date_s]} 00:00:00' AND '{$search[date_e]} 23:59:59' AND ob.order_status=2 ORDER BY idx DESC";
//echo $sql;
$rs = $Order->adodb->Execute($sql);

$count = $rs->recordCount();
$total = array();
while($row = $rs->FetchRow()) {

	$key = $row['pg_paymethod'];
	$order_num = $row['order_num'];

	$sum = $Order->adodb->getRow("SELECT SUM(price_end) AS price_end FROM {$tbl_op} WHERE order_num='{$order_num}' AND CONCAT(cs_type, cs_status) NOT IN ('E4')"); //상품별금액
	$pay_etc = $Order->adodb->getAssoc("SELECT etc_type, SUM(etc_price_origin) FROM {$tbl_pay_etc} WHERE order_num='{$order_num}' AND (etc_type_detail!='D' OR etc_type_detail IS NULL) GROUP BY etc_type"); //사용마일리지 및 포인트
	
	$pay = $Order->adodb->getRow("SELECT amount, amount_delivery FROM {$tbl_pay} WHERE order_num='{$order_num}'"); //실결제 금액
	
	

	$buy = array(
		'product'=>$sum['price_end'],
		'coupon'=>$pay_etc['coupon'],
		'point'=>$pay_etc['point'],
		'mileage'=>$pay_etc['mileage'],
		'delivery'=>$pay['amount_delivery'],
		'pg'=>$pay['amount']
	);

	$sales = array(
		'product'=>$row['sum_end'],
		'coupon'=>($row['coupon_basket_discount']+$row['coupon_product_discount']),
		'point'=>$row['use_point'],
		'mileage'=>$row['use_mileage'],
		'delivery'=>$row['pay_delivery']-$row['coupon_delivery_discount'],
		'pg'=>$row['pay_pg']
	);


	$refund = array(
		'product'=>$buy['product']-$sales['product'],
		'coupon'=>$buy['coupon']-$sales['coupon'],
		'point'=>$buy['point']-$sales['point'],
		'mileage'=>$buy['mileage']-$sales['mileage'],
		'delivery'=>$buy['delivery']-$sales['delivery'],
		'pg'=>$buy['pg']-$sales['pg']
	);

	$total['buy']['product']+=$buy['product'];
	$total['buy']['coupon']+=$buy['coupon'];
	$total['buy']['point']+=$buy['point'];
	$total['buy']['mileage']+=$buy['mileage'];
	$total['buy']['delivery']+=$buy['delivery'];
	$total['buy']['pg']+=$buy['pg'];

	$total['refund']['product']+=$refund['product'];
	$total['refund']['coupon']+=$refund['coupon'];
	$total['refund']['point']+=$refund['point'];
	$total['refund']['mileage']+=$refund['mileage'];
	$total['refund']['delivery']+=$refund['delivery'];
	$total['refund']['pg']+=$refund['pg'];

	$total['sales']['product']+=$sales['product'];
	$total['sales']['coupon']+=$sales['coupon'];
	$total['sales']['point']+=$sales['point'];
	$total['sales']['mileage']+=$sales['mileage'];
	$total['sales']['delivery']+=$sales['delivery'];
	$total['sales']['pg']+=$sales['pg'];


	if(array_key_exists($key, $list) === true) {
		if(is_array($sales)) {
			foreach($sales as $k=>$v) {
				$list[$key]['sales'][$k] += $v;
			}
		}

		if(is_array($buy)) {
			foreach($buy as $k=>$v) {
				$list[$key]['buy'][$k] += $v;
			}
		}

		if(is_array($refund)) {
			foreach($refund as $k=>$v) {
				$list[$key]['refund'][$k] += $v;
			}
		}
		
	}
	else {
		$list[$key]['sales'] = $sales;
		$list[$key]['buy'] = $buy;
		$list[$key]['refund'] = $refund;
	}
}

foreach($list as $k=>$v) {
	if($v['sales']['pg'] > 0 && $total['sales']['pg'] > 0) {
		$rate = round(($v['sales']['pg']/$total['sales']['pg'])*100,2);
	}
	else $rate = 0;
	$list[$k]['rate'] = $rate;
}



$assign = array(
	'count'=>$count,
	'list'=>$list,
	'total'=>$total
);


_render("sales/price_paymethod.inner.html", $assign, 'admin/template');

?>
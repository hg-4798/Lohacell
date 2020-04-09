<?php
/**
 * 주문별매출관리_ 엑셀다운로드 처리
 */

$Order = new ORDER('admin');
$xlsx_filename = "주문별매출관리_".date('YmdHis').".xlsx"; //다운로드 파일명

parse_str($g_search, $search);

//헤더
$headers = array();
$headers[] = array(
	'일자',
	'주문번호',
	'주문자',
	'상품명',
	'결제수단',
	'구매',
	'구매',
	'구매',
	'구매',
	'구매',
	'구매',
	'환불',
	'환불',
	'환불',
	'환불',
	'환불',
	'환불',
	'순매출',
	'순매출',
	'순매출',
	'순매출',
	'순매출',
	'순매출'
);

$headers[] = array(
	'일자',
	'주문번호',
	'주문자',
	'상품명',
	'결제수단',
	'상품금액',
	'쿠폰할인',
	'포인트',
	'마일리지',
	'배송비',
	'실결제액',
	'상품금액',
	'쿠폰할인',
	'포인트',
	'마일리지',
	'배송비',
	'실결제액',
	'상품금액',
	'쿠폰할인',
	'포인트',
	'마일리지',
	'배송비',
	'실결제액'
);

//날짜별 주문내역 검색
$tbl_op = 'tblorder_product';
$tbl_ob = 'tblorder_basic';
$tbl_p = 'tblproduct';
$tbl_pay_etc= 'tblorder_payment_etc';
$tbl_pay = $Order->tbls['order_payment'];

if($search['order_num']) {
	$where = " AND order_num='".$search['order_num']."'";
}
$sql  = "SELECT ob.* FROM {$tbl_ob} AS ob WHERE ob.date_insert BETWEEN '{$search[date_s]} 00:00:00' AND '{$search[date_e]} 23:59:59' AND ob.order_status=2 {$where} ORDER BY idx ASC";
//echo $sql;
$rs = $Order->adodb->Execute($sql);
$list = array();
$count = $rs->recordCount();
$total = array(
	'date'=>'합계',
	'order_num'=>'합계',
	'buyder_name'=>'합계',
	'order_title'=>'합계',
	'paymethod_name'=>'합계',
	'buy_product'=>0,
	'buy_coupon'=>0,
	'buy_point'=>0,
	'buy_mileage'=>0,
	'buy_delivery'=>0,
	'buy_pg'=>0,
	'refund_product'=>0,
	'refund_coupon'=>0,
	'refund_point'=>0,
	'refund_mileage'=>0,
	'refund_delivery'=>0,
	'refund_pg'=>0,
	'sales_product'=>0,
	'sales_coupon'=>0,
	'sales_point'=>0,
	'sales_mileage'=>0,
	'sales_delivery'=>0,
	'sales_pg'=>0
);
$key = 0;
while($row = $rs->FetchRow()) {
	
	$order_num = $row['order_num'];

	//결제수단
	$payinfo = $Order->setPayInfo($row['pg_paymethod'], array(), 'all');
	$row['paymethod_txt'] = $payinfo['name'];

	// AND CONCAT(order_status, cs_type, cs_status) NOT IN ('5E4')
	$sum = $Order->adodb->getRow("SELECT SUM(price_end) AS price_end FROM {$tbl_op} WHERE order_num='{$order_num}' AND CONCAT(cs_type, cs_status) NOT IN ('E4')"); //상품별금액
	$pay_etc = $Order->adodb->getAssoc("SELECT etc_type, SUM(etc_price_origin) FROM {$tbl_pay_etc} WHERE order_num='{$order_num}' AND (etc_type_detail!='D' OR etc_type_detail IS NULL)  GROUP BY etc_type"); //사용마일리지 및 포인트
	
	$pay = $Order->adodb->getRow("SELECT amount, amount_delivery FROM {$tbl_pay} WHERE order_num='{$order_num}'"); //실결제 금액
	
	
	//order_status>2 AND cs_type='E' AND cs_status>0

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

	$total['buy_product']+=$buy['product'];
	$total['buy_coupon']+=$buy['coupon'];
	$total['buy_point']+=$buy['point'];
	$total['buy_mileage']+=$buy['mileage'];
	$total['buy_delivery']+=$buy['delivery'];
	$total['buy_pg']+=$buy['pg'];

	$total['refund_product']+=$refund['product'];
	$total['refund_coupon']+=$refund['coupon'];
	$total['refund_point']+=$refund['point'];
	$total['refund_mileage']+=$refund['mileage'];
	$total['refund_delivery']+=$refund['delivery'];
	$total['refund_pg']+=$refund['pg'];

	$total['sales_product']+=$sales['product'];
	$total['sales_coupon']+=$sales['coupon'];
	$total['sales_point']+=$sales['point'];
	$total['sales_mileage']+=$sales['mileage'];
	$total['sales_delivery']+=$sales['delivery'];
	$total['sales_pg']+=$sales['pg'];

	$list[$key] = array(
		'date'=>substr($row['date_insert'],0,10),
		'order_num'=>$order_num,
		'buyder_name'=>$row['buyer_name'],
		'order_title'=>str_replace('&apos;', "'",(htmlspecialchars_decode($row['order_title']))),

		'paymethod_name'=>$row['paymethod_txt']
	);

	if(is_array($sales)) {
		foreach($sales as $k=>$v) {
			$list[$key]['sales_'.$k] += $v;
		}
	}

	if(is_array($buy)) {
		foreach($buy as $k=>$v) {
			$list[$key]['buy_'.$k] += $v;
		}
	}

	if(is_array($refund)) {
		foreach($refund as $k=>$v) {
			$list[$key]['refund_'.$k] += $v;
		}
	}

	$key++;
}


$list['total'] = $total;

?>
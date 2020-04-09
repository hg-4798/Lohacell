<?php
/**
 * 월별매출관리_ 엑셀다운로드 처리
 */

$Order = new ORDER('admin');
$xlsx_filename = "월별매출관리_".date('YmdHis').".xlsx"; //다운로드 파일명

parse_str($g_search, $search);

//헤더
$headers = array();
$headers[] = array(
	'월',
	'구매',
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
	'환불',
	'순매출',
	'순매출',
	'순매출',
	'순매출',
	'순매출',
	'순매출',
	'순매출'
);

$headers[] = array(
	'월',
	'주문건수',
	'주문개수',
	'상품금액',
	'쿠폰할인',
	'포인트',
	'마일리지',
	'배송비',
	'실결제액',
	'환불건수',
	'환불개수',
	'상품금액',
	'쿠폰할인',
	'포인트',
	'마일리지',
	'배송비',
	'실결제액',
	'주문건수',
	'주문개수',
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

$list = array();
for($i=1;$i<=12;$i++) {
	$list[$i] = array(
		'month'=>$i.'월',
		'buy_count_order'=>0,
		'buy_count_product'=>0,
		'buy_product'=>0,
		'buy_coupon'=>0,
		'buy_point'=>0,
		'buy_mileage'=>0,
		'buy_delivery'=>0,
		'buy_pg'=>0,
		'refund_count_order'=>0,
		'refund_count_product'=>0,
		'refund_product'=>0,
		'refund_coupon'=>0,
		'refund_point'=>0,
		'refund_mileage'=>0,
		'refund_delivery'=>0,
		'refund_pg'=>0,
		'sales_count_order'=>0,
		'sales_count_product'=>0,
		'sales_product'=>0,
		'sales_coupon'=>0,
		'sales_point'=>0,
		'sales_mileage'=>0,
		'sales_delivery'=>0,
		'sales_pg'=>0

	);
}

$sql  = "SELECT ob.* FROM {$tbl_ob} AS ob WHERE to_char(date_insert, 'YYYY')='".$search['year']."' AND ob.order_status=2  ORDER BY idx ASC";
$rs = $Order->adodb->Execute($sql);
$total = array(
	'date'=>'합계',
	'buy_count_order'=>0,
	'buy_count_product'=>0,
	'buy_product'=>0,
	'buy_coupon'=>0,
	'buy_point'=>0,
	'buy_mileage'=>0,
	'buy_delivery'=>0,
	'buy_pg'=>0,
	'refund_count_order'=>0,
	'refund_count_product'=>0,
	'refund_product'=>0,
	'refund_coupon'=>0,
	'refund_point'=>0,
	'refund_mileage'=>0,
	'refund_delivery'=>0,
	'refund_pg'=>0,
	'sales_count_order'=>0,
	'sales_count_product'=>0,
	'sales_product'=>0,
	'sales_coupon'=>0,
	'sales_point'=>0,
	'sales_mileage'=>0,
	'sales_delivery'=>0,
	'sales_pg'=>0
);

while($row = $rs->FetchRow()) {
	$key = date('n', strtotime($row['date_insert']));
	
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

	$total['buy_count_order']+=$buy['count_order'];
	$total['buy_count_product']+=$buy['count_product'];
	$total['buy_product']+=$buy['product'];
	$total['buy_coupon']+=$buy['coupon'];
	$total['buy_point']+=$buy['point'];
	$total['buy_mileage']+=$buy['mileage'];
	$total['buy_delivery']+=$buy['delivery'];
	$total['buy_pg']+=$buy['pg'];

	$total['refund_count_order']+=$refund['count_order'];
	$total['refund_count_product']+=$refund['count_product'];
	$total['refund_product']+=$refund['product'];
	$total['refund_coupon']+=$refund['coupon'];
	$total['refund_point']+=$refund['point'];
	$total['refund_mileage']+=$refund['mileage'];
	$total['refund_delivery']+=$refund['delivery'];
	$total['refund_pg']+=$refund['pg'];

	$total['sales_count_order']+=$sales['count_order'];
	$total['sales_count_product']+=$sales['count_product'];
	$total['sales_product']+=$sales['product'];
	$total['sales_coupon']+=$sales['coupon'];
	$total['sales_point']+=$sales['point'];
	$total['sales_mileage']+=$sales['mileage'];
	$total['sales_delivery']+=$sales['delivery'];
	$total['sales_pg']+=$sales['pg'];


	if(array_key_exists($key, $list) === true) {
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
	}
}
$list['total'] = $total;

?>
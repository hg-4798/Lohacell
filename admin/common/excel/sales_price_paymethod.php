<?php
/**
 * 결제수단별매출관리_ 엑셀다운로드 처리
 */

$Order = new ORDER('admin');
$xlsx_filename = "결제수단별매출관리_".date('YmdHis').".xlsx"; //다운로드 파일명

parse_str($g_search, $search);

//헤더
$headers = array(
	'결제수단',
	'구매',
	'환불',
	'순매출',
	'비율'
);

$Order = NEW ORDER;

//날짜별 주문내역 검색
$tbl_op = 'tblorder_product';
$tbl_ob = 'tblorder_basic';
$tbl_p = 'tblproduct';
$tbl_pay_etc= 'tblorder_payment_etc';
$tbl_pay = $Order->tbls['order_payment'];

$list = array(
	'card'=>array(
		'method'=>'카드결제',
		'buy_pg'=>'0',
		'refund_pg'=>'0',
		'sales_pg'=>'0',
		'rate'=>'0%'
	),
	'vcnt'=>array(
		'method'=>'가상계좌',
		'buy_pg'=>'0',
		'refund_pg'=>'0',
		'sales_pg'=>'0',
		'rate'=>'0%'
	),
	'acnt'=>array(
		'method'=>'실시간계좌이체',
		'buy_pg'=>'0',
		'refund_pg'=>'0',
		'sales_pg'=>'0',
		'rate'=>'0%'
	)
);

$sql  = "SELECT ob.* FROM {$tbl_ob} AS ob WHERE ob.date_insert BETWEEN '{$search[date_s]} 00:00:00' AND '{$search[date_e]} 23:59:59' AND ob.order_status=2 ORDER BY idx DESC";
//echo $sql;
$rs = $Order->adodb->Execute($sql);

$count = $rs->recordCount();
$total = array(
	'method'=>'합계',
	'buy_pg'=>0,
	'refund_pg'=>0,
	'sales_pg'=>0,
	'rate'=>'100%'
);
while($row = $rs->FetchRow()) {

	$key = $row['pg_paymethod'];
	
	$order_num = $row['order_num'];

	//결제수단
	//$payinfo = $Order->setPayInfo($row['pg_paymethod'], array(), 'all');
	//$row['paymethod_txt'] = $payinfo['name'];

	
	$sum = $Order->adodb->getRow("SELECT SUM(price_end) AS price_end FROM {$tbl_op} WHERE order_num='{$order_num}' AND CONCAT(cs_type, cs_status) NOT IN ('E4')"); //상품별금액
	$pay_etc = $Order->adodb->getAssoc("SELECT etc_type, SUM(etc_price_origin) FROM {$tbl_pay_etc} WHERE order_num='{$order_num}' AND (etc_type_detail!='D' OR etc_type_detail IS NULL) GROUP BY etc_type"); //사용마일리지 및 포인트
	
	$pay = $Order->adodb->getRow("SELECT amount, amount_delivery FROM {$tbl_pay} WHERE order_num='{$order_num}'"); //실결제 금액

	$buy = array(
		'pg'=>$pay['amount']
	);

	$sales = array(
		'pg'=>$row['pay_pg']
	);

	$refund = array(
		'pg'=>$buy['pg']-$sales['pg']
	);

	$total['buy_pg']+=$buy['pg'];
	$total['refund_pg']+=$refund['pg'];
	$total['sales_pg']+=$sales['pg'];


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

foreach($list as $k=>$v) {
	if($v['sales_pg'] > 0 && $total['sales_pg'] > 0) {
		$rate = round(($v['sales_pg']/$total['sales_pg'])*100,2);
	}
	else $rate = 0;
	$list[$k]['rate'] = $rate.'%';
}

$list['total'] = $total;
?>
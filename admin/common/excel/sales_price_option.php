<?php
/**
 * 품목별매출관리_ 엑셀다운로드 처리
 */

$Order = new ORDER('admin');
$xlsx_filename = "품목별매출관리_".date('YmdHis').".xlsx"; //다운로드 파일명

parse_str($g_search, $search);

//헤더
$headers = array(
	'순위',
	'상품명',
	'옵션명',
	'결제수량',
	'환불수량',
	'판매수량',
	'판매합계'
);

if($search['sw']) {
	switch($search['sf']) {
		case 'product_name':
			$field = 'p.productname';
		break;
		case 'option_name':
			$field = 'po.option_name';
		break;
	}
	$where = " AND {$field} LIKE '%".$search['sw']."%'";
}

$Product = NEW PRODUCT;

//날짜별 주문내역 검색
$tbl_op = 'tblorder_product';
$tbl_ob = 'tblorder_basic';
$tbl_p = 'tblproduct';
$tbl_po = 'tblproduct_option';
$tbl_pay_etc= 'tblorder_payment_etc';
$tbl_pay = $Order->tbls['order_payment'];

$sql  = "
SELECT
	op.productcode,
	p.productname,
	op.option_type,
	op.option_code,
	po.option_name,
	op.order_status,
	cs_type,
	cs_status,
	COUNT(*) as cnt,
	SUM(op.price_end) as price
FROM
	{$tbl_op} AS op
	left join {$tbl_ob} as ob on(op.order_num=ob.order_num)
	left join {$tbl_p} as p on(op.productcode=p.productcode)
	left join {$tbl_po} as po on(op.option_code=po.option_num::text)
WHERE
	ob.date_insert BETWEEN '{$search[date_s]} 00:00:00' AND '{$search[date_e]} 23:59:59'
	AND op.order_status >= 2
	AND CONCAT(cs_type, cs_status) NOT IN ('E4')
	{$where}
group by
	op.productcode,
	p.productname,
	op.option_type,
	op.option_code,
	po.option_name,
	op.order_status,
	op.cs_type,
	op.cs_status;
";

// echo $sql;
$rs = $Product->adodb->Execute($sql);
$list = array();
$count = $rs->recordCount();
$total = array(
	'rank'=>'합계',
	'productname'=>'합계',
	'optionname'=>'합계',
	'count_buy'=>0,
	'count_refund'=>0,
	'count_sales'=>0,
	'price'=>0
);

$rank = 1;
while($row = $rs->FetchRow()) {

	$key = $row['option_code'];

	$cs = $row['cs_type'].$row['cs_status'];

	// echo 'cs : '.$cs."<br>";
	//환불체크(반품완료, 취소완료, 교환취소완료)
	switch($cs) {
		case 'R4': //반품완료
		case 'C4': //취소완료
		//case 'E4': //교환취소완료
			$count_refund = $row['cnt'];
			$price = 0;
			break;
		
		default :
			$count_refund  = 0;

			$price = $row['price'];
			break;
	}

	$count_buy = $row['cnt'];

	if(array_key_exists($key, $list) === true) {
		$v = $list[$key];

		$list[$key]['count_buy'] = $v['count_buy']+$count_buy;
		$list[$key]['count_refund'] = $v['count_refund']+$count_refund;
		$count_sales = $list[$key]['count_buy']-$list[$key]['count_refund'];
		$list[$key]['count_sales'] = $count_sales;
		$list[$key]['price'] = $v['price']+$price;

	}
	else {
		if($row['option_type'] == 'product') {
			$product_info = $Product->getRowSimple($row['option_code']);
			
			$product_name = $product_info['productname'];
			$product_code = $row['option_code'];
			$option_name = '추가구매상품';
			$option_code = '';
		}
		else {
			$product_name = $row['productname'];
			$product_code = $row['productcode'];
			$option_name = $row['option_name'];
			$option_code = $row['option_code'];
		}
		$count_buy = $row['cnt'];
		$list[$key] = array(
			'product_name'=>$product_name,
			'option_name'=>$option_name,
			'count_buy'=>$count_buy,
			'count_refund'=>$count_refund,
			'count_sales'=>$count_sales,
			'price'=>$row['price']
		);
	}


	$total['count_buy']+=$count_buy;
	$total['count_refund']+=$count_refund;
	$total['count_sales']+=$count_sales;
	$total['price']+=$price;


}


uasort($list, 'rank'); //결제수량순서대로 정렬
$rank = 1;
foreach($list as $k=>&$v) {
	array_unshift($v, $rank);
	$rank++;
	// $list[$k]['rank'] = $rank++;
}

$list['total'] = $total;


function rank($a, $b) {
	global $search;
	$column = $search['sort'];
	if($a[$column] == $b[$column]) {
		return 0;
	}
	return ($a[$column] < $b[$column]) ? 1 : -1;
}



?>
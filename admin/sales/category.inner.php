<?php
/**
 * 주문건별조회 리스트
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


parse_str($_POST['search'], $search);


$Order = NEW ORDER;
$Category = new CATEGORYLIST;
//날짜별 주문내역 검색
$tbl_op = 'tblorder_product';
$tbl_ob = 'tblorder_basic';
$tbl_p = 'tblproduct';
$tbl_pay_etc= 'tblorder_payment_etc';
$tbl_pay = $Order->tbls['order_payment'];

$start_day = strtotime($search['date_s']);
$end_day = strtotime($search['date_e']);

$diff = ceil(($end_day-$start_day)/86400)+1;
if($diff>90) {
	exit;
};


//카테고리정보
$categories_rs = $Category->getAll();
$categories = array();
foreach($categories_rs as $k=>$v) {
	$categories[$v['code_all']] = array(
		'name'=>$v['code_name']
	);

	if($v['code_depth']>2) {
		$parent_code = str_pad(substr($v['code_all'], 0, ($v['code_depth']-1)*3), 12, '0');
		$categories[$v['code_all']]['parent'] = $categories[$parent_code]['name'];
	}
}

//카테고리 검색
$search_category = array_filter($search['category']);
if($search_category) {
	$sc = array_pop($search_category);
}

if($sc) {
	$sc = rtrim($sc,'0');
	$where = " AND pl.c_category LIKE '{$sc}%'";
}

$sql  = "
SELECT
	pl.c_category,
	op.order_status,
	cs_type,
	cs_status,
	COUNT(*) as cnt,
	SUM(op.price_end) as price
FROM
	{$tbl_op} AS op
	left join tblproductlink as pl on(op.productcode = pl.c_productcode)
WHERE
	op.date_insert BETWEEN '{$search[date_s]} 00:00:00' AND '{$search[date_e]} 23:59:59'
	AND op.order_status >= 2
	AND CONCAT(cs_type, cs_status) NOT IN ('E4')
	{$where}
group by
	pl.c_category,
	op.order_status,
	op.cs_type,
	op.cs_status;
"; //교환취소완료는 제외처리

$rs = $Order->adodb->Execute($sql);
$list = array();
$total = array(
	'buy'=>array(
		'count'=>0,
		'price'=>0
	),
	'refund'=>array(
		'count'=>0,
		'price'=>0
	),
	'sales'=>array(
		'count'=>0,
		'price'=>0
	)
);

while($row = $rs->FetchRow()) {
	$key = $row['c_category'];
	
	//결제수단
	$cs = $row['cs_type'].$row['cs_status'];

	//환불체크(반품완료, 취소완료, 교환취소완료)
	switch($cs) {
		case 'R4': //반품완료
		case 'C4': //취소완료
			$refund_count = $row['cnt'];
			$refund_price = $row['price'];
			break;
		
		default :
			$refund_count  = 0;
			$refund_price = 0;
			break;
	}
	$buy_count = $row['cnt'];
	$buy_price = $row['price'];


	$buy = array(
		'count'=>$buy_count,
		'price'=>$buy_price
	);

	$refund = array(
		'count'=>$refund_count,
		'price'=>$refund_price
	);

	$sales = array(
		'count'=>$buy_count-$refund_count,
		'price'=>$buy_price-$refund_price
	);


	
	if(array_key_exists($key, $list) === true) {
		$v = $list[$key];

		$list[$key]['buy_count'] = $v['buy_count']+$buy['count'];
		$list[$key]['buy_price'] = $v['buy_price']+$buy['price'];
		$list[$key]['refund_count'] = $v['refund_count']+$refund['count'];
		$list[$key]['refund_price'] = $v['refund_price']+$refund['price'];
		$list[$key]['sales_count'] = $list[$key]['buy_count']-$list[$key]['refund_count'];
		$list[$key]['sales_price'] = $list[$key]['buy_price']-$list[$key]['refund_price'];
	}
	else {

		$category_nav = ($categories[$key]['parent'])?$categories[$key]['parent'].'<i class="fa fa-caret-right" style="padding:0px 5px"></i>'.$categories[$key]['name']:$categories[$key]['name'];
		$list[$key] = array(
			'category_code'=>$key,
			'category_name'=>$categories[$key]['name'],
			'category_nav'=>$category_nav,
			'buy_count'=>$buy['count'],
			'buy_price'=>$buy['price'],
			'refund_count'=>$refund['count'],
			'refund_price'=>$refund['price'],
			'sales_count'=>$sales['count'],
			'sales_price'=>$sales['price']
		);
	}


	$total['buy']['count']+=$buy['count'];
	$total['buy']['price']+=$buy['price'];

	$total['refund']['count']+=$refund['count'];
	$total['refund']['price']+=$refund['price'];

	$total['sales']['count']+=$sales['count'];
	$total['sales']['price']+=$sales['price'];

}

uasort($list, 'rank'); //결제수량순서대로 정렬


$assign = array(
	'count'=>$count,
	'list'=>$list,
	'total'=>$total
);

_render("sales/category.inner.html", $assign, 'admin/template');


function rank($a, $b) {
	global $search;
	$column = $search['sort'];
	if($a[$column] == $b[$column]) {
		return 0;
	}
	return ($a[$column] < $b[$column]) ? 1 : -1;
}
?>
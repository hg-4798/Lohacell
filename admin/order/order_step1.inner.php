<?php
/**
 * 주문완료리스트
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


$Order = new ORDER;

$where = "op.order_status='1' AND op.cs_type='0'"; //기본검색

//검색
parse_str($_POST['search'], $search);
$where_add = $Order->adminSearch($search); //검색
$where.=$where_add;

// pre($search);
// echo $where;


$page = ($_POST['page'])?$_POST['page']:'1';
$limit = ($search['limit'])?$search['limit']:'20';

//정렬
switch($search['sort']) {
	case 'reg_desc':
	default:
		$orderby = 'date_insert DESC';
		break;
	case 'reg_asc':
		$orderby = 'date_insert ASC';
		break;
}


$tbl_op = $Order->tbls['order_product'];
$tbl_ob = $Order->tbls['order'];
$tbl_p = $Order->tbls['product'];

if($page) {
	$offset = ($page-1)*$limit;
}

$sql = "
SELECT
	op.order_num,
	op.order_status,
	MAX(op.option_type) AS option_type,
	MAX(op.option_code) AS option_code,
	MAX(p.productcode) AS productcode,
	COUNT(*) AS cnt,
	MAX(ob.date_insert) AS date_insert
FROM
	{$tbl_op} AS op
LEFT JOIN {$tbl_p} AS p ON
	(op.productcode = p.productcode)
LEFT JOIN {$tbl_ob} AS ob ON
	(ob.order_num = op.order_num)
WHERE
	{$where}
GROUP BY
	op.order_num,
	op.order_status,op.productcode, op.option_type, op.option_code
";

// echo $sql;



$count = $Order->adodb->getOne("SELECT COUNT(*) AS total FROM ({$sql}) x");

$num = $count-$offset;
$parent_key = '';
$list = array();

$num = $count-($offset);

$sql .= " ORDER BY {$orderby}, op.order_num DESC , op.option_type ASC OFFSET {$offset} LIMIT {$limit}";
// ECHO $sql;
$rs = $Order->adodb->Execute($sql);

$Product = new PRODUCT;
while($row = $rs->FetchRow()) {
	//상품정보
	if($row['option_type'] =='option') {
		$product = $Product->getRowSimple($row['productcode'], false);
		$option = $Product->getOptionRow($row['option_code'],'option_num, option_name');
	}
	else {
		$product = $Product->getRowSimple($row['option_code'], false);
		$option = array();
	}
	
	$row['product_info'] = $product;
	$row['option_info'] = $option;

	//메모
	$row['memo'] = $Order->getMemoRow($row['order_num']);

	if($parent_key != $row['order_num']) {
		$parent_key = $row['order_num'];
		$basic_info = $Order->adodb->getRow("SELECT idx, member_id, buyer_name, sum_end, pay_delivery, pay_pg, pay_total, pg_paymethod, date_insert FROM {$tbl_ob} WHERE order_num='{$parent_key}'");
		$payment_info = $Order->setPayInfo($basic_info['pg_paymethod'], array(), 'all');
		$basic_info['payment_info']= $payment_info;
		$basic_info['num'] = $num--; //순번
		//결제정보


		//주문자정보
		$list[$parent_key] = $basic_info;
		$list[$parent_key]['count'] = 1;
		$list[$parent_key]['children'][] = $row;
	}
	else {
		$list[$parent_key]['count']++;
		$list[$parent_key]['children'][] = $row;
	}
}


//페이징
$paging_config = array(
	'total'=>$count,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'OrderStep1.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();


$assign = array(
	'count'=>$count,
	'list'=>$list,
	'paging'=>$paging
);

_render("order/order_step1.inner.html", $assign, 'admin/template');
?>
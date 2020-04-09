<?php
/**
 * 반품리스트
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


$Order = new ORDER;

$where = "op.order_status > 0 AND op.cs_type='R' AND op.cs_status!='0' AND (op.cs_flag!='WD' OR op.cs_flag IS NULL)"; //기본검색(철회건 제외)

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
	case 'exchange_date_desc':
	default:
		$orderby = 'date_status_1 DESC';
		break;
	case 'exchange_date_asc':
	$orderby = 'date_status_1 ASC';
		break;
	case 'reg_desc':
		$orderby = 'date_insert DESC';
		break;
	case 'reg_asc':
		$orderby = 'date_insert ASC';
		break;
}


$tbl_op = $Order->tbls['order_product'];
$tbl_ob = $Order->tbls['order'];
$tbl_p = $Order->tbls['product'];
$tbl_orb = $Order->tbls['order_return']; //반품정보
$tbl_orp = $Order->tbls['order_return_product']; //반품상품정보

if($page) {
	$offset = ($page-1)*$limit;
}

/*
$sql = "
SELECT
	op.cs_idx,
	op.order_status,
	op.cs_type,
	op.cs_status,
	op.cs_flag,
	MAX(op.order_num) AS order_num,
	MAX(op.option_type) AS option_type,
	MAX(op.option_code) AS option_code,
	MAX(p.productcode) AS productcode,
	COUNT(*) AS cnt,
	MAX(ob.date_insert) AS date_insert,
	MAX(orb.date_status_1) AS date_status_1,
	MAX(orp.reason) AS reason,
	MAX(orp.reason_charger) AS reason_charger
FROM
	{$tbl_op} AS op
LEFT JOIN {$tbl_p} AS p ON
	(op.productcode = p.productcode)
LEFT JOIN {$tbl_ob} AS ob ON
	(ob.order_num = op.order_num)
LEFT JOIN {$tbl_orb} AS orb ON
	(op.cs_idx = orb.idx)
LEFT JOIN {$tbl_orp} AS orp ON 
	(op.idx = orp.order_product_idx)
WHERE
	{$where}
GROUP BY
	op.cs_idx, op.order_status,
	op.cs_type, op.cs_status, op.cs_flag, op.productcode, op.option_type, op.option_code
";
*/

$sql = "
SELECT
	op.cs_idx,
	op.order_status,
	op.cs_type,
	op.cs_status,
	op.cs_flag,
	MAX(op.order_num) AS order_num,
	MAX(op.option_type) AS option_type,
	MAX(op.option_code) AS option_code,
	MAX(p.productcode) AS productcode,
	COUNT(*) AS cnt,
	MAX(ob.date_insert) AS date_insert,
	MAX(orb.date_status_1) AS date_status_1,
	MAX(orp.reason) AS reason,
	MAX(orp.reason_charger) AS reason_charger
FROM
	{$tbl_op} AS op
LEFT JOIN {$tbl_p} AS p ON
	(op.productcode = p.productcode)
LEFT JOIN {$tbl_ob} AS ob ON
	(ob.order_num = op.order_num)
LEFT JOIN {$tbl_orb} AS orb ON
	(op.cs_idx = orb.idx)
LEFT JOIN {$tbl_orp} AS orp ON 
	(op.idx = orp.order_product_idx)
WHERE
	{$where}
GROUP BY
	op.cs_idx, op.order_status,
	op.cs_type, op.cs_status, op.cs_flag, op.productcode, op.option_type, op.option_code
";
// echo $sql;

$count = $Order->adodb->getOne("SELECT COUNT(*) AS total FROM ({$sql}) x");

$num = $count-$offset;
$parent_key = '';
$list = array();

$num = $count-($offset);

$sql .= " ORDER BY {$orderby}, op.cs_idx DESC OFFSET {$offset} LIMIT {$limit}";
// echo $sql;
$rs = $Order->adodb->Execute($sql);

$Product = new PRODUCT;
while($row = $rs->FetchRow()) {
	// pre($row);
	$data = $row;


	//상품정보
	if($row['option_type'] =='option') {
		$product = $Product->getRowSimple($row['productcode'], false);
		$option = $Product->getOptionRow($row['option_code'],'option_num, option_name');
	}
	else {
		$product = $Product->getRowSimple($row['option_code'], false);
		$option = array();
	}
	
	$data['product_info'] = $product;
	$data['option_info'] = $option;


	//메모
	$data['memo'] = $Order->getMemoRow($row['order_num']);

	$data['step_info'] = $Order->getStep($row);

	if($parent_key != $row['cs_idx']) {
		$parent_key = $row['cs_idx'];
		$info = $Order->adodb->getRow("SELECT * FROM {$tbl_orb} WHERE idx='".$parent_key."'");
		if($info['refund_idx'] > 0) {
			$refund_info = $Order->getRefundRow($info['refund_idx']);
			$info['refund'] = $refund_info;
		}

		$basic_info = $Order->adodb->getRow("SELECT idx, member_id, order_num, buyer_name, sum_end, pay_delivery, pay_pg, pay_total, pg_paymethod, date_insert FROM {$tbl_ob} WHERE order_num='".$row['order_num']."'");
		$payment_info = $Order->setPayInfo($basic_info['pg_paymethod'], array(), 'all');
		$basic_info['payment_info']= $payment_info;

		
		
		$info['basic'] = $basic_info;

		$list[$parent_key] = $info;
		$list[$parent_key]['count'] = 1;
		$list[$parent_key]['children'][] = $data;
	}
	else {
		$list[$parent_key]['count']++;
		$list[$parent_key]['children'][] = $data;
	}
}


// pre($list);


//페이징
$paging_config = array(
	'total'=>$count,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'CsReturn.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();



$assign = array(
	'count'=>$count,
	'list'=>$list,
	'paging'=>$paging
);

_render("cscenter/cs_return.inner.html", $assign, 'admin/template');
?>
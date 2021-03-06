<?php
/**
 * 취소리스트
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


$Order = new ORDER;

$where = "op.order_status > 0 AND op.cs_type='C'"; //기본검색

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
	case 'cancel_date_desc':
	default:
		$orderby = 'date_cancel_insert DESC';
		break;
	case 'cancel_date_asc':
	$orderby = 'date_cancel_insert ASC';
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
$tbl_oc = $Order->tbls['order_cancel']; //취소정보

if($page) {
	$offset = ($page-1)*$limit;
}

$sql = "
SELECT
	op.cs_idx,
	op.order_status,
	op.cs_type,
	op.cs_status,
	op.cs_flag,
	MAX(oc.refund_idx) AS refund_idx,
	MAX(op.order_num) AS order_num,
	MAX(op.option_type) AS option_type,
	MAX(op.option_code) AS option_code,
	MAX(p.productcode) AS productcode,
	COUNT(*) AS cnt,
	MAX(ob.date_insert) AS date_insert,
	MAX(oc.date_insert) AS date_cancel_insert
FROM
	{$tbl_op} AS op
LEFT JOIN {$tbl_p} AS p ON
	(op.productcode = p.productcode)
LEFT JOIN {$tbl_ob} AS ob ON
	(ob.order_num = op.order_num)
LEFT JOIN {$tbl_oc} AS oc ON
	(op.cs_idx = oc.idx)
WHERE
	{$where}
GROUP BY
	op.cs_idx, op.order_status,
	op.cs_type, op.cs_status, op.cs_flag, op.productcode, op.option_type, op.option_code
";


$count = $Order->adodb->getOne("SELECT COUNT(*) AS total FROM ({$sql}) x");

$num = $count-$offset;
$parent_key = '';
$list = array();

$num = $count-($offset);

$sql .= " ORDER BY {$orderby}, op.cs_idx DESC OFFSET {$offset} LIMIT {$limit}";
//  echo $sql;
$rs = $Order->adodb->Execute($sql);

$Product = new PRODUCT;
while($row = $rs->FetchRow()) {
	// pre($row);

	$data['cnt'] = $row['cnt'];
	$data['cs_flag'] = $row['cs_flag'];


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

	//환불금액
	$refund_info = $Order->getRefundRow($row['refund_idx']);
	$data['refund_info'] = $refund_info;


	//메모
	$data['memo'] = $Order->getMemoRow($row['order_num']);


	if($parent_key != $row['cs_idx']) {
		$parent_key = $row['cs_idx'];
		$info = $Order->adodb->getRow("SELECT * FROM {$tbl_oc} WHERE idx='".$parent_key."'");
		// pre($info);

		$basic_info = $Order->adodb->getRow("SELECT idx, member_id, order_num, buyer_name, sum_end, pay_delivery, pay_pg, pay_total, pg_paymethod, date_insert FROM {$tbl_ob} WHERE order_num='".$row['order_num']."'");
		$payment_info = $Order->setPayInfo($basic_info['pg_paymethod'], array(), 'all');
		$basic_info['payment_info']= $payment_info;

		$data['step_info'] = $Order->getStep($row);
		
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
	'url'=>'Cancel.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();



$assign = array(
	'count'=>$count,
	'list'=>$list,
	'paging'=>$paging
);

_render("cscenter/cs_cancel.inner.html", $assign, 'admin/template');
?>
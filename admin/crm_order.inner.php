<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Order = new ORDER('admin');

//검색
parse_str($_POST['search'], $search);


$where_add = $Order->adminSearch($search); //검색
$where.=$where_add;
$page = ($_POST['page'])?$_POST['page']:'1';
$limit = ($search['limit'])?$search['limit']:'5';
//print_r($where);
if($page) {
	$offset = ($page-1)*$limit;
}

$t_count = $Order->adodb->getOne("SELECT count(*) FROM tblorder_product op 
		LEFT JOIN tblorder_basic ob ON op.order_num = ob.order_num
		LEFT JOIN tblproduct p ON p.productcode = op.productcode 
		WHERE ob.member_id='".$search['id']."' AND op.order_status > 0 {$where}");

$sql = "SELECT op.*,p.productname,ob.pg_paymethod FROM tblorder_product op 
		LEFT JOIN tblorder_basic ob ON op.order_num = ob.order_num
		LEFT JOIN tblproduct p ON p.productcode = op.productcode 
		WHERE ob.member_id='".$search['id']."' AND op.order_status > 0 {$where} ORDER BY op.date_insert DESC OFFSET {$offset} LIMIT {$limit}";
$rs = $Order->adodb->Execute($sql);
//print_r($sql);
$list = array();
$no=0;
while($row_ord = $rs->FetchRow()) {
	$getStep = $Order->getStep($row_ord);
	//print_r($getStep);
	$number = ($t_count-($limit * ($page-1))-$no);

	$list[$no] = $row_ord;
	$list[$no]['no'] = $number;
	if ($row_ord['option_type'] == 'option') {
		$sub_sql = "SELECT * ";
		$sub_sql .= "FROM tblproduct_option ";
		$sub_sql .= "WHERE option_num = '" . $row_ord['option_code'] . "' ";
		$sub_row  = $Order->adodb->getRow($sub_sql);
		$list[$no]['info'] = $sub_row;
	} else if ($row_ord['option_type'] == 'product') {
		$sub_sql = "SELECT * ";
		$sub_sql .= "FROM tblproduct ";
		$sub_sql .= "WHERE productcode = '" . $row_ord['option_code'] . "' ";
		$sub_row  = $Order->adodb->getRow($sub_sql);
		$list[$no]['info'] = $sub_row;
	}
	$list[$no]['step'] = $getStep['msg'];
	$no++;
}

//페이징
$paging_config = array(
	'total'=>$t_count,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'CrmOrderList.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

//print_r($list);
$assign = array(
	'list'=>$list,
	'paging'=>$paging,
    'total' => $t_count
);
_render("order/crm_order.inner.html", $assign, 'admin/template');

?>
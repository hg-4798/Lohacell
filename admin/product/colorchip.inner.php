<?php
/**
 * 컬러칩
 * @author 이혜진
 */

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$product = new PRODUCT;
$argu = $_POST;



parse_str($_POST['search'], $search);
$page = ($argu['page'])?$argu['page']:1;
$limit = (isset($search['limit']))?$search['limit']:20;
$offset = ($page-1)*$limit;




$tbl = $product->tbls['product_color'];


$where_arr = array();
foreach($search as $field=>$v) {
	if(!$v || in_array($field, array('sf', 'date_sf')) || empty($v) || in_array($v, array('all'))) continue;
	switch($field){
		case 'sw': //검색어검색
			$sf = $search['sf'];
			$where_arr[] = "{$sf} = '{$v}'";
			//$where_arr[] = '('.implode(' OR ', $tmp).')';
			break;

		case 'use_yn': //사용여부
		case 'color_group': //컬러계열
			$where_arr[] = "{$field}='{$v}'";
			break;
	}
}

if(!empty($where_arr)) {
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}


//전체

$cnt_total = $product->adodb->getOne("SELECT COUNT(*) FROM {$tbl}");
$cnt_search = $product->adodb->getOne("SELECT COUNT(*) FROM {$tbl} {$where}");//검색개수


$sql = "SELECT * FROM {$tbl} {$where} ORDER BY date_insert DESC, idx DESC LIMIT {$limit} OFFSET {$offset}";

$rs = $product->adodb->Execute($sql);

$no = $cnt_search-(($page-1)*$limit);
$list = array();
while($row = $rs->FetchRow()) {
	$row['no'] = $no;
	// $row['product_cnt'] = $product->getLineCode($row['line_code']);
	$list[] = $row;
	$no--;
}

//페이징
$paging_config = array(
	'total'=>$cnt_search,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'ProductColor.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

$assign = array(
	'count'=>array(
		'total'=>$cnt_total,
		'search'=>$cnt_search
	),
	'list'=>$list,
	'paging'=>$paging
);

_render("product/colorchip.inner.html", $assign, 'admin/template');

?>

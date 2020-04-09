<?php

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
$product = new product();

$argu = $_POST;
parse_str($argu['search'], $assoc);
//pre($assoc);

$page = ($argu['page'])?$argu['page']:1;
$limit = (isset($assoc['limit']))?$assoc['limit']:20;
$offset = ($page-1)*$limit;


$tbl = $cfg_tbl['basket'];
$where = "WHERE to_char(date_insert, 'YYYY-MM-DD')='".$assoc['date_sf']."'";

switch($assoc['sort']) {
	case 'date_insert':
	default:
		$orderby = 'b.date_insert ASC';
		break;
	case 'productname':
		$orderby = 'p.productname ASC';
		break;
}




$cnt_search = $product->adodb->getOne("SELECT COUNT(*) FROM {$tbl} {$where}");//전체개수
$no =  $cnt_search-$offset;

$list = array();
$sql = "SELECT b.productcode, b.qty, b.date_insert, p.productname, p.endprice FROM {$tbl} AS b LEFT JOIN ".$cfg_tbl['product']." AS p ON(p.productcode=b.productcode) {$where} ORDER BY {$orderby} LIMIT {$limit} OFFSET {$offset}";

$rs = $product->adodb->Execute($sql);
while($row = $rs->FetchRow()) {
	$row['no'] = $no;
	//$row['product_info'] = $product->getRowSimple($row['productcode']);
	$list[] = $row;
}



//페이징
$paging_config = array(
	'total'=>$cnt_search,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'AnalysisBasket.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();




$assign = array(
	'count'=>array('total'=>$cnt_search, 'search'=>$cnt_search),
	'list'=>$list,
	'paging'=>$paging
);
_render("order/analysis_basket.inner.html", $assign, 'admin/template');
?>
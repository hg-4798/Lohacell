<?php
/**
 * 승인히스토리목록
 * @author 이혜진
 */

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/pagination.class.php"); //pagination class
include_once($Dir."lib/product.class.php"); //prodcut class

$product = new product();
$adodb = $product->adodb; //db connector
$argu = $_POST;

$page = $argu['page'];
$limit = 10;
$offset = ($page-1)*$limit;
$productcode = $argu['productcode'];


$tbl = $cfg_tbl['product_display_log'];
$where = "productcode='{$productcode}'";
$cnt = $adodb->getOne("SELECT COUNT(idx) FROM {$tbl} WHERE {$where}");//검색개수


//목록
$sql = "SELECT * FROM {$tbl} WHERE {$where} ORDER BY idx DESC LIMIT {$limit} OFFSET {$offset}";
$rs = $adodb->Execute($sql);

$no =  $cnt-$offset;

$list = array();
while($row = $rs->FetchRow()) {
	$row['no'] = $no;
	$list[] = $row;
	$no--;
}



//페이징
$paging_config = array(
	'total'=>$cnt,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'DisplayLog.load'
);



$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();




$assign = array(
	'class'=>array(
		'product'=>$product
	),
	'list'=>$list,
	'paging'=>$paging
);

_render("product/display_log.inner.html", $assign, 'admin/template');

?>

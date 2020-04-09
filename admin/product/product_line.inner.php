<?php
/**
 * 상품아이콘목록
 * @author 이혜진
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/pagination.class.php"); //pagination class
include_once($Dir."lib/product.class.php");

$product = new PRODUCT;


$adodb = adodb_connect(); //db connector
$argu = $_POST;

$page = $argu['page'];
$limit = (isset($argu['limit']))?$argu['limit']:10;
$offset = ($page-1)*$limit;

//전체
$cnt = $adodb->getOne("SELECT COUNT(*) FROM tblproduct_line");


$sql = "SELECT * FROM tblproduct_line ORDER BY date_insert DESC, idx DESC LIMIT {$limit} OFFSET {$offset}";

$rs = $adodb->Execute($sql);


$no = $cnt;
$list = array();
while($row = $rs->FetchRow()) {
	$row['no'] = $no;
	$row['product_cnt'] = $product->getLineCode($row['line_code']);
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
	'url'=>'ProductIcon.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

$assign = array(
	'count'=>$cnt,
	'list'=>$list,
	'paging'=>$paging
);

_render("product/product_line.inner.html", $assign, 'admin/template');

?>

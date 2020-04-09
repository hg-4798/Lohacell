<?php
/**
 * 관리자리뷰등록
 * 
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$productcode = $_GET['productcode'];

//선택한 상품정보
$Product = NEW PRODUCT;
$product_info = $Product->getRowSimple($productcode, false);
$product_option = $Product->getProductOption($productcode);
$layout = 'inc';
//pre($product_option);
$assign = array(
	'product_info'=>$product_info,
	'product_option'=>$product_option
);

include("../header.php");
_render("product/review_pop.html", $assign, 'admin/template');
include("../copyright.php");

?>
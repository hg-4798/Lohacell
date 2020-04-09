<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
/**
 * 사이즈조견표등록/수정
 * @author 이혜진
 */

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/product.class.php");

$product = new PRODUCT;
$adodb = adodb_connect();


$idx = $_POST['idx'];

$row = $adodb->getRow("SELECT * FROM tblproduct_timesale WHERE idx='{$idx}'");

//등록상품정보
$productcode_arr = explode(',', $row['productcodes']);

$list = array();
foreach($productcode_arr as $prcode) {
	$list[] = $product->getProductDetail($prcode);
}


$assign = array(
	'list'=>$list
);

//print_r($assign);

_render("product/product_timesale.product.html", $assign, 'admin/template');

?>

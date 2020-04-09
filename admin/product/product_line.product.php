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


$line_code = $_POST['line_code'];
$sql = "SELECT * FROM tblproduct WHERE line_code='{$line_code}'";
$rs = $adodb->Execute($sql);
$list = array();
while($row = $rs->FetchRow()) {
	$list[] = $product->getProductDetail($row['productcode']);
}


$assign = array(
	'list'=>$list
);

//print_r($assign);

_render("product/product_timesale.product.html", $assign, 'admin/template');

?>

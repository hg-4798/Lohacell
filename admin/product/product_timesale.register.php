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

include("../header.php");

$idx = $_GET['idx'];
if($idx>0) {
	$act = 'update';
	$row = $adodb->getRow("SELECT * FROM tblproduct_timesale WHERE idx='{$idx}'");

	if(empty($row)) {
		alert_go('존재하지 않는 할인설정입니다.','/admin/product/product_timesale.php');
	}

	//등록상품정보
	$productcode_arr = explode(',', $row['productcodes']);
    $sale_week_arr = explode(',', $row['sale_week']);
	$list = array();
	foreach($productcode_arr as $prcode) {
		$list[] = $product->getProductDetail($prcode);
	}
	$row['product'] = $list;
	foreach($sale_week_arr as $key => $val) {
        $row['sale_week'][$val] = $val;
    }
}
else {
	$act = 'insert';
	$row = array(
	);
}

$assign = array(
	'class'=>array(
		'product'=>$product
	),
	'mode'=>'register',
	'act'=>$act,
	'row'=>$row
);

//print_r($assign['row']);

_render("product/product_timesale.register.html", $assign, 'admin/template');

include("../copyright.php");
?>

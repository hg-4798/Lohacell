<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
/**
 * 사이즈조견표등록/수정
 * @author 이혜진
 */

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$product = new PRODUCT;
$adodb = adodb_connect();
$category = new CATEGORYLIST();

$idx = $_POST['idx'];
$use_part = $_POST['use_part'];

//print_r($_POST);
$row = $adodb->getRow("SELECT * FROM tblcouponinfo WHERE idx='{$idx}'");
//print_r($row);
//등록상품정보
$part_arr = explode(',', $row['part_detail']);

$list = array();
foreach($part_arr as $prcode) {
	if($use_part=='P'){
        $list[] = $product->getProductDetail($prcode);
	}else if($use_part=='L'){
        $list[] = $product->getLineDetail($prcode);
	}else if($use_part=='C'){
        $list[] = $category->getCateRow($prcode);
	}

}
//print_r($list);
//print_r($list);
$assign = array(
	'list'=>$list,
	'type'=>$use_part
);

//print_r($assign);

_render("coupon/coupon_part_detail.html", $assign, 'admin/template');

?>

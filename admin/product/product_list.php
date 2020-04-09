<?php
/**
 * 상품목록
 * @author 이혜진
 */
 //define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
//echo FCPATH;

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


include_once($Dir."lib/category.class.php");
include_once($Dir."lib/product.class.php");
include("../access.php");

include("../header.php");
$product = new PRODUCT;


//접속권한체크
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}


$category = new CATEGORYLIST;

//카테고리 정보
$cate_rs = $category->getAll("code_depth='1'");
if(is_array($cate_rs)) {
	$cate_1 = array_shift($cate_rs);
	$cate_2 = $category->getChildren($cate_1['code_all']);
}
else $cate_2 = array();


//날짜검색
$period = array();
$period[0] = date('Y-m-d');
$period[1] = date("Y-m-d");
$period[2] = date("Y-m-d",strtotime('-7 days'));
$period[3] = date("Y-m-d",strtotime('-14 days'));
$period[4] = date("Y-m-d",strtotime('-1 month'));

$product_line_list = $product->getLineList();

$assign = array(
	'cfg'=>array(
		'conts'=>$product->_conts,
		'Dir'=>$Dir,
		'period'=>$period,
		'limit'=>$arrLimit
	),
	'category'=>array(
		'c2'=>$cate_2
	),
	'product_line_list' => $product_line_list
);

_render("product/product_list.html", $assign, 'admin/template');

include("../copyright.php");
?>

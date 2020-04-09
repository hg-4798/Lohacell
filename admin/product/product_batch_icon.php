<?php
/**
 * 상품아이콘 일괄변경
 * @author 이혜진
 */

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/category.class.php");
include_once($Dir."lib/product.class.php");

$product = new PRODUCT;

//접속권한체크
include("../access.php");

####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}
#########################################################


$category = new CATEGORYLIST;
include("../header.php");

//카테고리 정보
$cate_rs = $category->getAll("code_depth='1'");
$cate_1 = array_shift($cate_rs);
$cate_2 = $category->getChildren($cate_1['code_all']);

//날짜검색
$period = array();
$period[0] = date('Y-m-d');
$period[1] = date("Y-m-d");
$period[2] = date("Y-m-d",strtotime('-7 days'));
$period[3] = date("Y-m-d",strtotime('-14 days'));
$period[4] = date("Y-m-d",strtotime('-1 month'));


//등록아이콘정보
$icon = $product->getIconAll('*','N');


$assign = array(
	'cfg'=>array(
		'conts'=>$product->conts,
		'Dir'=>$Dir,
		'period'=>$period,
		'limit'=>$arrLimit,
		'icon'=>$icon
	),
	'category'=>array(
		'c2'=>$cate_2
	)
);

_render("product/product_batch_icon.html", $assign, 'admin/template');

include("../copyright.php");
?>

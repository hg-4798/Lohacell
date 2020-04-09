<?php
/**
 * 카테고리 매출분석
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

include("../header.php");



$Product = NEW PRODUCT;
$Category = new CATEGORYLIST;

//카테고리 정보
$cate_rs = $Category->getAll("code_depth='1'");
if(is_array($cate_rs)) {
	$cate_1 = array_shift($cate_rs);
	$cate_2 = $Category->getChildren($cate_1['code_all']);
}
else $cate_2 = array();


// pre($color_group);
$assign = array(
	'search'=>array(
		'date_s'=>date('Y-m-01'),
		'date_e'=>date('Y-m-d')
	),
	'category'=>array(
		'c2'=>$cate_2
	)
);

_render("sales/category.html", $assign, 'admin/template');

include("../copyright.php");
?>

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
/**
 * 상품선택공통
 * @author 이혜진
 */

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/category.class.php");

$category = new CATEGORYLIST;

parse_str($_POST['search'], $search);

// $search['category_fix'] = '001002001000';
if($search['category_fix']) {
	$nav = $category->getNav($search['category_fix']);
}


//카테고리
$cate_rs = $category->getAll("code_depth='1'");
$cate_1 = array_shift($cate_rs);
$cate_2 = $category->getChildren($cate_1['code_all']);



$assign = array(
	'cfg'=>array(
		'limit'=>$arrLimit
	),
	'category'=>array(
		'c2'=>$cate_2
	),
	'nav'=>$nav,
	'mode'=>$search['mode'],
	'search'=>$search
);


_render("product/product_choice.html", $assign, 'admin/template');

?>

<?php
/**
 * 컬러칩등록/수정
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

include("../header.php");


$Design = new DESIGN;
$Category = new CATEGORYLIST;


//1depth 카테고리 정보
$cate_rs = $Category->getAll("code_depth='1'");
$cate_1 = array_shift($cate_rs);
$cate_2 = $Category->getChildren($cate_1['code_all']);
$cate_3 = $Category->getChildren($cate_2[0]['code_all']);


$idx = $_GET['idx'];
//print_r($categoryinfo);
if($idx) {
    $row = $Design->getCategoryBannerRow($idx);
	$dml = 'update';
    $categorycode = $row['categorycode'];
}
else {
	$dml = 'insert';
	$row = array(
		'pc_img'=>'/admin/images/product/noimg.jpg',
		'mobile_img'=>'/admin/images/product/noimg.jpg'
	);
    $categorycode = $_POST['categorycode'];
}
$categoryinfo = $Category->getCateRow($categorycode);
// pre($categoryinfo);
$assign = array(
	'dml'=>$dml,
	'row'=>$row,
    'categoryinfo'=>$categoryinfo,
    'category'=>array(
        'c2'=>$cate_2,
        'c3'=>$cate_3
    )
);


_render("design/category_banner_register.html", $assign, 'admin/template');

include("../copyright.php");
?>

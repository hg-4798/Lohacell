<?php
/**
 * 카테고리별 배너설정
 * 
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################


$Common = new COMMON;
$category = new CATEGORYLIST;

$categorycode = $_GET['code'];

//1depth 카테고리 정보
$cate_rs = $category->getAll("code_depth='1'");
$cate_1 = array_shift($cate_rs);
$cate_2 = $category->getChildren($cate_1['code_all']);
$cate_3 = $category->getChildren($cate_2[0]['code_all']);

$assign = array(
    'category'=>array(
        'c2'=>$cate_2,
        'c3'=>$cate_3
    ),
    'categorycode'=>$categorycode
);

include("./header.php");
_render("design/category_banner.html", $assign, 'admin/template');
include("./copyright.php");
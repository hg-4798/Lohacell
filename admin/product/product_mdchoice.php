<?php
#---------------------------------------------------------------
# 기본정보 설정파일을 가져온다.
#---------------------------------------------------------------
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/category.class.php");
include("../access.php");
# 파일 클래스 추가
include_once($Dir."lib/file.class.php");

##################### 페이지 접근권한 check #####################
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}
#################################################################
$category = new CATEGORYLIST;

include("../header.php");




//1depth 카테고리 정보
$cate_rs = $category->getAll("code_depth='1'");
$cate_1 = array_shift($cate_rs);
$cate_2 = $category->getChildren($cate_1['code_all']);
$cate_3 = $category->getChildren($cate_2[0]['code_all']);


$assign = array(
    'category'=>array(
        'c2'=>$cate_2,
        'c3'=>$cate_3
    )
);


_render("product/product_mdchoice.html", $assign, 'admin/template');
include("../copyright.php");
?>
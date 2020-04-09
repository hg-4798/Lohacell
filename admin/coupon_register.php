<?php
/**
 * 쿠폰등록
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
include_once("header.php");

$Member = new MEMBER;
$Product = new PRODUCT();
$category = new CATEGORYLIST;
//회원등급
$grade = $Member->getGroupPair();
$line = $Product->getLineList();


//카테고리 정보
$cate_rs = $category->getAll("code_depth='1'");
if(is_array($cate_rs)) {
    $cate_1 = array_shift($cate_rs);
    $cate_2 = $category->getChildren($cate_1['code_all']);
}
else $cate_2 = array();

$assign = array(
	'cfg'=>array(
		'grade'=>$grade,
		'line'=>$line
	),
    'category'=>array(
        'c2'=>$cate_2
    )
);

// pre($assign);

_render("coupon/coupon_register.html", $assign, 'admin/template');
include_once('copyright.php');
?>

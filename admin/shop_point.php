<?php
/**
 * 포인트설정
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
$cfg_point = $Common->getConfig('point','section'); //포인트관련 기능설정

//지급포인트
$Point = NEW POINT;
$pointset = $Point->getConfigPair();

$Member = new MEMBER;
$group_list = $Member->getMemberGroupList(); //등급설정정보

$assign = array(
	'cfg'=>array(
		'point'=>$cfg_point,
		'pointset'=>$pointset,
		'group'=>$group_list
	),
	'point'=>$point
);

include("./header.php");
_render("shop/shop_point.html", $assign, 'admin/template');
include("./copyright.php");
<?php
/**
 * 마일리지설정
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "sh-1";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################


$Common = new COMMON;
$cfg_mileage = $Common->getConfig('mileage','section'); //마일리지관련 기능설정


$Member = new MEMBER;
$group_list = $Member->getMemberGroupList(); //등급설정정보

$assign = array(
	'cfg'=>array(
		'mileage'=>$cfg_mileage,
		'group'=>$group_list
	)
);

include("./header.php");
_render("shop/shop_mileage.html", $assign, 'admin/template');
include("./copyright.php");
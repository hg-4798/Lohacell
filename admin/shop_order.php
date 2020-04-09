<?php
/**
 * 주문정책 설정
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
$cfg_order = $Common->getConfig('order','section'); //주문관련 기능설정

$assign = array(
	'order'=>$cfg_order
);


include("./header.php");
_render("shop/shop_order.html", $assign, 'admin/template');
include("./copyright.php");
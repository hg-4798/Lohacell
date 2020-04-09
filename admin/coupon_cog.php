<?php

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

$assign = array(

);

_render("coupon/coupon_cog.html", $assign, 'admin/template');
include_once('copyright.php');
?>

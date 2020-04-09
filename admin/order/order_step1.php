<?php
/**
 * 주문완료 조회
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("../access.php");
//include("calendar.php");

####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}
#########################################################
include("../header.php");

$assign = array(
	
);

_render("order/order_step1.html", $assign, 'admin/template');

include("../copyright.php");
exit;
<?php
/**
 * 메인리뷰설정
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



$assign = array(
);

include("./header.php");
_render("design/main_review.html", $assign, 'admin/template');
include("./copyright.php");
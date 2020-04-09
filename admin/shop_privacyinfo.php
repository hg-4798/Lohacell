<?php // hspark
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
$privacy = $Common->getConfig('privacy');
$privacy_buy = $Common->getConfig('privacy_buy');

$assign = array(
	'privacy'=>$privacy,
	'privacy_buy'=>$privacy_buy
);

include("./header.php");
_render("shop/privacyinfo.html", $assign, 'admin/template');
include("./copyright.php");
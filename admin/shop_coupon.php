<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "sh-3";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################


$Common = new COMMON;
$coupon = $Common->getConfig('coupon','section');

$assign = array(
	'coupon'=>$coupon
);

include("./header.php");
_render("shop/shop_coupon.html", $assign, 'admin/template');
include("./copyright.php");
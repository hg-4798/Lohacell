<?php
/**
 * 쇼핑몰 이용약관
 * 
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
$agreement = $Common->getConfig('basic');

$assign = array(
	'basic'=>$agreement
);

include("./header.php");
_render("shop/agreement.html", $assign, 'admin/template');
include("./copyright.php");
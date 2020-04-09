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

$Common = new COMMON;
$etc = $Common->getConfig(array('etc_1','etc_2','etc_3'));

$assign = array(
	'etc_1'=>$etc['etc_1'],
	'etc_2'=>$etc['etc_2'],
	'etc_3'=>$etc['etc_3']
);

include("./header.php");
_render("shop/agreement_etc.html", $assign, 'admin/template');
include("./copyright.php");
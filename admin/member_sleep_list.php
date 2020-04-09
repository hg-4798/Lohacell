<?php
/**
 * Created by PhpStorm.
 * User: 커머스랩97
 * Date: 2018-07-19
 * Time: 오후 6:31
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
);

include("./header.php");
_render("member/sleep_list.html", $assign, 'admin/template');
include("./copyright.php");
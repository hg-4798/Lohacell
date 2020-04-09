<?php
/**
 * Created by PhpStorm.
 * User: 커머스랩97
 * Date: 2018-07-19
 * Time: 오후 6:52
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "ma-2";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
    include("AccessDeny.inc.php");
    exit;
}
#########################################################

include_once("header.php");

$tbl_main	= 'tblgiftinfo';

$assign = array(

);

_render("promotion/gift_settings.html", $assign, 'admin/template');
include_once('copyright.php');
?>

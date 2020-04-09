<?php
/**
 * CS 교환
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Order = new ORDER;

include("../header.php");
include("../access.php");
####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}
#########################################################
$cnt = array();
$cnt['status_1'] = $Order->countProduct("cs_type='E' AND cs_status='1'");
$cnt['status_2'] = $Order->countProduct("cs_type='E' AND cs_status='2'");
$cnt['status_3'] = $Order->countProduct("cs_type='E' AND cs_status='3'");

$assign = array(
	'cnt'=>$cnt
);


_render("cscenter/cs_exchange.html", $assign, 'admin/template');

include("../copyright.php");
?>

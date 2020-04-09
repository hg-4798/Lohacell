<?php
/**
 * 마일리지 로그
 */
$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("../access.php");
####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}
#########################################################
$Member = new MEMBER;
$group_list = $Member->getGroupPair();

//현재가용마일리지
$valid_mileage = $Member->adodb->getOne("SELECT SUM(mileage_remain) FROM ".$Member->tbls['mileage']." WHERE mileage_valid !='N'");


$assign = array(
	'cfg'=>array(
		'group'=>$group_list,
		'mileage'=>$valid_mileage
	)
);

include("../header.php");
_render("member/mileage_log.html", $assign, 'admin/template');
include("../copyright.php");
?>
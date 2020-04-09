<?php
/**
 * 포인트 로그
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

//현재가용포인트
$valid_point = $Member->adodb->getOne("SELECT SUM(point_remain) FROM ".$Member->tbls['point']." WHERE point_valid !='N'");


$assign = array(
	'cfg'=>array(
		'group'=>$group_list,
		'point'=>$valid_point
	)
);

include("../header.php");
_render("member/point_log.html", $assign, 'admin/template');
include("../copyright.php");
?>
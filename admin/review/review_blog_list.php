<?php
/**
 * 리뷰 탑 영상/배너관리
 * @author 이기연
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("../access.php");
$Review = new REVIEW;

include("../header.php");

####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}
#########################################################

$assign = array(
);

_render("review/review_blog_list.html", $assign, 'admin/template');

include("../copyright.php");
?>
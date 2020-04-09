<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "me-1";
$MenuCode = "member";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################

$Member = new MEMBER;
$group_list = $Member->getGroupPair();

$assign = array(
	'cfg'=>array(
		'group'=>$group_list
	)
);

include("./header.php");
_render("member/list.html", $assign, 'admin/template');
include("./copyright.php");


exit;
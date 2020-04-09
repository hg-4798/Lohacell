<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");
# 파일 클래스 추가
include_once($Dir."lib/file.class.php");


####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################
$Design = new DESIGN;

include("header.php");

$list = $Design->get_all();


//debug($list);
$assign = array(
    'list'=>$list,
);	

_render("design/new_arrivals.html", $assign, 'admin/template');
?>

<?php
include("copyright.php");
?>
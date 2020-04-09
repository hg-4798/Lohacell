<?php
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("../access.php");

####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}
#########################################################

$Promotion = new PROMOTION;


$event_no = $_GET['no'];
$event_info = $Promotion->getPromoInfo($event_no);
// pre($event_info);

$assign = array(
	'event'=>$event_info
);

$layout = 'inc';
include("../header.php");
_render("promotion/event_comment.html", $assign, 'admin/template');
include("../copyright.php");


exit;

?>
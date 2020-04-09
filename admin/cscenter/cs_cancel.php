<?php
/**
 * CS 취소
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

//환불접수건 count
$cnt_rr = $Order->countProduct("cs_type='C' AND cs_flag='RR'");
$assign = array(
	'cnt'=>array(
		'rr'=>$cnt_rr
	)
);


_render("cscenter/cs_cancel.html", $assign, 'admin/template');

include("../copyright.php");
?>

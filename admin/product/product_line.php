<?php
/**
 * 상품아이콘관리
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("../header.php");
include("../access.php");
####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}
#########################################################

$assign = array();



_render("product/product_line.html", $assign, 'admin/template');

include("../copyright.php");
?>

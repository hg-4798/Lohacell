<?php
/**
 * 상품아이콘목록
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("../access.php");
$Coupon =  new COUPON();

$coupon_idx = $_GET['idx'];
####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}

$coupon_info = $Coupon->getCouponRow($coupon_idx);
$type_publish = $Coupon->type_publish;
#########################################################

include("../header.php");
$assign = array(
    'list'=>$coupon_info,
    'type_publish' => $type_publish
);

_render("coupon/coupon_detail.html", $assign, 'admin/template');

?>

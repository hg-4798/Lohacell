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
$adodb = adodb_connect();
$coupon_idx = $_GET['idx'];
####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}

$coupon_info = $Coupon->getCouponRow($coupon_idx);
$type_publish = $Coupon->type_publish;

$sql = "SELECT * FROM tblmembergroup ";
$membergroup = $adodb->getArray($sql);

#########################################################

include("../header.php");
$assign = array(
    'list'=>$coupon_info,
    'type_publish' => $type_publish,
    'membergroup'=>$membergroup
);

_render("coupon/coupon_manual_issued.html", $assign, 'admin/template');

?>

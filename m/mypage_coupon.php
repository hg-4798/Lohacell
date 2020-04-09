<?php
/**
 * 마이페이지 쿠폰
 */

if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');
$Coupon = new COUPON();
//로그인한 회원만 접근가능
Member::isMember();
$totoal = $Coupon->useCoupon(MEMID);
$used_totoal = $Coupon->usedCoupon(MEMID);
$assign = array(
        'total'=>$totoal['total'],
        'used_total'=>$used_totoal['total']
);

_render('mypage/mypage_coupon.html', $assign, DIR_M.'/template');

include('./include/bottom.php');
?>
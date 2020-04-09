<?php
/**
 * 리뷰
 */
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");


$Coupon = new COUPON();

$argu = $_POST;
$page = ($argu['page'])?$argu['page']:1;
$limit = 20;
$offset = ($page-1)*$limit;


$list = $Coupon->usedCoupon(MEMID);

//페이징
$paging_config = array(
    'total'=>$list['total'],
    'block_size'=>10,
    'list_size'=>$limit,
    'page_current'=>$page,
    'url_type'=>'javascript',
    'url'=>'CouponList.loadPage'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();



$assign = array(
    'list'=>$list['list'],
    'total'=>$list['total'],
    'paging'=>$paging
);

_render('coupon/used_couponlist_inner.html', $assign);

if($HTML_CACHE_EVENT=="OK") ob_end_flush();
?>

<?php
#---------------------------------------------------------------
# 기본정보 설정파일을 가져온다.
#---------------------------------------------------------------
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/category.class.php");
include_once($Dir."lib/promotion.class.php");
include("../access.php");
# 파일 클래스 추가
include_once($Dir."lib/file.class.php");

##################### 페이지 접근권한 check #####################
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}
#################################################################
$category = new CATEGORYLIST;
$promotion = new PROMOTION;

include("../header.php");

$pidx = $_GET['pidx'];

$promoInfo = $promotion->getPromoInfo($pidx); //기획전정보 가져오기
$title = $promoInfo['title']; //메인 기획전 제목
$executives_yn = $promoInfo['executives_yn']; //임직원 기획전 구분 값
$promotioninfo = $promotion->getPromotionList($pidx, "Y,N");
$promotion_list = $promotioninfo['promotion_list'];
$count = $promotioninfo['promotion_cnt'];

if($count<12){
    for($i=1; $i <= 12-$count; $i++) {
        $promotion_list[] = array('display_seq' => $i+$count, 'seq'=>"new".$i);
    }
}

$assign = array(
    'pidx' => $pidx,
    'promotion_list' => $promotion_list,
    'count' => $count,
    'title' => $title,
    'executives_yn' => $executives_yn
);


_render("promotion/market_promotion_product_new.html", $assign, 'admin/template');
include("../copyright.php");
?>
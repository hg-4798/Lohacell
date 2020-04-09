<?php
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");


include('../include/top.php');
include('../include/gnb.php');

$type = $_GET['status'];
$pidx = $_GET['idx'];
$promotion = new PROMOTION;

$promoinfo = $promotion->getPromoInfo($pidx);
$promotioninfo = $promotion->getPromotionList($pidx);
$executives = $promoinfo['executives_yn'];
$prev_and_next = $promotion->getPromoPrevAndNext($type, $pidx);
if($executives=='Y') {//임직원 기획전일경우
//    @TODO 접속자의 임직원여부 확인
    if(STAFF_YN =='N' || STAFF_YN==''){
        alert_go("비정상적인 경로로 접근하셨습니다.", $Dir.MainDir."main.php");

//        alert_go("비정상적인 경로로 접근하셨습니다.", $Dir.MDir."main.php"); //모바일용
    }
}else{

}

$assign = array(
    'executives' => $executives,
    'promoinfo' => $promoinfo,
    'type'  => $type,
    'prev' => array(
        'idx'=>$prev_and_next['prev_idx'],
        'title'=>$prev_and_next['prev_title'],
        'event_type'=>$prev_and_next['prev_event_type']
    ),
    'next' => array(
        'idx'=>$prev_and_next['next_idx'],
        'title'=>$prev_and_next['next_title'],
        'event_type'=>$prev_and_next['next_event_type']
    ),
    'promotioninfo' => $promotioninfo['promotion_list']
);

_render('promotion/promo_detail.html', $assign, DIR_M.'/template');

include('../include/bottom.php');
?>
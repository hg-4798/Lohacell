<?php
/**
 * 이벤트&기획전 상세
 */
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$type = $_GET['status'];
$pidx = $_GET['idx'];

if(!$pidx){
    alert_go('','/front/promotion/event.php');
}

$promotion = new PROMOTION;

$promoinfo = $promotion->getPromoInfo($pidx);
$prev_and_next = $promotion->getPromoPrevAndNext($type,$pidx);

$assign = array(
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
    )
);

include('../../front/include/top.php');
include('../../front/include/gnb.php');
_render('promotion/event_detail.html', $assign);
include('../../front/include/bottom.php');
if($HTML_CACHE_EVENT=="OK") ob_end_flush();
?>

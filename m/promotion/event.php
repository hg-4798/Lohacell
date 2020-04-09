<?php
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

include('../include/top.php');
include('../include/gnb.php');

$promotion = new PROMOTION;
$banner_list = $promotion->getPromotionBanner(); //기획전 배너가져오기

$assign = array(
    'banner_list' => $banner_list
);
_render('promotion/event.html', $assign, DIR_M.'/template');

include('../include/bottom.php');
?>

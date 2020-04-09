<?php

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$promotion = new PROMOTION;
$banner_list = $promotion->getPromotionBanner(); //기획전 배너가져오기

$assign = array(
    'banner_list' => $banner_list
);

include('../../front/include/top.php');
include('../../front/include/gnb.php');
_render('promotion/event.html', $assign);
include('../../front/include/bottom.php');
if($HTML_CACHE_EVENT=="OK") ob_end_flush();
?>

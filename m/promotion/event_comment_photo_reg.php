<?php

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$pidx = $_POST['pidx'];
$promotion = new PROMOTION;
$promoinfo = $promotion->getPromoInfo($pidx);

$assign = array(
    'promoinfo' => $promoinfo
);

_render('promotion/event_comment_photo_reg.html', $assign, MDir.'/template');

?>

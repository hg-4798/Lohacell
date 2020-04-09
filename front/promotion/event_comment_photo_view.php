<?php

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$bcnum = $_POST['bcnum'];
$end_yn = $_POST['end_yn'];

$promotion = new PROMOTION;
$comment_detail = $promotion->getPhotoCommentDetail($bcnum);

$assign = array(
    'list' => $comment_detail,
    'end_yn'=>$end_yn
);

_render('promotion/event_comment_photo_view.html', $assign);

?>

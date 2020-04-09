<?php
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Review = new REVIEW;

$idx = $_POST['idx'];
$review_detail = $Review->getReviewDetail($idx);
//print_r($review_detail);
$assign = array(
    'review_detail' =>$review_detail,
    'url'=>array(
        'productdetail'=>'/m/productdetail.php'
    ),
);
_render('review/review_detail.html', $assign, MDir.'/template');

?>
<?php
/**
 * 리뷰
 */
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include_once($Dir.'front/include/top.php');
include_once($Dir.'front/include/gnb.php');

$Review = new Review();
$tbl = $Review->tbls['review_banner'];

$num = $_GET['num'];
$video_list = $Review->adodb->getArray("SELECT * FROM {$tbl} WHERE banner_type='T' AND banner_hidden='Y' ORDER BY sort ASC");
$review_banner_list = $Review->adodb->getArray("SELECT * FROM {$tbl} WHERE banner_type='S' AND banner_hidden='Y' ORDER BY sort ASC");
//pre($video_list);
$assign = array(
    'review_banner_list'=>$review_banner_list,
    'video_list'=>$video_list
);
if(substr($_SERVER['REMOTE_ADDR'],0,10) == "218.234.32" || $_SERVER['REMOTE_ADDR'] == "59.9.185.17"){
    _render('review/reviewlist2.html', $assign);
}else {
    _render('review/reviewlist.html', $assign);
}

include_once($Dir.'front/include/bottom.php');

if($HTML_CACHE_EVENT=="OK") ob_end_flush();
?>

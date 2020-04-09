<?php
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$promotion = new PROMOTION;
$pidx = $_POST['idx'];
$event_type = $_POST['event_type'];
$page = $_POST['page'];
$limit = (isset($_POST['limit']))?$_POST['limit']:10;

//print_r($_POST);

$promoinfo = $promotion->getPromoInfo($pidx);
$end_yn = $promoinfo['end_yn'];
$comment = $promotion->getPromotionComment($pidx, $page, $limit);

//print_r($comment);

$total = $comment['cnt']; //전체개수

//페이징
$paging_config = array(
    'total'=>$total,
    'block_size'=>10,
    'list_size'=>$limit,
    'page_current'=>$page,
    'url_type'=>'javascript',
    'url'=>'EventDetail.paging'
);
$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

$assign = array(
    'pidx' => $pidx,
    'login' => array(
        'id' => MEMID
    ),
    'total'=>$total,
    'end_yn'=>$end_yn,
    'list'=>$comment['comment_list'],
    'paging'=>$paging
);

if($event_type==3){
    _render('promotion/event_comment_photo.list.html', $assign, DIR_M.'/template');
}else {
    _render('promotion/event_comment.list.html', $assign, DIR_M.'/template');
}
if($HTML_CACHE_EVENT=="OK") ob_end_flush();
?>
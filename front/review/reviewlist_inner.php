<?php
/**
 * 리뷰
 */
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Design = new DESIGN;
$Review = new REVIEW;

$argu = $_POST;

parse_str($_POST['search'], $search);
$page = ($argu['page'])?$argu['page']:1;
$limit = (isset($search['limit']))?$search['limit']:8;
$sort = $search['sort'];
$search = $search['productname'];
$offset = ($page-1)*$limit;

if($sort=='date'){
    $sort = "ORDER BY a.best_type DESC,a.date DESC ";
}else{
    $sort = "ORDER BY a.best_type DESC, a.marks DESC, a.date DESC";
}
$review_list = $Review->getTypeReview($limit,$offset,$search,$sort);

//페이징
$paging_config = array(
    'total'=>$review_list['total'],
    'block_size'=>10,
    'list_size'=>$limit,
    'page_current'=>$page,
    'url_type'=>'javascript',
    'url'=>'ReviewList.loadPage'
);
//pre($review_list);
$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();



$assign = array(
    'review_list'=>$review_list['list'],
    'total'=>$review_list['total'],
    'paging'=>$paging,
    'type'=>$type,
);
if(substr($_SERVER['REMOTE_ADDR'],0,10) == "218.234.32" || $_SERVER['REMOTE_ADDR'] == "59.9.185.17"){
    _render('review/reviewlist_inner2.html', $assign);
}else {
    _render('review/reviewlist_inner.html', $assign);
}

if($HTML_CACHE_EVENT=="OK") ob_end_flush();
?>

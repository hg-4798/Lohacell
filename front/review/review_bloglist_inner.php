<?php
/**
 * 리뷰
 */
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
$Review = new REVIEW;

$argu = $_POST;

parse_str($_POST['search'], $search);
$page = ($argu['page'])?$argu['page']:1;
$limit = (isset($search['limit']))?$search['limit']:5;
$offset = ($page-1)*$limit;
$where = "WHERE blog_hidden='Y'";

$list = $Review->getblogList($limit,$offset,$where);

//페이징
$paging_config = array(
    'total'=>$list['total'],
    'block_size'=>5,
    'list_size'=>$limit,
    'page_current'=>$page,
    'url_type'=>'javascript',
    'url'=>'ReviewList.blogLoad'
);
//pre($review_list);
$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();



$assign = array(
    'list'=>$list['list'],
    'total'=>$list['total'],
    'paging'=>$paging
);
_render('review/review_bloglist_inner.html', $assign);

if($HTML_CACHE_EVENT=="OK") ob_end_flush();
?>

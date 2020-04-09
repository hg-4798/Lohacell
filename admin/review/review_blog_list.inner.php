<?php
/**
 * 리뷰 탑 영상/배너관리
 * @author 이기연
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Review = new REVIEW;

$argu = $_POST;

parse_str($_POST['search'], $search);
$page = ($argu['page'])?$argu['page']:1;
$limit = (isset($search['limit']))?$search['limit']:20;
$offset = ($page-1)*$limit;

$list = $Review->getblogList($limit,$offset);

//페이징
$paging_config = array(
	'total'=>$list['total'],
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'BlogList.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

$assign = array(
	'list'=>$list['list'],
	'paging'=>$paging,
	'total'=>$list['total']
);
//pre($list['total']);

_render("review/review_blog_list.inner.html", $assign, 'admin/template');


?>
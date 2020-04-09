<?php
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");


$Review = new REVIEW;

$argu = $_POST;

parse_str($_POST['search'], $search);
$page = ($argu['page'])?$argu['page']:1;
$limit = (isset($search['limit']))?$search['limit']:3;
$offset = ($page-1)*$limit;
$where = "WHERE blog_hidden='Y'";

$list = $Review->getblogList($limit,$offset,$where);

$assign = array(
	'list'=>$list['list'],
	'total'=>$list['total']
);
_render('review/review_bloglist_inner.html', $assign, MDir.'/template');


?>
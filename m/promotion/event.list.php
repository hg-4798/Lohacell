<?php
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$promotion = new PROMOTION;
$argu = $_POST;
$type = $argu['type'];
parse_str($_POST['search'], $search);

$page = $argu['page'];
$limit = (isset($search['limit']))?$search['limit']:8;
//$offset = ($page-1)*$limit;

$promotion_info = $promotion->getPromoListAndCnt($type, $page, $limit);
$list = $promotion_info['list'];
$total = $promotion_info['cnt'];

//$promo_type = array('1'=>"기획전",'2'=>"댓글이벤트",'3' =>"포토이벤트");

//페이징
$paging_config = array(
    'total'=>$total,
    'block_size'=>10,
    'list_size'=>$limit,
    'page_current'=>$page,
    'url_type'=>'javascript',
    'url'=>'Event.paging'
);
$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

$assign = array(
	'type'=>$type,
	'list'=>$list,
	'paging'=>$paging
);

_render('promotion/event.list.html', $assign, DIR_M.'/template');
?>
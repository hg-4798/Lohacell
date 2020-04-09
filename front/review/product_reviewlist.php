<?php
/**
 * 리뷰
 */
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Review = new REVIEW;
$productcode = $_POST['productcode'];
$argu = $_POST;

$page = ($argu['page'])?$argu['page']:1;
$limit = (isset($search['limit']))?$search['limit']:5;
$offset = ($page-1)*$limit;
$productReview_list = $Review->productReview($productcode,'normal',$limit,$offset);
//print_r($productReview_list);
$review_auth = $Review->getAuth($productcode, '','', 'detail'); //리뷰작성권한체크
//pre($review_auth);
if(is_array($review_auth)){
	$review_auth= 1;
}

//페이징
$paging_config = array(
    'total'=>$productReview_list['total'],
    'block_size'=>10,
    'list_size'=>$limit,
    'page_current'=>$page,
    'url_type'=>'javascript',
    'url'=>'ProductDetail.reviewLoadPage'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();
$assign = array(
    'productReview_list'=>$productReview_list['list'],
    'total'=>$productReview_list['total'],
    'paging'=>$paging,
	'auth'=>$review_auth,
	'productcode'=>$productcode
);
_render('review/product_reviewlist.html', $assign);

?>

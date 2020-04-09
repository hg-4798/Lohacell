<?php

if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");


$product = new PRODUCT();
$widget = new WIDGET();

$section = $_GET['section']?:'product';
$like_id = MEMID;

$like_list_arr = $widget->getMyLikeList($section, $like_id);
/*$total = count($like_list_arr); //총개수
$like_list_str = implode("','",$like_list_arr);

$where_arr = array();
$where_arr[] = " hott_code IN ('{$like_list_str}') ";
$where = implode(' AND ',$where_arr);

$like_list = $widget->getLikeAll($where);
foreach ($like_list as $key => $val){

}
print_r($like_list);*/



$like_list = array();
foreach ($like_list_arr as $key => $val){
    $where = " productcode = '{$val}'";
    $like_list_row = $product->getProductList($where); //상품목록
    $like_list['goods'][$key] = $like_list_row['goods'][0];
}

//$like_list_str = implode("','",$like_list_arr);

//$where_arr = array();
//$where_arr[] = " productcode IN ('{$val}') ";
//$where = implode(' AND ',$where_arr);

//print_r($like_list_str);

//exit;

/*
list( $like_total) = pmysql_fetch( " SELECT COUNT(*) as count FROM tblhott_like WHERE like_id = '".MEMID."' " );

$widget = new WIDGET;
$argu = $_POST;
$type = 'ing';
parse_str($_POST['search'], $search);

$page = $argu['page'];
$limit = (isset($search['limit']))?$search['limit']:12;
//$offset = ($page-1)*$limit;

$promotion_info = $promotion->getPromoListAndCnt($type, $page, $limit);
$list = $promotion_info['list'];
$total = $promotion_info['cnt'];

$promo_type = array('1'=>"기획전",'2'=>"댓글이벤트",'3' =>"포토이벤트");

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
$paging = $pagination->getPageSet();*/

$assign = array(
	/*'type'=>$type,
	'promo_type' => $promo_type,*/
    'cfg'=>array(
        'colorchip'=>$product->_colorchip
    ),
    'url'=>array(
        'view'=>'/front/productdetail.php'
    ),
	'list'=>$like_list/*,
	'paging'=>$paging*/
);
if($section=='product') {
    _render('mypage/mypage_good.list.html', $assign);
}
if($HTML_CACHE_EVENT=="OK") ob_end_flush();
?>

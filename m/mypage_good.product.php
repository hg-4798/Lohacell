<?php
/**
 * 마이페이지 좋아요 - 상품 
 *
 */
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include_once($Dir."lib/shopdata.php");

$Product = new PRODUCT();
$widget = new WIDGET();
$section = 'product';

$like_id = MEMID;
$limit = "8";
$page = $_POST['page']?:1;
$offset = $limit*($page-1);
$section = 'product';
$like_product =$widget->getLikeAll("like_id='{$like_id}' AND section IN ('{$section}')", $offset, $limit);
$ProductTotal = $widget->countSection("like_id='{$like_id}' AND section='product'");
$like_product_list = $like_product['list'];


//페이징
$paging_config = array(
    'total'=>$ProductTotal,
    'block_size'=>10,
    'list_size'=>$limit,
    'page_current'=>$page,
    'url_type'=>'javascript',
    'url'=>'MypageGood.paging'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

$assign = array(
    'list' => array(
        'goods'=>$like_product_list
    ),
      //  $like_product_list,
    'paging' => $paging,
    'url'=>array(
        'view'=>'/m/productdetail.php'
    )
);

//pre($like_product_list);
_render('mypage/mypage_good.product.html', $assign, DIR_M.'/template');

?>

<?php
/**
 * 상품검색결과
 */


$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

//인스턴스생성
$product = new PRODUCT();
$widget = new WIDGET();
include('../front/include/top.php');
include('../front/include/gnb.php');

$page = $_GET['page']? : 1;
$sort = $_GET['sort']? : 'recent';
$search = $_GET['search'];

switch($sort) {
    case 'recent':
    default:
        $orderby="t.modifydate DESC";
        break;
    case 'sell'://인기순
        $orderby="t.sellcount DESC, t.modifydate DESC";
        break;
    case 'review': //상품평순
        $orderby="t.review_cnt DESC, t.modifydate DESC";
        break;
    case 'like': //좋아요순
        $orderby="t.pr_like_cnt DESC, t.modifydate DESC";
        break;
    case 'price_asc':
        $orderby="t.endprice ASC, t.modifydate DESC";
        break;
    case 'price_desc':
        $orderby="t.endprice DESC, t.modifydate DESC";
        break;
}

//pre($search['sort']);
$limit = 8;
$offset = $limit*($page-1);

$where_arr = array();
//pre($_GET);pre($search);
//parse_str($_GET['search'],$search);

$where_arr[] = "(productname ILIKE '%{$search}%' OR prodcode ILIKE '%{$search}%' or prkeyword ILIKE '%{$search}%' OR phrase_ad ILIKE '%{$search}%')";

$where = implode(' AND ',$where_arr);

$rs = $product->getProductListByCate('', $offset, $limit, $where, $orderby); //상품목록

//print_r($rs);

$assign = array(
    'searchword' => $search,
	'cfg'=>array(
		'colorchip'=>$product->_colorchip,
	),
	'url'=>array(
		'list'=>'/front/productlist.php',
		'view'=>'/front/productdetail.php'
	),
	'list'=>$rs
);


_render("main/search.html", $assign);
include('../front/include/bottom.php');
?>
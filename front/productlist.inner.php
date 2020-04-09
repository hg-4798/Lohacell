<?php
/**
 * 상품목록
 */

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

//인스턴스생성
$Product = new PRODUCT();
$page = $_GET['page']? : 1;
$sort = $_GET['sort']; //정렬(DEFAULT)
$code = $_GET['code']; //카테고리코드

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
$limit = 10;
$offset = $limit*($page-1);

$where_arr = array();
//pre($_GET);pre($search);
parse_str($_GET['search'],$search);

foreach($search as $f=>$v) {
	if(!$v || in_array($v, array('all'))) continue;
	$tmp_arr = array();
	//print_r($f);
	switch($f) {
		case 'search':
			$where_arr[] = "(productname ILIKE '%{$v}%' OR prodcode ILIKE '%{$v}%' or prkeyword ILIKE '%{$v}%' OR phrase_ad ILIKE '%{$v}%')";
			break;
	}
}


$where = implode(' AND ',$where_arr);


$rs = $Product->getProductListByCate($code, $offset, $limit, $where, $orderby); //상품목록

$assign = array(
	'url'=>array(
		'view'=>'/front/productdetail.php'
	),
	'list'=>$rs
);


$html = _render("product/list.inner.html", $assign,'template', true);
return_json(true, '', array('html'=>$html, 'count'=>$rs['count'],'page'=>$rs['page']));
?>


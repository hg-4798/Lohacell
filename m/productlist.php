<?php
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
$Design = new DESIGN();
$Product = new PRODUCT();
$Category = new CATEGORYLIST();

$code=$_REQUEST["code"];
$code = str_pad($code,12,'0');

include('./include/top.php');
include('./include/gnb.php');

$Product->checkGrant($code);//카테고리별 접근권한체크


//템플릿변수정의
$nav_cate = $Category->getMe($code); //현재메뉴정보
$nav = $Category->getNav($code); //네비게이션

$nav_d2 = $Category->getChildren(substr($code,0,3));
$nav_d3 = $Category->getChildren(substr($code,0,6));



$limit = 8; //1페이지 노출상품수
$rs = $Product->getProductListByCate($code,0,$limit); //상품목록
// pre($rs);exit;
//베스트상품
$best = $Product->getProductBestByCate($code);

//카테고리 배너
$main_code = substr($code,0, 6);
$category_banner_list = $Design->category_banner_list($main_code ,'main');

$assign = array(
	'code'=>$code,
	'cfg'=>array(
		'limit'=>$limit,
	),
	'url'=>array(
		'list'=>'/m/productlist.php',
		'view'=>'/m/productdetail.php'
	),
	'nav'=>array(
		'me'=>array(
			'd2'=>$Category->formatCode($code,2),
			'd3'=>$Category->formatCode($code,3)
		),
		'd2'=>$nav_d2,
		'd3'=>$nav_d3
	),
	// 'nav'=>$nav,
	'cate'=>$nav_cate,
	'best'=>$best,
	'list'=>$rs,
	'category_banner_list'=>$category_banner_list,
);

// pre($assign['nav']['me']);

_render('product/list.html', $assign, MDir.'template');
include('./include/bottom.php');
?>




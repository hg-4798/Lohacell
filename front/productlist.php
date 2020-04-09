<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

//인스턴스생성
$Product = new PRODUCT();
$category = new CATEGORYLIST();
// $widget = new Widget();
$Design = new DESIGN();


$code=$_REQUEST["code"];
$code = str_pad($code,12,'0');

if(ord($code)==0 && $code != "000000000000") {
	Header("Location:/");
	exit;
}

// $Product->sync_timesale();//기간할인적용

$Product->checkGrant($code);//카테고리별 접근권한체크


//템플릿변수정의
$nav_cate = $category->getMe($code); //현재메뉴정보
$nav = $category->getNav($code); //네비게이션

$limit = PRODUCT_LIMIT; //1페이지 노출상품수
$rs = $Product->getProductListByCate($code,0,$limit); //상품목록

//베스트상품
$best = $Product->getProductBestByCate($code);

//카테고리 배너
$main_code = substr($code,0, 6);
$category_banner_list = $Design->category_banner_list($main_code ,'main');

//pre($rs);

$assign = array(
	'code'=>$code,
	'cfg'=>array(
		'colorchip'=>$Product->_colorchip,
	),
	'url'=>array(
		'list'=>'/front/productlist.php',
		'view'=>'/front/productdetail.php'
	),
	'nav'=>$nav,
	'cate'=>$nav_cate,
	'best'=>$best,
	'list'=>$rs,
	'category_banner_list'=>$category_banner_list,
);

include('../front/include/top.php');
include('../front/include/gnb.php');

_render("product/list.html", $assign);

include('../front/include/bottom.php');
?>
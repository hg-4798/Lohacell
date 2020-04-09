<?php
/**
 * 상품등록/수정
 * @author 이혜진
 */

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("../access.php");

####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("../AccessDeny.inc.php");
	exit;
}
#########################################################

include_once($Dir."lib/product.class.php");
include_once($Dir."lib/category.class.php");
$product = new PRODUCT;
$category = new CATEGORYLIST;



//카테고리 정보
$cate_rs = $category->getAll("code_depth='1'");
$cate_1 = array_shift($cate_rs);
$cate_2 = $category->getChildren($cate_1['code_all']);


$pr_type = $_GET['prtype'];
$productcode = $_GET['productcode'];
$mode = $_GET['mode'];

$add_product_list = array();
if(ord($productcode)) {
	if($mode=='copy'){
		$act = 'insert';
	}else {
		$act = 'update';
	}
	$row = $product->getProductDetail($productcode);

	foreach(array('maximage','minimage','tinyimage') as $col) {
		$row[$col] = str_replace('?'.$product->ver,'',$row[$col]);
	}

	if($mode!='copy') {
		$add_product_arr = array_filter(explode(',', $row['add_product'])); //추가구매상품
		foreach ($add_product_arr as $add_code) {
			$add_product_list[] = $product->getRowSimple($add_code, false);
		}
	}

	$productcode = $row['productcode'];
	// $row['use_imgurl'] = 'Y'; //(strpos_array($row['maximage'], array("http://","https://"))!==false)?'Y':'N';


	//선택카테고리
	$row['cate_link'] = $product->getLink($productcode);
	$row['icon_arr'] = explode(',',$row['icon']);

	//기타이미지(10개)
	$row['image_etc'] = $product->getImageEtc($row['productcode']);

	if($mode!='copy') {
		//승인정보
		$row['display_log'] = $product->adodb->getRow("SELECT * from " . $cfg_tbl['product_display_log'] . " WHERE productcode='{$productcode}' ORDER BY idx DESC");
	}

	if($mode!='copy') {
		//재고정보
		$stock = $product->getStock($productcode);
		$row['stock'] = $stock;
	}

	//상품고시정보
	$property = $product->adodb->getArray("SELECT idx, name, contents FROM ".$cfg_tbl['product_property']." WHERE productcode='{$productcode}'");

	//상품 옵션 가져오기
	$option_list = $row['option_info'];

	if($mode=='copy'){
		$row['quantity'] = 0;
		$row['soldout'] = 'Y';
		$row['add_product_use'] = 0;
		$row['display'] = 'R';
		$row['regdate'] = '';
		$row['selldate'] = '';
		$row['modifydate'] = '';
	}

}
else {
	$act = 'insert';
	$image = array();
	for($i=1;$i<=10;$i++) {
		$field = 'primg'.str_pad($i, 2, '0', STR_PAD_LEFT);
		$image[$field] = '';
	}



	$row = array(
		'display'=>'Y', //승인여부
		'o2o_yn'=>'Y', //퀵배송가능여부
		'erp_price_yn'=>'N', //ERP가격연동
		'staff_dc_yn'=>'N', //임직원할인여부
		'naver_display'=>'Y', //네이버지식쇼핑
		'use_imgurl'=>'Y',
		'option_type'=>'N',
		'icon_arr'=>array(),
		'min_quantity'=>1,
		'max_quantity'=>-1,
		'add_product_use'=>'0',
		'image_etc'=> $image,
		'property_use'=>'y'
		//'image_etc'=>array_map(function($n) { return sprintf('primg%02d', $n); }, range(1, 10) )
	);

	$add_product_list = array(); //추가구매상품
}


//상품정보고시
$property_cfg = array(
	array(
		'idx'=>'new',
		'name'=>'제품명',
		'contents'=>''
	),
	array(
		'idx'=>'new',
		'name'=>'구성 및 용량',
		'contents'=>''
	),
	array(
		'idx'=>'new',
		'name'=>'기능성여부',
		'contents'=>''
	),
	array(
		'idx'=>'new',
		'name'=>'제조업자',
		'contents'=>''
	),
	array(
		'idx'=>'new',
		'name'=>'제조판매업자',
		'contents'=>''
	),
	array(
		'idx'=>'new',
		'name'=>'제조국',
		'contents'=>''
	),
	array(
		'idx'=>'new',
		'name'=>'사용기한 또는 개봉 후 사용기한',
		'contents'=>''
	),
	array(
		'idx'=>'new',
		'name'=>'사용법',
		'contents'=>''
	),
	array(
		'idx'=>'new',
		'name'=>'전성분',
		'contents'=>''
	),
	array(
		'idx'=>'new',
		'name'=>'품질보증기준',
		'contents'=>''
	),
	array(
		'idx'=>'new',
		'name'=>'사용 시의 주의사항',
		'contents'=>''
	),
	array(
		'idx'=>'new',
		'name'=>'제품리뷰 적립금 안내',
		'contents'=>''
	)
);


if(!$property) {
	$property = $property_cfg;
}


// pre($add_product_list);

//등록된 옵션이 없는경우 기본값
if(count($option_list) < 1) {
	$option_list = array(
		array(
			'option_num' => 'new',
			'option_quantity_remain'=>0,
			'option_quantity_sales'=>0,
			'option_count' => 0
		)
	);
}

$product_line_list = $product->getLineList();


//아이콘설정값
$icon = $product->getIconAll("*","N");


//상품타입 정의 및 카테고리 정의
$pr_type_text = "";
$category_val = 000000000000;
switch ($pr_type){
	case 2 : $pr_type_text = "바로구매상품";
		$category_val = DIRECTBUYCATEGORY;
		break;
	case 3 : $pr_type_text = "임직원상품";
		break;
	case 4 : $pr_type_text = "추가옵션상품";
		$category_val = ADDPRODUCTCATEGORY;
		break;
	default : $pr_type_text = "일반상품";
		break;
}


//템플릿변수정의
$assign = array(
	'class'=>array(
		'product'=>$product
	),
	'act'=>$act,
	'cfg'=>array(
		'icon'=>$icon
	),
	'row'=>$row,
	'category'=>array(
			'c2'=>$cate_2
	),

	'property'=>$property, //상품고시정보
	'pr_type' => $pr_type,
	'pr_type_text' => $pr_type_text,
	'option_list' => $option_list,
	'category_val' => $category_val,
	'add_product_list' => $add_product_list, //추가구매상품
	'product_line_list' => $product_line_list, //라인정보
	'mode'=>$mode, //복사일 경우 copy
	'productcode'=>$productcode //복사일 경우 사용하는 productcode
);


$layout = ($act == 'update'|| $mode=='copy')?'inc':'default';
include("../header.php");
_render("product/product_input.html", $assign, 'admin/template');
include("../copyright.php");
<?php
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$review = new review();
$product = new PRODUCT;

$argu = $_POST;

($argu['mode']=='')?$mode="write":$argu['mode'];

if($_POST['mode']=='modify'){
	$tbl = $review->tbls['product_review'];
	$list = $review->adodb->getRow("SELECT * FROM {$tbl} WHERE num='{$argu['idx']}'");
	$argu = $list;
	$argu['productorder_idx'] = $list['num'];
	$argu['order_num'] = $list['ordercode'];
	$mode='modify';
}
$product_info = $product->getRowSimple($argu['productcode']);
if($argu['type']=='M'){
	$argu  = $review->getAuth($argu['productcode'], '','', 'detail'); //리뷰작성권한체크
	//pre($argu);
}else{
	$tbl = $review->tbls['product_option'];
	$argu['option_name'] = $review->adodb->getOne("SELECT option_name FROM {$tbl} WHERE option_num='{$argu['option_code']}'");
}


//pre($product_info);
$assign = array(
	'review_info'=>$argu,
	'product_info'=>$product_info,
	'type'=>$_POST['type'],
	'mode'=>$mode
);

_render('review/review_write.html', $assign, MDir.'/template');

?>

<?php
/**
 * 상품목록
 * @author 이혜진
 */

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/category.class.php");
include_once($Dir."lib/product.class.php");
include("access.php");

$product = new PRODUCT;

//접속권한체크
$PageCode = "or-3";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}



$category = new CATEGORYLIST;
include("header.php");

$w_arr = array('일','월','화','수','목','금','토');
$date_arr = array();
for($i=time();$i>strtotime('-7 days');$i-=86400) {

	$w = date('w',$i);

	$date_key = date('Y-m-d', $i);
	$date = $date_key;
	//$date .= " ".$w_arr[$w];
	if(date('Ymd')  == date('Ymd', $i)) $date .= '('.$w_arr[$w].', 오늘)';
	else $date .= "(".$w_arr[$w].", ".floor((time()-$i)/86400).'일전)';

	$date_arr[$date_key] = $date;
}

$assign = array(
	'date'=>$date_arr
);

_render("order/analysis_basket.html", $assign, 'admin/template');

include("copyright.php");
?>

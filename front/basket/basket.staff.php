<?php
/**
 * 임직원상품 장바구니 리스트
 * @author hjlee
 */
$Dir="../../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Basket = new basket();
$Product = new product();

if(!$_ShopInfo) {
	$_ShopInfo = new Shopinfo(isset($_COOKIE['_sinfo'])?$_COOKIE['_sinfo']:null);
}

$basket_config = $Basket->getConfig('basket','section'); //장바구니설정

$pr_type = '3';
$where = "pr_type='{$pr_type}'";
$checked_no = (isset($_POST['no']))?$_POST['no']:'all'; //선택상품

//장바구니 정보
$rs = $Basket->getBasket($pr_type, $where, $checked_no);
$list = $rs['list']; //장바구니리스트

//통계스크립트용 데이터
$stats_list = $Basket->getBasketForStats($pr_type, $where);

$delivery = $Product->getDeilvery(); //배송비정보

$assign = array(
	'cfg'=>array(
		'delivery'=>array(
			'basefee'=>$delivery['deli_basefee'],
			'miniprice'=>$delivery['deli_miniprice_staff']
		),
		'basket'=>array(
			'keep_day'=>$basket_config['keep_day']
		)
	),
	'tab_id'=>'staff',
	'member'=>array(
		'staff_yn'=>$_ShopInfo->staff_yn
	),
	'gift'=>$gift,
	'list'=>$list,
	'total'=>$rs['total'],
    'stats_list'=>$stats_list
);



//렌더링
if(empty($list)) $tpl = 'basket.empty.html';
else $tpl = 'basket.inner.html';
_render("basket/".$tpl, $assign);
return false;
?>

<?php
/**
 * 일반상품 장바구니 리스트
 * @author hjlee
 */
$Dir="../../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
// include_once($Dir."lib/shopdata.php");

$Basket = new basket();
$Product = new product();
$marketgift = new marketgift();

if(!$_ShopInfo) {
	$_ShopInfo = new Shopinfo(isset($_COOKIE['_sinfo'])?$_COOKIE['_sinfo']:null);
}

$basket_config = $Basket->getConfig('basket','section'); //장바구니설정

$pr_type = '1';
$where = "pr_type='{$pr_type}'  AND buy_yn='N'";
$checked_no = (isset($_POST['no']))?$_POST['no']:'all'; //선택상품

//장바구니 정보
$rs = $Basket->getBasket($pr_type, $where, $checked_no);
$list = $rs['list']; //장바구니리스트

//통계스크립트용 데이터
$stats_list = $Basket->getBasketForStats($pr_type, $where);

//배송비
$delivery = $Product->getDeilvery(); //배송비설정

$assign = array(
	'cfg'=>array(
		'delivery'=>array(
			'basefee'=>$delivery['deli_basefee'],
			'miniprice'=>$delivery['deli_miniprice']
		),
		'basket'=>array(
			'keep_day'=>$basket_config['keep_day']
		)
	),
	'tab_id'=>'normal',
	'member'=>array(
		'staff_yn'=>$_ShopInfo->staff_yn
	),
	'list'=>$rs['list'],
	'total'=>$rs['total'],
    'stats_list'=>$stats_list
);


//렌더링
if(empty($list)) $tpl = 'basket.empty.html';
else $tpl = 'basket.inner.html';
_render("basket/".$tpl, $assign, DIR_M.'/template');
return false;
?>

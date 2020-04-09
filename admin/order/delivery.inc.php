<?php
/**
 * 주문상품별 송장번호 등록
 */
$Dir = '../../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Order = new ORDER;

//택배사정보
$deli_company = $Order->getDeliveryCompanyPair();

$assign = array(
	'idx'=>$_POST['idx'],
	'company'=>$deli_company
);
_render("order/delivery.inc.html", $assign, 'admin/template');
?>
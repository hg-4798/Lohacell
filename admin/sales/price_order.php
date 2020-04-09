<?php
/**
 * 주문건별조회
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

include("../header.php");



$Product = NEW PRODUCT;
$color_group = $Product->getColorGroup();
// pre($color_group);
$assign = array(
	'search'=>array(
		'date_s'=>date('Y-m-01'),
		'date_e'=>date('Y-m-d')
	)
);

_render("sales/price_order.html", $assign, 'admin/template');

include("../copyright.php");
?>

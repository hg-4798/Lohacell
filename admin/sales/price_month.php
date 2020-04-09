<?php
/**
 * 매출통계 > 월별
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
	'cfg'=>array(
		'color_group'=>$color_group
	)
);

_render("sales/price_month.html", $assign, 'admin/template');

include("../copyright.php");
?>

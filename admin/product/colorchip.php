<?php
/**
 * 컬러칩관리
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

_render("product/colorchip.html", $assign, 'admin/template');

include("../copyright.php");
?>

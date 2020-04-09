<?php
/**
 * 컬러칩등록/수정
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

include("../header.php");


$Product = new PRODUCT;
$color_group = $Product->getColorGroup();

$color_code = $_GET['cc'];

if($color_code) {
	$row = $Product->getColorRow($color_code);
	$dml = 'update';
}
else {
	$dml = 'insert';
	$row = array(
		'use_yn'=>'Y'
	);
}
// pre($color_group);
$assign = array(
	'dml'=>$dml,
	'cfg'=>array(
		'color_group'=>$color_group
	),
	'row'=>$row
);


_render("product/colorchip_register.html", $assign, 'admin/template');

include("../copyright.php");
?>

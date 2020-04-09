<?php
/**
 * 일반판매가 일괄변경
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Category = new CATEGORYLIST;

include("../header.php");

$cate = $Category->getAll("code_depth='2'");
$line = $Category->getLineAll();

$pr_type = ($_GET['type']=='staff')?4:1;
$assign = array(
	'cfg'=>array(
		'category'=>$cate,
		'line'=>$line
	),
	'pr_type'=>$pr_type
);

_render("product/price_change.html", $assign, 'admin/template');

include("../copyright.php");
?>

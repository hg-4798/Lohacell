<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
/**
 * 기간할인
 * @author 이혜진
 */

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

include("../header.php");


$assign = array(
	'cfg'=>array(
		'limit'=>$arrLimit
	)
);

_render("product/product_timesale.html", $assign, 'admin/template');

include("../copyright.php");
?>

<?php
/**
 * 일반판매가 일괄변경
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


include("../header.php");


$assign = array(
	'pr_type'=>'4'
);



_render("product/price_change.html", $assign, 'admin/template');

include("../copyright.php");
?>

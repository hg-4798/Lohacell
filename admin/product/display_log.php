<?php
/**
 * 승인히스토리
 * @author 이혜진
 */

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


$assign = array(
	'productcode'=>$_POST['productcode']
);

_render("product/display_log.html", $assign, 'admin/template');

?>

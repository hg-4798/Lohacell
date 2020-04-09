<?php
/**
 * 로그
 */

$Dir = '../../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Order = new ORDER;
$idxs = $_POST['idxs'];
$list = $Order->log_product($idxs);
$assign = array(
	'list'=>$list
);
_render("order/order_view.log.html", $assign, 'admin/template');

?>
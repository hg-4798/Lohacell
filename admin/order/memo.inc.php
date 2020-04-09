<?php
/**
 * 주문서별 메모
 */
$Dir = '../../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Order = new ORDER;
$order_num = $_POST['order_num'];

$row = $Order->getMemoRow($order_num);

$assign = array(
	'order_num'=>$_POST['order_num'],
	'row'=>$row
);
_render("order/memo.inc.html", $assign, 'admin/template');
?>
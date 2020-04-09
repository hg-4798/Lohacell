<?php

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");



$order_num = $_POST['order_num'];

$assign = array(
	'order_num'=>$order_num
);
//pre($argu);
_render('mypage/delivery_change.html', $assign);

?>

<?php

if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');


$Order = new ORDER;
$order_num = $Order->Dectypt_AES128CBC($_GET['oid']);
$assign = array(
	'order_num'=>$order_num,
	'order_status'=>$_GET['order_status']
);

_render('mypage/mypage_complete.html', $assign);

include('./include/bottom.php');
?>

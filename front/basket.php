<?php
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

$Basket = new BASKET;
?>
<!-- 전환페이지 설정 -->
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script> 
<script type="text/javascript"> 
var _nasa={};
_nasa["cnv"] = wcs.cnv("3","10"); // 전환유형, 전환가치 설정해야함. 설치매뉴얼 참고
</script> 

<?
$tab_id = ($_GET['type'])?$_GET['type']:'normal'; //임직원상품:sfaff, 일반상품:normal
$pr_type = ($tab_id == 'normal')?1:3;
$Basket->validator(); //장바구니 검증

$cnt_staff = $Basket->getCount("pr_type='3'");
$cnt_normal = $Basket->getCount("pr_type='1'");

$assign = array(
	'tab_id'=>$tab_id,
	'pr_type'=>$pr_type,
	'count'=>array(
		'staff'=>$cnt_staff,
		'normal'=>$cnt_normal
	)
);


_render('basket/basket.html', $assign);

include('./include/bottom.php');
?>
<?php
include_once(DOC_ROOT."/lib/cache_main.php");
include_once(DOC_ROOT."/conf/config.php");
Header("Pragma: no-cache");

$_ShopData=new ShopData($_ShopInfo);
$_SHOPDATA = get_object_vars($_ShopData->shopdata); //상점정보

$browser = Common::getBrowser(); //브라우저정보
// pre($browser);

$shoptitle = "";
$shopkeyword = "";
$shopdescription = "";

$assign_top = array(
	'browser'=>$browser,
    'shoptitle' => $_SHOPDATA['shoptitle'],
    'shopkeyword' => $_SHOPDATA['shopkeyword'],
    'shopdescription' => $_SHOPDATA['shopdescription'],
    'countpath' => $_SHOPDATA['countpath'] //카운트 스크립트용 추가
);
_render('_include/top.html', $assign_top);

?>
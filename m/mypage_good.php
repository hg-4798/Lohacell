<?php
/**
 * 마이페이지 좋아요
 *
 */
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

include('./include/top.php');
include('./include/gnb.php');

$Product = new PRODUCT();

$assign = array(
    'cfg'=>array(
        'colorchip'=>$Product->_colorchip,
    )
);
_render('mypage/mypage_good.html', $assign, DIR_M.'/template');

include('./include/bottom.php');
?>

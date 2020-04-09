<?php
/**
 * 마이페이지 좋아요
 *
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

include('../front/include/top.php');
include('../front/include/gnb.php');

$Product = new PRODUCT();

$assign = array(
    'cfg'=>array(
        'colorchip'=>$Product->_colorchip,
    )
);
_render('mypage/mypage_good.html', $assign);

include('../front/include/bottom.php');
?>

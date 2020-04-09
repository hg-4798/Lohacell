<?php

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Coupon = new COUPON();
$productcode = $_POST['productcode'];
$coupon_list = $Coupon->getProductCoupon($productcode);



$assign = array(
    'list'=>$coupon_list
);

_render('coupon/coupon_download.html', $assign);

?>

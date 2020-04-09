<?php
/**
 * Created by PhpStorm.
 * User: 커머스랩97
 * Date: 2018-09-04
 * Time: 오후 5:45
 */
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
//echo 'VIEWPORT : '.VIEWPORT."<br>";
$Mail = new MEMBER();


$Mail->memberSleep();

exit;

//$brith = $Coupon->couponStatusBatch();

//$Coupon->expiresBatch();
//$produst_coupon = $Coupon->getProductCoupon('001005002000000004');
//echo"//상품상세------------------------------------------------------------------<br>";
//pre($produst_coupon);
//echo"//상품상세------------------------------------------------------------------<br>";

//echo "<br><br><br>";

//$use_coupon = $Coupon->getCanUseCoupon('001005002000000004','dlrldus');
//echo"//주문서 사용가능쿠폰------------------------------------------------------------------<br>";
//pre($use_coupon);
//echo"//주문서 사용가능쿠폰------------------------------------------------------------------<br>";

//$use_basket_coupon = $Coupon->getUseBasketCoupon('dlrldus',0);
//echo"//주문서 사용가능쿠폰------------------------------------------------------------------<br>";
//pre($use_basket_coupon);
//echo"//주문서 사용가능쿠폰------------------------------------------------------------------<br>";

//$getvalid = $Coupon->getValid('dlrldus','40');

//$useprocessing= $Coupon->useProcessing('1558','1234567890111');

//pre($useprocessing);


?>
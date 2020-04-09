<?php
$Dir="./";
include ($Dir."lib/init.php");
include ($Dir."lib/lib.php");
include ($Dir."lib/product.class.php");
/*
$str = "109a6c24097fb1fc907ae6acf90a1acef6431f812bbd0bc1bb";
$str = "109a6c24097fb1fc916ce6acf91007c1b8312dd758aa579b78a06b";
echo $str;
echo "<br>";
echo decrypt_authkey($str);

$str  = "PG=C|ID=ecofactory|KEY=sue7496";
echo "<br>";
echo encrypt_md5($str,"*ghkddnjsrl*");

echo "<br>";
echo decrypt_authkey(encrypt_md5($str,"*ghkddnjsrl*"));
exit;
*/

## 작업후 /public/authkey/pg 파일 열어서 5개 값 전부 변경해 줘야 됨.


## 테스트 키 발급..
echo "<hr>";
//$str = "PG=G|ID=nictest00m|PW=123456|KEY=33F49GnCMS1mFYlGXisbUDzVf2ATWCl9k3R++d5hDd3Frmuos/XLx8XhXpe+LDYAbpGKZYSwtlyyLOtS/8aD7A=="; 
$str  = "PG=A|ID=A7J0L|KEY=3cZT8zmOI2F4E4hjISXl1J7__";
echo "<br>";
echo encrypt_md5($str,"*ghkddnjsrl*");
echo "<br>";
echo decrypt_authkey(encrypt_md5($str,"*ghkddnjsrl*"));
echo "<br>";
echo encrypt_md5("OK|*|*|".$_ShopInfo->getShopurl(),"*ghkddnjsrl*");
exit;
## 109a6c26097fb1fca03fb9f1ac0238f8a749bb5b891029df426545bd6990dd402475113485d131b64d0ebf
#  deco@182.162.154.102:/public/authkey/pg   키값 변경.
## watervis@116.122.37.132:/public/paygate/A/global.lib.php		g_conf_pa_url변경
## watervis@116.122.37.132:/public/paygate/A/charge.php			payplus_test.js 변경
## deco@182.162.154.102:/public/paygate/A/charge_exe.php        payplus_test.js 변경

?>
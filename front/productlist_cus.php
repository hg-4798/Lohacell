<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");


include('../front/include/top.php');
include('../front/include/gnb.php');

_render("product/list_cus.html");

include('../front/include/bottom.php');
?>
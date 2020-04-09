<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

//렌더링
include('../front/include/top.php');
include('../front/include/gnb.php');

_render("product/detail_cus.html", $assign);

include('../front/include/bottom.php');

?>

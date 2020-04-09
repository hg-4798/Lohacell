<?php

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


include("../header.php");


$assign = array();



_render("product/product_price_change.html", $assign, 'admin/template');

include("../copyright.php");
?>

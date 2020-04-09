<?php
/**
 * 상품아이콘관리
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


include("../header.php");


$assign = array();



_render("product/product_icon.html", $assign, 'admin/template');

include("../copyright.php");
?>

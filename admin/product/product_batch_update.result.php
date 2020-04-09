<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
/**
 * 엑셀다운로드
 * @author 이혜진
 */

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


$assign = $_POST;


_render("product/product_batch_update.result.html", $assign, 'admin/template');

?>

<?php
/**
 * 매장정보
 */

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

include('../include/top.php');
include('../include/gnb.php');


$assign = array(
    'store_area'=>$store_area,
    'store_category'=>$store_category
);
_render('brand/store.html', $assign);

include('../include/bottom.php');
?>

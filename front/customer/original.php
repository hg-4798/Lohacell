<?php
/**
 * 정품안내
 */

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

include('../include/top.php');
include('../include/gnb.php');

$assign = array();
$lnb_flag = 5;
$board = 'original';
_render('customer/original.html', $assign);

include('../include/bottom.php');
?>

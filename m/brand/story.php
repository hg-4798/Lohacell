<?php
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");


include('../include/top.php');
include('../include/gnb.php');

$assign = array();
_render('brand/story.html', $assign, DIR_M.'/template');

include('../include/bottom.php');
?>

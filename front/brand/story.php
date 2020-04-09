<?php
/**
 * 브랜드스토리
 */

if(strlen($Dir)==0) $Dir="../../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

include('../include/top.php');
include('../include/gnb.php');

$assign = array();
_render('brand/story.html', $assign);

include('../include/bottom.php');

?>

<?php
/**
 * 기획전페이지
 */
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Promotion = new PROMOTION();


$assign = array();
include('../../front/include/top.php');
include('../../front/include/gnb.php');
_render('promotion/special.html', $assign);
include('../../front/include/bottom.php');
if($HTML_CACHE_EVENT=="OK") ob_end_flush();
?>

<?php

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");


$type = $_POST['type'];
$assign = array(
	'list'=>$list
);

include('../../front/include/top.php');
_render('promotion/event.winner.html', $assign);
if($HTML_CACHE_EVENT=="OK") ob_end_flush();
?>

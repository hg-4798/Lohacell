<?php
/**
 * 포토리뷰
 */
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

echo 'cccccccc';

$assign = array();
_render('review/photo.html', $assign);
if($HTML_CACHE_EVENT=="OK") ob_end_flush();
?>

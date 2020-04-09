<?php
/**
 * 이메일 무단 수집 거부
 */
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

//@TODO 이메일무단수집 연동??
/*$Common = new COMMON;
$privacy = $Common->getConfig('privacy');*/

$assign = array(
);

_render('bottom/etc_email.html', $assign, DIR_M.'/template');

include('./include/bottom.php');
?>
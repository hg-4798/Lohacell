<?php
/**
 * 이용약관
 */
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

$Common = new COMMON;
$agreement = $Common->getConfig('basic');

$pattern=array("[SHOP]","[COMPANY]");
$replace=array($_data->shopname, $_data->companyname);
$agreement = str_replace($pattern,$replace,$agreement);

$assign = array(
    'basic'=>htmlspecialchars_decode($agreement)
);

_render('bottom/etc_agreement.html', $assign, DIR_M.'/template');

include('./include/bottom.php');
?>
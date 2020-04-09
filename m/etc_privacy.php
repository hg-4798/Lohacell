<?php
/**
 *개인정보 취급방침
 */
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

$Common = new COMMON;
$privacy = $Common->getConfig('privacy');
$pattern=array("[SHOP]","[COMPANY]","[EMAIL]","[TEL]","[NAME]");
$replace=array($_data->shopname, $_data->companyname,$_data->privercyemail,$_data->info_tel,$_data->privercyname);
$privacy = str_replace($pattern,$replace,$privacy);

$assign = array(
    'privacy'=>htmlspecialchars_decode($privacy)
);

_render('bottom/etc_privacy.html', $assign, DIR_M.'/template');

include('./include/bottom.php');
?>

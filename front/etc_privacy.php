<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

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
_render('bottom/etc_privacy.html', $assign);
include('./include/bottom.php');
?>

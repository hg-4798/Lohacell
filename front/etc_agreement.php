<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

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
_render('bottom/etc_agreement.html', $assign);
include('./include/bottom.php');
?>
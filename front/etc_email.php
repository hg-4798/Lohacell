<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

/*$pattern=array("[SHOP]","[COMPANY]");
$replace=array($_data->shopname, $_data->companyname);
$agreement = str_replace($pattern,$replace,$agreement);*/


//@TODO email 무단수집거부 연동된 관리자페이지??
/*$Common = new COMMON;
$privacy = $Common->getConfig('privacy');*/

include('./include/top.php');
include('./include/gnb.php');

$assign = array(
);
_render('bottom/etc_email.html', $assign);

include('../front/include/bottom.php');
?>
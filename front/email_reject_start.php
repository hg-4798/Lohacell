<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
$key = $_GET['key'];
switch(DEVICE){
    case 'PC':
    default:
    alert_go('',"/front/email_reject.php?key=".$key);
        break;
    case 'MOBILE':
        alert_go('',"/m/email_reject.php?key=".$key);
        break;
}
?>
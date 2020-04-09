<?php
include_once("./lib/init.php");

if(DIR_VIEW == '/m') header("location:/m/main.php");
else header("location:/front/main.php");
exit;

?>

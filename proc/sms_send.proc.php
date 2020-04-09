<?php

$Dir= $_SERVER['HOME']."/public/";
include ($Dir."lib/init.php");
include ($Dir."lib/lib.php");

$idx = $_POST['idx'];

$sms = NEW SMS();
$sms->send($idx);
?>
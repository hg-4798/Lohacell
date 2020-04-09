<?php
$Dir = "../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$idx = $_POST['idx'];

$mail = NEW MAIL;
$mail->send($idx);
?>
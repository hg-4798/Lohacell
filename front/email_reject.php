<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

$key = $_GET['key'];
$id = Common::Dectypt_AES128CBC($key,JayjunKey,JayjunIvKey);
$assign = array(
    'key' => $key,
    'id' => $id
);
_render('member/email_reject.html', $assign);

include('./include/bottom.php');
?>
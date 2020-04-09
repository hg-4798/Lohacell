<?
if(strlen($Dir)==0) $Dir="../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

include('./include/top.php');
include('./include/gnb.php');
$key = $_GET['key'];
$id = Common::Dectypt_AES128CBC($key,JayjunKey,JayjunIvKey);
$assign = array(
    'key'=> $key,
    'id' => $id
);
_render('member/email_reject.html', $assign, DIR_M.'/template');
include('./include/bottom.php');
?>

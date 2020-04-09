<?php
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

//로그인체크(로그인회원만 접근가능)
MEMBER::isMember();

$assign = array(

);

_render('mypage/coupon.html', $assign);

include('./include/bottom.php');

?>

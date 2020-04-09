<?php
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include_once($Dir."conf/config.sns.php"); //sns설정
include('./include/top.php');
include('./include/gnb.php');

$row_sns = pmysql_fetch_array(pmysql_query("SELECT * FROM tblmember_sns WHERE id='".MEMID."'"));

$assign = array(
    'cfg'=>array(
        'naver'=>$snsNvConfig,
        'facebook'=>$snsFbConfig,
        'kakao'=>$snsKtConfig
    ),
    'list'=>$row_sns
);
_render('mypage/mypage_sns.html', $assign, DIR_M.'/template');

include('./include/bottom.php');
?>

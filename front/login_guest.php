<?php
/**
 * 비회원 주문조회
 */
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include_once($Dir."conf/config.sns.php"); //sns설정

if(MEMID) { //로그인상태인경우 메인으로 리턴
	go('/');
}

include('./include/top.php');
include('./include/gnb.php');


$assign = array(
	'page'=>'login_guest',
	'cfg'=>array(
		'naver'=>$snsNvConfig,
		'facebook'=>$snsFbConfig,
		'kakao'=>$snsKtConfig
	),
);


_render('member/login_guest.html', $assign);

include('./include/bottom.php');

?>
<?php
/**
 * 마이페이지 - 포인트/마일리지
 */
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

//로그인체크(로그인회원만 접근가능)
MEMBER::isMember();


$Member = new MEMBER;
$member_info = $Member->getMemberRow(MEMID);

$search = array(
	'date_s'=>date('Y-m-d', strtotime('-1 month')),
	'date_e'=>date('Y-m-d')
);

$assign = array(
	'member'=>$member_info,
	'search'=>$search
);

_render('mypage/point.html', $assign);
include('./include/bottom.php');
<?php
/**
 * 마이페이지 취소/교환/반품
 */

if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

//로그인체크(로그인회원만 접근가능)
MEMBER::isMember();

include('./include/top.php');
include('./include/gnb.php');


$Member = new MEMBER;
$member_info = $Member->getMemberRow(MEMID,'name, group_code, reserve, act_point');


$assign = array(
	'member'=>$member_info,
	'order_cnt'=>array(
		'order_status_4'=>'0',
		'order_status_5'=>'0',
		'cs_type'=>'0'
	)
);

_render('mypage/order_cs.html', $assign);

include('./include/bottom.php');

exit;
 
?>
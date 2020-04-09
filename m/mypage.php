<?php
/**
 * 마이페이지
 */

if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

//로그인한 회원만 접근가능
Member::isMember();

$Member = new MEMBER;
$member_info = $Member->getMemberRow(MEMID);

$Order = new ORDER;
$order_status_cnt= $Order->adodb->getOne("SELECT count(*) FROM {$tbl_op} p JOIN {$tbl_ob} b ON p.order_num = b.order_num WHERE b.member_id = '".MEMID."' AND p.cs_type='0' GROUP BY order_status");


$assign = array(
	'order_cnt'=>array(
		'order_status_4'=>$order_status_4,
		'order_status_5'=>$order_status_5,
		'cs_type'=>$cs_type
	),
	'member'=>$member_info
);



_render('mypage/index.html', $assign, DIR_M.'/template');

include('./include/bottom.php');
?>
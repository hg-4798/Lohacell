<?php
if(strlen($Dir)==0) $Dir="../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

//로그인한 회원만 접근가능
Member::isMember();


include('./include/top.php');
include('./include/gnb.php');


$mem_grade_code			= $_mdata->group_code;
$mem_grade_name			= $_mdata->group_name;

$mem_grade_img	= "../data/shopimages/grade/groupimg_".$mem_grade_code.".gif";
$mem_grade_text	= $mem_grade_name;
$reg_date	= substr($_mdata->date,0,4)."-".substr($_mdata->date,4,2)."-".substr($_mdata->date,6,2);

// 다음등급 AP포인트
list($next_level_point)=pmysql_fetch_array(pmysql_query("select group_ap_s from tblmembergroup WHERE group_level > '{$_mdata->group_level}' order by group_level asc limit 1"));

// 다음등급까지 남은 AP 포인트
$need_act_point=($_mdata->act_point >= $next_level_point)?'0':($next_level_point-$_mdata->act_point);




$Member = new MEMBER;
$member_info = $Member->getMemberRow(MEMID);

$assign = array(
	'member'=>$member_info
);



_render('mypage/benefit.html', $assign, DIR_M.'/template');


include('./include/bottom.php');
?>
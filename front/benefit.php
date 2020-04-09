<?php
/**
 * 회원등급 및 혜택
 * 
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

include('../front/include/top.php');
include('../front/include/gnb.php');

//로그인체크(로그인회원만 접근가능)
MEMBER::isMember();

$Member = new MEMBER;
$member_info = $Member->getMemberRow(MEMID,'name, group_code, reserve, act_point');

/*

$sql = "SELECT a.*, b.group_level, b.group_name, b.group_code, b.group_orderprice_s, b.group_orderprice_e, b.group_ordercnt_s, b.group_ordercnt_e FROM tblmember a left join tblmembergroup b on a.group_code = b.group_code WHERE a.id='".$_ShopInfo->getMemid()."' ";
$result=pmysql_query($sql,get_db_conn());
if($row=pmysql_fetch_object($result)) {
	$_mdata=$row;
	if($row->member_out=="Y") {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		alert_go('회원 아이디가 존재하지 않습니다.',$Dir.FrontDir."login.php");
	}

	if($row->authidkey!=$_ShopInfo->getAuthidkey()) {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		alert_go('처음부터 다시 시작하시기 바랍니다.',$Dir.FrontDir."login.php");
	}
}
$staff_type = $row->staff_type;
pmysql_free_result($result);

$mem_grade_code			= $_mdata->group_code;
$mem_grade_name			= $_mdata->group_name;

$mem_grade_img	= "../data/shopimages/grade/groupimg_".$mem_grade_code.".gif";
$mem_grade_text	= $mem_grade_name;
$reg_date	= substr($_mdata->date,0,4)."-".substr($_mdata->date,4,2)."-".substr($_mdata->date,6,2);

// 멤버쉽 내용 불러오기
$mem_temp_sql = "SELECT etc_agreement3 FROM tbldesign ";
$mem_temp_result = pmysql_query($mem_temp_sql,get_db_conn());
if ($row=pmysql_fetch_object($mem_temp_result)) {
	$etc_agreement3 = ($row->etc_agreement3=="<P>&nbsp;</P>"?"":$row->etc_agreement3);
	$etc_agreement3 = str_replace('\\','',$etc_agreement3);
}
pmysql_free_result($mem_temp_result);

// 다음등급 AP포인트
list($next_level_point)=pmysql_fetch_array(pmysql_query("select group_ap_s from tblmembergroup WHERE group_level > '{$_mdata->group_level}' order by group_level asc limit 1"));

// 다음등급까지 남은 AP 포인트
$need_act_point=($_mdata->act_point >= $next_level_point)?'0':($next_level_point-$_mdata->act_point);
*/

$assign = array(
	'member'=>$member_info
);
_render('mypage/benefit.html', $assign);


include('../front/include/bottom.php');
?>

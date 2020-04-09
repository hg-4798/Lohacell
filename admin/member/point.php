<?php
$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Member = new MEMBER;
$Point = new POINT;

$Point->sync($_POST['mem_id']); //포인트 싱크(회원테이블, 포인트테이블)

$mem_id = $_POST['mem_id'];
$member_info = $Member->getMemberRow($mem_id);
// pre($member_info);

$assign = array(
	'member'=>$member_info,
	'point'=>array(
		'reason'=>'관리자 임의 포인트 처리',
		'term'=>365*$Point->cfg['expire_year']
	)
);
_render("member/point.html", $assign, 'admin/template');
?>
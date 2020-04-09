<?php
/**
 * 마일리지
 */
$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Member = new MEMBER;
$Mileage = new MILEAGE;
$mem_id = $_POST['mem_id'];
$member_info = $Member->getMemberRow($mem_id);


$assign = array(
	'member'=>$member_info,
	'mileage'=>array(
		'reason'=>'관리자 임의 마일리지 처리',
		'term'=>365*$Mileage->cfg['expire_year']
	)
);
_render("member/mileage.html", $assign, 'admin/template');
?>
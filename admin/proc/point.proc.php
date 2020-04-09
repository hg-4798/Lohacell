<?php
/**
 * 포인트프로세스
 * @since 2018-09-26
 */



$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Point = new POINT();
$mode = $_POST['mode'];
$act = $_POST['act'];

//$Point->expire(); //포인트 만료일체크

if($mode == 'member') { //회원목록
	//point 0 체크

	$point = str_replace(',','',$_POST['point']);
	if($point < 1) {
		return_json(false,'0보다 큰값을 입력하세요.');
	}

	if($act == 'give') { //지급
		//만료일
		$expire_term = $_POST['term'];
		$record = array(
			'mem_id'=>$_POST['mem_id'],
			'point'=>$point,
			'point_remain'=>$point,
			'point_reason_flag'=>'admin',
			'point_reason'=>$_POST['reason']
		);
		$rs = $Point->give($record, $_POST['term']);

	}
	else if($act == 'get') { //차감
		$Member = new MEMBER;
		$point = str_replace(',','',$_POST['point']);

		//최대차감액 체크
		$member_info = $Member->getMemberRow($_POST['mem_id'],'act_point');
		if($point > $member_info['act_point']) {
			return_json(false,'차감액이 보유 포인트보다 큽니다.<br><br>최대 차감 가능한 포인트 : <b>'.number_format($member_info['act_point']).'</b> P');
		}


		$point *=-1;
		$record = array(
			'mem_id'=>$_POST['mem_id'],
			'point'=>$point,
			'point_remain'=>0,
			'point_reason'=>$_POST['reason'],
			'point_reason_flag'=>'admin',
			'date_expire'=>NOW,
			'reg_admin_id'=>$_ShopInfo->id
		);

		$rs = $Point->act($record);
	}

	if($rs) {
		return_json(true,$_ALERT['C000']);
	}
	else {
		return_json(false,$_ALERT['C003']);
	}
}
?>
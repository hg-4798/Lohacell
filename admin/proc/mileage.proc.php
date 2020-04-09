<?php
/**
 * 마일리지 프로세스
 * @since 2018-09-26
 */



$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Mileage = new MILEAGE('admin');
$mode = $_POST['mode'];
$act = $_POST['act'];

//$Point->expire(); //마일리지 만료일체크



if($mode == 'member') { //회원목록
	//mileage 0 체크

	$mileage = str_replace(',','',$_POST['mileage']);
	if($mileage < 1) {
		return_json(false,'0보다 큰값을 입력하세요.');
	}



	if($act == 'give') { //지급

		
		//만료일
		$expire_term = $_POST['term'];
		$record = array(
			'mem_id'=>$_POST['mem_id'],
			'mileage'=>$mileage,
			'mileage_remain'=>$mileage,
			'mileage_reason_flag'=>'admin',
			'mileage_reason'=>$_POST['reason']
		);
		$rs = $Mileage->give($record, $_POST['term']);
		
	}
	else if($act == 'get') { //차감
		$Member = new MEMBER;
		$mileage = str_replace(',','',$_POST['mileage']);

		//최대차감액 체크
		$member_info = $Member->getMemberRow($_POST['mem_id'],'reserve');
		if($mileage > $member_info['reserve']) {
			return_json(false,'차감액이 보유 마일리지보다 큽니다.<br><br>최대 차감 가능한 마일리지 : <b>'.number_format($member_info['reserve']).'</b> M');
		}
		
		$mileage *=-1;
		$record = array(
			'mem_id'=>$_POST['mem_id'],
			'mileage'=>$mileage,
			'mileage_remain'=>0,
			'mileage_reason'=>$_POST['reason'],
			'mileage_reason_flag'=>'admin',
			'date_expire'=>NOW
		);


		$rs = $Mileage->act($record);
	}

	if($rs) {
		return_json(true,$_ALERT['C000']);
	}
	else {
		return_json(false,$_ALERT['C003']);
	}
}
?>
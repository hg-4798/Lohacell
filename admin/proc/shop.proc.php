<?php
/**
 * 샵정보 프로세싱
 * @author  이혜진(stickcandy81@nate.com)
 */

//실행파일 직접접근 방지
if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { 
	header("HTTP/1.0 404 Not Found");
	exit;
}


$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");

$mode = $_POST['mode'];
$act = $_POST['act'];
$adodb = adodb_connect();


if($mode == 'card') { //카드사 혜택안내 저장
	
	$tbl = 'tblconfig';
	$section = 'card';

	$record = array(
		'pc_title'=>$_POST['card_pc']['title'],
		'pc_contents'=>htmlspecialchars($_POST['card_pc']['contents'],ENT_QUOTES),
		'mobile_title'=>$_POST['card_mobile']['title'],
		'mobile_contents'=>htmlspecialchars($_POST['card_mobile']['contents'],ENT_QUOTES)
	);

	foreach($record as $field=>$value) {
		$sql = "UPDATE {$tbl} SET field_value='{$value}' WHERE field='{$field}' AND section='{$section}'";
		$rs = $adodb->Execute($sql);
	}
	
	
	if($rs) {
		return_json(true, '적용되었습니다.');
	}
	else {
		return_json(false,'잠시 후에 다시 시도해주세요.');
	}
}
else if($mode == 'agreement') { //약관저장
	$Common = new COMMON;

	if($act == 'get_standard') {
		$field = $_POST['field'];
		$field_value = $Common->getConfig($field);
		echo $field_value;

	}
	else {
		$field = $_POST['field'];
		$field_value = htmlspecialchars($_POST[$field], ENT_QUOTES);

		$rs = $Common->setConfig($mode, $field, $field_value);
		if($rs)  {
			return_json(true,$_ALERT['C000']);
		}
		else return_json(true,$_ALERT['C003']);
	}
}
else if($mode == 'coupon') {
	$Common = new COMMON;
	$success = true;
	foreach($_POST['coupon'] as $field=>$field_value) {
		$rs = $Common->setConfig($mode, $field, $field_value);
		if(!$rs) $success = false;
	}

	if($success) {
		return_json(true,$_ALERT['C000']);
	}
	else {
		return_json(true,$_ALERT['C003']);
	}
}
else if($mode == 'point') {
	$Common = new COMMON;
	$success = true;


	//기본설정정보 저장
	foreach($_POST['config'] as $field=>$field_value) {
		$field_value = str_replace(',','',$field_value);
		$rs = $Common->setConfig('point', $field, $field_value);
		if(!$rs) $success = false;
	}

	//포인트설정 저장
	foreach($_POST['point'] as $point_id=>$point) {
		$record = array(
			'point'=>str_replace(',','',$point)
		);
		$where = array('point_id'=>$point_id);
		$sql = sqlUpdate($record, $Common->tbls['point_config'], $where);
		$rs = $Common->adodb->Execute($sql);
		if(!$rs) $success = false;
	}


	if($rs) {
		return_json(true,'저장되었습니다.');
	}
	else {
		return_json(false,'잠시 후에 시도해 주세요.');
	}
}
else if($mode == 'mileage') {
	$Common = new COMMON;
	$success = true;

	// pre($_POST);

	//기본설정정보 저장
	foreach($_POST['config'] as $field=>$field_value) {
		$field_value = str_replace(',','',$field_value);

		$rs = $Common->setConfig('mileage', $field, $field_value);
		if(!$rs) $success = false;
	}

	//그룹별 적립금 설정저장
	foreach($_POST['mileage'] as $group_code=>$mileage) {
		$record = array(
			'group_addreserve'=>str_replace(',','',$mileage)
		);
		$where = array('group_code'=>$group_code);
		$sql = sqlUpdate($record, $Common->tbls['member_group'], $where);
		$rs = $Common->adodb->Execute($sql);
		if(!$rs) $success = false;
	}

	if($rs) {
		return_json(true,'저장되었습니다.');
	}
	else {
		return_json(false,'잠시 후에 시도해 주세요.');
	}
}
else if($mode == 'order') {
	$Common = new COMMON;
	$success = true;
	
	foreach($_POST['order'] as $field=>$field_value) {
		$field_value = str_replace(',','',$field_value);

		$rs = $Common->setConfig('order', $field, $field_value);
		if(!$rs) $success = false;
	}

	if($success) {
		return_json(true,'저장되었습니다.');
	}
	else {
		return_json(false,'잠시 후에 시도해 주세요.');
	}
}
else if($mode == 'area_deli') {
	$Common = new COMMON;
	if($act == 'remove') {
		$tbl = $Common->tbls['area_deli'];
		$no = $_POST['no'];
		$sql = "DELETE FROM {$tbl} WHERE no IN ({$no})";
		$rs = $Common->adodb->Execute($sql);
		if($rs) {
			return_json(true,'삭제되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 시도해 주세요.');
		}
	}
}
?>
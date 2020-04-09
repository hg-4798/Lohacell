<?php
/**
 * 공통처리 프로세싱
 * @author  이혜진
 */

$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


// include_once($Dir."lib/adminlib.php");
//include_once($Dir."/admin/_config/message.php");
$mode = $_POST['mode'];
$act = $_POST['act'];

if($mode == 'excel_format') {
	$Common = new COMMON;
	if($act == 'save') { //양식저장
		$record = array(
			'mem_id'=>$_ShopInfo->id,
			'item_name'=>$_POST['format_name'],
			'item_type'=>$_POST['type'],
			'item'=>$_POST['column'],
			'date_insert'=>NOW
		);
		$rs = $Common->insert($record, 'excel_info');
		if($rs) {
			return_json(true,$_ALERT['C000']);
		}
		else return_json(false,$_ALERT['C003']);

	}
	else if($act == 'delete') { //양식삭제
		$where = array(
			'eid'=>$_POST['eid']
		);
		$rs = $Common->delete($where,'excel_info');
		if($rs) {
			return_json(true,$_ALERT['C001']);
		}
		else {
			return_json(false, $_ALERT['C003']);
		}
	}
	else if($act == 'load_column') {
		include_once($Dir."/admin/_config/excel_format.php");

		$format = $Common->select_row(array('eid'=>$_POST['eid']),'excel_info');
		$column_arr = explode(',',$format['item']);
		$column_cfg = $config_excel[$format['item_type']];
		$column = array();
		foreach($column_arr as $c) {
			$column[] = array(
				'id'=>$c,
				'name'=>$column_cfg[$c]['name']
			);
		}

		return_json(true,'',$column);
	}
	
}
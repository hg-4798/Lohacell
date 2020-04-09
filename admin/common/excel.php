<?php
/**
 * 엑셀다운로드
 */
$Dir = '../../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

include_once($Dir."/admin/_config/excel_format.php");
$Common = new COMMON;

$type = $_POST['type'];
// parse_str($_POST['search'], $search); pre($search);

//저장된 엑셀양식 불러오기

$format = $Common->select_all(array('mem_id'=>$_ShopInfo->id,'item_type'=>$type),'excel_info');

$assign = array(
	'type'=>$type,
	'format'=>$format,
	'search'=>$_POST['search'],
	'column'=>$config_excel[$type]
);

// pre($assign);
_render("common/excel.html", $assign, 'admin/template');
?>
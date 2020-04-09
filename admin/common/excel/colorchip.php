<?php
/**
 * 컬러칩목록 엑셀다운로드 처리
 */
$product = new PRODUCT;
$xlsx_filename = "컬러칩목록_".date('YmdHis').".xlsx"; //다운로드 파일명

parse_str($g_search, $search);

//헤더
$column_arr = explode(',',$g_column);

$headers = array();
$cfg_column = $config_excel['colorchip'];
foreach($column_arr as $c) {
	$headers[] = $cfg_column[$c]['name'];
}


//검색
$where_arr = array();
foreach($search as $field=>$v) {
	if(!$v || in_array($field, array('sf', 'date_sf')) || empty($v) || in_array($v, array('all'))) continue;
	switch($field){
		case 'sw': //검색어검색
			$sf = $search['sf'];
			$where_arr[] = "{$sf} = '{$v}'";
			//$where_arr[] = '('.implode(' OR ', $tmp).')';
			break;

		case 'use_yn': //사용여부
		case 'color_group': //컬러계열
			$where_arr[] = "{$field}='{$v}'";
			break;
	}
}

if(!empty($where_arr)) {
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}

//pre($where_arr);exit;
$tbl = $product->tbls['product_color'];
//목록
$sql = "SELECT * FROM {$tbl} {$where} ORDER BY date_insert DESC, idx DESC ";



//pre($group_list);exit;
$rs = $product->adodb->Execute($sql);
while($row = $rs->FetchRow()) {
	$temp = array();
	foreach($column_arr as $c) {
		switch($c) {
			default:
				$temp[] = $row[$c];
			break;
		}
	}

	$list[] = $temp;
}
?>
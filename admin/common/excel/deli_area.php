<?php
/**
 * 지역별배송비
 */
$Common = new COMMON();
$xlsx_filename = "지역별배송비_".date('YmdHis').".xlsx"; //다운로드 파일명

parse_str($g_search, $search);

//헤더
$column_arr = explode(',',$g_column);
$headers = array();

$cfg_column = $config_excel[$g_mode];
foreach($column_arr as $c) {
	$headers[] = $cfg_column[$c]['name'];
}

$tbl = $Common->tbls['area_deli'];
$orderby = 'no DESC';//정렬

$where_arr = array();
foreach($search as $field=>$v) {
	if(!$v || in_array($field, array('sf')) || empty($v)) continue;
	switch($field){
		case 'sv': //검색어검색
			$where_arr[] = "area_name LIKE '%{$v}%'";
			break;
		default:
			break;

	}
}

if(!empty($where_arr)) {
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}

//목록
$sql = "SELECT * FROM {$tbl} {$where} ORDER BY {$orderby} ";



$rs = $Common->adodb->Execute($sql);
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
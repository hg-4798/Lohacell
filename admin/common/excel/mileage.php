<?php
/**
 * 마일리지목록 엑셀다운로드 처리
 */
$Mileage = new MILEAGE();
$Member = new MEMBER();
$xlsx_filename = "마일리지목록_".date('YmdHis').".xlsx"; //다운로드 파일명

parse_str($g_search, $search);

//헤더
$column_arr = explode(',',$g_column);

$headers = array();
$cfg_column = $config_excel['mileage'];
foreach($column_arr as $c) {
	$headers[] = $cfg_column[$c]['name'];
}


switch($search['sort']) {
	case 'date_desc':
	default;
		$orderby = 'p.date_insert DESC';
		break;
	case 'date_asc':
		$orderby = 'p.date_insert ASC';
		break;
}

//검색
$where_arr = array();
foreach($search as $field=>$v) {
	if(!$v || empty($v)) continue;
	switch($field){
		case 'sv': //검색어검색
			$f = $search['sf'];
			$where_arr[] = "{$f} LIKE '%{$v}%'";
			break;
		case 'group_code': //회원등급
			$where_arr[] = "m.group_code = '{$v}'";
			break;
		case 'date_s':
			$where_arr[] = "p.date_insert >= '{$v}'";
			break;
		case 'date_e':
			$where_arr[] = "p.date_insert <= '{$v}'";
			break;

	}
}

if(!empty($where_arr)) {
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}
$group_list = $Member->getGroupPair();
$tbl = $Mileage->tbls['mileage'];
$tbl_member = $Mileage->tbls['member'];
//목록
$sql = "SELECT p.*, m.name, m.group_code FROM {$tbl} AS p LEFT JOIN {$tbl_member} AS m ON(p.mem_id=m.id) {$where} ORDER BY {$orderby} ";

//pre($sql);exit;
$rs = $Mileage->adodb->Execute($sql);
while($row = $rs->FetchRow()) {
	$temp = array();
	foreach($column_arr as $c) {
		switch($c) {
			case 'group_code':
				$temp[] = $group_list[$row[$c]];
				break;
			default:
				$temp[] = $row[$c];
			break;
		}
	}

	$list[] = $temp;
}
?>
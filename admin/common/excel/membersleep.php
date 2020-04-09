<?php
/**
 * 휴면회원목록 엑셀다운로드 처리
 */
$Member = new MEMBER();
$xlsx_filename = "휴면회원목록_".date('YmdHis').".xlsx"; //다운로드 파일명

parse_str($g_search, $search);

//헤더
$column_arr = explode(',',$g_column);

$headers = array();
$cfg_column = $config_excel['membersleep'];
foreach($column_arr as $c) {
	$headers[] = $cfg_column[$c]['name'];
}


//정렬
switch($search['sort']) {
	case 'date_asc': //가입일 오름차순
		$orderby = 'date ASC';
		break;
	case 'date_desc': //가입일 내림차순
	default:
		$orderby = 'date DESC';
		break;
}

//검색
$where_arr = array();
foreach($search as $field=>$v) {
	if(!$v || in_array($field, array('sf', 'date_sf')) || empty($v)) continue;
	switch($field){
		case 'sw': //검색어검색
			$sf = $search['sf'];
			$sv = array_filter(explode("\n", $v));
			if(empty($sv)) continue;
			$tmp = array();
			foreach($sv as $vv) {
				$vv = trim($vv);
				if($sf == 'all'){
					$tmp[] = "id LIKE '%{$vv}%' OR name LIKE '%{$vv}%' ";
				}else {
					$tmp[] = "{$sf} LIKE '%{$vv}%'";
				}
			}

			$where_arr[] = '('.implode(' OR ', $tmp).')';
			break;
		case 'date_start': //가입일 시작
			$v = trim($v);
			if($v) {
				$v= date("YmdHis",strtotime($v));
				$where_arr[] = "date >= '{$v}'";
			}
			break;
		case 'date_end': //가입일 끝
			$v = trim($v);
			if($v) {
				$v= date("Ymd",strtotime($v))."235959";
				$where_arr[] = "date <= '{$v}'";
			}
			break;
		case 'logindate_start': //최근로그인 시작
			$v = trim($v);
			if($v) {
				$v= date("YmdHis",strtotime($v));
				$where_arr[] = "logindate >= '{$v}'";
			}
			break;
		case 'logindate_end': //최근로그인 끝
			$v = trim($v);
			if($v) {
				$v= date("Ymd",strtotime($v))."235959";
				$where_arr[] = "logindate <= '{$v}'";
			}
			break;
	}
}

if(!empty($where_arr)) {
	$where = ' AND ';
	$where .= implode(' AND ', $where_arr);
}

$tbl = $Member->tbls['member'];
//목록
$sql = "SELECT *
		FROM {$tbl}
		WHERE member_out = 'S'
		{$where}
        ORDER BY {$orderby} 
        ";

//pre($group_list);exit;
$rs = $Member->adodb->Execute($sql);
while($row = $rs->FetchRow()) {
	$temp = array();
	foreach($column_arr as $c) {
		switch($c) {
			case 'date':
				$temp[] = COMMON::format($row[$c],'Y-m-d');
				break;
			case 'email':
				$temp[] = Common::Dectypt_AES128CBC($row[$c]);
				break;
			case 'mobile':
				$temp[] = Common::Dectypt_AES128CBC($row[$c]);
				break;
			case 'logindate':
				$temp[] = COMMON::format($row[$c],'Y-m-d');
				break;
			default:
				$temp[] = $row[$c];
				break;
		}
	}

	$list[] = $temp;
}
?>
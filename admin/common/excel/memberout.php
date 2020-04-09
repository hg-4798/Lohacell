<?php
/**
 * 탈퇴회원목록 엑셀다운로드 처리
 */
$Member = new MEMBER();
$xlsx_filename = "퇴탈회원목록_".date('YmdHis').".xlsx"; //다운로드 파일명

parse_str($g_search, $search);

//헤더
$column_arr = explode(',',$g_column);

$headers = array();
$cfg_column = $config_excel['memberout'];
foreach($column_arr as $c) {
	$headers[] = $cfg_column[$c]['name'];
}


switch($search['sort']) {
	case 'outdate_asc': //탈퇴일 오름차순
		$orderby = 'b.date ASC';
		break;
	case 'outdate_desc': //탈퇴일 내림차순
		$orderby = 'b.date DESC';
		break;
	case 'date_asc': //가입일 오름차순
		$orderby = 'a.date ASC';
		break;
	case 'date_desc': //가입일 내림차순
	default:
		$orderby = 'a.date DESC';
		break;
}

//검색
$where_arr = array();
foreach($search as $field=>$v) {
	if(!$v || in_array($field, array('sf', 'date_sf')) || empty($v)) continue;
	switch($field){
		case 'date_start': //가입일 시작
			$v = trim($v);
			if($v) {
				$v= date("YmdHis",strtotime($v));
				$where_arr[] = "a.date >= '{$v}'";
			}
			break;
		case 'date_end': //가입일 끝
			$v = trim($v);
			if($v) {
				$v= date("Ymd",strtotime($v))."235959";
				$where_arr[] = "a.date <= '{$v}'";
			}
			break;
		case 'outdate_start': //최근로그인 시작
			$v = trim($v);
			if($v) {
				$v= date("YmdHis",strtotime($v));
				$where_arr[] = "b.date >= '{$v}'";
			}
			break;
		case 'outdate_end': //최근로그인 끝
			$v = trim($v);
			if($v) {
				$v= date("Ymd",strtotime($v))."235959";
				$where_arr[] = "b.date <= '{$v}'";
			}
			break;
	}
}

if(!empty($where_arr)) {
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}

$tbl = $Member->tbls['member'];
//목록
$sql = "SELECT a.id,
									   a.date,
									   b.name,
									   b.email,
									   b.tel,
									   b.date AS outdate,
									   b.out_reason,
									   b.out_reason_content
								FROM {$tbl} a
								LEFT JOIN tblmemberout b ON b.id=a.id
								WHERE a.member_out='Y' and (b.name is not null)
								{$where}
                                ORDER BY {$orderby} ";

$group_list = $Member->getGroupPair();

//pre($group_list);exit;
$rs = $Member->adodb->Execute($sql);
while($row = $rs->FetchRow()) {
	$temp = array();
	foreach($column_arr as $c) {
		switch($c) {
			case 'date':
				$temp[] = COMMON::format($row[$c],'Y-m-d');
				break;
			case 'outdate':
				$temp[] = COMMON::format($row[$c],'Y-m-d');
				break;
			case 'email':
				$temp[] = Common::Dectypt_AES128CBC($row[$c]);
				break;
			case 'tel':
				$temp[] = Common::Dectypt_AES128CBC($row[$c]);
				break;
			case 'out_reason':
				$temp[] = $arrMemberOutReason[$row[$c]];
				break;
			default:
				$temp[] = $row[$c];
			break;
		}
	}

	$list[] = $temp;
}
?>
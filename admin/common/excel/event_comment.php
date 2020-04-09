<?php
/**
 * 이벤트댓글 엑셀다운로드 처리
 */
$Promotion = new PROMOTION();
$xlsx_filename = "이벤트댓글_".date('YmdHis').".xlsx"; //다운로드 파일명

parse_str($g_search, $search);

//헤더
$column_arr = explode(',',$g_column);
$headers = array();
$cfg_column = $config_excel['event_comment'];
foreach($column_arr as $c) {
	$headers[] = $cfg_column[$c]['name'];
}
//pre($column_arr);exit;
$tbl = $Promotion->tbls['promo_comment'];
$tbl_member = $Promotion->tbls['member'];
$orderby = 'writetime DESC';//정렬
$sql = "SELECT e.*,m.email,m.mobile FROM {$tbl} e LEFT JOIN {$tbl_member} m ON (e.c_mem_id = m.id) WHERE parent='".$search['no']."'";

$rs = $Promotion->adodb->Execute($sql);
while($row = $rs->FetchRow()) {
	$temp = array();
	foreach($column_arr as $c) {
		switch($c) {
			case 'comment':
				$temp[] = $row[$c];
				break;
			case 'email':
				$temp[] = COMMON::Dectypt_AES128CBC($row[$c]);
				break;
			case 'mobile':
				$temp[] = COMMON::Dectypt_AES128CBC($row[$c]);
				break;
			default:
				$temp[] = $row[$c];
				break;
		}
	}

	$list[] = $temp;
}

?>
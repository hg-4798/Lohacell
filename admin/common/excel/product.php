<?php
/**
 * 상품목록 엑셀다운로드 처리
 */
$Product = new PRODUCT;
$xlsx_filename = "상품목록_".date('YmdHis').".xlsx"; //다운로드 파일명

parse_str($g_search, $search);

//헤더
$column_arr = explode(',',$g_column);

$headers = array();
$cfg_column = $config_excel['product'];
foreach($column_arr as $c) {
	$headers[] = $cfg_column[$c]['name'];
}


switch($search['sort']) {
	case 'sale_asc': //할인율 낮은순
		$orderby = 'p.sellprice_dc_rate ASC';
		break;
	case 'sale_desc': //할인율 높은순
		$orderby = 'p.sellprice_dc_rate DESC';
		break;
	default: //최근등록일순
		$orderby = 'p.regdate DESC';
		break;
}

//검색
$where_arr = array();
foreach($search as $field=>$v) {
	if(!$v || in_array($field, array('sf', 'date_sf')) || empty($v)) continue;
	switch($field){
		case 'category': //카테고리검색
			$cate = array_pop(array_filter($v));
			if(empty($cate)) continue;
			$productcode = rtrim($cate,'0');
			$where_arr[] = "pl.c_category LIKE '{$productcode}%'"; 
			break;
		case 'sv': //검색어검색
			$sf = $search['sf'];
			$sv = array_filter(explode("\n", $v));
			if(empty($sv)) continue;
			$tmp = array();
			foreach($sv as $vv) {
				$vv = trim($vv);
				$tmp[] = "p.{$sf} LIKE '%{$vv}%'";
			}

			$where_arr[] = '('.implode(' OR ', $tmp).')';
			break;
		case 'date_start': //날짜검색(시작)
			$sf = trim($search['date_sf']);
			$v = trim($v);
			$where_arr[] = "p.{$sf} >= '{$v} 00:00:00'";
			break;
		case 'date_end': //날짜검색(종료)
			$sf = trim($search['date_sf']);
			$v = trim($v);
			$where_arr[] = "p.{$sf} <= '{$v} 23:59:59'";
			break;
		case 'sellprice_min':
			$where_arr[] = "p.sellprice >= '{$v}'";
			break;
		case 'sellprice_max':
			$where_arr[] = "p.sellprice<='{$v}'";
			break;
		case 'quantity_min': //재고최소값
			$where_arr[] = "p.quantity >= '{$v}'";
			break;
		case 'quantity_max': //재고최대값
			$where_arr[] = "p.quantity <= '{$v}'";
			break;
		case 'display': //승인상태
		case 'soldout': //판매상태
		case 'line_code': //라인
		case 'pr_type': //상품종류
			$where_arr[] = "p.{$field}='{$v}'";
			break;
	}
}

if(!empty($where_arr)) {
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}


//목록
$sql = "SELECT DISTINCT ON(p.productcode, p.regdate, p.sellprice_dc_rate) p.* FROM tblproduct AS p LEFT JOIN tblproductlink AS pl ON(p.productcode = pl.c_productcode) {$where} ORDER BY {$orderby}";
$rs = $Product->adodb->Execute($sql);
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
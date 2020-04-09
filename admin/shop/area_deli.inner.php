<?php
/**
 * 회원별 포인트 지급/사용내역
 * @author hjlee
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


$Common = new COMMON;

parse_str($_POST['search'], $search);


$limit = $search['limit'];
$page = $_POST['page'];
$offset = ($page-1)*$limit;

$where_arr = array();

foreach($search as $field=>$v) {
	if(!$v || empty($v)) continue;
	switch($field){
		case 'sv': //검색어검색
			$where_arr[] = "area_name LIKE '%{$v}%'";
			break;
	
	}
}

if(!empty($where_arr)) {
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}

$orderby = 'no DESC'; //정렬

$tbl = $Common->tbls['area_deli'];
$sql = "SELECT * FROM {$tbl} {$where} ORDER BY {$orderby} OFFSET {$offset} LIMIT {$limit}";

$cnt_total =  $Common->adodb->getOne("SELECT COUNT(*) FROM {$tbl}");
$cnt_search = $Common->adodb->getOne("SELECT COUNT(*) FROM {$tbl} {$where}");

$rs = $Common->adodb->Execute($sql);

$list = array();

$num = $cnt_search-$offset;
while($row = $rs->FetchRow()) {
	$row['num'] = $num;
	$row['group_name'] = $group_list[$row['group_code']];
	$list[] = $row;
	$num--;
}

//페이징
$paging_config = array(
	'total'=>$cnt_search,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'AreaDeli.load'
);

$Pagination = new PAGINATION($paging_config);
$paging = $Pagination->getPageSet();



$assign = array(
	'count'=>array('total'=>$cnt_total, 'search'=>$cnt_search),
	'sum'=>$sum,
	'list'=>$list,
	'paging'=>$paging
);


_render("shop/area_deli.inner.html", $assign, 'admin/template');

?>

<?php
/**
 * 회원별 마일리지 지급/사용내역
 * @author hjlee
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


$Mileage = new POINT;
$Member = new MEMBER;

parse_str($_POST['search'], $search);


$limit = $search['limit'];
$page = $_POST['page'];
$offset = ($page-1)*$limit;

$where_arr = array();
$where_arr[] = "p.mileage !='0'";
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


switch($search['sort']) {
	case 'date_desc':
	default;
		$orderby = 'p.date_insert DESC';
		break;
	case 'date_asc':
		$orderby = 'p.date_insert ASC';
		break;
}

$tbl = $Mileage->tbls['mileage'];
$tbl_member = $Mileage->tbls['member'];
$sql = "SELECT p.*, m.name, m.group_code FROM {$tbl} AS p LEFT JOIN {$tbl_member} AS m ON(p.mem_id=m.id) {$where} ORDER BY {$orderby} OFFSET {$offset} LIMIT {$limit}";

$cnt_total =  $Mileage->adodb->getOne("SELECT COUNT(*) FROM {$tbl}");
$cnt_search = $Mileage->adodb->getOne("SELECT COUNT(*) FROM {$tbl} AS p LEFT JOIN {$tbl_member} AS m ON(p.mem_id=m.id) {$where}");

$rs = $Mileage->adodb->Execute($sql);

$list = array();
$group_list = $Member->getGroupPair(); //그룹정보

$num = $cnt_search-$offset;
while($row = $rs->FetchRow()) {
	$row['num'] = $num;
	$row['group_name'] = $group_list[$row['group_code']];
	$list[] = $row;
	$num--;
}

$sum = $Mileage->adodb->getRow("SELECT SUM(case when mileage> 0 then mileage end) as plus, SUM(case when mileage<0 then mileage end) as minus FROM {$tbl} AS p LEFT JOIN {$tbl_member} AS m ON(p.mem_id=m.id) {$where}");


 //페이징
$paging_config = array(
	'total'=>$cnt_search,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'MileageLog.load'
);

$Pagination = new PAGINATION($paging_config);
$paging = $Pagination->getPageSet();



$assign = array(
	'count'=>array('total'=>$cnt_total, 'search'=>$cnt_search),
	'sum'=>$sum,
	'list'=>$list,
	'paging'=>$paging
);


_render("member/mileage_log.inner.html", $assign, 'admin/template');

?>

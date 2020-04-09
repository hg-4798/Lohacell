<?php
/**
 * 포인트 사용/적립내역
 * 
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Point = new POINT();
$argu = $_POST;

parse_str($_POST['search'], $search);
$page = ($argu['page'])?$argu['page']:1;
$limit = (isset($search['limit']))?$search['limit']:20;
$offset = ($page-1)*$limit;

//검색
$where_arr = array();
foreach($search as $field=>$v) {
    if(!$v || in_array($field, array('sf', 'date_sf')) || empty($v)) continue;
    switch($field){
        case 'date_s': //날짜검색(시작)
            $sf = trim($search['date_s']);
            $v = trim($v);
            $where_arr[] = "date_insert >= '{$v} 00:00:00'";
            break;
        case 'date_e': //날짜검색(종료)
            $sf = trim($search['date_e']);
            $v = trim($v);
            $where_arr[] = "date_insert <= '{$v} 23:59:59'";
            break;
    }
}

if(!empty($where_arr)) {
    $where = ' AND ';
    $where .= implode(' AND ', $where_arr);
}

$list =  $Point->usePoint(MEMID,$limit,$offset,$where);

//페이징
$paging_config = array(
    'total'=>$list['total'],
    'block_size'=>10,
    'list_size'=>$limit,
    'page_current'=>$page,
    'url_type'=>'javascript',
    'url'=>'Point.loadPage'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

$assign = array(
	'list'=>$list['list'],
    'total'=>$list['total'],
    'paging'=>$paging
);

_render('mypage/point.inner.html', $assign);

?>

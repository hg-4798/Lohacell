<?php
/**
 * 마이페이지 쿠폰
 */

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

//로그인한 회원만 접근가능
Member::isMember();

$Mileage = new MILEAGE();
$argu = $_POST;

parse_str($_POST['search'], $search);
$page = ($argu['page'])?$argu['page']:1;
$limit = (isset($search['limit']))?$search['limit']:5;
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

$list =  $Mileage->useMileage(MEMID,$limit,$offset,$where);

//페이징
$paging_config = array(
    'total'=>$list['total'],
    'block_size'=>5,
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

_render('mypage/mileage.inner.html', $assign, DIR_M.'/template');

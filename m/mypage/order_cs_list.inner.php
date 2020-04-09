<?php
/**
 * 마이페이지 쿠폰
 */

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

//로그인한 회원만 접근가능
Member::isMember();

$Order = new ORDER;

$limit = 10;
$page = $_POST['page'];
$offset = ($page-1)*$limit;

parse_str($_POST['search'], $search);

$where_arr = array();
if(MEMID) {
    $where_arr[] = "member_id='".MEMID."'";
}
else {
    $where_arr[] = "member_id=''";
}
foreach($search as $f=>$v) {
    if(!$v) continue;
    switch($f) {
        case 'date_s':
            $where_arr[] = "op.date_insert >= '".$v." 00:00:00'";
            break;
        case 'date_e':
            $where_arr[] = "op.date_insert <= '".$v." 23:59:59'";
            break;
    }
}

$where_arr[] = "op.cs_type !='0'";
$where = implode(' AND ',$where_arr);
$rs = $Order->getBasicPaging($where, $limit, $page);
$cnt_total = $rs['count'];

// pre($rs['list']);

//페이징
$paging_config = array(
    'total'=>$cnt_total,
    'block_size'=>5,
    'list_size'=>$limit,
    'page_current'=>$page,
    'url_type'=>'javascript',
    'url'=>'OrderList.load'
);

$Pagination = new PAGINATION($paging_config);
$paging = $Pagination->getPageSet();



$assign = array(
    'count'=>array('total'=>$cnt_total),
    'list'=>$rs['list'],
    'paging'=>$paging
);

_render('mypage/order_cs_list.inner.html', $assign, DIR_M.'/template');

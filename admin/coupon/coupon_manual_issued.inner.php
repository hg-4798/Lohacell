<?php
/**
 * 상품아이콘목록
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
$Coupon = new COUPON();
$Member = new MEMBER();
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
        case 'sv': //검색어검색
            $sf = $search['sf'];
            $sv = array_filter(explode("\n", $v));
            if(empty($sv)) continue;
            $tmp = array();
            foreach($sv as $vv) {
                $vv = trim($vv);
                $tmp[] = "{$sf} LIKE '%{$vv}%'";
            }

            $where_arr[] = '('.implode(' OR ', $tmp).')';
            break;
        case 'group_code': //날짜검색(종료)
            $v = trim($v);
            $where_arr[] = "{$field} = '{$v}'";
            break;
        case 'date_start': //날짜검색(시작)
            $v = str_replace('-','',$v);
            $where_arr[] = "date >= '{$v}000000'";
            break;
        case 'date_end': //날짜검색(종료)
            $v = str_replace('-','',$v);
            $where_arr[] = "date <= '{$v}235959'";
            break;
    }
}

if(!empty($where_arr)) {
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}

$memberlist = $Member->getMemberSearch($where, $limit, $offset);



//pre($memberlist);
//페이징
$paging_config = array(
	'total'=>$memberlist['total'],
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'CouponManualIssues.loadPage'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();


$assign = array(
	'list'=>$memberlist['list'],
    'total'=>$memberlist['total'],
    'member_data'=>$where,
	'paging'=>$paging
);

_render("coupon/coupon_manual_issued.inner.html", $assign, 'admin/template');

?>

<?php
/**
 * 상품아이콘목록
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
$product =  new PRODUCT;
$Coupon = new COUPON();
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
        case 'coupon_idx': //쿠폰idx
            $where_arr[] = "info.idx = '{$v}'";
            break;
        case 'sv': //검색어검색
            $sf = $search['sf'];
            $sv = array_filter(explode("\n", $v));
            if(empty($sv)) continue;
            $tmp = array();
            foreach($sv as $vv) {
                $vv = trim($vv);
                $tmp[] = "issue.{$sf} LIKE '%{$vv}%'";
            }

            $where_arr[] = '('.implode(' OR ', $tmp).')';
            break;
		case 'used': //날짜검색(시작)
			$sf = trim($field);
			$where_arr[] = "issue.{$sf} = '{$v}'";
			break;
        case 'date_start': //날짜검색(시작)
            $sf = trim($search['date_sf']);
            $v = trim($v);
            $where_arr[] = "info.{$sf} >= '{$v} 00:00:00'";
            break;
        case 'date_end': //날짜검색(종료)
            $sf = trim($search['date_sf']);
            $v = trim($v);
            $where_arr[] = "info.{$sf} <= '{$v} 23:59:59'";
            break;
    }
}

if(!empty($where_arr)) {
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}

$couponlist = $Coupon->getDetailCoupon($where, $limit, $offset);
$type_publish = $Coupon->type_publish;
//print_r($couponlist);
//페이징
$paging_config = array(
	'total'=>$couponlist['total'],
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'CouponDetailList.loadPage'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();


$assign = array(
	'list'=>$couponlist['list'],
    'total'=>$couponlist['total'],
	'paging'=>$paging,
    'type_publish' => $type_publish
);

_render("coupon/coupon_detail.inner.html", $assign, 'admin/template');

?>

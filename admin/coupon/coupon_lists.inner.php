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
//print_r($search);
$type_publish = $Coupon->type_publish;

//검색
$where_arr = array();
foreach($search as $field=>$v) {
	if(!$v || in_array($field, array('sf', 'date_sf')) || empty($v)) continue;
	switch($field){
		case 'coupon_name': //구폰명
			$sf = $field;
            $where_arr[] = "{$sf} LIKE '%{$v}%'";
            break;
		case 'type_use': //쿠폰 타입
			$sf = trim($field);
			$where_arr[] = "{$sf} = '{$v}'";
			break;
		case 'type_publish': //발급구분
			$sf = trim($field);
			$v = trim($v);
			$where_arr[] = "{$sf} = '{$v}'";
			break;
	}
}

if(!empty($where_arr)) {
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}

$couponlist = $Coupon->getCouponList($where, $limit, $offset);

//페이징
$paging_config = array(
	'total'=>$couponlist['total'],
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'CouponList.loadPage'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();


$assign = array(
	'list'=>$couponlist['list'],
    'total'=>$couponlist['total'],
	'paging'=>$paging,
    'type_publish' => $type_publish
);

_render("coupon/coupon_lists.inner.html", $assign, 'admin/template');

?>

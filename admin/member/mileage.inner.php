<?php
/**
 * 회원별 마일리지 목록
 * @author hjlee
 * @since 2018-09-26
 */

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Mileage = new MILEAGE;

$limit = 10;
$page = $_POST['page'];
$offset = ($page-1)*$limit;

parse_str($_POST['search'], $search);

$where = "mem_id='".$search['mem_id']."'";
$rs = $Mileage->getPaging($where, $limit, $page);

$cnt_total = $rs['count'];
 //페이징
$paging_config = array(
	'total'=>$cnt_total,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'MemberMileage.load'
);

$Pagination = new PAGINATION($paging_config);
$paging = $Pagination->getPageSet();



$assign = array(
	'count'=>array('total'=>$cnt_total),
	'sum'=>$rs['sum'],
	'list'=>$rs['list'],
	'paging'=>$paging
);

_render("member/mileage.inner.html", $assign, 'admin/template');


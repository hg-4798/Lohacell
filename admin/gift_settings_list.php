<?php
/**
 * Created by PhpStorm.
 * User: 커머스랩97
 * Date: 2018-07-19
 * Time: 오후 6:52
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once("access.php");

$Gift = new Gift;

$limit = 10;
$page = $_POST['page'];
$page_offset = 0;
if($page) {
	$page_offset = ($page - 1) * $limit;
}

$Gift_list = $Gift->get_gift_list('',$page_offset, $limit);

//페이징
$paging_config = array(
	'total'=>$Gift_list['count'],
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript', //비동기 처리때 javascript 사용
	'url'=>'GiftSettings.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

$assign = array(
	'total' => $Gift_list['count'],
	'list' => $Gift_list['gifts'],
	'paging'=>$paging
);


_render("promotion/gift_settings_list.html", $assign, 'admin/template');
?>

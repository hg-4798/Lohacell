<?php

/**
 * 댓글목록
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Promotion = new PROMOTION;

parse_str($_POST['search'], $search);
$page = ($_POST['page'])?$_POST['page']:'1';
$limit = ($search['limit'])?$search['limit']:'20';
$offset = ($page-1)*$limit;

$tbl = $Promotion->tbls['promo_comment'];

$orderby = 'writetime DESC';//정렬
$sql = "SELECT * FROM {$tbl} WHERE parent='".$search['no']."'";

$count = $Promotion->adodb->getOne("SELECT COUNT(*) AS total FROM ({$sql}) x");

$num = $count-$offset;
$parent_key = '';
$list = array();



$sql .= " ORDER BY {$orderby} OFFSET {$offset} LIMIT {$limit}";
// ECHO $sql;
$rs = $Promotion->adodb->Execute($sql);

$num = $count-($offset);
while($row = $rs->FetchRow()) {
	$photo_img_arr = unserialize($row['photo_img']);
	$photo_img = array();
	if(is_array($photo_img_arr)) {
		foreach($photo_img_arr as $v) {
			if(!is_file(DOC_ROOT.$v['path'])) continue;
			$photo_img[] = $v;
		}
	}

	$row['photo_img'] = $photo_img;
	
	
	$row['idx'] = $num--;
	$list[] = $row;
}

//페이징
$paging_config = array(
	'total'=>$count,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'EventComment.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();


$assign = array(
	'count'=>$count,
	'list'=>$list,
	'paging'=>$paging
);


_render("promotion/event_comment.inner.html", $assign, 'admin/template');
?>
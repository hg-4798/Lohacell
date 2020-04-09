<?php
if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");


$Design = new DESIGN;
$Review = new REVIEW;

$argu = $_POST;

parse_str($_POST['search'], $search);
$page = ($argu['page'])?$argu['page']:1;
$limit = (isset($search['limit']))?$search['limit']:3;
$sort = $search['sort'];
$search = $search['productname'];
$offset = ($page-1)*$limit;

if($sort=='date'){
	$sort = "ORDER BY a.best_type DESC,a.date DESC ";
}else{
	$sort = "ORDER BY a.best_type DESC, a.marks DESC, a.date DESC";
}
$review_list = $Review->getTypeReview($limit,$offset,$search,$sort,'MOBILE');

$sql = "SELECT a.productcode,a.id, a.marks,a.date,a.content,a.subject,a.upfile,a.upfile2,a.type, p.maximage,p.minimage,p.tinyimage,p.productname,a.num,a.best_type,p.phrase_ad FROM tblproductreview a LEFT JOIN tblproduct p ON a.productcode = p.productcode WHERE p.pr_type !=3  AND a.best_type='1' ORDER BY a.best_type DESC, a.date DESC ";
//echo $sql;
$rs = $Review->adodb->Execute($sql);
$best_list = array();
while ($row = $rs->FetchRow()) {
	$row['no'] = $no;
	if(!empty($row['upfile'])){
		$row['image'][]= $row['upfile'];
	}
	if(!empty($row['upfile2'])){
		$row['image'][]= $row['upfile2'];
	}
	$best_list[]= $row;
	$no--;
}

//페이징
$paging_config = array(
    'total'=>$review_list['total'],
    'block_size'=>5,
    'list_size'=>$limit,
    'page_current'=>$page,
    'url_type'=>'javascript',
    'url'=>'ReviewList.loadPage'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

//pre($review_list);

$assign = array(
    'review_list'=>$review_list['list'],
    'total'=>$review_list['total'],
    'paging'=>$paging,
    'type'=>$type,
	'best_list'=>$best_list
);
if(substr($_SERVER['REMOTE_ADDR'],0,10) == "218.234.32" || $_SERVER['REMOTE_ADDR'] == "59.9.185.17"){
    _render('review/reviewlist_inner2.html', $assign, MDir.'/template');
}else {
    _render('review/reviewlist_inner.html', $assign, MDir.'/template');
}


?>
<?php
/**
 * 리뷰 탑 영상/배너관리
 * @author 이기연
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("../header.php");
$Review = new Review();

$idx = $_GET['idx'];
$tbl = $Review->tbls['review_banner'];

if($idx) {
	$row = $Review->adodb->getRow("SELECT * FROM {$tbl} WHERE idx='{$idx}'");
	$dml = 'update';
}
else {
	$dml = 'insert';
	$row = array(
		'banner_img'=>'/admin/images/product/noimg.jpg',
		'banner_img_m'=>'/admin/images/product/noimg.jpg'
	);

}

//pre($row);
$assign = array(
	'dml'=>$dml,
	'row'=>$row

);


_render("review/video_write.html", $assign, 'admin/template');

include("../copyright.php");
?>
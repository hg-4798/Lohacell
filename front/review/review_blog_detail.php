<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Review = new REVIEW;
$idx = $_POST['idx'];
$tbl = $Review->tbls['review_blog'];

$blog_detail = $Review->adodb->getRow("SELECT * FROM {$tbl} WHERE idx='{$idx}'");
//print_r($review_detail);
$assign = array(
    'blog_detail' =>$blog_detail
);
_render('review/review_blog_detail.html', $assign);
?>

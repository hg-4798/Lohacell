<?php
/**
 * 메인리뷰설정 - 선택된리뷰목록
 * 
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Design = new DESIGN;
$Common = new COMMON;
$tmp = $_POST['tmp'];

$main_review_list = $Design->main_review_list($tmp);


$assign = array(
    'main_review_list' =>$main_review_list,
);
_render("design/main_review.selected.html", $assign, 'admin/template');

<?php
/**
 * 메인리뷰설정 - 베스트리뷰목록
 *
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Common = new COMMON;
$Design = new DESIGN;
$category = new CATEGORYLIST;
$categorycode = $_POST['categorycode'];
$category_banner_list = $Design->category_banner_list($categorycode, 'admin');
$nav = $category->getNav($categorycode);

$assign = array(
    'list'=>$category_banner_list,
    'categorycode'=>$categorycode,
    'nav'=>$nav,
);
_render("design/category_inner.html", $assign, 'admin/template');

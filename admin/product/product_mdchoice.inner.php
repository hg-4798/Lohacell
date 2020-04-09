<?php
/**
 * 상품아이콘목록
 * @author 이혜진
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/product.class.php"); //product class
include_once($Dir."lib/category.class.php"); //product class

$product = new PRODUCT;
$category = new CATEGORYLIST;

parse_str($_POST['search'], $search);
$category_code = ($search['category_d3']=="")?$search['category_d2']:$search['category_d3']; //3차카테고리 기준으로 검색

$list = $product->getChoiceByCategory($category_code,'admin');
$nav = $category->getNav($category_code);

$assign = array(
    'class'=>array(
        'product'=>$product
    ),
    'nav'=>$nav,
    'list'=>$list
);

_render("product/product_mdchoice.inner.html", $assign, 'admin/template');

?>

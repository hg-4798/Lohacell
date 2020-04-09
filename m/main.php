<?php
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
COMMON::auto_viewport();


include('../m/include/top.php');
include('../m/include/gnb.php');

$Design = new DESIGN;

$best_item_list = $Design->best_item('87');
$middle_banner_list = $Design->middle_banner('85');
$bottom_banner_list = $Design->bottom_banner('118');
$new_arrivals_list = $Design->new_arrivals_banner();
$rolling_list = $Design->rolling_banner('77');
$instagram_list = $Design->instagram('6');
$review_banner_list = $Design->review_banner_list();
$event_popup_list = $Design->getEventPopupList('M');
//exdebug($instagram_list);

$assign = array(
    'best_list'=>$best_item_list,
    'middle_banner_list'=>$middle_banner_list,
    'new_arrivals_list'=>$new_arrivals_list,
    'bottom_banner_list'=>$bottom_banner_list,
    'rolling_list'=>$rolling_list,
    'instagram_list'=>$instagram_list,
    'review_banner_list'=>$review_banner_list,
    'img'=>array(
        'main'=> $Dir.DataDir."shopimages/mainbanner/",
    ),
    'url'=>array(
        'productdetail'=>'/m/productdetail.php'
    ),
	'layers'=>$event_popup_list,              // layers

);
_render('main/main.html', $assign, MDir.'template');

include('../m/include/bottom.php');
?>

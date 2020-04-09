<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


$adodb = adodb_connect();
//$item_type	= $_POST['item_type'];

switch($_POST['item']) {
	case 'option':
		$fields = parse_ini_file("./conf/excel/product_option.ini", true);
		$item_type = 'option';
	break;
	case 'product':
	default:
		$fields = parse_ini_file("./conf/excel/product.ini", true);
		$item_type = 'product';
	break;
}

$save_item=array();
$sql = "SELECT * FROM tblexcelinfo WHERE mem_id='".$_ShopInfo->getId()."' AND item_type='".$item_type."' ORDER BY regdt DESC";
$save_item = $adodb->getArray($sql);

$fp = fopen('php://temp', 'w+');

$arritem = array();
foreach ( $fields as $key => $arr ){
	$arrtmp	= array();
	if ( $arr['down'] == 'Y') {
		$arrtmp['text']	= $arr['text'];
		$arrtmp['val']		= $key;
		$arritem[]			= $arrtmp;
	}
}

$assign = array(
	'search'=>$_POST['search'],
	'item_type'=>$item_type,
	'arritem'=>$arritem,
	'save_item'=>$save_item
);
_render("product/product_excel.html", $assign, 'admin/template');
?>
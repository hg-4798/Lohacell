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

$idx = $_GET['idx'];
$mode = 'reg';
if($idx) {
    $mode = 'mod';
}
$tbl_main	= 'tblgiftinfo';

$Gift = new Gift;
if($idx) {
	$Gift_info = $Gift->getGiftRow($idx);
}
else {
	$Gift_info = array(
		'quantity_sale'=>'0'
	);
}


$assign = array(
	'giftinfo' => $Gift_info,
	'mode' => $mode,
	'idx' => $idx
);

_render("promotion/gift_settings_form.html", $assign, 'admin/template');
?>

<?php return false; ?>
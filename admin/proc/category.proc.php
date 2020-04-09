<?php
/**
 * 카테고리관련 프로세싱
 * @author  이혜진(stickcandy81@nate.com)
 */

$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");

$mode = $_POST['mode'];
$act = $_POST['act'];
$Category = NEW CATEGORYLIST;

if($mode == 'get_children') {
	$children = $Category->getChildren($_POST['parent']);

	return_json(true, '', $children);
}
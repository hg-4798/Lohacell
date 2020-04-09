<?php
/**
 * 관리자로그인처리
 */

$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");

$mode = $_POST['mode'];
if($mode == 'login') {
	$Common = new COMMON;
	
	$mem_id = $_POST['mem_id'];
	$mem_pw = $_POST['mem_pw'];

	$ssltype=$_POST["ssltype"];
	$sessid=$_POST["sessid"];

	$history="-1";
	$ssllogintype="";
	if($ssltype=="ssl" && ord($mem_id) && strlen($sessid)==32) {
		$ssllogintype="ssl";
		$history="-2";
	}

	$sql = "SELECT * FROM tblshopinfo ";
	$shop_info = $Common->adodb->getRow($sql);

	if(!$shop_info) {
		return_json(false,"쇼핑몰 정보 등록이 안되었습니다. \n쇼핑몰 설정을 먼저 하십시요");
	}

	// pre($shop_info);
	////echo 'a';

	$_ShopInfo->adminLogin();
}
?>
<?php
/**
 * 로그아웃처리
 */
$Dir = "../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Member = new MEMBER();

$rs = $Member->logout();
if($rs) {
	if(DIR_VIEW == '/m') header("location: /m/main.php");
	else header("location: /main/main.php");
}
else {
}
?>
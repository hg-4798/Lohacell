<?php
if($_SERVER['HTTPS']!="on") {
	header("HTTP/1.0 404 Not Found");
	exit;
}

#쇼핑몰 입점사 로그인
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$shopurl=$_POST["shopurl"];
$id=$_POST["id"];
$passwd=$_POST["passwd"];

if(!preg_match("/{$shopurl}/i",$_SERVER['HTTP_REFERER'])) {
	exit;
}

if(strlen($shopurl)==0 || strlen($id)==0 || strlen($passwd)==0) {
	alert_go('로그인 정보가 올바르지 않습니다.',-1);
}

$sql = "SELECT * FROM tblvenderinfo WHERE id='".$id."' AND passwd=md5('".$passwd."')";
$result = @pmysql_query($sql,get_db_conn());
if($row=@pmysql_fetch_object($result)) {
	$authkey = md5(uniqid(""));
	$sql = "UPDATE tblvenderinfo SET authkey='".$authkey."' WHERE id='".$id."' ";
	@pmysql_query($sql,get_db_conn());

	echo "<html><head><title></title></head><body>\n";
	echo "<form name=form1 method=post action=\"http://".$shopurl."/".RootPath.VenderDir."loginproc.php\">\n";
	echo "<input type=hidden name=ssltype value=\"ssl\">\n";
	echo "<input type=hidden name=id value=\"".$id."\">\n";
	echo "<input type=hidden name=sessid value=\"".$authkey."\">\n";
	echo "</form>\n";
	echo "<script>document.form1.submit();</script>\n";
	echo "</body></html>\n";
} else {	
	alert_go('비밀번호가 틀립니다.',-1);
}

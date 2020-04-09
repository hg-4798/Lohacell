<?php 
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");



$sql = "UPDATE tblmember SET authidkey='logout' WHERE id='".$_ShopInfo->getMemid()."' ";
	pmysql_query($sql,get_db_conn());
	$_ShopInfo->SetMemNULL();
	$_ShopInfo->Save();
?>
<body onload="logout_gourl()">
<SCRIPT LANGUAGE="JavaScript">
<!--
function logout_gourl() {
	document.location.href="/";
}
//-->
</SCRIPT>
</body>

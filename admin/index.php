<?php
$Dir="../";

include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$admin_id = $_ShopInfo->getId();
if(!$admin_id) {
	header('Location:/admin/login.php');
}
else {
	header('Location:order_list_all.php');
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>쇼핑몰 관리자</TITLE>
<!--<meta http-equiv="x-ua-compatible" content="IE=EmulateIE8" >-->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="CONTENT-TYPE" content="text/html; charset=UTF-8">
<link rel='shortcut icon' href="../static/img/common/favicon.ico" type="image/x-ico" >
</HEAD>
<frameset rows="*,0" border=0>
<frame src="login.php" name=mainframe scrolling=auto marginwidth=0 marginheight=0>
<frame src="about:blank" name=hiddenframe scrolling=no marginwidth=0 marginheight=0>
</frameset>
</frameset>
</body>
</html>
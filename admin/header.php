<?php // hspark
if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}
include_once(DOC_ROOT."/lib/adminlib.php");
include_once(DOC_ROOT."/conf/config.php");


$_SHOPINFO = get_object_vars($_ShopInfo);
$assign_header = array(
	'layout'=>$layout
);

_render("include/top.html", $assign_header, 'admin/template');


?>

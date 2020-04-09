<?php // hspark
$layout = "inc";
include("./header.php");
$mem_id = $_GET['search_keyword'];
$assign = array(
	'member_id'=>$mem_id
);
_render("order/crm_order.html", $assign, 'admin/template');

?>
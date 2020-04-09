<?php
/**
 * 주문서 배송지목록
 * @author hjlee
 */

$Dir="../../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Order = new ORDER;

$list = $Order->getDestinationList(MEMID);

$assign = array(
	'list'=>$list
);


//렌더링
_render("order/order.delivery.html", $assign, DIR_M.'/template');

?>
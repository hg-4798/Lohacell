<?php
/**
 * 카드사혜택 보기
 * @author hjlee
 */

$Dir="../../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Common = new COMMON;
$card = $Common->getConfig('card', 'section');

$assign = array(
    'card'=>$card
);

//렌더링
_render("order/order.benefit.html", $assign);

?>
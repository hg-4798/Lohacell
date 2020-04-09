<?php
/**
 * CS 통합리스트
 * @author 이혜진
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Order = new ORDER;

include("../header.php");

$assign = array(
);

_render("cscenter/cs_all.html", $assign, 'admin/template');

include("../copyright.php");
?>

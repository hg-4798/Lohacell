<?php
/**
 * 주문서
 */

if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

//로그인체크(로그인회원만 접근가능)
MEMBER::isMember();


$Order = new ORDER; //주문클래스
$Product = new PRODUCT;

$assign = array(
	'search'=>array(
		'date_s'=>date('Y-m-d', strtotime('-1 month')),
		'date_e'=>date('Y-m-d')
	)
);

_render('mypage/orderlist.html', $assign, DIR_M.'/template');

include('./include/bottom.php');

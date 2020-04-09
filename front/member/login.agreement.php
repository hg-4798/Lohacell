<?php
//로그인 비회원 동의

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Common = new COMMON;
$privacy_buy = $Common->getConfig('privacy_buy');
$privacy_buy = htmlspecialchars_decode($privacy_buy);

$assign = array(
	'url'=>array(
		'order'=>$_POST['url'],
		'join'=>'/front/member_certi.php'
	),
    'privacy_buy' => $privacy_buy
);
_render('member/login.agreement.html', $assign);


?>
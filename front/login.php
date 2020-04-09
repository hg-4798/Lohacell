<?php
/**
 * 로그인
 */

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
// include_once($Dir."lib/shopdata.php");
// include_once($Dir."conf/config.php");
// include_once($Dir."lib/cache_main.php");
include_once($Dir."conf/config.sns.php"); //sns설정

# SNS 관련 세션값 초기화
$_ShopInfo->setCheckSns("");
$_ShopInfo->setCheckSnsLogin("");
$_ShopInfo->Save();





//리턴url 정보가공
$chUrl = $_REQUEST['chUrl'];
$ret_url = $_REQUEST['ret_url'];
$rtn_info = parse_url($chUrl);


//로그인 체크
if(MEMID) {
	$return_url = ($chUrl)?$chUrl:'/';
	Header("Location:".$return_url);
	exit;
}



include('./include/top.php');
include('./include/gnb.php');


switch($rtn_info['path']) {
	case 'order.php': //주문서
		$template_type = 'order';
		break;
	default:
		$template_type = 'normal';
		break;
}

//아이디저장체크
if($_COOKIE['JID']) {
	$save_id = base64_decode($_COOKIE['JID']);
}
else $save_id = false;

$assign = array(
	'template_type'=>$template_type,
	'cfg'=>array(
		'naver'=>$snsNvConfig,
		'facebook'=>$snsFbConfig,
		'kakao'=>$snsKtConfig
	),
	'rtn'=>array(
		'url'=>urlencode($chUrl),
		'path'=>$rtn_info['path'],
		'query'=>$rtn_info['query'],
		'ret_url'=>$ret_url
	),
	'save_id'=>$save_id
);


_render('member/login.html', $assign);

include('./include/bottom.php');
?>
<?php

//2depth카테고리
$Category = new CATEGORYLIST();
$Design = new DESIGN();
$Widget = new WIDGET();
$gnb = $Category->getChildren('001000000000');

//임직원 기획전 조회
$promotion = new PROMOTION;
$executives_banner = $promotion->getPromoListAndCnt('','','','','Y');

//임직원 기획전 배너 닫았는지 확인
$exe_banner_close = $_COOKIE['exe_banner_close'];

//최근검색어 조회
$spc_arr = $Widget->getRecentWord();

// 기본검색어, 인기검색어 조회
$defaultAndBestKeyword = $Widget->getDefaultAndBestKeyword();
$best_keyword_list_cnt = $defaultAndBestKeyword['best_keyword_list_cnt']; //인기검색어 개수
$best_keyword_list = $defaultAndBestKeyword['best_keyword_list']; //인기검색어
$default_keyword = $defaultAndBestKeyword['default_keyword']; //기본검색어

$searchword = $_REQUEST['search']? : $default_keyword;

//로그인 chUrl 중복제거
unset($_GET['chUrl']);
$login_churl = $_SERVER['PHP_SELF'];
if($_GET) $login_churl .= '?'.http_build_query($_GET);


$gnb_banner_list = $Design->gnb_banner('113');
$assign_gnb = array(
	'gnb'=>$gnb,
	'gnb_banner_list'=>$gnb_banner_list,
	'img'=>array(
		'main'=> $Dir.DataDir."shopimages/mainbanner/",
		'error'=>"/static/img/common/img_default_306.gif"
	),
	'chUrl'=>'chUrl='.urlencode($login_churl),
	'best_keyword'=>$best_keyword_list,
	'defaultkeyword' => $default_keyword,
	'best_keyword_list_cnt' => $best_keyword_list_cnt,
	'searchword' => $searchword,
	'recent_keyword' => $spc_arr,
	'executives_banner' => $executives_banner['list'],
    'exe_banner_close' => $exe_banner_close,
	'memId'=>$_ShopInfo->getMemid()
);

_render('_include/gnb.html', $assign_gnb);

?>


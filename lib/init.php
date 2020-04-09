<?php
if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}
//쉘실행일 경우 true를 반환
function is_cli() { return (php_sapi_name() == 'cli'); }

define("DirPath", $Dir);
define("RootPath", "");


$host = ($_SERVER["HTTPS"] == 'on')?'https://'.$_SERVER["HTTP_HOST"]:'http://'.$_SERVER["HTTP_HOST"];
define("HOST", $host);

define('BLADE_EXT', '.htm'); 
define("AdminDir", "admin/");
define("MainDir", "main/");
define("AdultDir", "adult/");
define("AuctionDir", "auction/");
define("BoardDir", "board/");
define("FrontDir","front/");
define("GongguDir", "gonggu/");
define("PartnerDir", "partner/");
define("RssDir", "rss/");
define("TempletDir", "templet/");
define("SecureDir", "ssl/");
define("VenderDir", "vender/");
define("CashcgiDir", "cash.cgi/");
define("AuthkeyDir", "authkey/");
define("LibDir", "lib/");
define("MDir", "m/");

if(is_cli()) {
    define("DOC_ROOT", $_SERVER['HOME']."/public"); //DOCUMENT_ROOT
}else{
    define("DOC_ROOT", $_SERVER['DOCUMENT_ROOT']); //DOCUMENT_ROOT
}
define("DIR_FRONT", "/front");//프론트폴더
define("DIR_M", "/m"); //모바일폴더
define("DIR_ADMIN", '/admin'); //관리자폴더
define("PATH_UPLOAD_ROOT", DOC_ROOT.'/data'); //파일업로드경로

define("VER", time());
define("NOW", date('Y-m-d H:i:s'));
define("TIMESTAMP", date('Y-m-d H:i:s')); //현재시각(DB 날짜입력을 위함)

define("DataDir", "data/");
define("ImageDir", DataDir."shopimages/");

define("MinishopType", "OFF");

#암호/복호화 키입니다. (해당 쇼핑몰에서 꼭 수정하시기 바랍니다.)
define("enckey", "password");

#시스템 관리자 메일
define("AdminMail", "");

#암호/복호화 key 값
define("JayjunKey", "CommerceLabDefaultPassWordKey123");
#암호/복호화 iv 값
define("JayjunIvKey", "CommerceLabIvKey");

define("DIRECTBUYCATEGORY", '777000000001'); //바로구매상품 카테고리
define("ADDPRODUCTCATEGORY", '777000000002'); //추가선택(옵션)상품 카테고리
define("PRODUCT_LIMIT",12); //1페이지 상품노출수

include DOC_ROOT.DIRECTORY_SEPARATOR.DIR_ADMIN.'/_config/config.php';//전역설정값
include DOC_ROOT.DIRECTORY_SEPARATOR.DIR_ADMIN.'/_config/message.php';//ALERT/CONFIRM DEFINE
include DOC_ROOT.DIRECTORY_SEPARATOR.DIR_ADMIN.'/_config/menu.php';//관리자메뉴

//$device = (!!(FALSE !== strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile')) != 1) ?'PC':'MOBILE'; //접속디바이스
//define('DEVICE',$device);

$php_self=explode("/",$_SERVER['PHP_SELF']);

if($php_self[1]=="m"){
	$device='MOBILE';
}else{
	$device='PC';
}

define('DEVICE',$device);
$viewport = ($_COOKIE['VP'])?$_COOKIE['VP']:$device; //출력버전
$viewport = strtoupper($viewport);
define('VIEWPORT',strtoupper($viewport));

if($viewport == 'MOBILE') define('DIR_VIEW', DIR_M); //템플릿기본폴더
else define('DIR_VIEW', DIR_FRONT);

//KCP
define("PG","NHNKCP");

//주문서 유효시간(분)
define("ORDER_TIME_LIMIT",30);

//우편번호
$post_js = ($_SERVER['HTTPS'] =='on')?'https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js':'http://dmaps.daum.net/map_js_init/postcode.v2.js';
define("POST_JS", $post_js);

define("DIR_CDN", 'https://image.iknowione.co.kr/images/'); //이미지cdn주소

if($_SERVER['USER']=='jayjun') {
	define("IS_DEV", false); //실서버
}else{
	define("IS_DEV", true); //개발서버
}

#테이블정보
$cfg_tbl = array(

	'member'=>'tblmember', //회원
	'member_group'=>'tblmembergroup', //회원그룹
	'member_log' => 'tblmemberlog', //회원로그인로그

	'config'=>'tblconfig',
	'shopinfo'=>'tblshopinfo',

	'product'=>'tblproduct', //상품테이블
	'product_image'=>'tblmultiimages', //상품기타이미지
	'product_link'=>'tblproductlink', //상품카테고리정보
	'product_icon'=>'tblproduct_icon', //아이콘
	'product_timesale'=>'tblproduct_timesale', //기간할인
	'product_option'=>'tblproduct_option', //상품옵션_재고
	'product_review'=>'tblproductreview', //상품리뷰
	'review_banner'=>'tblreview_banner', //리뷰 배너관리
	'review_blog'=>'tblreview_blog', //리뷰 블로그관리
	'product_line'=>'tblproduct_line', //라인
	'product_display_log'=>'tblproduct_display_log', //ERP상품승인로그
	'product_log'=>'tblproduct_log', //상품관리로그
	'product_color'=>'tblproduct_color', //상품컬러관리
	'product_property'=>'tblproduct_property', //상품정보고시

	'like'=>'tblhott_like', //좋아요
	'basket'=>'tblbasket', //장바구니
	'order_temp'=>'tblorder_temp', //주문서(임시)
	'order'=>'tblorder_basic', //주문정보
	'order_product'=>'tblorder_product', //주문상품정보
	'order_payment'=>'tblorder_payment', //결제정보
	'order_payment_etc'=>'tblorder_payment_etc', //결제기타수단정보
	'order_gift'=>'tblorder_gift', //사은품
	'order_log'=>'tblorder_log', //주문로그
	'order_log_stock'=>'tblorder_log_stock', //주문재고처리로그
	'order_memo'=>'tblorder_memo', //관리용주문메모
	'order_cancel'=>'tblorder_cancel', //주문취소정보
	'order_refund'=>'tblorder_refund', //주문환불정보
	'order_refund_log'=>'tblorder_refund_log', //주문환불로그
	'order_return'=>'tblorder_return', //반품기본정보
	'order_return_product'=>'tblorder_return_product', //주문반품상품정보
	'order_exchange'=>'tblorder_exchange', //교환기본정보
	'order_exchange_product'=>'tblorder_exchange_product', //교환상품정보
	
	'destination'=>'tbldestination', //배송지정보
	'delicompany'=>'tbldelicompany', //택배사정보

	'category_banner'=>'tblcategory_banner', //배너 카테고리
	
	'banner_main'=>'tblmainbannerimg', //배너
	'banner_pc'=>'tbldesign_banner_pc', //배너 - pc배너 gnb/로그인/상품상세
	'banner_lnb'=>'tbldesign_banner_lnb', //배너 - 모바일 lnb
	'banner_promotion'=>'tbldesign_banner_promotion', //배너 - 프로모션

	'point'=>'tblpoint',
	'point_config'=>'tblpoint_config', //포인트설정

	'mileage'=>'tblmileage',//마일리지

	'store'=>'tblstore',//매장정보
	'gift'=>'tblgiftinfo', //사은품
	'instagram'=>'tblinstagram', //인스타그램

	'promo'=>'tblpromo', //이벤트/기획전
	'promo_comment'=>'tblboardcomment_promo', //이벤트댓글
	'coupon'=>'tblcouponinfo', //쿠폰정보
	'coupon_issue'=>'tblcouponissue', //쿠폰 발급정보
	'coupon_log'=>'tblcoupon_log', //쿠폰 로그
	'attach'=>'tblattach', //업로드파일그룹
	'attach_file'=>'tblattach_file', //업로드된 파일 개별정보

	'qna'=>'tblpersonal', //일대일문의

	'analytics'=>'tbl_analytics', //사이트접속raw 데이터
	'excel_info'=>'tblexcelinfo', //엑셀양식저장
	'area_deli'=>'tbldeliarea' //지역별배송비
);
<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
//include_once(DOC_ROOT.'/../_counter/conf.php'); //카운터 환경설정 파일 include

//인스턴스생성
$Product = new product();
$category = new CATEGORYLIST();
$widget = new widget();
$review = new review();
$Member = new MEMBER;
$Coupon = new COUPON();

// $Product->sync_timesale(); //타임세일


//parameter
$code = $_REQUEST["code"]; //카테고리코드
$productcode = $_REQUEST["productcode"]; //상품코드
$code = $Product->getCategoryFirst($productcode); //대표카테고리


//카테고리 매칭 유효성체크
$valid = $Product->checkValid($productcode);
if(!$valid) {
	alert_go('해당 분류가 존재하지 않습니다.',"/");
	exit;
}

$nav = $category->getNav($code); //상단네비게이션

//상세정보
$detail = $Product->getProductDetail($productcode);
switch($detail['pr_type']) {
	case '2': //바로구매상품
		break;
	case '3': //임직원상품
		if(STAFF_YN != 'Y') alert_go('잘못된 경로로 접근하였습니다.',"/");
		break;
	case '4': //추가구매상품
		alert_go('잘못된 경로로 접근하였습니다.',"/");
		break;
}

//스크립트 삽입용 '문자와 " 슬래시 처리한 상품명
$slashes_productname = addslashes($detail['productname']);

//상세페이지 노출가능체크(구매가능)
$valid_buy = $Product->authBuyer($detail);
if(!$valid_buy) {
	alert_go($Product->error_msg,"/");
}

//상품기타이미지
$image_etc = array_filter(array_values($Product->getImageEtc($detail['productcode']))); //상품기타이미지
if(is_array($image_etc)) {
	foreach($image_etc as &$v) {
		$v.='?'.$Product->ver;
	}
}
else $image_etc = array();
$detail['image_etc'] = $image_etc;


//배송비정보
switch($detail['deli']){
	case '1' :
		//기본배송비 무료
		$delivery = array('deli_basefee' => 0,
				    'deli_basefeetype' => 0,
				    'deli_miniprice' => 0,
				    'deli_miniprice_staff' => 0);
		break;
	default :
		//기본배송비
		$delivery = $Product->getDeilvery();
		break;
}

//쿠폰
if($detail['except_coupon'] != 'Y') {
	$coupon = DownPossibleCoupon($productcode); //쿠폰적용제외상품인경우
}
else $coupon = array();

//배송/반품안내
$deil_info = explode('=',$_data->deli_info);


//추천상품
$recommend = $Product->getRecommendByCategory($detail['code_represent']); //대표카테고리로 추천상품 가져오기

//리뷰
$review_average = $review->getAverageMarks($productcode); //평균별점
$review_average_rate = ($review_average*100/5); //평균별점(%, 5점만점)
$review_count = $review->getCount($productcode); //리뷰수
$review_auth = $review->getAuth($productcode); //리뷰작성권한체크

//회원정보
$member_info = $Member->getMemberRow(MEMID);

$Product->setToday('prd_'.$productcode);//오늘 본상품 쿠키저장
$tmp_code = substr($code,0, 6);
$choice_list = $Product->getChoiceByCategory($tmp_code,'detail');//함께 쓰면 좋은 제품
$coupon_list = $Coupon->getProductCoupon($productcode);

//상품정보고시
$property = $Product->getProperty($productcode);

//카테고리명 가져오기
$category_name = $Product->getCategoryName($productcode);

//템플릿변수정의
$assign = array(
	'member'=>$member_info,
	'banner'=>$banner,
	'url'=>array(
		'list'=>'/front/productlist.php',
		'detail'=>'/front/productdetail.php',
		'qna'=>'/front/mypage_personalwrite.php',
		'promotion'=>'/front/promotion_detail.php'
	),
	'cfg'=>array(
		'productcode'=>$productcode,
		'catecode'=>$code, //카테고리코드
		'delivery'=>$delivery,
		'delivery_desc'=>$deil_info[2] //배송/반품안내
	),
	'recommend'=>$recommend,
	'review'=>array(
		'count'=>$review_count,
		'average'=>$review_average,
		'average_rate'=>$review_average_rate,
		'auth'=>$review_auth
	),
	'coupon'=>$coupon,
	'nav'=>$nav,
	'siblings'=>$siblings,
	'detail'=>$detail,
	'gift'=>$gift[0],
	'choice_list'=>$choice_list,
	'coupon_list'=>$coupon_list,
	'property'=>$property,
    //'cafe24_key' => $cafe24_counter_key, //cafe24 counter key
	'category_name' => $category_name,
	'slashes_productname'=>$slashes_productname
);
//pre($detail);
//렌더링
include('../front/include/top.php');
include('../front/include/gnb.php');

_render("product/detail.html", $assign);

include('../front/include/bottom.php');

?>

<?
// 퇴사자 강제 로그아웃 2018년1월4일 이전 로그아웃 안한 회원
//	if($_SERVER["REMOTE_ADDR"] == "218.234.32.36"){
$staff_id = substr($_ShopInfo->getMemid(), 0, 2);
if($_ShopInfo->staff_yn == "N" && $staff_id == "sw"){
    list($log_data)=pmysql_fetch_array(pmysql_query("select date from tblmemberlog where id='".$_ShopInfo->getMemid()."' order by date desc limit 1" ));
    if($log_data < "20180104000000"){
        $logouturl = $Dir.MainDir."main.php?type=logout";
        Header("Location: ".$logouturl);
    }
}
//	}
?>
<?php
/*********************************************************************
// 파 일 명		: tem_top001.php
// 설     명		: 상단 템플릿
// 상세설명	: 상단 ( 대메뉴, 검색, 로그인, 회원가입) 템플릿
// 작 성 자		: 2015.11.02 - 김재수
// 수 정 자		: 2016.07.28 - 김재수
// 수 정 자		: 2017.01.20 - 위민트
//
 *********************************************************************/


shopSslChange(); // ssl 처리 2016-12-08 유동혁

include_once($Dir."lib/basket.class.php");  // 장바구니 내용을 구하기 위해서

// 쿼리 위민트 170205
include_once("tem_top001_sql.php");


$mobileBrower = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS|iPad)/';

// 모바일인지 pc인지 체크
if(preg_match($mobileBrower, $_SERVER['HTTP_USER_AGENT']) && !$_GET[pc]) {

    $m_referrer_tmp			= parse_url($_SERVER['HTTP_REFERER']);
    $m_referrer_url			= $m_referrer_tmp['host'];

    if ((strpos($_SERVER["REQUEST_URI"],'/front/') !== false || strpos($_SERVER["REQUEST_URI"],'/board/') !== false) && $m_referrer_url != $_SERVER['HTTP_HOST']) { // 서브페이지로 올 경우에만 적용하고 아닐경우는 index.php 에서 경로 재설정을 한다.
        //게시판일 경우
        if ($_GET['board']) {
            $mainurl= str_replace('/board/','/m/',$_SERVER["REQUEST_URI"]);
            if ($_GET['pagetype'] == 'view') { // 상세보기 일 경우
                if ($_GET['board'] == 'event') { // 이벤트 상세 보기일 경우
                    $mainurl= "/m/event_view.php";
                } else {
                    $mainurl= "/m/board_view.php";
                }
                $mainurl .= "?board=".$_GET['board']."&boardnum=".$_GET['num'];
            }
        } else {
            $mainurl= str_replace('/front/','/m/',$_SERVER["REQUEST_URI"]);
            $mainurl= str_replace('csfaq.php','customer_faq.php',$mainurl); // FAQ 경로 재설정
        }
        //echo $mainurl;
        Header("Location: ".$mainurl);
        exit;
    }
}

// productlist.php 의 code
$productlist_code   = $_GET['code'];
$productlist_code_a = substr($productlist_code, 0, 3);

// productdetail.php 의 productcode
$productdetail_code = $_GET['productcode'];

list($code_a,$code_b,$code_c,$code_d) = sscanf($productlist_code,'%3s%3s%3s%3s');
$code=$code_a.$code_b.$code_c.$code_d;
$thisCate = getDecoCodeLoc( $code );
$thisCate2 = getDecoCodeLoc($productdetail_code);

// 매장코드
$bridx      		= $_GET['bridx'];

//1:1 문의를 위한 회원 데이터를 가져온다.
$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
$result=pmysql_query($sql,get_db_conn());
if($row=pmysql_fetch_object($result)) {
    $hptemp	= explode('-',$row->mobile);
    $c_hp0		= $hptemp[0];
    $c_hp1		= $hptemp[1];
    $c_hp2		= $hptemp[2];
    $c_email	= $row->email;
}
pmysql_free_result($result);

#상품 페이스북 공유
$facebook_share = '';
if( $_GET['productcode'] ){
    $facebook_share = FacebookShare( $_GET['productcode'] );
    $twitter_share = TwitterShare( $_GET['productcode'] );
}

#프로모션 페이스북, 트위터 메타테그 생성 (2016-03-17 김재수 추가)
if (strpos($_SERVER["REQUEST_URI"],'promotion_detail.php') !== false && $_GET['idx']) {

    list($share_title, $share_content, $share_img)=pmysql_fetch_array(pmysql_query("select  title, content, thumb_img from  tblpromo WHERE idx = '".$_GET['idx']."'"));

    if( is_file($Dir.'/data/shopimages/timesale/'.$share_img) ){
        $share_thumb_img = "http://".$_SERVER[HTTP_HOST]."/data/shopimages/timesale/".$share_img;
    }

    $facebook_share  = "<meta property='og:site_name' content='".$_data->shoptitle."'/>\n";
    $facebook_share .= "<meta property=\"og:type\" content=\"website\" />\n";
    $facebook_share .= "<meta property=\"og:title\" content=\"".$_data->shoptitle."\" />\n";
    $facebook_share .= "<meta property=\"og:url\" content=\"http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]."\" />\n";
    $facebook_share .= "<meta property=\"og:description\" content=\"이벤트 - ".addslashes($share_title)."\" />\n";
    $facebook_share .= "<meta property=\"og:image\" content=\"".$share_thumb_img."\" />\n";


}else if(strpos($_SERVER["REQUEST_URI"],'magazine_detail.php') !== false && $_GET['no']){
    //매거진 상세 페이스북, 트위터 메타태그 추가(2016-09-24)
    list($share_title, $share_content, $share_img)=pmysql_fetch_array(pmysql_query("select  title, content, img_file from  tblmagazine WHERE no = '".$_GET['no']."'"));

    if( is_file($Dir.'/data/shopimages/magazine/'.$share_img) ){
        $share_thumb_img = "http://".$_SERVER[HTTP_HOST]."/data/shopimages/magazine/".$share_img;
    }

    $facebook_share  = "<meta property='og:site_name' content='".$_data->shoptitle."'/>\n";
    $facebook_share .= "<meta property=\"og:type\" content=\"website\" />\n";
    $facebook_share .= "<meta property=\"og:title\" content=\"".$_data->shoptitle."\" />\n";
    $facebook_share .= "<meta property=\"og:url\" content=\"http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]."\" />\n";
    $facebook_share .= "<meta property=\"og:description\" content=\"이벤트 - ".addslashes($share_title)."\" />\n";
    $facebook_share .= "<meta property=\"og:image\" content=\"".$share_thumb_img."\" />\n";


}else if(strpos($_SERVER["REQUEST_URI"],'lookbook_view.php') !== false && $_GET['no']){
    //룩북 상세 페이스북, 트위터 메타태그 추가(2016-09-24)
    list($share_title, $share_content, $share_img)=pmysql_fetch_array(pmysql_query("select  title, content, img_file from  tbllookbook WHERE no = '".$_GET['no']."'"));

    if( is_file($Dir.'/data/shopimages/lookbook/'.$share_img) ){
        $share_thumb_img = "http://".$_SERVER[HTTP_HOST]."/data/shopimages/lookbook/".$share_img;
    }

    $facebook_share  = "<meta property='og:site_name' content='".$_data->shoptitle."'/>\n";
    $facebook_share .= "<meta property=\"og:type\" content=\"website\" />\n";
    $facebook_share .= "<meta property=\"og:title\" content=\"".$_data->shoptitle."\" />\n";
    $facebook_share .= "<meta property=\"og:url\" content=\"http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]."\" />\n";
    $facebook_share .= "<meta property=\"og:description\" content=\"이벤트 - ".addslashes($share_title)."\" />\n";
    $facebook_share .= "<meta property=\"og:image\" content=\"".$share_thumb_img."\" />\n";

}



/*
// =====================================================================================================================================
// 장바구니
// =====================================================================================================================================
$Basket = new Basket();
$arrProdCode = array();
if($Basket->basket){
foreach( $Basket->basket as $bkVal ){
    array_push($arrProdCode, $bkVal->productcode);
}
}
$basket_products_html = MakeHeaderPreviewList('basket', count($arrProdCode), get_product_list($arrProdCode), '/front/basket.php');

// =====================================================================================================================================
// 위시리스트
// =====================================================================================================================================
$arrProdCode = array();
if ( $_ShopInfo->getMemid() != "" ) {
    $sql  = "SELECT productcode FROM tblwishlist WHERE id = '" . $_ShopInfo->getMemid() . "' ORDER BY wish_idx desc ";
    $result = pmysql_query($sql);

    while ( $row = pmysql_fetch_array($result) ) {
        array_push($arrProdCode, $row['productcode']);
    }
}
$wishlist_products_html = MakeHeaderPreviewList('wish', count($arrProdCode), get_product_list($arrProdCode), '/front/wishlist.php');

// =====================================================================================================================================
// 최근 본 상품
// =====================================================================================================================================
$today_product = today_product();
$recent_view_products_html = MakeHeaderPreviewList('view', count($today_product), $today_product, '/front/lately_view.php');
*/
//

// =====================================================================================================================================
// 검색어 리스트
// =====================================================================================================================================
$arrSearchKeyword = explode( ",", $_data->search_info['keyword'] );

// =====================================================================================================================================
// My Keyword
// =====================================================================================================================================
$arrMyKeyword = array();
if ( $_ShopInfo->getMemid() != "" ) {
    $result = pmysql_query($sql_mykeyword);
    while ( $row = pmysql_fetch_array($result) ) {
        array_push($arrMyKeyword, $row['keyword']);
    }
}

// 공지사항 1개
list($notice_num, $notice_title) = pmysql_fetch("SELECT  num, title  FROM tblboard WHERE board = 'notice' AND notice='0' AND deleted='0' AND pos = 0 AND depth = 0 ORDER BY thread, pos LIMIT 1");
// 장바구니
if ($_ShopInfo->getMemid()) { // 로그인 했을 경우
    // SHOPPING BAG
    //list($icon_gnb_basket_cnt)=pmysql_fetch_array(pmysql_query("select count(*) FROM tblbasket WHERE id='".$_ShopInfo->getMemid()."'"));
    #핫딜 상품 장바구니수량에 포함 안시키기위한 쿼리 수정2016-09-21
    /*	list($icon_gnb_basket_cnt)=pmysql_fetch_array(pmysql_query("select count(*) FROM tblbasket WHERE basketidx not in ( SELECT  a.basketidx FROM tblbasket a left join tblproduct b on(a.productcode=b.productcode) WHERE b.hotdealyn='Y' and id='".$_ShopInfo->getMemid()."' group by a.basketidx) and id='".$_ShopInfo->getMemid()."'"));*/
    list($icon_gnb_basket_cnt)=pmysql_fetch_array(pmysql_query("select count(*) FROM tblbasket a left join tblproduct b on a.productcode=b.productcode WHERE 1=1 and a.id='".$_ShopInfo->getMemid()."' and b.hotdealyn='N' and b.display='Y' group by a.id"));
} else {
    // SHOPPING BAG
    //list($icon_gnb_basket_cnt)=pmysql_fetch_array(pmysql_query("select count(*) FROM tblbasket WHERE id='' AND tempkey='".$_ShopInfo->getTempkey()."'"));
    #핫딜 상품 장바구니수량에 포함 안시키기위한 쿼리 수정2016-09-21
    /*	list($icon_gnb_basket_cnt)=pmysql_fetch_array(pmysql_query("select count(*) FROM tblbasket WHERE basketidx not in ( SELECT  a.basketidx FROM tblbasket a left join tblproduct b on(a.productcode=b.productcode) WHERE b.hotdealyn='Y' and id='' AND tempkey='".$_ShopInfo->getTempkey()."' group by a.basketidx) and  id='' AND tempkey='".$_ShopInfo->getTempkey()."'"));*/
    list($icon_gnb_basket_cnt)=pmysql_fetch_array(pmysql_query("select count(*) FROM tblbasket a left join tblproduct b on a.productcode=b.productcode WHERE 1=1 and a.id='' AND tempkey='".$_ShopInfo->getTempkey()."' and b.hotdealyn='N' and b.display='Y' group by a.id"));
}

//아울렛추가 201705
if(!$brand_idx) $bridx_class_on["main"]="class='on'";
else $bridx_class_on[$brand_idx]="class='on'";

//echo $_SESSION[brand_session_no];
if($_SESSION[brand_session_no]==""){
    if($_SERVER['PHP_SELF']=="/front/outlet.php"){
        $bridx_class_on["main"]="class=''";
        $_SESSION[brand_outlet]="Y";
    }else if($_SERVER['PHP_SELF']=="/index.htm"){
        unset($_SESSION[brand_outlet]);
    }else{
        $bridx_class_on["main"]="class=''";
    }
}


// 진행중인 프로모션이 있는지 확인
$currentDate = date('Y-m-d');
list($roulette_seq)=pmysql_fetch_array(pmysql_query("select serial from tblpromo where event_type='5'  and display_type in ('A','P') and hidden = 1  and start_date <= '".$currentDate."' and end_date >= '".$currentDate."' order by rdate desc , idx desc limit 1 "));


?>
<!doctype html>
<html lang="ko">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=1200,user-scalable=yes,target-densitydpi=device-dpi">
    <meta name="format-detection" content="telephone=no, address=no, email=no">
    <meta name="Keywords" content="<?=$_data->shopkeyword?>">
    <meta name="Description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
    <!-- 20170523 수정 -->
    <meta name="robots" content="index">
    <meta name="googlebot" content="index">

    <title><?=$_data->shoptitle?></title>

    <!-- 페이스북 쉐어 Start (2016.02.11 유동혁) -->
    <?=$facebook_share?>
    <!-- 페이스북 쉐어 End (2016.02.11 유동혁) -->
    <!-- 트위터 쉐어 Start (2016.02.11 유동혁) -->
    <?//=$twitter_share?>
    <!-- 페이스북 쉐어 End (2016.02.11 유동혁) -->

    <!-- 리뉴얼 (2017.01.20 위민트) -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600" rel="stylesheet"><!-- 제이준 (2018-06-29) -->
    <link rel="stylesheet" href="/jayjun/web/static/css/jquery.bxslider.css">
	<link rel="stylesheet" href="/jayjun/web/static/css/common.min.css">
    <link rel="stylesheet" href="/jayjun/web/static/css/component.min.css">
    <link rel="stylesheet" href="/jayjun/web/static/css/content.min.css">
    <link rel="stylesheet" href="/jayjun/web/static/css/jquery.mCustomScrollbar-3.1.3.min.css">
    <link rel="stylesheet" href="/jayjun/web/static/css/nouislider.css">
    <link rel="stylesheet" href="/jayjun/web/static/css/temporary.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600" rel="stylesheet">

    <script src="/jayjun/web/static/js/jquery-1.12.0.min.js"></script>
    <script src="/jayjun/web/static/js/dev.min.js"></script>
    <script src="../static/js/ui_jayjun.js"></script>
    <script src="/jayjun/web/static/js/jquery.mCustomScrollbar.concat-3.1.3.min.js"></script>
    <script src="/jayjun/web/static/js/jquery.masonry.min.js"></script>
    <script src="/jayjun/web/static/js/placeholders.min.min.js"></script>
    <script src="/jayjun/web/static/js/jquery.bxslider.min.js"></script>
    <script src="/jayjun/web/static/js/nouislider.min.js"></script>
    <script src="/jayjun/web/static/js/wNumb.min.js"></script>
    <script src="/jayjun/web/static/js/buildV63.js"></script>
    <script src="/jayjun/web/static/js/jquery.easing.1.3.min.js"></script><!-- 제이준 (2018-06-29) -->
	<script src="/jayjun/web/static/js/slick.min.js"></script><!-- 제이준 (2018-07-17) -->

    <!-- jquery 연속방지 js추가 2016-09-25 -->
    <script src="../js/jquery.blockUI.min.js"></script>
    <script type="text/javascript" src="../static/js/dev.min.js?v=2"></script>
    <script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
    <script src="//connect.facebook.net/ko_KR/all.js"></script>
<script type="text/javascript">
        <!--
        //console.log( $.ajaxSetup() );
        //-->
    </script>

    <!--[if lt IE 9]>
    <script type="text/javascript" src="/jayjun/web/static/js/html5shiv.js"></script>
    <![endif]-->

    <!-- IE8 반응형 대응 플러그인 -->
    <script type="text/javascript" src="../static/js/respond.min.js"></script>

    <!-- 공통 스크립트, 다음 주소팝업, 분석스크립트 Start (2016.07.28 - 김재수) -->
    <script src="../lib/lib.js.php" type="text/javascript"></script>
    <?php if($_SERVER["REQUEST_URI"]!='/index.htm') { ?>
        <?php if( $_SERVER['HTTPS'] == 'on' ){ ?>
            <script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
        <?php }else{ ?>
            <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
        <?php } ?>
    <?php } ?>
    <?php include_once($Dir.LibDir."analyticstracking.php") ?>
    <!-- 공통 스크립트, 다음 주소팝업, 분석스크립트 End (2016.07.28 - 김재수) -->


    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '232176484215529');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=232176484215529&ev=PageView
	&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->

</head>
<body>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-99599198-1', 'auto');
    ga('send', 'pageview');

</script>
<!-- 구글 마케팅 공통  -->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 852381434;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/852381434/?guid=ON&amp;script=0"/>
    </div>
</noscript>

<a href="#contents" class="skip">Skip to Content</a>

<!-- wrap -->
<div id="wrap">
    <!-- header -->
    <header id="header" class="header">
        <div class="hdwrp">
            <h1 class="logo"><a href="/"><img src="/jayjun/web/static/img/common/h1_logo.png" alt="i KNOW iONE"></a></h1>
            <div class="gnbwrp">
                <ul class="gnb_menu">
                    <li>
                        <a href="<?=$Dir.FrontDir?>brand_main.php?bridx=303">BRAND</a>
                        <div class="subwrp">
                            <div class="inner">
                                <div class="sub_menu_wrp">
                                    <ul class="sub_menu">
                                        <li><a href="">Brand Story</a></li>
                                        <li><a href="">제품라인업</a></li>
                                        <li><a href="">브랜드소식</a></li>
                                        <li><a href="">매장정보</a></li>
                                    </ul>
                                    <div class="sub_banner"><a href=""><img src="/jayjun/web/static/img/test/@gnb_banner01.jpg" alt="banner"></a></div>
                                    <div class="sub_banner"><a href=""><img src="/jayjun/web/static/img/test/@gnb_banner02.jpg" alt="banner"></a></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="<?=$Dir.FrontDir?>productlist.php?code=001001000000">SHOPPING</a>
                        <div class="subwrp">
                            <div class="inner">
                                <div class="sub_menu_wrp">
                                    <ul class="sub_menu">
                                        <li><a href="">BASE</a></li>
                                        <li><a href="">CHEEK</a></li>
                                        <li><a href="">EYE</a></li>
                                        <li><a href="">LIP</a></li>
                                        <li><a href="">CARE</a></li>
                                        <li><a href="">TOOLS</a></li>
                                    </ul>
                                    <div class="sub_banner"><a href=""><img src="/jayjun/web/static/img/test/@gnb_banner01.jpg" alt="banner"></a></div>
                                    <div class="sub_banner"><a href=""><img src="/jayjun/web/static/img/test/@gnb_banner02.jpg" alt="banner"></a></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="">EVENT</a>
                        <div class="subwrp">
                            <div class="inner">
                                <div class="sub_menu_wrp">
                                    <ul class="sub_menu">
                                        <li><a href="<?=$Dir.FrontDir."promotion.php?ptype=event"?>">이벤트</a></li>
                                        <li><a href="<?=$Dir.FrontDir."promotion.php?ptype=special"?>">기획전</a></li>
                                    </ul>
                                    <div class="sub_banner"><a href=""><img src="/jayjun/web/static/img/test/@gnb_banner01.jpg" alt="banner"></a></div>
                                    <div class="sub_banner"><a href=""><img src="/jayjun/web/static/img/test/@gnb_banner02.jpg" alt="banner"></a></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="">REVIEW</a>
                        <div class="subwrp">
                            <div class="inner">
                                <div class="sub_menu_wrp">
                                    <ul class="sub_menu">
                                        <li><a href="">Photo Review</a></li>
                                        <li><a href="">Review</a></li>
                                        <li><a href="">MOVIE GUIDES</a></li>
                                    </ul>
                                    <div class="sub_banner"><a href=""><img src="/jayjun/web/static/img/test/@gnb_banner01.jpg" alt="banner"></a></div>
                                    <div class="sub_banner"><a href=""><img src="/jayjun/web/static/img/test/@gnb_banner02.jpg" alt="banner"></a></div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="util">
                <ul class="util_menu">
                    <?if(strlen($_ShopInfo->getMemid())==0){?>
                        <li><a href="<?=$Dir.FrontDir?>login.php?chUrl=<?=$_SERVER[REQUEST_URI]?>">로그인</a></li>
                        <li><a href="<?=$Dir.FrontDir?>member_certi.php">회원가입</a></li>
                    <?}else{?>
                        <li><a href="javascript:logout();">로그아웃</a></li>
                    <?}?>
                    <li><a href="<?=$Dir.FrontDir?>mypage.php">마이페이지</a></li>
                </ul>
                <div class="btn_sch"><a href="javascript:;" id="schLyr_open" title="검색"><i class="icon-sch">검색</i></a></div>
            </div>
        </div>
		<div class="schwrp">
			<button type="button" class="sch_cls" id="schLyr_cls"><span><i class="icon-layer-close">닫기</i></span></button>
			<div class="inner">
				<div class="none-result hide"> <!-- [D] 검색결과 없는 경우 .hide 삭제 -->
					<strong class="point-color">'코트'</strong>의 검색 결과 <strong class="point-color">총 0개</strong>입니다.
				</div>
				<fieldset>
					<legend>상품 검색</legend>
					<form name="formForSearch" action="../front/productsearch.php" method="get" onsubmit="proSearchChk();return false;">
						<div class="schbox">
							<input type="text" class="input_sch" name="search" placeholder="검색어를 입력해 주세요" title="검색어를 입력해 주세요">
							<button type="submit" class="btn_sch"><i class="icon-find">검색</i></button>
						</div>
					</form>
				</fieldset>
				<div class="sch_keyword" data-ui="TabMenu">
					<div class="tabs">
						<a data-content="menu" class="active" title="선택됨"><span class="">최근검색어</span></a>
						<a data-content="menu"><span class="">인기검색어</span></a>
					</div>
					<!-- 최근검색어 -->
					<div class="active" data-content="content">
						<div class="sch_recent">
							<ul class="list">
								<li><a href="">아이러브 립스틱</a><button type="button" class="btn_del" title="검색어 삭제">삭제</button></li>
								<li><a href="">I LOVE LIP</a><button type="button" class="btn_del" title="검색어 삭제">삭제</button></li>
								<li><a href="">BASE</a><button type="button" class="btn_del" title="검색어 삭제">삭제</button></li>
								<li><a href="">마스카라</a><button type="button" class="btn_del" title="검색어 삭제">삭제</button></li>
								<li><a href="">볼터치</a><button type="button" class="btn_del" title="검색어 삭제">삭제</button></li>
							</ul>
							<div class="del_all"><button type="button">검색어 전체 삭제</button></div>
						</div>

						<div class="none hide"><!-- [D] 결과 없을 시 .hide 클래스 삭제 -->
							<i class="icon-find-none">결과 없음</i>
							<p class="mt-20">최근 검색어가 없습니다.</p>
						</div>
					</div>
					<!-- 인기검색어 -->
					<div data-content="content">
						<div class="sch_popular">
							<ul class="list">
								<li><span class="num">1</span><a href="">아이러브 립스틱</a></li>
								<li><span class="num">2</span><a href="">I LOVE LIP</a></li>
								<li><span class="num">3</span><a href="">BASE</a></li>
								<li><span class="num">4</span><a href="">마스카라</a></li>
								<li><span class="num">5</span><a href="">볼터치</a></li>
							</ul>
							<ul class="list">
								<li><span class="num">6</span><a href="">아이러브 립스틱</a></li>
								<li><span class="num">7</span><a href="">I LOVE LIP</a></li>
								<li><span class="num">8</span><a href="">BASE</a></li>
								<li><span class="num">9</span><a href="">마스카라</a></li>
								<li><span class="num">10</span><a href="">볼터치</a></li>
							</ul>
						</div>
					</div>
				</div><!-- //.sch_keyword -->
			</div>
		</div>
    </header>
    <!-- //header -->

    <?php
    $urls = array('/','/index2.htm','/front/productlist.php2','/front/brand_main.php','/front/brand_detail.php','/front/outlet.php','/front/lookbook_list.php','/front/ecatalog_list.php','/front/brand_store.php',
        '/front/promotion_detail2.php','/front/promotion.php','/m/index.htm','/front/storeList.php','/front/instagramlist.php','/m/movie_list.php',
        '/m/productlist.php','/m/brand_main.php','/m/brand_detail.php','/m/ecatalog_list.php','/m/promotion.php','/m/lookbook_list.php','/m/promotion_detail2.php','/front/productdetail2.php','/m/productdetail2.php');
    if ($HTML_CACHE_EVENT!="OK" && in_array($TEMP_SCRIPTNM,$urls) && $_SERVER['REQUEST_METHOD']=="GET" ) {

        $cache_file_name2 = $_SERVER['DOCUMENT_ROOT'].'/'.DataDir.'cache/'.urlsafe_b64encode($_SERVER['REQUEST_URI']).'_.'.$b_idx;

        if($_SERVER["REQUEST_URI"]=='/index.htm') {
            $coos = array();
            foreach ($_COOKIE as $key=>$val) {
                if(strpos($key,'layerNotOpen')===0) {
                    $coos[] = substr($key,12);
                }
            }
            asort($coos);
            $cache_file_name2 .= '~'.implode('.',$coos);
        }
//$cache_file_name2 .= '@'.$_ShopInfo->getMemid();

        function html_cache2($buffer) {
            global $cache_file_name2,$HTML_ERROR_EVENT;
            if(strlen($buffer)>10000) {
                file_put_contents($cache_file_name2,$buffer.pack("L",strlen($buffer)+4));
            }
            return $buffer;
        }

        function html_cache_out2() {
            global $cache_file_name2;

            $buffer = file_get_contents($cache_file_name2);
            list(,$len) = unpack("L",substr($buffer,-4));
            if($len==strlen($buffer)) {
                echo(substr($buffer,0,-4)); exit;
            }
        }
        if(strpos($TEMP_SCRIPTNM,'productdetail.php')>0) $ctime = 60*10;
        else $ctime = 60*30;


        if (file_exists($cache_file_name2) && time()-filemtime($cache_file_name2)<$ctime) {
            html_cache_out2();
        } else {
            $HTML_CACHE_EVENT="OK";
            ob_start("html_cache2");
        }
    }
    ?>

    <!-- 통계용 카운트 -->
    <!-- ajax loading img -->
    <div class="dimm-loading" id="dimm-loading">
        <div id="loading"></div>
    </div>
    <!-- // ajax loading img-->
    <span class="hide"><?=$_data->countpath?></span>

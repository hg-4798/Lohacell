<!DOCTYPE html>
<html lang="ko">
<head>
	<title>SHINWONMALL</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<meta name="Generator" content="">
	<meta name="Author" content="">
	<meta name="Keywords" content="">
	<meta name="Description" content="">
	
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">
	<link rel="stylesheet" href="static/css/common.css">
	<link rel="stylesheet" href="static/css/component.css">
	<link rel="stylesheet" href="static/css/content.css">
    <link rel="stylesheet" href="static/css/nouislider.css">

	<script type="text/javascript" src="static/js/jquery-1.12.0.min.js"></script>
	<script type="text/javascript" src="static/js/jquery.bxslider.min.js"></script>
	<script type="text/javascript" src="static/js/Carousel.js"></script>
	<script type="text/javascript" src="static/js/nouislider.min.js"></script>
	<script type="text/javascript" src="static/js/wNumb.js"></script>
	<script type="text/javascript" src="static/js/masonry.pkgd.min.js"></script>
	<script type="text/javascript" src="static/js/ui.js"></script>
</head>

<body>
<a href="#content" class="skip">Skip to Content</a>

<!-- 헤더 -->
<header id="header">
	<h1><a href="#"><img src="static/img/common/logo.png" alt="SHINWONMALL.COM"></a></h1>
	<!-- LNB -->
	<div class="lnb-wrap">
		<button class="btn-lnb-open" type="button"><img src="static/img/btn/btn_lnb_open.png" alt="카테고리 메뉴 보기"></button>
		<div class="lnb-layer">
			<div class="lnb-layer-dim"></div>
			<div class="lnb-layer-inner">
				<div class="lnb-top">
					<a href="#" class="btn-lnb-home"><img src="static/img/btn/btn_lnb_home.png" alt="HOME"></a>
					<span class="btn-menu"><a href="#">OUTLET</a></span>
					<button type="button" class="btn-lnb-close"><img src="static/img/btn/btn_lnb_close.png" alt="카테고리 메뉴 숨기기"></button>
				</div><!-- //.lnb-top -->

				<div class="content">
					<div class="lnb-tab" data-ui="TabMenu">
						<div class="tab-menu clear">
							<a data-content="menu" class="w33-per active" title="선택됨">신상품</a>
							<a data-content="menu" class="w33-per">브랜드</a>
							<a data-content="menu" class="w33-per">아울렛</a>
						</div>

						<!-- 신상품 카테고리 -->
						<div class="tab-content active" data-content="content">
							<ul class="main_category">
								<li>
									<a href="javascript:;">여성</a>
									<ul class="sub_category">
										<li><a href="#">전체</a></li>
										<li><a href="#">탑</a></li>
										<li><a href="#">드레스</a></li>
										<li><a href="#">아우터</a></li>
										<li><a href="#">팬츠/스커트</a></li>
										<li><a href="#">패션잡화</a></li>
									</ul>
								</li><!-- //[D] 하위메뉴 있는 경우 -->
								<li>
									<a href="javascript:;">남성</a>
									<ul class="sub_category">
										<li><a href="#">전체</a></li>
										<li><a href="#">탑</a></li>
										<li><a href="#">드레스</a></li>
										<li><a href="#">아우터</a></li>
										<li><a href="#">팬츠/스커트</a></li>
										<li><a href="#">패션잡화</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">패션잡화</a>
									<ul class="sub_category">
										<li><a href="#">탑</a></li>
										<li><a href="#">드레스</a></li>
										<li><a href="#">아우터</a></li>
										<li><a href="#">팬츠/스커트</a></li>
										<li><a href="#">패션잡화</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">수트라운지</a>
								</li>
								<li>
									<a href="javascript:;">브랜드</a>
									<ul class="sub_category">
										<li><a href="#">BESTIBELLI</a></li>
										<li><a href="#">VIKI</a></li>
										<li><a href="#">SI</a></li>
										<li><a href="#">ISABEY</a></li>
										<li><a href="#">SIEG</a></li>
										<li><a href="#">SIEG FAHRENHEIT</a></li>
										<li><a href="#">VanHart di Albazar</a></li>
									</ul>
								</li>
								<!-- 컨텐츠 메뉴(신상품,브랜드,아울렛에서 고정 노출) -->
								<li>
									<a href="javascript:;">스타일</a>
									<ul class="sub_category">
										<li><a href="#">E-CATALOG</a></li>
										<li><a href="#">LOOKBOOK</a></li>
										<li><a href="#">MAGAZINE</a></li>
										<li><a href="#">INSTAGRAM</a></li>
										<li><a href="#">MOVIE</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">프로모션</a>
									<ul class="sub_category">
										<li><a href="#">출석체크</a></li>
										<li><a href="#">이벤트</a></li>
										<li><a href="#">기획전</a></li>
									</ul>
								</li>
								<li><a href="#">쇼윈도</a></li><!-- //[D] 하위메뉴 없는 경우 -->
								<li>
									<a href="javascript:;">고객센터</a>
									<ul class="sub_category">
										<li><a href="#">공지사항</a></li>
										<li><a href="#">FAQ</a></li>
										<li><a href="#">1:1문의</a></li>
										<li><a href="#">매장안내</a></li>
										<li><a href="#">입점문의</a></li>
										<li><a href="#">멤버쉽 안내</a></li>
										<li><a href="#">수선(A/S)안내</a></li>
									</ul>
								</li>
								<!-- //컨텐츠 메뉴 -->
							</ul>
						</div>
						<!-- //신상품 카테고리 -->

						<!-- 브랜드 카테고리 -->
						<div class="tab-content" data-content="content">
							<ul class="main_category">
								<li>
									<a href="javascript:;">BESTIBELLI</a>
									<ul class="sub_category">
										<li><a href="#">브랜드 소개</a></li>
										<li><a href="#">매장</a></li>
										<li><a href="#">룩북</a></li>
										<li><a href="#">E-CATALOG</a></li>
										<li><a href="#">SHOP</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">VIKI</a>
									<ul class="sub_category">
										<li><a href="#">브랜드 소개</a></li>
										<li><a href="#">매장</a></li>
										<li><a href="#">룩북</a></li>
										<li><a href="#">E-CATALOG</a></li>
										<li><a href="#">SHOP</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">SI</a>
									<ul class="sub_category">
										<li><a href="#">브랜드 소개</a></li>
										<li><a href="#">매장</a></li>
										<li><a href="#">룩북</a></li>
										<li><a href="#">E-CATALOG</a></li>
										<li><a href="#">SHOP</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">ISABEY</a>
									<ul class="sub_category">
										<li><a href="#">브랜드 소개</a></li>
										<li><a href="#">매장</a></li>
										<li><a href="#">룩북</a></li>
										<li><a href="#">E-CATALOG</a></li>
										<li><a href="#">SHOP</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">SIEG</a>
									<ul class="sub_category">
										<li><a href="#">브랜드 소개</a></li>
										<li><a href="#">매장</a></li>
										<li><a href="#">룩북</a></li>
										<li><a href="#">E-CATALOG</a></li>
										<li><a href="#">SHOP</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">SIEG FAHRENHEIT</a>
									<ul class="sub_category">
										<li><a href="#">브랜드 소개</a></li>
										<li><a href="#">매장</a></li>
										<li><a href="#">룩북</a></li>
										<li><a href="#">E-CATALOG</a></li>
										<li><a href="#">SHOP</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">VanHart di Albazar</a>
									<ul class="sub_category">
										<li><a href="#">브랜드 소개</a></li>
										<li><a href="#">매장</a></li>
										<li><a href="#">룩북</a></li>
										<li><a href="#">E-CATALOG</a></li>
										<li><a href="#">SHOP</a></li>
									</ul>
								</li>
								<!-- 컨텐츠 메뉴 -->
								<li>
									<a href="javascript:;">스타일</a>
									<ul class="sub_category">
										<li><a href="#">E-CATALOG</a></li>
										<li><a href="#">LOOKBOOK</a></li>
										<li><a href="#">MAGAZINE</a></li>
										<li><a href="#">INSTAGRAM</a></li>
										<li><a href="#">MOVIE</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">프로모션</a>
									<ul class="sub_category">
										<li><a href="#">출석체크</a></li>
										<li><a href="#">이벤트</a></li>
										<li><a href="#">기획전</a></li>
									</ul>
								</li>
								<li><a href="#">쇼윈도</a></li><!-- //[D] 하위메뉴 없는 경우 -->
								<li>
									<a href="javascript:;">고객센터</a>
									<ul class="sub_category">
										<li><a href="#">공지사항</a></li>
										<li><a href="#">FAQ</a></li>
										<li><a href="#">1:1문의</a></li>
										<li><a href="#">매장안내</a></li>
										<li><a href="#">입점문의</a></li>
										<li><a href="#">멤버쉽 안내</a></li>
										<li><a href="#">수선(A/S)안내</a></li>
									</ul>
								</li>
								<!-- //컨텐츠 메뉴 -->
							</ul>
						</div>
						<!-- //브랜드 카테고리 -->

						<!-- 아울렛 카테고리 -->
						<div class="tab-content" data-content="content">
							<ul class="main_category">
								<li>
									<a href="javascript:;">여성</a>
									<ul class="sub_category">
										<li><a href="#">전체</a></li>
										<li><a href="#">탑</a></li>
										<li><a href="#">드레스</a></li>
										<li><a href="#">아우터</a></li>
										<li><a href="#">팬츠/스커트</a></li>
										<li><a href="#">패션잡화</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">남성</a>
									<ul class="sub_category">
										<li><a href="#">전체</a></li>
										<li><a href="#">탑</a></li>
										<li><a href="#">드레스</a></li>
										<li><a href="#">아우터</a></li>
										<li><a href="#">팬츠/스커트</a></li>
										<li><a href="#">패션잡화</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">패션잡화</a>
									<ul class="sub_category">
										<li><a href="#">탑</a></li>
										<li><a href="#">드레스</a></li>
										<li><a href="#">아우터</a></li>
										<li><a href="#">팬츠/스커트</a></li>
										<li><a href="#">패션잡화</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">브랜드</a>
									<ul class="sub_category">
										<li><a href="#">BESTIBELLI</a></li>
										<li><a href="#">VIKI</a></li>
										<li><a href="#">SI</a></li>
										<li><a href="#">ISABEY</a></li>
										<li><a href="#">SIEG</a></li>
										<li><a href="#">SIEG FAHRENHEIT</a></li>
										<li><a href="#">VanHart di Albazar</a></li>
									</ul>
								</li>
								<!-- 컨텐츠 메뉴 -->
								<li>
									<a href="javascript:;">스타일</a>
									<ul class="sub_category">
										<li><a href="#">E-CATALOG</a></li>
										<li><a href="#">LOOKBOOK</a></li>
										<li><a href="#">MAGAZINE</a></li>
										<li><a href="#">INSTAGRAM</a></li>
										<li><a href="#">MOVIE</a></li>
									</ul>
								</li>
								<li>
									<a href="javascript:;">프로모션</a>
									<ul class="sub_category">
										<li><a href="#">출석체크</a></li>
										<li><a href="#">이벤트</a></li>
										<li><a href="#">기획전</a></li>
									</ul>
								</li>
								<li><a href="#">쇼윈도</a></li>
								<li>
									<a href="javascript:;">고객센터</a>
									<ul class="sub_category">
										<li><a href="#">공지사항</a></li>
										<li><a href="#">FAQ</a></li>
										<li><a href="#">1:1문의</a></li>
										<li><a href="#">매장안내</a></li>
										<li><a href="#">입점문의</a></li>
										<li><a href="#">멤버쉽 안내</a></li>
										<li><a href="#">수선(A/S)안내</a></li>
									</ul>
								</li>
								<!-- //컨텐츠 메뉴 -->
							</ul>
						</div>
						<!-- //아울렛 카테고리 -->
					</div>
				</div><!-- //.content -->
			</div>
		</div>
	</div>
	<!-- //LNB -->

	<a href="#" id="btn_search"><img src="static/img/btn/btn_search.png" alt="검색"></a>

	<div class="pop_search">
		<button type="button" class="close_search"><img src="static/img/btn/btn_layer_close.png" alt="닫기"></button>
		<div class="container">
			<div class="searchbox">
				<input type="text" value="겨울 신상품 세일">
				<button type="button"><img src="static/img/btn/btn_search.png" alt="검색"></button>
			</div>
			
			<div class="searchtab" data-ui="TabMenu">
				<div class="tab-menu clear">
					<a data-content="menu" class="active" title="선택됨">추천 검색어</a>
					<a data-content="menu">최근 검색어</a>
				</div>
				<div class="tab-content active" data-content="content">
					<ul class="search_word">
						<li><a href="#">VIKE</a></li>
						<li><a href="#">SI</a></li>
						<li><a href="#">겨울</a></li>
						<li><a href="#">남성복</a></li>
						<li><a href="#">가디건</a></li>
					</ul><!-- //검색어 있는 경우(10개까지 노출) -->
					<!-- <div class="search_word_none">추천 검색어가 없습니다.</div> --><!-- //검색어 없는 경우 -->
				</div>
				<div class="tab-content" data-content="content">
					<!-- <ul class="search_word">
						<li><a href="#">VIKE</a></li>
						<li><a href="#">SI</a></li>
						<li><a href="#">겨울</a></li>
						<li><a href="#">남성복</a></li>
						<li><a href="#">가디건</a></li>
					</ul> --><!-- //검색어 있는 경우(10개까지 노출) -->
					<div class="search_word_none">최근 검색어가 없습니다.</div><!-- //검색어 없는 경우 -->
				</div>
			</div><!-- //.searchtab -->

			<!-- 검색결과가 없는 경우 -->
			<!-- <div class="search_result_none"><strong class="point-color">‘코트’</strong> 의 검색 결과 <strong class="point-color">총 0개</strong>입니다.</div>

			<div class="search_notice">
				<ul>
					<li>- 단어의 철자 및 띄어쓰기를 확인해주세요.</li>
					<li>- 검색어가 올바른지 다시 한번 확인해주세요.</li>
					<li>- 특수문자를 제외하고 검색해주세요.</li>
				</ul>
			</div> -->
			<!-- //검색결과가 없는 경우 -->
		</div>
	</div><!-- //.pop_search -->

	<div class="rnb-wrap">
		<button class="btn-rnb-open" type="button"><img src="static/img/btn/btn_rnb_open.png" alt="마이페이지 메뉴 보기"></button>
		<div class="rnb-layer">
			<div class="rnb-layer-dim"></div>
			<div class="rnb-layer-inner">
				<div class="lnb-top">
					<!-- 로그인 전 -->
					<a href="#" class="btn-lnb-login">
						<img src="static/img/btn/btn_lnb_login.png" alt="login">
						<span>로그인</span>
					</a>
					<!-- //로그인 전 -->

					<!-- 로그인 후 -->
					<!-- <a href="#" class="btn-lnb-login">
						<img src="static/img/btn/btn_lnb_logout.png" alt="logout">
						<span>로그아웃</span>
					</a> -->
					<!-- //로그인 후 -->

					<button type="button" class="btn-lnb-close"><img src="static/img/btn/btn_lnb_close.png" alt="마이페이지 메뉴 숨기기"></button>
				</div><!-- //.lnb-top -->

				<div class="content">
					<!-- 로그인 전 -->
					<!-- <div class="benefit_info">
						<p class="info_none">회원님께 특별한 혜택을 드립니다.</p>
					</div> -->
					<!-- //로그인 전 -->

					<!-- 로그인 후 -->
					<div class="benefit_info">
						<p class="msg"><strong>권영은</strong>님은 <strong>WELCOME</strong>등급입니다.</p>
						<p class="point point-color">통합: 100,000P <span class="bar">|</span> E: 100,000P</p>
					</div>
					<!-- //로그인 후 -->

					<div class="shortcut">
						<ul class="clear">
							<li>
								<a href="#">
									<span class="icon"><img src="static/img/icon/icon_like.png" alt="좋아요"></span>
									<span class="txt">좋아요</span>
								</a>
							</li>
							<li>
								<a href="#">
									<span class="icon_cart">5</span>
									<span class="txt">장바구니</span>
								</a>
							</li>
						</ul>
					</div><!-- //.shortcut -->

					<div class="mycategory">
						<h2>쇼핑내역</h2>
						<ul>
							<li><a href="#">주문/배송조회</a></li>
							<li><a href="#">취소/교환/반품 신청</a></li>
							<li><a href="#">취소/교환/반품 현황</a></li>
						</ul>
						<h2>혜택정보</h2>
						<ul>
							<li><a href="#">회원등급 및 혜택</a></li>
							<li><a href="#">포인트</a></li>
							<li><a href="#">쿠폰</a></li>
						</ul>
						<h2>활동정보</h2>
						<ul>
							<li><a href="#">이벤트 참여현황</a></li>
							<li><a href="#">상품리뷰</a></li>
							<li><a href="#">상품문의</a></li>
							<li><a href="#">1:1문의</a></li>
						</ul>
						<h2>회원정보</h2>
						<ul>
							<li><a href="#">회원정보 수정</a></li>
							<li><a href="#">배송지 관리</a></li>
							<li><a href="#">환불계좌 관리</a></li>
							<li><a href="#">회원탈퇴</a></li>
						</ul>
					</div><!-- //.mycategory -->

				</div>
			</div>
		</div>
	</div><!-- //.rnb-wrap -->

</header>
<!-- // 헤더 -->

<div id="page">
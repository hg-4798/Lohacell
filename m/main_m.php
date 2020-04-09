<!doctype html>
<html lang="ko">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
	<meta name="format-detection" content="telephone=no, address=no, email=no">
    <meta name="Keywords" content="">
    <meta name="Description" content="">

    <title>제이준코스메틱 m</title>

	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600" rel="stylesheet"><!-- 제이준 (2018-06-29) -->
	<link rel="stylesheet" href="/jayjun/m/static/css/common.css">
	<link rel="stylesheet" href="/jayjun/m/static/css/component.css">
	<link rel="stylesheet" href="/jayjun/m/static/css/content.css">
    <link rel="stylesheet" href="/jayjun/m/static/css/nouislider.css">
    <link rel="stylesheet" href="/jayjun/m/static/css/temporary.css">

	<script src="/jayjun/m/static/js/jquery-1.12.0.min.js"></script>
	<script src="/jayjun/m/static/js/jquery.bxslider.min.js"></script>
	<script src="/jayjun/m/static/js/Carousel.min.js"></script>
	<script src="/jayjun/m/static/js/nouislider.min.js"></script>
	<script src="/jayjun/m/static/js/wNumb.min.js"></script>
	<script src="/jayjun/m/static/js/masonry.pkgd.min.js"></script>
	<script src="./static/js/ui_jayjun.min.js?v=13"></script>
	<script src="/jayjun/m/static/js/buildV63.js"></script>
	<script src="/jayjun/web/static/js/slick.min.js"></script><!-- 제이준 (2018-09-03) -->
	<script src="/jayjun/m/static/js/jquery.easing.1.3.min.js"></script><!-- 제이준 (2018-07-16) -->

    <link rel='shortcut icon' href="./static/img/common/favicon.ico" type="image/x-ico" >

    <script src="../lib/lib.js.php"></script>
	<script src="./static/js/dev.js?v=11"></script>
	<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
</head>
<body>
<a href="#content" onclick="focus_anchor($(this).attr('href'));return false;" class="skip">Skip to Content</a>

<!-- Header -->
<header id="header">
	<div class="header">
		<!-- Top banner -->
		<div id="pop_close_off">
			<div class="topbanner">
				<a href="">
					<span class="logo_sm" style="padding:0 2px;line-height:36px;background:#fff"><img src="/jayjun/m/static/img/common/h1_logo.png" alt="i KNOW iONE"></span>
					<span class="ment">APP으로 포인트 받고 쇼핑하기</span>
				</a>
				<button type="button" class="btn_close" onclick=""><img src="/jayjun/m/static/img/btn/btn_layer_close.png" alt="닫기"></button>
			</div>
		</div>
		<!-- //Top banner -->
		<div class="hdwrp">
			<div class="logo"><a href=""><img src="/jayjun/m/static/img/common/h1_logo.png" alt="i KNOW iONE"></a></div>
			<div class="hdl"><button type="button" class="btn_lnb_open"><i class="icon-lnb">메뉴</i></button></div>
			<div class="hdr"><button type="button" class="btn_sch_open"><i class="icon-sch">검색</i></button></div>
		</div>
	</div>
	<!-- LNB -->
	<nav id="lnb_wrp">
		<div class="lnb_dim"></div>
		<div class="lnb_container">
			<div class="lnb">
				<div class="quick_area">
					<ul class="quick_menu clear">
						<li><a href=""><i class="icon-lnb-login"></i><p class="tit">로그인</p></a></li>
						<!-- <li><a href=""><i class="icon-lnb-logout"></i><p class="tit">로그아웃</p></a></li> -->
						<li><a href=""><i class="icon-lnb-mypage"></i><p class="tit">마이페이지</p></a></li>
						<li><a href=""><i class="icon-lnb-order"></i><p class="tit">주문배송조회</p></a></li>
					</ul>
				</div>
				<div class="gnb_area">
					<ul class="gnb">
						<li class="has_sub">
							<a href="javascript:;" class="gnbmenu">BRAND</a>
							<ul class="sub">
								<li><a href="">Brand Story</a></li>
								<li><a href="">매장정보</a></li>
							</ul>
						</li>
						<li class="has_sub">
							<a href="javascript:;" class="gnbmenu">SHOPPING</a>
							<ul class="sub">
								<li><a href="">BASE Make up</a></li>
								<li><a href="">Cheek &amp; High lighter</a></li>
								<li><a href="">EYE</a></li>
								<li><a href="">LIP</a></li>
								<li><a href="">CARE Make up</a></li>
								<li><a href="">TOOLS</a></li>
							</ul>  
						</li>
						<li><a href="" class="gnbmenu">EVENT</a></li>
						<li class="has_sub">
							<a href="javascript:;" class="gnbmenu">CONTENTS</a>
							<ul class="sub">
								<li><a href="">PHOTO REVIEW</a></li>
								<li><a href="">REVIEW</a></li>
							</ul>
						</li>
						<li><a href="" class="gnbmenu">고객센터</a></li>
					</ul>
				</div>
				<div class="sns_link">
					<a href="javascript:;" target="_blank"><i class="icon-instagram-dark">instagram</i></a>
					<a href="javascript:;" target="_blank"><i class="icon-youtube-dark">youtube</i></a>
					<a href="javascript:;" target="_blank"><i class="icon-facebook-dark">facebook</i></a>
				</div>
			</div>
			<a href="javascript:;" class="btn_lnb_cls">닫기</a>
		</div>
	</nav>
	<!-- //LNB -->
	<!-- Search -->
	<div class="schwrp">
		<h2 class="title">검색</h2>
		<button type="button" class="sch_cls" id="schLyr_cls"><span><i class="icon-lyr-cls">닫기</i></span></button>
		<div class="inner">
			<fieldset>
				<legend>상품 검색</legend>
				<form name="formForSearch" action="../m/productsearch.php" method="get" onsubmit="proSearchChk();return false;">
					<div class="schbox">
						<input type="text" class="input_sch" name="search" placeholder="검색어를 입력해 주세요" title="검색어를 입력해 주세요">
						<button type="button" class="btn_clear_sch"><i class="icon-clear-sch">삭제</i></button><!-- [D] 검색어 입력시 노출되고, 클릭하면 검색어 인풋 리셋 -->
						<button type="submit" class="btn_sch"><i class="icon-sch">검색</i></button>
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

					<div class="search_word_none hide">최근 검색어가 없습니다.</div><!-- [D] 결과 없을 시 .hide 클래스 삭제 -->
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
							<li><span class="num">6</span><a href="">아이러브 립스틱</a></li>
							<li><span class="num">7</span><a href="">I LOVE LIP</a></li>
							<li><span class="num">8</span><a href="">BASE</a></li>
							<li><span class="num">9</span><a href="">마스카라</a></li>
							<li><span class="num">10</span><a href="">볼터치</a></li>
						</ul>
					</div>
				</div>
			</div><!-- //.sch_keyword -->

			<div class="search_result_none hide"><strong class="point-color">‘코트’</strong> 의 검색 결과 <strong class="point-color">총 0개</strong>입니다.</div><!-- [D] 검색결과 없는 경우 .hide 삭제 -->
		</div>
	</div>
	<!-- //Search -->
</header>
<!-- //Header -->

<!-- Page -->
<div id="page">
	<!-- Content -->
	<main id="content" class="main_wrp">
		<!-- mainVisual -->
		<div class="visual with-pager">
			<ul class="slider">
				<li>
					<div class="item">
						<div class="big_img_wrp"><div class="img"><img src="/jayjun/m/static/img/test/@visual_big02.jpg" alt="visual image"></div></div>
						<div class="sm_img_wrp"><div class="img"><img src="/jayjun/m/static/img/test/@visual_sm02.jpg" alt="visual image"></div></div>
						<div class="txt bg_red">
							<p class="pre_tit">LIP LINE</p>
							<h3>I LOVE<br>LIP<br>PLUMPING PRIMER</h3>
						</div>
					</div>
				</li>
				<li>
					<div class="item">
						<div class="big_img_wrp"><div class="img"><img src="/jayjun/m/static/img/test/@visual_big01.jpg" alt="visual image"></div></div>
						<div class="sm_img_wrp"><div class="img"><img src="/jayjun/m/static/img/test/@visual_sm01.jpg" alt="visual image"></div></div>
						<div class="txt bg_gold">
							<p class="pre_tit">CHEEK LINE</p>
							<h3>I TOUCH<br>CHEEK<br>BLOSSOM</h3>
						</div>
					</div>
				</li>
				<li>
					<div class="item">
						<div class="big_img_wrp"><div class="img"><img src="/jayjun/m/static/img/test/@visual_big02.jpg" alt="visual image"></div></div>
						<div class="sm_img_wrp"><div class="img"><img src="/jayjun/m/static/img/test/@visual_sm02.jpg" alt="visual image"></div></div>
						<div class="txt bg_red">
							<p class="pre_tit">LIP LINE</p>
							<h3>I LOVE<br>LIP<br>PLUMPING PRIMER</h3>
						</div>
					</div>
				</li>
				<li>
					<div class="item">
						<div class="big_img_wrp"><div class="img"><img src="/jayjun/m/static/img/test/@visual_big01.jpg" alt="visual image"></div></div>
						<div class="sm_img_wrp"><div class="img"><img src="/jayjun/m/static/img/test/@visual_sm01.jpg" alt="visual image"></div></div>
						<div class="txt bg_gold">
							<p class="pre_tit">CHEEK LINE</p>
							<h3>I TOUCH<br>CHEEK<br>BLOSSOM</h3>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<!-- //mainVisual -->

		<!-- bestItem -->
		<div class="best_item">
			<h2 class="main_tit">BEST ITEM</h2>
			<div class="list">
				<div class="slider with-arrow">
					<div>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_380_02.jpg" alt="상품이미지"></div>
								<figcaption>
									<p class="goods-nm">I TOUCH CHEEK BLOSSOM 1</p>
									<p class="opt">10ml</p>
									<p class="price">15,000<span>원</span></p>
								</figcaption>
							</figure>
						</a>
					</div>
					<div>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_380_02.jpg" alt="상품이미지"></div>
								<figcaption>
									<p class="goods-nm">I TOUCH CHEEK BLOSSOM 2</p>
									<p class="opt">10ml</p>
									<p class="price">15,000<span>원</span></p>
								</figcaption>
							</figure>
						</a>
					</div>
					<div>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_380_02.jpg" alt="상품이미지"></div>
								<figcaption>
									<p class="goods-nm">I TOUCH CHEEK BLOSSOM 3</p>
									<p class="opt">10ml</p>
									<p class="price">15,000<span>원</span></p>
								</figcaption>
							</figure>
						</a>
					</div>
				</div>
				<div class="count"></div>
			</div>
		</div>
		<!-- //bestItem -->

		<!-- newItem -->
		<div class="new_item" data-ui="newTabMenu">
			<div class="tabs-type-btn">
				<button data-content="newMenu" class="active">#I NEED</button>
				<button data-content="newMenu">#I LOVE</button>
				<button data-content="newMenu">#I TOUCH</button>
				<button data-content="newMenu">#I LIKE</button>
				<button data-content="newMenu">#I WISH</button>
			</div>
			<!-- #I NEED -->
			<div data-content="newContent" class="active">
				<div class="slider">
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I NEED<br>CUSHION<br>CHEEK 1</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@goods_500_01.jpg')"></div>
						</div>
					</div>
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I NEED<br>CUSHION<br>CHEEK 2</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@visual_sm01.jpg')"></div>
						</div>
					</div>
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I NEED<br>CUSHION<br>CHEEK 3</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@visual_sm02.jpg')"></div>
						</div> 
					</div>
				</div>
				<div class="counter"></div>
			</div>
			<!-- #I LOVE -->
			<div data-content="newContent">
				<div class="slider">
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I LOVE<br>CUSHION<br>CHEEK 1</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@goods_500_01.jpg')"></div>
						</div>
					</div>
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I LOVE<br>CUSHION<br>CHEEK 2</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@visual_sm01.jpg')"></div>
						</div>
					</div>
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I LOVE<br>CUSHION<br>CHEEK 3</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@visual_sm02.jpg')"></div>
						</div> 
					</div>
				</div>
				<div class="counter"></div>
			</div>
			<!-- #I TOUCH -->
			<div data-content="newContent">
				<div class="slider">
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I TOUCH<br>CUSHION<br>CHEEK 1</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@goods_500_01.jpg')"></div>
						</div>
					</div>
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I TOUCH<br>CUSHION<br>CHEEK 2</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@visual_sm01.jpg')"></div>
						</div>
					</div>
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I TOUCH<br>CUSHION<br>CHEEK 3</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@visual_sm02.jpg')"></div>
						</div> 
					</div>
				</div>
				<div class="counter"></div>
			</div>
			<!-- #I LIKE -->
			<div data-content="newContent">
				<div class="slider">
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I LIKE<br>CUSHION<br>CHEEK 1</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@goods_500_01.jpg')"></div>
						</div>
					</div>
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I LIKE<br>CUSHION<br>CHEEK 2</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@visual_sm01.jpg')"></div>
						</div>
					</div>
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I LIKE<br>CUSHION<br>CHEEK 3</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@visual_sm02.jpg')"></div>
						</div> 
					</div>
				</div>
				<div class="counter"></div>
			</div>
			<!-- #I WISH -->
			<div data-content="newContent">
				<div class="slider">
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I WISH<br>CUSHION<br>CHEEK 1</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@goods_500_01.jpg')"></div>
						</div>
					</div>
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I WISH<br>CUSHION<br>CHEEK 2</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@visual_sm01.jpg')"></div>
						</div>
					</div>
					<div class="slick-slide">
						<div class="item">
							<div class="circle-txt">
								<div class="inner">
									<p class="cate">CHEEK LINE</p>
									<p class="name">I WISH<br>CUSHION<br>CHEEK 3</p>
									<a href="" class="btn-line">구매하기</a>
								</div>
							</div>
							<div class="circle-img" style="background-image:url('/jayjun/m/static/img/test/@visual_sm02.jpg')"></div>
						</div> 
					</div>
				</div>
				<div class="counter"></div>
			</div>
		</div>
		<!-- //newItem -->

		<!-- promotion -->
		<div class="promotion">
			<div class="pr_list">
				<div class="post">
					<a href="">
						<div class="img_wrp">
							<div class="img" style="background-image:url('/jayjun/web/static/img/test/@promotion_list01.jpg')"></div>
						</div>
						<div class="txt">
							<p class="subject">SPRING MAKEUP</p>
							<p class="exp">i KNOW i ONE에서 제안하는<br>2018 봄 메이크업!</p>
							<span class="link">VIEW</span>
						</div>
					</a>
				</div>
				<div class="post">
					<a href="">
						<div class="img_wrp">
							<div class="img" style="background-image:url('/jayjun/web/static/img/test/@promotion_list03.jpg')"></div>
						</div>
						<div class="txt">
							<p class="subject">다양한 혜택의 멤버쉽 안내</p>
							<p class="exp">i KNOW i ONE에서 고객 여러분께<br>감사의 마음을 전하는 멤버쉽 혜택입니다.</p>
							<span class="link">VIEW</span>
						</div>
					</a>
				</div>
				<div class="post">
					<a href="">
						<div class="img_wrp">
							<div class="img" style="background-image:url('/jayjun/web/static/img/test/@promotion_list02.jpg')"></div>
						</div>
						<div class="txt">
							<p class="subject">매일 30명씩 체험할 기회를 드립니다</p>
							<p class="exp">매일 선착순 30명씩 체험신청을 하신 분께 집앞까지 배달해 드립니다.</p>
							<span class="link">VIEW</span>
						</div>
					</a>
				</div>
			</div>
		</div>
		<!-- //promotion -->

		<!-- movie -->
		<div class="main_movie">
			<h2 class="main_tit">I LOVE LIP STICK</h2>
			<p class="exp">부드러운 질감과 한번의 터치로 완성되는<br> 칼라풀하고 사랑스런 입술!</p>
			<div class="movie_wrp">
				<div class="thumb"><a href="javascript:;"><img src="/jayjun/m/static/img/test/@main_movie01.jpg" alt="thumbnail"></a></div>
				<div class="movie">
					<iframe src="https://player.vimeo.com/video/223047819?title=0&amp;byline=0&amp;portrait=0" id="ytVids" title="video" width="680" height="382" allow="autoplay; encrypted-media" allowfullscreen=""></iframe>
				</div>
			</div>
		</div>
		<!-- //movie -->

		<!-- bestReview -->
		<div class="best_review bg-change" data-background="#f4e4bc">
			<h2 class="main_tit">BEST REVIEW</h2>
			<ul class="best_review_list">
				<li>
					<a href="">
						<figure class="post">
							<div class="img" style="background-image:url('/jayjun/web/static/img/test/@best_review01.jpg')"></div>
							<figcaption>
								<p class="subject">I TOUCH CHEEK BLOSSOM</p>
								<p class="text">힘없이 쳐지는 속눈썹에 힘과 탄력을 부여한다고 했는데, 써본지 1주일 정도 밖에 지나지 않았는데.</p>
								<p class="writer">iseeyou***</p>
							</figcaption>
						</figure>
					</a>
				</li>
				<li>
					<a href="">
						<figure class="post">
							<div class="img" style="background-image:url('/jayjun/web/static/img/test/@best_review02.jpg')"></div>
							<figcaption>
								<p class="subject">I LOVE LIP STICK</p>
								<p class="text">솔직히 별로 기대안했는데 진짜 생각 이상입니다. 발색좋고 촉촉하니 정말 좋아요.</p>
								<p class="writer">friend***</p>
							</figcaption>
						</figure>
					</a>
				</li>
				<li>
					<a href="">
						<figure class="post">
							<div class="img" style="background-image:url('/jayjun/web/static/img/test/@best_review03.jpg')"></div>
							<figcaption>
								<p class="subject">I NEED U CUSHION SPF50+</p>
								<p class="text">블러셔 사용하고 싶은데 능숙하지 못해서 진짜 은은하게 한듯안한듯한 자연스러운 컬러가 너무 맘에... </p>
								<p class="writer">bluemoon***</p>
							</figcaption>
						</figure>
					</a>
				</li>
				<li>
					<a href="">
						<figure class="post">
							<div class="img" style="background-image:url('/jayjun/web/static/img/test/@best_review04.jpg')"></div>
							<figcaption>
								<p class="subject">I NEED GLOW SHIMMER BASE</p>
								<p class="text">클렌징밤 사러 갔다가 테스트만 해보고 결국 이녀석을 구매했는데 완전 인생템 될꺼 같아요.</p>
								<p class="writer">iknowlif***</p>
							</figcaption>
						</figure>
					</a>
				</li>
			</ul>
		</div>
		<!-- //bestReview -->

		<!-- instagram -->
		<div class="main_insta">
			<h2 class="main_tit">#INSTAGRAM</h2>
			<div class="main_insta_wrp">
				<ul class="clear">
					<li><a href="" target="_blank"><div class="img" style="background-image:url('/jayjun/web/static/img/test/@main_insta01.jpg')"></div></a></li>
					<li><a href="" target="_blank"><div class="img" style="background-image:url('/jayjun/web/static/img/test/@main_insta02.jpg')"></div></a></li>
					<li><a href="" target="_blank"><div class="img" style="background-image:url('/jayjun/web/static/img/test/@main_insta03.jpg')"></div></a></li>
					<li><a href="" target="_blank"><div class="img" style="background-image:url('/jayjun/web/static/img/test/@main_insta05.jpg')"></div></a></li>
					<li><a href="" target="_blank"><div class="img" style="background-image:url('/jayjun/web/static/img/test/@main_insta06.jpg')"></div></a></li>
					<li><a href="" target="_blank"><div class="img" style="background-image:url('/jayjun/web/static/img/test/@main_insta04.jpg')"></div></a></li>
				</ul>
				<p class="ment">Snap it! Tag it ! Share it ! <strong># iknowione</strong></p>
			</div>
		</div>
		<!-- //instagram -->
	</main>
	<!-- //Content -->

	<!-- Quick -->
	<div class="quick_btn_wrp">
		<div class="btn_cart"><a href="" title="장바구니"><span class="count">5</span></a></div>
		<div class="btn_top"><a href="javascript:;"><span class="txt">TOP</span></a></div>
	</div>
	<!-- //Quick -->

	<!-- Footer -->
	<footer id="footer">
		<div class="ft_notice">
			<div class="notice_summary">
				<h2 class="tit">공지</h2>
				<p class="subject"><a href="">5월 5일 I KNOW I ONE 공식몰 Grand OPEN!</a></p>
				<a href="" class="more">MORE</a>
			</div>
			<div class="notice_summary">
				<h2 class="tit">가이드</h2>
				<p class="subject"><a href="">소중한 내 피부, 정품인지 꼭 확인하세요!</a></p>
				<a href="" class="more">MORE</a>
			</div>
		</div>
		<div class="ft_wrp">
			<div class="ft_menu">
				<ul>
					<li><a href="../m/etc_agreement.php">이용약관</a></li>
					<li><a href="../m/etc_privacy.php">개인정보취급방침</a></li>
					<li><a href="../m/customer_notice.php">고객센터</a></li>
					<li><a href="">PC버전</a></li>
				</ul>
			</div>
			<div class="sns_link">
				<a href="javascript:;" target="_blank"><i class="icon-instagram-light">instagram</i></a>
				<a href="javascript:;" target="_blank"><i class="icon-youtube-light">youtube</i></a>
				<a href="javascript:;" target="_blank"><i class="icon-facebook-light">facebook</i></a>
			</div>
			<div class="company_info">
				<p><span>서울특별시 강남구 논현로 526</span><span>고객(제품)상담 : 080-881-2001</span></p>
				<p><span>기업(IR)상담 : 02-2193-9513</span><span>팩스 : 02-2193-9595</span></p>
				<p><span>법인명 : 제이준코스메틱(주)</span><span>대표자 : 이진형, 판나</span></p>
				<p><span>사업자 등록번호 : [222-85-12156]</span></p>
				<p><span>통신판매업 신고 : 2016-서울강남-00111</span></p>
				<p><span>개인정보관리책임자 : 최시원</span></p>
			</div>
			<p class="copyright">Copyright(c) JAYJUNCOSMETIC. all right reserved.</p>
			<ul class="ft_etc">
				<li class="family_site">
					<select title="FAMILY SITE 바로가기">
						<option value="">FAMILY SITE</option>
						<option value="">FAMILY SITE 1</option>
						<option value="">FAMILY SITE 2</option>
					</select>
				</li>
				<li class="btn_link"><a href="" target="_blank">에스크로 서비스 가입 확인</a></li>
			</ul>
		</div>
	</footer>
	<!-- //Footer -->
</div>
<!-- //Page -->

<script>
$(document).ready(function(){
	// visual slider
	var mainSlider = $('.visual .slider').bxSlider({
		mode: 'fade',
		controls: false,
		auto: true,
		speed: 0,
		pause: 4000,
		preloadImages: 'all',
		preventDefaultSwipeX: false,
		onSliderLoad: function(currentIndex){
			$('.visual .slider').children().eq(currentIndex).addClass('active-slide');
		},
		onSlideBefore: function($slideElement,newIndex){
			$('.visual .slider').children().removeClass('active-slide next-slide');
			$('.visual .slider').children().eq(newIndex).addClass('next-slide');
			$slideElement.addClass('active-slide');
		},
		onSlideAfter: function($slideElement,newIndex){
			$('.visual .slider').children().removeClass('active-slide next-slide');
			$('.visual .slider').children().eq(newIndex).addClass('next-slide');
			$slideElement.addClass('active-slide');
			visualSlide();
			mainSlider.stopAuto();
			mainSlider.startAuto();
		}
	});
	function visualSlide(){
		$('.visual .active-slide').each(function(){
			var bigImg = $(this).find('.big_img_wrp'),
				smImg = $(this).find('.sm_img_wrp'),
				txt = $(this).find('.txt');
			bigImg.css('opacity','0');
			smImg.css({'right':'-5%','opacity':'0'});
			txt.css({'bottom':'-50px','opacity':'0'});
			bigImg.stop().animate({'opacity':'1'},400,'easeInCirc', function(){
				smImg.stop().animate({'right':'3.33%','opacity':'1'},500,'easeOutCirc');
				txt.delay(100).animate({'bottom':'2px','opacity':'1'},800,'easeOutQuad');
			});
		});
	}
	/*$('.visual .slider').on('touchmove', function(e) {
		var xStart, yStart = 0;
		xStart = e.touches[0].screenX;
		yStart = e.touches[0].screenY;
		var xMovement = Math.abs(e.touches[0].screenX - xStart);
		var yMovement = Math.abs(e.touches[0].screenY - yStart);
		if ((yMovement * 3) > xMovement) {
			e.preventDefault();
		}
	});*/
	
	// bestItem slider
	$('.best_item').each(function(){
		var ui = $(this).find('.slider');
		var status = $(this).find('.count');

		ui.on('init reInit afterChange', function(event, slick, currentSlide, nextSlide){
			var i = (currentSlide ? currentSlide : 0) + 1;
			status.text(i + '/' + slick.slideCount);
		});

		ui.slick({
			centerMode: true,
			centerPadding: '17.91%',
			slidesToShow: 1
		});
	});

	// newItem slider
	$('.new_item').each(function(){
		var config = {
			fade: true,
			autoplay: true,
			autoplaySpeed: 3000,
			speed: 1000,
			zIndex: 50,
			arrows: false
		};

		$('.new_item .slider').on('init reInit afterChange', function(event, slick, currentSlide, nextSlide){
			var i = (currentSlide ? currentSlide : 0) + 1;
			$(this).next('.counter').text(i + '/' + slick.slideCount);
		});

		$('.new_item .slider').slick(config);

		var newSlider = new Array();
		$('.new_item .slider').each(function(i, slider){
			newSlider[i] = $(slider).slick('refresh');
		});

		var ui = $(this);
		var menu = ui.find('[data-content="newMenu"]');
		var content = ui.find('[data-content="newContent"]');

		menu.click(function(){
			var index = menu.index(this);
			menu.removeClass('active').removeAttr('title').eq(index).addClass('active').attr('title','선택됨');
			content.removeClass('active').eq(index).addClass('active');

			$.each(newSlider, function(i, slider) { 
				slider.slick('unslick');
				slider.slick(config);
			});
		});
	});

	// movie > youtube play on click
	$('.main_movie .movie_wrp').each(function(){
		var ui = $(this);
		var thumb = $(this).find('.thumb a');
		var ytSrc = $('#ytVids').attr('src');

		thumb.click(function(){
			$('#ytVids').attr('src', ytSrc+'&autoplay=1');
			ui.addClass('play');
		});
	});
	
	// promotion slider
	$('.main_wrp .promotion .pr_list').slick({
		centerMode: true,
		centerPadding: '12.5%',
		slidesToShow: 1,
		arrows: false
	});

	// bestReview > change bgcolor on scroll
	$(window).scroll(function(){
		bgChange();
	});
	function bgChange(){
		var scTop = $(window).scrollTop(),
			bgChange = $('.bg-change'),
			bgColor = bgChange.attr('data-background'),
			currentItemTop = bgChange.offset().top,
			itemH = bgChange.outerHeight();
		$('body').css('height','auto');

		if( scTop > currentItemTop - 400 && scTop < currentItemTop + itemH - 300 ){
			$('body').css('background-color', bgColor);
			$('.main_wrp .best_review').addClass('on');
		}else{
			$('body').css('background-color','#fff');
			$('.main_wrp .best_review').removeClass('on');
		}
	}
});
</script>

</body>
</html>
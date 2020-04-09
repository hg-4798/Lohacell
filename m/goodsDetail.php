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
	<script src="/jayjun/m/static/js/slick.min.js"></script><!-- 제이준 (2018-07-16) -->
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
	<main id="content">
		<div class="goods_info_area">
			<div class="thumb_box">
				<div class="big_thumb">
					<ul class="slider">
						<li><img src="/jayjun/web/static/img/test/@goods_760_01.jpg" alt="상품 대표 썸네일"></li>
						<li><img src="/jayjun/m/static/img/test/@goods_110_01.jpg" alt="상품 대표 썸네일"></li>
						<li><img src="/jayjun/m/static/img/test/@goods_110_02.jpg" alt="상품 대표 썸네일"></li>
						<li><img src="/jayjun/m/static/img/test/@goods_110_03.jpg" alt="상품 대표 썸네일"></li>
						<li><img src="/jayjun/web/static/img/test/@goods_760_01.jpg" alt="상품 대표 썸네일"></li>
						<li><img src="/jayjun/m/static/img/test/@goods_110_01.jpg" alt="상품 대표 썸네일"></li>
					</ul>
				</div>
				<div class="sm_thumb">
					<ul class="slider">
						<li><a data-slide-index="0"><span class="img"><img src="/jayjun/web/static/img/test/@goods_760_01.jpg" alt="작은 썸네일"></span></a></li>
						<li><a data-slide-index="1"><span class="img"><img src="/jayjun/m/static/img/test/@goods_110_01.jpg" alt="작은 썸네일"></span></a></li>
						<li><a data-slide-index="2"><span class="img"><img src="/jayjun/m/static/img/test/@goods_110_02.jpg" alt="작은 썸네일"></span></a></li>
						<li><a data-slide-index="3"><span class="img"><img src="/jayjun/m/static/img/test/@goods_110_03.jpg" alt="작은 썸네일"></span></a></li>
						<li><a data-slide-index="4"><span class="img"><img src="/jayjun/web/static/img/test/@goods_760_01.jpg" alt="작은 썸네일"></span></a></li>
						<li><a data-slide-index="5"><span class="img"><img src="/jayjun/m/static/img/test/@goods_110_01.jpg" alt="작은 썸네일"></span></a></li>
					</ul>
				</div>
				<div class="share_wrp">
					<button type="button" class="btn_share"><i class="icon-share-btn">상품 공유하기</i></button>
				</div>
			</div><!-- //.thumb_box -->

			<div class="goods_spec">
				<section class="box_intro">
					<h2 class="ir-blind">상품명,간략소개</h2>
					<p class="goods_nm">I LOVE LIP STICK</p>
					<p class="goods_exp">어떤 입술도 누구나 쉽게 완성도 높은 립 메이크업 연출이 가능한 인체공학 스퀘어 버튼 멀티 립 펜슬</p>
				</section>

				<section class="box_summary">
					<h2 class="ir-blind">상품의 금액, 포인트, 배송비 정보</h2>
					<ul class="goods_summary">
						<li>
							<label>판매금액</label>
							<div class="inner">
								<div class="price">
									<del><strong>17,000</strong>원</del>
									<span class="sell_price"><strong>17,000</strong>원</span>
								</div>
							</div>
						</li>
						<li class="bdtop">
							<label>포인트</label>
							<div class="inner">5%</div>
						</li>
						<li>
							<label>배송비</label>
							<div class="inner">2,500원</div>
						</li>
					</ul>
				</section>

				<section class="box_option">
					<h2 class="ir-blind">상품의 옵션 선택</h2>
					<!-- 컬러칩+옵션선택 -->
					<div class="opt_select_wrp">
						<!-- 컬러칩선택 -->
						<div class="opt_colors">
							<input type="radio" name="color_choice" id="color_opt1"><label for="color_opt1"><img src="/jayjun/m/static/img/test/opt_color01.png" alt="컬러이름 출력"></label>
							<input type="radio" name="color_choice" id="color_opt2"><label for="color_opt2"><img src="/jayjun/m/static/img/test/opt_color02.png" alt="컬러이름 출력"></label> 
							<input type="radio" name="color_choice" id="color_opt3"><label for="color_opt3"><img src="/jayjun/m/static/img/test/opt_color03.png" alt="컬러이름 출력"></label>
							<input type="radio" name="color_choice" id="color_opt4"><label for="color_opt4"><img src="/jayjun/m/static/img/test/opt_color04.png" alt="컬러이름 출력"></label>
							<input type="radio" name="color_choice" id="color_opt5"><label for="color_opt5"><img src="/jayjun/m/static/img/test/opt_color05.png" alt="컬러이름 출력"></label>
							<input type="radio" name="color_choice" id="color_opt6" disabled><label for="color_opt6"><img src="/jayjun/m/static/img/test/opt_color06.png" alt="컬러이름 출력"></label>
							<input type="radio" name="color_choice" id="color_opt7"><label for="color_opt7"><img src="/jayjun/m/static/img/test/opt_color07.png" alt="컬러이름 출력"></label>
							<input type="radio" name="color_choice" id="color_opt8"><label for="color_opt8"><img src="/jayjun/m/static/img/test/opt_color08.png" alt="컬러이름 출력"></label>
							<input type="radio" name="color_choice" id="color_opt9"><label for="color_opt9"><img src="/jayjun/m/static/img/test/opt_color09.png" alt="컬러이름 출력"></label>
							<input type="radio" name="color_choice" id="color_opt10"><label for="color_opt10" class="bright"><img src="/jayjun/m/static/img/test/opt_color10.png" alt="컬러이름 출력"></label><!-- [D] 밝은 컬러인 경우 bright클래스 추가 -->
							<input type="radio" name="color_choice" id="color_opt11" disabled><label for="color_opt11" class="bright"><img src="/jayjun/m/static/img/test/opt_color11.png" alt="컬러이름 출력"></label><!-- [D] 밝은 컬러인 경우 bright클래스 추가 -->
						</div>
						<!-- 옵션선택 -->
						<div class="opt_select_add">
							<select class="select_line" title="옵션 선택">
								<option value="">선택</option>
								<option value="">PBE 101 orange</option>
								<option value="">PBE 102 coral</option>
								<option value="">PBE 103 beige</option>
							</select>
							<button type="button" class="btn_add"><span>추가하기</span></button>
						</div>
						<!-- 옵션추가내역 -->
						<div class="added_opt_list">
							<ul>
								<li>
									<div class="added_opt">
										<img src="/jayjun/m/static/img/test/opt_color06.png" alt="컬러이름 출력"><span class="name">PBE 103 beige</span>
									</div>
									<div class="ea-select">
										<input type="text" value="1" title="수량" readonly="">
										<button class="plus">수량증가</button>
										<button class="minus">수량감소</button>
									</div>
									<button type="button" class="btn_del"><span>삭제하기</span></button>
								</li>
							</ul>
						</div>
					</div>

					<!-- 추가구매선택 -->
					<div class="opt_select_wrp">
						<!-- 추가구매선택 -->
						<div class="opt_select_add">
							<select class="select_line" title="추가구매 상품 선택">
								<option value="">추가구매 상품 선택</option>
								<option value="">립앤아이 리무버</option>
							</select>
						</div>
						<!-- 추가구매내역 -->
						<div class="added_opt_list">
							<ul>
								<li>
									<div class="added_opt">
										<span class="name">립앤아이 리무버</span>
									</div>
									<div class="ea-select">
										<input type="text" value="1" title="수량" readonly="">
										<button class="plus">수량증가</button>
										<button class="minus">수량감소</button>
									</div>
									<button type="button" class="btn_del"><span>삭제하기</span></button>
								</li>
							</ul>
						</div>
					</div>

					<ul class="goods_summary">
						<li class="total_price">
							<label>총 상품 금액</label>
							<div class="inner total"><strong>17,000</strong>원</div>
						</li>
					</ul>
				</section>
			</div><!-- //.goods_spec -->

			<div class="area_button">
				<ul>
					<li class="like btn-like-count"><button type="button" class="btn-line"><i class="icon_like"></i><span class="like_count">12</span></button></li>
					<li><button class="btn-point"><span>장바구니</span></button></li>
					<li><button class="btn-point point2"><span>바로구매</span></button></li>
				</ul>
			</div>
		</div><!-- //.goods_info_area -->

		<div class="related_goods">
			<h3 class="title">함께 쓰면 좋은 제품</h3>
			<div class="goods_list">
				<ul>
					<li>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_thumb320_05.jpg" alt="상품 썸네일"></div>
								<figcaption>
									<p class="goods-nm">I NEED PORE VELVET</p>
									<p class="opt">5ml</p>
									<p class="price">19,000<span>원</span></p>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_380_01.jpg" alt="상품 썸네일"></div>
								<figcaption>
									<p class="goods-nm">I TOUCH CHEEK BLOSSOM</p>
									<p class="opt">10ml</p>
									<p class="price">15,000<span>원</span></p>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_thumb320_05.jpg" alt="상품 썸네일"></div>
								<figcaption>
									<p class="goods-nm">I NEED PORE VELVET</p>
									<p class="opt">5ml</p>
									<p class="price">19,000<span>원</span></p>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_380_01.jpg" alt="상품 썸네일"></div>
								<figcaption>
									<p class="goods-nm">I TOUCH CHEEK BLOSSOM</p>
									<p class="opt">10ml</p>
									<p class="price">15,000<span>원</span></p>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_thumb320_05.jpg" alt="상품 썸네일"></div>
								<figcaption>
									<p class="goods-nm">I NEED PORE VELVET</p>
									<p class="opt">5ml</p>
									<p class="price">19,000<span>원</span></p>
								</figcaption>
							</figure>
						</a>
					</li>
				</ul>
			</div>
		</div><!-- //.related_goods -->

		<div class="detail_content">
			<!-- 제품상세정보 -->
			<div class="articles on">
				<h2 class="title"><button type="button">제품 상세정보</button></h2>
				<div class="content">
					<div class="editor_area_detail">
						<p><img src="/jayjun/web/static/img/test/@goods_detail_content.jpg" alt="제품 상세정보"></p>
					</div>
				</div>
			</div>
			<!-- //제품상세정보 -->

			<!-- 구매후기 -->
			<div class="articles on">
				<h2 class="title"><button type="button">구매후기</button></h2>
				<div class="content">
					<div class="review">
						<div class="head clear">
							<div class="fl-l"><p class="count"><strong>구매후기</strong>(00)</p></div>
							<div class="fl-r"><a href="" class="btn-point">구매후기 작성</a></div>
						</div>
						<ul class="review_accordion">
							<li>
								<a href="javascript:;" class="btn_more">
									<div class="score">
										<img src="/jayjun/m/static/img/icon/rating_score5.png" alt="5점 만점중 5점">
										<!-- <img src="/jayjun/m/static/img/icon/rating_score4.png" alt="5점 만점중 4점">
										<img src="/jayjun/m/static/img/icon/rating_score3.png" alt="5점 만점중 3점">
										<img src="/jayjun/m/static/img/icon/rating_score2.png" alt="5점 만점중 2점">
										<img src="/jayjun/m/static/img/icon/rating_score1.png" alt="5점 만점중 1점"> -->
									</div>
									<div class="review_txt">
										<div class="editor-area">
											<p>제품명에서 알 수 있듯이 수분을 가득 피부에 충족시켜주는 워터 에센스 에센셜 토너~</p>
											<p>무엇보다 성분이 병에 자세히 나와 있어, 신뢰가 갈 뿐만이 아니라 이것을 하나의 제품 디자인으로 승화!</p>
											<p>피부가 지성이기는 하나, 수분이 부족하여 트러블이 나는 경우가 잦았었는데, 무향 무취의 에센스로 얼굴에 부드럽게 발려 자극이 없음을 느낄 수 있었다.</p>
											<p>봄 여름에 사용하다 보니, 이 에센스를 사용하면서 얼굴이 땡기거나 건조한 적은 없었던것 같다.</p>
										</div>
									</div>
									<div class="writer">
										<p class="date">2018.05.30</p>
										<p class="id">iloveio***</p>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:;" class="btn_more">
									<div class="score">
										<img src="/jayjun/m/static/img/icon/rating_score4.png" alt="5점 만점중 4점">
									</div>
									<div class="review_txt">
										<div class="editor-area">
											<p>제품명에서 알 수 있듯이 수분을 가득 피부에 충족시켜주는 워터 에센스 에센셜 토너~ 무엇보다 성분이 병에 자세히 나와 있어, 신뢰가 갈 뿐만이 아니라 이것을 하나의 제품 디자인으로 승화!</p>
											<p>피부가 지성이기는 하나, 수분이 부족하여 트러블이 나는 경우가 잦았었는데, 무향 무취의 에센스로 얼굴에 부드럽게 발려 자극이 없음을 느낄 수 있었다.</p>
											<p>봄 여름에 사용하다 보니, 이 에센스를 사용하면서 얼굴이 땡기거나 건조한 적은 없었던것 같다.</p>
											<p>피부에 자극적이지 않으면서 기능성까지 갖춘 제품, 앞으로도 많이 개발 &amp; 출시해주세요~ ^^</p>
										</div>
									</div>
									<div class="writer">
										<p class="date">2018.05.30</p>
										<p class="id">iloveio***</p>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:;" class="btn_more">
									<div class="score">
										<img src="/jayjun/m/static/img/icon/rating_score3.png" alt="5점 만점중 3점">
									</div>
									<div class="review_txt">
										<div class="editor-area">
											<p>제품명에서 알 수 있듯이 수분을 가득 피부에 충족시켜주는 워터 에센스 에센셜 토너~</p>
											<p>무엇보다 성분이 병에 자세히 나와 있어, 신뢰가 갈 뿐만이 아니라 이것을 하나의 제품 디자인으로 승화!</p>
											<p>피부가 지성이기는 하나, 수분이 부족하여 트러블이 나는 경우가 잦았었는데, 무향 무취의 에센스로 얼굴에 부드럽게 발려 자극이 없음을 느낄 수 있었다. 봄 여름에 사용하다 보니, 이 에센스를 사용하면서 얼굴이 땡기거나 건조한 적은 없었던것 같다.</p>
											<p>피부에 자극적이지 않으면서 기능성까지 갖춘 제품, 앞으로도 많이 개발 &amp; 출시해주세요~ ^^</p>
										</div>
									</div>
									<div class="writer">
										<p class="date">2018.05.30</p>
										<p class="id">iloveio***</p>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:;" class="btn_more">
									<div class="score">
										<img src="/jayjun/m/static/img/icon/rating_score2.png" alt="5점 만점중 2점">
									</div>
									<div class="review_txt">
										<div class="editor-area">
											<p>피부에 자극적이지 않으면서 기능성까지 갖춘 제품, 앞으로도 많이 개발 &amp; 출시해주세요~ ^^</p>
										</div>
									</div>
									<div class="writer">
										<p class="date">2018.05.30</p>
										<p class="id">iloveio***</p>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:;" class="btn_more">
									<div class="score">
										<img src="/jayjun/m/static/img/icon/rating_score1.png" alt="5점 만점중 1점">
									</div>
									<div class="review_txt">
										<div class="editor-area">
											<p>제품명에서 알 수 있듯이 수분을 가득 피부에 충족시켜주는 워터 에센스 에센셜 토너~</p>
											<p>무엇보다 성분이 병에 자세히 나와 있어, 신뢰가 갈 뿐만이 아니라 이것을 하나의 제품 디자인으로 승화!</p>
											<p>피부가 지성이기는 하나, 수분이 부족하여 트러블이 나는 경우가 잦았었는데, 무향 무취의 에센스로 얼굴에 부드럽게 발려 자극이 없음을 느낄 수 있었다.</p>
											<p>봄 여름에 사용하다 보니, 이 에센스를 사용하면서 얼굴이 땡기거나 건조한 적은 없었던것 같다.</p>
											<p>피부에 자극적이지 않으면서 기능성까지 갖춘 제품, 앞으로도 많이 개발 &amp; 출시해주세요~ ^^</p>
										</div>
									</div>
									<div class="writer">
										<p class="date">2018.05.30</p>
										<p class="id">iloveio***</p>
									</div>
								</a>
							</li>
						</ul>
					</div>
					<div class="list-paginate">
						<a href="#" class="prev-all">처음</a>
						<a href="#" class="prev">이전</a>
						<a href="#" class="on">1</a>
						<a href="#">2</a>
						<a href="#">3</a>
						<a href="#">4</a>
						<a href="#">5</a>
						<a href="#" class="next">다음</a>
						<a href="#" class="next-all">끝</a>
					</div>
				</div>
			</div>
			<!-- //구매후기 -->

			<!-- 포토리뷰 -->
			<div class="articles on">
				<h2 class="title"><button type="button">포토리뷰</button></h2>
				<div class="content">
					<div class="pt_review">
						<div class="head clear">
							<div class="fl-l"><p class="count"><strong>포토리뷰</strong>(00)</p></div>
							<div class="fl-r"><a href="" class="btn-point">포토리뷰 작성</a></div>
						</div>
						<ul class="pt_review_list">
							<li>
								<a href="javascript:;" class="btn_ptreview_view">
									<div class="post">
										<div class="img" style="background-image:url('/jayjun/web/static/img/test/@pt_review01.jpg')"></div>
										<div class="score">
											<img src="/jayjun/m/static/img/icon/rating_score4.png" alt="5점 만점중 4점">
										</div>
										<div class="txt">제품명에서 알 수 있듯이 수분을 가득 피부에 충족시켜주는 워터 에센스 에센셜 토너~</div>
										<p class="id">ilovei***</p>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:;" class="btn_ptreview_view">
									<div class="post">
										<div class="img" style="background-image:url('/jayjun/web/static/img/test/@pt_review02.jpg')"></div>
										<div class="score">
											<img src="/jayjun/m/static/img/icon/rating_score4.png" alt="5점 만점중 4점">
										</div>
										<div class="txt">제품명에서 알 수 있듯이 수분을 가득 피부에 충족시켜주는 워터 에센스 에센셜 토너~</div>
										<p class="id">ilovei***</p>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:;" class="btn_ptreview_view">
									<div class="post">
										<div class="img" style="background-image:url('/jayjun/web/static/img/test/@pt_review03.jpg')"></div>
										<div class="score">
											<img src="/jayjun/m/static/img/icon/rating_score4.png" alt="5점 만점중 4점">
										</div>
										<div class="txt">제품명에서 알 수 있듯이 수분을 가득 피부에 충족시켜주는 워터 에센스 에센셜 토너~</div>
										<p class="id">ilovei***</p>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:;" class="btn_ptreview_view">
									<div class="post">
										<div class="img" style="background-image:url('/jayjun/web/static/img/test/@pt_review04.jpg')"></div>
										<div class="score">
											<img src="/jayjun/m/static/img/icon/rating_score4.png" alt="5점 만점중 4점">
										</div>
										<div class="txt">제품명에서 알 수 있듯이 수분을 가득 피부에 충족시켜주는 워터 에센스 에센셜 토너~</div>
										<p class="id">ilovei***</p>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:;" class="btn_ptreview_view">
									<div class="post">
										<div class="img" style="background-image:url('/jayjun/web/static/img/test/@pt_review05.jpg')"></div>
										<div class="score">
											<img src="/jayjun/m/static/img/icon/rating_score4.png" alt="5점 만점중 4점">
										</div>
										<div class="txt">제품명에서 알 수 있듯이 수분을 가득 피부에 충족시켜주는 워터 에센스 에센셜 토너~</div>
										<p class="id">ilovei***</p>
									</div>
								</a>
							</li>
						</ul>
					</div>
					<div class="list-paginate">
						<a href="#" class="prev-all">처음</a>
						<a href="#" class="prev">이전</a>
						<a href="#" class="on">1</a>
						<a href="#">2</a>
						<a href="#">3</a>
						<a href="#">4</a>
						<a href="#">5</a>
						<a href="#" class="next">다음</a>
						<a href="#" class="next-all">끝</a>
					</div>
				</div>
			</div>
			<!-- //포토리뷰 -->

			<!-- 구매유의사항 -->
			<div class="articles on">
				<h2 class="title"><button type="button">구매유의사항</button></h2>
				<div class="content">
					<div class="purchase_notes">
						<dl>
							<dt>배송안내</dt>
							<dd>상품 발송은 평일(영업일 기준)오전 09시 이전 결제 완료 시 당일 발송 예정입니다.<br> (단, 제주도 및 도서산간지역은 3~5일정도 소요되며, 주문량이 많은 특별 행사기간에는 다소 지연될 수 있습니다.)<br>3만원 이상 주문 시 상품은 무료 배송되며, 3만원 미만 주문 시 기본 배송료 2,500원이 부과됩니다.<br> (단, 제주도 및 도서산간지역은 추가비용이 발생할 수 있습니다.)<br>제이준코스메틱 공식쇼핑몰은 기본적으로 CJ대한통운택배를 이용하여 상품을 배송해 드립니다.<br>배송지가 군부대일 경우 상품은 반송될 수 있으며, 이 때 발생하는 왕복 배송비는 고객님 부담입니다.</dd>
						</dl>
						<dl>
							<dt>교환 및 반품 안내</dt>
							<dd>단순 변심에 의한 교환 및 반품은 상품을 수령하신 일로부터 7일 이내 미 개봉 상품에 대해, 고객센터(소비자 상담실) 및 질문 답변 게시판을 통해 교환 및 반품에 대한 고객님의 의사를 표하신 경우 가능합니다.<br>(단, 이로 인해 발생하는 왕복 배송비는 고객님의 부담입니다.)<br><br>또한, 수령하신 상품 또는 상품의 내용이 표시광고 내용과 다르거나 계약 내용과 다르게 이행된 때에는 상품을 수령하신 일로부터 3월 이내, 그 사실을 안 날 또는 알 수 있었던 날부터 30일 이내 가능합니다.<br><br>상품 불량 및 오 배송 등으로 인한 교환 및 반품 신청의 경우 배송비는 무료입니다. </dd>
						</dl>
						<dl>
							<dt>교환 및 반품이 불가능한 경우 </dt>
							<dd>
								<ul class="dash_list">
									<li>단순 변심에 의한 교환 및 반품은 상품을 수령하신 일로부터 7일이 경과된 경우</li>
									<li>고객님의 부주의 및 책임으로 인해 상품(재화) 등이 멸실 또는 훼손된 경우</li>
									<li>고객님의 상품 사용 또는 일부 소비에 의하여 상품의 가치가 현저히 감소한 경우</li>
									<li>시간의 경과에 의하여 재판매가 곤란할 정도로 상품 등의 가치가 현저히 감소한 경우</li>
									<li>사전에 교환 및 반품에 대해 제한되는 사실을 고객이 알 수 있는 곳에 표기한 경우</li>
								</ul>
							</dd>
						</dl>
						<dl>
							<dt>고객센터</dt>
							<dd>
								<p><strong class="tel">080-881-2001</strong></p>
								<p><strong>고객센터 운영시간</strong>(평일 : 09:00 ~ 18:00)</p>
								<p>점심 12:00 ~ 13:00 (통화 불가) / 주말 및 공휴일 휴무</p>
							</dd>
						</dl>
					</div>
				</div>
			</div>
			<!-- //구매유의사항 -->
		</div>
	</main>
	<!-- //Content -->

	<!-- 공유하기팝업 -->
	<div class="pop_layer lyr_share">
		<div class="links">
			<a href="javascript:;" title="페이스북"><i class="icon-share-facebook">페이스북</i></a>
			<a href="javascript:;" title="인스타그램"><i class="icon-share-instagram">인스타그램</i></a>
			<a href="javascript:;" title="카카오 스토리"><i class="icon-share-kakaostory">카카오 스토리</i></a>
			<a href="javascript:;" title="링크"><i class="icon-share-url">링크</i></a>
			<a href="javascript:;" title="트위터"><i class="icon-share-twitter">트위터</i></a>
			<button type="button" class="btn_cls btn_close">닫기</button>
		</div>
	</div>
	<!-- //공유하기팝업 -->

	<!-- 포토리뷰보기팝업 -->
	<section class="pop_layer layer_ptreview_view">
		<div class="inner">
			<h3 class="title">포토리뷰 상세<button type="button" class="btn_close">닫기</button></h3>
			<div class="ptreview_view">
				<div class="img">
					<ul class="slider clear">
						<li><img src="/jayjun/web/static/img/test/@goods_760_01.jpg" alt="리뷰이미지"></li>
						<li><img src="/jayjun/m/static/img/test/@goods_110_01.jpg" alt="리뷰이미지"></li>
						<li><img src="/jayjun/m/static/img/test/@goods_110_02.jpg" alt="리뷰이미지"></li>
						<li><img src="/jayjun/m/static/img/test/@goods_110_03.jpg" alt="리뷰이미지"></li>
					</ul>
				</div>
				<div class="con">
					<div class="user clear">
						<div class="score"><img src="/jayjun/m/static/img/icon/rating_score4.png" alt="5점 만점중 4점"></div>
						<div class="writer">ilovei*** <span></span> 2018.08.30</div>
						<div class="function-btn">
							<button class="btn-point" type="button"><span>수정</span></button>
							<button class="btn-line" type="button"><span>삭제</span></button>
						</div>
					</div>
					<div class="txt">
						<strong>리뷰작성 타이틀은 여기에 출력</strong>
						제품명에서 알 수 있듯이 수분을 가득 피부에 충족시켜주는 워터 에센스 에센셜 토너~무엇보다 성분이 병에 자세히 나와 있어, 신뢰가 갈 뿐만이 아니라 이것을 하나의 제품 디자인으로 승화! 피부가 지성이기는 하나, 수분이 부족하여 트러블이 나는 경우가 잦았었는데,무향 무취의 에센스로 얼굴에 부드럽게 발려 자극이 없음을 느낄수 있었다
					</div>
					<div class="review-goods-link">
						<div class="thumbnail"><img src="../jayjun/web/static/img/test/@goods_380_02.jpg" alt=""></div>
						<div class="info">
							<div class="copy-main">아이 니드 글로우 쉬머 베이스</div>
							<div class="copy-sub">공기처럼 가볍고 코튼처럼 부드럽게 발리는 메이크업 베이스 두줄정도까지 나오면 이렇게 됩니다. 세줄부턴 감춥니다 자동으로</div>
							<div class="price-link">
								<div class="price">25,000<span>원</span></div>
								<a href="" class="btn-point">리뷰상품 보기</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- //포토리뷰보기팝업 -->

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
	// 작은 썸네일 slider
	$('.sm_thumb').each(function(){
		var thumb = $(this).find('li');
		if( thumb.length > 4 ){
			$('.sm_thumb .slider').bxSlider({
				slideWidth: 60,
				slideMargin: 3,
				minSlides: 4,
				maxSlides: 4,
				moveSlides: 1,
				infiniteLoop: false,
				hideControlOnEnd: true,
				pager: false
			});
		}
	});
	// 상품 대표 썸네일 slider
	$('.big_thumb .slider').bxSlider({
		mode: 'fade',
		controls: false,
		pagerCustom: '.sm_thumb .slider'
	});

	// 상세정보 > 메뉴 토글 
	$('.detail_content .articles').each(function(){
		var ui = $(this);
		var btn = $(this).find('.title button');
		btn.click(function(){
			ui.toggleClass('on');
		});
	});

	// 구매후기 toggle
	$('.review_accordion .btn_more').click(function(){
		var item = $(this).parents('li'),
			other = item.siblings('li');
		if( item.hasClass('active') ){
			item.removeClass('active').find('.btn_more').attr('title','더보기');
		}else{
			other.removeClass('active').find('.btn_more').attr('title','더보기');
			item.addClass('active').find('.btn_more').attr('title','닫기');
		}
	});
});
</script>

</body>
</html>
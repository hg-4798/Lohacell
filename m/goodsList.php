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
		<div class="list_head">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<ul class="goods_cate_sort">
				<li>
					<select title="1차 카테고리">
						<option value="">BASE</option>
						<option value="">CHEEK</option>
						<option value="">EYE</option>
						<option value="" selected>LIP</option>
						<option value="">CARE</option>
						<option value="">TOOLS</option>
						<option value="">BASE make up</option>
					</select>
				</li>
				<li>
					<select title="2차 카테고리">
						<option value="">LIP STICK</option>
						<option value="">LIP TINT</option>
						<option value="">LIP LINER</option>
						<option value="">Make up base</option>
					</select>
				</li>
			</ul>
		</div>

		<div class="list_visual">
			<ul class="slider">
				<li>
					<a href="#" class="goods-item">
						<figure>
							<div class="img"><img src="../../../jayjun/web/static/img/test/@list_visual01.jpg" alt=""></div>
							<div class="info_area">
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<p class="ment">사랑스러운 입술을 표현해주는<br>벨벳 느낌의 공기밀착</p>
								<figcaption>
									<p class="goods-nm">I TOUCH CHEEK BLOSSOM 1</p>
									<p class="opt">10ml</p>
									<p class="price">15,000<span>원</span></p>
									<div class="color-chip">
										<span><img src="../../../jayjun/web/static/img/test/chip_color01.png" alt="컬러이름 출력"></span>
										<span><img src="../../../jayjun/web/static/img/test/chip_color02.png" alt="컬러이름 출력"></span>
										<span><img src="../../../jayjun/web/static/img/test/chip_color03.png" alt="컬러이름 출력"></span>
									</div>
								</figcaption>
							</div>
						</figure>
					</a>
				</li>
				<li>
					<a href="#" class="goods-item">
						<figure>
							<div class="img"><img src="../../../jayjun/web/static/img/test/@list_visual01.jpg" alt=""></div>
							<div class="info_area">
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<p class="ment">사랑스러운 입술을 표현해주는<br>벨벳 느낌의 공기밀착</p>
								<figcaption>
									<p class="goods-nm">I TOUCH CHEEK BLOSSOM 2</p>
									<p class="opt">10ml</p>
									<p class="price">15,000<span>원</span></p>
									<div class="color-chip">
										<span><img src="../../../jayjun/web/static/img/test/chip_color01.png" alt="컬러이름 출력"></span>
										<span><img src="../../../jayjun/web/static/img/test/chip_color02.png" alt="컬러이름 출력"></span>
										<span><img src="../../../jayjun/web/static/img/test/chip_color03.png" alt="컬러이름 출력"></span>
									</div>
								</figcaption>
							</div>
						</figure>
					</a>
				</li>
				<li>
					<a href="#" class="goods-item">
						<figure>
							<div class="img"><img src="../../../jayjun/web/static/img/test/@list_visual01.jpg" alt=""></div>
							<div class="info_area">
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<p class="ment">사랑스러운 입술을 표현해주는<br>벨벳 느낌의 공기밀착</p>
								<figcaption>
									<p class="goods-nm">I TOUCH CHEEK BLOSSOM 3</p>
									<p class="opt">10ml</p>
									<p class="price">15,000<span>원</span></p>
									<div class="color-chip">
										<span><img src="../../../jayjun/web/static/img/test/chip_color01.png" alt="컬러이름 출력"></span>
										<span><img src="../../../jayjun/web/static/img/test/chip_color02.png" alt="컬러이름 출력"></span>
										<span><img src="../../../jayjun/web/static/img/test/chip_color03.png" alt="컬러이름 출력"></span>
									</div>
								</figcaption>
							</div>
						</figure>
					</a>
				</li>
			</ul>
		</div>

		<div class="goods_list_wrp">
			<div class="select_area">
				<select class="select_line" title="sort by">
					<option value="">Sort by</option>
					<option value="">신상품순</option>
					<option value="">인기순</option>
					<option value="">상품평순</option>
					<option value="">좋아요순</option>
					<option value="">낮은가격순</option>
					<option value="">높은가격순</option>
				</select>
			</div>
			<div class="goods_list">
				<ul>
					<li>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_thumb320_02.jpg" alt=""></div>
								<figcaption>
									<p class="goods-nm">I NEED PORE VELVET BASE</p>
									<p class="opt">SPF50 / PA+++ / 20ml</p>
									<p class="price">19,000<span>원</span></p>
									<div class="color-chip">
										<span><img src="/jayjun/web/static/img/test/chip_color01.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color02.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color03.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color04.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color05.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color06.png" alt="컬러이름 출력"></span>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">100</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_380_02.jpg" alt="상품 썸네일"></div>
								<figcaption>
									<p class="goods-nm">I TOUCH CHEEK BLOSSOM</p>
									<p class="opt">10ml</p>
									<p class="price">15,000<span>원</span></p>
									<div class="color-chip">
										<span><img src="/jayjun/web/static/img/test/chip_color14.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color15.png" alt="컬러이름 출력"></span>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">100</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_thumb320_07.jpg" alt="상품 썸네일"></div>
								<figcaption>
									<p class="goods-nm">I LIKE EYE SHADOW TRIO</p>
									<p class="opt">50ml</p>
									<p class="price">9,000<span>원</span></p>
									<div class="color-chip">
										<span><img src="/jayjun/web/static/img/test/chip_color07.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color16.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color17.png" alt="컬러이름 출력"></span>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">100</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_thumb320_01.jpg" alt="상품 썸네일"></div>
								<figcaption>
									<p class="goods-nm">I LIKE EYE SHADOW TRIO</p>
									<p class="opt">10ml</p>
									<p class="price">15,000<span>원</span></p>
									<div class="color-chip"></div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_thumb320_10.jpg" alt="상품 썸네일"></div>
								<figcaption>
									<p class="goods-nm">I NEED PORE VELVET</p>
									<p class="opt">5ml</p>
									<p class="price">19,000<span>원</span></p>
									<div class="color-chip">
										<span><img src="/jayjun/web/static/img/test/chip_color01.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color02.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color03.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color06.png" alt="컬러이름 출력"></span>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="#" class="goods-item">
							<figure>
								<div class="like-local"><button type="button"><i class="icon_like"></i> <span class="like_count">12</span></button></div>
								<div class="img"><img src="/jayjun/web/static/img/test/@goods_thumb320_09.jpg" alt="상품 썸네일"></div>
								<figcaption>
									<p class="goods-nm">I TOUCH CHEEK BLOSSOM</p>
									<p class="opt">10ml</p>
									<p class="price">15,000<span>원</span></p>
									<div class="color-chip"></div>
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
									<div class="color-chip">
										<span><img src="/jayjun/web/static/img/test/chip_color09.png" alt="컬러이름 출력"></span>
									</div>
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
									<div class="color-chip">
										<span><img src="/jayjun/web/static/img/test/chip_color07.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color08.png" alt="컬러이름 출력"></span>
										<span><img src="/jayjun/web/static/img/test/chip_color09.png" alt="컬러이름 출력"></span>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
				</ul>
				<a href="javascript:;" class="btn_more">더보기</a>
			</div>
		</div>
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
	// list visual slider
	$('.list_visual .slider').bxSlider({
		mode: 'fade',
		controls: false, 
		pagerType: 'short'
	});
});
</script>

</body>
</html>
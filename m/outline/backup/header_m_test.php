
<!doctype html>
<html lang="ko">
<head>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<meta charset="utf-8">
	<meta name="keywords" content="">
	<meta name="description" content="">

	<!-- 
		(D) title은 각 페이지에 맞게 수정되어야 합니다
		메인 : 사이트이름
		서브 : 소분류 < 중분류 < 대분류 - 사이트이름
		상세페이지 : 상품이름 상세페이지 - 사이트이름
	-->
	<title>ORYANY</title>

	<link rel="stylesheet" href="css2/common.css">
	<link rel="stylesheet" href="css2/main.css">
	<link rel="stylesheet" href="css2/sub.css">
	
	<script src="js2/libs/jquery-2.1.3.min.js"></script>
	<script src="js2/libs/TweenMax-1.15.0.min.js"></script>
	<script src="js2/libs/hammer-2.0.4.min.js"></script>
	<script src="js2/breadcrumb.js"></script>
	<script src="js2/common.js"></script>
	<script src="js2/mainEvent.js"></script>
	<script type="text/javascript" src="../js/jquery.sudoSlider.js" ></script>

</head>
<body>
	
	<!-- 헤더 고정 -->
	<header id="header">
		<!-- 글로벌 상단 -->
		<div class="topwrap">
			<h1><a href="mainToday.html"><img src="images/header_logo.png" alt="ORYANY"></a></h1>
			<nav class="gnb">
				<ul>
					<li><a href="#">BAGS</a></li>
					<li><a href="#">WALLET</a></li>
					<li><a href="#">UNISEX BAGS</a></li>
					<li><a href="#">ACC</a></li>
				</ul>
			</nav>
		</div>
		<!-- // 글로벌 상단 -->
		<nav class="depth2">
			<ul>
				<li class="on"><a href="#"><span>여성가방</span></a></li>
				<li><a href="#"><span>남성가방</span></a></li>
			</ul>
		</nav>

		<!-- 브레드크럼 - 상품리스트에서만 나오면 됩니다. -->
		<nav class="breadcrumb hide">
			<!--
				(D) 1뎁스의 개수에 따라 ul 에 1개일 때는 class="solo", 2개일 때는 class="duo" 를 추가합니다. (가로 길이 조절)
				선택된 1뎁스 li 에 class="on" 을 추가하고, .onebox 에 title="하위 메뉴 숨기기" 로 변경합니다.
				2뎁스 메뉴를 2열로 구성할 때는 .twobox ul 에 class="half" 를 추가합니다.
				하위 메뉴가 없을 때는 .onebox 안에 span 으로 내용을 넣어줍니다.
			-->
			<ul class="duo">
				<li>
					<div class="onebox" title="하위 메뉴 펼쳐보기"><a href="#">티셔츠/셔츠(324) <div class="arrow"></div></a></div>
					<div class="twobox">
						<ul class="half">
							<li><a href="#">PERFORMANCE <span>(252)</span></a></li>
							<li><a href="#">ACTIVE <span>(171)</span></a></li>
							<li><a href="#">LIFE STYLE <span>(339)</span></a></li>
							<li><a href="#">신상품 <span>(199)</span></a></li>
							<li><a href="#">다운 <span>(88)</span></a></li>
						</ul>
					</div>
				</li>
				<li class="opt">
					<div>
						<select name="" id="">
							<option value="">신상품순</option>
							<option value="">인기순</option>
						</select>
					</div>
				</li>
			</ul>
		</nav>
		<!-- // 브레드크럼 - 상품리스트에서만 나오면 됩니다. -->

		<!-- 서브페이지 상단에 출력되는 타이틀 및 뒤로가기버튼 -->
		<nav class="sub_page_title hide">
			상세보기
			<a href="#" class="btn_back" title="뒤로가기"></a>
		</nav>
		<!-- //서브페이지 상단에 출력되는 타이틀 및 뒤로가기버튼 -->
		
	</header>
	<!-- // 헤더 고정 -->

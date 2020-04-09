<?php
include_once('../outline/header.php')
?>
	<script defer src="../static/js/jquery.flexslider.js"></script>
	<link rel="stylesheet" href="../static/css/flexslider.css">

	<!-- 내용 -->
	<main id="content">
		<!-- 룩북 비주얼 -->
		<div class="js-studio-lookbook-visual">
			<h2>THE QUIET CITY</h2>
			<div class="js-menu-list">
				<button class="js-btn-toggle" title="펼쳐보기"><span class="ir-blind">룩북 목록</span></button>
				<div class="js-list-content">
					<ul>
						<li><a href="#">THE QUIET CITY</a></li>
						<li><a href="#">PLAY THE STAR</a></li>
						<li><a href="#">MERRY CASH HOLIDAY</a></li>
						<li><a href="#">THE ONLY CASH</a></li>
						<li><a href="#">FIRST 0123</a></li>
					</ul>
				</div>
			</div>
			<section class="slider">
				<div id="lookbook-thumb" class="flexslider">
					<ul class="slides">
						<li class=""><a href="#"><img src="../static/img/test/@studio_lookbook_visual1.jpg" alt=""></a></li>
						<li class=""><a href="#"><img src="../static/img/test/@studio_lookbook_visual2.jpg" alt=""></a></li>
						<li class=""><a href="#"><img src="../static/img/test/@studio_lookbook_visual3.jpg" alt=""></a></li>
						<li class=""><a href="#"><img src="../static/img/test/@studio_lookbook_visual1.jpg" alt=""></a></li>
						<li class=""><a href="#"><img src="../static/img/test/@studio_lookbook_visual2.jpg" alt=""></a></li>
						<li class=""><a href="#"><img src="../static/img/test/@studio_lookbook_visual3.jpg" alt=""></a></li>
					</ul>
				</div>
				<div id="lookbook-visual" class="flexslider page">
					<ul class="slides">
						<li class=""><a href="#"><img src="../static/img/test/@studio_lookbook_visual_thumb1.jpg" alt="1"></a></li>
						<li class=""><a href="#"><img src="../static/img/test/@studio_lookbook_visual_thumb2.jpg" alt="2"></a></li>
						<li class=""><a href="#"><img src="../static/img/test/@studio_lookbook_visual_thumb3.jpg" alt="3"></a></li>
						<li class=""><a href="#"><img src="../static/img/test/@studio_lookbook_visual_thumb1.jpg" alt="1"></a></li>
						<li class=""><a href="#"><img src="../static/img/test/@studio_lookbook_visual_thumb2.jpg" alt="2"></a></li>
						<li class=""><a href="#"><img src="../static/img/test/@studio_lookbook_visual_thumb3.jpg" alt="3"></a></li>
					</ul>
				</div>
			</section>
		</div>
		<!-- 룩북 비주얼 -->

	

	<script type="text/javascript">
	$(window).load(function() {
	// The slider being synced must be initialized first
	$('#lookbook-visual').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: 110,
		itemMargin: 0,
		asNavFor: '#lookbook-thumb'
	});

	$('#lookbook-thumb').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		sync: "#lookbook-visual"
		});
	});
	</script>



		<!-- 상품 리스트 -->
		<div class="goods-list studio-lookbook-list">
			<!-- (D) 위시리스트 담기 버튼 선택 시 class="on" title="담겨짐"을 추가합니다. -->
			<ul class="js-goods-list">
				<li>
					<a href="#">
						<figure>
							<div class="img"><img src="../static/img/test/@goods_list1.jpg" alt=""></div>
							<figcaption>
								<span class="brand">96NEWYORK</span>
								<span class="sale">[UP TO 15% OFF]</span>
								<span class="name">2WAY DOWN JUMPER</span>
								<span class="price"><del>898,000</del><strong>479,000</strong></span>
							</figcaption>
						</figure>
					</a>
					<button class="btn-wishlist on" type="button" title="담겨짐"><span class="ir-blind">위시리스트 담기/버리기</span></button>
				</li>
				<li>
					<a href="#">
						<figure>
							<div class="img"><img src="../static/img/test/@goods_list2.jpg" alt=""></div>
							<figcaption>
								<span class="brand">C.A.S.H</span>
								<span class="name">LONG TAILORED VOLUME JACKET</span>
								<span class="price"><del>898,000</del><strong>199,000</strong></span>
								<!-- (D) span.sale 이 없을 경우 높이를 맞춰주기 위해 span.empty를 넣어줍니다. -->
								<span class="empty">&#160;</span>
							</figcaption>
						</figure>
					</a>
					<button class="btn-wishlist" type="button"><span class="ir-blind">위시리스트 담기/버리기</span></button>
				</li>
				<li>
					<a href="#">
						<figure>
							<div class="img"><img src="../static/img/test/@goods_list3.jpg" alt=""></div>
							<figcaption>
								<span class="brand">96NEWYORK</span>
								<span class="sale">[UP TO 15% OFF]</span>
								<span class="name">2WAY DOWN JUMPER</span>
								<span class="price"><del>898,000</del><strong>479,000</strong></span>
							</figcaption>
						</figure>
					</a>
					<button class="btn-wishlist" type="button"><span class="ir-blind">위시리스트 담기/버리기</span></button>
				</li>
				<li>
					<a href="#">
						<figure>
							<div class="img"><img src="../static/img/test/@goods_list4.jpg" alt=""></div>
							<figcaption>
								<span class="brand">C.A.S.H</span>
								<span class="name">LONG TAILORED VOLUME JACKET</span>
								<span class="price"><del>898,000</del><strong>199,000</strong></span>
								<!-- (D) span.sale 이 없을 경우 높이를 맞춰주기 위해 span.empty를 넣어줍니다. -->
								<span class="empty">&#160;</span>
							</figcaption>
						</figure>
					</a>
					<button class="btn-wishlist on" type="button" title="담겨짐"><span class="ir-blind">위시리스트 담기/버리기</span></button>
				</li>
			</ul>
		</div>
		<!-- // 상품 리스트 -->


	</main>
	<!-- // 내용 -->

<?php
include_once('../outline/footer.php')
?>
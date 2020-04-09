<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">좋아요</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				
				<section data-ui="TabMenu">
					<div class="tabs top"> 
						<button type="button" data-content="menu" class="active"><span>ALL (100)</span></button>
						<button type="button" data-content="menu"><span>상품</span></button>
						<button type="button" data-content="menu"><span>E-CATALOG</span></button>
						<button type="button" data-content="menu"><span>룩북</span></button>
						<button type="button" data-content="menu"><span>매거진</span></button>
						<button type="button" data-content="menu"><span>인스타그램</span></button>
						<button type="button" data-content="menu"><span>MOVIE</span></button>
					</div>
					<div data-content="content" class="mt-50 my-main-list active">
						<ul class="clear">
							<li class="item">
								<a class="like-item" href="#">
									<div class="like-count"><i class="icon-dark-like"></i><span>255</span></div>
									<figure>
										<img src="../static/img/test/@loobook_thumb03.jpg" alt="">
										<figcaption>
											<div class="type">BESTIBELLI</div>
											<div class="subject">시프트 플라운스 원피스</div>
										</figcaption>
									</figure>
								</a>
							</li>
							<li class="item">
								<a class="like-item" href="#">
									<div class="like-count"><i class="icon-dark-like"></i><span>255</span></div>
									<figure>
										<img src="../static/img/banner/catalog_view01.jpg" alt="">
										<figcaption>
											<div class="type">카탈로그</div>
											<div class="subject">2017 S/S신상 기획전</div>
										</figcaption>
									</figure>
								</a>
							</li>
							<li class="item">
								<a class="like-item" href="#">
									<div class="like-count"><i class="icon-dark-like"></i><span>255</span></div>
									<figure>
										<img src="../static/img/banner/lookbook_view01.jpg" alt="">
										<figcaption>
											<div class="type">룩북</div>
											<div class="subject">2017 S/S신상 기획전</div>
										</figcaption>
									</figure>
								</a>
							</li>
							<li class="item">
								<a class="like-item" href="#">
									<div class="like-count"><i class="icon-dark-like"></i><span>255</span></div>
									<figure>
										<img src="../static/img/banner/outlet_banner580.jpg" alt="">
										<figcaption>
											<div class="type">매거진</div>
											<div class="subject">여행을 좋아하는 당신 지금 겨울여행을 떠나라</div>
										</figcaption>
									</figure>
								</a>
							</li>
							<li class="item">
								<a class="like-item" href="#">
									<div class="like-count"><i class="icon-dark-like"></i><span>255</span></div>
									<figure>
										<img src="../static/img/test/@loobook_thumb05.jpg" alt="">
										<figcaption>
											<div class="type">BESTIBELLI</div>
											<div class="subject">시프트 플라운스 원피스</div>
										</figcaption>
									</figure>
								</a>
							</li>
							<li class="item">
								<a class="like-item" href="#">
									<div class="like-count"><i class="icon-dark-like"></i><span>255</span></div>
									<figure>
										<img src="../static/img/banner/catalog_view01.jpg" alt="">
										<figcaption>
											<div class="type">카탈로그</div>
											<div class="subject">2017 S/S신상 기획전</div>
										</figcaption>
									</figure>
								</a>
							</li>
							<li class="item">
								<a class="like-item" href="#">
									<div class="like-count"><i class="icon-dark-like"></i><span>255</span></div>
									<figure>
										<img src="../static/img/banner/lookbook_view05.jpg" alt="">
										<figcaption>
											<div class="type">룩북</div>
											<div class="subject">2017 S/S신상 기획전</div>
										</figcaption>
									</figure>
								</a>
							</li>
							<li class="item">
								<a class="like-item" href="#">
									<div class="like-count"><i class="icon-dark-like"></i><span>255</span></div>
									<figure>
										<img src="../static/img/banner/lookbook_view02.jpg" alt="">
										<figcaption>
											<div class="type">매거진</div>
											<div class="subject">여행을 좋아하는 당신 지금 겨울여행을 떠나라</div>
										</figcaption>
									</figure>
								</a>
							</li>
						</ul>
						<div class="read-more mt-45"><button type="button"><span>READ MORE</span></button></div>
					</div>
					<div data-content="content" class="mt-50 my-main-list">
						ALL 와 동일2
					</div>
					<div data-content="content" class="mt-50 my-main-list">
						ALL 와 동일3
					</div>
					<div data-content="content" class="mt-50 my-main-list">
						ALL 와 동일4
					</div>
					<div data-content="content" class="mt-50 my-main-list">
						ALL 와 동일5
					</div>
					<div data-content="content" class="mt-50 my-main-list">
						ALL 와 동일6
					</div>
					<div data-content="content" class="mt-50 my-main-list">
						ALL 와 동일7
					</div>
				</section>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<script type="text/javascript">
$(function  () {
	var $container = $('.my-main-list ul');
	$container.imagesLoaded( function() {
		$container.masonry({ 
			itemSelector: '.item' 
		});
	});
});
</script>

<?php include_once('../outline/footer.php') ?>
<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="brand-page">

		<article class="brand-wrap">
			<header><h2 class="brand-title">룩북</h2></header>
			<div class="brand-gallery">
				<div class="goods-sort clear">
					<div class="sort-by ">
						<label for="season_by">Season</label>
						<div class="select">
							<select id="season_by">
								<option>2017 F/W</option>
								<option>2017 F/W</option>
							</select>
						</div>
					</div>
					<div class="sort-by ml-5">
						<label for="sort_by2">Sort by</label>
						<div class="select">
							<select id="sort_by2">
								<option>신상품순</option>
								<option>높은가격순</option>
							</select>
						</div>
					</div>
				</div><!-- //.goods-sort -->
				<ul class="flexible-list">
					<li class="item">
						<div class="like-show"><span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 --></div>
						<a href="/jayjun/web/brand/brand_lookbook_view.php" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p>2017년 F/W VIKI CATALOG</p>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<div class="like-show"><span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 --></div>
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p>2017년 F/W VIKI CATALOG</p>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<div class="like-show"><span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 --></div>
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p>2017년 F/W VIKI CATALOG</p>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<div class="like-show"><span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 --></div>
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p>2017년 F/W VIKI CATALOG</p>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<div class="like-show"><span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 --></div>
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p>2017년 F/W VIKI CATALOG</p>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<div class="like-show"><span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 --></div>
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p>2017년 F/W VIKI CATALOG</p>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<div class="like-show"><span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 --></div>
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p>2017년 F/W VIKI CATALOG</p>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<div class="like-show"><span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 --></div>
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p>2017년 F/W VIKI CATALOG</p>
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
				</ul>
			</div><!-- //.brand-gallery -->
			<div class="read-more mt-70"><button type="button"><span>READ MORE</span></button></div>
		</article>

	</div>
</div><!-- //#contents -->

<script type="text/javascript">
$(function  () {
	var $container = $('.flexible-list');
	$container.imagesLoaded( function() {
		$container.masonry({ 
			itemSelector: '.item' 
		});
	});
});
</script>

<?php include_once('../outline/footer.php') ?>
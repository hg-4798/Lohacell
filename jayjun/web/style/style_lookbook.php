<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="style-page">

		<article class="style-wrap">
			<header><h2 class="style-title">룩북</h2></header>
			<div class="brand-gallery">
				<div class="goods-sort clear">
					<div class="sort-by ">
						<label for="season_by5">Season</label>
						<div class="select">
							<select id="season_by5">
								<option>2017 F/W</option>
								<option>2017 F/W</option>
							</select>
						</div>
					</div>
					<div class="sort-by ml-5">
						<label for="sort_by">Sort by</label>
						<div class="select">
							<select id="sort_by5">
								<option>신상품순</option>
								<option>높은가격순</option>
							</select>
						</div>
					</div>
				</div><!-- //.goods-sort -->
				<ul class="flexible-list">
					<li class="item">
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p class="brand-nm">BESTIBELLI</p>
										<p>2017년 F/W VIKI CATALOG</p>
										<span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 -->
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p class="brand-nm">BESTIBELLI</p>
										<p>2017년 F/W VIKI CATALOG</p>
										<span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 -->
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p class="brand-nm">BESTIBELLI</p>
										<p>2017년 F/W VIKI CATALOG</p>
										<span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 -->
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p class="brand-nm">BESTIBELLI</p>
										<p>2017년 F/W VIKI CATALOG</p>
										<span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 -->
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p class="brand-nm">BESTIBELLI</p>
										<p>2017년 F/W VIKI CATALOG</p>
										<span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 -->
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p class="brand-nm">BESTIBELLI</p>
										<p>2017년 F/W VIKI CATALOG</p>
										<span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 -->
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p class="brand-nm">BESTIBELLI</p>
										<p>2017년 F/W VIKI CATALOG</p>
										<span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 -->
									</div>
								</figcaption>
							</figure>
						</a>
					</li>
					<li class="item">
						<a href="#" class="catalog-item">
							<figure>
								<img src="../static/img/test/@loobook_thumb01.jpg" alt="">
								<figcaption>
									<div class="inner">
										<p class="brand-nm">BESTIBELLI</p>
										<p>2017년 F/W VIKI CATALOG</p>
										<span><i class="icon-like">좋아요</i>11</span><!-- [D] 좋아요 i 태그에 .on 추가 -->
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
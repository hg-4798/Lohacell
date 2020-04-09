<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="goodsList-page">
		
		<div class="goods-breadcrumb">
			<a href="#" class="active">쇼윈도</a>
		</div>

		<article class="clear">
			<h2 class="v-hidden">쇼윈도 상품</h2>
			<aside class="filter-wrap">
				<h2><span>FILTER</span> <button type="reset" class="reset"><span><i class="icon-reset"></i> 초기화</span></button></h2>
				<section class="type-box">
					<h3>TYPE</h3>
					<ul class="filter-checkbox">
						<li>
							<div class="checkbox small">
								<input type="checkbox" id="type_cate01" checked>
								<label for="type_cate01">재킷</label>
							</div>
						</li>
						<li>
							<div class="checkbox small">
								<input type="checkbox" id="type_cate02" >
								<label for="type_cate02">점퍼</label>
							</div>
						</li>
						<li>
							<div class="checkbox small">
								<input type="checkbox" id="type_cate03" >
								<label for="type_cate03">트렌치</label>
							</div>
						</li>
						<li>
							<div class="checkbox small">
								<input type="checkbox" id="type_cate04" >
								<label for="type_cate04">코트</label>
							</div>
						</li>
					</ul>
				</section>
				<section class="type-box">
					<h3>BRAND</h3>
					<ul class="filter-checkbox">
						<li>
							<div class="checkbox small">
								<input type="checkbox" id="type_brand01" checked>
								<label for="type_brand01">BESTI BELLI</label>
							</div>
						</li>
						<li>
							<div class="checkbox small">
								<input type="checkbox" id="type_brand02" >
								<label for="type_brand02">VIKI</label>
							</div>
						</li>
						<li>
							<div class="checkbox small">
								<input type="checkbox" id="type_brand03" >
								<label for="type_brand03">SI</label>
							</div>
						</li>
						<li>
							<div class="checkbox small">
								<input type="checkbox" id="type_brand04" >
								<label for="type_brand04">ISABEY</label>
							</div>
						</li>
						<li>
							<div class="checkbox small">
								<input type="checkbox" id="type_brand05" >
								<label for="type_brand05">SIEG</label>
							</div>
						</li>
						<li>
							<div class="checkbox small">
								<input type="checkbox" id="type_brand06" >
								<label for="type_brand06">SIEG FAHRENHEIT</label>
							</div>
						</li>
						<li>
							<div class="checkbox small">
								<input type="checkbox" id="type_brand07" >
								<label for="type_brand07">VANHART DI ALBAZAR</label>
							</div>
						</li>
					</ul>
				</section>
				<section class="type-box">
					<h3>COLOR</h3>
					<div class="filter-color">
						<label class="chip-black"><input type="checkbox" value="black" checked><span></span></label>
						<label class="chip-darkGrey"><input type="checkbox" value="dark_grey"><span></span></label>
						<label class="chip-lightGrey"><input type="checkbox" value="light_grey"><span></span></label>
						<label class="chip-peach"><input type="checkbox" value="peach"><span></span></label>
						<label class="chip-magenta"><input type="checkbox" value="magenta"><span></span></label>
						<label class="chip-red"><input type="checkbox" value="red"><span></span></label>
						<label class="chip-orange"><input type="checkbox" value="orange"><span></span></label>
						<label class="chip-yellow"><input type="checkbox" value="yellow"><span></span></label>
						<label class="chip-yellowGreen"><input type="checkbox" value="yellow_green"><span></span></label>
						<label class="chip-green"><input type="checkbox" value="green"><span></span></label>
						<label class="chip-blue"><input type="checkbox" value="blue"><span></span></label>
						<label class="chip-ultramarineBlue"><input type="checkbox" value="ultramarine_blue"><span></span></label>
						<label class="chip-violet"><input type="checkbox" value="violet"><span></span></label>
						<label class="chip-strawberry"><input type="checkbox" value="strawberry"><span></span></label>
						<label class="chip-pink"><input type="checkbox" value="pink"><span></span></label>
						<label class="chip-beige with-border"><input type="checkbox" value="beige" checked><span></span></label>
						<label class="chip-white with-border"><input type="checkbox" value="white"><span></span></label>
					</div>
				</section>
				<section class="type-box size">
					<h3>SIZE</h3>
					<div class="filter-size">
						<div class="size-check">
							<input type="checkbox" id="type_size01" checked>
							<label for="type_size01">XS</label>
						</div>
						<div class="size-check">
							<input type="checkbox" id="type_size02" >
							<label for="type_size02">S</label>
						</div>
						<div class="size-check">
							<input type="checkbox" id="type_size03" >
							<label for="type_size03">M</label>
						</div>
						<div class="size-check">
							<input type="checkbox" id="type_size04" >
							<label for="type_size04">L</label>
						</div>
						<div class="size-check">
							<input type="checkbox" id="type_size06" >
							<label for="type_size06">XL</label>
						</div>
						<div class="size-check">
							<input type="checkbox" id="type_size07" >
							<label for="type_size07">XXL</label>
						</div>
						<div class="size-check">
							<input type="checkbox" id="type_size08" >
							<label for="type_size08">XXXL</label>
						</div>
					</div>
				</section>
				<section class="type-box price">
					<h3>PRICE</h3>
					<div class="filter-price">
						<div id="filter-priceRange"></div>
						<div class="range-box clear">
							<div class="fl-l"><input type="text" class="" id="price-start"></div>
							<div class="fl-l ml-10"><input type="text" class="" id="price-end"></div>
						</div>
					</div>
				</section>
			</aside><!-- //.filter-wrap -->
			<div class="goods-list-wrap show-window" data-ui="TabMenu">
				<div class="tabs"> 
					<button type="button" data-content="menu" class="active"><span>여성</span></button>
					<button type="button" data-content="menu"><span>남성</span></button>
					<button type="button" data-content="menu"><span>패션잡화</span></button>
				</div>

				<div class="goods-sort mt-40 clear">
					<div class="total-ea"><strong>235</strong> items</div>
					<div class="view-ea ">
						<label>View</label>
						<button class="btn-line on" type="button"><span>20</span></button>
						<button class="btn-line" type="button"><span>40</span></button>
						<button class="btn-line" type="button"><span>200</span></button>
					</div>
					<div class="sort-by ">
						<label for="sort_by10">Sort by</label>
						<div class="select">
							<select id="sort_by10">
								<option>신상품순</option>
								<option>높은가격순</option>
							</select>
						</div>
					</div>
				</div><!-- //.goods-sort -->

				<div class="showWindow-category active clear" data-content="content">
					<div class="list">
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show02.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show03.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
					</div>
					<div class="list">
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show01.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show02.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
					</div>
					<div class="list">
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show02.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show03.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
					</div>
					<div class="list">
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show01.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
									<p>후회 없어욤</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show02.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
					</div>
				</div>

				<div class="showWindow-category clear" data-content="content">
					<div class="list">
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show03.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
					</div>
					<div class="list">
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show02.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
					</div>
					<div class="list">
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show03.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
					</div>
					<div class="list">
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show01.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
									<p>후회 없어욤</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
					</div>
				</div>

				<div class="showWindow-category clear" data-content="content">
					<div class="list">
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show01.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
					</div>
					<div class="list">
						<div class="showWindow-item">
							<div class="hd"><img src="../static/img/common/brand_logo_si.png" alt="브랜드 로고"><p class="brand-nm">강남역점</p></div>
							<div class="thumb-img">
								<a href=""><img src="../static/img/test/@thumb_show03.jpg" alt="상품 이미지"></a>
								<div class="price">\ 105,800</div>
								<span class="percent"><strong>11</strong>%</span>
							</div>
							<div class="comment">
								<div class="subject ellipsis">레이어드 티셔츠</div>
								<div class="content">
									<p>너무 이쁜 티셔츠 입니다.</p>
									<p>추운겨울에 입어도 너무좋은 상품입니다. 칼라별로 몇 개 더 구입해도 후회 없을 기회입니다. 사이즈는 정사이즈로 구입하세요.</p>
								</div>
								<button class="comment-open" type="button"></button>
							</div>
							<div class="like"><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></div>
						</div>
					</div>
				</div>
				
				<div class="list-paginate">
					<a href="#" class="prev-all"></a>
					<a href="#" class="prev"></a>
					<a href="#" class="number on">1</a>
					<a href="#" class="number">2</a>
					<a href="#" class="number">3</a>
					<a href="#" class="number">4</a>
					<a href="#" class="number">5</a>
					<a href="#" class="next on"></a>
					<a href="#" class="next-all on"></a>
				</div><!-- //.list-paginate -->
			</div><!-- //.goods-list-wrap -->
		</article>

	</div>
</div><!-- //#contents -->

<script type="text/javascript">
//가격 설정
$(function(){
	var price_range = document.getElementById('filter-priceRange');
	var inputStart = document.getElementById('price-start');
	var inputEnd = document.getElementById('price-end');
	var inputs = [inputStart, inputEnd];

	noUiSlider.create(price_range, {
		start: [ 50000, 150000 ],
		step: 10,
		connect: true,
		behaviour: 'drag',
		range: {
			'min': [   0 ],
			'max': [ 200000 ]
		},
		format: wNumb({
			decimals: 0,
			thousand: ',',
			prefix: '￦ '
		})
	});

	price_range.noUiSlider.on('update', function( values, handle ) {
		inputs[handle].value = values[handle];
	});

	function setSliderHandle(i, value) {
		var r = [null,null];
		r[i] = value;
		price_range.noUiSlider.set(r);
	}

	inputs.forEach(function(input, handle) {

	input.addEventListener('change', function(){
		setSliderHandle(handle, this.value);
	});

	input.addEventListener('keydown', function( e ) {

		var values = price_range.noUiSlider.get();
		var value = Number(values[handle]);
		var steps = price_range.noUiSlider.steps();
		var step = steps[handle];
		var position;

		switch ( e.which ) {
			case 13:
				setSliderHandle(handle, this.value);
				break;
			}
		});
	});

})
</script>

<?php include_once('../outline/footer.php') ?>
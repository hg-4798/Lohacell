<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="goodsList-page">
		
		<div class="goods-breadcrumb">
			<a href="#">여성</a>
			<a href="#">아우터</a>
			<a href="#" class="active">코트</a>
		</div>

		<article class="clear">
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
			<div class="goods-list-wrap">
				<div class="goods-sort clear">
					<div class="total-ea"><strong>235</strong> items</div>
					<div class="type">
						<button type="button" id="type-half"><span>2개씩 보기</span></button>
						<button type="button" class="active" id="type-quarter"><span>4개씩 보기</span></button>
					</div>
					<div class="view-ea ">
						<label>View</label>
						<button class="btn-line on" type="button"><span>20</span></button>
						<button class="btn-line" type="button"><span>40</span></button>
						<button class="btn-line" type="button"><span>200</span></button>
					</div>
					<div class="sort-by ">
						<label for="sort_by11">Sort by</label>
						<div class="select">
							<select id="sort_by11">
								<option>신상품순</option>
								<option>높은가격순</option>
							</select>
						</div>
					</div>
				</div><!-- //.goods-sort -->
				<ul class="goods-list four clear">
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="/jayjun/web/goods/goods_view.php"><img src="../static/img/test/@goods_thumb690_01.jpg" alt="상품 썸네일"></a>
								<div class="layer">
									<div class="btn">
										<button type="button" class="btn-preview"><span><i class="icon-cart">장바구니</i></span></button>
										<button type="button"><span><i class="icon-like">좋아요</i></span><span>11</span></button> <!-- [D] 좋아요 선택시 .on 처리 -->
									</div>
									<div class="opt">
										<span>55</span>
										<span>66</span>
										<span class="disabled">77</span>
									</div>
								</div>
							</div><!-- //.thumb-img -->
							<div class="price-box">
								<div class="brand-nm">BESTI BELLI</div>
								<div class="goods-nm">솔리드 심플 벨티드 자켓</div>
								<div class="price"><del>\205,800</del>\105,800</div>
								<div class="color-chip">
									<span class="chip-brown"></span>
									<span class="chip-grey"></span>
								</div>
								<div class="goods-icon">
									<img src="../static/img/icon/icon_sale.gif" alt="SALE">
									<img src="../static/img/icon/icon_best.gif" alt="BEST">
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb500_02.jpg" alt="상품 썸네일"></a>
								<div class="layer">
									<div class="btn">
										<button type="button" class="btn-preview"><span><i class="icon-cart">장바구니</i></span></button>
										<button type="button"><span><i class="icon-like on">좋아요</i></span><span>11</span></button> <!-- [D] 좋아요 선택시 .on 처리 -->
									</div>
									<div class="opt">
										<span>55</span>
										<span>66</span>
										<span>77</span>
									</div>
								</div>
							</div><!-- //.thumb-img -->
							<div class="price-box">
								<div class="brand-nm">BESTI BELLI</div>
								<div class="goods-nm">솔리드 심플 벨티드 자켓</div>
								<div class="price">\105,800</div>
								<div class="color-chip">
									<span class="chip-brown"></span>
									<span class="chip-grey"></span>
								</div>
								<div class="goods-icon"></div>
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb500_03.jpg" alt="상품 썸네일"></a>
								<div class="layer">
									<div class="btn">
										<button type="button" class="btn-preview"><span><i class="icon-cart">장바구니</i></span></button>
										<button type="button"><span><i class="icon-like">좋아요</i></span><span>11</span></button> <!-- [D] 좋아요 선택시 .on 처리 -->
									</div>
									<div class="opt">
										<span>55</span>
										<span>66</span>
										<span>77</span>
									</div>
								</div>
							</div><!-- //.thumb-img -->
							<div class="price-box">
								<div class="brand-nm">BESTI BELLI</div>
								<div class="goods-nm">솔리드 심플 벨티드 자켓</div>
								<div class="price">\105,800</div>
								<div class="color-chip">
									<span class="chip-brown"></span>
									<span class="chip-grey"></span>
								</div>
								<div class="goods-icon">
									<img src="../static/img/icon/icon_sale.gif" alt="SALE">
									<img src="../static/img/icon/icon_best.gif" alt="BEST">
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb500_04.jpg" alt="상품 썸네일"></a>
								<div class="layer">
									<div class="btn">
										<button type="button" class="btn-preview"><span><i class="icon-cart">장바구니</i></span></button>
										<button type="button"><span><i class="icon-like on">좋아요</i></span><span>11</span></button> <!-- [D] 좋아요 선택시 .on 처리 -->
									</div>
									<div class="opt">
										<span>55</span>
										<span>66</span>
										<span>77</span>
									</div>
								</div>
							</div><!-- //.thumb-img -->
							<div class="price-box">
								<div class="brand-nm">BESTI BELLI</div>
								<div class="goods-nm">솔리드 심플 벨티드 자켓</div>
								<div class="price"><del>\205,800</del>\105,800</div>
								<div class="color-chip">
									<span class="chip-brown"></span>
									<span class="chip-grey"></span>
								</div>
								<div class="goods-icon"></div>
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb500_05.jpg" alt="상품 썸네일"></a>
								<div class="layer">
									<div class="btn">
										<button type="button" class="btn-preview"><span><i class="icon-cart">장바구니</i></span></button>
										<button type="button"><span><i class="icon-like">좋아요</i></span><span>11</span></button> <!-- [D] 좋아요 선택시 .on 처리 -->
									</div>
									<div class="opt">
										<span>55</span>
										<span>66</span>
										<span>77</span>
									</div>
								</div>
							</div><!-- //.thumb-img -->
							<div class="price-box">
								<div class="brand-nm">BESTI BELLI</div>
								<div class="goods-nm">솔리드 심플 벨티드 자켓</div>
								<div class="price">\105,800</div>
								<div class="color-chip">
									<span class="chip-brown"></span>
									<span class="chip-grey"></span>
								</div>
								<div class="goods-icon"></div>
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb500_06.jpg" alt="상품 썸네일"></a>
								<div class="layer">
									<div class="btn">
										<button type="button" class="btn-preview"><span><i class="icon-cart">장바구니</i></span></button>
										<button type="button"><span><i class="icon-like">좋아요</i></span><span>11</span></button> <!-- [D] 좋아요 선택시 .on 처리 -->
									</div>
									<div class="opt">
										<span>55</span>
										<span>66</span>
										<span>77</span>
									</div>
								</div>
							</div><!-- //.thumb-img -->
							<div class="price-box">
								<div class="brand-nm">BESTI BELLI</div>
								<div class="goods-nm">솔리드 심플 벨티드 자켓</div>
								<div class="price">\105,800</div>
								<div class="color-chip">
									<span class="chip-brown"></span>
									<span class="chip-grey"></span>
								</div>
								<div class="goods-icon">
									<img src="../static/img/icon/icon_sale.gif" alt="SALE">
									<img src="../static/img/icon/icon_best.gif" alt="BEST">
								</div>
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb500_07.jpg" alt="상품 썸네일"></a>
								<div class="layer">
									<div class="btn">
										<button type="button" class="btn-preview"><span><i class="icon-cart">장바구니</i></span></button>
										<button type="button"><span><i class="icon-like">좋아요</i></span><span>11</span></button> <!-- [D] 좋아요 선택시 .on 처리 -->
									</div>
									<div class="opt">
										<span>55</span>
										<span>66</span>
										<span>77</span>
									</div>
								</div>
							</div><!-- //.thumb-img -->
							<div class="price-box">
								<div class="brand-nm">BESTI BELLI</div>
								<div class="goods-nm">솔리드 심플 벨티드 자켓</div>
								<div class="price"><del>\205,800</del>\105,800</div>
								<div class="color-chip">
									<span class="chip-brown"></span>
									<span class="chip-grey"></span>
								</div>
								<div class="goods-icon">
									<img src="../static/img/icon/icon_sale.gif" alt="SALE">
									<img src="../static/img/icon/icon_best.gif" alt="BEST">
								</div>
								<div class="goods-icon"></div>
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb500_01.jpg" alt="상품 썸네일"></a>
								<div class="layer">
									<div class="btn">
										<button type="button" class="btn-preview"><span><i class="icon-cart">장바구니</i></span></button>
										<button type="button"><span><i class="icon-like">좋아요</i></span><span>11</span></button> <!-- [D] 좋아요 선택시 .on 처리 -->
									</div>
									<div class="opt">
										<span>55</span>
										<span>66</span>
										<span>77</span>
									</div>
								</div>
							</div><!-- //.thumb-img -->
							<div class="price-box">
								<div class="brand-nm">BESTI BELLI</div>
								<div class="goods-nm">솔리드 심플 벨티드 자켓</div>
								<div class="price">\105,800</div>
								<div class="color-chip">
									<span class="chip-brown"></span>
									<span class="chip-grey"></span>
								</div>
								<div class="goods-icon"></div>
							</div>
						</div>
					</li>
				</ul><!-- //.goods-list -->
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
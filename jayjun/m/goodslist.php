<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	<!-- 필터 레이어 팝업 -->
	<section class="filter_pop">
		<div class="filter">
			<h3 class="title">FILTER<button type="button" class="btn_close">닫기</button></h3>
			<ul class="filter_menu">
				<li class="on">
					<button type="button" class="filter_name">BRAND</button>
					<div class="filter_con brand">
						<label>
							<input type="checkbox" class="check_def" checked>
							<span>BESTI BELLI</span>
						</label>
						<label>
							<input type="checkbox" class="check_def">
							<span>VIKI</span>
						</label>
						<label>
							<input type="checkbox" class="check_def">
							<span>SI</span>
						</label>
						<label>
							<input type="checkbox" class="check_def">
							<span>ISABEY</span>
						</label>
						<label>
							<input type="checkbox" class="check_def">
							<span>SIEG</span>
						</label>
						<label>
							<input type="checkbox" class="check_def">
							<span>sieg fahREnheit</span>
						</label>
						<label>
							<input type="checkbox" class="check_def">
							<span>vanhart di albazar</span>
						</label>
					</div><!-- //.filter_con.brand -->
				</li>
				<li class="on">
					<button type="button" class="filter_name">COLOR</button>
					<div class="filter_con color">
						<label class="colorchip chip-black"><input type="checkbox" value="black" checked><span></span></label>
						<label class="colorchip chip-darkGrey"><input type="checkbox" value="dark_grey"><span></span></label>
						<label class="colorchip chip-lightGrey"><input type="checkbox" value="light_grey"><span></span></label>
						<label class="colorchip chip-peach"><input type="checkbox" value="peach"><span></span></label>
						<label class="colorchip chip-magenta"><input type="checkbox" value="magenta"><span></span></label>
						<label class="colorchip chip-red"><input type="checkbox" value="red"><span></span></label>
						<label class="colorchip chip-orange"><input type="checkbox" value="orange"><span></span></label>
						<label class="colorchip chip-yellow"><input type="checkbox" value="yellow"><span></span></label>
						<label class="colorchip chip-yellowGreen"><input type="checkbox" value="yellow_green"><span></span></label>
						<label class="colorchip chip-green"><input type="checkbox" value="green"><span></span></label>
						<label class="colorchip chip-blue"><input type="checkbox" value="blue"><span></span></label>
						<label class="colorchip chip-ultramarineBlue"><input type="checkbox" value="ultramarine_blue"><span></span></label>
						<label class="colorchip chip-violet"><input type="checkbox" value="violet"><span></span></label>
						<label class="colorchip chip-strawberry"><input type="checkbox" value="strawberry"><span></span></label>
						<label class="colorchip chip-pink"><input type="checkbox" value="pink"><span></span></label>
						<label class="colorchip chip-beige light-color"><input type="checkbox" value="beige" checked><span></span></label><!-- [D] 밝은컬러에 .light-color 클래스 추가(체크이미지 관련) -->
						<label class="colorchip chip-white light-color"><input type="checkbox" value="white"><span></span></label>
					</div><!-- //.filter_con.color -->
				</li>
				<li class="on">
					<button type="button" class="filter_name">SIZE</button>
					<div class="filter_con size">
						<label>
							<input type="checkbox" checked>
							<span>XS</span>
						</label>
						<label>
							<input type="checkbox">
							<span>S</span>
						</label>
						<label>
							<input type="checkbox">
							<span>M</span>
						</label>
						<label>
							<input type="checkbox">
							<span>L</span>
						</label>
						<label>
							<input type="checkbox">
							<span>XL</span>
						</label>
						<label>
							<input type="checkbox">
							<span>XXL</span>
						</label>
					</div><!-- //.filter_con.size -->
				</li>
				<li class="on">
					<button type="button" class="filter_name">PRICE</button>
					<div class="filter_con price">
						<div id="filter-priceRange"></div>
						<div class="range-box clear">
							<div class="fl-l"><input type="text" id="price-start"></div>
							<div class="fl-r"><input type="text" id="price-end"></div>
						</div>
					</div><!-- //.filter_con.price -->
				</li>
			</ul><!-- //.filter_menu -->
			<div class="btn_area">
				<ul class="ea2">
					<li><a href="javascript:;" class="btn-basic h-large">초기화</a></li>
					<li><a href="javascript:;" class="btn-point h-large">적용</a></li>
				</ul>
			</div>
		</div><!-- //.filter -->
	</section><!-- //.filter_pop -->
	<!-- //필터 레이어 팝업 -->

	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>여성</span>
		</h2>
		<div class="breadcrumb">
			<ul class="depth2">
				<li>
					<a href="javascript:;">탑</a>
					<ul class="depth3">
						<li><a href="#">전체</a></li>
						<li><a href="#">탑</a></li>
						<li><a href="#">팬츠</a></li>
						<li><a href="#">아우터</a></li>
					</ul>
				</li>
				<li>
					<a href="javascript:;">전체</a>
					<ul class="depth3">
						<li><a href="#">전체</a></li>
						<li><a href="#">티셔츠</a></li>
						<li><a href="#">블라우스</a></li>
						<li><a href="#">셔츠</a></li>
						<li><a href="#">니트/가디건</a></li>
					</ul>
				</li>
			</ul>
			<div class="dimm_bg"></div>
		</div>
	</section><!-- //.page_local -->

	<section class="listpage">
		<div class="list_sorting">
			<div class="item_num">235 Items</div>
			<div class="condition">
				<a href="javascript:;" class="btn_filter">FILTER+</a>
				<select class="select_def ml-15">
					<option value="">신상품순</option>
					<option value="">인기순</option>
					<option value="">높은가격순</option>
					<option value="">낮은가격순</option>
				</select>
			</div>
		</div><!-- //.list_sorting -->

		<ul class="goodslist"><!-- [D] 20개 페이징 -->
			<li>
				<a href="#">
					<figure>
						<div class="img"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></div>
						<figcaption>
							<p class="brand">BESTI BELLI</p>
							<p class="name">솔리드 심플 벨티트 자켓 </p>
							<p class="price">￦ 105,800</p>
							<div class="color">
								<span class="colorchip chip-brown">brown</span>
								<span class="colorchip chip-grey">grey</span>
							</div>
							<div class="tagset">
								<span class="tag"><img src="static/img/test/tag_sale.gif" alt="sale"></span>
								<span class="tag"><img src="static/img/test/tag_best.gif" alt="best"></span>
							</div>
						</figcaption>
					</figure>
				</a>
			</li>
			<li>
				<a href="#">
					<figure>
						<div class="img"><img src="static/img/test/@goodslist_02.jpg" alt="상품 이미지"></div>
						<figcaption>
							<p class="brand">BESTI BELLI</p>
							<p class="name">솔리드 심플 벨티트 자켓</p>
							<p class="price"><del>￦ 1,005,800</del>￦ 315,900</p><!-- [D] 할인가인 경우 -->
							<div class="color">
								<span class="colorchip chip-babyBlue">babyBlue</span>
								<span class="colorchip chip-grey">grey</span>
							</div>
							<div class="tagset">
								<span class="tag"><img src="static/img/test/tag_new.gif" alt="new"></span>
							</div>
						</figcaption>
					</figure>
				</a>
			</li>
			<li>
				<a href="#">
					<figure>
						<div class="img"><img src="static/img/test/@goodslist_09.jpg" alt="상품 이미지"></div>
						<figcaption>
							<p class="brand">BESTI BELLI</p>
							<p class="name">솔리드 심플 벨티트 자켓</p>
							<p class="price">￦ 105,800</p>
							<div class="color">
								<span class="colorchip chip-babyPink">babyPink</span>
								<span class="colorchip chip-grey">grey</span>
							</div>
							<div class="tagset">
								<span class="tag"><img src="static/img/test/tag_sale.gif" alt="sale"></span>
								<span class="tag"><img src="static/img/test/tag_best.gif" alt="best"></span>
							</div>
						</figcaption>
					</figure>
				</a>
			</li>
			<li>
				<a href="#">
					<figure>
						<div class="img"><img src="static/img/test/@goodslist_10.jpg" alt="상품 이미지"></div>
						<figcaption>
							<p class="brand">BESTI BELLI</p>
							<p class="name">솔리드 심플 벨티트 자켓 라쿤털 후드</p>
							<p class="price">￦ 315,900</p>
							<div class="color">
								<span class="colorchip chip-brown">brown</span>
								<span class="colorchip chip-grey">grey</span>
							</div>
							<div class="tagset">
								<span class="tag"><img src="static/img/test/tag_new.gif" alt="new"></span>
							</div>
						</figcaption>
					</figure>
				</a>
			</li>
			<li>
				<a href="#">
					<figure>
						<div class="img"><img src="static/img/test/@goodslist_11.jpg" alt="상품 이미지"></div>
						<figcaption>
							<p class="brand">BESTI BELLI</p>
							<p class="name">솔리드 심플 벨티트 자켓</p>
							<p class="price">￦ 105,800</p>
							<div class="color">
								<span class="colorchip chip-brown">brown</span>
								<span class="colorchip chip-grey">grey</span>
							</div>
							<div class="tagset">
								<span class="tag"><img src="static/img/test/tag_sale.gif" alt="sale"></span>
								<span class="tag"><img src="static/img/test/tag_best.gif" alt="best"></span>
							</div>
						</figcaption>
					</figure>
				</a>
			</li>
			<li>
				<a href="#">
					<figure>
						<div class="img"><img src="static/img/test/@goodslist_12.jpg" alt="상품 이미지"></div>
						<figcaption>
							<p class="brand">BESTI BELLI</p>
							<p class="name">솔리드 심플 벨티트 자켓</p>
							<p class="price">￦ 315,900</p>
							<div class="color">
								<span class="colorchip chip-babyBlue">babyBlue</span>
								<span class="colorchip chip-grey">grey</span>
							</div>
							<div class="tagset">
								<span class="tag"><img src="static/img/test/tag_new.gif" alt="new"></span>
							</div>
						</figcaption>
					</figure>
				</a>
			</li>
			<li>
				<a href="#">
					<figure>
						<div class="img"><img src="static/img/common/noimg.gif" alt="이미지가 없습니다"></div><!-- [D] 이미지가 없는 경우 -->
						<figcaption>
							<p class="brand">BESTI BELLI</p>
							<p class="name">솔리드 심플 벨티트 자켓</p>
							<p class="price">￦ 105,800</p>
							<div class="color">
								<span class="colorchip chip-black">black</span>
								<span class="colorchip chip-grey">grey</span>
							</div>
							<div class="tagset">
								<span class="tag"><img src="static/img/test/tag_sale.gif" alt="sale"></span>
								<span class="tag"><img src="static/img/test/tag_best.gif" alt="best"></span>
							</div>
						</figcaption>
					</figure>
				</a>
			</li>
			<li>
				<a href="#">
					<figure>
						<div class="img"><img src="static/img/test/@goodslist_14.jpg" alt="상품 이미지"></div>
						<figcaption>
							<p class="brand">BESTI BELLI</p>
							<p class="name">솔리드 심플 벨티트 자켓</p>
							<p class="price">￦ 315,900</p>
							<div class="color">
								<span class="colorchip chip-deepWine">deepWine</span>
								<span class="colorchip chip-grey">grey</span>
							</div>
							<div class="tagset">
								<span class="tag"><img src="static/img/test/tag_new.gif" alt="new"></span>
							</div>
						</figcaption>
					</figure>
				</a>
			</li>
			<li>
				<a href="#">
					<figure>
						<div class="img"><img src="static/img/test/@goodslist_15.jpg" alt="상품 이미지"></div>
						<figcaption>
							<p class="brand">BESTI BELLI</p>
							<p class="name">솔리드 심플 벨티트 자켓</p>
							<p class="price">￦ 105,800</p>
							<div class="color">
								<span class="colorchip chip-black">black</span>
								<span class="colorchip chip-grey">grey</span>
							</div>
							<div class="tagset">
								<span class="tag"><img src="static/img/test/tag_sale.gif" alt="sale"></span>
								<span class="tag"><img src="static/img/test/tag_best.gif" alt="best"></span>
							</div>
						</figcaption>
					</figure>
				</a>
			</li>
			<li>
				<a href="#">
					<figure>
						<div class="img"><img src="static/img/test/@goodslist_16.jpg" alt="상품 이미지"></div>
						<figcaption>
							<p class="brand">BESTI BELLI</p>
							<p class="name">솔리드 심플 벨티트 자켓</p>
							<p class="price">￦ 315,900</p>
							<div class="color">
								<span class="colorchip chip-strawberry">strawberry</span>
								<span class="colorchip chip-beige">beige</span>
							</div>
							<div class="tagset">
								<span class="tag"><img src="static/img/test/tag_new.gif" alt="new"></span>
							</div>
						</figcaption>
					</figure>
				</a>
			</li>
		</ul><!-- //.goodslist -->

		<div class="list-paginate mt-15">
			<a href="#" class="prev-all disabled">처음</a><!-- [D] 버튼 비활성인 경우 .disabled 클래스 추가 -->
			<a href="#" class="prev disabled">이전</a>
			<a href="#" class="on">1</a>
			<a href="#">2</a>
			<a href="#">3</a>
			<a href="#">4</a>
			<a href="#">5</a>
			<a href="#">6</a>
			<a href="#" class="next">다음</a>
			<a href="#" class="next-all">끝</a>
		</div>
	</section><!-- //.listpage -->

</main>
<!-- //내용 -->

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

<?php
include_once('outline/footer_m.php');
?>
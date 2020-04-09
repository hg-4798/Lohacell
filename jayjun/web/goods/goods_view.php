<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="goodsView-page">

		<article class="goods-view-wrap">
			<h2 class="fz-0">상품 상세정보 제공</h2>

			<div class="goods-info-area clear">
				<div class="thumb-box">
					<div class="big-thumb" id="thumb-zoomView">
						<ul class="thumbList-big">
							<li><img src="../static/img/test/@goods_thumb690_01.jpg" alt="상품 대표 썸네일"></li>
							<li><img src="../static/img/test/@goods_thumb690_02.jpg" alt="상품 대표 썸네일"></li>
							<li><img src="../static/img/test/@goods_thumb690_03.jpg" alt="상품 대표 썸네일"></li>
							<li><img src="../static/img/test/@goods_thumb690_04.jpg" alt="상품 대표 썸네일"></li>
							<li><img src="../static/img/test/@goods_thumb690_05.jpg" alt="상품 대표 썸네일"></li>
							<li><img src="../static/img/test/@goods_thumb690_06.jpg" alt="상품 대표 썸네일"></li>
						</ul>
					</div>
					<ul class="thumbList-small clear">
						<li><a data-slide-index="0"><img src="../static/img/test/@goods_thumb690_01.jpg" alt="상품 대표 썸네일"></a></li>
						<li><a data-slide-index="1"><img src="../static/img/test/@goods_thumb690_02.jpg" alt="상품 대표 썸네일"></a></li>
						<li><a data-slide-index="2"><img src="../static/img/test/@goods_thumb690_03.jpg" alt="상품 대표 썸네일"></a></li>
						<li><a data-slide-index="3"><img src="../static/img/test/@goods_thumb690_04.jpg" alt="상품 대표 썸네일"></a></li>
						<li><a data-slide-index="4"><img src="../static/img/test/@goods_thumb690_05.jpg" alt="상품 대표 썸네일"></a></li>
						<li><a data-slide-index="5"><img src="../static/img/test/@goods_thumb690_06.jpg" alt="상품 대표 썸네일"></a></li>
					</ul>
				</div><!-- //.thumb-box -->
				<div class="specification">
					<section class="box-intro">
						<h2>브랜드,상품명,금액,간략소개</h2>
						<p class="brand-nm">VIKI</p>
						<p class="goods-nm">시프트 플라운스</p>
						<p class="goods-code">(TLOBY2535)</p>
						<div class="price">
							<label>판매가</label><strong>\156,800</strong><del>\196,000</del>
							<div class="discount"><span>20</span>% <i class="icon-dc-arrow">할인</i></div>
						</div>
						<div class="price staff">
							<label>임직원가</label><strong class="point-color">\156,800</strong>
						</div>
						<div class="summarize-ment">
							<p>깔끔한 디자인의 원피스입니다.</p>
							<p>두툼한 소재로 만들어 초가을까지 입으실 수 있습니다.</p>
							<p>178CM 마네킹이 66사이즈를 착용하였습니다.</p>
						</div>
					</section><!-- //.box-intro -->
					<section class="box-summary">
						<h2>상품의 포인트, 할인정보, 배송비 정보</h2>
						<ul class="goods-summaryList">
							<li>
								<label>포인트 적립</label>
								<div>10,000 P (5%)</div>
							</li>
							<li>
								<label>할인정보</label>
								<div class="coupon-down">
									<div class="btn-line"><span>쿠폰 다운로드<i class="icon-download"></i></span></div>
									<ul class="list">
										<li>
											<p>20% 할인쿠폰</p>
											<button type="button" class="btn-line"><span>쿠폰 다운로드<i class="icon-download"></i></span></button>
										</li>
										<li>
											<p>10% 할인쿠폰</p>
											<button type="button" class="btn-line"><span>쿠폰 다운로드<i class="icon-download"></i></span></button>
										</li>
										<li>
											<p>가입축하 전상품 10% 할인쿠폰</p>
											<button type="button" class="btn-line"><span>쿠폰 다운로드<i class="icon-download"></i></span></button>
										</li>
									</ul>
								</div>
							</li>
							<li>
								<label>시즌정보</label>
								<div>2016SS</div>
							</li>
							<li>
								<label>배송비</label>
								<div>
									<p class="delivery-ment">30,000원 이상 무료배송 </p>
									<div class="question-btn ml-5">
										<i class="icon-question">무료배송기준 설명</i>
										<div class="comment">
											<dl>
												<dt>배송비 안내</dt>
												<dd><strong>택배발송:</strong> 30,000원 이상 결제시 무료배송</dd>
												<dd><strong>당일수령:</strong> 거리별 추가 배송비 발생</dd>
												<dd><strong>매장픽업:</strong> 배송비 발생하지 않음</dd>
											</dl>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</section><!-- //.box-summary -->
					<section class="box-opt">
						<h2>상품의 색상,사이즈,수량</h2>
						<div class="goods-colorChoice"><!-- [D] 상세페이지 로딩시 해당 색상은 input 태그 checked 필수 -->
							<label class="chip-black"><input type="radio" name="color_choice" value="BLACK" checked><span>BLACK</span></label> 
							<label class="chip-beige"><input type="radio" name="color_choice" value="BEIGE"><span>BEIGE</span></label>
							<label class="chip-white"><input type="radio" name="color_choice" value="WHITE"><span>WHITE</span></label>
							<label class="chip-pink"><input type="radio" name="color_choice" value="PINK"><span>PINK</span></label>
						</div>
						<div class="opt-size-wrap">
							<div class="opt-size">
								<div><input type="radio" name="optSize" id="size44" value="44"><label for="size44">44</label></div>
								<div><input type="radio" name="optSize" id="size55" value="55" disabled><label for="size55">55</label></div>
								<div><input type="radio" name="optSize" id="size66" value="66" checked><label for="size66">66</label></div>
								<div><input type="radio" name="optSize" id="size77" value="77"><label for="size77">77</label></div>
							</div>
							<a href="#" class="btn-size-guide">사이즈 가이드</a>
						</div>
						<div class="quantity mt-10">
							<input type="text" value="1" readonly>
							<button class="plus"></button>
							<button class="minus"></button>
						</div>
					</section><!-- //.box-opt -->
					<section class="box-delivery">
						<h2>상품수령방법 선택 - 택배발송,당일수령,매장픽업</h2>
						<div class="delivery-type mt-20" data-ui="TabMenu">
							<div class="type">
								<div class="radio" >
									<input type="radio" name="select_deliveryType" id="deliver_typeA" checked>
									<label for="deliver_typeA">택배발송</label>
								</div>
								<div class="radio">
									<input type="radio" name="select_deliveryType" id="deliver_typeB">
									<label for="deliver_typeB">당일수령</label>
								</div>
								<div class="radio">
									<input type="radio" name="select_deliveryType" id="deliver_typeC">
									<label for="deliver_typeC">매장픽업</label>
								</div>
								<div class="question-btn">
									<i class="icon-question">타이틀</i>
									<div class="comment">
										<dl>
											<dt>배송방법 안내</dt>
											<dd><strong>택배발송:</strong> 택배로 발송하는 기본 배송 서비스</dd>
											<dd><strong>당일수령:</strong> 당일수령이 가능한 라이더 배송 서비스</dd>
											<dd><strong>매장픽업:</strong> 원하는 날짜, 원하는 매장에서 상품을 <br><span style="padding-left:54px"></span>받아가는 맞춤형 배송 서비스</dd>
										</dl>
									</div>
								</div>
							</div><!-- //.type -->
							<div class="store-select">강남직영점(매장) 
								<!-- <button class="btn-basic" type="button" id="btn-shopToday"><span>매장선택</span></button> --> <!-- [D] 당일수령 선택시 -->
								<button class="btn-basic" type="button" id="btn-shopPickup"><span>매장선택</span></button> <!-- [D] 매장픽업 선택시 -->
							</div><!-- [D] 택배발송시엔 감춤 -->
						</div>
					</section><!-- //.box-delivery -->
					<section class="box-price">
						<h2>총 금액확인, 구매버튼, 장바구니버튼, 좋아요버튼</h2>
						<div class="total clear"><span>총 합계</span><strong>\156,800</strong></div>
						<div class="buy-btn clear">
							<!-- [D] 임직원가 수정(2017-04-24) -->
							<a href="#" class="btn-point w100-per">바로구매</a><!-- [D] 기본 노출 -->
							<ul class="clear"><!-- [D] 임직원 구매인 경우 노출 -->
								<li><a href="#" class="btn-basic w100-per">바로구매</a></li>
								<li><a href="#" class="btn-point w100-per">임직원 구매</a></li>
							</ul>
							<!-- //[D] 임직원가 수정(2017-04-24) -->
							<ul class="clear mt-10">
								<li><button class="btn-line" type="button"><span><i class="icon-cart mr-10"></i>장바구니</span></button></li>
								<!-- <li><button class="btn-line" type="button"><span><i class="icon-zoom mr-10"></i>상세보기</span></button></li> -->
								<li><button class="btn-line" type="button"><span><i class="icon-like mr-10"></i>좋아요 <span class="point-color">(432)</span></span></button></li><!-- [D] 좋아요 선택시 .on 클래스 추가 -->
							</ul>
						</div>
					</section><!-- //.box-price -->
					<ul class="layer-view-menu">
						<li><button type="button" id="btn-detailPop"><span>상품상세정보</span></button><i class="icon-crosshair"></i></li>
						<li><button type="button" id="btn-deliveryPop"><span>배송반품</span></button><i class="icon-crosshair"></i></li>
					</ul><!-- //.layer-view-menu -->
					<div class="board-share">
						<div class="board-btn">
							<button class="btn-line" type="button" id="btn-reviewList"><span>리뷰<span class="point-color">(30)</span></span></button>
							<button class="btn-line" type="button" id="btn-qnaList"><span>Q&amp;A<span class="point-color">(30)</span></span></button>
						</div>
						<div class="share">
							<button type="button"><span><i class="icon-share">상품 공유하기</i></span></button>
							<div class="links">
								<a href="#"><i class="icon-kas">카카오 스토리</i></a>
								<a href="#"><i class="icon-facebook-dark">페이스북</i></a>
								<a href="#"><i class="icon-twitter">트위터</i></a>
								<a href="#"><i class="icon-band">밴드</i></a>
								<a href="#"><i class="icon-link">링크</i></a>
							</div>
						</div>
					</div><!-- //.board-share -->
				</div><!-- //.goods-specification -->
			</div><!-- //.goods-info-area -->

			<div class="mds-choice">
				<h3 class="roof-title"><span>MD's CHOICE</span></h3>
				<ul class="goods-list four clear">
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb300_01.jpg" alt="상품 썸네일"></a>
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
								<div class="goods-nm">솔리드 심플 벨티드 자켓 롱롱한 길이의 자켓 멋지다</div>
								<div class="price">\105,800</div>
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb300_02.jpg" alt="상품 썸네일"></a>
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
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb300_03.jpg" alt="상품 썸네일"></a>
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
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb300_04.jpg" alt="상품 썸네일"></a>
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
								<div class="goods-nm">솔리드 심플 벨티드 자켓 롱롱한 길이의 자켓 멋지다</div>
								<div class="price">\105,800</div>
							</div>
						</div>
					</li>
				</ul>
			</div><!-- //.mds-choice -->
			<div class="category-best">
				<h3 class="roof-title"><span>CATEGORY BEST</span></h3>
				<ul class="goods-list four clear">
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb300_07.jpg" alt="상품 썸네일"></a>
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
								<div class="goods-nm">솔리드 심플 벨티드 자켓 롱롱한 길이의 자켓 멋지다</div>
								<div class="price">\105,800</div>
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb300_06.jpg" alt="상품 썸네일"></a>
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
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb300_02.jpg" alt="상품 썸네일"></a>
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
							</div>
						</div>
					</li>
					<li>
						<div class="goods-item">
							<div class="thumb-img">
								<a href="#"><img src="../static/img/test/@goods_thumb300_05.jpg" alt="상품 썸네일"></a>
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
								<div class="goods-nm">솔리드 심플 벨티드 자켓 롱롱한 길이의 자켓 멋지다</div>
								<div class="price">\105,800</div>
							</div>
						</div>
					</li>
				</ul>
			</div><!-- //.category-best -->

		</article><!-- //.goods-view-wrap -->

		<div class="goodsThumb-zoom inner-align ta-c hide">
			<button type="button" id="thumb-zoomClose"><span><i class="icon-close-small">닫기</i></span></button>
			<ul>
				<li><img src="../static/img/test/@goods_thumb900_01.jpg" alt="큰 썸네일"></li>
				<li><img src="../static/img/test/@goods_thumb900_02.jpg" alt="큰 썸네일"></li>
				<li><img src="../static/img/test/@goods_thumb900_03.jpg" alt="큰 썸네일"></li>
			</ul>
		</div><!-- //.goodsThumb-zoom -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
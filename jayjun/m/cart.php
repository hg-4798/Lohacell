<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
	<!-- 당일수령 팝업 -->
	<section class="pop_layer layer_select_store01">
		<div class="inner">
			<h3 class="title">
				매장선택
				<div class="wrap_bubble">
					<div class="btn_bubble"><button type="button" class="btn_help">?</button></div>
					<div class="pop_bubble">
						<div class="inner">
							<button type="button" class="btn_pop_close">닫기</button>
							<div class="container">
								<p class="tit_pop">당일수령 안내</p>
								<p class="list_txt">※ 당일수령은 주변 매장에 재고가 있어야 발송이 가능합니다.<br> 수령지를 입력하신 후 발송 가능 매장을 검색하세요.<br>(오후 4시전 주문시 당일수령 가능)</p>
							</div>
						</div>
					</div>
				</div><!-- //.wrap_bubble -->

				<button type="button" class="btn_close">닫기</button>
			</h3>
			<div class="select_store">
				<dl class="search_store">
					<dt>수령지 정보 입력</dt>
					<dd>
						<input type="text" class="w100-per" placeholder="주소검색">
						<input type="text" class="w100-per mt-5" placeholder="상세주소 입력">
						<a href="javascript:;" class="btn-point w100-per h-input mt-5">발송 가능 매장 찾기</a>
					</dd>
				</dl><!-- //.search_store -->

				<ul class="list_store mt-30">
					<li>
						<div class="info_area">
							<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
							<table class="tbl_txt">
								<colgroup>
									<col style="width:42px;">
									<col style="width:auto;">
								</colgroup>
								<tbody>
									<tr>
										<th>배송비 :</th>
										<td><strong class="point-color">6,500</strong> 원</td>
									</tr>
									<tr>
										<th>주소 :</th>
										<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
									</tr>
									<tr>
										<th>전화 :</th>
										<td>(02)1234-1111</td>
									</tr>
								</tbody>
							</table>
							<a href="javascript:;" class="btn_select on">선택</a><!-- [D] 선택되면 .on 클래스 추가 -->
							<a href="javascript:;" class="btn_map">지도보기</a>
						</div>
						<div class="map_area">
							<img src="static/img/test/@map_img.jpg" alt="지도"><!-- [D] 구글지도 연동 -->
						</div>
					</li>

					<li>
						<div class="info_area">
							<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
							<table class="tbl_txt">
								<colgroup>
									<col style="width:42px;">
									<col style="width:auto;">
								</colgroup>
								<tbody>
									<tr>
										<th>배송비 :</th>
										<td><strong class="point-color">6,500</strong> 원</td>
									</tr>
									<tr>
										<th>주소 :</th>
										<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
									</tr>
									<tr>
										<th>전화 :</th>
										<td>(02)1234-1111</td>
									</tr>
								</tbody>
							</table>
							<a href="javascript:;" class="btn_select">선택</a>
							<a href="javascript:;" class="btn_map">지도보기</a>
						</div>
						<div class="map_area">
							<img src="static/img/test/@map_img.jpg" alt="지도">
						</div>
					</li>
					
					<li>
						<div class="info_area">
							<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
							<table class="tbl_txt">
								<colgroup>
									<col style="width:42px;">
									<col style="width:auto;">
								</colgroup>
								<tbody>
									<tr>
										<th>배송비 :</th>
										<td><strong class="point-color">6,500</strong> 원</td>
									</tr>
									<tr>
										<th>주소 :</th>
										<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
									</tr>
									<tr>
										<th>전화 :</th>
										<td>(02)1234-1111</td>
									</tr>
								</tbody>
							</table>
							<a href="javascript:;" class="btn_select">선택</a>
							<a href="javascript:;" class="btn_map">지도보기</a>
						</div>
						<div class="map_area">
							<img src="static/img/test/@map_img.jpg" alt="지도">
						</div>
					</li>
					
					<li>
						<div class="info_area">
							<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
							<table class="tbl_txt">
								<colgroup>
									<col style="width:42px;">
									<col style="width:auto;">
								</colgroup>
								<tbody>
									<tr>
										<th>배송비 :</th>
										<td><strong class="point-color">6,500</strong> 원</td>
									</tr>
									<tr>
										<th>주소 :</th>
										<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
									</tr>
									<tr>
										<th>전화 :</th>
										<td>(02)1234-1111</td>
									</tr>
								</tbody>
							</table>
							<a href="javascript:;" class="btn_select">선택</a>
							<a href="javascript:;" class="btn_map">지도보기</a>
						</div>
						<div class="map_area">
							<img src="static/img/test/@map_img.jpg" alt="지도">
						</div>
					</li>
					
					<li>
						<div class="info_area">
							<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
							<table class="tbl_txt">
								<colgroup>
									<col style="width:42px;">
									<col style="width:auto;">
								</colgroup>
								<tbody>
									<tr>
										<th>배송비 :</th>
										<td><strong class="point-color">6,500</strong> 원</td>
									</tr>
									<tr>
										<th>주소 :</th>
										<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
									</tr>
									<tr>
										<th>전화 :</th>
										<td>(02)1234-1111</td>
									</tr>
								</tbody>
							</table>
							<a href="javascript:;" class="btn_select">선택</a>
							<a href="javascript:;" class="btn_map">지도보기</a>
						</div>
						<div class="map_area">
							<img src="static/img/test/@map_img.jpg" alt="지도">
						</div>
					</li>
				</ul><!-- //.list_store -->
			</div><!-- //.select_store -->
		</div>
	</section>
	<!-- //당일수령 팝업 -->

	<!-- 매장픽업 팝업 -->
	<section class="pop_layer layer_select_store02">
		<div class="inner">
			<h3 class="title">
				매장선택
				<div class="wrap_bubble">
					<div class="btn_bubble"><button type="button" class="btn_help">?</button></div>
					<div class="pop_bubble">
						<div class="inner">
							<button type="button" class="btn_pop_close">닫기</button>
							<div class="container">
								<p class="tit_pop">매장픽업 안내</p>
								<p class="list_txt">※ 원하는 날짜, 원하는 매장에서 상품을 픽업하는 맞춤형 배송 서비스입니다.</p>
							</div>
						</div>
					</div>
				</div><!-- //.wrap_bubble -->
				<button type="button" class="btn_close">닫기</button>
			</h3>
			<div class="select_store">
				<dl class="search_store">
					<dt>픽업 가능 매장 검색</dt>
					<dd class="wrap_select">
						<ul class="ea3 clear">
							<li>
								<select class="select_line">
									<option value="">시·도</option>
									<option value=""></option>
									<option value=""></option>
								</select>
							</li>
							<li>
								<select class="select_line">
									<option value="">구·군</option>
									<option value=""></option>
									<option value=""></option>
								</select>
							</li>
							<li>
								<select class="select_line">
									<option value="">수령일 선택</option>
									<option value=""></option>
									<option value=""></option>
								</select>
							</li>
						</ul>
					</dd>
				</dl><!-- //.search_store -->

				<div class="pickup_tab" data-ui="TabMenu">
					<div class="tab-menu clear">
						<a data-content="menu" class="active" title="선택됨">동일 브랜드 매장</a>
						<a data-content="menu">기타 브랜드 매장</a>
					</div>
					<!-- 동일 브랜드 매장 -->
					<div class="tab-content active" data-content="content">
						<ul class="list_store">
							<li>
								<div class="info_area">
									<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
									<table class="tbl_txt">
										<colgroup>
											<col style="width:32px;">
											<col style="width:auto;">
										</colgroup>
										<tbody>
											<tr>
												<th>주소 :</th>
												<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
											</tr>
											<tr>
												<th>전화 :</th>
												<td>(02)1234-1111</td>
											</tr>
										</tbody>
									</table>
									<a href="javascript:;" class="btn_select on">선택</a><!-- [D] 선택되면 .on 클래스 추가 -->
									<a href="javascript:;" class="btn_map">지도보기</a>
								</div>
								<div class="map_area">
									<img src="static/img/test/@map_img.jpg" alt="지도">
								</div>
							</li>

							<li>
								<div class="info_area">
									<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
									<table class="tbl_txt">
										<colgroup>
											<col style="width:32px;">
											<col style="width:auto;">
										</colgroup>
										<tbody>
											<tr>
												<th>주소 :</th>
												<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
											</tr>
											<tr>
												<th>전화 :</th>
												<td>(02)1234-1111</td>
											</tr>
										</tbody>
									</table>
									<a href="javascript:;" class="btn_select">선택</a>
									<a href="javascript:;" class="btn_map">지도보기</a>
								</div>
								<div class="map_area">
									<img src="static/img/test/@map_img.jpg" alt="지도">
								</div>
							</li>
							
							<li>
								<div class="info_area">
									<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
									<table class="tbl_txt">
										<colgroup>
											<col style="width:32px;">
											<col style="width:auto;">
										</colgroup>
										<tbody>
											<tr>
												<th>주소 :</th>
												<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
											</tr>
											<tr>
												<th>전화 :</th>
												<td>(02)1234-1111</td>
											</tr>
										</tbody>
									</table>
									<a href="javascript:;" class="btn_select">선택</a>
									<a href="javascript:;" class="btn_map">지도보기</a>
								</div>
								<div class="map_area">
									<img src="static/img/test/@map_img.jpg" alt="지도">
								</div>
							</li>
							
							<li>
								<div class="info_area">
									<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
									<table class="tbl_txt">
										<colgroup>
											<col style="width:32px;">
											<col style="width:auto;">
										</colgroup>
										<tbody>
											<tr>
												<th>주소 :</th>
												<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
											</tr>
											<tr>
												<th>전화 :</th>
												<td>(02)1234-1111</td>
											</tr>
										</tbody>
									</table>
									<a href="javascript:;" class="btn_select">선택</a>
									<a href="javascript:;" class="btn_map">지도보기</a>
								</div>
								<div class="map_area">
									<img src="static/img/test/@map_img.jpg" alt="지도">
								</div>
							</li>
							
							<li>
								<div class="info_area">
									<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
									<table class="tbl_txt">
										<colgroup>
											<col style="width:32px;">
											<col style="width:auto;">
										</colgroup>
										<tbody>
											<tr>
												<th>주소 :</th>
												<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
											</tr>
											<tr>
												<th>전화 :</th>
												<td>(02)1234-1111</td>
											</tr>
										</tbody>
									</table>
									<a href="javascript:;" class="btn_select">선택</a>
									<a href="javascript:;" class="btn_map">지도보기</a>
								</div>
								<div class="map_area">
									<img src="static/img/test/@map_img.jpg" alt="지도">
								</div>
							</li>
						</ul><!-- //.list_store -->
					</div>
					<!-- //동일 브랜드 매장 -->
					<!-- 기타 브랜드 매장 -->
					<div class="tab-content" data-content="content">
						<ul class="list_store">
							<li>
								<div class="info_area">
									<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
									<table class="tbl_txt">
										<colgroup>
											<col style="width:32px;">
											<col style="width:auto;">
										</colgroup>
										<tbody>
											<tr>
												<th>주소 :</th>
												<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
											</tr>
											<tr>
												<th>전화 :</th>
												<td>(02)1234-1111</td>
											</tr>
										</tbody>
									</table>
									<a href="javascript:;" class="btn_select">선택</a>
									<a href="javascript:;" class="btn_map">지도보기</a>
								</div>
								<div class="map_area">
									<img src="static/img/test/@map_img.jpg" alt="지도">
								</div>
							</li>
							
							<li>
								<div class="info_area">
									<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
									<table class="tbl_txt">
										<colgroup>
											<col style="width:32px;">
											<col style="width:auto;">
										</colgroup>
										<tbody>
											<tr>
												<th>주소 :</th>
												<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
											</tr>
											<tr>
												<th>전화 :</th>
												<td>(02)1234-1111</td>
											</tr>
										</tbody>
									</table>
									<a href="javascript:;" class="btn_select on">선택</a><!-- [D] 선택되면 .on 클래스 추가 -->
									<a href="javascript:;" class="btn_map">지도보기</a>
								</div>
								<div class="map_area">
									<img src="static/img/test/@map_img.jpg" alt="지도">
								</div>
							</li>

							<li>
								<div class="info_area">
									<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
									<table class="tbl_txt">
										<colgroup>
											<col style="width:32px;">
											<col style="width:auto;">
										</colgroup>
										<tbody>
											<tr>
												<th>주소 :</th>
												<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
											</tr>
											<tr>
												<th>전화 :</th>
												<td>(02)1234-1111</td>
											</tr>
										</tbody>
									</table>
									<a href="javascript:;" class="btn_select">선택</a>
									<a href="javascript:;" class="btn_map">지도보기</a>
								</div>
								<div class="map_area">
									<img src="static/img/test/@map_img.jpg" alt="지도">
								</div>
							</li>
							
							<li>
								<div class="info_area">
									<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
									<table class="tbl_txt">
										<colgroup>
											<col style="width:32px;">
											<col style="width:auto;">
										</colgroup>
										<tbody>
											<tr>
												<th>주소 :</th>
												<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
											</tr>
											<tr>
												<th>전화 :</th>
												<td>(02)1234-1111</td>
											</tr>
										</tbody>
									</table>
									<a href="javascript:;" class="btn_select">선택</a>
									<a href="javascript:;" class="btn_map">지도보기</a>
								</div>
								<div class="map_area">
									<img src="static/img/test/@map_img.jpg" alt="지도">
								</div>
							</li>
							
							<li>
								<div class="info_area">
									<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점 <span class="stock point-color">재고있음</span></p>
									<table class="tbl_txt">
										<colgroup>
											<col style="width:32px;">
											<col style="width:auto;">
										</colgroup>
										<tbody>
											<tr>
												<th>주소 :</th>
												<td>서울 강남구 언주역 354-45 번지 GEP 빌딩 1-2층</td>
											</tr>
											<tr>
												<th>전화 :</th>
												<td>(02)1234-1111</td>
											</tr>
										</tbody>
									</table>
									<a href="javascript:;" class="btn_select">선택</a>
									<a href="javascript:;" class="btn_map">지도보기</a>
								</div>
								<div class="map_area">
									<img src="static/img/test/@map_img.jpg" alt="지도">
								</div>
							</li>
						</ul><!-- //.list_store -->
					</div>
					<!-- //기타 브랜드 매장 -->
				</div><!-- //.pickup_tab -->
			</div><!-- //.select_store -->
		</div>
	</section>
	<!-- //매장픽업 팝업 -->

	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>주문/결제</span>
		</h2>
		<div class="page_step">
			<ul class="clear">
				<li class="on"><span class="icon_order_step01"></span>장바구니</li>
				<li><span class="icon_order_step02"></span>주문하기</li>
				<li><span class="icon_order_step03"></span>주문완료</li>
			</ul>
		</div>
	</section><!-- //.page_local -->

	<section class="cartpage">

		<div class="list_cart">

			<!-- 브랜드별 반복 -->
			<div class="list_brand">
				<h3 class="cart_tit">BESTI BELLI 주문상품</h3>
				<ul class="cart_goods">
					<!-- 상품 반복 -->
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="check_area"><input type="checkbox" class="check_def"></div>
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="option">품번: SLOAX2520</p>
										<p class="option">색상 : NAM / 사이즈 : 74 / 1개</p>
										<p class="price">￦ 105,800</p>
										<p class="staff_price point-color">￦ 105,800 (임직원가)</p><!-- //[D] 임직원가 수정(2017-04-24) -->
									</div>
								</div>
							</div>
							<div class="btn_area">
								<a href="javascript:;" class="btn_open_opt btn-line">옵션/수량 변경</a>
								<a href="javascript:;" class="btn-basic">좋아요</a>
								<a href="javascript:;" class="btn-point">매장픽업전환</a>
							</div>
						</div><!-- //.cart_wrap -->
						<div class="optbox">
							<button type="button" class="btn_close_opt btn_close">닫기</button>
							<form>
							<dl>
								<dt>색상</dt>
								<dd class="colorchip_area">
									<label class="colorchip chip-darkGrey"><input type="radio" name="selectColor" value="dark_grey" checked><span></span></label>
									<label class="colorchip chip-beige light-color"><input type="radio" name="selectColor" value="beige"><span></span></label>
								</dd>
							</dl>
							<dl>
								<dt>사이즈</dt>
								<dd class="size_select">
									<label>
										<input type="radio" name="selectSize" checked>
										<span>S(44)</span>
									</label>
									<label>
										<input type="radio" name="selectSize" disabled>
										<span>M(55)</span>
									</label>
									<label>
										<input type="radio" name="selectSize">
										<span>L(66)</span>
									</label>
									<label>
										<input type="radio" name="selectSize">
										<span>XL(77)</span>
									</label>
								</dd>
							</dl>
							<dl>
								<dt>옵션명</dt>
								<dd class="opt_name">
									<select class="select_line">
										<option value="">선택</option>
										<option value="">옵션1</option>
										<option value="">옵션2</option>
									</select>
								</dd>
							</dl>
							<dl>
								<dt>수량</dt>
								<dd>
									<div class="ea-select">
										<input type="text" value="1" readonly="">
										<button type="button" class="plus">증가</button>
										<button type="button" class="minus">감소</button>
									</div>
								</dd>
							</dl>
							<div class="btn_place">
								<a href="javascript:;" class="btn_close_opt btn-line">변경취소</a>
								<a href="javascript:;" class="btn-basic">변경적용</a>
							</div>
							</form>
						</div><!-- //.optbox -->
					</li>
					<!-- //상품 반복 -->
				</ul><!-- //.cart_goods -->
				<div class="cart_calc">
					<ul>
						<li>
							<label>상품합계</label>
							<span>￦ 30,000,000</span>
						</li>
						<li>
							<label>배송비</label>
							<span>￦ 2,000</span>
						</li>
						<li class="total">
							<label>합계금액</label>
							<span>￦ 30,000,000</span>
							<span class="staff point-color">(임직원가) <strong>￦ 20,000,000</strong></span><!-- //[D] 임직원가 수정(2017-04-24) -->
						</li>
					</ul>
				</div>
			</div>
			<!-- //브랜드별 반복 -->

			<!-- 브랜드별 반복 -->
			<div class="list_brand">
				<h3 class="cart_tit">VIKI 주문상품</h3>
				<ul class="cart_goods">
					<!-- 상품 반복 -->
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="check_area"><input type="checkbox" class="check_def"></div>
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">VIKI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="option">품번: SLOAX2520</p>
										<p class="option">색상 : NAM / 사이즈 : 74 / 1개</p>
										<p class="price">￦ 105,800</p>
									</div>
								</div>
							</div>
							<div class="btn_area">
								<a href="javascript:;" class="btn_open_opt btn-line">옵션/수량 변경</a>
								<a href="javascript:;" class="btn-basic">좋아요</a>
							</div>
						</div><!-- //.cart_wrap -->
						<div class="optbox">
							<button type="button" class="btn_close_opt btn_close">닫기</button>
							<form>
							<dl>
								<dt>색상</dt>
								<dd class="colorchip_area">
									<label class="colorchip chip-darkGrey"><input type="radio" name="selectColor" value="dark_grey" checked><span></span></label>
									<label class="colorchip chip-beige light-color"><input type="radio" name="selectColor" value="beige"><span></span></label>
								</dd>
							</dl>
							<dl>
								<dt>사이즈</dt>
								<dd class="size_select">
									<label>
										<input type="radio" name="selectSize" checked>
										<span>S(44)</span>
									</label>
									<label>
										<input type="radio" name="selectSize" disabled>
										<span>M(55)</span>
									</label>
									<label>
										<input type="radio" name="selectSize">
										<span>L(66)</span>
									</label>
									<label>
										<input type="radio" name="selectSize">
										<span>XL(77)</span>
									</label>
								</dd>
							</dl>
							<dl>
								<dt>옵션명</dt>
								<dd class="opt_name">
									<select class="select_line">
										<option value="">선택</option>
										<option value="">옵션1</option>
										<option value="">옵션2</option>
									</select>
								</dd>
							</dl>
							<dl>
								<dt>수량</dt>
								<dd>
									<div class="ea-select">
										<input type="text" value="1" readonly="">
										<button type="button" class="plus">증가</button>
										<button type="button" class="minus">감소</button>
									</div>
								</dd>
							</dl>
							<div class="btn_place">
								<a href="javascript:;" class="btn_close_opt btn-line">변경취소</a>
								<a href="javascript:;" class="btn-basic">변경적용</a>
							</div>
							</form>
						</div><!-- //.optbox -->
					</li>
					<!-- //상품 반복 -->
					<!-- 상품 반복 -->
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="check_area"><input type="checkbox" class="check_def"></div>
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">VIKI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="option">품번: SLOAX2520</p>
										<p class="option">색상 : NAM / 사이즈 : 74 / 1개</p>
										<p class="price">￦ 105,800</p>
									</div>
								</div>
							</div>
							<div class="btn_area">
								<a href="javascript:;" class="btn_open_opt btn-line">옵션/수량 변경</a>
								<a href="javascript:;" class="btn-basic">좋아요</a>
							</div>
						</div><!-- //.cart_wrap -->
						<div class="optbox">
							<button type="button" class="btn_close_opt btn_close">닫기</button>
							<form>
							<dl>
								<dt>색상</dt>
								<dd class="colorchip_area">
									<label class="colorchip chip-darkGrey"><input type="radio" name="selectColor" value="dark_grey" checked><span></span></label>
									<label class="colorchip chip-beige light-color"><input type="radio" name="selectColor" value="beige"><span></span></label>
								</dd>
							</dl>
							<dl>
								<dt>사이즈</dt>
								<dd class="size_select">
									<label>
										<input type="radio" name="selectSize" checked>
										<span>S(44)</span>
									</label>
									<label>
										<input type="radio" name="selectSize" disabled>
										<span>M(55)</span>
									</label>
									<label>
										<input type="radio" name="selectSize">
										<span>L(66)</span>
									</label>
									<label>
										<input type="radio" name="selectSize">
										<span>XL(77)</span>
									</label>
								</dd>
							</dl>
							<dl>
								<dt>옵션명</dt>
								<dd class="opt_name">
									<select class="select_line">
										<option value="">선택</option>
										<option value="">옵션1</option>
										<option value="">옵션2</option>
									</select>
								</dd>
							</dl>
							<dl>
								<dt>수량</dt>
								<dd>
									<div class="ea-select">
										<input type="text" value="1" readonly="">
										<button type="button" class="plus">증가</button>
										<button type="button" class="minus">감소</button>
									</div>
								</dd>
							</dl>
							<div class="btn_place">
								<a href="javascript:;" class="btn_close_opt btn-line">변경취소</a>
								<a href="javascript:;" class="btn-basic">변경적용</a>
							</div>
							</form>
						</div><!-- //.optbox -->
					</li>
					<!-- //상품 반복 -->
				</ul><!-- //.cart_goods -->
				<div class="cart_calc">
					<ul>
						<li>
							<label>상품합계</label>
							<span>￦ 30,000,000</span>
						</li>
						<li>
							<label>배송비</label>
							<span>￦ 2,000</span>
						</li>
						<li class="total">
							<label>합계금액</label>
							<span>￦ 30,000,000</span>
						</li>
					</ul>
				</div>
			</div>
			<!-- //브랜드별 반복 -->

			<!-- O2O 상품 -->
			<div class="list_brand with_deli_info">
				<h3 class="cart_tit">O2O 상품</h3>
				<ul class="cart_goods">
					<!-- 상품 반복 -->
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="check_area"><input type="checkbox" class="check_def"></div>
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="option">품번: SLOAX2520</p>
										<p class="option">색상 : NAM / 사이즈 : 74 / 1개</p>
										<p class="price">￦ 105,800</p>
									</div>
								</div>
							</div>
							<div class="btn_area">
								<a href="javascript:;" class="btn_open_opt btn-line">옵션/수량 변경</a>
								<a href="javascript:;" class="btn-basic">좋아요</a>
								<a href="javascript:;" class="btn-point">택배수령전환</a>
							</div>
						</div><!-- //.cart_wrap -->
						<div class="optbox">
							<button type="button" class="btn_close_opt btn_close">닫기</button>
							<form>
							<dl>
								<dt>색상</dt>
								<dd class="colorchip_area">
									<label class="colorchip chip-darkGrey"><input type="radio" name="selectColor" value="dark_grey" checked><span></span></label>
									<label class="colorchip chip-beige light-color"><input type="radio" name="selectColor" value="beige"><span></span></label>
								</dd>
							</dl>
							<dl>
								<dt>사이즈</dt>
								<dd class="size_select">
									<label>
										<input type="radio" name="selectSize" checked>
										<span>S(44)</span>
									</label>
									<label>
										<input type="radio" name="selectSize" disabled>
										<span>M(55)</span>
									</label>
									<label>
										<input type="radio" name="selectSize">
										<span>L(66)</span>
									</label>
									<label>
										<input type="radio" name="selectSize">
										<span>XL(77)</span>
									</label>
								</dd>
							</dl>
							<dl>
								<dt>옵션명</dt>
								<dd class="opt_name">
									<select class="select_line">
										<option value="">선택</option>
										<option value="">옵션1</option>
										<option value="">옵션2</option>
									</select>
								</dd>
							</dl>
							<dl>
								<dt>수량</dt>
								<dd>
									<div class="ea-select">
										<input type="text" value="1" readonly="">
										<button type="button" class="plus">증가</button>
										<button type="button" class="minus">감소</button>
									</div>
								</dd>
							</dl>
							<div class="btn_place">
								<a href="javascript:;" class="btn_close_opt btn-line">변경취소</a>
								<a href="javascript:;" class="btn-basic">변경적용</a>
							</div>
							</form>
						</div><!-- //.optbox -->
						<div class="delibox">
							<h4 class="cart_tit">
								발송매장
								<div class="wrap_bubble today_shipping">
									<div class="btn_bubble"><button type="button" class="btn_help">?</button></div>
									<div class="pop_bubble">
										<div class="inner">
											<button type="button" class="btn_pop_close">닫기</button>
											<div class="container">
												<p>선택하신 상품은 당일수령이 가능한 상품입니다.</p>
											</div>
										</div>
									</div>
								</div><!-- //.wrap_bubble -->
							</h4>
							<div class="change_store">
								<span class="store_name">FAHRENHEIT 강남역점</span>
								<a href="javascript:;" class="btn_select_store01 btn-basic">매장변경</a>
							</div>
						</div><!-- //.delibox -->
					</li>
					<!-- //상품 반복 -->
					<!-- 상품 반복 -->
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="check_area"><input type="checkbox" class="check_def"></div>
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="option">품번: SLOAX2520</p>
										<p class="option">색상 : NAM / 사이즈 : 74 / 1개</p>
										<p class="price">￦ 105,800</p>
									</div>
								</div>
							</div>
							<div class="btn_area">
								<a href="javascript:;" class="btn_open_opt btn-line">옵션/수량 변경</a>
								<a href="javascript:;" class="btn-basic">좋아요</a>
							</div>
						</div><!-- //.cart_wrap -->
						<div class="optbox">
							<button type="button" class="btn_close_opt btn_close">닫기</button>
							<form>
							<dl>
								<dt>색상</dt>
								<dd class="colorchip_area">
									<label class="colorchip chip-darkGrey"><input type="radio" name="selectColor" value="dark_grey" checked><span></span></label>
									<label class="colorchip chip-beige light-color"><input type="radio" name="selectColor" value="beige"><span></span></label>
								</dd>
							</dl>
							<dl>
								<dt>사이즈</dt>
								<dd class="size_select">
									<label>
										<input type="radio" name="selectSize" checked>
										<span>S(44)</span>
									</label>
									<label>
										<input type="radio" name="selectSize" disabled>
										<span>M(55)</span>
									</label>
									<label>
										<input type="radio" name="selectSize">
										<span>L(66)</span>
									</label>
									<label>
										<input type="radio" name="selectSize">
										<span>XL(77)</span>
									</label>
								</dd>
							</dl>
							<dl>
								<dt>옵션명</dt>
								<dd class="opt_name">
									<select class="select_line">
										<option value="">선택</option>
										<option value="">옵션1</option>
										<option value="">옵션2</option>
									</select>
								</dd>
							</dl>
							<dl>
								<dt>수량</dt>
								<dd>
									<div class="ea-select">
										<input type="text" value="1" readonly="">
										<button type="button" class="plus">증가</button>
										<button type="button" class="minus">감소</button>
									</div>
								</dd>
							</dl>
							<div class="btn_place">
								<a href="javascript:;" class="btn_close_opt btn-line">변경취소</a>
								<a href="javascript:;" class="btn-basic">변경적용</a>
							</div>
							</form>
						</div><!-- //.optbox -->
						<div class="delibox">
							<h4 class="cart_tit">
								픽업매장
								<div class="wrap_bubble today_shipping">
									<div class="btn_bubble"><button type="button" class="btn_help">?</button></div>
									<div class="pop_bubble">
										<div class="inner">
											<button type="button" class="btn_pop_close">닫기</button>
											<div class="container">
												<p>선택하신 매장을 방문하여 입어보고 수령하시면 됩니다. <br>(재고가 있을 경우 : 당일~3일 이내 방문수령 / 재고가 없을 경우 : 3일~5일 이내 방문수령)</p>
											</div>
										</div>
									</div>
								</div><!-- //.wrap_bubble -->
							</h4>
							<div class="change_store">
								<span class="store_name">FAHRENHEIT 강남역점(2017.01.19)</span>
								<a href="javascript:;" class="btn_select_store02 btn-basic">매장변경</a>
							</div>
						</div><!-- //.delibox -->
					</li>
					<!-- //상품 반복 -->
				</ul><!-- //.cart_goods -->
				<div class="cart_calc">
					<ul>
						<li>
							<label>상품합계</label>
							<span>￦ 30,000,000</span>
						</li>
						<li>
							<label>배송비</label>
							<span>￦ 2,000</span>
						</li>
						<li class="total">
							<label>합계금액</label>
							<span>￦ 30,000,000</span>
						</li>
					</ul>
				</div>
			</div>
			<!-- //O2O 상품 -->

			<div class="btn_area mt-10 mr-10 ml-10">
				<ul class="ea3">
					<li><a href="javascript:;" id="allCheck" class="btn-line">전체선택</a></li>
					<li><a href="javascript:;" id="allCheckF" class="btn-line">선택해제</a></li>
					<li><a href="javascript:;" class="btn-line">선택삭제</a></li>
				</ul>
			</div>

		</div><!-- //.list_cart -->

		<div class="calc_area"><!-- [D] 체크된 상품의 상품가, 배송비 정보 노출 -->
			<h3 class="cart_tit">총 구입금액</h3>
			<div class="cart_calc">
				<ul>
					<li>
						<label>택배배송 상품가</label>
						<span>￦ 30,000,000</span>
						<span class="staff point-color">(임직원가) ￦ 20,000,000</span><!-- //[D] 임직원가 수정(2017-04-24) -->
					</li>
					<li>
						<label>O2O 상품가</label>
						<span>￦ 30,000</span>
						<span class="staff point-color">(임직원가) ￦ 20,000,000</span><!-- //[D] 임직원가 수정(2017-04-24) -->
					</li>
					<li class="total">
						<label>상품금액 합계</label>
						<span>￦ 30,030,000</span>
						<span class="staff point-color">(임직원가) <strong>￦ 20,000,000</strong></span><!-- //[D] 임직원가 수정(2017-04-24) -->
					</li>
				</ul>
			</div>
			<div class="cart_calc mt-5">
				<ul>
					<li>
						<label>택배 배송비</label>
						<span>￦ 2,500</span>
					</li>
					<li>
						<label>O2O 배송비</label>
						<span>￦ 2,500</span>
					</li>
					<li class="total">
						<label>배송비 합계</label>
						<span>￦ 5,000</span>
					</li>
				</ul>
			</div>
			<div class="cart_calc mt-5">
				<ul>
					<li class="all_total">
						<label>총 주문금액</label>
						<span class="point-color">￦ 30,035,000</span>
						<span class="staff point-color">(임직원가) <strong>￦ 20,000,000</strong></span><!-- //[D] 임직원가 수정(2017-04-24) -->
					</li>
				</ul>
			</div>
		</div><!-- //.calc_area -->
		
		<!-- [D] 임직원가 수정(2017-04-24) -->
		<div class="btn_area mt-20 mr-10 ml-10"><!-- [D] 기본 노출 -->
			<ul>
				<li><a href="javascript:;" class="btn-point h-input">선택상품구매</a></li>
			</ul>
		</div>

		<div class="btn_area mt-20 mr-10 ml-10"><!-- [D] 임직원 구매인 경우 노출 -->
			<ul class="ea2">
				<li><a href="javascript:;" class="btn-basic h-input">선택상품구매</a></li>
				<li><a href="javascript:;" class="btn-point h-input">선택상품 임직원 구매</a></li>
			</ul>
		</div>
		<!-- //[D] 임직원가 수정(2017-04-24) -->

	</section>

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
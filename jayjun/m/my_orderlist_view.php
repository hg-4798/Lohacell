<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
	<!-- 매장안내 팝업 -->
	<section class="pop_layer layer_store_info">
		<div class="inner">
			<h3 class="title">매장 위치정보 <button type="button" class="btn_close">닫기</button></h3>
			<div class="select_store">
				<div class="list_store">
					<div class="info_area">
						<p class="store_name"><span class="brand">[SIEG FAHRENHEIT]</span> 강남역점</p>
						<table class="tbl_txt mt-20">
							<colgroup>
								<col style="width:52px;">
								<col style="width:auto;">
							</colgroup>
							<tbody>
								<tr>
									<th>주소 :</th>
									<td>서울 강남구 강남대로 123-12서울 강남구 강남대 123-12</td>
								</tr>
								<tr>
									<th>운영시간 :</th>
									<td>평일 09:00 ~ 18:00  (토/일 09:00 ~ 18:00) </td>
								</tr>
								<tr>
									<th>휴무정보 :</th>
									<td>매주 일요일 / 국경일</td>
								</tr>
								<tr>
									<th>전화번호 :</th>
									<td>02-1234-1111</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="map_area">
						<img src="static/img/test/@map_img2.jpg" alt="지도"><!-- [D] 구글지도 연동 -->
					</div>
				</div><!-- //.list_store -->
			</div><!-- //.select_store -->
		</div>
	</section>
	<!-- //매장안내 팝업 -->

	<!-- 교환신청 팝업 -->
	<section class="pop_layer layer_exchange">
		<div class="inner">
			<h3 class="title">교환신청 <button type="button" class="btn_close">닫기</button></h3>
			<div class="pb-30">
				<ul class="cart_goods">
					<!-- 상품 반복 -->
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓 베이직 시크 엘레강스 스타일의 업그레이드 완성 코트</p><!-- [D] 상품명은 최대 2줄까지 노출 -->
										<p class="option">색상 : NAM / 사이즈 : 74 / 1개</p>
										<p class="price">￦ 105,800 </p>
									</div>
								</div>
							</div>
						</div><!-- //.cart_wrap -->
						<div class="optbox">
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
										<span>44</span>
									</label>
									<label>
										<input type="radio" name="selectSize" disabled>
										<span>55</span>
									</label>
									<label>
										<input type="radio" name="selectSize">
										<span>66</span>
									</label>
									<label>
										<input type="radio" name="selectSize">
										<span>77</span>
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
							</form>
						</div><!-- //.optbox -->
					</li>
					<!-- //상품 반복 -->
				</ul><!-- //.cart_goods -->
				<div class="cart_calc mt-10">
					<ul>
						<li>
							<label>상품합계</label>
							<span>￦ 30,000,000</span>
						</li>
						<li>
							<label>할인</label>
							<span class="point-color">- ￦ 2,000</span>
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
				</div><!-- //.cart_calc -->
				
				<!-- 교환사유 -->
				<div class="order_table">
					<h3 class="cart_tit">교환사유</h3>
					<table class="th-left">
						<colgroup>
							<col style="width:29.37%;">
							<col style="width:auto;">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="required">교환사유</span></th>
								<td>
									<select class="select_line w100-per">
										<option value="">선택</option>
										<option value="">교환사유1</option>
										<option value="">교환사유2</option>
									</select>
								</td>
							</tr>
							<tr>
								<th><span class="required">상세사유</span></th>
								<td><textarea class="w100-per"></textarea></td>
							</tr>
						</tbody>
					</table>
				</div><!-- //.order_table -->
				<!-- //교환사유 -->

				<!-- 택배비 발송 -->
				<div class="order_table">
					<h3 class="cart_tit">택배비 발송</h3>
					<table class="th-left">
						<colgroup>
							<col style="width:29.37%;">
							<col style="width:auto;">
						</colgroup>
						<tbody>
							<tr>
								<td class="pl-10">
									<label>
										<input type="radio" class="radio_def" name="deli_charge" checked>
										<span>동봉 (5천원)</span>
									</label>
									<label class="ml-20">
										<input type="radio" class="radio_def" name="deli_charge">
										<span>선불 + 2500원 동봉</span>
									</label>
									<label class="ml-20">
										<input type="radio" class="radio_def" name="deli_charge">
										<span>신원부담</span>
									</label>
								</td>
							</tr>
							<tr>
								<td class="pl-10">
									<label class="radio_with_input">
										<input type="radio" class="radio_def" name="deli_charge">
										<span>계좌이체(5천원)<br><strong>[신원 은행 계좌번호]</strong></span>
										<input type="text" class="with_input" placeholder="입금자명">
									</label>
								</td>
							</tr>
						</tbody>
					</table>
				</div><!-- //.order_table -->
				<!-- //택배비 발송 -->

				<div class="attention mt-20">
					<h3 class="tit">유의사항</h3>
					<ul class="list">
						<li>교환은 같은 옵션상품만 가능합니다. 다른 옵션의 상품으로 교환을 원하실 경우, 반품 후 재구매를 해주세요.</li>
						<li>상품이 손상/훼손되었거나 이미 사용하셨다면 교환이 불가능합니다. </li>
						<li>교환 사유가 구매자 사유일 경우 왕복 교환 배송비를 상품과 함께 박스에 동봉해 주세요.</li>
						<li>교환 왕복 배송비가 동봉되지 않았을 경우 별도 입금 요청을 드릴 수 있습니다. </li>
						<li>교환 사유가 판매자 사유일 경우 별도 배송비를 동봉하지 않으셔도 됩니다. </li>
						<li>상품 확인 후 실제로 판매자 사유가 아닐 경우 별도 배송비 입금 요청을 드릴 수 있습니다.</li>
					</ul>
				</div>
				<div class="btn_area mt-20 mr-10 ml-10">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-input">취소</a></li>
						<li><a href="javascript:;" class="btn-point h-input">교환신청</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section><!-- //.layer_exchange -->
	<!-- //교환신청 팝업 -->

	<!-- 반품신청 팝업 -->
	<section class="pop_layer layer_refund">
		<div class="inner">
			<h3 class="title">환불/반품신청 <button type="button" class="btn_close">닫기</button></h3>
			<div class="pb-30">
				<ul class="cart_goods">
					<!-- 상품 반복 -->
					<li>
						<div class="cart_wrap">
							<div class="clear">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓 베이직 시크 엘레강스 스타일의 업그레이드 완성 코트</p><!-- [D] 상품명은 최대 2줄까지 노출 -->
										<p class="option">색상 : NAM / 사이즈 : 74 / 1개</p>
										<p class="price">￦ 105,800 </p>
									</div>
								</div>
							</div>
						</div><!-- //.cart_wrap -->
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
							<label>할인</label>
							<span class="point-color">- ￦ 2,000</span>
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
				</div><!-- //.cart_calc -->

				<ul class="list_notice">
					<li>* 할인금액, 배송비를 제외한 금액으로 환불됩니다.</li>
					<li>* 결제 수단별 환불방법과 환불소요기간에 차이가 있습니다. </li>
				</ul>

				<!-- 반품사유 -->
				<div class="order_table">
					<h3 class="cart_tit">반품사유</h3>
					<table class="th-left">
						<colgroup>
							<col style="width:29.37%;">
							<col style="width:auto;">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="required">반품사유</span></th>
								<td>
									<select class="select_line w100-per">
										<option value="">선택</option>
										<option value="">반품사유1</option>
										<option value="">반품사유2</option>
									</select>
								</td>
							</tr>
							<tr>
								<th><span class="required">상세사유</span></th>
								<td><textarea class="w100-per"></textarea></td>
							</tr>
							<tr>
								<th><span class="required">환불방법</span></th>
								<td>신용카드 취소</td>
							</tr>
						</tbody>
					</table>
				</div><!-- //.order_table -->
				<!-- //반품사유 -->

				<!-- 환불계좌 -->
				<div class="order_table">
					<h3 class="cart_tit">환불계좌</h3>
					<table class="th-left">
						<colgroup>
							<col style="width:29.37%;">
							<col style="width:auto;">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="required">은행명</span></th>
								<td>
									<select class="select_line w100-per">
										<option value="">선택</option>
										<option value="">은행1</option>
										<option value="">은행2</option>
									</select>
								</td>
							</tr>
							<tr>
								<th><span class="required">계좌번호</span></th>
								<td><input type="text" class="w100-per" placeholder="하이픈(-) 없이 입력"></td>
							</tr>
							<tr>
								<th><span class="required">예금주</span></th>
								<td><input type="text" class="w100-per" placeholder="이름"></td>
							</tr>
							<tr>
								<th><span class="required">연락처</span></th>
								<td>
									<div class="input_tel">
										<select class="select_line">
											<option value="">010</option>
											<option value=""></option>
											<option value=""></option>
										</select>
										<span class="dash"></span>
										<input type="tel" maxlength="4">
										<span class="dash"></span>
										<input type="tel" maxlength="4">
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div><!-- //.order_table -->
				<!-- //환불계좌 -->

				<!-- 택배비 발송 -->
				<div class="order_table">
					<h3 class="cart_tit">택배비 발송</h3>
					<table class="th-left">
						<colgroup>
							<col style="width:29.37%;">
							<col style="width:auto;">
						</colgroup>
						<tbody>
							<tr>
								<td class="pl-10">
									<label>
										<input type="radio" class="radio_def" name="deli_charge2" checked>
										<span>동봉 (5천원)</span>
									</label>
									<label class="ml-20">
										<input type="radio" class="radio_def" name="deli_charge2">
										<span>선불 + 2500원 동봉</span>
									</label>
									<label class="ml-20">
										<input type="radio" class="radio_def" name="deli_charge2">
										<span>신원부담</span>
									</label>
								</td>
							</tr>
							<tr>
								<td class="pl-10">
									<label class="radio_with_input">
										<input type="radio" class="radio_def" name="deli_charge2">
										<span>계좌이체(5천원)<br><strong>[신원 은행 계좌번호]</strong></span>
										<input type="text" class="with_input" placeholder="입금자명">
									</label>
								</td>
							</tr>
						</tbody>
					</table>
				</div><!-- //.order_table -->
				<!-- //택배비 발송 -->

				<div class="attention mt-20">
					<h3 class="tit">유의사항</h3>
					<ul class="list">
						<li>상품이 손상/훼손 되었거나 이미 사용하셨다면 반품이 불가능합니다.  </li>
						<li>반품 사유가 단순변심, 구매자 사유일 경우반품 배송비를 상품과 함께 박스에 동봉해 주세요 </li>
						<li>배송비가 동봉되지 않았을 경우 별도 입금 요청을 드릴 수 있습니다.  </li>
						<li>반품 사유가 상품불량/파손, 배송누락/오배송 등 판매자 사유일 경우 별도 배송비를 동봉하지 않으셔도 됩니다.  </li>
						<li>상품 확인 후 실제로 판매자 사유가 아닐 경우 별도 배송비 입금 요청을 드릴 수 있습니다.</li>
						<li>가상계좌로 결제하신 경우에는 환불이 영업일 기준으로 1~2일정도 소요될 수 있습니다.</li>
					</ul>
				</div>
				<div class="btn_area mt-20 mr-10 ml-10">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-input">취소</a></li>
						<li><a href="javascript:;" class="btn-point h-input">반품신청</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section><!-- //.layer_refund -->
	<!-- //반품신청 팝업 -->

	<!-- 배송지 변경 팝업 -->
	<section class="pop_layer layer_change_addr">
		<div class="inner">
			<h3 class="title">배송지 변경 <button type="button" class="btn_close">닫기</button></h3>

			<div class="order_table">
				<div class="table_top clear">
					<label><input type="checkbox" class="check_def"> <span>기본 배송지로 설정</span></label>
					<div class="btn_area"><a href="javascript:;" class="btn_deli_site btn-basic">배송지목록</a></div>
				</div>
			</div><!-- //.order_table -->

			<div class="board_type_write">
				<dl>
					<dt><span class="required">받는사람</span></dt>
					<dd><input type="text" class="w100-per" placeholder="홍길동"></dd>
				</dl>
				<dl>
					<dt><span class="required">휴대폰 번호</span></dt>
					<dd>
						<div class="input_tel">
							<select class="select_line">
								<option value="">010</option>
								<option value=""></option>
								<option value=""></option>
							</select>
							<span class="dash"></span>
							<input type="tel" maxlength="4" placeholder="1234">
							<span class="dash"></span>
							<input type="tel" maxlength="4" placeholder="1234">
						</div>
					</dd>
				</dl>
				<dl>
					<dt>전화번호 (선택)</dt>
					<dd>
						<div class="input_tel">
							<select class="select_line">
								<option value="">02</option>
								<option value=""></option>
								<option value=""></option>
							</select>
							<span class="dash"></span>
							<input type="tel" maxlength="4">
							<span class="dash"></span>
							<input type="tel" maxlength="4">
						</div>
					</dd>
				</dl>
				<dl>
					<dt><span class="required">주소</span></dt>
					<dd>
						<div class="input_addr">
							<input type="text" class="w100-per" placeholder="15364">
							<div class="btn_addr"><a href="javascript:;" class="btn-basic h-input">주소찾기</a></div>
						</div>
						<input type="text" class="w100-per mt-5" placeholder="서울 강남구 강남대로 238">
						<input type="text" class="w100-per mt-5" placeholder="11-1">
					</dd>
				</dl>
				<dl>
					<dt>배송 요청사항</dt>
					<dd>
						<select class="select_line w100-per">
							<option value="">직접입력</option>
							<option value=""></option>
							<option value=""></option>
						</select>
						<input type="text" class="w100-per mt-5">
					</dd>
				</dl>
				<div class="btn_area mt-20">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-input">취소</a></li>
						<li><a href="javascript:;" class="btn-point h-input">적용</a></li>
					</ul>
				</div>
			</div><!-- //.board_type_write -->
		</div>
	</section><!-- //.layer_change_addr -->
	<!-- //배송지 변경 팝업 -->

	<!-- 배송지 목록 팝업 -->
	<section class="pop_layer layer_deli_site">
		<div class="inner">
			<h3 class="title">배송지 목록<button type="button" class="btn_close">닫기</button></h3>
			<div class="list_type">
				<table class="list_with_radio">
					<colgroup>
						<col style="width:40px;">
						<col style="width:auto;">
					</colgroup>
					<tbody>
						<tr>
							<th><input type="radio" id="deliSite01" class="radio_def" name="deliSite"></th>
							<td>
								<label for="deliSite01">
									<p class="name">우리집</p>
									<p class="mt-5">서울 강남구 강남대로 123번지 서울 강남구 강남대로 123번지</p>
								</label>
							</td>
						</tr>
						<tr>
							<th><input type="radio" id="deliSite02" class="radio_def" name="deliSite"></th>
							<td>
								<label for="deliSite02">
									<p class="name">회사</p>
									<p class="mt-5">서울 강남구 강남대로 123번지 서울 강남구 강남대로 123번지</p>
								</label>
							</td>
						</tr>
						<tr>
							<th><input type="radio" id="deliSite03" class="radio_def" name="deliSite"></th>
							<td>
								<label for="deliSite03">
									<p class="name">배송지 이름</p>
									<p class="mt-5">서울 강남구 강남대로 123번지 서울 강남구 강남대로 123번지</p>
								</label>
							</td>
						</tr>
						<tr>
							<th><input type="radio" id="deliSite04" class="radio_def" name="deliSite"></th>
							<td>
								<label for="deliSite04">
									<p class="name">배송지 2</p>
									<p class="mt-5">서울 강남구 강남대로 123번지 서울 강남구 강남대로 123번지</p>
								</label>
							</td>
						</tr>
						<!-- 등록된 배송지가 없는 경우 -->
						<!-- <tr>
							<td colspan="2" style="height:150px;text-align:center;">
								등록된 배송지가 없습니다. 
							</td>
						</tr> -->
						<!-- //등록된 배송지가 없는 경우 -->
					</tbody>
				</table>

				<div class="btn_area">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-large">취소</a></li>
						<li><a href="javascript:;" class="btn-point h-large">적용</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<!-- //배송지 목록 팝업 -->

	<!-- 리뷰작성 팝업 -->
	<section class="pop_layer layer_review_write">
		<div class="inner">
			<h3 class="title">리뷰작성<button type="button" class="btn_close">닫기</button></h3>
			<div class="board_type_write">
				<dl>
					<dt>상품명</dt>
					<dd class="subject">레이어드 스타일 티셔츠</dd>
				</dl>
				<dl>
					<dt>별점</dt>
					<dd>
						<div class="rating_list">
							<label>사이즈</label>
							<div class="rating clear">
								<input type="radio" class="rating-input" id="rating-size5" name="ratingSize" >
								<label for="rating-size5" class="rating-star score5"><p>5점 만점 중<span>5</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-size4" name="ratingSize" checked>
								<label for="rating-size4" class="rating-star score4"><p>5점 만점 중<span>4</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-size3" name="ratingSize">
								<label for="rating-size3" class="rating-star score3"><p>5점 만점 중<span>3</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-size2" name="ratingSize">
								<label for="rating-size2" class="rating-star score2"><p>5점 만점 중<span>2</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-size1" name="ratingSize">
								<label for="rating-size1" class="rating-star score1"><p>5점 만점 중<span>1</span>점</p></label>
							</div>
						</div>
						<div class="rating_list">
							<label>색상</label>
							<div class="rating clear">
								<input type="radio" class="rating-input" id="rating-color5" name="ratingColor" >
								<label for="rating-color5" class="rating-star score5"><p>5점 만점 중<span>5</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-color4" name="ratingColor" checked>
								<label for="rating-color4" class="rating-star score4"><p>5점 만점 중<span>4</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-color3" name="ratingColor">
								<label for="rating-color3" class="rating-star score3"><p>5점 만점 중<span>3</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-color2" name="ratingColor">
								<label for="rating-color2" class="rating-star score2"><p>5점 만점 중<span>2</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-color1" name="ratingColor">
								<label for="rating-color1" class="rating-star score1"><p>5점 만점 중<span>1</span>점</p></label>
							</div>
						</div>
						<div class="rating_list">
							<label>배송</label>
							<div class="rating clear">
								<input type="radio" class="rating-input" id="rating-deli5" name="ratingDeli" >
								<label for="rating-deli5" class="rating-star score5"><p>5점 만점 중<span>5</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-deli4" name="ratingDeli" checked>
								<label for="rating-deli4" class="rating-star score4"><p>5점 만점 중<span>4</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-deli3" name="ratingDeli">
								<label for="rating-deli3" class="rating-star score3"><p>5점 만점 중<span>3</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-deli2" name="ratingDeli">
								<label for="rating-deli2" class="rating-star score2"><p>5점 만점 중<span>2</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-deli1" name="ratingDeli">
								<label for="rating-deli1" class="rating-star score1"><p>5점 만점 중<span>1</span>점</p></label>
							</div>
						</div>
						<div class="rating_list">
							<label>품질/만족도</label>
							<div class="rating clear">
								<input type="radio" class="rating-input" id="rating-good5" name="ratingGood" >
								<label for="rating-good5" class="rating-star score5"><p>5점 만점 중<span>5</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-good4" name="ratingGood" checked>
								<label for="rating-good4" class="rating-star score4"><p>5점 만점 중<span>4</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-good3" name="ratingGood">
								<label for="rating-good3" class="rating-star score3"><p>5점 만점 중<span>3</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-good2" name="ratingGood">
								<label for="rating-good2" class="rating-star score2"><p>5점 만점 중<span>2</span>점</p></label>
								<input type="radio" class="rating-input" id="rating-good1" name="ratingGood">
								<label for="rating-good1" class="rating-star score1"><p>5점 만점 중<span>1</span>점</p></label>
							</div>
						</div>
					</dd>
				</dl>
				<dl>
					<dt>상세정보</dt>
					<dd class="body_info">
						<label>키(cm)<input type="text" value="160"></label>
						<label>몸무게(kg)<input type="text" value="60"></label>
					</dd>
				</dl>
				<dl>
					<dt>제목</dt>
					<dd>
						<input type="text" class="w100-per" placeholder="제목 입력(필수)">
					</dd>
				</dl>
				<dl>
					<dt>내용</dt>
					<dd>
						<textarea class="w100-per" rows="6" placeholder="내용 입력(필수)"></textarea>
					</dd>
				</dl>
				<dl>
					<dt>이미지 첨부</dt>
					<dd>
						<div class="upload_img">
							<ul>
								<li>
									<label>
										<input type="hidden" name="v_up_filename[0]" value="" class="vi-image"><input type="file" name="up_filename[0]" class="add-image">
										<div class="image_preview" style='display:none;position:absolute;top:0;left:0;width:100%;height:100%;'>
											<img src="" style='position:absolute;top:0;left:0;width:100%;height:100%;'>
											<a href="#" class="delete-btn">
												<button type="button"></button>
											</a>
										</div>
									</label>
								</li>
								<li>
									<label>
										<input type="hidden" name="v_up_filename[1]" value="" class="vi-image"><input type="file" name="up_filename[1]" class="add-image">
										<div class="image_preview" style='display:none;position:absolute;top:0;left:0;width:100%;height:100%;'>
											<img src="" style='position:absolute;top:0;left:0;width:100%;height:100%;'>
											<a href="#" class="delete-btn">
												<button type="button"></button>
											</a>
										</div>
									</label>
								</li>
								<li>
									<label>
										<input type="hidden" name="v_up_filename[2]" value="" class="vi-image"><input type="file" name="up_filename[2]" class="add-image">
										<div class="image_preview" style='display:none;position:absolute;top:0;left:0;width:100%;height:100%;'>
											<img src="" style='position:absolute;top:0;left:0;width:100%;height:100%;'>
											<a href="#" class="delete-btn">
												<button type="button"></button>
											</a>
										</div>
									</label>
								</li>
								<li>
									<label>
										<input type="hidden" name="v_up_filename[3]" value="" class="vi-image"><input type="file" name="up_filename[3]" class="add-image">
										<div class="image_preview" style='display:none;position:absolute;top:0;left:0;width:100%;height:100%;'>
											<img src="" style='position:absolute;top:0;left:0;width:100%;height:100%;'>
											<a href="#" class="delete-btn">
												<button type="button"></button>
											</a>
										</div>
									</label>
								</li>
							</ul>
						</div>
						<p class="mt-5">파일명: 한글, 영문, 숫자/파일 크기: 3mb 이하/파일 형식: GIF, JPG, JPEG</p>
					</dd>
				</dl>

				<div class="btn_area">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-large">취소</a></li>
						<li><a href="javascript:;" class="btn-point h-large">등록</a></li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<!-- //리뷰작성 팝업 -->

	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>주문/배송조회</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="orderlist_view sub_bdtop">
		<!-- 주문상세 테이블 -->
		<div class="order_table mt-15">
			<table class="th-left">
				<colgroup>
					<col style="width:32.8%;">
					<col style="width:auto;">
				</colgroup>
				<thead>
					<tr>
						<th colspan="2">
							<span class="ordnum">주문번호: 20170119141335-78978646036</span>
							<span class="date">2017.01.19</span>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="2">
							<div class="ordered_goods">
								<a href="#">
									<div class="img"><img src="static/img/test/@goodslist_01.jpg" alt=""></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓 솔리드 심플 벨티트 자켓 솔리드 심플 벨티트 자켓</p>
									</div>
								</a>
							</div>
						</td>
					</tr>
					<tr>
						<th>품번</th>
						<td>SLOAX2520</td>
					</tr>
					<tr>
						<th>옵션</th>
						<td>색상 : NAM / 사이즈 : 74 </td>
					</tr>
					<tr>
						<th>수량</th>
						<td>3개</td>
					</tr>
					<tr>
						<th>판매가</th>
						<td>￦ 81,000</td>
					</tr>
					<tr>
						<th>배송정보</th>
						<td>
							<div class="delivery_info">
								<span class="tit">[발송매장]</span>
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
								<p class="name">VIKI 강남역점</p>
								<p class="price">￦ 3,000</p>
								<a href="javascript:;" class="btn_store_info btn-basic">매장안내</a>
							</div>
						</td>
					</tr>
					<tr>
						<th>상태</th>
						<td>배송중 <a href="javascript:;" class="btn-line ml-5">배송추적</a></td>
					</tr>
					<tr>
						<th>취소/확정/리뷰</th>
						<td>
							<div class="decision"><!-- [D] 주문 상태 값에 따라 버튼 변경 -->
								<!-- [D] 주문 상태: 주문접수, 결제완료 -->
								<a href="javascript:;" class="btn-basic basic2">주문취소</a>

								<!-- [D] 주문 상태: 배송준비 -->
								<span>-</span>

								<!-- [D] 주문 상태: 배송중, 배송완료 -->
								<a href="javascript:;" class="btn_refund btn-basic">반품</a>
								<a href="javascript:;" class="btn_exchange btn-line">교환</a>
								<a href="javascript:;" class="btn-point">구매확정</a>

								<!-- [D] 주문 상태: 구매확정 -->
								<a href="javascript:;" class="btn_review_write btn-line">리뷰작성</a>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->
		<!-- //주문상세 테이블 -->

		<!-- 주문상세 테이블 -->
		<div class="order_table mt-15">
			<table class="th-left">
				<colgroup>
					<col style="width:32.8%;">
					<col style="width:auto;">
				</colgroup>
				<tbody>
					<tr>
						<td colspan="2">
							<div class="ordered_goods">
								<a href="#">
									<div class="img"><img src="static/img/test/@goodslist_01.jpg" alt=""></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓 솔리드 심플 벨티트 자켓 솔리드 심플 벨티트 자켓</p>
									</div>
								</a>
							</div>
						</td>
					</tr>
					<tr>
						<th>품번</th>
						<td>SLOAX2520</td>
					</tr>
					<tr>
						<th>옵션</th>
						<td>색상 : NAM / 사이즈 : 74 </td>
					</tr>
					<tr>
						<th>수량</th>
						<td>3개</td>
					</tr>
					<tr>
						<th>판매가</th>
						<td>￦ 81,000</td>
					</tr>
					<tr>
						<th>배송정보</th>
						<td>
							<div class="delivery_info">
								<span class="tit">[픽업매장]</span>
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
								<p class="name">VIKI 강남역점</p>
								<p class="price">￦ 3,000</p>
								<a href="javascript:;" class="btn_store_info btn-basic">매장안내</a>
							</div>
						</td>
					</tr>
					<tr>
						<th>상태</th>
						<td>결제완료</td>
					</tr>
					<tr>
						<th>취소/확정/리뷰</th>
						<td>
							<div class="decision">
								<a href="javascript:;" class="btn-basic basic2">주문취소</a>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->
		<!-- //주문상세 테이블 -->

		<!-- 주문상세 테이블 -->
		<div class="order_table mt-15">
			<table class="th-left">
				<colgroup>
					<col style="width:32.8%;">
					<col style="width:auto;">
				</colgroup>
				<tbody>
					<tr>
						<td colspan="2">
							<div class="ordered_goods">
								<a href="#">
									<div class="img"><img src="static/img/test/@goodslist_01.jpg" alt=""></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓 솔리드 심플 벨티트 자켓 솔리드 심플 벨티트 자켓</p>
									</div>
								</a>
							</div>
						</td>
					</tr>
					<tr>
						<th>품번</th>
						<td>SLOAX2520</td>
					</tr>
					<tr>
						<th>옵션</th>
						<td>색상 : NAM / 사이즈 : 74 </td>
					</tr>
					<tr>
						<th>수량</th>
						<td>3개</td>
					</tr>
					<tr>
						<th>판매가</th>
						<td>￦ 81,000</td>
					</tr>
					<tr>
						<th>배송정보</th>
						<td>
							<div class="delivery_info">
								<span class="tit">[택배수령]</span>
								<div class="wrap_bubble today_shipping">
									<div class="btn_bubble"><button type="button" class="btn_help">?</button></div>
									<div class="pop_bubble">
										<div class="inner">
											<button type="button" class="btn_pop_close">닫기</button>
											<div class="container">
												<p>본사물류 또는 해당 브랜드 매장에서 택배로 고객님께 상품이 배송됩니다. <br>(주문 완료 후, 3~5일 이내 수령)</p>
											</div>
										</div>
									</div>
								</div><!-- //.wrap_bubble -->
							</div>
						</td>
					</tr>
					<tr>
						<th>상태</th>
						<td>배송중 <a href="javascript:;" class="btn-line ml-5">배송추적</a></td>
					</tr>
					<tr>
						<th>취소/확정/리뷰</th>
						<td>
							<div class="decision">
								<a href="javascript:;" class="btn_refund btn-basic">반품</a>
								<a href="javascript:;" class="btn_exchange btn-line">교환</a>
								<a href="javascript:;" class="btn-point">구매확정</a>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->
		<!-- //주문상세 테이블 -->

		<div class="btn_area mt-20 mr-10 ml-10">
			<ul class="ea2 dib_type">
				<!-- [D] 주문 상태: 주문접수, 결제완료인 경우에만 노출 -->
				<li><a href="javascript:;" class="btn-line h-input">전체주문취소</a></li>
				<!-- //[D] 주문 상태: 주문접수, 결제완료인 경우에만 노출 -->
				<li><a href="my_orderlist.php" class="btn-point h-input">목록</a></li>
			</ul>
		</div>

		<!-- 할인 및 결제정보 -->
		<div class="order_table mt-25">
			<h3 class="cart_tit">할인 및 결제정보</h3>
			<table class="th-left">
				<colgroup>
					<col style="width:32.8%;">
					<col style="width:auto;">
				</colgroup>
				<tbody>
					<tr>
						<th>총 상품금액</th>
						<td>￦ 10,000,000</td>
					</tr>
					<tr>
						<th>포인트 사용</th>
						<td><span class="point-color">- 10,000 P</span></td>
					</tr>
					<tr>
						<th>E포인트 사용</th>
						<td><span class="point-color">- 10,000 P</span></td>
					</tr>
					<tr>
						<th>쿠폰할인</th>
						<td><span class="point-color">- ￦ 10,000</span></td>
					</tr>
					<tr>
						<th>배송비</th>
						<td>￦ 2,500</td>
					</tr>
					<tr>
						<th>실 결제금액</th>
						<td><strong class="point-color">￦ 8,000,000</strong></td>
					</tr>
					<tr>
						<th>적립예정포인트</th>
						<td>1,000P<p class="msg_sm mt-5">(구매확정 시 적립예정 포인트가 지급됩니다.)</p></td>
					</tr>
					<tr>
						<th>결제방법</th>
						<td>신용카드(승인일자: 2017.01.16 16:00:57)</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->
		<!-- //할인 및 결제정보 -->

		<!-- 배송지 정보 -->
		<div class="order_table">
			<h3 class="cart_tit">
				배송지 정보
				<!-- [D] 주문 상태: 주문접수, 결제완료인 경우에만 노출 -->
				<a href="javascript:;" class="btn_change_addr btn-line">배송지변경</a>
				<!-- //[D] 주문 상태: 주문접수, 결제완료인 경우에만 노출 -->
			</h3>
			<table class="th-left">
				<colgroup>
					<col style="width:32.8%;">
					<col style="width:auto;">
				</colgroup>
				<tbody>
					<tr>
						<th>받는사람</th>
						<td>홍길동</td>
					</tr>
					<tr>
						<th>휴대전화</th>
						<td>010-5212-4512</td>
					</tr>
					<tr>
						<th>전화번호(선택)</th>
						<td>02-1521-2242</td>
					</tr>
					<tr>
						<th>주소</th>
						<td>
							<p class="post">12345</p>
							<p class="mt-5">서울 강남구 강남대로 238 – 11</p>
						</td>
					</tr>
					<tr>
						<th>배송 요청사항</th>
						<td>배송 전 연락주세요.</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //.order_table -->
		<!-- //배송지 정보 -->
	</section><!-- //.orderlist_view -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
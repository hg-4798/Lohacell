<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="cartOrder-page">

		<article class="cart-order-wrap">
			<header class="progess-title">
				<h2>주문/결제</h2>
				<ul class="flow clear">
					<li class="active"><div><i></i><span>STEP 1</span>장바구니</div></li>
					<li><div><i></i><span>STEP 2</span>주문하기</div></li>
					<li><div><i></i><span>STEP 3</span>주문완료</div></li>
				</ul>
			</header>
			
			<section class="mt-70">
				<header class="cart-section-title">
					<h3>BESTI BELLI 주문상품</h3>
					<p class="att">*본사물류 또는 해당 브랜드 매장에서 택배로 고객님께 상품이 배송됩니다. (주문 완료 후, 3~5일 이내 수령)</p>
				</header>
				<table class="th-top">
					<caption>장바구니 담긴 품목</caption>
					<colgroup>
						<col style="width:54px">
						<col style="width:auto">
						<col style="width:170px">
						<col style="width:90px">
						<col style="width:130px">
						<col style="width:130px">
						<col style="width:116px">
						<col style="width:20px">
					</colgroup>
					<thead>
						<tr>
							<th scope="col"><div class="checkbox"><input type="checkbox" id="itemPutAll01"><label for="itemPutAll01"></label></div></th>
							<th scope="col">상품정보</th>
							<th scope="col">수량</th>
							<th scope="col">적립</th>
							<th scope="col">판매가</th>
							<th scope="col">배송정보</th>
							<th scope="col">선택</th>
							<th scope="col" class="fz-0">삭제</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="8" class="reset">
								<div class="cart-total-price clear">
									<dl>
										<dt>상품합계</dt>
										<dd>\ 30,000,000</dd>
									</dl>
									<span class="txt">+</span>
									<dl>
										<dt>배송비</dt>
										<dd>\ 0</dd>
									</dl>
									<dl class="sum">
										<dt>합계</dt>
										<dd>\ 30,000,000</dd>
									</dl>
									<!-- [D] 임직원가 수정(2017-04-24) -->
									<div class="staff_price point-color">
										<dl>
											<dt>(임직원가)</dt>
											<dd>\ 20,000,000</dd>
										</dl>
									</div>
									<!-- //[D] 임직원가 수정(2017-04-24) -->
								</div>
							</td>
						</tr>
					</tfoot>
					<tbody data-ui="TabMenu">
						<tr>
							<td><div class="checkbox"><input type="checkbox" id="itemPut01"><label for="itemPut01"></label></div></td>
							<td>
								<div class="goods-in-td">
									<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_02.jpg" alt="썸네일"></a></div>
									<div class="info">
										<p class="brand-nm">BESTI BELLI</p>
										<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
										<p class="opt">품번: SLOAX2520 / 색상 : NAM  / 사이즈 55</p>
										<button class="btn-line h-small" type="button" data-content="menu"><span>옵션변경</span></button>
									</div>
								</div>
							</td>
							<td class="change-quantity">
								<div class="quantity">
									<input type="text" value="1" readonly="">
									<button class="plus"></button>
									<button class="minus"></button>
								</div>
								<div class="btn"><button type="button" class="btn-line h-small"><span>변경</span></button></div>
							</td>
							<td class="txt-toneB">10%</td>
							<td class="txt-toneA">\ 10,955,800 <p class="point-color mt-5">\ 76,000(임직원가)</p></td><!-- //[D] 임직원가 수정(2017-04-24) -->
							<td class="flexible-delivery"><strong class="txt-toneA">\3,000</strong><div class="pt-5">50,000원 이상<br>무료배송</div></td>
							<td>
								<div class="td-btnGroup">
									<button class="btn-basic h-small"><span>좋아요</span></button>
								</div>
							</td>
							<td class="va-t ta-l"><button class="item-del"><span>장바구니에서 삭제</span></button></td>
						</tr>
						<tr data-content="content">
							<td class="reset" colspan="8">
								<div class="opt-change">
									<h4>상품옵션 변경</h4>
									<div>
										<dl class="d-iblock">
											<dt>색상</dt>
											<dd>
												<div class="goods-colorChoice">
													<label class="chip-black"><input type="radio" name="color_choice" value="BLACK" checked></label> 
													<label class="chip-beige"><input type="radio" name="color_choice" value="BEIGE"></label>
													<label class="chip-white"><input type="radio" name="color_choice" value="WHITE"></label>
													<label class="chip-pink"><input type="radio" name="color_choice" value="PINK"></label>
												</div>
											</dd>
										</dl>
										<span class="arrow"></span>
										<dl class="d-iblock">
											<dt>사이즈</dt>
											<dd>
												<div class="opt-size">
													<div><input type="radio" name="cartOptSize" id="sizeChange44" value="44"><label for="sizeChange44">44</label></div>
													<div><input type="radio" name="cartOptSize" id="sizeChange66" value="66" checked><label for="sizeChange66">66</label></div>
													<div><input type="radio" name="cartOptSize" id="sizeChange77" value="77"><label for="sizeChange77">77</label></div>
												</div>
											</dd>
										</dl>
									</div>
									<dl class="mt-15">
										<dt><label for="changeOpt_name2">옵션명</label></dt>
										<dd>
											<div class="select">
												<select id="changeOpt_name2">
													<option value="">선택</option>
												</select>
											</div>
										</dd>
									</dl>
									<div class="btn">
										<button class="btn-basic h-small" type="button"><span>옵션변경</span></button>
										<button class="btn-line h-small" type="button"><span>변경취소</span></button>
									</div>
									<button class="item-del"><span>닫기</span></button>
								</div>
							</td>
						</tr>
						<tr>
							<td><div class="checkbox"><input type="checkbox" id="itemPut02"><label for="itemPut02"></label></div></td>
							<td>
								<div class="goods-in-td">
									<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_01.jpg" alt="썸네일"></a></div>
									<div class="info">
										<p class="brand-nm">BESTI BELLI</p>
										<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
										<p class="opt">품번: SLOAX2520 / 색상 : NAM  / 사이즈 55</p>
										<button class="btn-line h-small" type="button" data-content="menu"><span>옵션변경</span></button>
									</div>
								</div>
							</td>
							<td class="change-quantity">
								<div class="quantity">
									<input type="text" value="1" readonly="">
									<button class="plus"></button>
									<button class="minus"></button>
								</div>
								<div class="btn"><button type="button" class="btn-line h-small"><span>변경</span></button></div>
							</td>
							<td class="txt-toneB">10%</td>
							<td class="txt-toneA">\ 10,955,800 <p class="point-color mt-5">\ 76,000(협력업체가)</p></td><!-- //[D] 임직원가 수정(2017-04-24) -->
							<td class="flexible-delivery"><strong class="txt-toneA">\3,000</strong><div class="pt-5">50,000원 이상<br>무료배송</div></td>
							<td>
								<div class="td-btnGroup">
									<button class="btn-point h-small"><span>픽업매장전환</span></button>
									<button class="btn-basic h-small"><span>좋아요</span></button>
								</div>
							</td>
							<td class="va-t ta-l"><button class="item-del"><span>장바구니에서 삭제</span></button></td>
						</tr>
						<tr data-content="content">
							<td class="reset" colspan="8">
								<div class="opt-change">
									<h4>상품옵션 변경</h4>
									<div>
										<dl class="d-iblock">
											<dt>색상</dt>
											<dd>
												<div class="goods-colorChoice">
													<label class="chip-violet"><input type="radio" name="color_choice" value="VIOLET" checked></label> 
													<label class="chip-red"><input type="radio" name="color_choice" value="RED"></label>
													<label class="chip-green"><input type="radio" name="color_choice" value="GREEn"></label>
												</div>
											</dd>
										</dl>
									</div>
									<dl class="mt-15">
										<dt><label for="changeOpt_name3">옵션명</label></dt>
										<dd>
											<div class="select">
												<select id="changeOpt_name3">
													<option value="">선택</option>
												</select>
											</div>
										</dd>
									</dl>
									<div class="btn">
										<button class="btn-basic h-small" type="button"><span>옵션변경</span></button>
										<button class="btn-line h-small" type="button"><span>변경취소</span></button>
									</div>
									<button class="item-del"><span>닫기</span></button>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</section><!-- //.cart-section-title -->

			<section class="mt-60">
				<header class="cart-section-title">
					<h3>BESTI BELLI 주문상품</h3>
					<p class="att">*본사물류 또는 해당 브랜드 매장에서 택배로 고객님께 상품이 배송됩니다. (주문 완료 후, 3~5일 이내 수령)</p>
				</header>
				<table class="th-top">
					<caption>장바구니 담긴 품목</caption>
					<colgroup>
						<col style="width:54px">
						<col style="width:auto">
						<col style="width:170px">
						<col style="width:90px">
						<col style="width:130px">
						<col style="width:130px">
						<col style="width:116px">
						<col style="width:20px">
					</colgroup>
					<thead>
						<tr>
							<th scope="col"><div class="checkbox"><input type="checkbox" id="itemPutAll02"><label for="itemPutAll02"></label></div></th>
							<th scope="col">상품정보</th>
							<th scope="col">수량</th>
							<th scope="col">적립</th>
							<th scope="col">판매가</th>
							<th scope="col">배송정보</th>
							<th scope="col">선택</th>
							<th scope="col" class="fz-0">삭제</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="8" class="reset">
								<div class="cart-total-price clear">
									<dl>
										<dt>상품합계</dt>
										<dd>\ 30,000,000</dd>
									</dl>
									<span class="txt">+</span>
									<dl>
										<dt>배송비</dt>
										<dd>\ 0</dd>
									</dl>
									<dl class="sum">
										<dt>합계</dt>
										<dd>\ 30,000,000</dd>
									</dl>
								</div>
							</td>
						</tr>
					</tfoot>
					<tbody data-ui="TabMenu">
						<tr>
							<td><div class="checkbox"><input type="checkbox" id="itemPut03"><label for="itemPut03"></label></div></td>
							<td>
								<div class="goods-in-td">
									<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_02.jpg" alt="썸네일"></a></div>
									<div class="info">
										<p class="brand-nm">BESTI BELLI</p>
										<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
										<p class="opt">품번: SLOAX2520 / 색상 : NAM  / 사이즈 55</p>
										<button class="btn-line h-small" type="button" data-content="menu"><span>옵션변경</span></button>
									</div>
								</div>
							</td>
							<td class="change-quantity">
								<div class="quantity">
									<input type="text" value="1" readonly="">
									<button class="plus"></button>
									<button class="minus"></button>
								</div>
								<div class="btn"><button type="button" class="btn-line h-small"><span>변경</span></button></div>
							</td>
							<td class="txt-toneB">10%</td>
							<td class="txt-toneA">\ 10,955,800</td>
							<td class="flexible-delivery"><strong class="txt-toneA">[당일배송]<br>\3,000</strong><div class="pt-5">BESTI BELLI 강남역점</div></td>
							<td>
								<div class="td-btnGroup">
									<button class="btn-point h-small"><span>택배수령전환</span></button>
									<button class="btn-basic h-small"><span>좋아요</span></button>
								</div>
							</td>
							<td class="va-t ta-l"><button class="item-del"><span>장바구니에서 삭제</span></button></td>
						</tr>
						<tr data-content="content">
							<td class="reset" colspan="8">
								<div class="opt-change">
									<h4>상품옵션 변경</h4>
									<div>
										<dl class="d-iblock">
											<dt>색상</dt>
											<dd>
												<div class="goods-colorChoice">
													<label class="chip-black"><input type="radio" name="color_choice" value="BLACK" checked></label> 
													<label class="chip-beige"><input type="radio" name="color_choice" value="BEIGE"></label>
													<label class="chip-white"><input type="radio" name="color_choice" value="WHITE"></label>
													<label class="chip-pink"><input type="radio" name="color_choice" value="PINK"></label>
												</div>
											</dd>
										</dl>
										<span class="arrow"></span>
										<dl class="d-iblock">
											<dt>사이즈</dt>
											<dd>
												<div class="opt-size">
													<div><input type="radio" name="cartOptSize" id="sizeChange11" value="11"><label for="sizeChange11">11</label></div>
													<div><input type="radio" name="cartOptSize" id="sizeChange22" value="22" checked><label for="sizeChange22">22</label></div>
													<div><input type="radio" name="cartOptSize" id="sizeChange33" value="33"><label for="sizeChange33">33</label></div>
												</div>
											</dd>
										</dl>
									</div>
									<dl class="mt-15">
										<dt><label for="changeOpt_name4">옵션명</label></dt>
										<dd>
											<div class="select">
												<select id="changeOpt_name4">
													<option value="">선택</option>
												</select>
											</div>
										</dd>
									</dl>
									<div class="btn">
										<button class="btn-basic h-small" type="button"><span>옵션변경</span></button>
										<button class="btn-line h-small" type="button"><span>변경취소</span></button>
									</div>
									<button class="item-del"><span>닫기</span></button>
								</div>
							</td>
						</tr>
						<tr>
							<td><div class="checkbox"><input type="checkbox" id="itemPut04"><label for="itemPut04"></label></div></td>
							<td>
								<div class="goods-in-td">
									<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_01.jpg" alt="썸네일"></a></div>
									<div class="info">
										<p class="brand-nm">BESTI BELLI</p>
										<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
										<p class="opt">품번: SLOAX2520 / 색상 : NAM  / 사이즈 55</p>
										<button class="btn-line h-small" type="button" data-content="menu"><span>옵션변경</span></button>
									</div>
								</div>
							</td>
							<td class="change-quantity">
								<div class="quantity">
									<input type="text" value="1" readonly="">
									<button class="plus"></button>
									<button class="minus"></button>
								</div>
								<div class="btn"><button type="button" class="btn-line h-small"><span>변경</span></button></div>
							</td>
							<td class="txt-toneB">10%</td>
							<td class="txt-toneA">\ 10,955,800</td>
							<td class="flexible-delivery">
								<strong class="txt-toneA">[픽업매장]</strong><div class="pt-5">BESTI BELLI 강남역점<br>2017-01.20</div>
								<div class="btn mt-5"><button class="btn-basic h-small" id="btn-shopPickup"><span>매장변경</span></button></div>
							</td>
							<td>
								<div class="td-btnGroup">
									<button class="btn-basic h-small"><span>좋아요</span></button>
								</div>
							</td>
							<td class="va-t ta-l"><button class="item-del"><span>장바구니에서 삭제</span></button></td>
						</tr>
						<tr data-content="content">
							<td class="reset" colspan="8">
								<div class="opt-change">
									<h4>상품옵션 변경</h4>
									<div>
										<dl class="d-iblock">
											<dt>색상</dt>
											<dd>
												<div class="goods-colorChoice">
													<label class="chip-violet"><input type="radio" name="color_choice" value="VIOLET" checked></label> 
													<label class="chip-red"><input type="radio" name="color_choice" value="RED"></label>
													<label class="chip-green"><input type="radio" name="color_choice" value="GREEn"></label>
												</div>
											</dd>
										</dl>
									</div>
									<dl class="mt-15">
										<dt><label for="changeOpt_name1">옵션명</label></dt>
										<dd>
											<div class="select">
												<select id="changeOpt_name1">
													<option value="">선택</option>
												</select>
											</div>
										</dd>
									</dl>
									<div class="btn">
										<button class="btn-basic h-small" type="button"><span>옵션변경</span></button>
										<button class="btn-line h-small" type="button"><span>변경취소</span></button>
									</div>
									<button class="item-del"><span>닫기</span></button>
								</div>
							</td>
						</tr>
					</tbody>
					
				</table>
			</section><!-- //.cart-section-title -->
			
			<div class="cart-clear">
				<button class="btn-line w100"><span>선택상품 삭제</span></button>
				<button class="btn-line w100"><span>전체삭제</span></button>
			</div>

			<section class="cart-total-price alone mt-40 clear">
				<h4>총 구입금액</h4>
				<dl>
					<dt>택배배송 상품가</dt>
					<dd>\ 30,000,000</dd>
				</dl>
				<span class="txt">+</span>
				<dl>
					<dt>O2O 상품가</dt>
					<dd>\ 0</dd>
				</dl>
				<span class="txt">+</span>
				<dl>
					<dt>배송비</dt>
					<dd>\ 0</dd>
				</dl>
				<dl class="sum">
					<dt>총 주문금액</dt>
					<dd class="point-color fz-18">\ 30,000,000</dd>
				</dl>
				<!-- [D] 임직원가 수정(2017-04-24) -->
				<div class="staff_price">
					<dl class="sum">
						<dt class="point-color">(임직원가)</dt>
						<dd class="point-color fz-18">\ 20,000,000</dd>
					</dl>
				</div>
				<!-- //[D] 임직원가 수정(2017-04-24) -->
			</section><!-- //.cart-total-price -->
			<!-- [D] 임직원가 수정(2017-04-24) -->
			<div class="btnPlace mt-45"><!-- [D] 기본 노출 -->
				<a href="#" class="btn-line h-large w200">쇼핑 계속하기</a>
				<a href="#" class="btn-line h-large w200">선택 상품 구매</a>
				<a href="#" class="btn-point h-large w200">전체 상품 구매</a>
			</div>
			<div class="btnPlace mt-45"><!-- [D] 임직원 구매인 경우 노출 -->
				<a href="#" class="btn-line h-large w200">쇼핑 계속하기</a>
				<a href="#" class="btn-line h-large w200">선택 상품 구매</a>
				<a href="#" class="btn-line h-large w200">선택 상품 임직원 구매</a>
				<a href="#" class="btn-basic h-large w200">전체 상품 구매</a>
				<a href="#" class="btn-point h-large w200">전체 상품 임직원 구매</a>
			</div>
			<!-- //[D] 임직원가 수정(2017-04-24) -->
		</article><!-- //.cart-order-wrap -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
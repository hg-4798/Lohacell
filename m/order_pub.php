<?php
if(strlen($Dir)==0) $Dir="../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include_once(DOC_ROOT."/lib/shopdata.php");
include_once(DOC_ROOT."/lib/basket.class.php");
include_once(DOC_ROOT."/lib/delivery.class.php");

include('./include/top.php');
include('./include/gnb.php');
?>

<div id="page">
<!-- 내용 -->
<main id="content" class="subpage">
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>주문/결제</span>
		</h2>
		<div class="page_step">
			<ul class="clear">
				<li><span class="icon_order_step01"></span>장바구니</li>
				<li class="on"><span class="icon_order_step02"></span>주문하기</li>
				<li><span class="icon_order_step03"></span>주문완료</li>
			</ul>
		</div>
	</section><!-- //.page_local -->

	<section class="orderpage">
		<div class="guest-buy-agree">
			<h4>비회원 주문 약관 동의</h4>
			<div class="check">
				<div class="option-box dir-flow"><label><input type="checkbox" class="check_def"><span>비회원구매 개인정보수집 및 이용동의 (필수)</span></label></div>
				<a href="javascript:;" class="btn_guest_terms">약관보기</a>
			</div>
			<div class="comment">
				<p>회원으로 주문하시면 신규회원 가입 축하 쿠폰 등 더 큰 혜택을 이용하실 수 있습니다.</p>
				<a href="" class="btn-point">회원가입</a>
			</div>
		</div><!-- //.guest-buy-agree -->

		<div class="cart-item-wrap">
			<div class="title-section with-border">
				<h4 class="tit">주문상품 (4)</h4>
			</div>
			<div class="box-sector">
				<div class="sector-inner">
					<div class="goods-item is-mygoods">
						<figure>
							<div class="img"><a href=""><img src="/jayjun/web/static/img/test/@goods_380_03.jpg" alt="상품 썸네일"></a></div>
							<figcaption>
								<p class="code">YHK6427</p>
								<p class="goods-nm">I LIKE EYE SHADOW TRIO</p>
							</figcaption>
						</figure>
					</div>

					<div class="cart-item-opt">
						<div class="opt-wrp">
							<p class="opt"><span class="point-color">[옵션]</span> 컬러_01 길이가 긴 옵션은 밑으로 떨어지겠지 길이가 긴 옵션은 밑으로 떨어지겠지<span class="quantity">1개</span></p>
							<div class="price">
								<strong>10,000</strong><del>12,000</del><span class="discount-color">[17%]</span>
							</div>
						</div>
						<div class="cart-item-price clear">
							<label>주문금액</label>
							<div class="price"><strong>12,000</strong><span class="mileage">500M</span></div>
						</div>
					</div>
				</div>
			</div>
			<!-- //.box-sector -->
			
			<div class="box-sector">
				<div class="sector-inner">
					<div class="goods-item is-mygoods">
						<figure>
							<div class="img"><a href=""><img src="/jayjun/web/static/img/test/@goods_380_03.jpg" alt="상품 썸네일"></a></div>
							<figcaption>
								<p class="code">YHK6427</p>
								<p class="goods-nm">I LIKE EYE SHADOW TRIO</p>
							</figcaption>
						</figure>
					</div>

					<div class="cart-item-opt">
						<div class="opt-wrp">
							<p class="opt"><span class="point-color">[옵션]</span> 컬러_05<span class="quantity">1개</span></p>
							<div class="price">
								<strong>10,000</strong><del>12,000</del><span class="discount-color">[17%]</span>
							</div>
						</div>
						<div class="cart-item-price clear">
							<label>주문금액</label>
							<div class="price"><strong>12,000</strong><span class="mileage">500M</span></div>
						</div>
					</div>

					<div class="cart-item-opt">
						<div class="opt-wrp">
							<p class="opt"><span class="txt-toneA">[추가]</span> 순면화장솜<span class="quantity">2개</span></p>
							<div class="price">
								<strong>10,000</strong><del>12,000</del><span class="discount-color">[17%]</span>
							</div>
						</div>
						<div class="cart-item-price clear">
							<label>주문금액</label>
							<div class="price"><strong>12,000</strong><span class="mileage">500M</span></div>
						</div>
					</div>
				</div>
			</div>
			<!-- //.box-sector -->

			<div class="box-sector">
				<div class="sector-inner">
					<div class="price-sum-total">
						<dl>
							<dt>총 상품금액</dt>
							<dd><strong class="txt-toneA">95,000</strong> <span>원</span></dd>
						</dl>
						<dl>
							<dt>총 할인금액</dt>
							<dd>- 18,800 <span>원</span></dd>
						</dl>
						<dl>
							<dt>배송비
								<div class="tooltip">
									<i class="btn_help" title="배송비설명">?</i>
									<div class="cover" style="width:280px">
										<div class="box">
											<ul class="comment-list is-dash">
												<li>3만원 이상 구매시 무료배송됩니다.</li>
												<li>3만원 미만 구매시 배송비 3,000원이 부과됩니다.</li>
												<li>도서산간 지역은 배송비가 추가 될 수 있습니다.</li>
											</ul>
										</div>
										<button class="btn-close is-large" type="button">닫기</button>
									</div>
								</div>
							</dt>
							<dd>+ 0 <span>원</span></dd>
						</dl>
						<dl class="total">
							<dt>총 주문금액</dt>
							<dd><strong class="point-color">76,200</strong> <span>원</span><p class="mileage">(적립 마일리지 <b>3,810M</b>)</p></dd>
						</dl>
					</div>
				</div>
			</div>
			<!-- //.box-sector -->

			<!-- 사은혜택 -->
			<div class="box-sector">
				<div class="sector-inner">
					<dl class="freebie check">
						<dt>사은혜택(2)</dt>
						<dd>여성 UNI 절개배색 다운 점퍼 구매시 여성 밍크 Fur 쁘띠쁘띠 예쁜 Fur 쁘띠</dd>
						<dd>수지 광고컷 착용 @@ 가죽 뱅글 (랜덤발송)</dd>
					</dl>
				</div>
			</div>
			<!-- //사은혜택 -->

			<!-- 할인적용(회원) -->
			<div class="box-sector">
				<div class="sector-inner">
					<div class="title-section">
						<h4 class="tit">할인 적용</h4>
					</div>
					<table class="th-left discount_apply">
						<caption>할인 적용</caption>
						<colgroup>
							<col style="width:90px">
							<col style="width:auto">
							<col style="width:76px">
						</colgroup>
						<tbody>
							<tr>
								<th scope="row"><label>상품 쿠폰</label></th>
								<td>
									<span class="mr-10"><strong class="discount-color">- 3,500</strong> 원</span>
									<div class="board-attention">적용 가능 : <strong>9</strong>장</div>
								</td>
								<td><a href="javascript:;" class="btn-line h-input">쿠폰취소</a></td>
							</tr>
							<tr>
								<th scope="row"><label>장바구니 쿠폰</label></th>
								<td>
									<span class="mr-10"><strong class="discount-color">- 0</strong> 원</span>
									<div class="board-attention">적용 가능 : <strong>0</strong>장</div>
								</td>
								<td><a href="javascript:;" class="btn-basic h-input btn_coupon_use">쿠폰적용</a></td>
							</tr>
							<tr>
								<th scope="row"><label>무료배송 쿠폰</label></th>
								<td>
									<span class="mr-10"><strong class="discount-color">- 0</strong> 원</span>
									<div class="board-attention">적용 가능 : <strong>0</strong>장</div>
								</td>
								<td><a href="javascript:;" class="btn-basic h-input btn_coupon_use">쿠폰적용</a></td>
							</tr>
							<tr>
								<th scope="row"><label>마일리지</label></th>
								<td colspan="2">
									<input type="text" title="마일리지 입력"> <span class="mr-5">M</span>
									<div class="tooltip mr-10">
										<i class="btn_help" title="마일리지설명">?</i>
										<div class="cover" style="width: 300px; display: none;">
											<div class="box">
												<ul class="comment-list is-dash">
													<li>마일리지는 상품금액 30,000원 이상 결제시 사용 가능합니다.</li>
													<li>최소 100M이상 부터, 100M 단위로 사용 가능합니다. (1P = 1원)</li>
													<li>구매 금액의 최대 10%까지 사용 가능합니다.</li>
													<li>적립 마일리지 유효기간은 : 적립일 기준 2 년이며, 소멸된 미사용 적립금은 보원 또는 보상하지 않습니다.</li>
												</ul>
											</div>
											<button class="btn-close is-large" type="button">닫기</button>
										</div>
									</div>
									<div class="board-attention">보유 : <strong>10,000</strong> M</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>포인트</label></th>
								<td colspan="2">
									<input type="text" title="포인트 입력"> <span class="mr-5">P</span>
									<div class="tooltip mr-10">
										<i class="btn_help" title="포인트설명">?</i>
										<div class="cover" style="width: 300px; display: none;">
											<div class="box">
												<ul class="comment-list is-dash">
													<li>마일리지는 상품금액 30,000원 이상 결제시 사용 가능합니다.</li>
													<li>최소 100M이상 부터, 100M 단위로 사용 가능합니다. (1P = 1원)</li>
													<li>구매 금액의 최대 10%까지 사용 가능합니다.</li>
													<li>적립 마일리지 유효기간은 : 적립일 기준 2 년이며, 소멸된 미사용 적립금은 보원 또는 보상하지 않습니다.</li>
												</ul>
											</div>
											<button class="btn-close is-large" type="button">닫기</button>
										</div>
									</div>
									<div class="board-attention">보유 : <strong>5,000</strong> P</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- //할인적용(회원) -->

			<!-- 주문고객(비회원) -->
			<div class="delivery-check">
				<div class="title-section is-function with-border">
					<h4 class="tit">주문고객</h4>
					<div class="function-area"><a href="javascript:;" class="btn-line btn_change_buyer">주문고객 정보 변경</a></div>
				</div>
				<div class="inner">
					<ul>
						<li class="txt-toneC">주문자 정보를 입력해 주세요.</li>
					</ul>
				</div>
			</div>
			<!-- //주문고객(비회원) -->
			
			<!-- 주문고객(회원) -->
			<div class="delivery-check">
				<div class="title-section is-function with-border">
					<h4 class="tit">주문고객</h4>
					<div class="function-area"><a href="javascript:;" class="btn-line btn_change_buyer">주문고객 정보 변경</a></div>
				</div>
				<div class="inner">
					<div class="delivery-name"><strong>홍길동</strong></div>
					<ul>
						<li>010-1234-5655 <span class="txt-toneC" style="padding:0 10px">/</span> 031-5123-2512</li>
						<li>honggildong@hotmail.com</li>
					</ul>
				</div>
			</div>
			<!-- //주문고객(회원) -->
			
			<!-- 배송지정보(비회원) -->
			<div class="delivery-check">
				<div class="title-section is-function with-border">
					<h4 class="tit">배송지 정보</h4>
					<div class="option-box dir-flow"><label><input type="checkbox" class="check_def" title="주문고객 정보와 동일"><span>주문고객 정보와 동일</span></label></div>
					<div class="function-area"><a href="javascript:;" class="btn-line btn_change_delivery">배송지 변경</a></div>
				</div>
				<div class="inner">
					<ul>
						<li class="txt-toneC">배송지를 입력해 주세요.</li>
					</ul>
				</div>
			</div>
			<!-- //배송지정보(비회원) -->
			
			<!-- 배송지정보(회원) -->
			<div class="delivery-check">
				<div class="title-section is-function with-border">
					<h4 class="tit">배송지 정보</h4>
					<div class="option-box dir-flow"><label><input type="checkbox" class="check_def" title="주문고객 정보와 동일"><span>주문고객 정보와 동일</span></label></div>
					<div class="function-area"><a href="javascript:;" class="btn-line btn_change_delivery">배송지 변경</a></div>
				</div>
				<div class="inner">
					<div class="delivery-name">
						<strong>홍길동</strong>
						<a href="javascript:;" class="btn-basic btn_shipping_list">배송지목록</a>
					</div>
					<ul>
						<li>12345<br>서울시 강남구 10논현로 24길 234 길동빌딩 5층</li>
						<li>010-1234-5655 <span class="txt-toneC" style="padding:0 10px">/</span> 031-5123-2512</li>
						<li>
							<select class="select_line w100-per" title="배송시 요청사항 선택">
								<option>배송시 요청사항 선택해 주세요.</option>
								<option>빠른 배송 부탁드립니다.</option>
								<option>배송 전 연락 바랍니다.</option>
							</select>
						</li>
					</ul>
				</div>
			</div>
			<!-- //배송지정보(회원) -->
			
			<!-- 결제금액 -->
			<div class="price-sum-total with-bg">
				<h4 class="title">결제금액</h4>
				<div class="inner">
					<dl>
						<dt>총 상품금액</dt>
						<dd><strong>95,000</strong> 원</dd>
					</dl>
					<dl>
						<dt>할인</dt>
						<dd class="discount-color">- 18,800 원</dd>
					</dl>
					<dl>
						<dt>쿠폰할인</dt>
						<dd class="discount-color">- 0 원</dd>
					</dl>
					<dl>
						<dt>마일리지 사용</dt>
						<dd class="discount-color">- 0 원</dd>
					</dl>
					<dl>
						<dt>포인트 사용</dt>
						<dd class="discount-color">- 0 원</dd>
					</dl>
					<dl>
						<dt>배송비</dt>
						<dd>+ 0 원</dd>
					</dl>
					<dl class="total">
						<dt>총 결제금액</dt>
						<dd><strong class="point-color">76,200</strong> 원</dd>
					</dl>
				</div>
			</div>
			<!-- //결제금액 -->
			
			<div class="payment-type">
				<!-- 결제수단 -->
				<div class="title-section with-border"><h4 class="tit">결제수단</h4></div>
				<div class="inner">
					<div class="payment-tab" data-ui="TabMenu">
						<div class="divide-box-wrap three">
							<ul class="tabs-menu divide-box">
								<li><button type="button" data-content="menu" class="active" title="선택됨"><span><i class="icon-pay-credit"></i>신용카드</span></button></li>
								<li><button type="button" data-content="menu" class=""><span><i class="icon-pay-bank"></i>실시간 계좌이체</span></button></li>
								<li><button type="button" data-content="menu" class=""><span><i class="icon-pay-account"></i>무통장입금 (가상계좌)</span></button></li>
							</ul>
						</div>
						<div class="tabs-content">
							<div class="active" data-content="content">
								<ul class="dash-list">
									<li>신용카드 결제시 '카드사혜택' 버튼을 클릭하시면 무이자할부/청구할인/즉시할인에 대한 정보를 보실 수 있습니다.</li>
									<li>체크카드, 법인카드의 경우 무이자 할부행사에서 제외됩니다.</li>
									<li>신용카드로 결제하시는 최종 결제 금액이 기준금액 미만이거나, 그 외 무이자 할부가 되지 않는 기타 신용카드를 사용하시는 <br>경우는 유이자 할부로 결제되오니 반드시 참고하시기 바랍니다.</li>
								</ul>
								<a href="javascript:;" class="btn-point mt-10">카드사 혜택</a>
							</div>
							<div data-content="content" class="">
								<ul class="dash-list">
									<li>결제와 동시에 즉시 이체되며, 전체 주문 취소 시 당일 입금되며, 부분취소 시 익일 입금됩니다.</li>
									<li>계좌이체 수수료는 별도로 부과되지 않습니다.</li>
									<li>결제 시 입력한 본인의 계좌에서 즉시 이체 처리되며, 처리 과정에서 문제 발생 시 취소 처리와 함께 입력한 계좌로 즉시 재입금 처리 됩니다.</li>
									<li>은행 사정에 따라 서비스 가능 시간이 있으니, 23시 이후에는 은행 별 이용 가능시간을 미리 확인하신 후 결제를 진행해 주세요.</li>
								</ul>
							</div>
							<div data-content="content" class="">
								<ul class="dash-list">
									<li>계좌입금 시 입금자명은 회원명과 동일하게 입력하여 주시기 바랍니다.</li>
									<li>계좌입금 시 금액은 결제 금액과 일치해야만 입금이 되므로 입금금액을 꼭 확인하여 주시기 바랍니다</li>
								</ul>
								<table class="th-left mt-15">
									<caption>무통장입금 정보 입력</caption>
									<colgroup>
										<col style="width:90px">
										<col style="width:auto">
									</colgroup>
									<tbody>
										<tr>
											<th scope="row"><label for="deposit_person">입금자명</label></th>
											<td>
												<input type="text" id="deposit_person" title="입금자명 입력자리" style="width:150px">
												<p class="board-attention">* 실제 무통장입금 예금주 이름</p>
											</td>
										</tr>
										<tr>
											<th scope="row"><label>입금정보</label></th>
											<td>
												<p style="line-height:1.4">은행 : KB국민은행</p>
												<p style="line-height:1.4">계좌번호 : 39919011059152</p>
												<p style="line-height:1.4">예금주 : 게스홀딩스코리아유한회사</p>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- //결제수단 -->
				<!-- 주문동의 -->
				<div class="inner">
					<div class="agree">
						<div class="title-section is-function">
							<h5 class="tit">주문동의</h5>
							<div class="function-area">
								<div class="option-box dir-flow"><label><input type="checkbox" class="check_def" title="주문동의"><span>동의합니다</span></label></div>
							</div>
						</div>
						<p>주문할 상품의 상품명, 상품가격, 배송정보를 확인하였으며, 구매에 동의 하시겠습니까? (전자상거래법 제8조 제2항)</p>
					</div>
				</div>
				<!-- //주문동의 -->
			</div>
			
			<div class="box-sector no-line">
				<div class="sector-inner">
					<div class="btn_area no-margin">
						<ul class="ea2">
							<li><a href="javascript:;" class="btn-line h-large">장바구니</a></li>
							<li><a href="javascript:;" class="btn-point h-large">결제하기</a></li>
						</ul>
					</div>
				</div>
			</div>
			
			<div class="attention">
				<div class="title">주문 전 확인사항 <a class="show">보기</a></div>
				<ul>
					<li>쿠폰적용할인/마일리지 및 포인트 사용은 회원만 이용하실 수 있습니다.</li>
					<li>비회원 및 임직원 상품 구매시는 쿠폰적용할인/마일리지 및 포인트 사용이 불가합니다.</li>
					<li>상품구매시 마일리지는 iKNOWiONE 회원만 적립 가능합니다.</li>
				</ul>
			</div>
		</div><!-- //.cart-item-wrap -->
		
	</section><!-- //.orderpage -->

	<!-- 비회원구매이용약관팝업 -->
	<section class="pop_layer layer_guest_terms">
		<div class="inner">
			<h3 class="title">비회원구매 개인정보수집 및 이용약관<button type="button" class="btn_close">닫기</button></h3>
			<div class="layer-contents">
				<div class="guest-terms">
					<div class="editor-area">
						<p><strong>개인정보 취급방침</strong></p>
						<p></p>
						<p>제1조(목적)</p>
						<p></p>
						<p>이 약관은 게스홀딩스코리아 유한회사(이하 “회사"라 한다)가 운영하는 사이버 몰( www.guesskorea.com, 이하 “몰"이라 한다)에서 제공하는 인터넷 관련 서비스(이하 "서비스"라 한다)를 이용함에 있어 “몰"과 이용자의 권리•의무 및 책임사항을 규정함을 목적으로 합니다. </p>
					</div>
					<div class="btn_area mt-20">
						<ul>
							<li><button type="button" class="btn-point h-large">확인</button></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- //비회원구매이용약관팝업 -->
	
	<!-- 쿠폰적용팝업 -->
	<section class="pop_layer layer_coupon_use">
		<div class="inner">
			<h3 class="title">쿠폰 적용<button type="button" class="btn_close">닫기</button></h3>
			<div class="layer-contents">
				<div class="cart-item-wrap">
					<div class="title-section with-border mt-15">
						<span class="comment">※ 상품쿠폰과 장바구니 쿠폰은 동시 사용이 불가능합니다.</span>
					</div>

					<div class="box-sector">
						<div class="sector-inner">
							<div class="goods-item is-mygoods">
								<figure>
									<div class="img"><a href=""><img src="/jayjun/web/static/img/test/@goods_380_03.jpg" alt="상품 썸네일"></a></div>
									<figcaption>
										<p class="code">YHK6427</p>
										<p class="goods-nm">I LIKE EYE SHADOW TRIO</p>
									</figcaption>
								</figure>
							</div>

							<div class="cart-item-opt">
								<div class="opt-wrp">
									<p class="opt"><span class="point-color">[옵션]</span> 컬러_05</p>
									<select class="select_line" title="쿠폰 선택">
										<option value="">쿠폰을 선택하세요!</option>
										<option value="" selected>[9월] #I LIKE 가을 신상품 10% 할인</option>
										<option value="">첫 구매 10 % 할인</option>
										<option value="">신규 브랜드 런칭 기념 10% 할인</option>
									</select>
									<div class="discount-price">
										<label class="v-hidden">할인금액</label>
										<strong class="discount-color">- 800</strong> 원
									</div>
								</div>
							</div>

							<div class="cart-item-opt">
								<div class="opt-wrp">
									<p class="opt"><span class="txt-toneA">[추가]</span> 순면화장솜</p>
									<select class="select_line" title="쿠폰 선택">
										<option value="">쿠폰을 선택하세요!</option>
										<option value="">[9월] #I LIKE 가을 신상품 10% 할인</option>
										<option value="" selected>첫 구매 10 % 할인</option>
										<option value="">신규 브랜드 런칭 기념 10% 할인</option>
									</select>
									<div class="discount-price">
										<label class="v-hidden">할인금액</label>
										<strong class="discount-color">- 800</strong> 원
									</div>
								</div>
							</div>
						</div>
					</div>
			
					<div class="box-sector">
						<div class="sector-inner">
							<div class="goods-item is-mygoods">
								<figure>
									<div class="img"><a href=""><img src="/jayjun/web/static/img/test/@goods_380_03.jpg" alt="상품 썸네일"></a></div>
									<figcaption>
										<p class="code">YHK6427</p>
										<p class="goods-nm">I LIKE EYE SHADOW TRIO</p>
									</figcaption>
								</figure>
							</div>

							<div class="cart-item-opt">
								<div class="opt-wrp">
									<p class="opt"><span class="point-color">[옵션]</span> 컬러_01 길이가 긴 옵션은 밑으로 떨어지겠지 길이가 긴 옵션은 밑으로 떨어지겠지</p>
									<select class="select_line" title="쿠폰 선택">
										<option value="">쿠폰을 선택하세요!</option>
										<option value="">[9월] #I LIKE 가을 신상품 10% 할인</option>
										<option value="">첫 구매 10 % 할인</option>
										<option value="">신규 브랜드 런칭 기념 10% 할인</option>
									</select>
									<div class="discount-price">
										<label class="v-hidden">할인금액</label>
										<strong class="discount-color">- 0</strong> 원
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="box-sector">
						<div class="sector-inner">
							<div class="cart-item-opt alltype">
								<div class="opt-wrp">
									<p class="opt"><strong>장바구니쿠폰</strong> (중복 사용 불가)</p>
									<select class="select_line" title="쿠폰 선택" disabled>
										<option value="">적용 가능한 쿠폰이 없습니다.</option>
									</select>
									<div class="discount-price">
										<label class="v-hidden">할인금액</label>
										<strong class="discount-color">- 0</strong> 원
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="box-sector no-line">
						<div class="sector-inner">
							<div class="cart-item-opt alltype">
								<div class="opt-wrp">
									<p class="opt"><strong>배송비 쿠폰</strong></p>
									<select class="select_line" title="쿠폰 선택">
										<option value="">쿠폰을 선택하세요!</option>
										<option value="" selected>VVIP 무료 배송 쿠폰</option>
									</select>
									<div class="discount-price">
										<label class="v-hidden">할인금액</label>
										<span class="discount-color">배송비 무료</span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="price-sum-total with-bg">
						<dl class="total">
							<dt>총 할인금액</dt>
							<dd><strong class="discount-color">- 1,600</strong> <span>원</span></dd>
						</dl>
					</div>

					<div class="box-sector no-line">
						<div class="sector-inner">
							<div class="btn_area mt-5">
								<ul class="ea2">
									<li><a href="javascript:;" class="btn-line h-large">취소</a></li>
									<li><button type="submit" class="btn-point h-large">적용</button></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- //쿠폰적용팝업 -->
	
	<!-- 주문고객정보변경팝업 -->
	<section class="pop_layer layer_change_buyer">
		<div class="inner">
			<h3 class="title">주문고객 정보 변경<button type="button" class="btn_close">닫기</button></h3>
			<div class="layer-contents regist_lyr">
				<div class="title-section">
					<span class="comment">입력한 정보는 이번 주문에만 적용됩니다.</span>
				</div>
				<table class="th-left">
					<caption>주문고객 정보 변경</caption>
					<colgroup>
						<col style="width:80px">
						<col style="width:auto">
					</colgroup>
					<tbody>
						<tr>
							<th scope="row"><label class="required" for="pop_buyer_name">주문자명</label></th>
							<td>
								<input type="text" id="pop_buyer_name" class="w100-per" title="주문자명 입력자리" placeholder="이름을 입력하세요.">
							</td>
						</tr>
						<tr>
							<th scope="row" class="va-top"><label class="required" for="pop_buyer_email">이메일</label></th>
							<td>
								<div class="email-cover">
									<div><input type="text" title="이메일 아이디 입력" id="pop_buyer_email"></div>
									<div><input type="text" title="이메일 도메인 직접입력"></div>
								</div>
								<div style="margin-top:3px">
									<select title="도메인 선택" class="select_line w100-per">
										<option>직접입력</option>
										<option>naver.com</option>
										<option>직접입력</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<th scope="row" class="va-top"><label class="required" for="pop_buyer_tel1">휴대전화</label></th>
							<td>
								<div class="tel-cover">
									<div>
										<select title="휴대전화 앞자리 선택" id="pop_buyer_tel1" class="select_line">
											<option>010</option>
										</select>
									</div>
									<div><input type="text" title="휴대전화 번호 가운데 입력자리"></div>
									<div><input type="text" title="휴대전화 번호 마지막 입력자리"></div>
								</div>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="pop_buyer_tel2">전화번호</label></th>
							<td>
								<div class="tel-cover">
									<div>
										<select title="전화번호 앞자리 선택" id="pop_buyer_tel2" class="select_line">
											<option>선택</option>
										</select>
									</div>
									<div><input type="text" title="전화번호 가운데 입력자리"></div>
									<div><input type="text" title="전화번호 마지막 입력자리"></div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="btn_area">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-large">취소</a></li>
						<li><button type="submit" class="btn-point h-large">적용</button></li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<!-- //주문고객정보변경팝업 -->
	
	<!-- 배송지변경팝업 -->
	<section class="pop_layer layer_change_delivery">
		<div class="inner">
			<h3 class="title">배송지 변경<button type="button" class="btn_close">닫기</button></h3>
			<div class="layer-contents regist_lyr">
				<div class="title-section">
					<span class="comment">새로운 배송지 정보를 입력하세요.</span>
				</div>
				<table class="th-left">
					<caption>배송지 정보 변경</caption>
					<colgroup>
						<col style="width:80px">
						<col style="width:auto">
					</colgroup>
					<tbody>
						<tr>
							<th scope="row"><label class="required" for="pop_buyer_name2">받는분</label></th>
							<td>
								<input type="text" id="pop_buyer_name2" class="w100-per" title="주문자명 입력자리" placeholder="이름을 입력하세요.">
							</td>
						</tr>
						<tr>
							<th scope="row" class="va-top"><label class="required" for="pop_delivery_adress">주소</label></th>
							<td>
								<div class="form-multi">
									<input type="text" id="pop_delivery_adress" title="우편번호 출력자리" style="width:145px">
									<a href="" class="btn-basic h-input" style="padding:0 6px">우편번호찾기</a>
								</div>
								<div class="form-multi"><input type="text" class="w100-per" title="기본 주소 위치"></div>
								<div class="form-multi"><input type="text" class="w100-per" title="상세 주소 입력" placeholder="상세 주소를 입력하세요."></div>
							</td>
						</tr>
						<tr>
							<th scope="row" class="va-top"><label class="required" for="pop_buyer_tel3">휴대전화</label></th>
							<td>
								<div class="tel-cover">
									<div>
										<select title="휴대전화 앞자리 선택" id="pop_buyer_tel3" class="select_line">
											<option>010</option>
										</select>
									</div>
									<div><input type="text" title="휴대전화 번호 가운데 입력자리"></div>
									<div><input type="text" title="휴대전화 번호 마지막 입력자리"></div>
								</div>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="pop_buyer_tel4">전화번호</label></th>
							<td>
								<div class="tel-cover">
									<div>
										<select title="전화번호 앞자리 선택" id="pop_buyer_tel4" class="select_line">
											<option>선택</option>
										</select>
									</div>
									<div><input type="text" title="전화번호 가운데 입력자리"></div>
									<div><input type="text" title="전화번호 마지막 입력자리"></div>
								</div>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="pop_delivery_message">배송메모</label></th>
							<td>
								<select title="배송메모 선택" id="pop_delivery_message" class="select_line w100-per">
									<option>배송시 요청사항 선택해 주세요.</option>
										<option>빠른 배송 부탁드립니다.</option>
										<option>배송 전 연락 바랍니다.</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="btn_area">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-large">취소</a></li>
						<li><button type="submit" class="btn-point h-large">적용</button></li>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<!-- //배송지변경팝업 -->
	
	<!-- 배송지목록팝업 -->
	<div class="pop_layer layer_shipping_list">
		<div class="inner"> 
			<h3 class="title">배송지 목록<button type="button" class="btn_close">닫기</button></h3>
			<div class="layer-contents">
				
				<div class="delivery-before">
					<div class="title-section">
						<span class="comment">배송지 목록은 최대 5개까지 등록 가능합니다. 배송지 정보 수정/삭제는 마이페이지>배송지관리에서 가능합니다.</span>
					</div>
					<table class="th-top">
						<caption>배송지 목록</caption>
						<colgroup>
							<col style="width:40px">
							<col style="width:75px">
							<col style="width:auto">
						</colgroup>
						<thead class="v-hidden">
							<tr>
								<th scope="col">선택</th>
								<th scope="col">배송지명</th>
								<th scope="col">배송지</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><div class="option-box is-alone"><label><input type="radio" class="radio_def" name="pop_delivery_list" checked=""><span>선택</span></label></div></td>
								<td><p class="point-color">[기본]</p> 우리집</td>
								<td class="ta-l">
									<ul class="address">
										<li class="name"><strong>홍길동</strong><span>010-1111-1111</span></li>
										<li><p>경기도 부천시 괴안동 소사아파트 2동 102호</p></li>
									</ul>
								</td>
							</tr>
							<tr>
								<td><div class="option-box is-alone"><label><input type="radio" class="radio_def" name="pop_delivery_list"><span>선택</span></label></div></td>
								<td>회사</td>
								<td class="ta-l">
									<ul class="address">
										<li class="name"><strong>홍길동</strong><span>010-1111-1111</span></li>
										<li><p>경기도 부천시 괴안동 소사아파트 2동 102호</p></li>
									</ul>
								</td>
							</tr>
							<tr>
								<td><div class="option-box is-alone"><label><input type="radio" class="radio_def" name="pop_delivery_list"><span>선택</span></label></div></td>
								<td>회사</td>
								<td class="ta-l">
									<ul class="address">
										<li class="name"><strong>홍길동</strong><span>010-1111-1111</span></li>
										<li><p>경기도 부천시 괴안동 소사아파트 2동 102호</p></li>
									</ul>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="btn_area">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-large">취소</a></li>
						<li><button type="submit" class="btn-point h-large">적용</button></li>
					</ul>
				</div>
			</div>
		</div> 
	</div>
	<!-- //배송지목록팝업 -->
		
</main>
<!-- //내용 -->

<? include('./include/bottom.php'); ?>

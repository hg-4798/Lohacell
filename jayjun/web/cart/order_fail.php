<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="cartOrder-page">

		<article class="cart-order-wrap">
			<header class="progess-title">
				<h2>주문/결제</h2>
				<ul class="flow clear">
					<li><div><i></i><span>STEP 1</span>장바구니</div></li>
					<li><div><i></i><span>STEP 2</span>주문하기</div></li>
					<li class="active"><div><i></i><span>STEP 3</span>주문완료</div></li>
				</ul>
			</header>
			
			<div class="orderEnd-result mt-80">
				<p>결제가 취소되었습니다.</p>
			</div>

			<section class="mt-60">
				<header class="cart-section-title">
					<h3>주문상품</h3>
				</header>
				<table class="th-top">
					<caption>주문 상품 확인</caption>
					<colgroup>
						<col style="width:auto">
						<col style="width:90px">
						<col style="width:140px">
						<col style="width:170px">
						<col style="width:136px">
					</colgroup>
					<thead>
						<tr>
							<th scope="col">상품정보</th>
							<th scope="col">수량</th>
							<th scope="col">판매가</th>
							<th scope="col">배송정보</th>
							<th scope="col">주문상태</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="5" class="reset">
								<div class="cart-total-price clear">
									<dl>
										<dt>상품합계</dt>
										<dd>\ 30,000,000</dd>
									</dl>
									<span class="txt point-color">-</span>
									<dl class="point-color">
										<dt>할인</dt>
										<dd>\ 0</dd>
									</dl>
									<span class="txt">+</span>
									<dl>
										<dt>배송비</dt>
										<dd>\ 0</dd>
									</dl>
									<dl class="sum">
										<dt>합계</dt>
										<dd class="fz-20 point-color fw-normal">\ 30,000,000</dd>
									</dl>
								</div>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<td class="pl-25">
								<div class="goods-in-td">
									<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_01.jpg" alt="썸네일"></a></div>
									<div class="info">
										<p class="brand-nm">BESTI BELLI</p>
										<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
										<p class="opt">품번: SLOAX2520 / 색상 : NAM  / 사이즈 55</p>
									</div>
								</div>
							</td>
							<td>2</td>
							<td class="txt-toneB">10,000 P</td>
							<td class="flexible-delivery">
								<div>
									<strong class="txt-toneA">[발송매장]</strong>
								</div>
								<strong class="txt-toneA">\3,000</strong><div class="pt-5">VIKI 강남역점</div>
							</td>
							<td class="txt-toneA fz-13">결제취소</td>
						</tr>
						<tr>
							<td class="pl-25">
								<div class="goods-in-td">
									<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_02.jpg" alt="썸네일"></a></div>
									<div class="info">
										<p class="brand-nm">BESTI BELLI</p>
										<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
										<p class="opt">품번: SLOAX2520 / 색상 : NAM  / 사이즈 55</p>
									</div>
								</div>
							</td>
							<td>2</td>
							<td class="txt-toneB">10,000 P</td>
							<td class="flexible-delivery">
								<div>
									<strong class="txt-toneA">[픽업매장]</strong>
								</div>
								<strong class="txt-toneA">\3,000</strong><div class="pt-5">VIKI 강남역점</div>
							</td>
							<td class="txt-toneA fz-13">결제취소</td>
						</tr>
						<tr>
							<td class="pl-25">
								<div class="goods-in-td">
									<div class="thumb-img"><a href="#"><img src="../static/img/test/@goods_thumb300_03.jpg" alt="썸네일"></a></div>
									<div class="info">
										<p class="brand-nm">BESTI BELLI</p>
										<p class="goods-nm">솔리드 심플 벨티트 자켓 베이직 스타일</p>
										<p class="opt">품번: SLOAX2520 / 색상 : NAM  / 사이즈 55</p>
									</div>
								</div>
							</td>
							<td>2</td>
							<td class="txt-toneB">10,000 P</td>
							<td class="flexible-delivery">
								<div>
									<strong class="txt-toneA">[택배발송]</strong>
								</div>
								<strong class="txt-toneA">\3,000</strong><div class="pt-5">VIKI 강남역점</div>
							</td>
							<td class="txt-toneA fz-13">결제취소</td>
						</tr>
					</tbody>
					
				</table>
			</section><!-- //브랜드 주문상품 -->

			<div class="orderEnd-info clear mt-60">
				<section class="inner-payment">
					<header class="cart-section-title">
						<h3>할인 및 결제정보</h3>
					</header>
					<table class="th-left">
						<caption>할인 및 결제 확인</caption>
						<colgroup>
							<col style="width:168px">
							<col style="width:auto">
						</colgroup>
						<tbody>
							<tr>
								<th scope="row"><label>총 상품금액</label></th>
								<td>\ 10,000,000</td>
							</tr>
							<tr>
								<th scope="row"><label>포인트 사용</label></th>
								<td class="point-color">- 10,000 P</td>
							</tr>
							<tr>
								<th scope="row"><label>E포인트 사용</label></th>
								<td class="point-color">- 10,000 P</td>
							</tr>
							<tr>
								<th scope="row"><label>쿠폰할인</label></th>
								<td class="point-color">- \ 10,000</td>
							</tr>
							<tr>
								<th scope="row"><label>배송비</label></th>
								<td>\ 2,500</td>
							</tr>
							<tr>
								<th scope="row"><label>실 결제금액</label></th>
								<td class="fz-14 fw-bold point-color">\ 8,000,000</td>
							</tr>
							<tr>
								<th scope="row"><label>결제방법</label></th>
								<td class="fz-13">신용카드(승인일자: -) <strong class="fw-700 txt-toneA">취소일자: 2017.01.16 16:00:57</strong></td>
							</tr>
							<tr>
								<th scope="row"><label>취소사유</label></th>
								<td class="fz-13">카드한도초과</td>
							</tr>
						</tbody>
					</table>
				</section><!-- //.inner-payment -->
				<section class="inner-delivery">
					<header class="cart-section-title">
						<h3>배송지 정보</h3>
					</header>
					<table class="th-left">
						<caption>배송지 정보 확인</caption>
						<colgroup>
							<col style="width:168px">
							<col style="width:auto">
						</colgroup>
						<tbody>
							<tr>
								<th scope="row"><label>받는사람</label></th>
								<td>\ 10,000,000</td>
							</tr>
							<tr>
								<th scope="row"><label>휴대전화</label></th>
								<td>010-1254-2121</td>
							</tr>
							<tr>
								<th scope="row"><label>전화번호(선택)</label></th>
								<td>02-123-1234</td>
							</tr>
							<tr>
								<th scope="row"><label>주소</label></th>
								<td>
									<ul class="input-multi">
										<li>[12345]</li>
										<li>서울 강남구 강남대로 238-11</li>
										<li>높은 빌딩 92층 D라인 경비실 앞 고양이집</li>
									</ul>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>배송 요청사항</label></th>
								<td>부재시 경비실에 맡겨주세요.</td>
							</tr>
						</tbody>
					</table>
				</section><!-- //.inner-delivery -->
			</div><!-- //.orderEnd-info -->
			<div class="btnPlace mt-40">
				<a class="btn-line h-large" href="#" style="width:220px">주문내역 확인하기</a>
				<a class="btn-point h-large" href="#"  style="width:220px">쇼핑 계속하기</a>
			</div>

		</article><!-- //.cart-order-wrap -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
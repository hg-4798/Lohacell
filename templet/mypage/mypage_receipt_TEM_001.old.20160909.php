<div id="contents">
	<div class="inner">
		<main class="mypage_wrap"><!-- 페이지 성격에 맞게 클래스 구분 -->

			<!-- LNB -->
			<? include  "mypage_TEM01_left.php";  ?>
			<!-- //LNB -->

			<article class="mypage_content">
				<section class="mypage_main">
					<div class="title_box_border">
						<h3>증빙서류 발급</h3>
					</div>
					<!-- 증빙서류 리스트 -->
					<div class="order_list_wrap voucher mt-50">
						<div class="order_right">
							<div class="total">총 15건</div>
							<div class="date-sort clear">
								<div class="type month">
									<p class="title">기간별 조회</p>
									<button type="button" class="on"><span>1개월</span></button>
									<button type="button"><span>3개월</span></button>
									<button type="button"><span>6개월</span></button>
								</div>
								<div class="type calendar">
									<p class="title">일자별 조회</p>
									<div class="box">
										<input type="text" title="일자별 시작날짜" value="2016-06-21">
										<button type="button">달력 열기</button>
									</div>
									<span>-</span>
									<div class="box">
										<input type="text" title="일자별 시작날짜" value="2016-06-21">
										<button type="button">달력 열기</button>
									</div>
								</div>
								<button type="button" class="btn-go"><span>검색</span></button>
							</div>
						</div>
						<table class="th_top">
							<caption></caption>
							<colgroup>
								<col style="width:20%">
								<col style="width:10%">
								<col style="width:auto">
								<col style="width:12%">
								<col style="width:12%">
								<col style="width:12%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">주문번호</th>
									<th scope="col">주문날짜</th>
									<th scope="col">상품정보</th>
									<th scope="col">결제수단</th>
									<th scope="col">결제금액</th>
									<th scope="col">영수증</th>
								</tr>
							</thead>
							<tbody>
								<tr class="bold">
									<td class="order_num"><a href="../mypage/order_detail.php">2016-07-13-15462035112A</a></td>
									<td class="date">2016-07-13</td>
									<td class="goods_info">
										<a href="javascript:void(0)">
											<img src="../static/img/test/@mypage_main_order1.jpg" alt="마이페이지 상품 썸네일 이미지">
											<ul>
												<li>[나이키]</li>
												<li>루나에픽 플라이니트 MEN 신발 러닝</li>
											</ul>
										</a>
									</td>
									<td>무통장 입금</td>
									<td class="payment">219,000원</td>
									<td>
										<div class="btn_voucher">
											<p><a href="javascript:void(0)" class="btn-line btn-cash-receipt">현금영수증</a></p>
											<p><a href="javascript:void(0)" class="btn-line btn-tax-invoice">세금계산서</a></p>
										</div>
									</td>
								</tr>
								<tr class="bold">
									<td class="order_num"><a href="javascript:void(0)">2016-07-13-15462035112B</a></td>
									<td class="date">2016-07-13</td>
									<td class="goods_info">
										<a href="javascript:void(0)">
											<img src="../static/img/test/@mypage_main_order2.jpg" alt="마이페이지 상품 썸네일 이미지">
											<ul>
												<li>[나이키]</li>
												<li>나이키 에어 줌 페가수스33</li>
											</ul>
										</a>
									</td>
									<td>카드결제</td>
									<td class="payment">139,000원</td>
									<td>
										<div class="btn_voucher">
											<p><a href="javascript:void(0)" class="btn-line">신용카드 매출전표</a></p>
											<p><a href="javascript:void(0)" class="btn-line btn-tax-invoice">세금계산서</a></p>
										</div>
									</td>
								</tr>
								<tr class="bold">
									<td class="order_num"><a href="javascript:void(0)">2016-07-13-15462035112A</a></td>
									<td class="date">2016-07-13</td>
									<td class="goods_info">
										<a href="javascript:void(0)">
											<img src="../static/img/test/@mypage_main_order1.jpg" alt="마이페이지 상품 썸네일 이미지">
											<ul>
												<li>[나이키]</li>
												<li>루나에픽 플라이니트 MEN 신발 러닝</li>
											</ul>
										</a>
									</td>
									<td>휴대폰 결제</td>
									<td class="payment">219,000원</td>
									<td>
										<div class="btn_voucher">
											<p><a href="javascript:void(0)" class="btn-line btn-cash-receipt">현금영수증</a></p>
										</div>
									</td>
								</tr>
								<tr class="bold">
									<td class="order_num"><a href="javascript:void(0)">2016-07-13-15462035112B</a></td>
									<td class="date">2016-07-13</td>
									<td class="goods_info">
										<a href="javascript:void(0)">
											<img src="../static/img/test/@mypage_main_order2.jpg" alt="마이페이지 상품 썸네일 이미지">
											<ul>
												<li>[나이키]</li>
												<li>나이키 에어 줌 페가수스33</li>
											</ul>
										</a>
									</td>
									<td>실시간 계좌</td>
									<td class="payment">139,000원</td>
									<td>
										<div class="btn_voucher">
											<p><a href="javascript:void(0)" class="btn-line btn-cash-receipt">현금영수증</a></p>
											<p><a href="javascript:void(0)" class="btn-line btn-tax-invoice">세금계산서</a></p>
										</div>
									</td>
								</tr>
								<tr class="bold">
									<td class="order_num"><a href="javascript:void(0)">2016-07-13-15462035112B</a></td>
									<td class="date">2016-07-13</td>
									<td class="goods_info">
										<a href="javascript:void(0)">
											<img src="../static/img/test/@mypage_main_order2.jpg" alt="마이페이지 상품 썸네일 이미지">
											<ul>
												<li>[나이키]</li>
												<li>나이키 에어 줌 페가수스33</li>
											</ul>
										</a>
									</td>
									<td>카드결제</td>
									<td class="payment">139,000원</td>
									<td>
										<div class="btn_voucher">
											<p><a href="javascript:void(0)" class="btn-line">신용카드 매출전표</a></p>
											<p><a href="javascript:void(0)" class="btn-line btn-tax-invoice">세금계산서</a></p>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="list-paginate mt-30">
							<span class="border_wrap">
								<a href="#" class="prev-all">처음으로</a>
								<a href="#" class="prev">이전</a>
							</span>
							<a href="#" class="on">1</a>
							<a href="#">2</a>
							<a href="#">3</a>
							<a href="#">4</a>
							<a href="#">5</a>
							<a href="#">6</a>
							<a href="#">7</a>
							<a href="#">8</a>
							<a href="#">9</a>
							<a href="#">10</a>
							<span class="border_wrap">
								<a href="#" class="next">다음</a>
								<a href="#" class="next-all">끝으로</a>
							</span>
						</div>
					</div>
					<!-- // 증빙서류 리스트 -->

					<!-- 안내 -->
					<div class="list_text">
						<h3>유의사항</h3>
						<ul>
							<li>ㆍ 구매확정 후 48시간 이내에 현금영수증 정보가 국세청으로 이관된 후 증빙서류로 출력이 가능합니다</li>
							<li>ㆍ 이벤트 성격으로 지급된 Action 포인트의 경우 일부 현금영수증 발행 대상 금액에서 제외되며 결제금액과 상이할 수 있습니다</li>
							<li>ㆍ 구매확정 이후 현금영수증 발행 정보를 전달하므로 국세청 사이트에서는 즉시 확인이 되지 않을 수 있습니다</li>
							<li>ㆍ 휴대폰 결제금액은 증빙서류 발급에서 제외됩니다 (현금영수증은 휴대폰 요금을 현금납부하는 경우에만 해당 이동통신사에서 발급합니다)</li>
							<li>ㆍ 부분취소 발생 시 취소금액이 적용되어 증빙 금액이 변경될 수 있습니다</li>
						</ul>
					</div>
					<!-- // 안내 -->

				</section>
			</article>
		</main>
	</div>
</div><!-- //#contents -->

<!-- 현금영수증 팝업-->
<div class="layer-dimm-wrap pop-cash-receipt"> <!-- .layer-class 이부분에 클래스 추가하여 사용합니다. -->
	<div class="dimm-bg"></div>
	<div class="layer-inner w500">
		<h3 class="layer-title"><span class="type_txt1">현금영수증</span> 신청</h3>
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content">
			<div class="receipt_box">
				<p>
					<input type="radio" name="view-type" id="view" class="radio-def" checked="">
					<label for="view">개인</label>
					<input type="radio" name="view-type" id="no-view" class="radio-def ml-40">
					<label for="no-view">사업자</label>
				</p>
				<p class="mobile">
					<label for="text1">휴대폰 번호</label>
					<input type="text" placeholder="하이픈(-) 없이 입력" id="text1" title="휴대폰번호 입력자리" class="ml-10" style="width:228px;">
				</p>
				<p class="license">
					<label for="text2">사업자 번호</label>
					<input type="text" placeholder="하이픈(-) 없이 입력" id="text2" title="휴대폰번호 입력자리" class="ml-10" style="width:228px;">
				</p>
			</div>
			<div class="btn_wrap mt-40"><a href="#" class="btn-type1 c1">신청</a></div>
		</div>
	</div>
</div>
<!-- // 현금영수증 팝업 -->

<!-- 세금계산서 팝업-->
<div class="layer-dimm-wrap pop-tax-invoice"> <!-- .layer-class 이부분에 클래스 추가하여 사용합니다. -->
	<div class="dimm-bg"></div>
	<div class="layer-inner w500">
		<h3 class="layer-title"><span class="type_txt1">세금계산서</span> 신청</h3>
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content">
			<table class="th_left">
				<caption></caption>
				<colgroup>
					<col style="width:100px">
					<col style="width:auto">
				</colgroup>
				<tbody>
					<tr>
						<th scope="row"><label for="">회사명 <span class="required">*</span></label></th>
						<td>
							<input type="text" placeholder="회사명" title="회사명 입력자리" style="width:100%;">
						</td>
					</tr>
					<tr>
						<th scope="row">사업자 번호 <span class="required">*</span></th>
						<td>
							<ul class="int_license">
								<li><input type="text" title="사업자 번호 앞자리"></li>
								<li><input type="text" title="사업자 번호 중간자리"></li>
								<li><input type="text" title="사업자 번호 마지막자리"></li>
							</ul>
						</td>
					</tr>
					<tr>
						<th scope="row">대표자명 <span class="required">*</span></th>
						<td><input type="text" placeholder="대표자명" title="대표자명 입력자리" style="width:100%;"></td>
					</tr>
					<tr>
						<th scope="row">업태 <span class="required">*</span></th>
						<td><input type="text" placeholder="업태" title="업태 입력자리" style="width:100%;"></td>
					</tr>
					<tr>
						<th scope="row">종목 <span class="required">*</span></th>
						<td><input type="text" placeholder="종목" title="종목 입력자리" style="width:100%;"></td>
					</tr>
					<tr>
						<th scope="row">사업장 주소 <span class="required">*</span></th>
						<td><input type="text" placeholder="사업장 주소" title="사업장 주소 입력자리" style="width:100%;"></td>
					</tr>
				</tbody>
			</table>
			<p class="s_txt">ㆍ세금계산서는 법인카드만 신청가능</p>
			<div class="btn_wrap mt-40"><a href="#" class="btn-type1 c1">신청</a></div>
		</div>
	</div>
</div>
<!-- // 세금계산서 팝업 -->
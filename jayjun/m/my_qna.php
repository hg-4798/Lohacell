<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	<!-- Q&A작성 팝업 -->
	<section class="pop_layer layer_qna_write">
		<div class="inner">
			<h3 class="title">Q&amp;A작성<button type="button" class="btn_close">닫기</button></h3>
			<div class="board_type_write">
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
					<dt>답변받을 이메일</dt>
					<dd>
						<div class="input_mail">
							<input type="text" class="">
							<span class="at">&#64;</span>
							<select class="select_line">
								<option value="">선택</option>
							</select>
						</div>
						<input type="text" class="w100-per mt-5" placeholder="직접입력">
					</dd>
				</dl>
				<dl>
					<dt>휴대폰 번호</dt>
					<dd>
						<div class="input_tel">
							<select class="select_line">
								<option value="010">010</option>
								<option value="011">011</option>
								<option value="016">016</option>
								<option value="017">017</option>
								<option value="018">018</option>
								<option value="019">019</option>
							</select>
							<span class="dash"></span>
							<input type="tel" maxlength="4">
							<span class="dash"></span>
							<input type="tel" maxlength="4">
						</div>
					</dd>
				</dl>
				<dl>
					<dt>공개여부</dt>
					<dd>
						<label>
							<input type="radio" class="radio_def" name="open" checked>
							<span>공개</span>
						</label>
						<label class="ml-25">
							<input type="radio" class="radio_def" name="open">
							<span>비공개</span>
						</label>
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
	<!-- //Q&A작성 팝업 -->

	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>상품문의</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="mypage_qna sub_bdtop">

		<div class="tab_type1 mt-15" data-ui="TabMenu">
			<div class="tab-menu clear mb-20">
				<a data-content="menu" class="active" title="선택됨">답변대기</a>
				<a data-content="menu">답변완료</a>
			</div>

			<!-- 답변대기 -->
			<div class="tab-content active" data-content="content">
				<div class="check_period">
					<ul>
						<li class="on"><a href="javascript:;">1개월</a></li><!-- [D] 해당 조회기간일때 .on 클래스 추가 -->
						<li><a href="javascript:;">3개월</a></li>
						<li><a href="javascript:;">6개월</a></li>
						<li><a href="javascript:;">12개월</a></li>
					</ul>
				</div><!-- //.check_period -->

				<div class="review_list qna_list">
					<ul class="accordion_list">
						<li>
							<p class="date">작성일 2017-01-18<span class="private">공개</span></p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
									</div>
									<div class="btns">
										<a href="javascript:;" class="btn_qna_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
							<div class="qna_con">
								<div class="question accordion_btn">
									<p class="tit">너무 마음에 들어 사이즈 문의합니다.</p>
									<p class="txt accordion_con">너무 마음에 들어 사이즈 문의합니다.<br>오늘안으로 답변 주실 수 있나요? </p>
								</div>
							</div>
						</li>
						<li>
							<p class="date">작성일 2017-01-18<span class="private">비공개</span></p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
									</div>
									<div class="btns">
										<a href="javascript:;" class="btn_qna_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
							<div class="qna_con">
								<div class="question accordion_btn">
									<p class="tit">너무 마음에 들어 사이즈 문의합니다.</p>
									<p class="txt accordion_con">너무 마음에 들어 사이즈 문의합니다.<br>오늘안으로 답변 주실 수 있나요? </p>
								</div>
							</div>
						</li>
						<li>
							<p class="date">작성일 2017-01-18<span class="private">공개</span></p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
									</div>
									<div class="btns">
										<a href="javascript:;" class="btn_qna_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
							<div class="qna_con">
								<div class="question accordion_btn">
									<p class="tit">너무 마음에 들어 사이즈 문의합니다.</p>
									<p class="txt accordion_con">너무 마음에 들어 사이즈 문의합니다.<br>오늘안으로 답변 주실 수 있나요? </p>
								</div>
							</div>
						</li>
						<li>
							<p class="date">작성일 2017-01-18<span class="private">공개</span></p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
									</div>
									<div class="btns">
										<a href="javascript:;" class="btn_qna_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
							<div class="qna_con">
								<div class="question accordion_btn">
									<p class="tit">너무 마음에 들어 사이즈 문의합니다.</p>
									<p class="txt accordion_con">너무 마음에 들어 사이즈 문의합니다.<br>오늘안으로 답변 주실 수 있나요? </p>
								</div>
							</div>
						</li>
						<li>
							<p class="date">작성일 2017-01-18<span class="private">공개</span></p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
									</div>
									<div class="btns">
										<a href="javascript:;" class="btn_qna_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
							<div class="qna_con">
								<div class="question accordion_btn">
									<p class="tit">너무 마음에 들어 사이즈 문의합니다.</p>
									<p class="txt accordion_con">너무 마음에 들어 사이즈 문의합니다.<br>오늘안으로 답변 주실 수 있나요? </p>
								</div>
							</div>
						</li>
					</ul>
				</div><!-- //.review_list -->
				
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
				</div><!-- //.list-paginate -->
			</div>
			<!-- //답변대기 -->

			<!-- 답변완료 -->
			<div class="tab-content" data-content="content"><!-- [D] 통합포인트와 구성 동일 -->
				<div class="check_period">
					<ul>
						<li class="on"><a href="javascript:;">1개월</a></li><!-- [D] 해당 조회기간일때 .on 클래스 추가 -->
						<li><a href="javascript:;">3개월</a></li>
						<li><a href="javascript:;">6개월</a></li>
						<li><a href="javascript:;">12개월</a></li>
					</ul>
				</div><!-- //.check_period -->

				<div class="review_list qna_list">
					<ul class="accordion_list">
						<li>
							<p class="date">작성일 2017-01-18<span class="private">비공개</span></p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
									</div>
								</div>
							</div>
							<div class="qna_con">
								<div class="question accordion_btn">
									<p class="tit">너무 마음에 들어 사이즈 문의합니다.</p>
									<p class="txt accordion_con">너무 마음에 들어 사이즈 문의합니다.<br>오늘안으로 답변 주실 수 있나요? </p>
								</div>
								<div class="answer accordion_con">
									<p class="writer"><span>관리자</span><span class="a_date">2017.01.14</span></p>
									<p class="txt">안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
						<li>
							<p class="date">작성일 2017-01-18<span class="private">공개</span></p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
									</div>
								</div>
							</div>
							<div class="qna_con">
								<div class="question accordion_btn">
									<p class="tit">너무 마음에 들어 사이즈 문의합니다.</p>
									<p class="txt accordion_con">너무 마음에 들어 사이즈 문의합니다.<br>오늘안으로 답변 주실 수 있나요? </p>
								</div>
								<div class="answer accordion_con">
									<p class="writer"><span>관리자</span><span class="a_date">2017.01.14</span></p>
									<p class="txt">안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
						<li>
							<p class="date">작성일 2017-01-18<span class="private">공개</span></p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
									</div>
								</div>
							</div>
							<div class="qna_con">
								<div class="question accordion_btn">
									<p class="tit">너무 마음에 들어 사이즈 문의합니다.</p>
									<p class="txt accordion_con">너무 마음에 들어 사이즈 문의합니다.<br>오늘안으로 답변 주실 수 있나요? </p>
								</div>
								<div class="answer accordion_con">
									<p class="writer"><span>관리자</span><span class="a_date">2017.01.14</span></p>
									<p class="txt">안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
						<li>
							<p class="date">작성일 2017-01-18<span class="private">공개</span></p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
									</div>
								</div>
							</div>
							<div class="qna_con">
								<div class="question accordion_btn">
									<p class="tit">너무 마음에 들어 사이즈 문의합니다.</p>
									<p class="txt accordion_con">너무 마음에 들어 사이즈 문의합니다.<br>오늘안으로 답변 주실 수 있나요? </p>
								</div>
								<div class="answer accordion_con">
									<p class="writer"><span>관리자</span><span class="a_date">2017.01.14</span></p>
									<p class="txt">안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
						<li>
							<p class="date">작성일 2017-01-18<span class="private">공개</span></p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
									</div>
								</div>
							</div>
							<div class="qna_con">
								<div class="question accordion_btn">
									<p class="tit">너무 마음에 들어 사이즈 문의합니다.</p>
									<p class="txt accordion_con">너무 마음에 들어 사이즈 문의합니다.<br>오늘안으로 답변 주실 수 있나요? </p>
								</div>
								<div class="answer accordion_con">
									<p class="writer"><span>관리자</span><span class="a_date">2017.01.14</span></p>
									<p class="txt">안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
					</ul>
				</div><!-- //.review_list -->
				
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
				</div><!-- //.list-paginate -->
			</div>
			<!-- //답변완료 -->
		</div><!-- //.point_tab -->

	</section><!-- //.mypage_point -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
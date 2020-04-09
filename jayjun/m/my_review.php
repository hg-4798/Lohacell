<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
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
			<span>상품리뷰</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="mypage_review">

		<div class="review_info">
			<ul class="clear">
				<li>
					<span class="icon_review"></span>
					<span class="txt">리뷰 작성시<br><span class="point-color">200P</span> 지급</span>
				</li>
				<li>
					<span class="icon_photo_review"></span>
					<span class="txt">포토 리뷰 작성시<br><span class="point-color">500P</span> 지급</span>
				</li>
			</ul>
		</div><!-- //.review_info -->
		
		<div class="tab_type1 mt-15" data-ui="TabMenu">
			<div class="tab-menu clear mb-20">
				<a data-content="menu" class="active" title="선택됨">리뷰작성</a>
				<a data-content="menu">완료리뷰</a>
			</div>

			<!-- 리뷰작성 -->
			<div class="tab-content active" data-content="content">
				<div class="check_period">
					<ul>
						<li class="on"><a href="javascript:;">1개월</a></li><!-- [D] 해당 조회기간일때 .on 클래스 추가 -->
						<li><a href="javascript:;">3개월</a></li>
						<li><a href="javascript:;">6개월</a></li>
						<li><a href="javascript:;">12개월</a></li>
					</ul>
				</div><!-- //.check_period -->

				<div class="review_list">
					<ul>
						<li>
							<p class="date">주문날짜 2017-01-18</p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</div>
									<div class="btns"><a href="javascript:;" class="btn_review_write btn-basic">리뷰작성</a></div>
								</div>
							</div>
						</li>
						<li>
							<p class="date">주문날짜 2017-01-18</p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</div>
									<div class="btns"><a href="javascript:;" class="btn_review_write btn-basic">리뷰작성</a></div>
								</div>
							</div>
						</li>
						<li>
							<p class="date">주문날짜 2017-01-18</p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</div>
									<div class="btns"><a href="javascript:;" class="btn_review_write btn-basic">리뷰작성</a></div>
								</div>
							</div>
						</li>
						<li>
							<p class="date">주문날짜 2017-01-18</p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</div>
									<div class="btns"><a href="javascript:;" class="btn_review_write btn-basic">리뷰작성</a></div>
								</div>
							</div>
						</li>
						<li>
							<p class="date">주문날짜 2017-01-18</p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</div>
									<div class="btns"><a href="javascript:;" class="btn_review_write btn-basic">리뷰작성</a></div>
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
			<!-- //리뷰작성 -->

			<!-- 완료리뷰 -->
			<div class="tab-content" data-content="content"><!-- [D] 통합포인트와 구성 동일 -->
				<div class="check_period">
					<ul>
						<li class="on"><a href="javascript:;">1개월</a></li><!-- [D] 해당 조회기간일때 .on 클래스 추가 -->
						<li><a href="javascript:;">3개월</a></li>
						<li><a href="javascript:;">6개월</a></li>
						<li><a href="javascript:;">12개월</a></li>
					</ul>
				</div><!-- //.check_period -->

				<div class="review_list">
					<ul class="accordion_list">
						<li>
							<p class="date">주문날짜 2017-01-18</p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info accordion_btn">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</div>
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
							<div class="review_con accordion_con">
								<div class="star">
									<!-- <img src="static/img/icon/rating1.png" alt="5점 만점 중 1점"> -->
									<!-- <img src="static/img/icon/rating2.png" alt="5점 만점 중 2점"> -->
									<!-- <img src="static/img/icon/rating3.png" alt="5점 만점 중 3점"> -->
									<img src="static/img/icon/rating4.png" alt="5점 만점 중 4점">
									<!-- <img src="static/img/icon/rating5.png" alt="5점 만점 중 5점"> -->
								</div>
								<p class="tit">너무 마음에 들어 또 구매하고 싶습니다. </p>
								<div class="txt">
									<div class="body_info">키 <strong>160cm</strong>, 몸무게 <strong>54kg</strong> 의 고객이 <strong>L</strong>사이즈로 주문하였습니다.</div><!-- //[D] 리뷰 작성시 구매자의 체형 정보를 입력하면 해당정보 노출(키와 몸무게 다 작성시에만 노출) -->
									최고의 상품입니다. <br>
									꼭 사고 싶습니다.<br><br>
									<img src="static/img/test/@review_img.jpg" alt="리뷰 이미지">
								</div>
							</div>
						</li>
						<li>
							<p class="date">주문날짜 2017-01-18</p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info accordion_btn">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</div>
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
							<div class="review_con accordion_con">
								<div class="star">
									<img src="static/img/icon/rating5.png" alt="5점 만점 중 5점">
								</div>
								<p class="tit">너무 마음에 들어 또 구매하고 싶습니다. </p>
								<div class="txt">
									최고의 상품입니다. <br>
									꼭 사고 싶습니다.<br><br>
									<img src="static/img/test/@review_img.jpg" alt="리뷰 이미지">
								</div>
							</div>
						</li>
						<li>
							<p class="date">주문날짜 2017-01-18</p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info accordion_btn">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</div>
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
							<div class="review_con accordion_con">
								<div class="star">
									<img src="static/img/icon/rating5.png" alt="5점 만점 중 5점">
								</div>
								<p class="tit">너무 마음에 들어 또 구매하고 싶습니다. </p>
								<div class="txt">
									최고의 상품입니다. <br>
									꼭 사고 싶습니다.<br><br>
									<img src="static/img/test/@review_img.jpg" alt="리뷰 이미지">
								</div>
							</div>
						</li>
						<li>
							<p class="date">주문날짜 2017-01-18</p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info accordion_btn">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</div>
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
							<div class="review_con accordion_con">
								<div class="star">
									<img src="static/img/icon/rating5.png" alt="5점 만점 중 5점">
								</div>
								<p class="tit">너무 마음에 들어 또 구매하고 싶습니다. </p>
								<div class="txt">
									최고의 상품입니다. <br>
									꼭 사고 싶습니다.<br><br>
									<img src="static/img/test/@review_img.jpg" alt="리뷰 이미지">
								</div>
							</div>
						</li>
						<li>
							<p class="date">주문날짜 2017-01-18</p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="#"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></a></div>
									<div class="info accordion_btn">
										<p class="brand">BESTI BELLI</p>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</div>
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
							<div class="review_con accordion_con">
								<div class="star">
									<img src="static/img/icon/rating5.png" alt="5점 만점 중 5점">
								</div>
								<p class="tit">너무 마음에 들어 또 구매하고 싶습니다. </p>
								<div class="txt">
									최고의 상품입니다. <br>
									꼭 사고 싶습니다.<br><br>
									<img src="static/img/test/@review_img.jpg" alt="리뷰 이미지">
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
			<!-- //완료리뷰 -->
		</div><!-- //.point_tab -->

	</section><!-- //.mypage_point -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
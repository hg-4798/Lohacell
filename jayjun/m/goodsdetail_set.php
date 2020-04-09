<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	<!-- 상품상세정보 팝업 -->
	<section class="pop_layer layer_goods_detail">
		<div class="inner">
			<h3 class="title">상품상세정보<button type="button" class="btn_close">닫기</button></h3>
			<div>
				<img src="static/img/test/@goods_detail.jpg" alt="상품상세정보">	
			</div>
		</div>
	</section>
	<!-- //상품상세정보 팝업 -->

	<!-- 배송반품 팝업 -->
	<section class="pop_layer layer_goods_delivery">
		<div class="inner">
			<h3 class="title">배송반품<button type="button" class="btn_close">닫기</button></h3>
			<div class="info_txt">
				<h4>배송정보</h4>
				<ul>
					<li>배송 방법 : 택배</li>
					<li>배송 지역 : 전국지역 </li>
					<li>배송 비용 : 개별배송상품을 제외하고 배송비는 [무료]입니다. </li>
					<li>배송 기간 : 3일 ~ 7일</li>
					<li>배송 안내 : 산간벽지나 도서지방은 별도의 추가금액을 지불하셔야 하는 경우가 있습니다. </li>
					<li class="point-color">※ 고객님께서 주문하신 상품은 입금 확인후 배송해 드립니다. 다만, 상품종류에 따라서 상품의 배송이 다소 지연될 수 있습니다. </li>
				</ul>
				<h4>교환 및 반품</h4>
				<h5>교환 및 반품이 가능한 경우</h5>
				<ul>
					<li>- 상품을 공급 받으신 날로부터 7일이내 단, 가전제품의 경우 포장을 개봉하였거나 포장이 훼손되어 상품가치가 상실된 경우에는 교환/반품이 불가능합니다.</li>
					<li>- 공급받으신 상품 및 용역의 내용이 표시.광고 내용과 다르거나 다르게 이행된 경우에는 공급받은 날로부터 3월이내, 그사실을 알게 된 날로부터 30일이내 </li>
				</ul>
				<h5>교환 및 반품이 불가능한 경우</h5>
				<ul>
					<li>- 고객님의 책임 있는 사유로 상품등이 멸실 또는 훼손된 경우. 단, 상품의 내용을 확인하기 위하여 포장 등을 훼손한 경우는 제외 </li>
					<li>- 포장을 개봉하였거나 포장이 훼손되어 상품가치가 상실된 경우 (예 : 가전제품, 식품, 음반 등, 단 액정화면이 부착된 노트북, LCD모니터, 디지털 카메라 등의 불량화소에  따른 반품/교환은 제조사 기준에 따릅니다.)  </li>
					<li>- 고객님의 사용 또는 일부 소비에 의하여 상품의 가치가 현저히 감소한 경우 단, 화장품등의 경우 시용제품을 제공한 경우에 한 합니다. </li>
					<li>- 시간의 경과에 의하여 재판매가 곤란할 정도로 상품등의 가치가 현저히 감소한 경우</li>
					<li>- 복제가 가능한 상품등의 포장을 훼손한 경우 <br>(자세한 내용은 고객만족센터 1:1 E-MAIL상담을 이용해 주시기 바랍니다.) </li>
					<li class="point-color">※ 고객님의 마음이 바뀌어 교환, 반품을 하실 경우 상품반송 비용은 고객님께서 부담하셔야 합니다. <br>(색상 교환, 사이즈 교환 등 포함)</li>
				</ul>
			</div>
		</div>
	</section>
	<!-- //배송반품 팝업 -->

	<!-- 리뷰 리스트 팝업 -->
	<section class="pop_layer layer_review_list">
		<div class="inner">
			<h3 class="title">리뷰<button type="button" class="btn_close">닫기</button></h3>
			<div class="board_type_list">
				<div class="notice">
					<div class="rating_img">
						<div class="icon">
							<!-- <img src="static/img/icon/rating1.png" alt="5점 만점 중 1점"> -->
							<!-- <img src="static/img/icon/rating2.png" alt="5점 만점 중 2점"> -->
							<!-- <img src="static/img/icon/rating3.png" alt="5점 만점 중 3점"> -->
							<img src="static/img/icon/rating4.png" alt="5점 만점 중 4점">
							<!-- <img src="static/img/icon/rating5.png" alt="5점 만점 중 5점"> -->
						</div>
						<span class="score">4.0</span>
					</div>
					<p class="ment">고객님의 소중한 후기를 남겨주시기 바랍니다.</p>
					<div class="btn"><a href="javascript:;" class="btn_review_write btn-point">리뷰작성</a></div>
				</div>
				
				<div class="board_top">
					<span class="count">전체 21</span>
					<select class="select_def">
						<option value="">일반리뷰</option>
						<option value="">포토리뷰</option>
					</select>
				</div>
				
				<div class="list_review">
					<ul class="list_board">
						<li>
							<div class="title_area">
								<div class="info">
									<span class="rating"><img src="static/img/icon/rating4.png" alt="5점 만점 중 4점"></span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">정말 마음에 듭니다. <img src="static/img/icon/icon_camera.png" alt="사진첨부"></a></p>
							</div>
							<div class="con_area">
								<div class="review_txt">
									<img src="static/img/test/@ranking_img.jpg" alt="테스트"><br><br>
									이번에 새로 구입했는데 정말 좋네요.<br>선물로 강추해요!
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
						</li>

						<li>
							<div class="title_area">
								<div class="info">
									<span class="rating"><img src="static/img/icon/rating4.png" alt="5점 만점 중 4점"></span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">정말 마음에 듭니다. </a></p>
							</div>
							<div class="con_area">
								<div class="review_txt">
									이번에 새로 구입했는데 정말 좋네요.<br>선물로 강추해요!
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
						</li>

						<li>
							<div class="title_area">
								<div class="info">
									<span class="rating"><img src="static/img/icon/rating3.png" alt="5점 만점 중 3점"></span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">정말 마음에 듭니다. </a></p>
							</div>
							<div class="con_area">
								<div class="review_txt">
									이번에 새로 구입했는데 정말 좋네요.<br>선물로 강추해요!
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
						</li>

						<li>
							<div class="title_area">
								<div class="info">
									<span class="rating"><img src="static/img/icon/rating5.png" alt="5점 만점 중 5점"></span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">정말 마음에 듭니다. </a></p>
							</div>
							<div class="con_area">
								<div class="review_txt">
									이번에 새로 구입했는데 정말 좋네요.<br>선물로 강추해요!
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
						</li>

						<li>
							<div class="title_area">
								<div class="info">
									<span class="rating"><img src="static/img/icon/rating4.png" alt="5점 만점 중 4점"></span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">정말 마음에 듭니다. <img src="static/img/icon/icon_camera.png" alt="사진첨부"></a></p>
							</div>
							<div class="con_area">
								<div class="review_txt">
									<img src="static/img/test/@ranking_img.jpg" alt="테스트"><br><br>
									이번에 새로 구입했는데 정말 좋네요.<br>선물로 강추해요!
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
						</li>

						<li>
							<div class="title_area">
								<div class="info">
									<span class="rating"><img src="static/img/icon/rating4.png" alt="5점 만점 중 4점"></span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">정말 마음에 듭니다. </a></p>
							</div>
							<div class="con_area">
								<div class="review_txt">
									이번에 새로 구입했는데 정말 좋네요.<br>선물로 강추해요!
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
						</li>

						<li>
							<div class="title_area">
								<div class="info">
									<span class="rating"><img src="static/img/icon/rating4.png" alt="5점 만점 중 4점"></span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">정말 마음에 듭니다. </a></p>
							</div>
							<div class="con_area">
								<div class="review_txt">
									이번에 새로 구입했는데 정말 좋네요.<br>선물로 강추해요!
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
						</li>

						<li>
							<div class="title_area">
								<div class="info">
									<span class="rating"><img src="static/img/icon/rating4.png" alt="5점 만점 중 4점"></span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">정말 마음에 듭니다. </a></p>
							</div>
							<div class="con_area">
								<div class="review_txt">
									이번에 새로 구입했는데 정말 좋네요.<br>선물로 강추해요!
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
						</li>

						<li>
							<div class="title_area">
								<div class="info">
									<span class="rating"><img src="static/img/icon/rating4.png" alt="5점 만점 중 4점"></span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">정말 마음에 듭니다. </a></p>
							</div>
							<div class="con_area">
								<div class="review_txt">
									이번에 새로 구입했는데 정말 좋네요.<br>선물로 강추해요!
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
						</li>

						<li>
							<div class="title_area">
								<div class="info">
									<span class="rating"><img src="static/img/icon/rating4.png" alt="5점 만점 중 4점"></span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">정말 마음에 듭니다. </a></p>
							</div>
							<div class="con_area">
								<div class="review_txt">
									이번에 새로 구입했는데 정말 좋네요.<br>선물로 강추해요!
									<div class="btns">
										<a href="javascript:;" class="btn_review_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
						</li>

					</ul><!-- //.list_board -->
				</div><!-- //.list_qna -->
				
				<div class="list-paginate">
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
								
			</div>
		</div>
	</section>
	<!-- //리뷰 리스트 팝업 -->

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
						<label>몸무게<input type="text" value="60"></label>
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
						<p class="mt-5">파일명: 한글, 영문, 숫자/파일 크기: 3mb 이하/파일 형식: GIF, JPG, JPEG, PNG</p>
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

	<!-- Q&A 리스트 팝업 -->
	<section class="pop_layer layer_qna_list">
		<div class="inner">
			<h3 class="title">Q&amp;A<button type="button" class="btn_close">닫기</button></h3>
			<div class="board_type_list">
				<div class="notice">
					<p class="ment">상품관련 문의사항을 남겨주시기 바랍니다.</p>
					<div class="btn"><a href="javascript:;" class="btn_qna_write btn-line">문의하기</a></div>
				</div>
				
				<div class="board_top">
					<span class="count">전체 21</span>
					<select class="select_def">
						<option value="">답변완료</option>
						<option value="">답변대기</option>
					</select>
				</div>
				
				<div class="list_qna">
					<ul class="list_board">
						<li>
							<div class="title_area">
								<div class="info">
									<span class="status point-color">답변대기</span><!-- [D] 답변대기인 경우 .point-color 클래스 추가 -->
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">사이즈 문의드립니다. <img src="static/img/icon/icon_privacy.gif" alt="비밀글"></a></p>
							</div>
							<div class="con_area">
								<div class="q_txt">
									사이즈가 있나요?<br>정말 마음에 들어서 꼭 사고 싶습니다.
									<div class="btns"><!-- [D] 답변대기 상태일 때만 수정,삭제 버튼 노출 -->
										<a href="javascript:;" class="btn_qna_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
						</li>

						<li>
							<div class="title_area">
								<div class="info">
									<span class="status">답변완료</span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">사이즈 문의드립니다.</a></p>
							</div>
							<div class="con_area">
								<div class="q_txt">
									사이즈가 있나요?<br>정말 마음에 들어서 꼭 사고 싶습니다.
								</div>
								<div class="a_txt">
									<span>관리자</span>
									<span class="date">2017.01.14</span>
									<p>안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
						
						<li>
							<div class="title_area">
								<div class="info">
									<span class="status point-color">답변대기</span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">사이즈 문의드립니다.</a></p>
							</div>
							<div class="con_area">
								<div class="q_txt">
									사이즈가 있나요?<br>정말 마음에 들어서 꼭 사고 싶습니다.
									<div class="btns">
										<a href="javascript:;" class="btn_qna_write btn-line">수정</a>
										<a href="javascript:;" class="btn-basic">삭제</a>
									</div>
								</div>
							</div>
						</li>

						<li>
							<div class="title_area">
								<div class="info">
									<span class="status">답변완료</span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">사이즈 문의드립니다.</a></p>
							</div>
							<div class="con_area">
								<div class="q_txt">
									사이즈가 있나요?<br>정말 마음에 들어서 꼭 사고 싶습니다.
								</div>
								<div class="a_txt">
									<span>관리자</span>
									<span class="date">2017.01.14</span>
									<p>안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
						
						<li>
							<div class="title_area">
								<div class="info">
									<span class="status">답변완료</span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">사이즈 문의드립니다.</a></p>
							</div>
							<div class="con_area">
								<div class="q_txt">
									사이즈가 있나요?<br>정말 마음에 들어서 꼭 사고 싶습니다.
								</div>
								<div class="a_txt">
									<span>관리자</span>
									<span class="date">2017.01.14</span>
									<p>안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
						
						<li>
							<div class="title_area">
								<div class="info">
									<span class="status">답변완료</span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">사이즈 문의드립니다.</a></p>
							</div>
							<div class="con_area">
								<div class="q_txt">
									사이즈가 있나요?<br>정말 마음에 들어서 꼭 사고 싶습니다.
								</div>
								<div class="a_txt">
									<span>관리자</span>
									<span class="date">2017.01.14</span>
									<p>안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
						
						<li>
							<div class="title_area">
								<div class="info">
									<span class="status">답변완료</span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">사이즈 문의드립니다.</a></p>
							</div>
							<div class="con_area">
								<div class="q_txt">
									사이즈가 있나요?<br>정말 마음에 들어서 꼭 사고 싶습니다.
								</div>
								<div class="a_txt">
									<span>관리자</span>
									<span class="date">2017.01.14</span>
									<p>안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
						
						<li>
							<div class="title_area">
								<div class="info">
									<span class="status">답변완료</span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">사이즈 문의드립니다.</a></p>
							</div>
							<div class="con_area">
								<div class="q_txt">
									사이즈가 있나요?<br>정말 마음에 들어서 꼭 사고 싶습니다.
								</div>
								<div class="a_txt">
									<span>관리자</span>
									<span class="date">2017.01.14</span>
									<p>안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
						
						<li>
							<div class="title_area">
								<div class="info">
									<span class="status">답변완료</span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">사이즈 문의드립니다.</a></p>
							</div>
							<div class="con_area">
								<div class="q_txt">
									사이즈가 있나요?<br>정말 마음에 들어서 꼭 사고 싶습니다.
								</div>
								<div class="a_txt">
									<span>관리자</span>
									<span class="date">2017.01.14</span>
									<p>안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
						
						<li>
							<div class="title_area">
								<div class="info">
									<span class="status">답변완료</span>
									<span class="id">hoegjeo61**</span>
									<span class="date">2017.01.14</span>
								</div>
								<p class="subject"><a href="javascript:;">사이즈 문의드립니다.</a></p>
							</div>
							<div class="con_area">
								<div class="q_txt">
									사이즈가 있나요?<br>정말 마음에 들어서 꼭 사고 싶습니다.
								</div>
								<div class="a_txt">
									<span>관리자</span>
									<span class="date">2017.01.14</span>
									<p>안녕하세요. 고객님<br>해당 상품은 재입고 예정이 없습니다. 감사합니다.</p>
								</div>
							</div>
						</li>
					</ul><!-- //.list_board -->
				</div><!-- //.list_qna -->
				
				<div class="list-paginate">
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
								
			</div>
		</div>
	</section>
	<!-- //Q&A 리스트 팝업 -->

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


	<section class="detailpage">

		<div class="detail_view">
			<div class="colorchip_area">
				<ul>
					<li><label class="colorchip chip-darkGrey"><input type="radio" name="colorchip" value="dark_grey" checked><span></span></label></li>
					<li><label class="colorchip chip-beige light-color"><input type="radio" name="colorchip" value="beige"><span></span></label></li>
				</ul>
			</div>
			<div class="detail_img with-btn-rolling">
				<ul class="slide">
					<li><img src="static/img/test/@goodsdetail_01.jpg" alt="상품 이미지"></li>
					<li><img src="static/img/test/@goodsdetail_01.jpg" alt="상품 이미지"></li>
					<li><img src="static/img/test/@goodsdetail_01.jpg" alt="상품 이미지"></li>
					<li><img src="static/img/test/@goodsdetail_01.jpg" alt="상품 이미지"></li>
					<li><img src="static/img/test/@goodsdetail_01.jpg" alt="상품 이미지"></li>
					<li><img src="static/img/test/@goodsdetail_01.jpg" alt="상품 이미지"></li>
					<li><img src="static/img/test/@goodsdetail_01.jpg" alt="상품 이미지"></li>
				</ul>
			</div>
		</div><!-- //.detail_view -->

		<div class="goods_info">
			<p class="brand">BESTI BELLI</p>
			<p class="name">솔리드 심플 벨티트 자켓<span class="code">(TLOBY2535/TLOBY2222)</span></p><!-- [D] 세트상품 (개별 상품코드 모두 노출) -->
			<p class="price">
				<label>판매가</label>
				<span>
					<strong>￦ 105,800</strong><del>￦ 105,800</del>
					<span class="tag_discount"><strong>20</strong>% <img src="static/img/icon/icon_darr.png" alt="할인"></span>
				</span>
			</p>
			<p class="price">
				<label>임직원가</label>
				<span><strong class="point-color">￦ 105,800</strong></span>
			</p>
			<!-- <p class="text">깔끔한 디자인의 원피스입니다.<br>두툼한 소재로 만들어 초가을까지 입으실 수 있습니다.<br>178cm 마네킹이 66사이즈를 착용하였습니다.</p> -->
			<ul class="etc">
				<li>
					<label>포인트 적립</label>
					<span>10,000 P (5%)</span>
				</li>
				<li>
					<label>할인정보</label>
					<span>
						<div class="wrap_bubble">
							<div class="btn_bubble"><button type="button" class="btn_coupon_down">쿠폰 다운로드</button></div>
							<div class="pop_bubble">
								<div class="inner">
									<button type="button" class="btn_pop_close">닫기</button>
									<div class="container">
										<p class="tit_pop">쿠폰 다운로드</p>
										<ul class="list_coupon">
											<li>
												<label>20% 할인쿠폰</label>
												<button type="button" class="btn_coupon_down on">쿠폰 다운로드</button>
											</li>
											<li>
												<label>10% 할인쿠폰</label>
												<button type="button" class="btn_coupon_down">쿠폰 다운로드</button>
											</li>
											<li>
												<label>가입축하 전상품 10% 할인쿠폰</label>
												<button type="button" class="btn_coupon_down">쿠폰 다운로드</button>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</span>
				</li>
				<li>
					<label>시즌정보</label>
					<span>2016SS</span>
				</li>
				<li>
					<label>배송비</label>
					<span>
						30,000원 이상 무료배송
						<div class="wrap_bubble shipping_fee">
							<div class="btn_bubble"><button type="button" class="btn_help">?</button></div>
							<div class="pop_bubble">
								<div class="inner">
									<button type="button" class="btn_pop_close">닫기</button>
									<div class="container">
										<p class="tit_pop">배송비 안내</p>
										<table class="tbl_txt">
											<colgroup>
												<col style="width:44px;">
												<col style="width:auto;">
											</colgroup>
											<tbody>
												<tr>
													<th>택배발송:</th>
													<td>30,000원 이상 결제시 무료배송</td>
												</tr>
												<tr>
													<th>당일수령:</th>
													<td>거리별 추가 배송비 발생</td>
												</tr>
												<tr>
													<th>매장픽업:</th>
													<td>배송비 발생하지 않음</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div><!-- //.wrap_bubble -->
					</span>
				</li>
			</ul>
		</div><!-- //.goods_info -->

		<div class="goods_info_pop">
			<ul class="menu_list">
				<li><a href="javascript:;" class="btn_goods_detail">상품상세정보</a></li>
				<li><a href="javascript:;" class="btn_goods_delivery">배송반품</a></li>
			</ul>
			<div class="menu_btn">
				<a href="javascript:;" class="btn_review_list btn-line">리뷰<span class="point-color">(30)</span></a>
				<a href="javascript:;" class="btn_qna_list btn-line">Q&A<span class="point-color">(30)</span></a>
				
				<div class="wrap_bubble layer_sns_share">
					<div class="btn_bubble"><button type="button" class="btn_sns_share">sns 공유</button></div>
					<div class="pop_bubble">
						<div class="inner">
							<button type="button" class="btn_pop_close">닫기</button>
							<div class="icon_container">
								<a href="javascript:;"><img src="static/img/icon/icon_sns_kas.png" alt=""></a>
								<a href="javascript:;"><img src="static/img/icon/icon_sns_face.png" alt=""></a>
								<a href="javascript:;"><img src="static/img/icon/icon_sns_twit.png" alt=""></a>
								<a href="javascript:;"><img src="static/img/icon/icon_sns_band.png" alt=""></a>
								<a href="javascript:;"><img src="static/img/icon/icon_sns_link.png" alt=""></a>
							</div>
						</div>
					</div>
				</div><!-- //.wrap_bubble -->

			</div>
		</div><!-- //.goods_info_pop -->

		<div class="recommend_list" data-ui="TabMenu">
			<div class="tab-menu clear">
				<a data-content="menu" class="active" title="선택됨">MD’s CHOICE</a>
				<a data-content="menu">CATEGORY BEST</a>
			</div>

			<!-- MD’s CHOICE -->
			<div class="tab-content active" data-content="content">
				<div class="nowrap_list">
					<ul class="goodslist">
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></div>
									<figcaption>
										<p class="name">솔리드 심플 벨티트 자켓 </p>
										<p class="price">￦ 105,800</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="static/img/test/@goodslist_02.jpg" alt="상품 이미지"></div>
									<figcaption>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 315,900</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="static/img/test/@goodslist_09.jpg" alt="상품 이미지"></div>
									<figcaption>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="static/img/test/@goodslist_10.jpg" alt="상품 이미지"></div>
									<figcaption>
										<p class="name">솔리드 심플 벨티트 자켓 라쿤털 후드</p>
										<p class="price">￦ 315,900</p>
									</figcaption>
								</figure>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<!-- //MD’s CHOICE -->

			<!-- CATEGORY BEST -->
			<div class="tab-content" data-content="content">
				<div class="nowrap_list">
					<ul class="goodslist">
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="static/img/test/@goodslist_13.jpg" alt="상품 이미지"></div>
									<figcaption>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="static/img/test/@goodslist_14.jpg" alt="상품 이미지"></div>
									<figcaption>
										<p class="name">클래식 믹스 레이어드</p>
										<p class="price">￦ 315,900</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="static/img/test/@goodslist_15.jpg" alt="상품 이미지"></div>
									<figcaption>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 105,800</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="static/img/test/@goodslist_16.jpg" alt="상품 이미지"></div>
									<figcaption>
										<p class="name">솔리드 심플 벨티트 자켓</p>
										<p class="price">￦ 315,900</p>
									</figcaption>
								</figure>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<!-- //CATEGORY BEST -->
		</div><!-- //.recommend_list -->

		<div class="goods_order">
			<div class="bg"></div>
			<div class="contents">
				<div class="option_open">
					<div class="select_shipping" data-ui="TabMenu">
						<button type="button" class="btn_close">닫기</button>
						<div class="wrap_tabmenu">
							<ul class="tab-menu clear">
								<li data-content="menu" class="active" title="선택됨"><label><input type="radio" class="radio_def" name="shipMethod" checked> 택배발송</label></li>
								<!-- [D] 세트상품은 O2O배송 불가 -->
								<li class="disabled"><label><input type="radio" class="radio_def" name="shipMethod" disabled> 당일수령</label></li>
								<li class="disabled"><label><input type="radio" class="radio_def" name="shipMethod" disabled> 매장픽업</label></li>
								<!-- //[D] 세트상품은 O2O배송 불가 -->
							</ul>
							<div class="wrap_bubble shipping_info">
								<div class="btn_bubble"><button type="button" class="btn_help">?</button></div>
								<div class="pop_bubble">
									<div class="inner">
										<button type="button" class="btn_pop_close">닫기</button>
										<div class="container">
											<p class="tit_pop">배송방법 안내</p>
											<table class="tbl_txt">
												<colgroup>
													<col style="width:44px;">
													<col style="width:auto;">
												</colgroup>
												<tbody>
													<tr>
														<th>택배발송:</th>
														<td>택배로 발송하는 기본 배송 서비스</td>
													</tr>
													<tr>
														<th>당일수령:</th>
														<td>당일수령이 가능한 라이더 배송 서비스</td>
													</tr>
													<tr>
														<th>매장픽업:</th>
														<td>원하는 날짜, 원하는 매장에서 상품을 받아가는 맞춤형 배송 서비스</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div><!-- //.wrap_bubble -->
						</div>
						<!-- 택배발송 -->
						<div class="tab-content active" data-content="content">
							<!-- [D] 내용없음 -->
						</div>
						<!-- //택배발송 -->
						<!-- 당일수령 -->
						<div class="tab-content" data-content="content">
							<dl>
								<dt>강남직영점(매장)</dt>
								<dd><a href="javascript:;" class="btn_select_store01 btn-basic">매장선택</a></dd>
							</dl>
						</div>
						<!-- //당일수령 -->
						<!-- 매장픽업 -->
						<div class="tab-content" data-content="content">
							<dl>
								<dt>강남직영점(매장)</dt>
								<dd><a href="javascript:;" class="btn_select_store02 btn-basic">매장선택</a></dd>
							</dl>
						</div>
						<!-- //매장픽업 -->
					</div><!-- //.select_shipping -->

					<div class="select_option">
						<!-- [D] 세트상품 : 색상 옵션 선택 불가. 개별 상품명 및 사이즈 옵션 노출 -->
						<div class="select_s">
							<dl>
								<dt>시프트 플라운스 상의</dt>
								<dd class="size_select">
									<label>
										<input type="radio" name="selectSizeTop" checked>
										<span>XS(44)</span>
									</label>
									<label>
										<input type="radio" name="selectSizeTop" disabled>
										<span>S(55)</span>
									</label>
									<label>
										<input type="radio" name="selectSizeTop">
										<span>M(66)</span>
									</label>
									<label>
										<input type="radio" name="selectSizeTop">
										<span>L(77)</span>
									</label>
									<label>
										<input type="radio" name="selectSizeTop">
										<span>XL(88)</span>
									</label>
									<label>
										<input type="radio" name="selectSizeTop">
										<span>XXL(88)</span>
									</label>
									<label>
										<input type="radio" name="selectSizeTop" disabled>
										<span>XXXL(88)</span>
									</label>
								</dd>
							</dl>
						</div>
						<div class="select_s mt-15">
							<dl>
								<dt>시프트 플라운스 하의</dt>
								<dd class="size_select">
									<label>
										<input type="radio" name="selectSizeBtm" checked>
										<span>XS(24)</span>
									</label>
									<label>
										<input type="radio" name="selectSizeBtm" disabled>
										<span>S(25)</span>
									</label>
									<label>
										<input type="radio" name="selectSizeBtm">
										<span>M(26)</span>
									</label>
									<label>
										<input type="radio" name="selectSizeBtm">
										<span>L(27)</span>
									</label>
								</dd>
							</dl>
						</div>

						<hr class="contour mt-15">
						<!-- //[D] 세트상품 -->

						<div class="ea_area">
							<div class="ea-select">
								<input type="text" value="1" readonly="">
								<button class="plus">증가</button>
								<button class="minus">감소</button>
							</div>
						</div>
					</div><!-- //.select_option -->

					<div class="total_price">
						<dl>
							<dt>총 합계</dt>
							<dd>￦ 105,800</dd>
						</dl>
					</div><!-- //.total_price -->
				</div><!-- //.option_open -->

				<div class="btnset">
					<ul class="clear">
						<li><a href="javascript:;" class="btn_addcart"><span class="icon_cart_black"></span>장바구니</a></li>
						<li><a href="javascript:;" class="btn_like"><span class="icon_like"></span>좋아요 <span class="point-color">(302)</span></a></li><!-- [D] 클릭시 좋아요 숫자+1, 재클릭시 좋아요 숫자-1 -->
						<li class="btn_ordernow"><a href="#">바로구매</a></li>
					</ul>
				</div><!-- //.btnset -->
			</div>
		</div><!-- //.goods_order -->

	</section><!-- //.detailpage -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
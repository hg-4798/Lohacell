<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	<!-- AS 접수 팝업 -->
	<section class="pop_layer layer_as_write">
		<div class="inner">
			<h3 class="title">AS 접수<button type="button" class="btn_close">닫기</button></h3>
			<div class="board_type_write">
				<form>
				<dl>
					<dt>상품선택</dt>
					<dd>
						<select class="select_line w100-per"><!-- [D] 배송중, 배송완료 상태인 상품 노출(같은 이름의 상품은 1개만 노출) -->
							<option value="">선택안함</option>
							<option value="">솔리드 심플 벨티드 쟈켓</option>
						</select>
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
					<dt>파일첨부</dt>
					<dd>
						<div class="input_file">
							<input class="upload_name" disabled>
							<label for="ex_filename" class="btn-basic h-input">찾기</label>
							<input type="file" id="ex_filename" class="upload_hidden">
						</div>
					</dd>
				</dl>

				<div class="btn_area">
					<ul class="ea2">
						<li><a href="javascript:;" class="btn-line h-large">취소</a></li>
						<li><a href="javascript:;" class="btn-point h-large">등록</a></li>
					</ul>
				</div>
				</form>
			</div>
		</div>
	</section>
	<!-- //AS 접수 팝업 -->

	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>AS 접수</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="wrap_as sub_bdtop">
		<div class="step_as">
			<dl>
				<dt>
					<div class="icon"><img src="static/img/icon/icon_step_as01.png" alt="AS 접수"></div>
					<p>AS 접수</p>
				</dt>
				<dd>
					<p class="tit">매장 구매 고객</p>
					<ul class="list">
						<li>구입 매장 방문 접수</li>
					</ul>
					<p class="tit mt-5">신원몰 구매 고객</p>
					<ul class="list">
						<li>온라인 AS 게시판 접수</li>
						<li>온라인 콜센터 접수</li>
					</ul>
				</dd>
			</dl>
			<dl>
				<dt>
					<div class="icon"><img src="static/img/icon/icon_step_as02.png" alt="고객만족실"></div>
					<p>고객만족실</p>
				</dt>
				<dd>
					<ul class="list">
						<li>상품 인수 후 수선 또는 외부 심의 판단</li>
						<li>고객에게 유선 연락 진행</li>
					</ul>
				</dd>
			</dl>
			<dl>
				<dt>
					<div class="icon"><img src="static/img/icon/icon_step_as03.png" alt="수선진행"></div>
					<p>수선진행</p>
				</dt>
				<dd>
					<ul class="list">
						<li>자체 수선실 및 협력업체에서 수선 진행</li>
					</ul>
				</dd>
			</dl>
			<dl>
				<dt>
					<div class="icon"><img src="static/img/icon/icon_step_as04.png" alt="수선완료 후 발송"></div>
					<p>수선완료 후 발송</p>
				</dt>
				<dd>
					<ul class="list">
						<li>수선완료 후 발송</li>
					</ul>
				</dd>
			</dl>
		</div><!-- //.step_as -->

		<div class="as_contact">
			<h3>AS 접수 및 문의 (1661-2585)</h3>
			<dl>
				<dt>주소 : </dt>
				<dd>서울 중랑구 신내로1길 20 신원몰CS<br> CJ대한통운택배중랑대리점</dd>
			</dl>
		</div><!-- //.as_contact -->

		<div class="as_reception">
			<div class="check_period">
				<ul>
					<li class="on"><a href="javascript:;">1개월</a></li><!-- [D] 해당 조회기간일때 .on 클래스 추가 -->
					<li><a href="javascript:;">3개월</a></li>
					<li><a href="javascript:;">6개월</a></li>
					<li><a href="javascript:;">12개월</a></li>
				</ul>
			</div><!-- //.check_period -->

			<div class="pt-10 pr-10 pl-10">
				<a href="javascript:;" class="btn_as_write btn-line w100-per h-input">AS 접수하기</a>
			</div>

				<div class="inquiry_list mt-10">
					<ul class="accordion_list">
						<li>
							<div class="my_inquiry">
								<p class="info"><span class="date">2017.01.20</span><strong>AS 접수</strong></p>
								<p class="tit accordion_btn">궁금하고 또 궁금해서 물어보는데요. 꼭 답변주실 수 있으신거죠? 네? 네?</p>
							</div>
							<div class="qna_con accordion_con">
								<div class="question">
									<p><strong>주문상품 :</strong> <span>솔리드 심플 벨티드 쟈켓</span></p>
									<strong>수선의뢰 내용</strong>
									<p>사이즈가 잘 맞지 않아 입기가 힘듭니다. <br>32사이즈인데 28사이즈 인 듯 합니다.<br>허리사이즈를 늘려주세요.</p>
								</div>
								<div class="answer">
									<p class="writer"><span>관리자</span><span class="a_date">2017.01.14</span></p>
									<p class="txt">- 안녕하세요. 고객님 3월 24일 AS접수가 완료되었습니다.</p>
									<p class="txt">- 3월 27일 수선이 완료되어 발송처리 하였습니다.</p>
								</div>
							</div>
						</li>
						<li>
							<div class="my_inquiry">
								<p class="info"><span class="date">2017.01.20</span><strong class="point-color">심의중</strong></p>
								<p class="tit accordion_btn">궁금하고 또 궁금해서 물어보는데요.</p>
								<div class="btns">
									<a href="javascript:;" class="btn_as_write btn-line">수정</a>
									<a href="javascript:;" class="btn-basic">삭제</a>
								</div>
							</div>
							<div class="qna_con accordion_con">
								<div class="question">
									<p><strong>주문상품 :</strong> <span>솔리드 심플 벨티드 쟈켓</span></p>
									<strong>수선의뢰 내용</strong>
									<p>사이즈가 잘 맞지 않아 입기가 힘듭니다. <br>32사이즈인데 28사이즈 인 듯 합니다.<br>허리사이즈를 늘려주세요.</p>
								</div>
							</div>
						</li>
					</ul>
				</div><!-- //.inquiry_list -->
				
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
		</div><!-- //.as_reception -->

	</section><!-- //.wrap_as -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
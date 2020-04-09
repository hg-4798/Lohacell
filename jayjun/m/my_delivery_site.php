<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">

	<!-- 배송지 추가 팝업 -->
	<section class="pop_layer layer_add_deli">
		<div class="inner">
			<h3 class="title">배송지 추가 <button type="button" class="btn_close">닫기</button></h3>

			<div class="board_type_write">
				<dl>
					<dt>
						<span class="required">배송지명</span>
						<label><input type="checkbox" class="check_def"> <span>기본 배송지로 설정</span></label>
					</dt>
					<dd><input type="text" class="w100-per"></dd>
				</dl>
				<dl>
					<dt><span class="required">받는사람</span></dt>
					<dd><input type="text" class="w100-per"></dd>
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
							<input type="text" class="w100-per" placeholder="우편번호">
							<div class="btn_addr"><a href="javascript:;" class="btn-basic h-input">주소찾기</a></div>
						</div>
						<input type="text" class="w100-per mt-5" placeholder="기본주소">
						<input type="text" class="w100-per mt-5" placeholder="상세주소">
					</dd>
				</dl>
				<div class="btn_area mt-20">
					<ul>
						<li><a href="javascript:;" class="btn-point h-input">저장</a></li>
					</ul>
				</div>
			</div><!-- //.board_type_write -->
		</div>
	</section><!-- //.layer_add_deli -->
	<!-- //배송지 추가 팝업 -->

	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>배송지 관리</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="my_deli_site sub_bdtop">
		<div class="btn_area">
			<ul>
				<li><button type="button" class="btn_add_deli btn-line h-input">배송지 추가</button></li>
			</ul>
		</div>

		<div class="list_deli">
			<ul>
				<li>
					<div class="info">
						<p class="tit">우리집 주소 <span class="btn-point h-small">기본</span></p>
						<p class="tel">010-5021-1423</p>
						<p class="addr">서울 강남구 강남대로 123번지 서울 강남구 강남대로 123번지</p>
					</div>
					<div class="btns">
						<a href="javascript:;" class="btn_add_deli btn-line">수정</a>
						<a href="javascript:;" class="btn-basic">삭제</a>
					</div>
				</li>
				<li>
					<div class="info">
						<p class="tit">우리집 주소</p>
						<p class="tel">010-5021-1423</p>
						<p class="addr">서울 강남구 강남대로 123번지 서울 강남구</p>
					</div>
					<div class="btns">
						<a href="javascript:;" class="btn_add_deli btn-line">수정</a>
						<a href="javascript:;" class="btn-basic">삭제</a>
					</div>
				</li>
				<li>
					<div class="info">
						<p class="tit">우리집 주소</p>
						<p class="tel">010-5021-1423</p>
						<p class="addr">서울 강남구 강남대로 123번지 서울 강남구 강남대로 123번지</p>
					</div>
					<div class="btns">
						<a href="javascript:;" class="btn_add_deli btn-line">수정</a>
						<a href="javascript:;" class="btn-basic">삭제</a>
					</div>
				</li>
				<li>
					<div class="info">
						<p class="tit">우리집 주소</p>
						<p class="tel">010-5021-1423</p>
						<p class="addr">서울 강남구 강남대로 123번지 서울 강남구</p>
					</div>
					<div class="btns">
						<a href="javascript:;" class="btn_add_deli btn-line">수정</a>
						<a href="javascript:;" class="btn-basic">삭제</a>
					</div>
				</li>
				<li>
					<div class="info">
						<p class="tit">우리집 주소</p>
						<p class="tel">010-5021-1423</p>
						<p class="addr">서울 강남구 강남대로 123번지 서울 강남구</p>
					</div>
					<div class="btns">
						<a href="javascript:;" class="btn_add_deli btn-line">수정</a>
						<a href="javascript:;" class="btn-basic">삭제</a>
					</div>
				</li>
			</ul>
		</div><!-- //.list_deli -->

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

	</section><!-- //.my_deli_site -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage fullh">

	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>입점문의</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="sub_bdtop">
		<div class="board_type_write pb-35">
			<dl>
				<dt><span class="required">작성자</span></dt>
				<dd><input type="text" class="w100-per" placeholder="작성자 입력(필수)"></dd>
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
				<dt><span class="required">이메일</span></dt>
				<dd>
					<div class="input_mail">
						<input type="text" class="">
						<span class="at">@</span>
						<select class="select_line">
							<option value="">선택</option>
						</select>
					</div>
					<input type="text" class="w100-per mt-5" placeholder="직접입력">
				</dd>
			</dl>
			<dl>
				<dt><span class="required">제목</span></dt>
				<dd><input type="text" class="w100-per" placeholder="제목 입력(필수)"></dd>
			</dl>
			<dl>
				<dt><span class="required">내용</span></dt>
				<dd><textarea class="w100-per" rows="5" placeholder="내용 입력(필수)"></textarea></dd>
			</dl>

			<div class="btn_area mt-35">
				<ul>
					<li><a href="javascript:;" class="btn-point h-input">문의하기</a></li>
				</ul>
			</div>
		</div><!-- //.board_type_write -->

	</section><!-- //.my_deli_site -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
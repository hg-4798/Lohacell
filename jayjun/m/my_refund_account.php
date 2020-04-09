<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">

	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>환불계좌 관리</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="my_refund_account sub_bdtop">
		<div class="board_type_write">
			<dl>
				<dt><span class="required">은행명</span></dt>
				<dd>
					<select class="select_line w100-per">
						<option value="">은행명 선택</option>
						<option value=""></option>
						<option value=""></option>
					</select>
				</dd>
			</dl>
			<dl>
				<dt><span class="required">계좌번호</span></dt>
				<dd><input type="tel" class="w100-per" placeholder="하이픈(-)없이 입력"></dd>
			</dl>
			<dl>
				<dt><span class="required">예금주</span></dt>
				<dd><input type="text" class="w100-per"></dd>
			</dl>
			<dl>
				<dt><span class="required">연락처</span></dt>
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
			<div class="btn_area mt-20">
				<ul>
					<li><a href="javascript:;" class="btn-point h-input">저장</a></li>
				</ul>
			</div>
		</div><!-- //.board_type_write -->
	</section><!-- //.my_refund_account -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
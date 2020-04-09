<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="cs-page">

		<h2 class="page-title">입점문의</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/cs_lnb.php') ?>
			<article class="cs-content">
				<h2 class="v-hidden">입점문의하기</h2>
				<section>
					<header class="my-title">
						<h3 class="v-hidden">입점문의</h3>
					</header>
					<form>
						<table class="th-left">
							<caption>입점문의</caption>
							<colgroup>
								<col style="width:144px">
								<col style="width:auto">
							</colgroup>
							<tbody>
								<tr>
									<th scope="row"><label for="contact_name" class="essential">작성자</label></th>
									<td><div class="input-cover"><input type="text" style="width:175px" title="작성자 입력" id="contact_name"></div></td>
								</tr>
								<tr>
									<th scope="row"><label class="essential">휴대폰 번호</label></th>
									<td>
										<div class="input-cover">
											<div class="select">
												<select style="width:110px">
													<option value="">선택</option>
													<option value="">010</option>
												</select>
											</div>
											<span class="txt">-</span>
											<input type="text" title="휴대폰 가운데 번호 입력" style="width:110px">
											<span class="txt">-</span>
											<input type="text" title="휴대폰 마지막 번호 입력" style="width:110px">
										</div>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="contact_email" class="essential">이메일</label></th>
									<td>
										<div class="input-cover">
											<input type="text"  style="width:175px" title="이메일 입력" id="contact_email">
											<span class="txt">@</span>
											<div class="select">
												<select style="width:175px">
													<option value="">naver.com</option>
													<option value="">직접입력</option>
												</select>
											</div>
											<input type="text" title="도메인 직접 입력" class="ml-10" style="width:175px"> <!-- [D] 직접입력시 인풋박스 출력 -->
										</div>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="contact_title" class="essential">제목</label></th>
									<td><div class="input-cover"><input type="text" class="w100-per" title="제목 입력" id="contact_title"></div></td>
								</tr>
								<tr>
									<th scope="row"><label for="contact_textarea" class="essential">문의내용</label></th>
									<td><textarea id="contact_textarea" class="w100-per" style="height:272px"></textarea></td>
								</tr>
							</tbody>
						</table>
						<div class="ta-c mt-40"><button class="btn-point h-large w200" type="submit">문의하기</button></div>
					</form>
				</section>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
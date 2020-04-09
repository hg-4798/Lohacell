<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">환불계좌 관리</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				
				<fieldset>
					<legend>환불계좌 입력</legend>
					<table class="th-left mt-10">
						<caption>환불계좌 입력</caption>
						<colgroup>
							<col style="width:178px">
							<col style="width:auto">
						</colgroup>
						<tbody>
							<tr>
								<th scope="row"><label class="essential" for="bank_name">은행명</label></th>
								<td>
									<div class="input-cover">
										<div class="select">
											<select id="bank_name" style="width:270px">
												<option>은행명 선택</option>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label class="essential" for="bank_account">계좌번호</label></th>
								<td>
									<div class="input-cover"><input type="text" id="bank_account" title="계좌번호 입력" placeholder="하이픈(-)없이 입력" style="width:270px"></div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label class="essential" for="account_name">예금주</label></th>
								<td>
									<div class="input-cover"><input type="text" id="account_name" title="예금주 이름 입력" placeholder="이름 입력" style="width:270px"></div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="account_tel">연락처</label></th>
								<td>
									<div class="input-cover">
										<div class="select">
											<select id="account_tel" style="width:110px">
												<option>02</option>
											</select>
										</div>
										<span class="txt">-</span>
										<input type="text" title="선택 전화번호 가운데 입력자리" style="width:110px">
										<span class="txt">-</span>
										<input type="text" title="선택 전화번호 마지막 입력자리" style="width:110px">
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="btnPlace mt-40">
						<button type="submit" class="btn-point h-large" style="width:220px"><span>저장</span></button>
					</div>
				</fieldset>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">배송지 관리</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/mypage_lnb.php') ?>
			<article class="my-content">
				
				<div class="ta-r"><button class="btn-line w100 btn-postList add" type="button"><span class="fz-14">배송지 추가</span></button></div>
				<table class="th-top mt-10">
					<colgroup>
						<col style="width:115px">
						<col style="width:115px">
						<col style="width:auto">
						<col style="width:160px">
						<col style="width:170px">
					</colgroup>
					<thead>
						<tr>
							<th scope="col">배송지명</th>
							<th scope="col">받는사람</th>
							<th scope="col">주소</th>
							<th scope="col">연락처</th>
							<th scope="col">수정/삭제</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="txt-toneB">집</td>
							<td class="txt-toneA">홍길동</td>
							<td class="subject">경기도 고양시 일산동구 백성동 백송마을 임광 아파트 123</td>
							<td class="txt-toneB">010-3141-6811</td>
							<td>
								<div class="refund-btnGroup">
									<button class="btn-basic btn-postList modify" type="button"><span>수정</span></button>
									<button class="btn-line ml-5" type="button"><span>삭제</span></button>
								</div>
							</td>
						</tr>
						<tr>
							<td class="txt-toneB">둘리네</td>
							<td class="txt-toneA">고길동</td>
							<td class="subject">서울 강북구 미아동 고길동집</td>
							<td class="txt-toneB">010-3141-6811</td>
							<td>
								<div class="refund-btnGroup">
									<button class="btn-basic btn-postList modify" type="button"><span>수정</span></button>
									<button class="btn-line ml-5" type="button"><span>삭제</span></button>
								</div>
							</td>
						</tr>
						<tr>
							<td class="txt-toneB">집</td>
							<td class="txt-toneA">최순실</td>
							<td class="subject">경기도 삼성동 좋은동네</td>
							<td class="txt-toneB">010-3141-6811</td>
							<td>
								<div class="refund-btnGroup">
									<button class="btn-basic btn-postList modify" type="button"><span>수정</span></button>
									<button class="btn-line ml-5" type="button"><span>삭제</span></button>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="list-paginate mt-20">
					<a href="#" class="prev-all"></a>
					<a href="#" class="prev"></a>
					<a href="#" class="number on">1</a>
					<a href="#" class="number">2</a>
					<a href="#" class="number">3</a>
					<a href="#" class="number">4</a>
					<a href="#" class="number">5</a>
					<a href="#" class="number">6</a>
					<a href="#" class="number">7</a>
					<a href="#" class="number">8</a>
					<a href="#" class="number">9</a>
					<a href="#" class="number">10</a>
					<a href="#" class="next on"></a>
					<a href="#" class="next-all on"></a>
				</div>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
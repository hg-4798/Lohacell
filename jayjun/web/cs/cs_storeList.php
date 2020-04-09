<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="cs-page">

		<h2 class="page-title">매장안내</h2>

		<div class="inner-align page-frm clear">

			<?php include_once('../outline/cs_lnb.php') ?>
			<article class="cs-content">
				
				<section class="store-list cs">
					<header class="my-title">
						<h3 class="fz-0">매장안내</h3>
						<div class="count">전체 <strong>235</strong></div>
						<div class="align-input">
							<fieldset>
								<legend>매장 검색</legend>
								<div class="select">
									<select style="width:120px">
										<option value="">브랜드선택</option>
									</select>
								</div>
								<div class="select ml-5">
									<select style="width:120px">
										<option value="">지역선택</option>
									</select>
								</div>
								<input type="text" title="검색어 입력자리" placeholder="검색어를 입력해주세요" class="ml-5 w250">
								<button class="btn-point ml-5 w60 va-t" type="submit"><span>검색</span></button>
							</fieldset>
						</div>
					</header>
					<table class="th-top ">
						<caption>브랜드</caption>
						<colgroup>
							<col style="width:150px">
							<col style="width:180px">
							<col style="width:auto">
							<col style="width:150px">
							<col style="width:100px">
						</colgroup>
						<thead>
							<tr>
								<th scope="col">브랜드</th>
								<th scope="col">지점명</th>
								<th scope="col">주소</th>
								<th scope="col">전화번호</th>
								<th scope="col">영업시간</th>
							</tr>
						</thead>
						<tbody data-ui=TabMenu>
							<tr data-content="menu">
								<td>VanHart di Albazar</td>
								<td>롯데백화점 동대문점</td>
								<td class="subject"><div class="address">서울특별시 동대문구 롯데백화점 4층</div></td>
								<td>032-1233-1234</td>
								<td>10:00~22:00</td>
							</tr>
							<tr data-content="content">
								<td colspan="5" class="reset">
									<div class="map"><img src="../static/img/test/@map980x400.jpg" alt=""></div>
								</td>
							</tr>
							<tr data-content="menu">
								<td>VanHart di Albazar</td>
								<td>롯데백화점 동대문점</td>
								<td class="subject"><div class="address">서울특별시 동대문구 롯데백화점 4층</div></td>
								<td>032-1233-1234</td>
								<td>10:00~22:00</td>
							</tr>
							<tr data-content="content">
								<td colspan="5" class="reset">
									<div class="map"><img src="../static/img/test/@map980x400.jpg" alt=""></div>
								</td>
							</tr>
							<tr data-content="menu">
								<td>VanHart di Albazar</td>
								<td>롯데백화점 동대문점</td>
								<td class="subject"><div class="address">서울특별시 동대문구 롯데백화점 4층</div></td>
								<td>032-1233-1234</td>
								<td>10:00~22:00</td>
							</tr>
							<tr data-content="content">
								<td colspan="5" class="reset">
									<div class="map"><img src="../static/img/test/@map980x400.jpg" alt=""></div>
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
					
				</section>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
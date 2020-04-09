<?php
include_once('outline/header_m.php');
$page_cate = '매장';
?>

<!-- 내용 -->
<main id="content" class="subpage">
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>VIKI</span>
		</h2>
		<div class="breadcrumb">
			<?php include_once('brand_menu.php'); ?>
		</div>
	</section><!-- //.page_local -->

	<section class="brand_store">
		<div class="select_store">
			<dl class="search_store">
				<dd>
					<div class="input_search">
						<select class="select_line">
							<option value="">지역 선택</option>
							<option value=""></option>
							<option value=""></option>
						</select>
						<input type="text" class="w100-per" placeholder="매장명을 입력해 주세요.">
					</div>
					<a href="javascript:;" class="btn-point w100-per h-input mt-5">검색</a>
				</dd>
			</dl><!-- //.search_store -->

			<p class="store_result"><strong class="point-color">220</strong>개의 매장이 검색되었습니다.</p>

			<ul class="list_store mt-10">
				<li>
					<div class="info_area">
						<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점</p>
						<table class="tbl_txt">
							<colgroup>
								<col style="width:52px;">
								<col style="width:auto;">
							</colgroup>
							<tbody>
								<tr>
									<th>주소 :</th>
									<td>서울 강남구 언주역</td>
								</tr>
								<tr>
									<th>전화 :</th>
									<td>(02)1234-1111</td>
								</tr>
								<tr>
									<th>영업시간 :</th>
									<td>10:00~20:00</td>
								</tr>
							</tbody>
						</table>
						<a href="javascript:;" class="btn_map">지도보기</a>
					</div>
					<div class="map_area">
						<img src="static/img/test/@map_img.jpg" alt="지도"><!-- [D] 구글지도 연동 -->
					</div>
				</li>

				<li>
					<div class="info_area">
						<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점</p>
						<table class="tbl_txt">
							<colgroup>
								<col style="width:52px;">
								<col style="width:auto;">
							</colgroup>
							<tbody>
								<tr>
									<th>주소 :</th>
									<td>서울 강남구 언주역</td>
								</tr>
								<tr>
									<th>전화 :</th>
									<td>(02)1234-1111</td>
								</tr>
								<tr>
									<th>영업시간 :</th>
									<td>10:00~20:00</td>
								</tr>
							</tbody>
						</table>
						<a href="javascript:;" class="btn_map">지도보기</a>
					</div>
					<div class="map_area">
						<img src="static/img/test/@map_img.jpg" alt="지도"><!-- [D] 구글지도 연동 -->
					</div>
				</li>

				<li>
					<div class="info_area">
						<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점</p>
						<table class="tbl_txt">
							<colgroup>
								<col style="width:52px;">
								<col style="width:auto;">
							</colgroup>
							<tbody>
								<tr>
									<th>주소 :</th>
									<td>서울 강남구 언주역</td>
								</tr>
								<tr>
									<th>전화 :</th>
									<td>(02)1234-1111</td>
								</tr>
								<tr>
									<th>영업시간 :</th>
									<td>10:00~20:00</td>
								</tr>
							</tbody>
						</table>
						<a href="javascript:;" class="btn_map">지도보기</a>
					</div>
					<div class="map_area">
						<img src="static/img/test/@map_img.jpg" alt="지도"><!-- [D] 구글지도 연동 -->
					</div>
				</li>

				<li>
					<div class="info_area">
						<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점</p>
						<table class="tbl_txt">
							<colgroup>
								<col style="width:52px;">
								<col style="width:auto;">
							</colgroup>
							<tbody>
								<tr>
									<th>주소 :</th>
									<td>서울 강남구 언주역</td>
								</tr>
								<tr>
									<th>전화 :</th>
									<td>(02)1234-1111</td>
								</tr>
								<tr>
									<th>영업시간 :</th>
									<td>10:00~20:00</td>
								</tr>
							</tbody>
						</table>
						<a href="javascript:;" class="btn_map">지도보기</a>
					</div>
					<div class="map_area">
						<img src="static/img/test/@map_img.jpg" alt="지도"><!-- [D] 구글지도 연동 -->
					</div>
				</li>

				<li>
					<div class="info_area">
						<p class="store_name"><span class="brand">[VIKI]</span> 강남직영점</p>
						<table class="tbl_txt">
							<colgroup>
								<col style="width:52px;">
								<col style="width:auto;">
							</colgroup>
							<tbody>
								<tr>
									<th>주소 :</th>
									<td>서울 강남구 언주역</td>
								</tr>
								<tr>
									<th>전화 :</th>
									<td>(02)1234-1111</td>
								</tr>
								<tr>
									<th>영업시간 :</th>
									<td>10:00~20:00</td>
								</tr>
							</tbody>
						</table>
						<a href="javascript:;" class="btn_map">지도보기</a>
					</div>
					<div class="map_area">
						<img src="static/img/test/@map_img.jpg" alt="지도"><!-- [D] 구글지도 연동 -->
					</div>
				</li>

				<!-- [D] 검색결과가 없는 경우 -->
				<li class="result_none">검색된 매장이 없습니다.</li>
			</ul><!-- //.list_store -->
		</div>
	</section>

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
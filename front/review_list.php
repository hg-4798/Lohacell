<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
?>

<?php include($Dir.MainDir.$_data->menu_type.".php") ?>

<div id="contents">
	<div class="review_wrp">
		<div class="inner_width">
			<ul class="breadcrumb">
				<li>
					<a href="/">HOME</a>
				</li>
				<li>
					<a href="">REVIEW</a>
				</li>
				<li class="now">
					<a href="">PHOTO REVIEW</a>
				</li>
			</ul>
			<div class="sub_tit_area">
				<h2 class="title">PHOTO REVIEW</h2>
			</div>
		</div>

		<div class="best_review">
			<div class="inner_width">
				<div class="main_title">
					<h2>BEST REVIEW</h2>
					<p class="exp">i KNOW iONE 고객들의 생생 리뷰입니다</p>
				</div>
				<ul class="review_list">
					<li>
						<a href="">
							<figure class="post">
								<div class="img" style="background-image:url('/jayjun/web/static/img/test/@best_review01.jpg')"></div>
								<figcaption>
									<p class="subject">I TOUCH CHEEK BLOSSOM</p>
									<p class="text">힘없이 쳐지는 속눈썹에 힘과 탄력을 부여한다고 했는데, 써본지 1주일 정도 밖에 지나지 않았는데.</p>
									<p class="writer">iseeyou***</p>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="">
							<figure class="post">
								<div class="img" style="background-image:url('/jayjun/web/static/img/test/@best_review02.jpg')"></div>
								<figcaption>
									<p class="subject">I LOVE LIP STICK</p>
									<p class="text">솔직히 별로 기대안했는데 진짜 생각 이상입니다. 발색좋고 촉촉하니 정말 좋아요.</p>
									<p class="writer">friend***</p>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="">
							<figure class="post">
								<div class="img" style="background-image:url('/jayjun/web/static/img/test/@best_review03.jpg')"></div>
								<figcaption>
									<p class="subject">I NEED U CUSHION SPF50+</p>
									<p class="text">블러셔 사용하고 싶은데 능숙하지 못해서 진짜 은은하게 한듯안한듯한 자연스러운 컬러가 너무 맘에... </p>
									<p class="writer">bluemoon***</p>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="">
							<figure class="post">
								<div class="img" style="background-image:url('/jayjun/web/static/img/test/@best_review04.jpg')"></div>
								<figcaption>
									<p class="subject">I NEED GLOW SHIMMER BASE</p>
									<p class="text">클렌징밤 사러 갔다가 테스트만 해보고 결국 이녀석을 구매했는데 완전 인생템 될꺼 같아요.</p>
									<p class="writer">iknowlif***</p>
								</figcaption>
							</figure>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="list_area">
			<div class="inner_width">
				<div class="board-title is-function">
					<div class="title"><strong>총 123건</strong>의 상품평이 작성되었습니다.</div>
					<div class="function">
						<div class="select is-custom">
							<select title="">
								<option>최신순</option>
							</select>
						</div>
						<div class="form-search">
							<form>
								<fieldset class="v-hidden">검색</fieldset>
								<input type="text" title="검색어 입력" placeholder="상품명을 입력해주세요.">
								<button type="submit"><i class="icon-sch">검색</i></button>
							</form>
						</div>
					</div>
				</div>

				<table class="th-top">
					<caption>테이블 제목</caption>
					<colgroup>
						<col style="width:100px">
						<col style="width:120px">
						<col style="width:auto">
						<col style="width:160px">
						<col style="width:160px">
					</colgroup>
					<thead>
						<tr>
							<th scope="col">no.</th>
							<th scope="col" colspan="2">제목</th>
							<th scope="col">작성일</th>
							<th scope="col">작성자</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>123</td>
							<td><div class="thumb" style="background:url(../jayjun/web/static/img/test/@goods_380_01.jpg) no-repeat;"></div></td>
							<td>
								<a href="" class="subject">
									<strong>I NEED GLOW SHIMMER BASE</strong>
									<span>아주 잘 사용 하고 있어요~ <img src="/jayjun/web/static/img/icon/rating_score4.png" alt="5점 만점중 4점"></span>
								</a>
							</td>
							<td>2018.08.30</td>
							<td><span class="text-toneB">vivivi***</span></td>
						</tr>
						<tr>
							<td>122</td>
							<td><div class="thumb" style="background:url(../jayjun/web/static/img/test/@goods_380_02.jpg) no-repeat;"></div></td>
							<td>
								<a href="" class="subject">
									<strong>I LOVE LIP STICK GBE 101</strong>
									<span>인생템을 찾았어요! <img src="/jayjun/web/static/img/icon/rating_score5.png" alt="5점 만점중 5점"></span>
								</a>
							</td>
							<td>2018.08.29</td>
							<td><span class="text-toneB">pou***</span></td>
						</tr>
						<tr>
							<td>121</td>
							<td><div class="thumb" style="background:url(../jayjun/web/static/img/test/@goods_380_03.jpg) no-repeat;"></div></td>
							<td>
								<a href="" class="subject">
									<strong>I NEED GLOW SHIMMER BASE</strong>
									<span>아주 잘 사용 하고 있어요~ <img src="/jayjun/web/static/img/icon/rating_score4.png" alt="5점 만점중 4점"></span>
								</a>
							</td>
							<td>2018.08.28</td>
							<td><span class="text-toneB">lovel***</span></td>
						</tr>
					</tbody>
				</table>

				<p class="att">※ 리뷰 게시판은 제이준코스메틱 쇼핑몰 회원이 구매한 제품에 대한 이용후기를 공유하는 게시판입니다. 리뷰 작성을 원하시면 <a href="">바로가기</a>를 눌러주세요. </p>

				<div class="list-paginate">
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
					<a href="#" class="next "></a>
					<a href="#" class="next-all "></a>
				</div>

			</div>
		</div>

	</div>
</div>

<div id="create_openwin" style="display:none"></div>

<?php  include ($Dir."lib/bottom.php") ?>
<?=$onload?>
</BODY>
</HTML>


<?php
include_once('outline/header_m.php');
$page_cate = '매거진';
?>

<!-- 내용 -->
<main id="content" class="subpage">
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>스타일</span>
		</h2>
		<div class="breadcrumb">
			<?php include_once('style_menu.php'); ?>
		</div>
	</section><!-- //.page_local -->

	<section class="brand_lookbook">
		<div class="wrap_select">
			<ul class="ea2">
				<li>
					<select class="select_line">
						<option value="">2016 F/W</option>
						<option value="">2016 S/S</option>
						<option value="">2015 F/W</option>
					</select>
				</li>
				<li>
					<select class="select_line">
						<option value="">최신순</option>
						<option value="">좋아요순</option>
						<option value="">조회수순</option>
					</select>
				</li>
			</ul>
		</div><!-- //.wrap_select -->

		<div>
			<ul class="lookbook_list">
				<li>
					<figure>
						<a href="#">
							<div class="img"><img src="static/img/test/@magazine_list01.jpg" alt="매거진 이미지"></div>
							<figcaption class="info">
								<p class="brand">여행을 좋아하는 당신의 패션</p>
								<p class="name">2017.02.03</p>
							</figcaption>
						</a>
						<div class="btn_like_area">
							<div class="dim"></div>
							<button type="button" class="btn_like on" title="선택됨"><!-- [D] 좋아요 클릭한 상품의 경우 .on 클래스 추가 -->
								<span class="icon">좋아요</span>
								<span class="count">23</span>
							</button>
						</div>
					</figure>
				</li>
				<li>
					<figure>
						<a href="#">
							<div class="img"><img src="static/img/test/@magazine_list02.jpg" alt="매거진 이미지"></div>
							<figcaption class="info">
								<p class="brand">여행을 좋아하는 당신의 패션</p>
								<p class="name">2017.02.03</p>
							</figcaption>
						</a>
						<div class="btn_like_area">
							<div class="dim"></div>
							<button type="button" class="btn_like" title="선택 안됨">
								<span class="icon">좋아요</span>
								<span class="count">253</span>
							</button>
						</div>
					</figure>
				</li>
				<li>
					<figure>
						<a href="#">
							<div class="img"><img src="static/img/test/@magazine_list03.jpg" alt="매거진 이미지"></div>
							<figcaption class="info">
								<p class="brand">여행을 좋아하는 당신의 패션</p>
								<p class="name">2017.02.03</p>
							</figcaption>
						</a>
						<div class="btn_like_area">
							<div class="dim"></div>
							<button type="button" class="btn_like" title="선택 안됨">
								<span class="icon">좋아요</span>
								<span class="count">23</span>
							</button>
						</div>
					</figure>
				</li>
				<li>
					<figure>
						<a href="#">
							<div class="img"><img src="static/img/test/@magazine_list04.jpg" alt="매거진 이미지"></div>
							<figcaption class="info">
								<p class="brand">여행을 좋아하는 당신의 패션</p>
								<p class="name">2017.02.03</p>
							</figcaption>
						</a>
						<div class="btn_like_area">
							<div class="dim"></div>
							<button type="button" class="btn_like" title="선택 안됨">
								<span class="icon">좋아요</span>
								<span class="count">253</span>
							</button>
						</div>
					</figure>
				</li>
			</ul>
			<div class="read_more_line"><a href="javascript:;">READ MORE</a></div>
		</div><!-- //[D] 리스트 디폴트 10개, 더보기 클릭시 10개씩 추가로 리스팅 -->

	</section>

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
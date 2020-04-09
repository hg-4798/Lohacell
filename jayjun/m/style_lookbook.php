<?php
include_once('outline/header_m.php');
$page_cate = '룩북';
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

	<section class="mypage_like sub_bdtop">
		<div class="wrap_select">
			<ul>
				<li>
					<select class="select_line">
						<option value="">BESTIBELLI</option>
						<option value="">VIKI</option>
						<option value="">SI</option>
						<option value="">ISABEY</option>
						<option value="">SIEG</option>
						<option value="">SIEG FAHRENHEIT</option>
						<option value="">VanHart di Albazar</option>
					</select>
				</li>
			</ul>
			<ul class="ea2 mt-5">
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
			<ul class="lookbook_list grid_col2">
				<li class="grid_item">
					<figure>
						<a href="#">
							<div class="img"><img src="static/img/test/@lookbook_list01.jpg" alt="상품 이미지"></div>
							<figcaption class="info">
								<p class="brand">VIKI</p>
								<p class="name">2016 LOOKBOOK</p>
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
				<li class="grid_item">
					<figure>
						<a href="#">
							<div class="img"><img src="static/img/test/@lookbook_list09.jpg" alt="상품 이미지"></div>
							<figcaption class="info">
								<p class="brand">SIEG FAHRENHEIT</p>
								<p class="name">2016 LOOKBOOK</p>
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
				<li class="grid_item">
					<figure>
						<a href="#">
						<div class="img"><img src="static/img/test/@lookbook_list04.jpg" alt="상품 이미지"></div>
							<figcaption class="info">
								<p class="brand">VIKI</p>
								<p class="name">2016 LOOKBOOK</p>
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
				<li class="grid_item">
					<figure>
						<a href="#">
						<div class="img"><img src="static/img/test/@lookbook_list10.jpg" alt="상품 이미지"></div>
							<figcaption class="info">
								<p class="brand">ISABEY</p>
								<p class="name">2016 LOOKBOOK</p>
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
				<li class="grid_item">
					<figure>
						<a href="#">
						<div class="img"><img src="static/img/test/@lookbook_list11.jpg" alt="상품 이미지"></div>
							<figcaption class="info">
								<p class="brand">BESTI BELLI</p>
								<p class="name">2016 LOOKBOOK</p>
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
				<li class="grid_item">
					<figure>
						<a href="#">
						<div class="img"><img src="static/img/test/@likelist04.jpg" alt="상품 이미지"></div>
							<figcaption class="info">
								<p class="brand">VIKI</p>
								<p class="name">2016 LOOKBOOK</p>
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
			<div class="read_more_line mt-10"><a href="javascript:;">READ MORE</a></div><!-- [D] 디폴트 10개(더보기 클릭시 10개씩 노출) -->
		</div>
		
	</section><!-- //.mypage_like -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
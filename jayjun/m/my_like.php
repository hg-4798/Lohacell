<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>좋아요</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="mypage_like sub_bdtop">

		<div class="wrap_select">
			<select class="select_line">
				<option value="">ALL</option>
				<option value="">상품</option>
				<option value="">E-CATALOG</option>
				<option value="">룩북</option>
				<option value="">매거진</option>
				<option value="">인스타그램</option>
				<option value="">MOVIE</option>
			</select>
		</div>

		<div>
			<ul class="lookbook_list grid_col2">
				<li class="grid_item">
					<figure>
						<a href="#">
							<div class="img"><img src="static/img/test/@lookbook_list01.jpg" alt="상품 이미지"></div>
							<figcaption class="info">
								<p class="brand">BESTI BELLI</p>
								<p class="name">솔리드 심플 벨티트 자켓</p>
							</figcaption>
						</a>
						<div class="btn_like_area">
							<div class="dim"></div>
							<button type="button" class="btn_like on" title="선택됨"><!-- [D] 하트를 클릭하여 좋아요 삭제 가능(삭제시 리스트에서 사라짐) -->
								<span class="icon">좋아요</span>
								<span class="count">23</span>
							</button>
						</div>
					</figure>
				</li>
				<li class="grid_item">
					<figure>
						<a href="#">
							<div class="img"><img src="static/img/test/@likelist01.jpg" alt="상품 이미지"></div>
							<figcaption class="info">
								<p class="brand">룩북</p>
								<p class="name">2017 s/s신상 기획전</p>
							</figcaption>
						</a>
						<div class="btn_like_area">
							<div class="dim"></div>
							<button type="button" class="btn_like on" title="선택됨">
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
								<p class="brand">MOVIE</p>
								<p class="name">한채영 화보촬영 현장</p>
							</figcaption>
						</a>
						<div class="btn_like_area">
							<div class="dim"></div>
							<button type="button" class="btn_like on" title="선택됨">
								<span class="icon">좋아요</span>
								<span class="count">23</span>
							</button>
						</div>
					</figure>
				</li>
				<li class="grid_item">
					<figure>
						<a href="#">
						<div class="img"><img src="static/img/test/@likelist02.jpg" alt="상품 이미지"></div>
							<figcaption class="info">
								<p class="brand">인스타그램</p>
								<p class="name">최근 구매한 솔리드 심플 벨티트 자켓 입니다.</p>
							</figcaption>
						</a>
						<div class="btn_like_area">
							<div class="dim"></div>
							<button type="button" class="btn_like on" title="선택됨">
								<span class="icon">좋아요</span>
								<span class="count">253</span>
							</button>
						</div>
					</figure>
				</li>
				<li class="grid_item">
					<figure>
						<a href="#">
						<div class="img"><img src="static/img/test/@likelist03.jpg" alt="상품 이미지"></div>
							<figcaption class="info">
								<p class="brand">BESTI BELLI</p>
								<p class="name">솔리드 심플 벨티트 자켓</p>
							</figcaption>
						</a>
						<div class="btn_like_area">
							<div class="dim"></div>
							<button type="button" class="btn_like on" title="선택됨">
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
								<p class="brand">매거진</p>
								<p class="name">솔리드 심플 벨티트 자켓</p>
							</figcaption>
						</a>
						<div class="btn_like_area">
							<div class="dim"></div>
							<button type="button" class="btn_like on" title="선택됨">
								<span class="icon">좋아요</span>
								<span class="count">253</span>
							</button>
						</div>
					</figure>
				</li>
			</ul>
			<div class="read_more_line mt-10"><a href="javascript:;">READ MORE</a></div><!-- [D] 디폴트 8개(더보기 클릭시 8개씩 노출) -->
		</div>
		
	</section><!-- //.mypage_like -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
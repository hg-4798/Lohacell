<?php
include_once('outline/header_m.php');
$page_cate = '기획전';
?>

<!-- 내용 -->
<main id="content" class="subpage">
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>프로모션</span>
		</h2>
		<div class="breadcrumb">
			<?php include_once('promotion_menu.php'); ?>
		</div>
	</section><!-- //.page_local -->

	<section class="photo_type_view">
		<h4 class="title_area with_brand">
			<span class="brand">아울렛 입고상품 30% 할인</span>
			<span class="date">2017.01.14~2017.01.14</span>
		</h4>

		<div><!-- [D] 에디터 영역 -->
			<img src="static/img/test/@promo_view01.jpg" alt="기획전 이미지">
		</div>

		<div class="btns mt-20">
			<ul>
				<li><a href="promotion.php" class="icon_list">목록</a></li>
				<li><a href="javascript:;" class="icon_like" title="선택 안됨">좋아요</a> <span class="count">23</span></li><!-- [D] 클릭시 좋아요 숫자+1, 재클릭시 좋아요 숫자-1 -->
				<li>
					<div class="wrap_bubble layer_sns_share on">
						<div class="btn_bubble"><button type="button" class="btn_sns_share">sns 공유</button></div>
						<div class="pop_bubble">
							<div class="inner">
								<button type="button" class="btn_pop_close">닫기</button>
								<div class="icon_container">
									<a href="javascript:;"><img src="static/img/icon/icon_sns_kas.png" alt="카카오스토리"></a>
									<a href="javascript:;"><img src="static/img/icon/icon_sns_face.png" alt="페이스북"></a>
									<a href="javascript:;"><img src="static/img/icon/icon_sns_twit.png" alt="트위터"></a>
									<a href="javascript:;"><img src="static/img/icon/icon_sns_band.png" alt="밴드"></a>
									<a href="javascript:;"><img src="static/img/icon/icon_sns_link.png" alt="url"></a>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div><!-- //.btns -->

		<div class="other_posting">
			<dl>
				<dt>PREV</dt>
				<dd><a href="#">비오는 날을 좋아하는 당신의 패션</a></dd>
			</dl>
			<dl>
				<dt>NEXT</dt>
				<dd><a href="#">햇볕 좋은 날을 좋아하는 당신의 패션</a></dd>
			</dl>
		</div><!-- //.other_posting -->

		<div class="pr_category divide-box-wrap two mt-30">
			<ul class="divide-box">
				<li><a href="#prList01">SIEG</a></li>
				<li><a href="#prList02">SIEG FAHRENHEIT</a></li>
				<li><a href="#prList01">SIEG</a></li>
				<li><a href="#prList02">SIEG FAHRENHEIT</a></li>
				<li><a href="#prList01">SIEG</a></li>
			</ul>
		</div><!-- //.pr_category -->

		<div id="prList01" class="wrap_prgoods">
			<h5><span>SIEG</span></h5>
			<div class="prgoods">
				<ul class="goodslist col1">
					<li>
						<a href="#">
							<figure>
								<div class="img"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></div>
								<figcaption>
									<p class="brand">BESTI BELLI</p>
									<p class="name">솔리드 심플 벨티트 자켓 </p>
									<p class="price">￦ 105,800</p>
								</figcaption>
							</figure>
						</a>
					</li>
				</ul><!-- //.goodslist -->
				<ul class="goodslist mt-20">
					<li>
						<a href="#">
							<figure>
								<div class="img"><img src="static/img/test/@goodslist_02.jpg" alt="상품 이미지"></div>
								<figcaption>
									<p class="brand">BESTI BELLI</p>
									<p class="name">솔리드 심플 벨티트 자켓</p>
									<p class="price"><del>￦ 1,005,800</del>￦ 315,900</p><!-- [D] 할인가인 경우 -->
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="#">
							<figure>
								<div class="img"><img src="static/img/test/@goodslist_10.jpg" alt="상품 이미지"></div>
								<figcaption>
									<p class="brand">BESTI BELLI</p>
									<p class="name">솔리드 심플 벨티트 자켓</p>
									<p class="price">￦ 105,800</p>
								</figcaption>
							</figure>
						</a>
					</li>
				</ul><!-- //.goodslist -->
			</div><!-- //.prgoods -->
		</div><!-- //.wrap_prgoods -->

		<div id="prList02" class="wrap_prgoods">
			<h5><span>SIEG FAHRENHEIT</span></h5>
			<div class="prgoods">
				<ul class="goodslist col1">
					<li>
						<a href="#">
							<figure>
								<div class="img"><img src="static/img/test/@goodslist_09.jpg" alt="상품 이미지"></div>
								<figcaption>
									<p class="brand">BESTI BELLI</p>
									<p class="name">솔리드 심플 벨티트 자켓 </p>
									<p class="price">￦ 105,800</p>
								</figcaption>
							</figure>
						</a>
					</li>
				</ul><!-- //.goodslist -->
				<ul class="goodslist mt-20">
					<li>
						<a href="#">
							<figure>
								<div class="img"><img src="static/img/test/@goodslist_11.jpg" alt="상품 이미지"></div>
								<figcaption>
									<p class="brand">BESTI BELLI</p>
									<p class="name">솔리드 심플 벨티트 자켓</p>
									<p class="price">￦ 315,900</p>
								</figcaption>
							</figure>
						</a>
					</li>
					<li>
						<a href="#">
							<figure>
								<div class="img"><img src="static/img/test/@goodslist_12.jpg" alt="상품 이미지"></div>
								<figcaption>
									<p class="brand">BESTI BELLI</p>
									<p class="name">솔리드 심플 벨티트 자켓</p>
									<p class="price">￦ 105,800</p>
								</figcaption>
							</figure>
						</a>
					</li>
				</ul><!-- //.goodslist -->
			</div><!-- //.prgoods -->
		</div><!-- //.wrap_prgoods -->

	</section><!-- //.photo_type_view -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
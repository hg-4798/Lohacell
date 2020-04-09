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

		<div class="wrap_prgoods">
			<div class="prgoods">
				<ul class="prgoods_detail">
					<li>
						<a href="#">
							<div class="img"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></div>
							<div class="goods_info">
								<p class="brand">BESTI BELLI</p>
								<p class="name">솔리드 심플 벨티트 자켓<span class="code">(toefoe16561)</span></p>
								<p class="price">
									<strong>￦ 105,800</strong><del>￦ 105,800</del>
									<span class="tag_discount"><strong>20</strong>% <img src="static/img/icon/icon_darr.png" alt="할인"></span>
								</p>
								<p class="text">깔끔한 디자인의 원피스입니다.<br>두툼한 소재로 만들어 초가을까지 입으실 수 있습니다. 178cm 마네킹이 66사이즈를 착용하였습니다.</p>
							</div>
						</a>
						<div class="btn_area">
							<ul class="ea2">
								<li><a href="javascript:;" class="btn_addcart btn-line h-large"><span class="icon_cart_black"></span>장바구니</a></li>
								<li><a href="javascript:;" class="btn_like btn-line h-large"><span class="icon_like"></span>좋아요 <span class="point-color">(302)</span></a></li>
							</ul>
						</div>
					</li>
					<li>
						<a href="#">
							<div class="img"><img src="static/img/test/@goodslist_02.jpg" alt="상품 이미지"></div>
							<div class="goods_info">
								<p class="brand">BESTI BELLI</p>
								<p class="name">솔리드 심플 벨티트 자켓<span class="code">(toefoe16561)</span></p>
								<p class="price">
									<strong>￦ 105,800</strong><del>￦ 105,800</del>
									<span class="tag_discount"><strong>20</strong>% <img src="static/img/icon/icon_darr.png" alt="할인"></span>
								</p>
								<p class="text">깔끔한 디자인의 원피스입니다.<br>두툼한 소재로 만들어 초가을까지 입으실 수 있습니다. 178cm 마네킹이 66사이즈를 착용하였습니다.</p>
							</div>
						</a>
						<div class="btn_area">
							<ul class="ea2">
								<li><a href="javascript:;" class="btn_addcart btn-line h-large"><span class="icon_cart_black"></span>장바구니</a></li>
								<li><a href="javascript:;" class="btn_like btn-line h-large"><span class="icon_like"></span>좋아요 <span class="point-color">(302)</span></a></li>
							</ul>
						</div>
					</li>
				</ul><!-- //.prgoods_detail -->
			</div><!-- //.prgoods -->
		</div><!-- //.wrap_prgoods -->

	</section><!-- //.photo_type_view -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
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

	<section class="photo_type_view">
		<h4 class="title_area with_brand">
			<span class="brand">VIKI</span>
			<span class="tit">2016 신상 컬렉션</span> <span class="date">2017.01.14</span>
		</h4>
		<div class="img_area">
			<div class="img"><img src="static/img/test/@lookbook_view02.jpg" alt="룩북 이미지"></div>
			<a href="#" class="btn_prev">이전</a><!-- [D] 클릭시 이전글로 이동 -->
			<a href="#" class="btn_next">다음</a><!-- [D] 클릭시 다음글로 이동 -->
		</div>

		<div class="btns">
			<ul>
				<li><a href="style_lookbook.php" class="icon_list">목록</a></li>
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
		</div>

		<div class="list_area">
			<ul class="goodslist">
				<li>
					<a href="#">
						<figure>
							<div class="img"><img src="static/img/test/@goodslist_01.jpg" alt="상품 이미지"></div>
							<figcaption>
								<p class="name">솔리드 심플 벨티트 자켓 </p><!-- [D] 두줄 이상 넘어가면 말줄임(모든 상품리스트 동일) -->
							</figcaption>
						</figure>
					</a>
				</li>
				<li>
					<a href="#">
						<figure>
							<div class="img"><img src="static/img/test/@goodslist_02.jpg" alt="상품 이미지"></div>
							<figcaption>
								<p class="name">솔리드 심플 벨티트 자켓</p>
							</figcaption>
						</figure>
					</a>
				</li>
				<li>
					<a href="#">
						<figure>
							<div class="img"><img src="static/img/test/@goodslist_09.jpg" alt="상품 이미지"></div>
							<figcaption>
								<p class="name">솔리드 심플 벨티트 자켓</p>
							</figcaption>
						</figure>
					</a>
				</li>
				<li>
					<a href="#">
						<figure>
							<div class="img"><img src="static/img/test/@goodslist_10.jpg" alt="상품 이미지"></div>
							<figcaption>
								<p class="name">솔리드 심플 벨티트 자켓 라쿤털 후드</p>
							</figcaption>
						</figure>
					</a>
				</li>
			</ul>
		</div>
	</section>

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
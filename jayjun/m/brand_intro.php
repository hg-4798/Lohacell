<?php
include_once('outline/header_m.php');
$page_cate = '브랜드 소개';
?>

<!-- 내용 -->
<main id="content" class="subpage fullh">
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>VIKI</span>
		</h2>
		<div class="breadcrumb">
			<?php include_once('brand_menu.php'); ?>
		</div>
	</section><!-- //.page_local -->

	<section class="brand_intro">
		<div class="img"><img src="static/img/test/@brand_img02.jpg" alt="브랜드 이미지"></div>
		<a href="brand_shop.php" class="btn_shop">SHOP</a>
		<div class="box_txt">
			<dl>
				<dt>STORY</dt>
				<dd>이탈리아어로 ‘가장 아름답다’는 뜻을 가지고 있는 베스띠벨리는 1990년대에 처음 선보인 이래, 국내 여성복을 대표하는 브랜드로 성장해 왔습니다.</dd>
			</dl>
			<dl>
				<dt>CONCEPT</dt>
				<dd>세련되면서 지적인 이미지의 미니멀 무드와 완성도 높은 테일러링으로 컨템포러리하면서도 트렌디한 감성을 추구합니다. <br>토털 코디네이션을 지향해온 BESTIBELLI는 현재 의류를 비롯해, 신발, 가방, 액세서리에 이르기까지 다양한 제품 라인업을 선보이고 있습니다.</dd>
			</dl>
			<dl>
				<dt>NEW PRODUCT</dt>
				<dd>멋쟁이를 위한 필수 아이템 겨울 코트</dd>
			</dl>
		</div>
	</section>

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
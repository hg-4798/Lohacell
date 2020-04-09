<?php
$sql="select * from tblstaritem where hidden = 1 order by sort asc, no desc";
$result =pmysql_query($sql);
$img_path = "/data/shopimages/staritem/";
$banner_img_path = "http://www.JayJun.com/SE2/images/2017/best.gif";

// ==================================================================================
// 대카테고리 정보 조회
// ==================================================================================
$arrCategoryCode = array("");    // 전체

$category_sql  = "select * from tblproductcode where code_b = '000' and is_hidden = 'N' order by cate_sort asc ";
$category_result = pmysql_query($category_sql);

$categoryHtml = "";
$categoryHtml .= '<li class="js-brand-menu-content CLASS_TAB on" id="tab_ALL"><a href="javascript:;" onClick="javascript:changeStarItemTab(\'ALL\');"><span>ALL</span></a></li>';


while( $category_row = pmysql_fetch_array( $category_result ) ){
    $categoryHtml .= '<li class="js-brand-menu-content CLASS_TAB" id="tab_'. $category_row['code_a'] .'""><a href="javascript:;" onClick="javascript:changeStarItemTab(\'' . $category_row['code_a'] . '\');"><span>' . $category_row['code_name'] . '</span></a></li>';
}

?>

	<div class="sub-title">
        <h2>지금 가장 뜨거운 사랑을 받는 아이템</h2> <!--디자이너가 추천하는 <?=date("n")?>월의 스타상품 -> 지금 가장 뜨거운 사랑을 받는 아이템 -->
		<a class="btn-prev" href="javascript:history.go(-1);"><img src="./static/img/btn/btn_page_prev.png" alt="이전 페이지"></a>
	</div>

	<div class="promotion-area">
		<img src="<?=$banner_img_path?>" alt="STAR ITEM 배너">
		<div class="staritem category-tab-wrap">
        <!-- 슬라이드 메뉴 -->
        <ul class="star_slide">
			<?=$categoryHtml?>
		</ul>
        <!-- // 슬라이드 메뉴 -->

		<ul class="staritem-list">
			<?while( $row = pmysql_fetch_array($result) ){?>
			<li>
				<?php if($row['samechk_link']){?>
				<a href="<?= str_replace("/front/","/m/",$row['link'])?>">
				<?php } else {?>
				<a href="<?= $row['link_m']?>">
				<?php }?>
					<div class="brand-nm"><?=$row['brandtitle']?></div>
					<div class="caption">
						<p class="tit"><?=$row['title']?></p>
						<p class="comment"><?=$row['subtitle']?></p>
					</div>
					<?php if($row['samechk_img']){?>
					<div class="thumb"><img src="<?=$img_path.$row['img']?>" alt="STAR ITEM 이미지"></div>
					<?php } else {?>
					<div class="thumb"><img src="<?=$img_path.$row['img_m']?>" alt="STAR ITEM 이미지"></div>
					<?php }?>
				</a>
			</li>
			<?php }?>
		</ul>

	</div>

<script type="text/javascript">

    function changeStarItemTab(cate_code) {
        $(".CLASS_TAB").removeClass("on");
        $("#tab_"+cate_code).addClass("on");

        $.ajax({
            type: "get",
            url: "/front/ajax_get_staritem_category_list.php",
            data: 'cate_code='+cate_code
        }).success(function ( result ) {
            $('.staritem-list').empty();
            $('.staritem-list').append(result);
        });
    }

</script>
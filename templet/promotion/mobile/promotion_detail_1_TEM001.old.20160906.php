<?php include($Dir.TempletDir."promotion/mobile/promotion_navi_TEM001.php"); ?>

<!-- 프로모션 내용 -->
<article class="promo-detail-content">
    <div class="promo-title">
        <h3><strong><?=$title?></strong><span class="date"><?=$start_date?>~<?=$end_date?></span></h3>
        <button class="btn-share" onclick="popup_open('#popup-sns');return false;"><span class="ir-blind">공유</span></button>
    </div>

    <?php if ( $image_type_m == "E" ) { ?>
    <div class="promo-content-inner">
        <?=$content_m?>
    </div>
    <?php } elseif ( !empty($banner_img_m) ) { ?>
    <div class="promo-content-inner">
        <img src="/data/shopimages/timesale/<?=$banner_img_m?>" alt="">
    </div>
    <?php } ?>
</article>
<!-- // 프로모션 내용 -->

<!-- 프로모션 상품 -->
<div class="promo-detail-goods">
    <div class="goods-anchor">
        <ul>
            <?=$promotion_tab_mobile_html?>
        </ul>
    </div>
    <?=$promotion_tablist_mobile_html?>

</div>
<!-- // 프로모션 상품 -->

<?=$view_more_html?>


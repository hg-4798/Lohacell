
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

<!-- 프로모션 포토 -->
<section class="promo-detail-photo">
    <h4>TOTAL <strong>(<?=number_format($t_count)?>)</strong></h4>
    <div class="photo-list">
        <ul>
            <?=$list_html?>
        </ul>
    </div>
    <div class="btnwrap">
        <div class="box">
            <?php if(strlen($_ShopInfo->getMemid())==0) {?>
                <a class="btn-def" href="javascript:;" onClick="javascript:goLogin();">포토등록</a>
            <?} else { ?>
                <a class="btn-def" href="?<?=$_SERVER['QUERY_STRING']?>&mode=write">포토등록</a>
            <?} ?>
        </div>
    </div>

    <?php
        if( $paging->pagecount > 1 ){
    ?>
        <div class="paginate">
            <div class="box">
                    <?php echo $paging->a_prev_page.$paging->print_page.$paging->a_next_page; ?>
            </div>
        </div>
    <?php
        }
    ?>
</section>
<!-- // 프로모션 포토 -->

<?=$view_more_html?>

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

<!-- 프로모션 댓글 -->
<section class="promo-detail-comment">
    <form class="reply-reg-form" method=post name=comment_form action="/board/board.php" onSubmit="return chkCommentForm();">
        <input type=hidden name=pagetype value="promotion_comment_result">
        <input type=hidden name=board value="event">
        <input type=hidden name=num value="<?=$idx?>">
        <input type=hidden name=block value="<?=$block?>">
        <input type=hidden name=gotopage value="<?=$gotopage?>">
        <input type=hidden name=search value="<?=$search?>">
        <input type=hidden name=s_check value="<?=$s_check?>">
        <input type=hidden name=event_type value="<?=$event_type?>">
        <input type=hidden name=view_mode value="<?=$view_mode?>">
        <input type=hidden name=view_type value="<?=$view_type?>">
        <input type=hidden name=mode value="up">
        <input type=hidden name=is_mobile value="<?=$isMobile?>" >
        <input type=hidden id="messagebyte" value="0" >
        <!--input type=hidden name='up_name' id="inpt-name" title="작성자 입력자리">
        <input type=hidden name='up_passwd' id="inpt-pwd" title="비밀전호 입력자리">
        <input type="checkbox" id="inpt-check" name='up_is_secret' value='1' -->

    <h4>COMMENT <strong>(<?=number_format($total_comment_count)?>)</strong></h4>

    <?   
        $placeHolderMsg = "로그인 하셔야 작성이 가능합니다.";   
        if ( strlen($_ShopInfo->getMemid()) > 0 ) {
            $placeHolderMsg = "댓글 작성 가능합니다.";
        }
    ?>

    <div class="comment-write" style="display:none;">
        <input type="text" placeholder="<?=$placeHolderMsg?>" title="댓글" id="up_comment" name="up_comment" onKeyUp="checkByte(this.form);" onFocus="clearMessage(this.form);">
        <!--button class="btn-def btn-write" type="button">등록</button-->
        <button class="btn-def btn-write" type="submit">등록</button>
    </div>
    <div class="comment-list">
        <ul>
            <?=$review_html?>
        </ul>
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

    </form>

</section>
<!-- // 프로모션 댓글 -->

<?=$view_more_html?>



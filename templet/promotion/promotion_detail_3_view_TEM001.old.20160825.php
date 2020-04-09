<?php
    // ==================================================================================
    // 상단 배너
    // ==================================================================================
    $sql    = "SELECT * FROM tblpromo where idx = '{$idx}' ";
    $result = pmysql_query($sql);
    $row    = pmysql_fetch_object($result);

    $title      = $row->title;
    $start_date = str_replace("-", ".", $row->start_date);
    $end_date   = str_replace("-", ".", $row->end_date);
    $banner_img = $row->banner_img;
    $banner_img_m = $row->banner_img_m;

    // ==================================================================================
    // 게시물 내용
    // ==================================================================================

    // 전체 게시물 수
    $sql    = "select count(*) from tblboard_promo where board = 'photo' AND promo_idx = {$idx} ";
    $row    = pmysql_fetch_object(pmysql_query($sql));
    $total_comment_count = $row->count;

    $sql    = "select * from tblboard_promo where board = 'photo' AND promo_idx = '{$idx}' AND num = {$num} ";
    $row    = pmysql_fetch_object(pmysql_query($sql));

    $title = $row->title;
    $content = $row->content;
    $filename1 = $row->vfilename;
    $filename2 = $row->vfilename2;
    $filename3 = $row->vfilename3;
    $filename4 = $row->vfilename4;
    $mem_id = $row->mem_id;

    $reg_date = date("Y/m/d H:i:s", $row->writetime);

    if ( $isMobile ) {
        $content_html = '';

        if ( $content ) { $content_html .= '<p>' . nl2br($content) . '</p>'; }
        if ( $filename1 ) { $content_html .= '<img src="/data/shopimages/board/photo/' . $filename1 . '" alt=""><br/>'; }
        if ( $filename2 ) { $content_html .= '<img src="/data/shopimages/board/photo/' . $filename2 . '" alt=""><br/>'; }
        if ( $filename3 ) { $content_html .= '<img src="/data/shopimages/board/photo/' . $filename3 . '" alt=""><br/>'; }
        if ( $filename4 ) { $content_html .= '<img src="/data/shopimages/board/photo/' . $filename4 . '" alt=""><br/>'; }
    } else {
        $content_html = '<p class="title">' . $title . ' <span class="date">' . $reg_date . '</span></p>
                <div class="view-content">';

        if ( $content ) { $content_html .= nl2br($content); }
        if ( $filename1 ) { $content_html .= '<img src="/data/shopimages/board/photo/' . $filename1 . '" alt=""><br/>'; }
        if ( $filename2 ) { $content_html .= '<img src="/data/shopimages/board/photo/' . $filename2 . '" alt=""><br/>'; }
        if ( $filename3 ) { $content_html .= '<img src="/data/shopimages/board/photo/' . $filename3 . '" alt=""><br/>'; }
        if ( $filename4 ) { $content_html .= '<img src="/data/shopimages/board/photo/' . $filename4 . '" alt=""><br/>'; }

        $content_html .= '</div>';
    }

    // 이전/다음 링크용
    $view_more_html = GetPhotoEventViewMore();

    // 수정용
    $article_title      = $title;
    $article_content    = $content;
    $article_filename1  = $filename1;
    $article_filename2  = $filename2;
    $article_filename3  = $filename3;
    $article_filename4  = $filename4;

    if ( $isMobile ) {
        if ( empty($mode) ) {
            include($Dir.TempletDir."promotion/mobile/promotion_detail_3_view_TEM001.php");
        } else {
            include($Dir.TempletDir."promotion/mobile/promotion_detail_3_write_TEM001.php");
        }
    } else {
?>

<SCRIPT LANGUAGE="JavaScript" src="/board/chk_form.js.php"></SCRIPT>

<?php include($Dir.TempletDir."promotion/promotion_detail_3_upload_layer_TEM001.php"); ?>

    <div id="contents">
        <div class="containerBody sub-page">

            <div class="breadcrumb">
                <ul>
                    <li><a href="#">HOME</a></li>
                    <li class="on"><a href="/front/promotion.php">PROMOTION</a></li>
                </ul>
            </div><!-- //.breadcrumb -->

            <div class="promotion-wrap">

                <div class="board-view">
                    <p class="title"><?=$title?> <span class="date"><?=$start_date?>~<?=$end_date?></span></p>
                    <div class="view-content">
                        <img src="/data/shopimages/timesale/<?=$banner_img?>" alt="">
                    </div>

                    <p class="ea">게시된 포토 (<?=number_format($total_comment_count)?>)</p>
                    <?=$content_html?>
                </div><!-- //.board-view -->

                <?=$view_more_html?>

                <div class="btn-place-view">
                    <?php if(strlen($_ShopInfo->getMemid())==0) {?>
                        <button class="btn-dib-function" type="button" onClick="javascript:goLogin();"><span>MODIFY</span></button>
                    <?php } elseif ( $mem_id === $_ShopInfo->getMemid() )  { ?>
                        <button class="btn-dib-function photo-event-write" type="button"><span>MODIFY</span></button>
                    <?php } ?>

                    <button class="btn-dib-function" type="button" id="photo_list_btn"><span>LIST</span></button>
                </div>

            </div><!-- //.promotion-wrap -->

        </div><!-- //공통 container -->
    </div><!-- //contents -->

<?
    }
?>

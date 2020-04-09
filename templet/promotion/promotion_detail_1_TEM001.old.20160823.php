<?php
    // ==================================================================================
    // 상단 배너
    // ==================================================================================
    $sql    = "SELECT * FROM tblpromo where idx = '{$idx}' ";   
    $result = pmysql_query($sql);
    $row    = pmysql_fetch_object($result);

    $title          = $row->title;
    $start_date     = str_replace("-", ".", $row->start_date);
    $end_date       = str_replace("-", ".", $row->end_date);
    $banner_img     = $row->banner_img;
    $banner_img_m   = $row->banner_img_m;
    $image_type     = $row->image_type;
    $image_type_m   = $row->image_type_m;
    $content        = $row->content;
    $content_m      = $row->content_m;
    $thumb_img      = $row->thumb_img;

    // ==================================================================================
    // 기획전 리스트
    // ==================================================================================
    $sql  = "SELECT * FROM tblpromotion ";
    $sql .= "WHERE promo_idx = '{$idx}' ";
//    $sql .= "WHERE promo_idx = '{$idx}' AND title <> '' ";
    $sql .= "ORDER BY display_seq asc "; // 노출순서 적용
    $result = pmysql_query($sql);

    $promotion_tab_html             = '';
    $promotion_tab_mobile_html      = '';

    $promotion_tablist_html         = '';
    $promotion_tablist_mobile_html  = '';

    while ($row = pmysql_fetch_array($result)) {
        $tab_name = "promotion-tab-" . $row['seq'];

        if ( !empty($row['title']) ) {
            $promotion_tab_html .= '<li><a href="#' . $tab_name . '">' . $row['title'] . '</a></li>';
            $promotion_tab_mobile_html .= '<li><a href="#' . $tab_name . '" onclick="scroll_anchor($(this).attr(\'href\'));return false;">' . $row['title'] . '</a></li>';
        } else {
            $promotion_tab_html .= "";
            $promotion_tab_mobile_html .= "";
        }

        $sub_sql        = "SELECT * FROM tblspecialpromo WHERE special = '" . $row['seq'] . "' ";
        $sub_result     = pmysql_query($sub_sql);
        $sub_row        = pmysql_fetch_object($sub_result);
        $special_list   = $sub_row->special_list;

        $arrProdCode = explode(",", $special_list);
        $productcodes = "'" . implode("','", $arrProdCode) . "'";

        // 프로모션 상품 리스트
        $prod_sql  = "SELECT productcode, productname, sellprice, consumerprice, brand, maximage, minimage, tinyimage, ";
        $prod_sql .= "mdcomment, review_cnt, icon, soldout, quantity, over_minimage ";
        $prod_sql .= "FROM tblproduct WHERE display = 'Y' and productcode in ( {$productcodes} ) ";

        if ( $isMobile ) {
            $arrProd = productlist_print($prod_sql, "W_015", $arrProdCode, count($arrProdCode));

            if ( !empty($row['title']) ) {
                $promotion_tablist_mobile_html .= '
                        <section id="' . $tab_name . '">
                            <h4>' . $row['title'] . '</h4>
                            <div class="goods-list">
                            ' . $arrProd[0] . '
                            </div>
                        </section>';
            }
        } else {
            $arrProd = productlist_print($prod_sql, "W_011", $arrProdCode);

            if ( !empty($row['title']) ) {
                $promotion_tablist_html .= '
                        <a name="' . $tab_name . '"></a>
                        <div class="promotion-product-list">
                            <span class="roof"></span>
                            <h4 class="title">' . $row['title'] . '</h3>
                            ' . $arrProd[0] . '
                        </div>';
            }
        }
    }

    // 이전/다음 링크용
    $view_more_html = GetPromotionViewMore($isMobile);
	$sns_text	    = "[".$_data->shoptitle."] PROMOTION - ".addslashes($title);
    $sns_thumb_img  = 'http://'.$_SERVER[HTTP_HOST].'/data/shopimages/timesale/'.$thumb_img;
?>

<?
    if ( $isMobile ) {
        include($Dir.TempletDir."promotion/mobile/promotion_detail_1_TEM001.php");
    } else {
?>
<div id="contents">
        <div class="containerBody sub-page">
            
            <div class="breadcrumb">
                <ul>
                    <li><a href="/">HOME</a></li>
                    <li class="on"><a href="/front/promotion.php">PROMOTION</a></li>
                </ul>
            </div><!-- //.breadcrumb -->

            <div class="promotion-wrap">

                <div class="board-view">
                    <p class="title"><?=$title?> <span class="date"><?=$start_date?>~<?=$end_date?></span></p>
                    <div class="view-content">
                        <?php if ( $image_type == "E" ) { ?>
                        <?=$content?>
                        <?php } else { ?>
                        <img src="/data/shopimages/timesale/<?=$banner_img?>" alt="">
                        <?php } ?>
                    </div>
					<div class="sns-icon02">
						<a href="javascript:sns('kakao','<?=$sns_text?>')" class="facebook" id='kakaostory-share-button'>카카오스토리</a>
						<a href="javascript:sns('facebook','<?=$sns_text?>')" class="instagram">페이스북</a>
						<a href="javascript:sns('twitter','<?=$sns_text?>')" class="twitter">트위터</a>
					</div>
                </div><!-- //.board-view -->

                <?php if ( !empty($promotion_tab_html) ) { ?>
                <ul class="promotion-tab">
                    <?=$promotion_tab_html?>
                </ul><!-- //.promotion-tab -->
                <?php } ?>

                <?=$promotion_tablist_html?>
                
                <?=$view_more_html?>

                <div class="btn-place-view"><button class="btn-dib-function" type="button" id="list_btn"><span>LIST</span></button></div>

            </div><!-- //.promotion-wrap -->

        </div><!-- //공통 container -->
    </div><!-- //contents -->
<? } ?>

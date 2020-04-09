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

    // 이전/다음 링크용
    $view_more_html = GetPromotionViewMore($isMobile);

    // ==================================================================================
    // 댓글 리스트
    // ==================================================================================

    // 전체 댓글 수
    $sql    = "select count(*) from tblboardcomment_promo where board = 'event' and parent = {$idx} ";
    $row    = pmysql_fetch_object(pmysql_query($sql));
    $total_comment_count = $row->count;

    $sql    = "select * from tblboardcomment_promo where board = 'event' and parent = {$idx} order by num desc ";

    if ( $isMobile ) {
        $listnum = 5;   // 한 페이지에 나오는 댓글수
        $paging = new New_Templet_mobile_paging($sql,3,$listnum,'GoPage',true);
    } else {
        $listnum = 8;   // 한 페이지에 나오는 댓글수
        $paging = new New_Templet_paging($sql, 10, $listnum, 'GoPage', true);
    }
    $t_count = $paging->t_count; 
    $gotopage = $paging->gotopage;

    $sql = $paging->getSql($sql);
    $result = pmysql_query($sql);

    $review_html = '';
    while ($row = pmysql_fetch_array($result)) {
        $reg_date = date("Y/m/d H:i:s", $row['writetime']);
        $arrRegDate = explode(" ", $reg_date);
        $comment = str_replace("\n", "<br/>", $row['comment']);

        if ( $isMobile ) {
            $review_html .= '
                    <li>
                        <div class="box">
                            <span class="date">' . $arrRegDate[0] . '</span>
                            <span class="id">' . setIDEncryp($row['c_mem_id']) . '</span>
                        </div>
                        <p>' . $comment . ' ';

            if ( $_ShopInfo->getMemid() == $row['c_mem_id'] ) {
                $review_html .= '<button class="btn-delete" type="button" onClick="javascript:delete_comment(\'event\', \'' . $row['num'] . '\');"><img src="./static/img/btn/btn_close_x.png" alt="삭제"></button>';
            }

            $review_html .= '</p>
                    </li>';
        } else {
            $review_html .= '
                <tr>
                    <td>' . setIDEncryp($row['c_mem_id']) . '</td>
                    <td class="subject">' . $comment;

            if ( $_ShopInfo->getMemid() == $row['c_mem_id'] ) {
                $review_html .= '<button class="btn-delete" type="button" onClick="javascript:delete_comment(\'event\', \'' . $row['num'] . '\');"><img src="../static/img/btn/close.png" alt="삭제"></button>';
            }

            $review_html .= '</td>
                    <td class="date">' . $arrRegDate[0] . '<span>' . $arrRegDate[1] . '</span></td>
                </tr>';
        }
    }
	$sns_text	    = "[".$_data->shoptitle."] PROMOTION - ".addslashes($title);
    $sns_thumb_img  = 'http://'.$_SERVER[HTTP_HOST].'/data/shopimages/timesale/'.$thumb_img;

    if ( $isMobile ) {
        include($Dir.TempletDir."promotion/mobile/promotion_detail_2_TEM001.php");
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

                <div class="reply-list-wrap">
                    <p class="ea">댓글 (<?=number_format($total_comment_count)?>)</p>
                    <div class="inner">
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
                            <!--input type=hidden name='up_name' id="inpt-name" title="작성자 입력자리">
                            <input type=hidden name='up_passwd' id="inpt-pwd" title="비밀전호 입력자리">
                            <input type="checkbox" id="inpt-check" name='up_is_secret' value='1' --> 

                            <fieldset>
                                <legend>댓글입력 폼</legend>
                                <textarea cols="30" rows="10" id="up_comment" name="up_comment" onKeyUp="checkByte(this.form);" onFocus="clearMessage(this.form);"></textarea>
                                <button class="btn-reply-reg" type="submit">댓글<br>입력</button>
                                <span class="byte"><strong id="messagebyte">0</strong>/300</span>
                                <ul class="attention">
                                    <li>- 20자 이상 입력 시 댓글등록 가능</li>
                                    <li>- 로그인 후 작성 가능</li>
                                </ul>
                            </fieldset>
                        </form>
                        <table class="reply-list">
                            <caption>댓글 리스트</caption>
                            <colgroup><col style="width:185px"><col style="width:auto"><col style="width:90px"></colgroup>
                            <?=$review_html?>
                        </table>
                    </div>
                </div><!-- //.reply-list-wrap -->

                <div class="list-paginate">
                    <?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
                </div>

                <?=$view_more_html?>

                <div class="btn-place-view"><button class="btn-dib-function" type="button" id="list_btn"><span>LIST</span></button></div>

            </div><!-- //.promotion-wrap -->

        </div><!-- //공통 container -->
    </div><!-- //contents -->

<? } ?>


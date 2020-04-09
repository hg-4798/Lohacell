<?php
$listnum        = 5;

// ===================================================================================================
// 진행중인 프로모션
// ===================================================================================================

$sql  = "SELECT * FROM tblpromo ";
$sql .= "WHERE event_type <> '4' AND display_type in ('A', 'M') AND hidden = 1 AND ( current_date >= start_date AND current_date <= end_date ) ";

// ============================================================
// 프로모션 시작일이 오래된 순으로 정렬
// 시작일이 같은 경우 등록순으로 정렬
// by 최문성 ( 요청 : 조경복과장님 )
// date : 2016-05-04
// 2016-05-10 : 시작일 가장 최근것부터 나오게 수정 요청하여 재수정 (요청 : 조경복) by JeongHo, Jeong
// ============================================================
//$sql .= "ORDER BY start_date asc, idx::integer desc  ";
$sql .= "ORDER BY start_date desc, idx::integer desc ";  
$paging = new New_Templet_mobile_paging($sql, 5, $listnum, 'GoPageAjax_running_promotion', true);
$gotopage = $paging->gotopage;

$sql = $paging->getSql($sql);

$review_result = pmysql_query($sql);

$htmlResult = '';
while ( $row = pmysql_fetch_object($review_result) ) {
    $start_date = str_replace("-", ".", $row->start_date);
    $end_date = str_replace("-", ".", $row->end_date);
    $thumbImg = getProductImage($Dir.DataDir.'shopimages/timesale/', $row->thumb_img_m);

    $htmlResult .= '
        <li>
            <a href="/m/promotion_detail.php?idx=' . $row->idx . '&event_type=' . $row->event_type . '">
                <figure>
                    <img src="' . $thumbImg . '" alt="">
                    <figcaption>
                        <span class="title">' . $row->title . '</span>
                        <span class="date">' . $start_date . '~' . $end_date . '</span>
                    </figcaption>
                </figure>
            </a>
        </li>';
}
pmysql_free_result($result);

// ===================================================================================================
// 지난 프로모션
// ===================================================================================================

$sql  = "SELECT * FROM tblpromo ";
$sql .= "WHERE event_type <> '4' AND display_type in ('A', 'M') AND hidden = 1 AND current_date > end_date ";

// ============================================================
// 프로모션 시작일이 오래된 순으로 정렬
// 시작일이 같은 경우 등록순으로 정렬
// by 최문성 ( 요청 : 조경복과장님 )
// date : 2016-05-04
// 2016-05-10 : 시작일 가장 최근것부터 나오게 수정 요청하여 재수정 (요청 : 조경복) by JeongHo, Jeong
// ============================================================
//$sql .= "ORDER BY start_date asc, idx::integer desc ";
$sql .= "ORDER BY start_date desc, idx::integer desc ";  

$paging2 = new New_Templet_mobile_paging($sql, 5, $listnum, 'GoPageAjax_end_promotion', true);
$gotopage = $paging2->gotopage;

$sql = $paging2->getSql($sql);
$review_result = pmysql_query($sql);

$htmlResult2 = '';
while ( $row = pmysql_fetch_object($review_result) ) {
    $start_date = str_replace("-", ".", $row->start_date);
    $end_date = str_replace("-", ".", $row->end_date);
    $thumbImg = getProductImage($Dir.DataDir.'shopimages/timesale/', $row->thumb_img_m);

    $htmlResult2 .= '
        <li>
            <a href="/m/promotion_detail.php?idx=' . $row->idx . '&event_type=' . $row->event_type . '">
                <figure>
                    <img src="' . $thumbImg . '" alt="">
                    <figcaption>
                        <span class="title">' . $row->title . '</span>
                        <span class="date">' . $start_date . '~' . $end_date . '</span>
                    </figcaption>
                </figure>
            </a>
        </li>';

}
pmysql_free_result($result);
?>			

			<!-- 히어로 배너 -->
            <?php if ( $bannerCount > 0 ) { ?>
			<div class="js-promo-hero">
				<div class="js-carousel-list">
					<ul>
                        <?=$bannerHtml?>
					</ul>
				</div>
				<div class="page">
					<ul>
                        <?php for ( $i = 1; $i <= $bannerCount; $i++ ) { ?>
						<li class="js-carousel-page"><a href="javascript:;"><span class="ir-blind"><?=$i?></span></a></li>
                        <?php } ?>
					</ul>
				</div>
				<button class="js-carousel-arrow" data-direction="prev" type="button"><img src="./static/img/btn/btn_slider_arrow_prev.png" alt="이전"></button>
				<button class="js-carousel-arrow" data-direction="next" type="button"><img src="./static/img/btn/btn_slider_arrow_next.png" alt="다음"></button>
			</div>
            <?php } ?>
			<!-- // 히어로 배너 -->
			
			<!-- 트윈배너 -->
            <?php if ( !empty($banner1_html) || !empty($banner2_html) ) { ?>
			<div class="promo-twin">
				<ul>
                    <?=$banner1_html?>
                    <?=$banner2_html?>
				</ul>
			</div>
            <?php } ?>
			<!-- // 트윈배너 -->

<?php
        $list_type = "A";                   // A : 프로모션 메인페이지 그대로 보여줌, W : 당첨자 발표 리스트만 보여줌.
        $onRunningPromotionClass = "on";
        $onEndPromotionClass = "";

        if ( $view_type == "E" ) {
            // 종료된 이벤트
            $onRunningPromotionClass    = "";
            $onEndPromotionClass        = "on";
        } elseif ( $view_type == "W" ) {
            // 당첨자 리스트
            $list_type = "W";
        }

        if ( $list_type == "W" ) {
            $sql  = "SELECT * FROM tblpromo ";
            $sql .= "WHERE display_type in ('A', 'M') and hidden = 1 AND winner_list_content <> '' ";   // '전시상태'가 모두 or PC인 경우만

            // ============================================================
            // 프로모션 시작일이 오래된 순으로 정렬
            // 시작일이 같은 경우 등록순으로 정렬
            // by 최문성 ( 요청 : 조경복과장님 )
            // date : 2016-05-04
            // 2016-05-10 : 시작일 가장 최근것부터 나오게 수정 요청하여 재수정 (요청 : 조경복) by JeongHo, Jeong
            // ============================================================
            //$sql .= "ORDER BY start_date asc, idx::integer desc   ";
            $sql .= "ORDER BY start_date desc, idx::integer desc ";  

            $paging3 = new New_Templet_mobile_paging($sql, 5, $listnum, 'GoPageAjax_winner_list_promotion', true);
            $gotopage = $paging3->gotopage;
                            
            $sql = $paging3->getSql($sql);
            $result = pmysql_query($sql);

            $winner_promotion_list_html = '';
            $list_cnt = 0;
            while ( $row = pmysql_fetch_array($result) ) {
                $linkUrl = '/front/promotion_detail.php';
                if ( $isMobile ) {
                    $linkUrl = '/m/promotion_detail.php';
                } 
                $linkUrl .= '?idx=' . $row['idx'] . '&event_type=' . $row['event_type'] . '&' . $addedQuery;
                
                if ( $isMobile ) {
                    $winner_list_html .= '<li><a href="' . $linkUrl . '">' . $row['title'] . '</a></li>';
                } else {
                    $winner_list_html .= '<dd><a href="' . $linkUrl . '">' . $row['title'] . '</a></dd>';
                }
            
                $winner_promotion_list_html .= '
                    <tr>
                        <td>' . ( $list_cnt + 1 ) . '</td>
                        <td class="subject"><a href="/m/promotion_detail.php?idx=' . $row['idx'] . '">' . $row['title'] . '</a></td>
                        <td>' . $row['rdate'] . '</td>
                    </tr>';

                $list_cnt++;
            }
            pmysql_free_result($result);

            if ( $list_cnt == 0 ) {
                $winner_promotion_list_html = '
                    <tr>
                        <td colspan="3">해당 내용이 없습니다.</td>
                    </tr>';
            }

?>
    <div class="js-tab-content cs-notice-wrap on">
        <table class="th-top">
            <caption>당첨자 발표 리스트</caption>
            <colgroup>
                <col style="width:45px">
                <col style="width:auto">
                <col style="width:90px">
            </colgroup>
            <thead>
                <tr>
                    <th scope="col">NO</th>
                    <th scope="col">제목</th>
                    <th scope="col">등록일</th>
                </tr>
            </thead>
            <tbody id="winner_promo_list">
                <?=$winner_promotion_list_html?>
            </tbody>
        </table>

        <?php if ( $list_cnt > 0 ) { ?>
        <div class="paginate">
            <div class="box" id="winner_promo_page">
            <?php
                if( $paging3->pagecount > 1 ){
                    echo $paging3->a_prev_page.$paging3->print_page.$paging3->a_next_page;
                } 
            ?>
            </div>
        </div>
        <?php } ?>
    </div>
<?php
        } else {
?>
			<!-- 당첨자발표 -->
            <?php if ( !empty($winner_list_html) ) { ?>
			<div class="promo-event">
				<h2>당첨자 발표</h2>
				<ul>
                    <?=$winner_list_html?>
				</ul>
				<a class="btn-more" href="/m/promotion.php?view_type=W"><span class="ir-blind">더보기</span></a>
			</div>
            <?php } ?>
			<!-- // 당첨자발표 -->
			<!-- 프로모션 리스트 -->
			<div class="js-promo-list">
				<div class="content-tab">
					<div class="js-menu-list">
						<div class="js-tab-line"></div>
						<ul>
							<li class="js-tab-menu <?=$onRunningPromotionClass?>"><a href="#"><span>진행 중 프로모션</span></a></li>
							<li class="js-tab-menu <?=$onEndPromotionClass?>"><a href="#"><span>지난 프로모션</span></a></li>
						</ul>
					</div>
				</div>
				
				<!-- 진행 중 리스트 -->
				<div class="js-tab-content">
					<ul class="promo-list" id="running_promo_list">
                        <?=$htmlResult?>
					</ul>

                    <div class="paginate">
                        <div class="box" id="running_promo_page">
                            <?=$paging->a_prev_page . $paging->print_page . $paging->a_next_page?>
                        </div>
                    </div>
				</div>
				<!-- // 진행 중 리스트 -->
				
				<!-- 지난 리스트 -->
				<div class="js-tab-content">
					<ul class="promo-list" id="end_promo_list">
                        <?=$htmlResult2?>
					</ul>

                    <div class="paginate">
                        <div class="box" id="end_promo_page">
                            <?=$paging2->a_prev_page . $paging2->print_page . $paging2->a_next_page?>
                        </div>
                    </div>
				</div>
				<!-- // 지난 리스트 -->
			</div>
			<!-- // 프로모션 리스트 -->
<?php } ?>


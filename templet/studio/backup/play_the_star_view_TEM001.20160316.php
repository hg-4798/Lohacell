<?php
    // ============================================================================
    // 게시물 조회
    // ============================================================================
    $sql  = "SELECT * FROM tblplaythestar WHERE hidden = 1 ORDER BY no desc ";

    if ( $isMobile ) {
        $listnum = 5;
        $paging = new New_Templet_mobile_paging($sql, 3, $listnum, 'GoPage', true);
    } else {
        $listnum = 8;
        $paging = new New_Templet_paging($sql, 10, $listnum, 'GoPage', true);
    }
    $t_count = $paging->t_count;
    $gotopage = $paging->gotopage;

    $sql    = $paging->getSql($sql);
    $result = pmysql_query($sql);

    $list_html = '';
    while ($row = pmysql_fetch_array($result)) {
        // 등록일
        $reg_date = $row['regdate'];
        $reg_date = substr($reg_date, 0, 4) . "." . substr($reg_date, 4, 2) . "." . substr($reg_date, 6, 2);
        $thumbImg = getProductImage($Dir.DataDir."/shopimages/playthestar/", $row['img_m']);

        if ( $isMobile ) {
            $list_html .= '
                <li>
                    <a class="btn-detail" href="#">
                        <figure>
                            <img src="' . $thumbImg . '" alt="">
                            <figcaption>' . $row['title'] . '</figcaption>
                        </figure>
                    </a>
                </li>
            ';
        } else {
            $list_html .= '
                    <div class="board-view">
                        <p class="title">' . $row['title'] . ' <span class="date">' . $reg_date . '</span></p>
                        <div class="view-content">' . $row['content'] . '</div>
                    </div>';
        }
    }


    if ( $isMobile ) {
        include ($Dir.TempletDir."studio/mobile/play_the_star_view_TEM001.php");
    } else {
?>

<div id="contents">
        <div class="containerBody sub-page">

            <? include ($Dir.TempletDir."studio/navi_TEM001.php"); ?>
			<div class="board_list_tap">
            	<ul>
                	<li class="on"><a href="#">갤러리형</a></li>
                    <li><a href="#">리스트형</a></li>
                </ul>
            </div>
            <div class="board-default-wrap">

                <?=$list_html?>

                <div class="list-paginate-wrap">
                    <div class="list-paginate">
                    <?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
                    </div>
                </div>

            </div><!-- //.promotion-wrap -->

        </div><!-- //공통 container -->
    </div><!-- //contents -->

<?php } ?>

<form name=form2 method=get action="<?=$_SERVER['PHP_SELF']?>" >
    <input type=hidden name=idx value="<?=$idx?>">
    <input type=hidden name=listnum value="<?=$listnum?>">
    <input type=hidden name=block value="<?=$block?>">
    <input type=hidden name=gotopage value="<?=$gotopage?>">
</form>

<script type="text/javascript">
function chk_from() {
    if ( $("#keyword").val().trim() == "" ) {
        // 검색어가 없는 경우
        alert("검색어를 입력해 주세요.");
        $("#search_word").val("").focus();
        return false;
    }

    document.form2.block.value = "";
    document.form2.gotopage.value = "";
}

function GoPage(block,gotopage) {
    document.form2.block.value=block;
    document.form2.gotopage.value=gotopage;
    document.form2.submit();
}

</script>


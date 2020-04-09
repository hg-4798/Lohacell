<?php include ($Dir.TempletDir."studio/mobile/navi_TEM001.php"); ?>

<!-- 플레이더스타 리스트 -->
<div class="studio-play-list">
    <ul>
        <?=$list_html?>
    </ul>

    <div class="paginate">
        <div class="box">
                <?php echo $paging->a_prev_page.$paging->print_page.$paging->a_next_page; ?>
        </div>
    </div>
</div>
<!-- // 플레이더스타 리스트 -->


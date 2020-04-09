
<div class="sub-title">
                <h2>진행중 프로모션</h2>
                <a class="btn-prev" href="#"><img src="./static/img/btn/btn_page_prev.png" alt="이전 페이지"></a>
                <div class="js-sub-menu">
                    <button class="js-btn-toggle" title="펼쳐보기"><img src="./static/img/btn/btn_arrow_down.png" alt="메뉴"></button>
                    <div class="js-menu-content">
                        <ul>
                            <li><a href="#">진행중 프로모션</a></li>
                            <li><a href="#">지난 프로모션</a></li>
                            <li><a href="#">당첨자 발표</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- 프로모션 내용 -->
            <article class="promo-detail-content">
                <div class="promo-title">
                    <h3>
                        <strong><?=$title?></strong>
                        <span class="box">
                            <span class="date"><?=$reg_date?></span>
                            <span class="id"><?=setIDEncryp($mem_id)?></span>
                        </span>
                    </h3>
                    <button class="btn-share" onclick="popup_open('#popup-sns');return false;"><span class="ir-blind">공유</span></button>
                </div>
                <div class="promo-content-inner"><?=$content_html?></div>
            </article>
            <!-- // 프로모션 내용 -->
            
            <div class="btnwrap promo-detail-btn">
                <div class="box">
                    <?php if(strlen($_ShopInfo->getMemid())==0) {?>
                        <a class="btn-def" href="javascript:;" onClick="javascript:goLogin();">수정</a>
                    <?php } elseif ( $_ShopInfo->getMemid() == $mem_id ) { ?>
                        <a class="btn-def" href="?<?=$_SERVER['QUERY_STRING']?>&mode=modify">수정</a>
                    <?php } ?>

                    <a class="btn-def" href="javascript:;" id="photo_list_btn">목록</a>
                    <?php if(strlen($_ShopInfo->getMemid())==0) {?>
                        <a class="btn-def" href="javascript:;" onClick="javascript:goLogin();">삭제</a>
                    <?php } elseif ( $_ShopInfo->getMemid() == $mem_id ) { ?>
                        <!--a class="btn-def" href="/board/board_photo.php?event_type=<?=$event_type?>&promo_idx=<?=$idx?>&view_type=<?=$view_type?>&view_mode=<?=$view_mode?>&board=photo&pagetype=delete_photo&num=<?=$num?>&mode=delete&is_mobile=<?=$isMobile?>">삭제</a-->
                        <a class="btn-def" href="javascript:;" onClick="javascript:delete_photo_event_article('<?=$event_type?>', '<?=$idx?>', '<?=$view_type?>', '<?=$view_mode?>', '<?=$num?>', '<?=$isMobile?>');">삭제</a>
                    <?php } ?>
                </div>
            </div>

<script type="text/javascript">
function delete_photo_event_article(event_type, idx, view_type, view_mode, num, is_mobile) {
    if ( confirm("정말로 삭제하시겠습니까?") ) {
        location.href = "/board/board_photo.php?event_type=<?=$event_type?>&promo_idx=<?=$idx?>&view_type=<?=$view_type?>&view_mode=<?=$view_mode?>&board=photo&pagetype=delete_photo&num=<?=$num?>&mode=delete&is_mobile=<?=$isMobile?>";
    } 
}
</script>



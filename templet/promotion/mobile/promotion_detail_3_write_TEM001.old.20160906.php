<?php

$exec = "write";
if ( $event_type == "3" && !empty($num) ) {
    // 포토게시물 상세 페이지 인 경우
    $exec = "modify";
}

$arrFileExists = array();

for ( $i = 0; $i < 4; $i++ ) {
    $arrFileExists[$i] = "N";
            
    $varName = "article_filename" . ($i+1);
    if ( $$varName ) { $arrFileExists[$i] = "Y"; }
}                   

?>
<div class="sub-title">
    <h2>프로모션 포토등록</h2>
    <a class="btn-prev" href="#"><img src="./static/img/btn/btn_page_prev.png" alt="이전 페이지"></a>
</div>

<!-- 포토등록 -->
<div class="form_photo_write">
    <form name="fileform" method="post">
        <input type="hidden" name="board" value="photo">
        <input type="hidden" name="max_filesize" value="1024000">
        <input type="hidden" name="btype" value="I">
    </form>

    <form name=writeForm method='post' action='/board/board_photo.php' enctype='multipart/form-data'>
        <input type=hidden name=mode value='<?=$mode?>'>
        <input type=hidden name=pagetype value='write_photo'>
        <input type=hidden name=exec value='<?=$exec?>'>
        <input type=hidden name=num value='<?=$num?>'>
        <input type=hidden name=board value='photo'>
        <input type=hidden name=s_check value=''>
        <input type=hidden name=search value=''>
        <input type=hidden name=block value=''>
        <input type=hidden name=gotopage value=''>
        <input type=hidden name=pridx value=''>
        <input type=hidden name=pos value=''>
        <input type=hidden name=up_name value='<?=$_ShopInfo->getMemname()?>'>
        <input type=hidden name=up_passwd value='1234'>
        <input type=hidden name=up_email value=''>
        <input type=hidden name=promo_idx value='<?=$idx?>'>
        <input type=hidden name=event_type value='<?=$event_type?>'>
        <input type=hidden name=view_mode value='<?=$view_mode?>'>
        <input type=hidden name=view_type value='<?=$view_type?>'>
        <input type=hidden name=file_exist1 value='<?=$arrFileExists[0]?>'>
        <input type=hidden name=file_exist2 value='<?=$arrFileExists[1]?>'>
        <input type=hidden name=file_exist3 value='<?=$arrFileExists[2]?>'>
        <input type=hidden name=file_exist4 value='<?=$arrFileExists[3]?>'>
        <input type=hidden name=is_mobile value='<?=$isMobile?>'>

    <section class="write-title">
        <h3>제목</h3>
        <input type="text" id="up_subject" name="up_subject" value="<?=$article_title?>" placeholder="제목을 입력하세요" title="제목">
    </section>
    <section class="write-content">
        <h3>내용</h3>
        <textarea id="ir1" name="up_memo" placeholder="내용을 입력하세요" title="내용"><?=$article_content?></textarea>
    </section>
    <section class="write-upload">
        <h3>이미지등록 (최대4장)</h3>
        <ul>
            <li><label><span><?=$article_filename1?></span><input type="file" id="add-image1" name="up_filename[]" ids="1" accept="image/*"></label></li>
            <li><label><span><?=$article_filename2?></span><input type="file" id="add-image1" name="up_filename[]" ids="2" accept="image/*"></label></li>
            <li><label><span><?=$article_filename3?></span><input type="file" id="add-image1" name="up_filename[]" ids="3" accept="image/*"></label></li>
            <li><label><span><?=$article_filename4?></span><input type="file" id="add-image1" name="up_filename[]" ids="4" accept="image/*"></label></li>
        </ul>
        <p class="note">파일명: 한글,영문,숫자 / 용량: 3M이하 / 파일형식: GIF,JPG</p>
    </section>
    <div class="btnwrap">
        <div class="box">
            <?
                $btnName = "등록"; 
                if ( $mode == "modify" ) {
                    $btnName = "수정";
                }
            ?>

            <?php if(strlen($_ShopInfo->getMemid())==0) {?>
                <a class="btn-def" href="javascript:;" onClick="javascript:goLogin();"><?=$btnName?></a>
            <?php } else { ?>
                <a class="btn-def" href="javascript:;" onClick="javascript:chk_writeForm(document.writeForm);"><?=$btnName?></a>
            <?php } ?>
            <a class="btn-def" href="javascript:;" onClick="javascript:cancel_writeForm();">취소</a>
        </div>
    </div>

        <input type="hidden" name="ins4eField[mode]">
        <input type="hidden" name="ins4eField[pagetype]">
        <input type="hidden" name="ins4eField[exec]">
        <input type="hidden" name="ins4eField[num]">
        <input type="hidden" name="ins4eField[board]">
        <input type="hidden" name="ins4eField[s_check]">
        <input type="hidden" name="ins4eField[search]">
        <input type="hidden" name="ins4eField[block]">
        <input type="hidden" name="ins4eField[gotopage]">
        <input type="hidden" name="ins4eField[pridx]">
        <input type="hidden" name="ins4eField[pos]">
        <input type="hidden" name="ins4eField[up_is_secret]">
        <input type="hidden" name="ins4eField[up_name]">
        <input type="hidden" name="ins4eField[up_passwd]">
        <input type="hidden" name="ins4eField[up_email]">
        <input type="hidden" name="ins4eField[up_subject]">
        <input type="hidden" name="ins4eField[up_memo]">
        <input type="hidden" name="ins4eField[up_filename[]]">
        <input type="hidden" name="ins4eField[promo_idx]">
        <input type="hidden" name="ins4eField[event_type]">
        <input type="hidden" name="ins4eField[view_type]">
        <input type="hidden" name="ins4eField[view_mode]">
        <input type="hidden" name="ins4eField[file_exist1]">
        <input type="hidden" name="ins4eField[file_exist2]">
        <input type="hidden" name="ins4eField[file_exist3]">
        <input type="hidden" name="ins4eField[file_exist4]">
        <input type="hidden" name="ins4eField[is_mobile]">

    </form>
</div>
<!-- // 포토등록 -->

<script type="text/javascript">

    // 파일 업로드 이벤트 
    $('input[type=file]').bind('change', function (e) {
        var fileName = $(this).val().split('\\').pop();
        var idx = $(this).attr("ids");

//        $(this).parent().find("span").html(fileName + '<button class="del-img" type="button" ids="' + idx + '">이미지삭제</button>');
        $(this).parent().find("span").html(fileName);
        $("input[name=file_exist" + idx + "]").val("Y");
    });

    function cancel_writeForm() {
        if ( confirm("정말 취소하겠습니까?") ) {
            history.go(-1);
        }
    }

    $(document).ready(function() {
        nhn.husky.EZCreator.createInIFrame({
            oAppRef: oEditors,
            elPlaceHolder: "ir1",
            sSkinURI: "../SE2/SmartEditor2Skin.html",
            htParams : {
                bUseToolbar : true,             // 툴바 사용 여부 (true:사용/ false:사용하지 않음)
                bUseVerticalResizer : false,     // 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
                bUseModeChanger : true,         // 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
                //aAdditionalFontList : aAdditionalFontSet,     // 추가 글꼴 목록
                fOnBeforeUnload : function(){
                }
            },
            fOnAppLoad : function(){
            },
            fCreator: "createSEditor2"
        });
    });

</script>



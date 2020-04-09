<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");
include("calendar.php");
include("header.php");
include_once($Dir."lib/file.class.php");
include_once($Dir."conf/config.php");
####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################
$page_type = $_REQUEST["page_type"];
$page_text = "기획전";
$return_page_link = "market_promotion_list.php";

$pidx=$_REQUEST["pidx"];
$idx=$_REQUEST['idx'];
$mode=$_REQUEST['mode'];
$itemCount=(int)$_REQUEST["itemCount"];


if(is_array($_REQUEST["start_date_time"])) {
    $start_date_time = implode(':', $_REQUEST["start_date_time"]);
    $start_date = $_REQUEST["start_date"].' '.$start_date_time;
}

if(is_array($_REQUEST["end_date_time"])) {
    $end_date_time = implode(':', $_REQUEST["end_date_time"]);
    $end_date = $_REQUEST["end_date"].' '.$end_date_time;    
}


$no_coupon      = $_REQUEST["no_coupon"]?$_REQUEST["no_coupon"]:"N";
$imagepath      = $cfg_img_path['timesale'];
$filedata       = new FILE($imagepath);
$image_type     = $_REQUEST['image_type'];
$image_type_m   = $_REQUEST['image_type_m'];
$hidden         = $_REQUEST['hidden'];
$errmsg = $filedata->chkExt();

if($errmsg==''){
    $up_file = $filedata->upFiles();
}

$content = trim($_REQUEST["content"]);
$content = str_replace("'", "''", $content);

$content_m  = trim($_REQUEST["content_m"]);
$content_m  = str_replace("'", "''", $content_m);

if(ord($_REQUEST["mode2"])>0){
    $ppidx_ = $_REQUEST["ppidx"];
    $pidx_ = $_REQUEST["pidx"];
    $sql = "DELETE FROM tblpromotion WHERE idx = '{$ppidx_}' AND promo_idx = '{$pidx_}' ";
    pmysql_query($sql);
    echo "<script>alert('삭제되었습니다.')</script>";
}

$cqry="select count(*) from tblpromotion WHERE promo_idx='{$pidx}'";
$cres=pmysql_query($cqry);
$crow=pmysql_fetch_array($cres);
pmysql_free_result($cres);
$count=$crow['count'];

$cqry="select count(*) from tblpromo ";
$cres=pmysql_query($cqry);
$crow=pmysql_fetch_array($cres);
pmysql_free_result($cres);
$mcount=$crow['count'];

$event_type = $_POST['event_type'] ? : 1;
$executives_yn = $_POST['executives_yn'] ? : 'N';

switch($mode){
    case "del" : 	$seq=$_REQUEST['seq']; /*삭제할때 삭제할 로우보다 진열 순위가 낮은 로우를 한개씩 위로 올림*/
        $dcsql = "SELECT count(*) FROM tblpromo WHERE idx = ( select * from (select idx where display_seq > {$seq}) as a)";
        $dcres = pmysql_query($dcsql,get_db_conn());
        $dcrow=pmysql_fetch_array($dcres);
        if($dcrow[0]!=0){
            $dusql = "UPDATE tblpromo SET display_seq = display_seq-1 
						WHERE idx = ( select * from (select idx where display_seq > {$seq}) as a)";
            pmysql_query($dusql,get_db_conn());
        }
        /*메인 타이틀 삭제*/
        $dsql = "DELETE FROM tblpromo WHERE idx='{$pidx}'";
        pmysql_query($dsql);

        /*상품 삭제*/
        $ddsql = "SELECT seq FROM tblpromotion WHERE promo_idx='{$pidx}'"; //idx를 seq로 변경
        $ddres = pmysql_query($ddsql);
        while($ddrow = pmysql_fetch_object($ddres)){
            $dsql2 = "DELETE FROM tblspecialpromo WHERE special='".$ddrow->seq."'"; //idx를 seq로 변경
            pmysql_query($dsql2);
        }
        /*서브 타이틀 삭제*/
        $dsql3 = "DELETE FROM tblpromotion WHERE promo_idx='{$pidx}' ";
        pmysql_query($dsql3);

        /*관련 댓글 삭제*/
        $dsql4 = "DELETE FROM tblboardcomment_promo WHERE parent='{$pidx}' ";
        pmysql_query($dsql4);

        echo "<script>alert('삭제되었습니다.');</script>";
// 				echo "<script>document.location.href='market_promotion_new.php';</script>";
        echo "<script>document.location.href='".$return_page_link."';</script>";
        break;

    case "ins" : $count=$count+1; $mcount= $mcount+1; break;

    case "ins_submit" : $ptitle = pmysql_escape_string($_POST["ptitle"]); $pinfo = $_POST["pinfo"]; $pseq = $_POST["pseq"]; $ptem = $_POST["ptem"]; $pppidx = $_POST["pppidx"];
        $pt = explode(",", $ptitle); $pi = explode(",", $pinfo); $ps = explode(",", $pseq); $pte = explode(",", $ptem); $pidxs = explode(",", $pppidx);
        $mt = pmysql_escape_string($_POST["mtitle"]); $mdt = $_POST["display_type"]; $mds = $_POST["mdisplay_seq"];

        $mcount++;

        $mnsql = "select idx from tblpromo order by idx desc";
        $mnres = pmysql_query($mnsql);
        $tempx = 1;
        while($mnrow = pmysql_fetch_object($mnres)){
            if($tempx <= $mnrow->idx){
                $tempx = $mnrow->idx+1;
            }
        }

        $misql = "insert into tblpromo (idx, title, thumb_img, thumb_img_m, banner_img, display_type, display_seq, rdate, hidden, ";
        $misql.= "start_date, end_date, ";

        $misql.= "no_coupon,image_type, image_type_m, content, content_m, title_banner, banner_img_m, ";
        $misql.= "event_type,  executives_yn ) ";
        $misql.= "values('".$tempx."', '{$mt}', '{$up_file['thumb_img'][0]['v_file']}', '{$up_file['thumb_img_m'][0]['v_file']}', ";
        $misql.= "'{$up_file['banner_img'][0]['v_file']}', '{$mdt}', '{$mds}', current_date, {$hidden}, '{$start_date}', '{$end_date}', ";
        $misql.= "'{$no_coupon}','{$image_type}', '{$image_type_m}', '{$content}', '{$content_m}', ";
        $misql.= "'{$up_file['title_banner'][0]['v_file']}', '{$up_file['banner_img'][1]['v_file']}', ";
        $misql.="'{$event_type}','{$executives_yn}' ) ";

        pmysql_query($misql);
        if(!pmysql_error()){
            echo "<script>alert('등록되었습니다.');</script>";
            //echo "<script>document.location.href='market_promotion_new.php';</script>";
            echo "<script>document.location.href='".$return_page_link."';</script>";
            break;
        }else{
            echo "<script>alert('오류가 발생하였습니다.');</script>";
        }

    case "mod_submit" :
        $mt = pmysql_escape_string($_POST["mtitle"]); $mdt = $_POST["display_type"]; $mds = $_POST["mdisplay_seq"];

        $promo_code = $_POST["promo_code"];
        $promo_view = $_POST["promo_view"];


        $musql = "SELECT display_seq FROM tblpromo WHERE idx='{$pidx}' ";
        $mures = pmysql_query($musql);
        $murow = pmysql_fetch_array($mures);

        /*메인테이블 업데이트*/
        $musql = "update tblpromo set title = '{$mt}', display_type = '{$mdt}', display_seq =  '{$mds}', promo_code =  '{$promo_code}', promo_view =  '{$promo_view}', 
								start_date = '{$start_date}', end_date = '{$end_date}', ";

        $musql.= "no_coupon = '{$no_coupon}', image_type = '{$image_type}', image_type_m = '{$image_type_m}', ";
        $musql.= "content = '{$content}', content_m = '{$content_m}' ";
        $musql.= ", hidden = {$hidden}";

        if($up_file['thumb_img'][0]['v_file']){
            $musql.=", thumb_img = '{$up_file['thumb_img'][0]['v_file']}' ";

            list($temp_banner_img)=pmysql_fetch("select thumb_img from tblpromo where idx='{$pidx}'");
            if($temp_banner_img) @unlink($imagepath.$temp_banner_img);
        }

        if($up_file['thumb_img_m'][0]['v_file']){
            $musql.=", thumb_img_m = '{$up_file['thumb_img_m'][0]['v_file']}' ";

            list($temp_banner_img)=pmysql_fetch("select thumb_img_m from tblpromo where idx='{$pidx}'");
            if($temp_banner_img) @unlink($imagepath.$temp_banner_img);
        }

        if($up_file['banner_img'][0]['v_file']){
            $musql.=", banner_img = '{$up_file['banner_img'][0]['v_file']}' ";

            list($temp_banner_img)=pmysql_fetch("select banner_img from tblpromo where idx='{$pidx}'");
            if($temp_banner_img) @unlink($imagepath.$temp_banner_img);
        }
        if($up_file['banner_img'][1]['v_file']){
            $musql.=", banner_img_m = '{$up_file['banner_img'][1]['v_file']}' ";

            list($temp_banner_m_img)=pmysql_fetch("select banner_img_m from tblpromo where idx='{$pidx}'");
            if($temp_banner_m_img) @unlink($imagepath.$temp_banner_m_img);
        }
        // 핏플랍 모바일 타이틀 베너
        if($up_file['title_banner'][0]['v_file']){
            $musql.=", title_banner = '{$up_file['title_banner'][0]['v_file']}' ";

            list($temp_tbanner_img)=pmysql_fetch("select title_banner from tblpromo where idx='{$pidx}'");
            if($temp_tbanner_img) @unlink($imagepath.$temp_tbanner_img);
        }

        // 출석체크시 설정값 업데이트
        $musql .= ", event_type = '{$event_type}' ";

        $musql .= " where idx='{$pidx}' ";
        //echo $musql . "<br/>";

        pmysql_query($musql);

        if(!pmysql_error()) {
            echo "<script>alert('수정되었습니다.');</script>";
            echo "<script>document.location.href='" . $return_page_link . "';</script>";
            break;
        }else {
            echo "<script>alert('오류가 발생하였습니다.".$musql."');</script>";
        }
}
?>

    <script type="text/javascript" src="lib.js.php"></script>
    <script type="text/javascript" src="../lib/DropDown.admin.js.php"></script>

    <script language="JavaScript">
        function chkfrm()	{
            if ( $("#mtitle").val().trim() === "" ) {
                alert("메인 타이틀을 입력해 주세요.");
                $("#mtitle").val("").focus();
                return false;
            }

            if ( $("input[name='start_date']").val().trim() === "" ) {
                alert("노출 시작일을 입력해 주세요.");
                return false;
            }

            if ( $("input[name='end_date']").val().trim() === "" ) {
                alert("노출 마감일을 입력해 주세요.");
                return false;
            }

            var mode = document.eventform.mode.value;

            if(mode=="ins"){
                if(confirm("등록하시겠습니까?")){
                    document.eventform.mode.value = "ins_submit";
                }
            }else if(mode=="mod"){
                if(confirm("수정하시겠습니까?")){
                    document.eventform.mode.value = "mod_submit";
                }
            }

            if(mode=="ins") {
                var itemCount = $(".table_style01 [name=promotable]:last").attr("class").replace("item", "");
                //promo_seq
                for (var i = 1; i <= itemCount; i++) {
                    for (var ii = 0; ii < 6; ii++) {
                        var itemname
                        var hiddenname
                        switch (ii) {
                            case 0 :
                                itemname = ".item" + i + " [name=title]";
                                hiddenname = document.eventform.ptitle;
                                break;
                            case 1 :
                                itemname = ".item" + i + " [name=info]";
                                hiddenname = document.eventform.pinfo;
                                break;
                            case 2 :
                                itemname = ".item" + i + " [name=display_seq]";
                                hiddenname = document.eventform.pseq;
                                break;
                            case 3 :
                                itemname = ".item" + i + " [name=display_tem]";
                                hiddenname = document.eventform.ptem;
                                break;
                            case 4 :
                                itemname = ".item" + i + " [name=ppidx]";
                                hiddenname = document.eventform.pppidx;
                                break;
                            case 5 :
                                itemname = ".item" + i + " [name=promo_seq]";
                                hiddenname = document.eventform.ppromo_seq;
                                break;
                        }
                        if (hiddenname.value == "") {
                            hiddenname.value = $(itemname).val();
                        } else {
                            hiddenname.value = hiddenname.value + "," + $(itemname).val();
                        }
                    }
                }
            }

            if ( oEditors.getById["ir1_m"] ) {
                var sHTML = oEditors.getById["ir1_m"].getIR();
                document.eventform.content_m.value=sHTML;
            }

            if ( oEditors.getById["ir1"] ) {
                var sHTML = oEditors.getById["ir1"].getIR();
                document.eventform.content.value=sHTML;
            }
        }

    </script>
    <div class="content-wrap">
        <div class="title_depth3"><?=$page_text?> <?if($mode=="ins"){echo "등록";}else{echo "수정";} ?>
                <a href="#">
                    <img align="right" class="tr_remove" src="../admin/images/botteon_del.gif" align="right" alt="삭제하기" onclick="javascript:all_remove()"></a>
                <?if($mode=="mod"){?>
                    <a href="/admin/promotion/market_promotion_product_new.php?pidx=<?=$pidx?>" target="_self">
                        <img align="right" id="add_prod" src="/admin/images/btn_promo_product.gif" alt="상품등록"/></a>&nbsp;
                    <a href="/front/promotion/promo_detail.php?idx=<?=$pidx?>&status=ing" target="_blank">
                        <img align="right" src="/admin/images/btn_preview.gif" alt="미리보기"/></a>
                <?}?>
        </div>



        <form name="eventform" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onsubmit="return chkfrm();">
            <input type="hidden" name="ptitle">
            <input type="hidden" name="pinfo">
            <input type="hidden" name="pseq">
            <input type="hidden" name="ptem">
            <input type="hidden" name="pppidx">
            <input type="hidden" name="ppromo_seq">
            <input type="hidden" name="itemCount">
            <input type="hidden" name="mode" value="<?=$mode?>">
            <input type="hidden" name="idx" value="<?=$idx?>">
            <input type="hidden" name="pidx" value="<?=$pidx?>">
            <input type="hidden" name="page_type" value="<?=$page_type?>">

            <!-- 테이블스타일01 -->
            <div class="table_style01 pt_20" style="position:relative">
                <div id="img_view_div" style="position:absolute;top:150px;left:400px;"><img style="display:none;width:500px" ></div>
                <table cellpadding=0 cellspacing=0 border=0 width=100%>
                    <?php
                    $msql = "SELECT * FROM tblpromo WHERE idx = '{$pidx}'";
                    $mres = pmysql_query($msql);
                    $mrow = pmysql_fetch_array($mres);

                    // 신규일 경우, 이벤트 종류를 '기획전'으로 세팅
                    if ( $mrow['event_type'] == "" ) { $mrow['event_type'] = "1"; }

                    ?>
                    <tr>
                        <th><span>메인 타이틀</span></th>
                        <td><input type="text" name="mtitle" id="mtitle" style="width:50%" value="<?=$mrow['title']?>" alt="타이틀" /></td>
                    </tr>
                    <?if($mode=="ins"){?>
                    <tr>
                        <th><span>기획전 종류</span></th>
                        <td>
                                <input type="radio" name="executives_yn" value="N" checked />일반
                                <input type="radio" name="executives_yn" value="Y" <?if($mrow['executives_yn']=='Y') echo "checked";?> />임직원

                            <!-- <input type="radio" name="event_type" value="4" <?if($mrow['event_type']=='4') echo "checked";?> onChange="javascript:changeEventType(this);" />출석체크 -->
                        </td>
                    </tr>
                    <?}?>
                    <tr style="display:none;">
                        <th><span>메인 카테고리</span></th>
                        <td>
                            <select name = 'promo_code'>
                                <option value = ''>--카테고리 선택--</option>
                                <?
                                $selected['promo_code'][$mrow['promo_code']] = 'selected';
                                $checked['promo_view']['Y'] = 'checked';
                                # 1차 카테고리만 출력
                                $first_cate_sql = "
										SELECT 
											* 
										FROM 
											tblproductcode 
										WHERE 
											group_code!='NO' 
											AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') 
											AND code_b = '000' 
											AND code_c = '000' 
											AND code_d = '000' 
										ORDER BY 
											sequence 
										DESC";
                                $first_cate_result = pmysql_query($first_cate_sql,get_db_conn());
                                while($first_cate_row=pmysql_fetch_object($first_cate_result)) {
                                    ?>
                                    <option value = '<?=$first_cate_row->code_a?>' <?=$selected['promo_code'][$first_cate_row->code_a]?>><?=$first_cate_row->code_name?></option>
                                    <?
                                }
                                ?>
                            </select>
                            <input type = 'checkbox' name = 'promo_view' value = 'Y' <?=$checked['promo_view'][$mrow['promo_view']]?>> 메인 노출
                        </td>
                    </tr>
                    <tr>
                        <th style="border-top: 1px solid black; border-left: 1px solid black;">
                            <span>썸네일 이미지(PC)</span>
                            <div class="font_orange">(일반:390X260)<br>(임직원:1900X100)</div>
                        </th>
                        <td style="border-top: 1px solid black; border-right: 1px solid black;">
                            <input type="file" name="thumb_img[]" alt="썸네일 이미지" />
                            <?
                            if($mrow['thumb_img']){
                                ?>
                                <br><img src="<?=$imagepath?><?=$mrow['thumb_img']?>" style="height:30px;" class="img_view_sizeset">
                                <?
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th style="border-left: 1px solid black;">
                            <span>메인 이미지 타입 선택(PC)</span>
                        </th>
                        <td style="border-right: 1px solid black;">
                            <input type="radio" name="image_type" value="F" <?if($mrow['image_type']=="F" || $mrow['image_type']=="") echo "checked";?> />파일 업로드 &nbsp;
                            <input type="radio" name="image_type" value="E" <?if($mrow['image_type']=="E") echo "checked";?> />에디터 사용
                        </td>
                    </tr>
                    <tr id="img_E" style="display:none;">
                        <th style="border-bottom: 1px solid black; border-left: 1px solid black;">
                            <span>메인 이미지 에디터(PC)</span>
                            <div class="font_orange">(권장사이즈:1200X@)</div>
                        </th>
                        <td style="border-bottom: 1px solid black; border-right: 1px solid black;"><textarea wrap=off  id="ir1" style="WIDTH: 100%; HEIGHT: 300px" name=content><?=stripslashes($mrow['content'])?></textarea></td>
                    </tr>
                    <tr id="img_F">
                        <th style="border-bottom: 1px solid black; border-left: 1px solid black;">
                            <span>메인 이미지 (PC)</span>
                            <div class="font_orange">(권장사이즈:1200X@)</div>
                        </th>
                        <td style="border-bottom: 1px solid black; border-right: 1px solid black;">
                            <input type="file" name="banner_img[]" alt="본문 이미지" />
                            <?
                            if($mrow['banner_img']){
                                ?>
                                <br><img src="<?=$imagepath?><?=$mrow['banner_img']?>" style="height:30px;" class="img_view_sizeset">
                                <?
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <th style="border-top: 1px solid black; border-left: 1px solid black;">
                            <span>썸네일 이미지(MOBILE)</span>
                            <div class="font_orange">(일반:750X400)<br>(임직원:640X150)</div>
                        </th>
                        <td style="border-top: 1px solid black; border-right: 1px solid black;">
                            <input type="file" name="thumb_img_m[]" alt="썸네일 이미지" />
                            <?
                            if($mrow['thumb_img_m']){
                                ?>
                                <br><img src="<?=$imagepath?><?=$mrow['thumb_img_m']?>" style="height:30px;" class="img_view_sizeset">
                                <?
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th style="border-left: 1px solid black;"><span>메인 이미지 타입 선택(MOBILE)</span></th>
                        <td style="border-right: 1px solid black;">
                            <input type="radio" name="image_type_m" value="F" <?if($mrow['image_type_m']=="F" || $mrow['image_type_m']=="") echo "checked";?> />파일 업로드 &nbsp;
                            <input type="radio" name="image_type_m" value="E" <?if($mrow['image_type_m']=="E") echo "checked";?> />에디터 사용
                        </td>
                    </tr>
                    <tr id="img_FM">
                        <th style="border-bottom: 1px solid black; border-left: 1px solid black;">
                            <span>메인 이미지 (MOBILE)</span>
                            <div class="font_orange">(권장사이즈:750X@)</div>
                        </th>
                        <td style="border-bottom: 1px solid black; border-right: 1px solid black;">
                            <input type="file" name="banner_img[]" alt="본문 이미지" />
                            <?
                            if($mrow['banner_img_m']){
                                ?>
                                <br><img src="<?=$imagepath?><?=$mrow['banner_img_m']?>" style="height:30px;" class="img_view_sizeset">
                                <?
                            }
                            ?>
                        </td>
                    </tr>
                    <tr id="img_EM" style="display:none;">
                        <th style="border-bottom: 1px solid black; border-left: 1px solid black;">
                            <span>메인 이미지 에디터(MOBILE)</span>
                            <div class="font_orange">(권장사이즈:750X@)</div>
                        </th>
                        <td style="border-bottom: 1px solid black; border-right: 1px solid black;"><textarea wrap=off  id="ir1_m" style="WIDTH: 100%; HEIGHT: 300px" name=content_m><?=stripslashes($mrow['content_m'])?></textarea></td>
                    </tr>
                    <tr>
                        <th><span>전시 상태</span></th>
                        <td><select name="display_type" id="display_type">
                                <option value="A" <?if($mrow['display_type']=='A') echo "selected";?>>모두</option>
                                <option value="P" <?if($mrow['display_type']=='P') echo "selected";?>>PC만</option>
                                <option value="M" <?if($mrow['display_type']=='M') echo "selected";?>>모바일만</option>
                                <option value="N" <?if($mrow['display_type']=='N') echo "selected";?>>보류</option>
                                <!-- <option value="S" <?if($mrow['display_type']=='S') echo "selected";?>>PC 비전시</option>
					<option value="D" <?if($mrow['display_type']=='D') echo "selected";?>>모바일 비전시</option>
					<option value="B" <?if($mrow['display_type']=='B') echo "selected";?>>fitflop 모바일만</option>
					<option value="C" <?if($mrow['display_type']=='C') echo "selected";?>>fitflop 모바일 비전시</option> -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><span>노출</span></th>
                        <td>
                            <select name="hidden" >
                                <option value="0" <?if($mrow['hidden']=='0') echo "selected";?>>노출</option>
                                <option value="1" <?if($mrow['hidden']=='1') echo "selected";?>>비노출</option>
                            </select>
                        </td>
                    </tr>
                    <tr id="fmobile" <?if($mrow['display_type']!='B') echo " style='display: none'";?>>
                        <th><span>핏플랍 모바일 타이틀 배너</span></th>
                        <td>
                            <input type="file" name="title_banner[]" alt="본문 이미지" />
                            <?
                            if($mrow['title_banner']){
                                ?>
                                <br><img src="<?=$imagepath?><?=$mrow['title_banner']?>" style="height:30px;" class="img_view_sizeset">
                                <?
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th><span>영역 우선순위</span></th>
                        <td>
                            <select name="mdisplay_seq" id="mdisplay_seq">
                                <?if($count==0){$count=1;} for($i=1; $i<=$mcount; $i++){?>
                                    <option value="<?=$i?>" <?if($mrow['display_seq']== $i) echo "selected";?>><?=$i?></option>
                                <?}?>
                            </select>
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <th><span>쿠폰, 적립금 사용금지</span></th>
                        <td>
                            <input type="checkbox" name="no_coupon" value="Y" <?if($mrow['no_coupon'] == 'Y') echo checked;?> />
                        </td>
                    </tr>
                    <TR>
                        <th><span>노출 기간</span></th>
                        <TD class="td_con1">

                            <?
                            list($start_date, $start_time) = explode(' ',$mrow['start_date']); 
                            $start_time_arr = explode(':',$start_time);
                            list($end_date, $end_time) = explode(' ',$mrow['end_date']); 
                            $end_time_arr = explode(':',$end_time);
                            ?>
                            <INPUT style="TEXT-ALIGN: center" onfocus=this.blur(); onclick=Calendar(event) size=15 name="start_date" value="<?=$start_date?>" class="input calendar">
        
                            <select name="start_date_time[]" class="input">
                                <?
                                for ($i=0; $i<=23; $i++) {
                                    $i = $i<10?"0".$i:$i;
                                    $selected = ($i==$start_time_arr[0])?'selected':'';
                                    echo "<option value='{$i}' {$selected}>$i</option>";
                                }
                                ?>
                            </select>시
                            <select name="start_date_time[]" class="input">
                                <?
                                for ($i=0; $i<=59; $i++) {
                                    $i = $i<10?"0".$i:$i;
                                    $selected = ($i==$start_time_arr[1])?'selected':'';
                                    echo "<option value='{$i}' {$selected}>$i</option>";
                                }
                                ?>
                            </select>분
                            <input type="hidden" name="start_date_time[]" value="00">
                            부터

                            <INPUT style="TEXT-ALIGN: center" onfocus=this.blur(); onclick=Calendar(event) size=15 name=end_date value="<?=$end_date?>" class="input calendar">
                            <select name="end_date_time[]"class="input">
                                <?
                                for ($i=0; $i<=23; $i++) {
                                    $i = $i<10?"0".$i:$i;
                                    $selected = ($i==$end_time_arr[0])?'selected':'';
                                    echo "<option value='{$i}' {$selected}>$i</option>";
                                }
                                ?>
                            </select>시
                            <select name="end_date_time[]"class="input">
                                <?
                                for ($i=0; $i<=59; $i++) {
                                    $i = $i<10?"0".$i:$i;
                                    $selected = ($i==$end_time_arr[1])?'selected':'';
                                    echo "<option value='{$i}' {$selected}>$i</option>";
                                }
                                ?>
                            </select>분
                            <input type="hidden" name="end_date_time[]" value="00">
                            <span>까지</span>
                        </TD>
                    </TR>
                </table>
                &nbsp;

                <!--기획전들-->
                <?if($mode=="ins"){?>
                    <table name="promotable" cellpadding=0 cellspacing=0 border=0 width=100% class="item1" hidden>
                        <tr>
                            <th><span><?=$page_text?> 타이틀</span></th>
                            <td><input type="text" name="title" id="title" style="width:20%" value="" alt="타이틀" /></td>
                        </tr>
                        <tr style='display:none;' >
                            <th><span>타이틀 설명</span></th>
                            <td><textarea name="info" style="width:500;height:100;"></textarea> </td>
                        </tr>
                        <tr>
                            <th><span>영역 우선순위</span></th>
                            <td>
                                <select name="display_seq"class="display_seq">
                                    <?if($count==0){$count=1;}else{ for($i=1; $i<=$count; $i++){?>
                                        <option value="<?=$i?>"><?=$i?></option>
                                    <?}}?>
                                </select>
                            </td>
                        </tr>
                        <tr >
                            <th><span>상품 리스팅 템플릿</span></th>
                            <td><select name="display_tem">
                                    <option value="1" >기본형(웹4단/모바일2단)</option>
                                    <option value="2" >복합형(웹7단/모바일3단)</option>
                                    <option value="3" >강조형(웹2단/모바일1단)</option>
                                    <option value="4" >세로형</option>
                                    <option value="5" >슬라이드형</option>
                                </select>
                            </td>
                        </tr>
                        <input type="hidden" name="ppidx" value="1"/>
                    </table>
                <?}?>
                <div id="add_div"></div>
            </div>
            <div style="width:100%;text-align:center">
                <input type="image" src="../admin/images/btn_confirm_com.gif">
                <img src="../admin/images/btn_list_com.gif" onclick="document.location.href='<?=$return_page_link ?>'">
            </div>


        </form>
    </div>

    <form name="delform" method="post" action="<?=$_SERVER['PHP_SELF']?>" >
        <input type="hidden" name="ppidx" />
        <input type="hidden" name="mode" value="mod" />
        <input type="hidden" name="mode2" value="!!!" />
        <input type="hidden" name="pidx" value="<?=$pidx?>" />
    </form>
    <script type="text/javascript" src="/third_party/SE2/js/HuskyEZCreator.js" charset="utf-8"></script>
    <script language="javascript">
        $(document).ready(function(){
            $(".img_view_sizeset").on('mouseover',function(){
                $("#img_view_div").find('img').attr('src',($(this).attr('src')));
                $("#img_view_div").find('img').css('display','block');
            });

            $(".img_view_sizeset").on('mouseout',function(){
                $("#img_view_div").find('img').css('display','none');
            });

            $('input[name=image_type]:checked').trigger('click');
            $('input[name=image_type_m]:checked').trigger('click');


            //핏플랍 모바일 타이틀 배너 display
            $("#display_type").change(function() {
                if($("#display_type option:selected").val()=="B"){
                    $("#fmobile").show();
                }else{
                    $("#fmobile").hide();
                }
            });
        });

        function del_prmo(t){
            if(confirm("삭제하시겠습니까?")){
                document.delform.ppidx.value=t;
                document.delform.submit();
            }
        }

        var oEditors = [];
        var flagShowEditor = false;
        var flagShowEditor_m = false;

        $('input[name=image_type]').click(function(){
            var type = $(this).val();
            if(type == "E"){
                $('#img_E').show();

                // 에디터를 보여줘야 하는 경우
                if ( flagShowEditor == false ) {

                    nhn.husky.EZCreator.createInIFrame({
                        oAppRef: oEditors,
                        elPlaceHolder: "ir1",
                        sSkinURI: "/third_party/SE2/SmartEditor2Skin.html",
                        htParams : {
                            bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
                            bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
                            bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
                            //aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
                            fOnBeforeUnload : function(){
                            }
                        },
                        fOnAppLoad : function(){
                        },
                        fCreator: "createSEditor2"
                    });

                    flagShowEditor = true;
                }

                $('#img_F').hide();
            }else if(type == "F"){
                $('#img_E').hide();
                $('#img_F').show();
            }
        });

        $('input[name=image_type_m]').click(function(){
            var type = $(this).val();
            if(type == "E"){
                $('#img_EM').show();

                // 에디터를 보여줘야 하는 경우
                if ( flagShowEditor_m == false ) {

                    nhn.husky.EZCreator.createInIFrame({
                        oAppRef: oEditors,
                        elPlaceHolder: "ir1_m",
                        sSkinURI: "/third_party/SE2/SmartEditor2Skin.html",
                        htParams : {
                            bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
                            bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
                            bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
                            //aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
                            fOnBeforeUnload : function(){
                            }
                        },
                        fOnAppLoad : function(){
                        },
                        fCreator: "createSEditor2"
                    });

                    flagShowEditor_m = true;
                }

                $('#img_FM').hide();
            }else if(type == "F"){
                $('#img_EM').hide();
                $('#img_FM').show();
            }
        });

        nhn.husky.EZCreator.createInIFrame({
            oAppRef: oEditors,
            elPlaceHolder: "ir2",
            sSkinURI: "/third_party/SE2/SmartEditor2Skin.html",
            htParams : {
                bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
                bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
                bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
                //aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
                fOnBeforeUnload : function(){
                }
            },
            fOnAppLoad : function(){
            },
            fCreator: "createSEditor2"
        });
    </script>
    <script language="JavaScript">
        /*
        function htmlsetmode(mode,i){
            if(mode==document.eventform.htmlmode.value) {
                return;
            } else {
                i.checked=true;
                editor_setmode('content',mode);
            }
            document.eventform.htmlmode.value=mode;
        }
        _editor_url = "htmlarea/";
        editor_generate('content');
        */
    </script>
<?//include("layer_brandListPop.php");?>
    <script type="text/javascript" src="../js/admin_layer_product_sel.js" ></script>
<?=$onload?>
<?php
include("copyright.php");

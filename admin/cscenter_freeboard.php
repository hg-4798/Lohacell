<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");
include_once($Dir."lib/shopdata.php");
include($Dir."lib/file.class.php");
include("calendar.php");
include("access.php");
####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################
//print_r($_POST);
if(!$_REQUEST['up_board']) $_REQUEST['up_board']= $_REQUEST['board'];

$up_file=new FILE("../data/shopimages/board/".$_REQUEST['up_board']."/");

//include($Dir.BoardDir."file.inc.php");

$prqnaboard=getEtcfield($_shopdata->etcfield,"PRQNA");

$board_name="CS";
$board = 'freeboard';

$setup=array();
$file_icon_path = "images/board/file_icon";
$imgdir = "images/board";
$img = "/admin/img/btn";
$nameLength=20;
//alert_go($_REQUEST["mode"]."@@@@@@@@@@@@@",-1);


$CurrentTime	= time();
$period[0]		= date("Y-m-d",$CurrentTime);
$period[1]		= date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2]		= date("Y-m-d",$CurrentTime-(60*60*24*14));
$period[3]		= date("Y-m-d",strtotime('-1 month'));
$period[4]		= substr($_shopdata->regdate,0,4)."-".substr($_shopdata->regdate,4,2)."-".substr($_shopdata->regdate,6,2);
$search_start = $_REQUEST['search_start'];
$search_end = $_REQUEST['search_end'];
//echo "<br>search start : ".$search_start;
//echo "<br>search end : ".$search_end;
$search_start	= $search_start?$search_start:$period[1];
$search_end	= $search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s		= $search_start?str_replace("-","",$search_start."000000"):"";
$search_e		= $search_end?str_replace("-","",$search_end."235959"):"";

function reWriteForm() {
    global $exec;
    if ($_POST['up_html']) $up_html = "checked";
    $up_subject = urlencode(stripslashes($_POST['up_subject']));
    $up_memo = urlencode(stripslashes($_POST['up_memo']));
    $up_name = urlencode(stripslashes($_POST['up_name']));

    echo "<form name=reWriteForm method=post action={$_SERVER['PHP_SELF']}?exec={$exec}>\n";
    echo "<input type=hidden name=\"mode\" value=\"reWrite\">\n";
    echo "<input type=hidden name=\"thisBoard[name]\" value=\"{$up_name}\">\n";
    echo "<input type=hidden name=\"thisBoard[passwd]\" value=\"{$_POST['up_passwd']}\">\n";
    echo "<input type=hidden name=\"thisBoard[id]\" value=\"{$_POST['up_id']}\">\n";
    echo "<input type=hidden name=\"thisBoard[use_html]\" value=\"{$up_html}\">\n";
    echo "<input type=hidden name=\"thisBoard[title]\" value=\"{$up_subject}\">\n";
    echo "<input type=hidden name=\"thisBoard[content]\" value=\"{$up_memo}\">\n";
    echo "<input type=hidden name=\"thisBoard[pos]\" value=\"{$_POST['pos']}\">\n";

    echo "<input type=hidden name=num value=\"{$_POST['num']}\">\n";
    echo "<input type=hidden name=board value=\"{$_POST['board']}\">\n";
    echo "<input type=hidden name=up_board value=\"{$_POST['up_board']}\">\n";
    echo "<input type=hidden name=s_check value=\"{$_POST['s_check']}\">\n";
    echo "<input type=hidden name=search value=\"{$_POST['search']}\">\n";
    echo "<input type=hidden name=block value=\"{$_POST['block']}\">\n";
    echo "<input type=hidden name=gotopage value=\"{$_POST['gotopage']}\">\n";
    echo "</form>\n";
    echo "<script>document.reWriteForm.submit();</script>";
    //exit;
}

$list_header_bg_color = "#F6F6F6";
$list_header_dark0 = "#DFDFDF";
$list_header_dark1 = "#FFFFFF";
$list_header_back = "#EAF4F6";

$list_mouse_over_color = "#F6F6F6";

$list_divider = "#DFDFDF";

$list_footer_bg_color = "#D6D6D6";

$list_notice_bg_color = "#FEFEFE";
$list_bg_color = "white";

$view_divider = "#cfcfcf";
$view_left_header_color = "#F6F6F6";
$view_body_color = "#FFFFFF";

$comment_header_bg_color = "#CCCCCC";

//코멘트 달기



if($_REQUEST["mode"]=="comment_result") {

    $exec=$_POST["exec"];
    $num=$_POST["num"];
    $block=$_POST["block"];
    $gotopage=$_POST["gotopage"];
    $search=$_POST["search"];
    $s_check=$_POST["s_check"];

    $up_id=$_POST["up_id"];
    $up_name=$_POST["up_name"];
    $up_comment=$_POST["up_comment"];

    $sql = "SELECT * FROM tblcsboard WHERE num = {$num} " ;
    $result = pmysql_query($sql,get_db_conn());
    if ($row=pmysql_fetch_object($result)) {
        pmysql_free_result($result);

        $setup = @pmysql_fetch_array(@pmysql_query("SELECT * FROM tblboardadmin WHERE board = '{$board}' ",get_db_conn()));
        $setup['max_filesize'] = $setup['max_filesize']*(1024*100);
        $setup['btype']=$setup['board_skin'][0];
        if(ord($setup['board'])==0) {
            alert_go('해당 게시판이 존재하지 않습니다.',-1);
        }
    } else {
        $errmsg="댓글 달 게시글이 없습니다.";
        alert_go($errmsg,-1);
    }

    if ($setup['use_comment'] != "Y") {
        $errmsg="해당 게시판은 댓글 기능을 지원하지 않습니다.";
        alert_go($errmsg,-1);
    }

    if(stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])===FALSE) {
        $errmsg="잘못된 경로로 접근하셨습니다.";
        alert_go($errmsg,-1);
    }

    if(isNull($up_comment)) {
        $errmsg="내용을 입력하셔야 합니다.";
        alert_go($errmsg,-1);
    }

    if(isNull($up_name)) {
        $errmsg="이름을 입력하셔야 합니다.";
        alert_go($errmsg,-1);
    }

    $up_name = pg_escape_string($up_name);
    $up_comment = autoLink($up_comment);
    $up_comment = pg_escape_string($up_comment);

    $sql = "INSERT INTO tblcsboardcomment DEFAULT VALUES RETURNING num";
    $row2 = pmysql_fetch_array(pmysql_query($sql,get_db_conn()));
    $sql  = "UPDATE tblcsboardcomment SET ";
    $sql.= "parent		= '{$row->num}', ";
    $sql.= "id		    = '{$up_id}', ";
    $sql.= "name		= '{$up_name}', ";
    $sql.= "ip			= '{$_SERVER['REMOTE_ADDR']}', ";
    $sql.= "writetime	= '".time()."', ";
    $sql.= "comment		= '{$up_comment}' WHERE num={$row2[0]}";
    $insert = pmysql_query($sql,get_db_conn());

    // 코멘트 갯수를 구해서 정리
    $total=pmysql_fetch_array(pmysql_query("SELECT COUNT(*) FROM tblcsboardcomment WHERE parent='{$row->num}'",get_db_conn()));
    pmysql_query("UPDATE tblcsboard SET total_comment='{$total[0]}' WHERE num='{$row->num}'",get_db_conn());

    echo "<script>window.location.href='{$_SERVER['PHP_SELF']}?exec=view&num=$num&block=$block&gotopage=$gotopage&search=$search&s_check=$s_check';</script>";
    exit;
} elseif($_REQUEST["mode"]=="comment_modify_result") {


    $exec=$_POST["exec"];
    $num=$_POST["num"];
    $block=$_POST["block"];
    $gotopage=$_POST["gotopage"];
    $search=$_POST["search"];
    $s_check=$_POST["s_check"];

    $c_no=$_POST["c_no"];
    $up_comment=$_POST["up_comment"];

    $sql = "SELECT * FROM tblcsboard WHERE num = {$num} ";

    $result = pmysql_query($sql,get_db_conn());
    if ($row=pmysql_fetch_object($result)) {
        pmysql_free_result($result);

        $setup = @pmysql_fetch_array(@pmysql_query("SELECT * FROM tblboardadmin WHERE board = '{$board}' ",get_db_conn()));
        $setup['max_filesize'] = $setup['max_filesize']*(1024*100);
        $setup['btype']=$setup['board_skin'][0];
        if(ord($setup['board'])==0) {
            alert_go('해당 게시판이 존재하지 않습니다.',-1);
        }
    } else {
        $errmsg="댓글 달 게시글이 없습니다.";
        alert_go($errmsg,-1);
    }

    if ($setup['use_comment'] != "Y") {
        $errmsg="해당 게시판은 댓글 기능을 지원하지 않습니다.";
        alert_go($errmsg,-1);
    }

    if(stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])===FALSE) {
        $errmsg="잘못된 경로로 접근하셨습니다.";
        alert_go($errmsg,-1);
    }

    if(isNull($up_comment)) {
        $errmsg="내용을 입력하셔야 합니다.";
        alert_go($errmsg,-1);
    }

    $up_comment = autoLink($up_comment);
    $up_comment = pg_escape_string($up_comment);

    $sql  = "UPDATE tblcsboardcomment SET ";
    $sql.= "comment		= '{$up_comment}' WHERE num={$c_no}";
    //echo $sql;
    //exit;
    $insert = pmysql_query($sql,get_db_conn());

    echo "<script>window.location.href='{$_SERVER['PHP_SELF']}?exec=view&num=$num&block=$block&gotopage=$gotopage&search=$search&s_check=$s_check';</script>";
    exit;
} elseif($_REQUEST["mode"]=="comment_del") {
    $exec=$_REQUEST["exec"];
    $num=$_REQUEST["num"];
    $c_num=$_REQUEST["c_num"];
    $block=$_REQUEST["block"];
    $gotopage=$_REQUEST["gotopage"];
    $search=$_REQUEST["search"];
    $s_check=$_REQUEST["s_check"];

    $sql = "SELECT * FROM tblcsboardcomment WHERE parent='{$num}' AND num = {$c_num} ";
    $result = pmysql_query($sql,get_db_conn());
    if ($row=pmysql_fetch_object($result)) {
        $sql = "DELETE FROM tblcsboardcomment WHERE parent='{$num}' AND num = '{$c_num}'";
        $delete = pmysql_query($sql,get_db_conn());

        if ($delete) {
            @pmysql_query("UPDATE tblcsboard SET total_comment = total_comment - 1 WHERE num='{$num}'",get_db_conn());
        }
    }
    //header("Location:{$_SERVER['PHP_SELF']}?exec=view&num=$num&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage");
    echo "<script>window.location.href='{$_SERVER['PHP_SELF']}?exec=view&num=$num&block=$block&gotopage=$gotopage&search=$search&s_check=$s_check';</script>";
    exit;
} elseif($_REQUEST["mode"]=="comment_del_re") {
    $exec=$_REQUEST["exec"];
    $num=$_REQUEST["num"];
    $c_num=$_REQUEST["c_num"];
    $block=$_REQUEST["block"];
    $gotopage=$_REQUEST["gotopage"];
    $search=$_REQUEST["search"];
    $s_check=$_REQUEST["s_check"];

    echo "<script>window.location.href='{$_SERVER['PHP_SELF']}?exec=view&num=$num&block=$block&gotopage=$gotopage&search=$search&s_check=$s_check';</script>";
    exit;
}

$exec=$_REQUEST["exec"];
if(ord($exec)==0) $exec="list";

$s_check=$_REQUEST["s_check"];
$search=$_REQUEST["search"];
$search_site= $_REQUEST["site_name"];
$reply_status=$_REQUEST["reply_status"];



switch ($s_check) {
    case "c":
        $check_c = "selected";
        break;
    case "n":
        $check_n = "selected";
        break;
    case "t":
        $check_t = "selected";
        break;
    case "p":
        $check_p = "selected";
        break;
    default:
        $check_t = "selected";
        break;
}
include("header.php");

?>
<script type="text/javascript">
	function OnChangePeriod(val) {
		var pForm = document.frm;
		var period = new Array(7);
		period[0] = "<?=$period[0]?>";
		period[1] = "<?=$period[1]?>";
		period[2] = "<?=$period[2]?>";
		period[3] = "<?=$period[3]?>";
		period[4] = "<?=$period[4]?>";

		//if(val < 4) {
		pForm.search_start.value = period[val];
		pForm.search_end.value = period[0];
		//}else{
		//    pForm.search_start.value = '';
		//    pForm.search_end.value = '';
		//}
	}
</script>

    <div class="content-wrap">
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td>
                                                    <!-- 페이지 타이틀 -->
                                                    <div class="title_depth3"><?=$board_name?> 게시판</div>
                                                    <!-- 소제목 -->
                                                    <div class="title_depth3_sub"><span>CS 게시판의 모든 게시물을 관리할 수 있습니다.</span></div>
                                                </td>
                                            </tr>
                                            <?if ($exec=='list') {?>
                                                <tr>

                                                    <form method=get name=frm action=<?=$PHP_SELF?>>
                                                        <td>
                                                            <div class="table_style01">
                                                                <table WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>

                                                                    <TR>
                                                                        <th><span>기간선택</span></th>
                                                                        <td>
                                                                            <input class="input_bd_st01" type="text" name="search_start" OnClick="Calendar(event)" value="<?=$search_start?>"/> ~ <input class="input_bd_st01" type="text" name="search_end" OnClick="Calendar(event)" value="<?=$search_end?>"/>
                                                                            <img src=images/btn_today01.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(0)">
                                                                            <img src=images/btn_day07.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(1)">
                                                                            <img src=images/btn_day14.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(2)">
                                                                            <img src=images/btn_day30.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(3)">
                                                                            <img src=images/btn_day_total.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(4)">
                                                                        </td>
                                                                    </TR>
                                                                    <tr>
                                                                        <th><span>검색조건</span></th>
                                                                        <td align=left bgcolor="white">
                                                                            <SELECT name="s_check" class="select" style="width:100px;height:32px;vertical-align:middle;">
                                                                                <OPTION value="">
                                                                                    ---- 검색종류 ----
                                                                                </OPTION>
                                                                                <OPTION value="t" <?=$check_t?>>
                                                                                    제목+내용
                                                                                </OPTION>
                                                                                <OPTION value="n" <?=$check_n?>>
                                                                                    작성자
                                                                                </OPTION>
                                                                                <OPTION value="c" <?=$check_c?>>
                                                                                    고객명
                                                                                </OPTION>
                                                                                <OPTION value="p" <?=$check_p?>>
                                                                                    상품코드
                                                                                </OPTION>
                                                                            </SELECT>
                                                                            <!--
								<INPUT class="input" size="30" name="search" value="<?=$search?>">
								-->
                                                                            <textarea rows="2" cols="10" class="w200" name="search" id="search" style="resize:none;vertical-align:middle;"><?=$search?></textarea>
                                                                            <a
                                                                                    href="javascript:document.frm.submit();"><img src="images/icon_search.gif" alt="검색" align="absMiddle" border="0">
                                                                            </a>
                                                                            <A
                                                                                    href="javascript:search_default();"><IMG src="images/icon_search_clear.gif" align="absMiddle" border="0" hspace="2">
                                                                            </A>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </form>
                                                </tr>
                                                <tr><td height=10></td></tr>
                                            <?}?>
                                            <tr>
                                                <td><?php include("cscenter_freeboard.{$exec}.inc.php"); ?></td>
                                            </tr>
                                            <tr><td height="20"></td></tr>
                                            <!--			<tr>
                                                            <td>
                                                            <div class="sub_manual_wrap">
                                                                    <div class="title"><p>매뉴얼</p></div>
                                                                    <dl>
                                                                        <dt><span>게시판 게시물 관리</span></dt>
                                                                        <dd>
                                                                            - 쇼핑몰에 등록된 게시판의 모든 글을 수정/삭제 및 작성하실 수 있습니다.<br>
                                                                            - 회원 게시판에 별도의 로그인 없이 비밀글 열람 및 게시물 관리가 가능합니다.
                                                                        </dd>
                                                                    </dl>
                                                                </div>
                                                            </td>
                                                        </tr>-->
                                            
										</table>
											</div>

<?=$onload?>
<?php
include("copyright.php");

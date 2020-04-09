<?php // hspark

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/instagram.php");
include("access.php");
#include("calendar.php");
####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################

$mode = $_REQUEST['mode'];
if ($mode=='') $mode = "list";
//echo $mode;
if($mode == "remove_token" || $mode == "list") {
    setcookie("insta_token", "", time()-3600);
    $_COOKIE['insta_token']	= "";
    if($mode == "remove_token") {
        echo "<script>location.href='{$_SERVER['PHP_SELF']}';</script>";
        exit;
    }
}
$token = $_COOKIE['insta_token'];
//echo $token;
/*$s_check = $_REQUEST['s_check'];
$s_check = $s_check?$s_check:"1";
setcookie("insta_brand", $s_check, time()+31536000);*/

switch($mode){
    case "add":
        #debug($_POST);
        $checked_idx = $_POST['checked_idx'];
        $media_id = $_POST['media_id'];
        $insta_id = $_POST['insta_id'];
        $link     = $_POST['link'];
        $img_low  = $_POST['img_low'];
        $img_thm  = $_POST['img_thm'];
        $img_std  = $_POST['img_std'];
        $txt      = $_POST['txt'];

        //$hash_tag_arr	= array('SINWON', 'VIKI', 'SIEG','SIEG FAHRENHEIT','VANHART DI ALBAZAR',"BESTIBELLI","SI");
        //$hash_tag	= $hash_tag_arr[$s_check];
        $hash_tag	= 'JAYJUN';

        foreach($checked_idx as $i){
            $regdt	= date("YmdHis");
            $txt_val  = str_replace("'", "`", $txt[$i]);
            $title	= mb_substr($txt_val, 0, 150, 'utf-8');

            //$sql = "INSERT INTO tblsnsinstamedia (insta_id,media_id,link,image_low,image_thum,image_std,text,reg_date) VALUES ({$insta_id[$i]},{$media_id[$i]},'{$link[$i]}','{$img_low[$i]}','{$img_thm[$i]}','{$img_std[$i]}','{$txt_val}',now())";
            $sql = "
            WITH upsert as (
                update  tblinstagram 
                set 	
                        title = '{$title}',
                        content = '{$txt_val}', 
						link_url = '{$link[$i]}',
						link_m_url = '{$link[$i]}',
						img_file = '{$img_std[$i]}',
						img_rfile = '{$img_std[$i]}',
						img_m_file = '{$img_std[$i]}',
						img_m_rfile = '{$img_std[$i]}',
						hash_tags = '{$hash_tag}',
						display = 'Y',
						regdt = '{$regdt}'
                where insta_id = '{$insta_id}' AND media_id = '{$media_id}'
                RETURNING * 
            )
            insert into tblinstagram (
			title,
			content,
			link_url,
			link_m_url,
			img_file,
			img_rfile,
			img_m_file,
			img_m_rfile,
			hash_tags,
			insta_id,
			media_id,
			display,
			regdt
			) 
            Select  
			'{$title}',
			'{$txt_val}',
			'{$link[$i]}',
			'{$link[$i]}',
			'{$img_std[$i]}',
			'{$img_std[$i]}',
			'{$img_std[$i]}',
			'{$img_std[$i]}',
			'{$hash_tag}',
			'{$insta_id[$i]}',
			'{$media_id[$i]}',
			'Y',
			'{$regdt}'
            WHERE NOT EXISTS ( select * from upsert ) ";
            //exdebug($sql);
            $res = pmysql_query($sql);
            #debug($sql);
        }
        if($res){
            alert_go("추가되었습니다.");
        }else{
            alert_go("오류가 발생하였습니다.");
        }
        break;
    case "search" :
        $insta = new Instagram;
        $insta->client_id	= $insta->jayjun_client_id;
        $insta->client_secret	= $insta->jayjun_client_secret;

        $data = array("access_token"=>$token);
        $insta->api = "v1/users/self/media/recent/";
        $insta->method = 0;
        $res = $insta->get_json($data);

        //image size : low_resolution(320x320),thumbnail(150x150),standard_resolution(640x640)
        if($res->data){
            foreach($res->data as $data){
                $media_id = explode("_", $data->id);
                $res1['media_id'] = $media_id[0];
                $res1['insta_id'] = $data->user->id;
                $res1['link']     = $data->link;
                $res1['img_low']  = $data->images->low_resolution->url;
                $res1['img_thm']  = $data->images->thumbnail->url;
                $res1['img_std']  = $data->images->standard_resolution->url;
                $res1['txt']      = $data->caption->text;
                $res2[] = $res1;
                $media_ids[] = $media_id[0];
            }
            $query = "SELECT media_id, insta_id FROM tblinstagram WHERE media_id in ('".implode("','",$media_ids)."')";
            //$query = "SELECT media_id, insta_id FROM tblinstagram LIMIT 20";
            $result = pmysql_query($query);
            while($row=pmysql_fetch_object($result)) {
                $media_saved[] = $row->media_id."_".$row->insta_id;
            }
            pmysql_free_result($result);
            if(count($media_saved)==20) $msg = "최근 20건 모두 저장되어있습니다.";
        }
        break;
    case "list" :
        $insta = new Instagram;
        $insta->client_id	= $insta->jayjun_client_id;
        $insta->client_secret	= $insta->jayjun_client_secret;

        //$query = "SELECT media_id, insta_id FROM tblinstagram WHERE media_id in ('".implode("','",$media_ids)."')";
        $query = "SELECT * FROM tblinstagram ORDER BY idx DESC LIMIT 20 ";
        $result = pmysql_query($query);
        while($row=pmysql_fetch_array($result)) {
            $media_saved[] = $row;
        }
        pmysql_free_result($result);
        //if(count($media_saved)==20) $msg = "최근 20건 모두 저장되어있습니다.";
        break;

    case "delete" :
        $checked_idx = $_POST['checked_idx'];
        $del_idx = '';
        if (is_array($checked_idx))
        {
            $del_idx = implode(",", $checked_idx);
        }
        $query = "DELETE FROM tblinstagram WHERE idx IN (".$del_idx.")";
        $result = pmysql_query($query,get_db_conn());
        if($result){
            alert_go("삭제하였습니다.");
        }else{
            alert_go("오류가 발생하였습니다.");
        }
        break;

}

$insta_client_id	= Instagram::$jayjun_client_id;


include("header.php");
?>
<style>
    ul#media { line-hegiht:100px }
    ul#media li { float:left }
    ul#media li textarea { width:150px; height:150px; vertical-align:top; background-color:#eee; border:0; padding:3px }
    ul#media li input[type=checkbox]{ vertical-align:top }
</style>
<script type="text/javascript" src="lib.js.php"></script>
<script>
    $( document ).ready( function() {
        <?php if($msg) echo "\talert('{$msg}');"; ?>

        $('#chkall').click( function() {
            $('ul#media input[type=checkbox]:enabled').prop( 'checked', this.checked );
        });
    });

    function insta_search() {
        <?php if(!$token){ ?>
        var url = "https://api.instagram.com/oauth/authorize/?client_id=<?=$insta_client_id?>&redirect_uri=<?=Instagram::$redirect_uri?>&response_type=code";
        window.open(url,"insta_pop","width=420,height=240,resizable=yes");
        <?php }else{ ?>
        document.form1.mode.value = "search";
        //document.form1.s_check.value = document.sForm.s_check.value;
        document.form1.submit();
        <?php }?>
    }

    function insta_add(){
        if($("input[name='checked_idx[]']:checked").length > 0) {
            document.form1.mode.value = "add";
            //document.form1.s_check.value = document.sForm.s_check.value;
            document.form1.submit();
        }else{
            alert("선택된 항목이 없습니다.");
        }
    }

    function insta_del(){
        if($("input[name='checked_idx[]']:checked").length > 0) {
            if (confirm("정말 삭제하시겠습니까?")) {
                document.form1.mode.value = "delete";
                //document.form1.sno.value = id;
                document.form1.submit();
            }
        }else{
            alert("선택된 항목이 없습니다.");
        }
    }
    function remove_token(){
        document.form1.mode.value = "remove_token";
        document.form1.submit();
    }
</script>
<div class="content-wrap">
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <!-- 페이지 타이틀 -->
                <div class="title_depth3">인스타그램 연동 <span>인스타그램에 등록된 최근 20건의 미디어를 저장할 수 있습니다</span></div>
                <!-- 소제목 -->
            </td>
        </tr>
        <form name="sForm" action="<?=$_SERVER['PHP_SELF']?>" method="post">
            <input type="hidden" name="mode" value="list">
        </form>
        <tr>
            <td style="padding-top:4pt;" align="center">
                <button type="button" class="btn-point" onclick="javascript:insta_search();">조회하기</button>&nbsp;
                <? if($res2){ ?>
                    <button type="button" class="btn-point" onclick="javascript:insta_add()">추가하기</button>&nbsp;
                <? }else{ ?>
                    <button type="button" class="btn-point" onclick="javascript:insta_del()">삭제하기</button>&nbsp;
                <? } ?>
            </td>
        </tr>
        <tr><td height="20"></td></tr>
        <?php if($res2){ ?>
            <tr>
                <td style="padding-bottom:3pt;">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="372">&nbsp;</td>
                            <td width="" align="right"><img src="images/icon_8a.gif" border="0">총 : <B><?=count($res2)?></B>건</td>
                        </tr>
                    </table>
                </td>
            </tr>
        <?}?>
        <tr>
            <td>
                <div class="table_style02">
                    <form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
                        <input type=hidden name=mode>
                        <?php if($res2){ ?>
                            <input type="checkbox" id="chkall" style="padding-bottom:10px" /><label for="chkall">모두선택</label>
                            <ul id="media">
                                <?php
                                $idx = 0;
                                foreach($res2 as $data) {
                                    $media = $data['media_id']."_".$data['insta_id'];
                                    $disabled = in_array($media,$media_saved) ? " disabled" : "";
                                    ?>
                                    <li>
                                        <input type="checkbox" name="checked_idx[]" value="<?=$idx?>"<?=$disabled?> />
                                        <input type="hidden" name="media_id[<?=$idx?>]" value="<?=$data['media_id']?>" />
                                        <input type="hidden" name="insta_id[<?=$idx?>]" value="<?=$data['insta_id']?>" />
                                        <input type="hidden" name="link[<?=$idx?>]" value="<?=$data['link']?>" />
                                        <input type="hidden" name="img_low[<?=$idx?>]" value="<?=$data['img_low']?>" />
                                        <input type="hidden" name="img_thm[<?=$idx?>]" value="<?=$data['img_thm']?>" />
                                        <input type="hidden" name="img_std[<?=$idx?>]" value="<?=$data['img_std']?>" />
                                        <a href="<?=$data['link']?>" target="_blank"><img src='<?=$data['img_thm']?>'/></a>
                                        <textarea name="txt[<?=$idx?>]"><?=$data['txt']?></textarea>
                                    </li>
                                    <?php
                                    $idx++;
                                }
                                ?>
                            </ul>
                        <?php }else{ ?>
                            <input type="checkbox" id="chkall" style="padding-bottom:10px" /><label for="chkall">모두선택</label>
                            <ul id="media">
                                <?php
                                foreach($media_saved as $key => $val) {
                                    ?>
                                    <li>
                                        <input type="checkbox" name="checked_idx[]" value="<?=$val['idx']?>" />
                                        <input type="hidden" name="media_id[<?=$val['idx']?>]" value="<?=$val['media_id']?>" />
                                        <input type="hidden" name="insta_id[<?=$val['idx']?>]" value="<?=$val['insta_id']?>" />
                                        <input type="hidden" name="link[<?=$val['idx']?>]" value="<?=$val['link']?>" />
                                        <input type="hidden" name="img_low[<?=$val['idx']?>]" value="<?=$val['img_low']?>" />
                                        <input type="hidden" name="img_thm[<?=$val['idx']?>]" value="<?=$val['img_thm']?>" />
                                        <input type="hidden" name="img_std[<?=$val['idx']?>]" value="<?=$val['img_std']?>" />
                                        <a href="<?=$val['link_url']?>" target="_blank"><img src='<?=$val['img_file']?>' width="150px" height="150px"/></a>
                                        <textarea name="txt[<?=$idx?>]"><?=$val['content']?></textarea>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        <? } ?>
                    </form>
                </div>
            </td>
        </tr>
        <tr><td height=20></td></tr>
        <tr>
            <td>
                <!-- 매뉴얼 -->
                <div class="sub_manual_wrap">
                    <div class="title"><p>매뉴얼</p></div>
                    <dl>
                        <dt><span>인스타그램에 등록된 미디어 연동</span></dt>
                        <dd>- 인스타그램에 로그인하지 않았을 경우 로그인 창이 나옵니다.</dd>
                        <dd>- 최근에 등록된 20건의 미디어가 조회됩니다.</dd>
                        <dd>- 추가할 미디어를 선택한 후, 추가하기 버튼을 누르면 저장됩니다.</dd>
                        <dd>- 이미 추가된 미디어는 체크박스가 비활성화되며, 상품 연계 리스트에서 조회할 수 있습니다.</dd>
                        <dd>- 적용일 기준으로 메인화면(PC 8건, Mobile 6건)에 노출됩니다.</dd>
                    </dl>
                </div>
            </td>
        </tr>
    </table>
</div>
<?php
include("copyright.php");
?>

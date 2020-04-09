<?php
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$mode=$_POST["mode"];
$id = MEMID;
$bank_code = $_POST["bank_code"];
$account_num = $_POST["account_num"];
$depositor = $_POST["depositor"];

//로그인체크
MEMBER::isMember();

include('./include/top.php');
include('./include/gnb.php');

#DB 처리
if($mode == "save"){
    #해당 id 정보에 추가 업데이트
    $where[]="bank_code='".$bank_code."'";
    $where[]="account_num='".$account_num."'";
    $where[]="depositor='".$depositor."'";

    $sql = "UPDATE tblmember SET ";
    $sql.= implode(", ",$where);
    $sql.=" WHERE id = '".$id."'";

    pmysql_query($sql, get_db_conn());
    if(!pmysql_error()){
        alert_go('저장되었습니다.', $_SERVER['REQUEST_URI']);
    }else{
        alert_go('오류가 발생하였습니다.', $_SERVER['REQUEST_URI']);
    }
}




#환불계좌정보 조회
$view_sql ="SELECT id, bank_code, account_num, depositor FROM tblmember
			WHERE id = '".$id."'";
$result = pmysql_query($view_sql, get_db_conn());
$row = pmysql_fetch_object($result);
$rBankCode = $row->bank_code;
$rAccountNum = $row->account_num;
$rDepositor = $row->depositor;

?>

<script src="../static/js/bpopup/jquery.bpopup.min.js"></script>

<!-- 컨텐츠 영역 -->
<div id="contents">
    <div class="mypage-page">

        <h2 class="page-title">배송지 관리</h2>
        <div class="inner-align page-frm clear">

            <!-- LNB -->
            <? include  "mypage_TEM01_left.php";  ?>
            <!-- //LNB -->
            <article class="my-content">
                <form name='refund_form' id='refund_form' action="<?=$_SERVER['PHP_SELF']?>" method='POST' enctype="multipart/form-data">
                    <input type='hidden' id='mode' name='mode'>
                    <input type='hidden' id='home_tel' name='home_tel'>
                    <input type='hidden' id='mobile' name='mobile'>
                    <div class="sub-tit-wrap clear" style="margin-bottom: 0;">
                        <h3>환불계좌 관리</h3>
                    </div>
                    <div class="refund-info-wrap clear">
                        <p>고객님께서 환불 받으실 계좌 정보 입니다.</p>
                        <!-- <div class="c-btns">
                            <a href="" class="btn-small line">초기화</a>
                            <a href="" class="btn-small line">신규등록</a>
                        </div> -->
                    </div>
                    <table class="tb-refund">
                        <colgroup>
                            <col width="335"></col>
                            <col width="350"></col>
                            <col width="335"></col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>은행명</th>
                            <th>계좌번호</th>
                            <th>예금주</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <div class="select" style="width:225px" name="bank_code_div" id="bank_code_div">
                                    <input type="hidden" name="bank_code" id="bank_code" value="<?=$rBankCode?>">
                                    <span class="ctrl"><span class="arrow"></span></span>
                                    <button type="button" class="my_value" ><span style="color: #aaa;">선택</span></button>
                                    <ul class="a_list">
                                        <? foreach ($kcp_bank_code as $k => $v) { ?>
                                            <li id="<?=$k?>"><a href="javascript:;" onclick="bankCodeChange(this);"><?=$v?></a></li>
                                        <? } ?>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="account_num" id="account_num" title="타이틀 작성" placeholder="계좌번호(-를 제외한 숫자 연속 기입)" style="width:330px" value="<?=$rAccountNum?>" maxlength="20" onKeyPress=onlyNumber() onBlur=onlyNumber2(this);>
                                <!-- <a href="" class="btn-regular is-line">인증</a> -->
                            </td>
                            <td>
                                <input type="text" name="depositor" id="depositor" title="예금주" value="<?=$rDepositor?>" placeholder="홍길동" style="width:225px" maxlength="20">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <ul class="c-txt-service" style="margin-top: 20px">
                        <li class="txt-2">
                            <span>- 고객님께서 환불 받으실 계좌번호를 관리하실 수 있습니다.</span>
                        </li>
                        <li class="txt-2">
                            <span>- 보이스 피싱 등 금융사기 방지를 위하여 본인계좌인증이 필요 합니다.</span>
                        </li>
                    </ul>
                    <div class="btn-area ta-c" style="margin-top: 60px;">
                        <button style="width: 169px;" class="btn-basic point mr-10" id="btnSubmit" type="button"><span>확인</span></button><button style="width: 169px;" class="btn-basic line"><span>취소</span></button>
                    </div>
                </form>
            </article>
        </div>
    </div>
    <!-- //컨텐츠 영역 -->
</div>


<script>
    $(function(){
        // 스크롤 시 헤더 컬러 화이트
        var beforeScroll = null;

        $(window).scroll(function(){
            var nowScroll = $(window).scrollTop();

            if(nowScroll > 0){
                $('#header').addClass('bg');
                if(nowScroll > beforeScroll){
                    // 마우스 휠 또는 스크롤 내릴때
                    $('#header').addClass('scroll');
                } else if ( nowScroll < beforeScroll){
                    // 마우스 휠 또는 스크롤 올릴 때
                    $('#header').removeClass('scroll');
                }
            } else if (nowScroll == 0){
                $('#header').removeClass('bg');
            }

            beforeScroll = nowScroll ;
        });

    });
</script>


<script Language="JavaScript">
    function bankCodeChange(obj){
        var select_bankCode = $(obj).parent("li").attr("id");
        if(select_bankCode == ''){
            $("#bank_code").val("");
        }else {
            $("#bank_code").val(select_bankCode);
        }
    }

    $(document).ready(function (){
        //저장된 은행 선택
        var rBank= '<?=$rBankCode?>';
        if(rBank != ''){
            rBank= '#'+'<?=$rBankCode?>';
        }
        if(rBank) {
            $(rBank).attr("class","hover");
            $("#bank_code_div button span").html($("#bank_code_div ul li").filter(".hover").text());
        }

        $("#btnSubmit").click(function(){
            if(check_form()){
                $("#mode").val("save");
                $("#refund_form").submit();
            }
        });
    });

    function check_form() {
        var procSubmit = true;
        var acnt_chk_val = acnt_chk();
        if(!acnt_chk_val){
            return false;
        }
        $(".required_value").each(function(){
            if(!$(this).val()){
                if($(this).attr('label') == "은행명"){
                    alert($(this).attr('label')+"을 정확히 입력해 주세요");
                }else{
                    alert($(this).attr('label')+"를 정확히 입력해 주세요");
                }
                $(this).focus();
                procSubmit = false;
                return false;
            }
        });

        if(procSubmit){
            return true;
        }else{
            return false;
        }
    }


    function acnt_chk(){
        var m = false;
        var bank_code = $("#bank_code").val();
        var owner_nm = $("#depositor").val();
        var account_no = $("#account_num").val();

        if(!bank_code){
            alert("은행명을 선택해주세요.");
            return m;
        }

        if(!account_no){
            alert("계좌번호를 입력해주세요.");
            return m;
        }

        if(!owner_nm){
            alert("예금주를 입력해주세요.");
            return m;
        }

        $.ajax({
            cache: false,
            type: 'POST',
            url: '../paygate/A/pp_cli_hub.php',
            data: "pay_method=ACCO&req_tx=pay&bank_code="+bank_code+"&owner_nm="+owner_nm+"&account_no="+account_no,
            async: false,
            success: function(data) {
                var res = data.split("|||");
                var res_code = res[0].trim();
                if(res_code!="0000") {
                    alert("계좌인증을 실패하였습니다. 계좌정보를 다시 확인해주세요.");
                    //실패시 로그 파일 확인 필요 (paygate/A/payplus/log 폴더 확인)
                    m = false;
                }else {
                    alert("계좌인증이 완료되었습니다.");
                    m = true;
                }
            },
            error: function(result) {
                alert("에러가 발생하였습니다.");
                m = false;
            }
        });
        return m;
    }

    // 숫자만 입력받는다. 특수문자('-','.',...)도 허용한다.
    function onlyNumber() {
        if((event.keyCode > 31) && (event.keyCode < 45) || (event.keyCode > 57)) {
            event.returnValue = false;
        }
    }

    // 숫자만 입력받는다. "-"도 받지않는다.
    function onlyNumber2(loc) {
        if(/[^0123456789]/g.test(loc.value)) {
            alert("숫자만 입력해주세요.");
            loc.value = "";
            loc.focus();
        }
    }
</script>
<?php include('./include/bottom.php'); ?>

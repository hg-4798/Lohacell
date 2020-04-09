<?php
/*********************************************************************
// 파 일 명		: mypage_usermodify.php
// 설     명		: 회원정보 수정/관리
// 상세설명	: 마이페이지에서 회원정보 수정할수 있는 페이지
// 작 성 자		: hspark
// 수 정 자		: 2015.10.30 - 김재수
//
//
 *********************************************************************/
?>
<?php
#---------------------------------------------------------------
# 기본정보 설정파일을 가져온다.
#---------------------------------------------------------------
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

include_once($Dir."conf/config.point.new.php");
// 추가입력 적립금
$reserve_join_over=(int)$pointSet_new['over_point'];

if(strlen($_ShopInfo->getMemid())==0) {
    Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
    exit;
}

$recom_ok=$_data->recom_ok;
$member_addform=$_data->member_addform;

$type=$_POST["type"];
$history="-1";
$sslchecktype="";
if($_POST["ssltype"]=="ssl" && strlen($_POST["sessid"])==64) {
    $sslchecktype="ssl";
    $history="-2";
}

$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
$result=pmysql_query($sql,get_db_conn());
if($row=pmysql_fetch_object($result)) {
    if($row->member_out=="Y") {
        $_ShopInfo->SetMemNULL();
        $_ShopInfo->Save();
        echo "<html><head></head><body onload=\"alert('회원 아이디가 존재하지 않습니다.');parent.location.href='member_agree.php';\"></body></html>";exit;
    }

    if($row->authidkey!=$_ShopInfo->getAuthidkey()) {
        $_ShopInfo->SetMemNULL();
        $_ShopInfo->Save();
        echo "<html><head></head><body onload=\"alert('처음부터 다시 시작하시기 바랍니다.');parent.location.href='member_agree.php';\"></body></html>";exit;
    }

    $id=$row->id;
    if($type!="modify"){
        $name=$row->name;
        $passwd1="";
        $passwd2="";
        $birth=$row->birth;
        $birth1=substr($birth,0,4);
        $birth2=substr($birth,4,2);
        $birth3=substr($birth,6,2);
        //$birth1=$row->birth;
        $lunar=$row->lunar;
        $gender=trim($row->gender);
        $email=Common::Dectypt_AES128CBC($row->email,JayjunKey,JayjunIvKey);
        $email_arr=explode("@",Common::Dectypt_AES128CBC($row->email,JayjunKey,JayjunIvKey));



        if (in_array($email_arr[1], $email_domain_arr)) {
            $email_com	= $email_arr[1];
        } else {
            $email_com	= $email_arr[1]?"custom":"";
        }

        $home_post=$row->home_post;
        $home_post1=substr($row->home_post,0,3);
        $home_post2=substr($row->home_post,3,3);
        $home_addr=$row->home_addr;
        $home_addr_temp=explode("↑=↑",$home_addr);
        $home_addr1=$home_addr_temp[0];
        $home_addr2=Common::Dectypt_AES128CBC($row->home_addr_detail,JayjunKey,JayjunIvKey);
        $home_tel=Common::Dectypt_AES128CBC($row->home_tel,JayjunKey,JayjunIvKey);
        $home_tel_arr=explode("-", Common::Dectypt_AES128CBC($row->home_tel,JayjunKey,JayjunIvKey));
        $mobile=Common::Dectypt_AES128CBC($row->mobile,JayjunKey,JayjunIvKey);
        $mobile_arr=explode("-", Common::Dectypt_AES128CBC($row->mobile,JayjunKey,JayjunIvKey));
        $mem_type=$row->mem_type;
        $job_code=$row->job_code;
        $job=$row->job;
        $skintype=$row->skintype;
        $icon = $imagepath.$row->icon;
        $v_icon = $row->icon;
        $nickname = $row->nickname;

        if($row->news_yn=="Y") {
            $news_mail_yn="Y";
            $news_sms_yn="Y";
        } else if($row->news_yn=="M") {
            $news_mail_yn="Y";
            $news_sms_yn="N";
        } else if($row->news_yn=="S") {
            $news_mail_yn="N";
            $news_sms_yn="Y";
        } else if($row->news_yn=="N") {
            $news_mail_yn="N";
            $news_sms_yn="N";
        }
        $gdn_name=$row->gdn_name;
        //$gdn_birth=$row->gdn_birth;
        //$gdn_birth1=substr($gdn_birth,0,6);
        //$gdn_birth2=substr($gdn_birth,6,1);
        $gdn_birth1=$row->gdn_birth;
        $gdn_gender=$row->gdn_gender;
        $gdn_mobile=$row->gdn_mobile;
        $gdn_email=$row->gdn_email;

        $join_type	= (ord($gdn_name) == 0)?"1":"0";
        $auth_type	= $row->auth_type;
        $bank_code	= $row->bank_code;
        $account_num	= $row->account_num;
        $depositor	= $row->depositor;
    } else {

        $auth_type			= $_POST["auth_type"];										// auth_type

        $passwd1			= $_POST["passwd1"];										// 비밀번호
        $passwd2			= $_POST["passwd2"];										// 비밀번호 확인
        $home_zonecode	= trim($_POST["home_zonecode"]);
        $home_post1			= trim($_POST["home_post1"]);
        $home_post2			= trim($_POST["home_post2"]);
        $home_addr1			= trim($_POST["home_addr1"]);
        $home_addr2			= trim($_POST["home_addr2"]);
        $home_addr_detail = Common::Enctypt_AES128CBC(trim($home_addr2),JayjunKey,JayjunIvKey);

        if ($_POST['home_tel2'] != '' && $_POST['home_tel3'] !='') {
            $home_tel			= Common::Enctypt_AES128CBC(trim($_POST['home_tel']),JayjunKey,JayjunIvKey);	// 전화번호
        } else {
            $home_tel			= "";
        }

        $email				= Common::Enctypt_AES128CBC(trim($_POST['email']),JayjunKey,JayjunIvKey);		// 이메일
        $skintype				= $_POST["skintype"];		// 피부타입
        $news_mail_yn	= $_POST["news_mail_yn"];								// 메일수신여부
        $news_sms_yn	= $_POST["news_sms_yn"];								// SMS수신여부



        $name				= trim($_POST["name"]);										// 이름
        $birth					= trim($_POST["birth1"]).trim($_POST["birth2"]).trim($_POST["birth3"]);		// 생년월일
        $lunar				= trim($_POST['lunar']);									// 음력여부 1양력 0음력
        $gender				= trim($_POST["gender"]);									// 성별
        $dupinfo				= trim($_SESSION[ipin][dupinfo]);
        $mobile				= Common::Enctypt_AES128CBC(trim($_POST['mobile']),JayjunKey,JayjunIvKey);									//휴대폰
        $bank_code			=$_POST['bank_code'];
        $account_num			=$_POST['account_num'];
        $depositor			=$_POST['depositor'];
    }

    $passwd=$row->passwd;
} else {
    $_ShopInfo->SetMemNULL();
    $_ShopInfo->Save();
    echo "<html><head></head><body onload=\"alert('회원 아이디가 존재하지 않습니다.');parent.location.href='member_agree.php';\"></body></html>";exit;
}
pmysql_free_result($result);

if($type=="modify") {
    $onload="";

    if(ord($onload)) {
    } else {
        if(!$onload) {
            if($news_mail_yn=="Y" && $news_sms_yn=="Y") {
                $news_yn="Y";
            } else if($news_mail_yn=="Y") {
                $news_yn="M";
            } else if($news_sms_yn=="Y") {
                $news_yn="S";
            } else {
                $news_yn="N";
            }



            $home_addr			=$home_addr1;
            if ($job_code=='') $job	= "";

            $sql = "UPDATE tblmember SET ";
            if(ord($passwd1)) {
                $shadata = "*".strtoupper(SHA1(unhex(SHA1($passwd1))));
                $sql.= "passwd		= '".$shadata."', ";
            }
            $sql.= "email		= '{$email}', ";
            $sql.= "home_post	= '{$home_zonecode}', ";
            $sql.= "home_addr	= '{$home_addr}', ";
            $sql.= "home_addr_detail	= '{$home_addr_detail}', ";

            $sql.= "home_tel		= '{$home_tel}', ";
            $sql.= "bank_code	= '{$bank_code}', ";
            $sql.= "account_num	= '{$account_num}', ";
            $sql.= "depositor	= '{$depositor}', ";

            $sql.= "skintype			= '{$skintype}', ";
            $sql.= "mobile		= '{$mobile}', ";
            if ($auth_type == 'old') {
                $sql.= "name		= '{$name}', ";
                $sql.= "birth		= '{$birth}', ";
                $sql.= "lunar		= '{$lunar}', ";
                $sql.= "gender		= '{$gender}', ";
                $sql.= "dupinfo		= '{$dupinfo}', ";
                $sql.= "auth_type		= 'mobile', ";
            }


            $sql.= "news_yn		= '{$news_yn}' ";
            $sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";

            //print_r($sql);
            //exit;

            $update=pmysql_query($sql,get_db_conn());

            if($_ShopInfo->getMememail()!=$up_email) {
                $_ShopInfo->setMememail($up_email);
                $_ShopInfo->Save();
            }

            // ERP로 회원정보 전송..2016-12-19
            //sendErpMemberInfo($_ShopInfo->getMemid(), "modify");

            if($old_height=='' && $height!="" && $old_weigh=='' && $weigh!=""){ //추가정보 입력포인트
                insert_point_act($id, $reserve_join_over, '추가정보입력 포인트', '@join_add', $_ShopInfo->getMemid(), 'join_add', 0);
            }

            //SMS 발송
            //sms_autosend( 'mem_modify', $_ShopInfo->getMemid(), '', $ekey );
            //SMS 관리자 발송
            //sms_autosend( 'admin_modify', $_ShopInfo->getMemid(), '', $ekey );
            echo "<html><head></head><body onload=\"alert('개인정보 수정이 완료되었습니다.\\n\\n감사합니다.');parent.location.href='mypage.php';\"></body></html>";exit;
        }
    }
    if(ord($onload)) {
        echo "<html><head></head><body onload=\"alert('{$onload}');\"></body></html>";exit;
    }
}

$checked['news_mail_yn'][$news_mail_yn] = "checked";
$checked['news_sms_yn'][$news_sms_yn] = "checked";
$checked['skintype'][$skintype] = "checked";


include('../front/include/top.php');
include('../front/include/gnb.php');

list ( $pw_cnt ) = pmysql_fetch("SELECT COUNT(passwd) FROM tblmember WHERE id = '".$_ShopInfo->getMemid()."' and passwd =''");

if($pw_cnt > 0){
    $_POST['my_passwd_check'] = 'Y';
}
if ($_POST['my_passwd_check'] != 'Y') { // 비밀번호 확인페이지를 확인 안한 경우
    $menu_title_text	= "회원정보 수정";
    $menu_title_val	= "modify";
    ?>

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="">
                <?include ($Dir.TempletDir."mypage/mypage_mbpasscheck{$_data->design_mbmodify}.php");?>
            </td>
        </tr>
    </table>

    <?
} else {
    ?>
    <SCRIPT LANGUAGE="JavaScript">
        <!--


        function ValidFormPassword(jointype, type){//비밀번호 유효성 체크
            var val			= $("input[name=passwd1]").val();
            var pw2_val	= $("input[name=passwd2]").val();

            if (val == '' && pw2_val == '') {
                $("#passwd1_checked").val("1");
                $("#passwd2_checked").val("1");
                ValidFormPasswordRe(jointype, type);
                return;
            } else {
                if (val == '') {
                    alert("비밀번호를 입력해 주세요.");
                    $("input[name=passwd1]").focus();
                    return;
                }else{
                    if (!(new RegExp(/^(?=.*[a-zA-Z])(?=.*[~!@#$%^*+=-])(?=.*[0-9]).{8,20}$/)).test(val)) {
                        alert('영문 대소문자+숫자+특수문자 조합, 공백 없이 8자 ~ 20자리 이내로 입력해주세요.');
                        $("input[name=passwd1]").focus();
                        return;
                    } else {
                        $("#passwd1_checked").val("1");
                        ValidFormPasswordRe(jointype, type);
                        return;
                    }
                }
            }
        }

        function ValidFormPasswordRe(jointype, type){ //비밀번호 확인 유효성 체크
            var val			= $("input[name=passwd2]").val();
            var pw1_val	= $("input[name=passwd1]").val();

            if (val == '' && pw1_val == '') {
                $("#passwd1_checked").val("1");
                $("#passwd2_checked").val("1");
                ValidFormAddr(jointype, type);
            } else {
                if (val == '') {
                    alert("비밀번호 확인을 입력해 주세요.");
                    $("input[name=passwd2]").focus();
                    return;
                } else {
                    if (val != pw1_val) {
                        alert("비밀번호가 일치하지 않습니다.");
                        $("input[name=passwd2]").focus();
                        return;
                    } else {
                        $("#passwd2_checked").val("1");
                        ValidFormAddr(jointype, type);
                    }
                }
            }
        }

        function ValidFormAddr(jointype, type){ // 주소 유효성 체크
            var home_zonecode	= $("input[name=home_zonecode]").val();
            var home_post1			= $("input[name=home_post1]").val();
            var home_post2			= $("input[name=home_post2]").val();
            var home_addr1			= $("input[name=home_addr1]").val();
            var home_addr2			= $("input[name=home_addr2]").val();

            if (home_zonecode != '' || home_addr1 != '' || home_addr2 != '') {
                if (home_zonecode.length > 5) {
                    alert("신주소를 입력해 주세요.");
                    return;
                } else {
                    if (home_addr1 == '' || home_addr2 == '') {
                        alert("주소를 입력해 주세요.");
                        return;
                    } else {
                        $("#home_addr_checked").val("1");
                        ValidFormEmail(jointype, type);
                        return;
                    }
                }
            } else {
                $("#home_addr_checked").val("1");
                ValidFormEmail(jointype, type);
                return;
            }
        }

        function ValidFormEmail(jointype, type) { //이메일 유효성 체크
            var val1	= $("input[name=email1]").val();
            var val2	= $("input[name=email2]").val();
            if (val1 == '') {
                alert('이메일을 등록해 주세요');
                $("input[name=email1]").focus();
                return;
            } else {

                if($('#email_com').val()==''){
                    alert("이메일 도메인을 선택해 주세요");
                    $('#email_com').focus();
                    return;
                }

                if (val2 == '') {
                    alert("이메일 도메인을 입력해 주세요");
                    $("input[name=email2]").focus();
                    return;
                } else {
                    if (type=='S') {
                        if ($("#email_checked").val() == '0')	{
                            alert("이메일 중복확인을 해주세요.");
                            $("input[name=email1]").focus();
                            return;
                        } else {
                            if ($("#auth_type").val() == 'old') {
                                var u_name			= $("input[name=name]").val();
                                var mobile2			= $("input[name=mobile2]").val();
                                var mobile3			= $("input[name=mobile3]").val();
                                if (u_name =='') {
                                    alert('이름을 입력해 주세요');
                                    $("input[name=name]").focus();
                                    return;
                                } else {
                                    if (mobile2 == '' || mobile3 == '') {
                                        alert('휴대폰 번호를 입력해 주세요');
                                        if (mobile2 == '') {
                                            $("input[name=mobile2]").focus();
                                        } else if (mobile3 == '') {
                                            $("input[name=mobile3]").focus();
                                        }
                                    } else {
                                        document.getElementById("ifrmHidden").src="./checkplus/checkplus_main.php";
                                    }
                                    return;
                                }

                            } else {
                                CheckFormSubmit();
                            }
                            return;
                        }
                    } else {
                        var val = val1 + '@' + val2;
                        $.ajax({
                            type: "GET",
                            url: "../front/iddup.proc.php",
                            data: "email=" + val + "&mode=email",
                            dataType:"json",
                            success: function(data) {
                                if (data.code == 0) {
                                    alert(data.msg);
                                    return;
                                } else {
                                    $("#email_checked").val('1');
                                    alert("등록하신 이메일 "+val+" 는(은) 사용 가능 합니다.");
                                    return;
                                }

                            },
                            error: function(result) {
                                alert("에러가 발생하였습니다.");
                                $("input[name=email1]").focus();
                            }
                        });
                    }
                }
            }
        }

        function ipin_chk(ipin, uname,birthdate,mobileno){
            var cust_name		= $("input[name=name]").val();
            if(mobileno.length < 11) {
                var mobile  = mobileno.substr(0, 3);
                var mobile1 = mobileno.substr(3, 3);
                var mobile2 = mobileno.substr(6, 4);
            }else{
                var mobile  = mobileno.substr(0, 3);
                var mobile1 = mobileno.substr(3, 4);
                var mobile2 = mobileno.substr(7, 4);
            }
            if (cust_name != uname) {
                alert("회원정보와 인증된 정보가 다릅니다. 같은 정보로 인증하시기 바랍니다.");
            } else {
                $('#mobile').val(mobile+'-'+mobile1+'-'+mobile2);
                $("#mobile1 > option[value='"+mobile+"']").attr("selected", true);
                $('#mobile2').val(mobile1);
                $('#mobile3').val(mobile2);
            }
            return;
        }


        function certi_return(rt_yn, rt_name, rt_id, rt_mobileno){
            alert(rt_yn+"1");
            alert(rt_name+"2");
            alert(rt_id+"3");
            alert(rt_mobileno+"4");
            if(rt_yn=='1'){
                var cust_mobile	= $("select[name=mobile1] option:selected").val()+$("input[name=mobile2]").val()+$("input[name=mobile3]").val();
                //alert(rt_mobileno+"/"+cust_mobile);
                if (rt_mobileno != cust_mobile) {
                    alert("회원정보와 인증된 정보가 다릅니다. 같은 정보로 인증하시기 바랍니다.");
                } else {
                    CheckFormSubmit();
                }
                return;
            }else{
                //alert(rt_name+" 고객님께서는 "+rt_id+"로 이미 가입하셨습니다.");
                alert("이미 본인인증된 휴대폰번호 입니다.");
                return;
            }
        }

        function CheckForm(jointype) {
            $("#passwd1_checked").val("0");
            $("#passwd2_checked").val("0");
            $("#home_addr_checked").val("0");
            ValidFormPassword(jointype, 'S');
        }

        function CheckFormSubmit() {

            form=document.form1;

            var passwd1_checked		= $("input[name=passwd1_checked]").val();
            var passwd2_checked		= $("input[name=passwd2_checked]").val();
            var home_addr_checked	= $("input[name=home_addr_checked]").val();
            var email_checked		= $("input[name=email_checked]").val();
            var bank_checked		= $("input[name=bank_checked]").val();
            var bank_code_checked	= $("#bank_code").val();
            var account_num_checked	= $("#account_num").val();
            var depositor_checked	= $("#depositor").val();

            $('#job').val($("select[name=job_code] option:selected").text());
            $('#home_tel').val($("select[name=home_tel1] option:selected").val()+"-"+$("input[name=home_tel2]").val()+"-"+$("input[name=home_tel3]").val());
            $('#email').val($("input[name=email1]").val()+"@"+$("input[name=email2]").val());

            if (passwd1_checked == '1' && passwd2_checked == '1' && home_addr_checked == '1' && email_checked == '1')
            {

                if(bank_checked =="0"){
                    if(bank_code_checked !="" || account_num_checked !="" || depositor_checked !=""){
                        alert("본인계좌인증이 필요합니다");
                        return false;
                    }
                }
                form.type.value="modify";
                form.target	= "HiddenFrame";
                if(confirm("회원님의 개인정보를 수정하시겠습니까?"))
                    form.submit();
                else
                    return;
            } else {
                return
            }
        }


        function customChk(val){

            if((val=='custom')){
                $('#email2').show();
                $('#email2').val('');
                $('#email2').focus();
            }else{
                $('#email2').hide();
                $('#email2').val(val);
            }


        }
        //-->
    </SCRIPT>
    <script src="<?=POST_JS;?>"></script>
    <script>
        document.onkeydown = function () {
            var backspace = 8;
            var t = document.activeElement;

            if (event.keyCode == backspace) {
                if (t.tagName == "SELECT")
                    return false;

                if ((t.tagName == "INPUT" || t.tagName == "TEXTAREA") && $(t).attr("readonly") == "readonly"){
                    return false;
                }
            }
        }

        function openDaumPostcode() {
            new daum.Postcode({
                oncomplete: function(data) {
                    $("#home_zonecode").val(data.zonecode);
                    $("#home_post1").val(data.postcode1);
                    $("#home_post2").val(data.postcode2);
                    $("#home_addr1").val(data.address);
                    $("#home_addr2").val('');
                    $("#home_addr2").focus();
                }
            }).open();
        }
        $(document).ready( function() {
            $('.numbersOnly').keyup(function () {
                this.value = this.value.replace(/[^0-9\.]/g,'');
            });
        });


        function MoCheckForm(type) {

            $('#auth_type').val(type);
            document.getElementById("ifrmHidden").src="./checkplus/checkplus_main.php";

        }
    </script>



    <form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post enctype="multipart/form-data">
        <input type=hidden name=type value="">
        <input type=hidden name=auth_type id=auth_type value="<?=$auth_type?>">
        <input type=hidden name=mem_type value="<?=$mem_type?>">
        <input type=hidden name=join_type value="<?=$join_type?>">
        <input type=hidden name=passwd1_checked id=passwd1_checked value="0">
        <input type=hidden name=passwd2_checked id=passwd2_checked value="0">
        <input type=hidden name=home_addr_checked id=home_addr_checked value="0">
        <input type=hidden name=email_checked id=email_checked value="1">
        <input type=hidden name=bank_checked id=bank_checked value="0">
        <?php
        include ($Dir.TempletDir."mbmodify/mbmodify{$_data->design_mbmodify}.php");
        ?>
    </form>
    <?
}
?>
<form name=outform action="<?$Dir.FrontDir?>mypage_memberout.php" method=post>
    <input type=hidden name=my_passwd_check value="Y">
</form>
<?=$onload?>

<div class="hide"><iframe name="ifrmHidden" id="ifrmHidden" width=1000 height=1000></iframe></div>

<IFRAME name="HiddenFrame" id="HiddenFrame" width=0 height=0 frameborder=0 scrolling="no" marginheight="0" marginwidth="0"></IFRAME>

<? include('../front/include/bottom.php'); ?>

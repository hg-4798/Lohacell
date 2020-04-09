<?php
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");


include_once($Dir."conf/config.point.new.php"); //적립금정보
// 추가입력 적립금
$reserve_join_over=(int)$pointSet_new['over_point'];

######################## 회원정보수정 시작 ########################

if(strlen($_ShopInfo->getMemid())==0) {
	Header("Location:".$Dir.MDir."login.php?chUrl=".getUrl());
	alert_go(null,$Dir.MDir."login.php?chUrl=".getUrl());
	exit;
}

$recom_ok=$_data->recom_ok;
$member_addform=$_data->member_addform;
$imagepath = $Dir.DataDir."shopimages/member_icon/";
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
			alert_go('회원 아이디가 존재하지 않습니다.',$Dir.MDir."login.php");
		}

		if($row->authidkey!=$_ShopInfo->getAuthidkey()) {
			$_ShopInfo->SetMemNULL();
			$_ShopInfo->Save();
			alert_go('처음부터 다시 시작하시기 바랍니다.',$Dir.MDir."login.php");
		}

    $id=$row->id;
    if($type!="modify" && $type =="") {
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
        $email=$row->email;
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
        $news_kakao_yn=$row->kko_yn;

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


        if($type=="usermodify"){
            $home_tel= $_POST['home_tel1']."-".$_POST['home_tel2']."-".$_POST['home_tel3'];
            $home_tel_arr[0]= $_POST['home_tel1'];
            $home_tel_arr[1]= $_POST['home_tel2'];
            $home_tel_arr[2]= $_POST['home_tel3'];
            $mobile= substr($_POST['mobileno'],0,3)."-".substr($_POST['mobileno'],3,4)."-".substr($_POST['mobileno'],7,4);
            $mobile_arr[0]= substr($_POST['mobileno'],0,4);
            $mobile_arr[1]= substr($_POST['mobileno'],3,4);
            $mobile_arr[2]= substr($_POST['mobileno'],7,4);
            $email=$_POST['email1']."@".$_POST['email2'];
            $email_arr[0]=$_POST['email1'];
            $email_arr[1]=$_POST['email2'];
            $email_com	= ($_POST['email2']=="")?"custom":$_POST['email2'];
            $home_post = $_POST['home_zonecode'];
        }else{
            if ($_POST['home_tel2'] != '' && $_POST['home_tel3'] !='') {
                $home_tel			= Common::Enctypt_AES128CBC(trim($_POST['home_tel']),JayjunKey,JayjunIvKey);	// 전화번호
            } else {
                $home_tel			= "";
            }
            $email				= Common::Enctypt_AES128CBC(trim($_POST['email']),JayjunKey,JayjunIvKey);		// 이메일
            $mobile				= Common::Enctypt_AES128CBC(trim($_POST['mobile']),JayjunKey,JayjunIvKey);									//휴대폰
        }

        $skintype				= $_POST["skintype"];		// 피부타입
        $job				= trim($_POST['job']);									//직업text
        $job_code		= trim($_POST['job_code']);									//직업text
        $news_mail_yn	= $_POST["news_mail_yn"];								// 메일수신여부
        $news_sms_yn	= $_POST["news_sms_yn"];								// SMS수신여부



        $name				= trim($_POST["name"]);										// 이름
        $birth					= trim($_POST["birth1"]).trim($_POST["birth2"]).trim($_POST["birth3"]);		// 생년월일
        $lunar				= trim($_POST['lunar']);									// 음력여부 1양력 0음력
        $gender				= trim($_POST["gender"]);									// 성별
        $dupinfo				= trim($_SESSION[ipin][dupinfo]);

        $bank_code			=$_POST['bank_code'];
        $account_num			=$_POST['account_num'];
        $depositor			=$_POST['depositor'];
    }

    $passwd=$row->passwd;

} else {
    $_ShopInfo->SetMemNULL();
    $_ShopInfo->Save();
    alert_go('회원 아이디가 존재하지 않습니다.',$Dir.MDir."login.php");
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

            if($news_kakao_yn!="Y") $news_kakao_yn	= "N";

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
                $sql.= "mobile		= '{$mobile}', ";
                $sql.= "auth_type		= 'mobile', ";
            }

            $sql.= "news_yn		= '{$news_yn}', ";
            $sql.= "kko_yn		= '{$news_kakao_yn}' ";
            $sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";

            //echo $sql;
            //exit;

            $update=pmysql_query($sql,get_db_conn());

            if($_ShopInfo->getMememail()!=$up_email) {
                $_ShopInfo->setMememail($up_email);
                $_ShopInfo->Save();
            }

            //SMS 발송
            //sms_autosend( 'mem_modify', $_ShopInfo->getMemid(), '', $ekey );
            //SMS 관리자 발송
            //sms_autosend( 'admin_modify', $_ShopInfo->getMemid(), '', $ekey );
            alert_go("개인정보 수정이 완료되었습니다.\\n\\n감사합니다.",$Dir.MDir."mypage.php");
        }
    }
    if(ord($onload)) {
        alert_go($onload,$Dir.MDir."mypage_usermodify.php");
    }
}

$checked['news_mail_yn'][$news_mail_yn] = "checked";
$checked['news_sms_yn'][$news_sms_yn] = "checked";
$checked['skintype'][$skintype] = "checked";

######################## 회원정보수정 끝 ########################
include('./include/top.php');
include('./include/gnb.php');



$my_passwd_check_arr	= explode("|", decrypt_md5($_GET['my_passwd_check']));
list ( $pw_cnt ) = pmysql_fetch("SELECT COUNT(passwd) FROM tblmember WHERE id = '".$_ShopInfo->getMemid()."' and passwd =''");
//exdebug($pw_cnt);
if($pw_cnt > 0){
    $my_passwd_check_arr[0] = $_ShopInfo->getMemid();
    $my_passwd_check_arr[1] = 'Y';
}

if ($my_passwd_check_arr[0] != $_ShopInfo->getMemid() && $my_passwd_check_arr[1] != 'Y') { // 비밀번호 확인페이지를 확인 안한 경우
    ?>

    <SCRIPT LANGUAGE="JavaScript">
        <!--

        function CheckForm() {

            form=document.form1;

            //기존 비밀번호 유효성 체크
            var mem_id	= $("input[name=mem_id]").val();
            var val	= $("input[name=oldpasswd]").val();

            if (val == '') {
                alert($("input[name=oldpasswd]").attr("title"));
                $("input[name=oldpasswd]").focus();
                return;
            } else {
                $.ajax({
                    type: "GET",
                    url: "<?=$Dir.FrontDir?>iddup.proc.php",
                    data: {passwd : val, mode : "passwd", access_type : "mobile" , mem_id : mem_id},
                    dataType:"json",
                    success: function(data) {
                        $("#oldpasswd_checked").val(data.code);
                        if (data.code == 0) {
                            alert(data.msg);
                            $("input[name=oldpasswd]").focus();
                            return;
                        }

                        var oldpasswd_checked	= $("input[name=oldpasswd_checked]").val();

                        if (oldpasswd_checked == '1')
                        {
                            location.href = "<?=$_SERVER['PHP_SELF']?>?my_passwd_check=<?=encrypt_md5($_ShopInfo->getMemid().'|Y')?>";
                        } else {
                            return;
                        }
                    },
                    error: function(result) {
                        alert("에러가 발생하였습니다.");
                        $("input[name=oldpasswd]").focus();
                        return;
                    }
                });
            }
        }
        //-->
    </SCRIPT>

    <div id="page">
		<!-- 내용 -->
        <main id="content" class="subpage with_bg">

            <section class="page_local">
                <h2 class="page_title">
                    <a href="javascript:history.back();" class="prev">이전페이지</a>
                    <span>회원정보 수정</span>
                </h2>
            </section><!-- //.page_local -->

            <section class="my_modify sub_bdtop">
                <form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method="post">
                    <input type=hidden name=oldpasswd_checked id=oldpasswd_checked value="0">
                    <input type=hidden name=my_passwd_check id=my_passwd_check value="N">
                    <input type=hidden name=mem_id id=mem_id value="<?=$_ShopInfo->getMemid()?>">
                    <div class="my_modify_pw">
                        <p>본인 확인을 위해 비밀번호를 입력해주세요.</p>
                        <input type="password" class="w100-per mt-25" id="pwd" name="oldpasswd" value = '' title="비밀번호를 입력해 주시기 바랍니다." placeholder="비밀번호 입력">
                        <div class="btn_area mt-15">
                            <ul>
                                <li><a href="javascript:CheckForm();" class="btn-point h-input">확인</a></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </section><!-- //.my_modify -->

        </main>
        <!-- //내용 -->
    </div>

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
                                            var mod_user_data	= 'modify';
                                            mod_user_data += '!@'+$("input[name=auth_type]").val();
                                            mod_user_data += '!@'+$("input[name=name]").val();
                                            mod_user_data += '!@'+$("select[name=birth1] option:selected").val();
                                            mod_user_data += '!@'+$("select[name=birth2] option:selected").val();
                                            mod_user_data += '!@'+$("select[name=birth3] option:selected").val();
                                            mod_user_data += '!@'+$("input:radio[name=lunar]:checked").val();
                                            mod_user_data += '!@'+$("input:radio[name=gender]:checked").val();
                                            mod_user_data += '!@'+$("input[name=passwd1]").val();
                                            mod_user_data += '!@'+$("input[name=home_zonecode]").val();
                                            mod_user_data += '!@'+$("input[name=home_addr1]").val();
                                            mod_user_data += '!@'+$("input[name=home_addr2]").val();
                                            mod_user_data += '!@'+$("input[name=home_tel2]").val();
                                            mod_user_data += '!@'+$("input[name=home_tel3]").val();
                                            mod_user_data += '!@'+$("select[name=home_tel1] option:selected").val()+"-"+$("input[name=home_tel2]").val()+"-"+$("input[name=home_tel3]").val();
                                            mod_user_data += '!@'+$("select[name=mobile1] option:selected").val()+"-"+$("input[name=mobile2]").val()+"-"+$("input[name=mobile3]").val();
                                            mod_user_data += '!@'+$("input[name=email1]").val()+"@"+$("input[name=email2]").val();

                                            mod_user_data += '!@'+$("input[name=height]").val();
                                            mod_user_data += '!@'+$("input[name=weigh]").val();
                                            mod_user_data += '!@'+$("select[name=job_code] option:selected").val();
                                            mod_user_data += '!@'+$("select[name=job_code] option:selected").text();
                                            mod_user_data += '!@';
                                            if($("input[name=news_mail_yn]").prop("checked"))
                                                mod_user_data += 'Y';
                                            mod_user_data += '!@';
                                            if($("input[name=news_sms_yn]").prop("checked"))
                                                mod_user_data += 'Y';
                                            mod_user_data += '!@';
                                            if($("input[name=news_kakao_yn]").prop("checked"))
                                                mod_user_data += 'Y';

                                            $('input[name=mod_user_data]').val(mod_user_data);
                                            document.auth_form.action = "./checkplus/checkplus_main.php";
                                            document.auth_form.submit();
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

            function CheckForm(jointype) {
                $("#passwd1_checked").val("0");
                $("#passwd2_checked").val("0");
                $("#home_addr_checked").val("0");
                ValidFormPassword(jointype, 'S');
            }

            function CheckFormSubmit(){

                form=document.form1;

                var passwd1_checked		= $("input[name=passwd1_checked]").val();
                var passwd2_checked		= $("input[name=passwd2_checked]").val();
                var home_addr_checked	= $("input[name=home_addr_checked]").val();
                var email_checked			= $("input[name=email_checked]").val();
                var bank_code_checked	= $("#bank_code option:selected").val();
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
                    if(confirm("회원님의 개인정보를 수정하시겠습니까?"))
                        form.submit();
                    else
                        return;
                } else {
                    return;
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

        <div id="addressWrap" style="display:none;position:fixed;overflow:hidden;z-index:9999;-webkit-overflow-scrolling:touch;">
            <img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;width:20px;right:0px;top:-1px;z-index:9999" onclick="foldDaumPostcode()" alt="접기 버튼">
        </div>


    <div id="page">
        <!-- 내용 -->
        <main id="content" class="subpage with_bg">

            <section class="page_local">
                <h2 class="page_title">
                    <a href="javascript:history.back();" class="prev">이전페이지</a>
                    <span>회원정보 수정</span>
                </h2>
            </section><!-- //.page_local -->

            <section class="joinpage join_form my_modify sub_bdtop">
                <form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" >
                    <input type=hidden name=type value="">
                    <input type=hidden name=auth_type id=auth_type value="<?=$auth_type?>">
                    <input type=hidden name=mem_type value="<?=$mem_type?>">
                    <input type=hidden name=join_type value="<?=$join_type?>">
                    <input type=hidden name=passwd1_checked id=passwd1_checked value="0">
                    <input type=hidden name=passwd2_checked id=passwd2_checked value="0">
                    <input type=hidden name=home_addr_checked id=home_addr_checked value="0">
                    <input type=hidden name=email_checked id=email_checked value="1">
                    <input type=hidden name=bank_checked id=bank_checked value="0">
                    <div class="form_notice"><strong class="point-color">*</strong> 표시는 필수항목입니다.</div>

                    <div class="board_type_write">
                        <dl>
                            <dt>이름</dt>
                            <dd>

                                    <?=$name?>
                                    <input type="hidden" id="user-name" name="name" value="<?=$name?>" title="이름을 입력하세요." tabindex="1" maxlength="20"></dd>
                        </dl>
                        <dl>
                            <dt>생년월일</dt>
                            <dd>
                                <?
                                if ($auth_type!='old') {
                                    ?>
                                    <span><?=$birth1?>년 <?=$birth2?>월 <?=$birth3?>일
                                    <input type="hidden" id="birth1" name="birth1" value="<?=$birth1?>" tabindex="1" maxlength="20">
                                    <input type="hidden" id="birth2" name="birth2" value="<?=$birth2?>" tabindex="1" maxlength="20">
                                    <input type="hidden" id="birth3" name="birth3" value="<?=$birth3?>" tabindex="1" maxlength="20">
                                    </span>
                                    <?
                                } else {
                                    ?>

                                    <div class="input_tel">
                                        <select class="select_line" id="birth1" name="birth1" >
                                            <?for($y=2017;$y >= 1900;$y--) {?>
                                                <option value="<?=$y?>"><?=$y?></option>
                                            <?}?>
                                        </select>
                                        <span class="dash"></span>
                                        <select class="select_line" id="birth2" name="birth2">
                                            <?for($m=1;$m <= 12;$m++) {?>
                                                <option value="<?=$m<10?'0'.$m:$m?>"><?=$m?></option>
                                            <?}?>
                                        </select>
                                        <span class="dash"></span>
                                        <select class="select_line" id="birth3" name="birth3">
                                            <?for($d=1;$d <= 31;$d++) {?>
                                                <option value="<?=$d<10?'0'.$d:$d?>"><?=$d?></option>
                                            <?}?>
                                        </select>
                                    </div>
                                <?}?>
                                <label class="ml-10" for="birth_typeA">
                                    <input type="radio" class="radio_def" name="lunar" id="birth_typeA" value="1"<?=$lunar=='1'?' checked':''?><?if ($auth_type!='old') {?> disabled='disabled'<?}?>>
                                    <span>양력</span>
                                </label>
                                <label class="ml-5" for="birth_typeB">
                                    <input type="radio" class="radio_def" name="lunar" id="birth_typeB" value="0"<?=$lunar=='0'?' checked':''?><?if ($auth_type!='old') {?> disabled='disabled'<?}?>>
                                    <span>음력</span>
                                </label>
                            </dd>
                        </dl>
                        <dl>
                            <dt>성별</dt>
                            <dd>
                                <?
                                if ($auth_type!='old') {
                                    ?>
                                    <?

                                    if($gender=='1' || $gender=='3'){
                                        echo "남자";
                                    }
                                    if($gender=='0' || $gender=='4'){
                                        echo "여자";
                                    }

                                    ?>
                                    <input type="hidden" id="gender" name="gender" value="<?=$gender?>" tabindex="1" maxlength="20">
                                    <?
                                } else {
                                    ?>
                                    <label class="ml-10" for="genderA">
                                        <input type="radio" class="radio_def" name="gender" id="genderA" value="1"<?=$gender=='1' || $gender=='3'?' checked':''?><?if ($auth_type!='old') {?> disabled='disabled'<?}?>>
                                        <span>남자</span>
                                    </label>
                                    <label class="ml-5" for="genderB">
                                        <input type="radio" class="radio_def" name="gender" id="genderB" value="2"<?=$gender=='0' || $gender=='4'?' checked':''?><?if ($auth_type!='old') {?> disabled='disabled'<?}?>>
                                        <span>여자</span>
                                    </label>
                                    <?
                                }
                                ?>
                            </dd>
                        </dl>
                        <dl>
                            <dt>아이디</dt>
                            <dd>
                                <?=$id?>
                                <input type="hidden" id="user_id" name="user_id" value="<?=$id?>" tabindex="1" maxlength="20">
                            </dd>
                        </dl>
                        <dl>
                            <dt><span class="required">비밀번호</span></dt>
                            <dd>
                                <input type="password" class="w100-per" id="mbReg_pw1" name="passwd1" title="비밀번호 입력자리" placeholder="비밀번호 입력 (영문, 숫자 포함 8~20자리)">
                                <input type="password" class="w100-per mt-5" id="mbReg_pw2" name="passwd2" title="비밀번호 재입력자리" placeholder="비밀번호 확인">
                            </dd>
                        </dl>
                        <dl>
                            <dt><span class="required">주소</span></dt>
                            <dd>
                                <input type="hidden" id="home_post1" name = 'home_post1' value="<?=$home_post1?>">
                                <input type="hidden" id='home_post2' name = 'home_post2' value="<?=$home_pos2?>">
                                <div class="input_addr">
                                    <input type="text" class="w100-per" name = 'home_zonecode' id = 'home_zonecode' value="<?=$home_post?>" placeholder="우편번호" readonly>
                                    <div class="btn_addr"><a href="javascript:;" class="btn-basic h-input" onclick="openDaumPostcode();return false;">주소찾기</a></div>
                                </div>
                                <input type="text" class="w100-per mt-5" name = 'home_addr1' id = 'home_addr1' value="<?=$home_addr1?>" title="검색된 주소" placeholder="기본주소" readonly>
                                <input type="text" class="w100-per mt-5" name = 'home_addr2' id = 'home_addr2' value="<?=$home_addr2?>" title="상세주소 입력" placeholder="상세주소">
                            </dd>
                        </dl>
                        <dl>
                            <dt>전화번호</dt>
                            <dd>
                                <div class="input_tel">
                                    <select class="select_line" id="home_tel1" name="home_tel1">
                                        <option value="02" <?if($home_tel_arr[0]=="02"){?>selected<?}?>>02</option>
                                        <option value="031" <?if($home_tel_arr[0]=="031"){?>selected<?}?>>031</option>
                                        <option value="032" <?if($home_tel_arr[0]=="032"){?>selected<?}?>>032</option>
                                        <option value="033" <?if($home_tel_arr[0]=="033"){?>selected<?}?>>033</option>
                                        <option value="041" <?if($home_tel_arr[0]=="041"){?>selected<?}?>>041</option>
                                        <option value="042" <?if($home_tel_arr[0]=="042"){?>selected<?}?>>042</option>
                                        <option value="043" <?if($home_tel_arr[0]=="043"){?>selected<?}?>>043</option>
                                        <option value="044" <?if($home_tel_arr[0]=="044"){?>selected<?}?>>044</option>
                                        <option value="051" <?if($home_tel_arr[0]=="051"){?>selected<?}?>>051</option>
                                        <option value="052" <?if($home_tel_arr[0]=="052"){?>selected<?}?>>052</option>
                                        <option value="053" <?if($home_tel_arr[0]=="053"){?>selected<?}?>>053</option>
                                        <option value="054" <?if($home_tel_arr[0]=="054"){?>selected<?}?>>054</option>
                                        <option value="055" <?if($home_tel_arr[0]=="055"){?>selected<?}?>>055</option>
                                        <option value="061" <?if($home_tel_arr[0]=="061"){?>selected<?}?>>061</option>
                                        <option value="062" <?if($home_tel_arr[0]=="062"){?>selected<?}?>>062</option>
                                        <option value="063" <?if($home_tel_arr[0]=="063"){?>selected<?}?>>063</option>
                                        <option value="064" <?if($home_tel_arr[0]=="064"){?>selected<?}?>>064</option>
                                    </select>
                                    <span class="dash"></span>
                                    <input type="tel" id="home_tel2" name="home_tel2" value="<?=$home_tel_arr[1]?>" title="선택 전화번호 가운데 입력자리" maxlength="4">
                                    <span class="dash"></span>
                                    <input type="tel" id="home_tel3" name="home_tel3" value="<?=$home_tel_arr[2]?>" title="선택 전화번호 마지막 입력자리" maxlength="4">
                                    <input type="hidden" name="home_tel" id="home_tel" value="<?=$home_tel?>">
                                </div>
                            </dd>
                        </dl>
                        <dl>
                            <dt><span class="required">휴대폰 번호</span></dt>
                            <dd>
								<div class="input_addr">
									<div class="input_tel">
										<select class="select_line" id="mobile1" name="mobile1"  <?=$mobile_arr[0]!="" && $auth_type!='old'?" disabled=\"disabled\"":""?>>
											<option value="010" <?if($mobile_arr[0]=="010"){?>selected<?}?>>010</option>
											<option value="011" <?if($mobile_arr[0]=="011"){?>selected<?}?>>011</option>
											<option value="016" <?if($mobile_arr[0]=="016"){?>selected<?}?>>016</option>
											<option value="017" <?if($mobile_arr[0]=="017"){?>selected<?}?>>017</option>
											<option value="018" <?if($mobile_arr[0]=="018"){?>selected<?}?>>018</option>
											<option value="019" <?if($mobile_arr[0]=="019"){?>selected<?}?>>019</option>
										</select>
										<span class="dash"></span>
										<input type="tel" id="mobile2" name="mobile2" value="<?=$mobile_arr[1]?>" title="필수 휴대폰 번호 가운데 입력자리"<?=$mobile_arr[1]!="" && $auth_type!='old'?" readonly":""?> maxlength="4">
										<span class="dash"></span>
										<input type="tel" id="mobile3" name="mobile3" value="<?=$mobile_arr[2]?>" title="필수 휴대폰 번호 마지막 입력자리"<?=$mobile_arr[2]!="" && $auth_type!='old'?" readonly":""?> maxlength="4">
										<input type="hidden" name="mobile" id="mobile" value="<?=$mobile?>">
									</div>
                                    <div class="btn_addr">
										<button class="btn-basic h-input" onclick="CheckForm_certi('mobile');return false;"><span>변경하기</span></button>
									</div>
								</div>
                            </dd>
                        </dl>
                        <dl>
                            <dt><span class="required">이메일</span></dt>
                            <dd>
                                <div class="input_addr">
                                    <div class="input_mail">
                                        <input type="text" class="w100-per" id="email1" name="email1" value="<?=$email_arr[0]?>" title="이메일 입력" onChange="javascript:$('input[name=email_checked]').val('0');">
                                        <span class="at">&#64;</span>
                                        <select class="select_line" id="email_com" onchange="customChk(this.value);">
                                            <option value="">선택</option>
                                            <option value="custom" <?if($email_com=="custom"){?>selected<?}?>>직접입력</option>
                                            <?php foreach ($email_domain_arr AS $key =>$val) {?>
                                                <option value="<?=$val?>" <? if($email_com==$val){?>selected<?}?>><?=$val?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" id="email" name="email" value="<?=$email?>">
                                    </div>
                                    <div class="btn_addr"><a href="javascript:;" class="btn-basic h-input" onclick="ValidFormEmail('1','');return false;">중복확인</a></div>
                                </div>
                                <input type="text" class="w100-per mt-5" id="email2" name="email2" value="<?=$email_arr[1]?>" title="도메인 직접 입력" placeholder="직접입력" style="<?if($email_com!="custom"){?>display: none;<?}?>" onChange="javascript:$('input[name=email_checked]').val('0');">
                            </dd>
                        </dl>
                        <dl class="">
                            <dt>환불계좌관리</dt>
                            <dd>
                                <!-- 환불계좌 등록 -->
                                <div id="bank_div">
									<div class="form-multi">
										<input type="hidden" name="bank_name" id="bank_name">
										<select class="select_line" title="환불계좌 은행명 선택" name="bank_code" id="bank_code" onchange=" bankCodeChange();">
											<option value="">선택</option>
                                            <? foreach ($kcp_bank_code as $k => $v) {?>
                                                <option value="<?=$k?>" <?if($bank_code==$k)echo "selected";?>><?=$v?></option>
                                            <? } ?>
										</select>
										<input type="text" title="계좌번호 입력자리" placeholder="계좌번호를 입력하세요" name="account_num" id="account_num">
									</div>
									<div class="form-multi">
										<input type="text" title="예금주 입력자리" placeholder="예금주" name="depositor"  id="depositor">
										<button class="btn-basic h-input" onclick="acnt_chk();return false;"><span>본인계좌인증</span></button>
									</div>
                                    <div class="board-attention">∙ 계좌번호는 – 을 빼고 숫자만 입력해주세요.</div>
                                </div>
                                <!-- 등록된 계좌가 있을 시 노출 -->
                                <div class="refund_management hide" id="bank_info">
                                    <span class="txt" id="bank_info_text">우리은행 / 1234567890 / 홍길동</span>
                                    <a href="javascript:;" class="btn-small is-point" onclick="acnt_del();"><span>삭제</span></a>
                                </div>
                            </dd>
                        </dl>
                        <dl>
                            <dt>추가정보</dt>
                            <dd class="body_info">
                                <label class="radio" for="skintype1">
                                    <input type="radio" class="radio_def" name="skintype" id="skintype1" value="1" <?=$checked['skintype']['1']?>>
                                    <span>건성</span>
                                </label>
                                <label class="radio" for="skintype2">
                                    <input type="radio" class="radio_def" name="skintype" id="skintype2" value="2" <?=$checked['skintype']['2']?>>
                                    <span>지성</span>
                                </label>
                                <label class="radio" for="skintype3">
                                    <input type="radio" class="radio_def" name="skintype" id="skintype3" value="3" <?=$checked['skintype']['3']?>>
                                    <span>중성</span>
                                </label>
                                <label class="radio" for="skintype4">
                                    <input type="radio" class="radio_def" name="skintype" id="skintype4" value="4" <?=$checked['skintype']['4']?>>
                                    <span>복합성</span>
                                </label>
                                <label class="radio" for="skintype5">
                                    <input type="radio" class="radio_def" name="skintype" id="skintype5" value="5" <?=$checked['skintype']['5']?>>
                                    <span>트러블성</span>
                                </label>
                                <label class="radio" for="skintype6">
                                    <input type="radio" class="radio_def" name="skintype" id="skintype6" value="6" <?=$checked['skintype']['6']?>>
                                    <span>민감성(과민감성)</span>
                                </label>
                                <label class="radio" for="skintype7">
                                    <input type="radio" class="radio_def" name="skintype" id="skintype7" value="7" <?=$checked['skintype']['7']?>>
                                    <span>아토피</span>
                                </label>
                                <label class="radio" for="skintype0">
                                    <input type="radio" class="radio_def" name="skintype" id="skintype0" value="0" <?=$checked['skintype']['0']?>>
                                    <span>없음</span>
                                </label>
                            </dd>
                        </dl>
                        <dl>
                            <dt>마케팅 활동 동의</dt>
                            <dd>
                                <?=$_data->shopname?>이 제공하는 다양한 이벤트 및 혜택 안내에 대한 수신동의 여부를 확인해주세요. <br>수신 체크 시 고객님을 위한 다양하고 유용한 정보를 제공합니다.
                                <div class="btn_area mt-10">
                                    <ul class="ea3">
                                        <li><label><input type="checkbox" class="check_def" id="news_mail_yn" name="news_mail_yn" value="Y" <?=$checked['news_mail_yn']['Y']?>> <span>이메일 수신</span></label></li>
                                        <li><label><input type="checkbox" class="check_def" id="news_sms_yn" name="news_sms_yn" value="Y" <?=$checked['news_sms_yn']['Y']?>> <span>SMS 수신</span></label></li>
                                    </ul>
                                </div>
                            </dd>
                        </dl>
                    </div><!-- //.board_type_write -->

                    <div class="btn_area mt-30">
                        <ul>
                            <li><a href="javascript:;" onclick="CheckForm('1');return false;" class="btn-point h-input">저장</a></li>
                        </ul>
                    </div>

                </form>

            </section><!-- //.joinpage -->

        </main>
        <!-- //내용 -->
    </div>
    <form method="GET" id="auth_form" name="auth_form">
        <input type=hidden name=mod_user_data>
    </form>

    <form name=outform action="<?$Dir.MDir?>mypage_memberout.php" method=get>
        <input type=hidden name=my_passwd_check value="<?=encrypt_md5($_ShopInfo->getMemid().'|Y')?>">
    </form>
<?}?>

<?php if( $_SERVER['HTTPS'] == 'on' ){ ?>
    <script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
<?php }else{ ?>
    <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<?php } ?>
<script>
    // 우편번호 찾기 찾기 화면을 넣을 element
    var element_layer = document.getElementById('addressWrap');

    function foldDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
        element_layer.style.display = 'none';
    }

    function openDaumPostcode() {
        // 현재 scroll 위치를 저장해놓는다.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('home_zonecode').value = data.zonecode; //5자리 새우편번호 사용
                document.getElementById('home_post1').value = data.postcode1;
                document.getElementById('home_post2').value = data.postcode2;
                document.getElementById('home_addr1').value = fullAddr;
                document.getElementById('home_addr2').value = '';
                document.getElementById('home_addr2').focus();

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_layer.style.display = 'none';

                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                document.body.scrollTop = currentScroll;
            },
            // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
            onresize : function(size) {
                //console.log("Size:", size, element_layer)
                //element_layer.style.height = size.height+'px';
            },
            width : '100%',
            height : '100%'
        }).embed(element_layer);

        // iframe을 넣은 element를 보이게 한다.
        element_layer.style.display = 'block';

        // iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
        initLayerPosition();
    }

    // 브라우저의 크기 변경에 따라 레이어를 가운데로 이동시키고자 하실때에는
    // resize이벤트나, orientationchange이벤트를 이용하여 값이 변경될때마다 아래 함수를 실행 시켜 주시거나,
    // 직접 element_layer의 top,left값을 수정해 주시면 됩니다.
    function initLayerPosition(){
        var width = (window.innerWidth || document.documentElement.clientWidth)-20; //우편번호서비스가 들어갈 element의 width
        var height = (window.innerHeight || document.documentElement.clientHeight)-200; //우편번호서비스가 들어갈 element의 height
        var borderWidth = 1; //샘플에서 사용하는 border의 두께

        // 위에서 선언한 값들을 실제 element에 넣는다.
        element_layer.style.width = width + 'px';
        element_layer.style.height = height + 'px';
        element_layer.style.border = borderWidth + 'px solid';
        // 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
        element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
        element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
    }

    function acnt_chk(){
        var bank_code = $("#bank_code option:selected").val();
        var bank_name = $("#bank_name").val();
        var owner_nm = $("#depositor").val();
        var account_no = $("#account_num").val();
        if(bank_name==""){
            alert('은행을 선택해주세요.');
            return false;
        }
        if(account_no==""){
            alert('계좌번호를 입력해 주세요.');
            $("#account_num").focus();
            return false;
        }
        if(owner_nm==""){
            alert('예금주를 입력해 주세요.');
            $("#depositor").focus();
            return false;
        }

        account_no = parseInt(account_no);
        $.ajax({
            cache: false,
            type: 'POST',
            url: '../third_party/pg/NHNKCP/hub_account.php',
            //contentType: "application/x-www-form-urlencoded;charset=ISO-8859-15",
            data: "pay_method=ACCO&req_tx=pay&bank_code="+bank_code+"&owner_nm="+owner_nm+"&account_no="+account_no,
            async: false,
            dataType:'json',
            success: function(r) {
                if(r.success) {
                    alert(r.msg);
                    $('#bank_div').addClass('hide');
                    $('#bank_info').removeClass('hide');
                    $('#bank_info_text').html(bank_name+ ' / '+account_no+' / '+ owner_nm);
                    $("input[name=bank_checked]").val("1");
                }else {
                    alert(r.msg);
                    return false;
                }
            },
            error: function(result) {
                alert("에러가 발생하였습니다.");

            }
        });
    }


    function acnt_del(){

        $('#bank_div').removeClass('hide');
        $('#bank_info').addClass('hide');
        $("#bank_code").val("");
        $("#bank_name").val("");
        $("#depositor").val("");
        $("#account_num").val("");
        $("#bank_code_div ul li").removeClass("hover");
        $("#bank_code_div button ").html("선택");
        $("input[name=bank_checked]").val("0");
    }

    function bankCodeChange(){

        var select_bankname = $("#bank_code option:selected").text();

        if(select_bankname == '선택'){

            $("#bank_name").val("");
        }else {

            $("#bank_name").val(select_bankname);
        }
    }

    function CheckForm_certi(cert_type){

        if(cert_type == "mobile"){
            var mod_user_data = '!@'+$("input[name=name]").val();
            mod_user_data += '!@'+$("input[name=birth1]").val();
            mod_user_data += '!@'+$("input[name=birth2]").val();
            mod_user_data += '!@'+$("input[name=birth3]").val();
            mod_user_data += '!@'+$("input[name=gender]").val();
            mod_user_data += '!@'+$("input[name=user_id]").val();
            mod_user_data += '!@'+$("input:radio[name=lunar]:checked").val();
            mod_user_data += '!@'+$("select[name=mobile1] option:selected").val();
            mod_user_data += '!@'+$("input[name=mobile2]").val();
            mod_user_data += '!@'+$("input[name=mobile3]").val();
            mod_user_data += '!@'+$("input[name=email1]").val();
            mod_user_data += '!@'+$("input[name=email2]").val();
            mod_user_data += '!@'+$("input:radio[name=skintype]:checked").val();
            mod_user_data += '!@'+$("select[name=home_tel1] option:selected").val();
            mod_user_data += '!@'+$("input[name=home_tel2]").val();
            mod_user_data += '!@'+$("input[name=home_tel3]").val();
            mod_user_data += '!@'+$("input[name=home_zonecode]").val();
            mod_user_data += '!@'+$("input[name=home_addr1]").val();
            mod_user_data += '!@'+$("input[name=home_addr2]").val();
            mod_user_data += '!@'+$("input:checkbox[name=news_mail_yn]:checked").val();
            mod_user_data += '!@'+$("input:checkbox[name=news_sms_yn]:checked").val();
            mod_user_data += '!@';

            //alert(mod_user_data);
            //return;

            $('input[name=mod_user_data]').val(mod_user_data);
            document.auth_form.action = "./checkplus/checkplus_main.php";
            document.auth_form.submit();
        }
    }

    $(document).ready(function (){


        var kcp_bank_name = '<?=$kcp_bank_code[$bank_code]?>';
        var bank_code = '<?=$bank_code?>';
        var account_num = '<?=$account_num?>';
        var depositor = '<?=$depositor?>';

        if(bank_code !=''){
            $('#bank_div').addClass('hide');
            $('#bank_info').removeClass('hide');
            $('#bank_info_text').html(kcp_bank_name+ ' / '+account_num+' / '+ depositor);
            $("#bank_code").val(bank_code);
            $("#account_num").val(account_num);
            $("#depositor").val(depositor);
            $("input[name=bank_checked]").val("1");
        }

    });


</script>

<? include('./include/bottom.php'); ?>

<?php
/*********************************************************************
// 파 일 명		: member_certi.php
// 설     명		: 회원가입 인증 또는 확인
// 상세설명	: 회원가입시 약관 및 간편회원 추가입력폼
// 작 성 자		: 2016.07.28 - 김재수
// 수 정 자		:
//
//
*********************************************************************/
?>
<?php
	session_start();

#---------------------------------------------------------------
# 기본정보 설정파일을 가져온다.
#---------------------------------------------------------------
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");
	include_once($Dir."conf/config.sns.php");

	$mem_type = $_POST[mem_type];
	if (!$mem_type) $mem_type = 0;
	$join_type = $_POST[join_type];

	if(strlen($_ShopInfo->getMemid())>0) {
		$mem_auth_type	= getAuthType($_ShopInfo->getMemid());
		if ($mem_auth_type != 'sns') {
			header("Location:../index.php");
			exit;
		}
	}
	$_SESSION[ipin][name]	="";
	$_SESSION[ipin][dupinfo]	="";
	$_SESSION[ipin][gender]	="";
	$_SESSION[ipin][birthdate]	="";
	$_SESSION[ipin][mobileno]	="";

	include('../front/include/top.php');
	include('../front/include/gnb.php');


    $cut_url= end(explode("/",$_SERVER['HTTP_REFERER']));
    if($cut_url !='login.proc.php'){
        //session_destroy();
        session_unset();

    }
?>



<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(t,type) {

	
		
			$('#auth_type').val(type);
			if(type=='ipin'){
				//document.getElementById("ifrmHidden").src="./ipin/ipin_main.php";
				document.getElementById("ifrmHidden").src="./ipin_new/ipin_main.php";
			}else{
				document.getElementById("ifrmHidden").src="./checkplus/checkplus_main.php";
				//document.getElementById("ifrmHidden").src="./checkplus/checkplus_main_test.php"; // 테스트용
			}		
		


}

function ipin_chk(ipin, uname,ubirth){
    var real_age = getAgeFromBirthDay(ubirth);  //새로운 분기처리부분
    if(real_age < 14){
        alert('만 14세 미만은 회원가입이 불가능합니다'); //만14세 미만일경우 alert return
        return;
    } else {
        document.getElementById("ifrmHidden").src="./member_chkid.php";
    }
    return;
}


function certi_return(rt_yn, rt_name, rt_id, full_id){
    if(rt_yn=='1'){
        $("form[name=form_agree]").submit();
    }else if(rt_yn=='3'){
        alert("회원 탈퇴회원입니다. 90일 후 재가입이 가능합니다.");
        location.href="login.php";
        return;
    }else{
        //alert(rt_name+" 고객님께서는 "+rt_id+"로 이미 가입하셨습니다.");
        alert(rt_name+" 고객님은 "+rt_id+"로 이미 가입한 회원이십니다. 로그인으로 이동합니다.");
        location.href="login.php";
        return;
    }
}
function sns_certi_return(rt_yn, sns_type, date,full_id,mem_date){
    if(rt_yn=="0"){
        $('#member_id').val(full_id);
        $('#result_type').val(rt_yn);
        $('#mem_date').val(mem_date);
    }else{
        $('#result_type').val(rt_yn);
        $('#sns_type').val(sns_type);
        $('#sns_date').val(date);
    }
    $("form[name=form_sns]").submit();


}
function getAgeFromBirthDay(birth_day) {

    var birthday = birth_day;
    var today = new Date();
    var years = today.getFullYear() - birthday.substring(0, 4).toString();

    /* 계속해서 만 나이 계산을 원하면 아래 처리를 계속해준다.
    * 연도가 같은 두 객체를 비교하여 생년월일 객체가 오늘 날짜 객체보다 크다면 -1 해준다.
    * (생일이 아직 지나지 않았다면 -1 을 한다.)*/

    // 생년월일 객체의 연도를 오늘 날짜 객체의 연도로 변경

    var now_year = today.getFullYear();

    var date_year = today.getFullYear().toString();
    var date_month = (today.getMonth()+1).toString();
    var date_date = today.getDate().toString();

    if(date_month.length < 2){
        date_month = '0'+(today.getMonth()+1).toString();
    }
    if(date_date.length < 2){
        date_date = '0'+today.getDate().toString();
    }

    var set_today = date_year+date_month+date_date;

    var get_birth_MD = birthday.substring(4, 8).toString();
    var set_birth = now_year+get_birth_MD;

    // 같은 연도가 된 객체를 비교하여 월일이 지났는 지 여부를 판단하여 years 를 뺀다.
    if (set_today < set_birth) years --;

    return years;

}

//-->
</SCRIPT>

<div id="contents">
	<div class="member-page">
		<form name="form_agree" action="member_agree.php" method=post>
		<input type="hidden" name="auth_type" id="auth_type" >
		<input type="hidden" name="mem_type" id="mem_type" value="0">
		<input type="hidden" name="join_type" id="join_type" value="1">
		<input type="hidden" name="staff_join" value="<?=$_REQUEST['staff_join']?>">
		<input type="hidden" name="cooper_join" value="<?=$_REQUEST['cooper_join']?>">

		<article class="memberJoin-wrap">
			<header class="join-title">
				<h2>회원가입</h2>
				<ul class="flow clear">
					<li class="active"><div><i></i><span>STEP 1</span>본인인증</div></li>
					<li><div><i></i><span>STEP 2</span>약관동의</div></li>
					<li><div><i></i><span>STEP 3</span>정보입력</div></li>
					<li><div><i></i><span>STEP 4</span>가입완료</div></li>
				</ul>
			</header>
			<section class="align-inner join-certification">
				<header class="sub-title">
					<h3>실명인증</h3>
					<p class="att">고객님의 개인정보 보호를 위해 본인인증을 해주세요. <br>휴대폰 인증 및 아이핀 인증이 가능합니다.</p>
				</header>
				<div class="frm-box mt-40 clear">
					<div class="inner">
						<img src="/jayjun/web/static/img/common/certification_phone.png" alt="휴대폰 인증">
						<div class="comment mt-25"><span>본인명의의 휴대폰 번호로 인증하여 회원가입을 진행합니다.<br>타인명의/법인 휴대폰 회원님은 본인인증이 불가합니다.</span></div>
						<button class="btn-point h-large" type="button" onclick="CheckForm('1','mobile');"><span>휴대폰 인증</span></button>
					</div>
					<div class="inner">
						<img src="/jayjun/web/static/img/common/certification_ipin.png" alt="아이핀 인증">
						<div class="comment mt-25"><span>아이핀으로 인증하여 회원가입을 진행합니다.</span></div>
						<button class="btn-point h-large" type="button" onclick="CheckForm('1','ipin');"><span>아이핀 인증</span></button>
					</div>
				</div><!-- //.frm-box -->
			</section>
			
		</article>
		</form>
	</div>
</div><!-- //#contents -->

<form name="form_sns" action="member_sns_end.php" method=post>
    <input type="hidden" name="member_id" id="member_id" >
    <input type="hidden" name="mem_date" id="mem_date" >
    <input type="hidden" name="result_type" id="result_type" value="">
    <input type="hidden" name="sns_type" id="sns_type" value="">
    <input type="hidden" name="sns_date" id="sns_date" value="">
</form>

<div class="hide"><iframe name="ifrmHidden" id="ifrmHidden" width=1000 height=1000></iframe></div>
<? include('../front/include/bottom.php'); ?>
	

<?

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once("lib.inc.php");
include('./include/top.php');
include('./include/gnb.php');
//include_once('sub_header.inc.php');
$subTitle = "아이디 찾기";
if(MEMID) {
	echo ("<script>location.replace('/m/');</script>");
	exit;
}

$CertificationData = pmysql_fetch_object(pmysql_query("select realname_id, realname_password, realname_check, realname_adult_check, ipin_id, ipin_password, ipin_check, ipin_adult_check from tblshopinfo"));

if(!$CertificationData->ipin_check  && !$CertificationData->realname_check){
	$mail_chk="checked";
}

$now_find_type	= "wakeup";
$now_cert_type	= $_GET['now_cert_type'];
$mode				= "wakeup";
$name				= $_GET['name'];
$uid					= $_GET['uid'];
?>
<script>
	var now_find_type	= "wakeup";
	var now_cert_type	= "";
	var mode = "<?=$mode?>";
	$(document).ready(function(){
	});


	function go_submit(find_type, cert_type){

		now_find_type	= find_type;
		now_cert_type	= cert_type;

		if(cert_type=="mobile"){
            document.auth_form.action = "./checkplus/checkplus_main.php";
			//document.auth_form.action = "./checkplus/checkplus_main_test.php"; // 테스트용
            $("#au_auth_type").val("wakeup");
            $("#au_find_type").val(find_type);
            $("#au_cert_type").val(cert_type);
            document.auth_form.submit();
		}else{
			document.auth_form.action = "./ipin_m_new/ipin_main.php";
            $("#au_auth_type").val("wakeup");
            $("#au_find_type").val(find_type);
            $("#au_cert_type").val(cert_type);
			document.auth_form.submit();
		}	
}

</script>
<div id="page">
<!-- 내용 -->
<main id="content" class="subpage with_bg">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>휴면회원안내</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="loginpage sub_bdtop">

		<div class="idpw_tab tab_type1" data-ui="TabMenu">
			<!-- 아이디 찾기 -->
			<div class="tab-content active" data-content="content">
				<!-- 찾기 -->
				<div class="certification">
					<div class="icon certi_phone"><img src="/jayjun/m/static/img/icon/icon_certi_phone.png" alt="휴대폰 인증"></div>
					<div class="info">
						<p>본인명의의 휴대폰 번호로 가입여부 및 본인여부를 확인합니다. 타인명의/법인 휴대폰 회원님은 본인인증이 불가합니다.</p>
						<a href="javascript:go_submit('wakeup','mobile');" class="btn-point">휴대폰 인증</a>
					</div>
				</div>
				<div class="certification">
					<div class="icon certi_ipin"><img src="/jayjun/m/static/img/icon/icon_certi_ipin.png" alt="아이핀 인증"></div>
					<div class="info">
						<p>회원가입시 아이핀으로 가입한 경우 본인여부 확인이 가능합니다.</p>
						<a href="javascript:go_submit('wakeup','ipin');" class="btn-point">아이핀 인증</a>
					</div>
				</div>
				<!-- //찾기 -->
			</div>
			<!-- //아이디 찾기 -->
		</div>

	</section><!-- //.loginpage -->

</main>
<!-- //내용 -->
</div>
<form method="GET" id="auth_form" name="auth_form">
	<input type="hidden" id="au_auth_type" name="auth_type" />
	<input type="hidden" id="au_find_type" name="find_type" />
	<input type="hidden" id="au_cert_type" name="cert_type" />
</form>

<div class="hide"><iframe name="ifrmHidden" id="ifrmHidden" width=1000 height=1000></iframe></div>
<? include('../m/include/bottom.php');?>

<?php
/**
 * 아이디찾기
 */
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

if(MEMID) { //로그인상태인경우 메인으로
    alert_go('','main.php');
	exit;
}

$CertificationData = pmysql_fetch_object(pmysql_query("select realname_id, realname_password, realname_check, realname_adult_check, ipin_id, ipin_password, ipin_check, ipin_adult_check from tblshopinfo"));
if(!$CertificationData->ipin_check  && !$CertificationData->realname_check){
	$mail_chk="checked";
}
?>
<script>
	var now_find_type	= "wakeup";
	var now_cert_type	= "";

	function ipin_chk(ipin, uname){
		var name	= "";
		var id			= "";
		var email	= "";

		$.ajax({
			type: "POST",
			url: "<?=$Dir.FrontDir?>find_idpw_indb.php",
			data: {mode:now_find_type, cert_type:now_cert_type, name:name, id:id, email:email},
			dataType:"json",
			success: function(data) {
				if (data.code == '1') {
					alert("휴면이 해제되었습니다.");
					location.href = "<?=$Dir.MainDir?>main.php";
				} else {
					alert(data.msg);
					location.href = "<?=$Dir.FrontDir?>member_sleep.php";
				}
			},
			error: function(result) {
				alert("에러가 발생하였습니다.");
			}
		});
	}

	function go_submit(find_type, cert_type){

		now_find_type	= find_type;
		now_cert_type	= cert_type;

		if(cert_type=="mobile"){
			//document.getElementById("ifrmHidden").src='./checkplus/checkplus_main_test.php';
			document.getElementById("ifrmHidden").src='./checkplus/checkplus_main.php';
		}else if(cert_type=="ipin"){
			document.getElementById("ifrmHidden").src="./ipin_new/ipin_main.php";
		}	
	}
    $(document).ready(function(){
    });

</script>
<?
$page_code = "find_id_pw";
?>


<div id="contents">
	<div class="member-page">

		<article class="memberLogin-wrap certification" data-ui="TabMenu">
			<header class="login-title"><h2>휴면회원안내</h2></header>
			<form name="findform" action="member_sleep.php" method="post" onsubmit="return;">
			<input type="hidden" name="mode" value="findid">
			<input type="hidden" name="u_id">
			<input type="hidden" name="dinfo">

			<div class="frm-box mt-50">
				<h4 class="title-ment border">
					<strong>회원님의 계정은 현재 휴면 상태입니다.</strong>
					<span class="assist">개인정보 보호를 위해 1년 이상 구매 또는 로그인 이력이 없으신 회원님의 개인정보는 별도 보관 처리 됩니다.
					<br>
					<strong>아래 본인 인증 후 휴면상태가 해제되고 로그인 이후 정상적인 서비스를 이용하실 수 있습니다.</strong>
					</span>
				</h4>
			</div>
			
			<div class="frm-box mt-50 with-inner active" data-content="content" id="id_search_div">
				<div class="inner">
					<img src="/jayjun/web/static/img/common/certification_phone.png" alt="휴대폰 인증">
					<div class="comment mt-25"><span>본인명의의 휴대폰 번호로 가입여부 및 본인여부를 확인합니다.<br>타인명의/법인 휴대폰 회원님은 본인인증이 불가합니다.</span></div>
					<button class="btn-point h-large" type="button" onclick="go_submit('wakeup','mobile');"><span>휴대폰 인증</span></button>
					
				</div>
				<div class="inner">
					<img src="/jayjun/web/static/img/common/certification_ipin.png" alt="아이핀 인증">
					<div class="comment mt-25"><span>회원가입시 아이핀으로 가입한 경우 본인여부 확인이 가능합니다.</span></div>
					<button class="btn-point h-large" type="button" onclick="go_submit('wakeup','ipin');"><span>아이핀 인증</span></button>
				</div>
				
			</div>
			</form>
		</article>
	</div>
</div><!-- //#contents -->


<div class="hide"><iframe name="ifrmHidden" id="ifrmHidden" width=1000 height=1000></iframe></div>
<? 
include('./include/bottom.php');
?>
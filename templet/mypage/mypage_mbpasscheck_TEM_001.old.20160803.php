<?php
/********************************************************************* 
// 파 일 명		: mypage_mbpasscheck_TEM_001.php 
// 설     명		: 비밀번호 확인
// 상세설명	: 회원정보 수정 또는 탈퇴시 비밀번호 확인을 위한 페이지
// 작 성 자		: 2016.02.27 - 김재수
// 수 정 자		: 
// 
// 
*********************************************************************/ 
?>		

<SCRIPT LANGUAGE="JavaScript">
<!--


function CheckForm() {

	form=document.form1;	

	//기존 비밀번호 유효성 체크
	var val	= $("input[name=oldpasswd]").val();
	if (val == '') {
		alert($("input[name=oldpasswd]").attr("title"));
	} else {
		/*if (!(new RegExp(/^.*(?=.{8,20})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).*$/)).test(val)) {
			$("input[name=oldpasswd]").parent().find(".join-att-ment").html("8~20자 이내 영문, 숫자, 특수문자(!@#$%^&amp;*) 3가지 조합으로 이루어져야 합니다.");
			$("input[name=oldpasswd]").addClass("alert-line");
		} else {*/
			$.ajax({ 
				type: "GET", 
				url: "<?=$Dir.FrontDir?>iddup.proc.php", 
				data: "passwd=" + val + "&mode=passwd",
				dataType:"json", 
				success: function(data) {
					$("#oldpasswd_checked").val(data.code);
					if (data.code == 0) {
						alert(data.msg);
						return;
					}
					
					var oldpasswd_checked	= $("input[name=oldpasswd_checked]").val();
					
					if (oldpasswd_checked == '1')
					{
						form.my_passwd_check.value="Y";
						form.submit();
					} else {
						return;
					}	
				},
				error: function(result) {
					alert("에러가 발생하였습니다."); 
				}
			}); 
		//}
	}
}
//-->
</SCRIPT>

	<div class="containerBody sub-page">

	<div class="breadcrumb">
		<ul>
			<li><a href="/">HOME</a></li>
			<li><a href="mypage.php">MY PAGE</a></li>
			<li class="on"><a><?=$menu_title_text?></a></li>
		</ul>
	</div>

	<!-- LNB -->
	<div class="left_lnb">
		<? include ($Dir.FrontDir."mypage_TEM01_left.php");?> 
		<!---->
	</div><!-- //LNB -->

	<div class="right_section mypage-content-wrap">

			<form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method="post">
			<input type=hidden name=oldpasswd_checked id=oldpasswd_checked value="0">
			<input type=hidden name=my_passwd_check id=my_passwd_check value="N">
			<fieldset>
				<legend><?=$menu_title_text?>시 정보보호를 위해 비밀번호 재확인 입력</legend>
				<div class="memberout-login">
					<div class="inner">
						<p class="ment">회원님의 소중한 정보보호를 위해<br>비밀번호를 재확인하고 있습니다.</p>
						<ul>
							<li><label>아이디</label><input type="text" readonly value="<?=$_ShopInfo->getMemid()?>"></li>
							<li><label for="pwd">비밀번호</label><input type="password" id="pwd" name="oldpasswd" class="input-def" value = '' onfocusout="ValidFormPassword()" title="비밀번호를 입력해 주시기 바랍니다."></li>
						</ul>
					</div>
					<div class="btn-place">
						<a href="javascript:;" class="btn-dib-function" onClick="javascript:CheckForm();"><span>확인</span></a>
						<a href="javascript:history.back(-1);" class="btn-dib-function line" ><span>취소</span></a>
					</div>
				</div>
			</fieldset>
		</form>

	</div><!-- //.right_section -->
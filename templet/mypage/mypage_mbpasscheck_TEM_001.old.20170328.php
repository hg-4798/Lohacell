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

<div id="contents">
	 <!-- 네비게이션 -->
	<div class="top-page-local">
		<ul>
			<li><a href="/">HOME</a></li>
			<li><a href="<?=$Dir?>front/mypage.php">마이 페이지</a></li>
			<li class="on">정보수정</li>
		</ul>
	</div>
	<!-- //네비게이션-->
	<div class="inner">
		<main class="mypage_wrap"><!-- 페이지 성격에 맞게 클래스 구분 -->

			<!-- LNB -->
			<?php
			 include ($Dir.FrontDir."mypage_TEM01_left.php");
			?>
			<!-- //LNB -->

			<article class="mypage_content">
				<section class="mypage_main">

					<div class="title_box_border">
						<h3>정보수정</h3>
					</div>

					<!-- 비밀번호 입력폼 -->
					<div class="password_check mt-40">
						<p class="tit_txt">본인 확인을 위해 <em>비밀번호를 입력해 주세요</em></p>
						<form name="form1" action="<?=$_SERVER['PHP_SELF']?>" onsubmit="CheckForm();return false;" method="post">
						<input type=hidden name=oldpasswd_checked id=oldpasswd_checked value="0">
						<input type=hidden name=my_passwd_check id=my_passwd_check value="N">
							<fieldset>
								<label for="pwd" class="hide">비밀번호 확인</label>
								<input type="password" id="pwd" name="oldpasswd" placeholder="비밀번호" onfocusout="ValidFormPassword()" title="비밀번호를 입력해 주시기 바랍니다.">
							</fieldset>
						</form>
						<div class="btn_wrap"><a href="javascript:;" class="btn-type1 c1" onClick="javascript:CheckForm();">확인</a></div>
					</div>
					<!-- // 비밀번호 입력폼 -->

				</section>
			</article>
		</main>
	</div>
</div><!-- //#contents -->
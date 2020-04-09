<?php
/********************************************************************* 
// 파 일 명		: login_TEM_001.php 
// 설     명		: 로그인 템플릿
// 상세설명	: 회원 로그인 템플릿
// 작 성 자		: hspark
// 수 정 자		: 2015.01.07 - 김재수
// 
// 
*********************************************************************/ 
?>
<!-- 메인 컨텐츠 -->
<?php
	$sel_hide[$mode] = 'style="display:none"';	//선택하지 않은 레이어는 숨기기 위해
	$sel_on[$mode] =' class=on';	// 선택한 탭을 on 시키기 위해
	
	if(!$chUrl){
	$chUrl=trim(urldecode($_SERVER["HTTP_REFERER"]));
	}

	$page_code = "login";
?>


<!--로그인시 토큰 값 셋팅 함수 시작 ( 안드로이드에서 호출 )-->
<script type="text/javascript">
	function settingPushSerial(regid,os){
		if (os === undefined) os = "Android";
		$("input[name='push_os']").val(os);
		$("input[name='push_token']").val(regid);
	}
</script>
<!--로그인시 토큰 값 셋팅 함수 종료 ( 안드로이드에서 호출 )-->

	<div class="containerBody sub_skin">

		
		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li class="on"><a>LOGIN</a></li>
			</ul>
		</div>

		<div class="login-wrap login-page">
			<div class="divide-login">
				<div class="inner-login-type">
					<ul class="tab id">
						<li class="on"><button type="button"><span>회원</span></button></li>
						<li><button type="button"><span>비회원(주문조회)</span></button></li>
					</ul>

					<form action="[FORM_ACTION]" method="post" name="form1">
					<input type=hidden name=chUrl value="<?=$chUrl?>">
					<div class="type id">
						<table class="th-left-util">
							<colgroup>
								<col style="width:60px" ><col style="width:auto" >
							</colgroup>
							<tr>
								<th scope="row"><label for="user-id">아이디</label></th>
								<td><input type="text" class="input-def" name="id" id="user-id" maxlength="100" onblur="document.form1.passwd.focus(); "onkeypress="if(event.keyCode==13){CheckForm();}" title="아이디 입력자리"></td>
							</tr>
							<tr>
								<th scope="row"><label for="user-pw">비밀번호</label></th>
								<td><input type="password" class="input-def" name="passwd" maxlength="20" id="user-pw" onkeypress="if(event.keyCode==13){CheckForm();}" title="비밀번호 입력자리"></td>
							</tr>
						</table>
						<div class="btn-place low-margin" ><a href="JavaScript:CheckForm();" class="btn-dib-function"><span>LOGIN</span></a></div>
						<!--로그인시 토큰 필드 시작--><input type="hidden" name="push_token"><input type='hidden' name='push_os'><!--로그인시 토큰 필드 종료-->
					</div>
					
					<div class="type id">
						<table class="th-left-util">
							<colgroup>
								<col style="width:60px" ><col style="width:auto" >
							</colgroup>
							<tr>
								<th scope="row"><label for="user-name">이름</label></th>
								<td><input type="text" class="input-def" name="ordername" id="user-name" maxlength="20" onblur="document.form1.ordercode.focus();" title="이름 입력자리"></td>
							</tr>
							<tr>
								<th scope="row"><label for="order-no">주문번호</label></th>
								<td><input type="text" class="input-def" name="ordercode" maxlength="21" id="order-no" title="주문번호 입력자리"></td>
							</tr>
						</table>
						<div class="btn-place low-margin"><a href=[ORDEROK] class="btn-dib-function"><span>OK</span></a></div>
					</div>

					</form>
					
				</div>
				<!--ul class="attention">
					<li>아이디,비밀번호를 잊으셨나요? <a href="findid.php" class="btn-dib-line"><span>아이디/비밀번호 찾기</span></a></li>
					<li>회원과 동일한 서비스 정책이 적용됩니다. <a href="[NOLOGIN]" class="btn-dib-line"><span>비회원 구매</span></a></li>
				</ul-->
                <ul class="attention">
					<li><a href="findid.php">아이디 찾기</a></li>
					<li><a href="findid.php">비밀번호 찾기</a></li>
                    <?if(basename($chUrl)=="order.php") {?><li><a href="[NOLOGIN]">비회원 구매</a></li><?}?>
				</ul>
			</div>

			<div class="divide-join">
				<div class="inner-join-info">
					<dl>
						<dt>회원가입</dt>
						<dd>10% 할인쿠폰</dd>
						<dd>매월 회원등급별 혜택제공</dd>
						<dd>구매금액의 1% 마일리지 적립</dd>
						<dd>상품리뷰/이벤트 참여시 마일리지 적립 또는 쿠폰 발급</dd>
					</dl>
					<div class="btn-place"><a href="/front/member_agree.php" class="btn-dib-function"><span>JOIN US</span></a></div>
				</div>
			</div>
		</div>
		<div class="banner-local-login"><a href="http://cash-stores.com/board/board.php?pagetype=view&num=239999&board=notice&block=&gotopage=1&search=&s_check="><img src="../static/img/banner/banner_login.jpg" alt=""></a></div>
						<table>
						<tr>
								
							<?if($snsNvConfig["use"] == "nv"){?>
								<td>
									<a href="javascript:;" onclick="javascript:sns_open('/plugin/sns/sns_access.php?sns=<?=$snsNvConfig["use"]?>&sns_login=1&ac=front', '<?=$snsNvConfig["use"]?>');">
										<i></i><span>네이버ID<br>간편로그인</span>
									</a>
								</td>
							<?}?>


							<?if($snsKtConfig["use"] == "kt"){?>
								<td>
									<a href="javascript:;" onclick="javascript:sns_open('/plugin/sns/sns_access.php?sns=<?=$snsKtConfig["use"]?>&sns_login=1&ac=front', '<?=$snsKtConfig["use"]?>');">
										<i></i><span>카카오톡계정<br>간편로그인</span>
									</a>
								</td>
							<?}?>


							<?if($snsFbConfig["use"] == "fb"){?>
								<td>
									<a href="javascript:;" onclick="javascript:sns_open('/plugin/sns/sns_access.php?sns=<?=$snsFbConfig["use"]?>&sns_login=1&ac=front', '<?=$snsFbConfig["use"]?>');">
										<i></i><span>페이스북<br>간편로그인</span>
									</a>
								</td>
							<?}?>

						</tr>
						</table>


        <?php if(false) { ?>
    
		<div class="login_wrap hide">

			<!-- 로그인영역 -->
			<div class="login_area">
				<ul class="login_tab">
					<li<?=$sel_on["member"]?>><a href="javascript:loginTab(0);">회원 로그인</a></li>
					<li<?=$sel_on["nonmember"]?>><a href="javascript:loginTab(1);">비회원 조회하기</a></li>
				</ul>

				<form action="[FORM_ACTION]" method="post" name="form1">
				<input type=hidden name=chUrl value="<?=$chUrl?>">
				<table class="login_form" width=650 cellpadding=0 cellspacing=0 border=0 align=center <?=$sel_hide["nonmember"]?>>
					<colgroup>
						<col width="200" ><col width="250" ><col width="*px" >
					</colgroup>
					<tr>
						<th class="indent">아이디 </th>
						<td><input type="text" class="id_pw" name="id" id="" maxlength="100" onblur="document.form1.passwd.focus(); "onkeypress="if(event.keyCode==13){CheckForm();}"></td>
						<td rowspan=2><a href="JavaScript:CheckForm();" class="btn_A login">로그인</a></td>
					</tr>
					<tr>
						<th class="indent">비밀번호</th>
						<td><input type="password" class="id_pw" name="passwd" maxlength="20" id="" onkeypress="if(event.keyCode==13){CheckForm();}"></td>
					</tr>
					<tr>
						<td colspan=3>
							<ul class="ment">
								<li>아이디 영문/숫자 조합으로 구성합니다. <br >비밀번호는 영문 및 영문/숫자 조합으로 구성하여 영문 대소문자를 구별합니다.</li>
								<li class="mt_10">
									<input type="checkbox" name="idsave" id=""> 아이디저장
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<dl class="guest">
								<dt>비회원 주문</dt>
								<dd>비회원으로 주문하실 경우 <?=$_data->shoptitle?>에서 제공되는 다양한 서비스 혜택을 받으실 수 없습니다.</dd>
								<dd class="mt_5"><a href="[NOLOGIN]" class="btn_util">비회원 구매하기</a></dd>
							</dl>
						</td>
					</tr>
				</table>
				
				
				
				<table class="login_form" width=650 cellpadding=0 cellspacing=0 border=0 align=center <?=$sel_hide["member"]?>>
					<colgroup>
						<col width="200" ><col width="250" ><col width="*px" >
					</colgroup>
					<tr>
						<th class="indent">이름</th>
						<td><input type="text" class="id_pw" name="ordername" id="" maxlength="20" onblur="document.form1.ordercode.focus();" ></td>
						<td rowspan=2><a href=[ORDEROK] class="btn_A login">비회원 조회</a></td>
					</tr>
					<tr>
						<th class="indent">주문번호</th>
						<td><input type="text" class="id_pw" name="ordercode" maxlength="21" id="" ></td>
					</tr>
					<tr>
						<td colspan=3>
							<ul class="ment">
								<li>· 비회원으로 구매한 이력이 있는 경우에만 주문/배송 조회가 가능합니다.</li>
								<li>· 주문/배송 조회 이외의 서비스는 회원 가입 후 이용이 가능합니다.</li>
								<li>· 주문번호가 생각나지 않으실 경우, 고객센터로 문의주시기 바랍니다.</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<dl class="guest">
								<dt><?=$_data->shoptitle?> 회원이 아니신가요?</dt>
								<dd>회원으로 가입하시면 <?=$_data->shoptitle?>의 다양한 서비스를 이용하실 수 있습니다.</dd>
								<dd><a href="member_agree.php" class="btn_util">회원가입</a> <a href="findid.php" class="btn_util">아이디/비밀번호 찾기</a></dd>
							</dl>
						</td>
					</tr>
				</table>

				</form>

				<div class="login_about hide">
					<ul class="about">
						<li><a href="member_agree.php" class="btn_join">회원가입</a></li>
						<li><a href="findid.php" class="btn_find">아이디/비밀번호 찾기</a></li>
					</ul>
				</div>
			</div><!-- //로그인영역 -->
		
		</div><!-- //.login_wrap -->

        <?php } ?>


		<!-- SNS 간편 가입 테스트 -->
		<form action="[FORM_ACTION]" method="POST" name="frmSns">
			<input type="hidden" name="chUrl" value="<?=$chUrl?>">
			<input type='hidden' name='sns_login' value="1">
			<input type='hidden' name='sns_id' value="">
			<input type='hidden' name='sns_email' value="">
			<input type='hidden' name='sns_name' value="">
			<input type='hidden' name='sns_type' value="">
			<input type='hidden' name='sns_token' value="">
		</form>
		<!-- SNS 간편 가입 테스트 -->

	</div><!-- //containerBody -->

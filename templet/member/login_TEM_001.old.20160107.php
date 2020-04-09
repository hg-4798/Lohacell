<?php
/********************************************************************* 
// 파 일 명		: login_TEM_001.php 
// 설     명		: 로그인 템플릿
// 상세설명	: 회원 로그인 템플릿
// 작 성 자		: hspark
// 수 정 자		: 2015.10.28 - 김재수
// 
// 
*********************************************************************/ 
?>
<?php
#---------------------------------------------------------------
# 기본정보를 설정한다.
#---------------------------------------------------------------
	$sel_hide[$mode] = 'style="display:none"';	//선택하지 않은 레이어는 숨기기 위해
	$sel_on[$mode] =' class=on';	// 선택한 탭을 on 시키기 위해
	
	if(!$chUrl){
	$chUrl=trim(urldecode($_SERVER["HTTP_REFERER"]));
	}

	$page_code = "login";
?>
<!-- 메인 컨텐츠 -->
<div class="main_wrap">
	<?
	$subTop_flag = 3;
	//include ($Dir.MainDir."sub_top.php");
	?>
	<div class="containerBody sub_skin">

	<div class="left_lnb">
		<?
		/* lnb 호출 */
		$lnb_flag = 4;
		include ($Dir.MainDir."lnb.php");
		?>
	</div>
	<div class="right_section">
		
		<div class="login_wrap">
			
			<h3 class="title">
				로그인
				<p class="line_map"><a>Member</a> &gt; <a class="on">로그인</a></p>
			</h3>

			<!-- 로그인영역 -->
			<div class="login_area">
				<ul class="login_tab">
					<li<?=$sel_on["member"]?>><a href="javascript:loginTab(0);">회원 로그인</a></li>
					<li<?=$sel_on["nonmember"]?>><a href="javascript:loginTab(1);">페이스북 로그인</a></li>
				</ul>

				<form action="[FORM_ACTION]" method="post" name="form1">
				<input type=hidden name=chUrl value="<?=$chUrl?>">
				<table class="login_form" width=650 cellpadding=0 cellspacing=0 border=0 align=center <?=$sel_hide["nonmember"]?>>
					<colgroup>
						<col width="200" /><col width="250" /><col width="*px" />
					</colgroup>
					<tr>
						<th class="indent">이메일 </th>
						<td><input type="text" class="id_pw" name="email" id="" maxlength="20" onblur="document.form1.passwd.focus(); "onkeypress="if(event.keyCode==13){CheckForm();}"/></td>
						<td rowspan=2><a href="JavaScript:CheckForm();" class="btn_A login">로그인</a></td>
					</tr>
					<tr>
						<th class="indent">비밀번호</th>
						<td><input type="password" class="id_pw" name="passwd" maxlength="20" id="" onkeypress="if(event.keyCode==13){CheckForm();}"/></td>
					</tr>
					<tr>
						<td colspan=3>
							<ul class="ment">
								<li>비밀번호는 영문 및 영문/숫자 조합으로 구성하여 영문 대소문자를 구별합니다.</li>
								<li class="mt_10">
									<!--<input type="checkbox" name="" id="" /> 로그인 상태 유지-->
									<input type="checkbox" name="emailsave" id=""/> 이메일저장
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td colspan=3><a href="javascript:facebook_open('/front/member_join_facebook.php?access=1');" class="btn_D on">페이스북으로 그인</a>
						</td>
					</tr>
					<tr>
						<td colspan=3>
							<dl class="guest">
								<dt>회원이 아니신가요?</dt>
								<dd>회원으로 가입하시면 다양한 서비스를 이용하실 수 있습니다.</dd>
								<dd><a href="member_agree.php" class="btn_util">회원가입</a> <a href="findid.php" class="btn_util">아이디/비밀번호 찾기</a></dd>
							</dl>
						</td>
					</tr>
					<!--tr>
						<td colspan=3>
							<dl class="guest">
								<dt>비회원 주문</dt>
								<dd>비회원으로 주문하실 경우 ORYANY에서 제공되는 다양한 서비스 혜택을 받으실 수 없습니다.</dd>
								<dd class="mt_5"><a href="[NOLOGIN]" class="btn_util">비회원 구매하기</a></dd>
							</dl>
						</td>
					</tr-->
				</table>
				
				
				
				<!-- table class="login_form" width=650 cellpadding=0 cellspacing=0 border=0 align=center <?=$sel_hide["member"]?>>
					<colgroup>
						<col width="200" /><col width="250" /><col width="*px" />
					</colgroup>
					<tr>
						<th class="indent">고객명</th>
						<td><input type="text" class="id_pw" name="ordername" id="" maxlength="20" onblur="document.form1.ordercode.focus();" /></td>
						<td rowspan=2><a href=[ORDEROK] class="btn_A login">비회원 조회</a></td>
					</tr>
					<tr>
						<th class="indent">주문번호</th>
						<td><input type="text" class="id_pw" name="ordercode" maxlength="21" id="" /></td>
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
								<dt>회원이 아니신가요?</dt>
								<dd>회원으로 가입하시면 다양한 서비스를 이용하실 수 있습니다.</dd>
								<dd><a href="member_agree.php" class="btn_util">회원가입</a> <a href="findid.php" class="btn_util">아이디/비밀번호 찾기</a></dd>
							</dl>
						</td>
					</tr>
				</table-->

				</form>
				<form action="[FORM_ACTION]" method="post" name="form11">
				<input type=hidden name=chUrl value="<?=$chUrl?>">
				<input type=hidden name=facebook_id value="">
				<input type=hidden name=facebook_email value="">
				<input type=hidden name=facebook_name value="">
				<input type=hidden name=facebook_token value="">
				</form>

				<div class="login_about hide">
					<ul class="about">
						<li><a href="member_agree.php" class="btn_join">회원가입</a></li>
						<li><a href="findid.php" class="btn_find">아이디/비밀번호 찾기</a></li>
					</ul>
				</div>
			</div><!-- //로그인영역 -->

<!-- 			배너영역
			<div class="rolling_three2 login_banner" style="position:relative">
				<div class="slides_container">
					<ul>
						[LBANNER1]
					</ul>
				</div>
			</div>//배너영역
					</div>
					
					로그인 하단배너
					<ul class="login_bottom_banner">
			[LBANNER2]			
					</ul>
					//로그인 하단배너
			 -->
	</div>

	</div><!-- //container1100 -->
</div><!-- //메인 컨텐츠 -->
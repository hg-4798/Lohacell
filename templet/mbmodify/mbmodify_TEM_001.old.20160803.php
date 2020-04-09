<?php
/********************************************************************* 
// 파 일 명		: membermodify_TEM_001.php 
// 설     명		: 회원정보 수정/관리 HTML
// 상세설명	: 마이페이지에서 회원정보 수정 HTML
// 작 성 자		: hspark
// 수 정 자		: 2015.10.30 - 김재수
// 
// 
*********************************************************************/ 
?>
<style>
	/** 달력 팝업 **/
	.calendar_pop_wrap {position:relative; background-color:#FFF;}
	.calendar_pop_wrap .calendar_con {position:absolute; top:0px; left:0px;width:247px; padding:10px; border:1px solid #b8b8b8; background-color:#FFF;}
	.calendar_pop_wrap .calendar_con .month_select { text-align:center; background-color:#FFF; padding-bottom:10px;}
	.calendar_pop_wrap .calendar_con .month_select a img { display:inline ; }
	
	.calendar_pop_wrap .calendar_con .day {clear:both;border-left:1px solid #e4e4e4;}
	.calendar_pop_wrap .calendar_con .day th {background:url('../admin/img/common/calendar_top_bg.gif') repeat-x; width:34px; font-size:11px; border-top:1px solid #9d9d9d;border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4; padding:6px 0px 4px;}
	.calendar_pop_wrap .calendar_con .day th.sun {color:#ff0012;}
	.calendar_pop_wrap .calendar_con .day td {border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4; background-color:#FFF; width:34px;  font-size:11px; text-align:center; font-family:tahoma;}
	.calendar_pop_wrap .calendar_con .day td a {color:#35353f; display:block; padding:2px 0px;}
	.calendar_pop_wrap .calendar_con .day td a:hover {font-weight:bold; color:#ff6000; text-decoration:none;}
	.calendar_pop_wrap .calendar_con .day td.pre_month a {color:#fff; display:block; padding:3px 0px;}
	.calendar_pop_wrap .calendar_con .day td.pre_month a:hover {text-decoration:none; color:#fff;}
	.calendar_pop_wrap .calendar_con .day td.today {background-color:#52a3e7; }
	.calendar_pop_wrap .calendar_con .day td.today a {color:#fff;}
	.calendar_pop_wrap .calendar_con .close_btn {padding-top:10px; padding-left:100px;}
</style>
		

	<div class="containerBody sub-page">

	<div class="breadcrumb">
		<ul>
			<li><a href="/">HOME</a></li>
			<li><a href="mypage.php">MY PAGE</a></li>
			<li class="on"><a>개인정보 수정</a></li>
		</ul>
	</div>

	<!-- LNB -->
	<div class="left_lnb">
		<? include ($Dir.FrontDir."mypage_TEM01_left.php");?> 
		<!---->
	</div><!-- //LNB -->

	 <div class="right_section mypage-content-wrap">
		<h4 class="mypage-title align-top">개인정보</h4>
			<!-- 테이블 Wrap -->
		<div class="user-reg">
				<table class="th-left-util" summary="">
					<colgroup>
						<col style="width:121px" />
						<col style="width:auto" />
					</colgroup>
					<tbody>
						<tr>
							<th scope="row">아이디 </th>
							<td>
								<?=$id?>				
							</td>
						</tr>
						<tr>
							<th scope="row">*기존 비밀번호</th>
							<td>
								<input type="password" id="user-pass" name="oldpasswd" class="input-def w250" value = '' maxlength="16" title="기존 비밀번호를 입력하세요."  onfocusout="ValidFormPassword('')">
								<span class="join-att-ment color"></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="user-pass1">비밀번호</label></th>
							<td>
								<input type="password" id="user-pass1" class="input-def w250" name="passwd1" value = '' maxlength="16" title="비밀번호를 입력하세요."   onfocusout="ValidFormPassword1('')">
								<span class="join-att-ment"></span>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="identify-pass">비밀번호 확인</label></th>
							<td>
								<input type="password" id="identify-pass" class="input-def w250" name="passwd2" value = '' maxlength="16" title="비밀번호를 한 번 더 입력하세요."   onfocusout="ValidFormPasswordRe('')">
								<span class="join-att-ment color"></span>
							</td>
						</tr>
						<tr>
							<th scope="row">*이름</th>
							<td>
								<?=$name?>								
							</td>
						</tr>
						<tr class="line-duble">
							<th scope="row" valign="top"><label for="post-code">주소</label></th>
							<td>
								<ul>
									<li>
										<input type="text" class="input-def w90" name='home_zonecode' id='home_zonecode' size=5 value="<?=$home_post?>" label="우편번호" title="" readonly >
										<input type="hidden" name='home_post1' id='home_post1' value="<?=$home_post1?>" title="우편번호 앞 입력자리">
										<input type="hidden" name='home_post2' id='home_post2' value="<?=$home_post2?>" title="우편번호 뒤 입력자리">
										<a href="javascript:openDaumPostcode();" class="btn-dib-line">우편번호</a>
									</li>
									<li class="mt_5">
										<input type="text" name='home_addr1' id='home_addr1' value="<?=$home_addr1?>" size=30 required label="주소" class="input-def w380" title="주소를 입력해 주세요." readonlY >
										<input type="text" name='home_addr2' id='home_addr2' value="<?=$home_addr2?>" size=30 required label="세부주소" class="input-def w250" title="주소를 입력해 주세요."   onfocusout="ValidFormAddr('')">
										<span class="join-att-ment"></span>
									</li>
								</ul>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="user-email">이메일</label></th>
							<td>
								<div class="email-cover">
									<input type="text" id="user-email" name="email" value="<?=$email[0]?>@<?=$email[1]?>" class="input-def w250" title="이메일을 입력해 주세요." onkeyup="domail_list_up(this.value)"  onfocusout="ValidFormEmail('')" autocomplete="off">
									<ul class="domain-list">
										<li><a href="javascript:;" onClick="javascript:email_in(this)" alt='@naver.com'><?=$email[0]?>@naver.com</a></li>
										<li><a href="javascript:;" onClick="javascript:email_in(this)" alt='@gmail.com'><?=$email[0]?>@gmail.com</a></li>
										<li><a href="javascript:;" onClick="javascript:email_in(this)" alt='@nate.com'><?=$email[0]?>@nate.com</a></li>
										<li><a href="javascript:;" onClick="javascript:email_in(this)" alt='@daum.net'><?=$email[0]?>@daum.net</a></li>
										<li><a href="javascript:;" onClick="javascript:email_in(this)" alt='@yahoo.com'><?=$email[0]?>@yahoo.com</a></li>
									</ul>
								</div>
								<?
									$checked['news_mail_yn'][$news_mail_yn] = "checked";
								?>
								<label class="with-check"><input type="checkbox" class="checkbox-def"  name='news_mail_yn' id="" value="Y" <?=$checked['news_mail_yn']['Y']?>> 이메일 수신동의</label>
								<span class="join-att-ment"></span>
							</td>
						</tr>
						<tr>
							<th scope="row">휴대폰번호</th>
							<td>
								<div class="select small">
									<?
										$selected['mobile'][$mobile[0]] = ' class="hover"';
									?>
									<span class="ctrl"><span class="arrow"></span></span>
									<button type="button" class="my_value selected"><span><?=$mobile[0]?></span></button>
									<ul class="a_list">
										<li <?=$selected['mobile']['010']?>><a href="javascript:;" onClick="javascript:mobile1_change('010')">010</a></li>
										<li <?=$selected['mobile']['011']?>><a href="javascript:;" onClick="javascript:mobile1_change('011')">011</a></li>
										<li <?=$selected['mobile']['016']?>><a href="javascript:;" onClick="javascript:mobile1_change('016')">016</a></li>
										<li <?=$selected['mobile']['017']?>><a href="javascript:;" onClick="javascript:mobile1_change('017')">017</a></li>
										<li <?=$selected['mobile']['018']?>><a href="javascript:;" onClick="javascript:mobile1_change('018')">018</a></li>
										<li <?=$selected['mobile']['019']?>><a href="javascript:;" onClick="javascript:mobile1_change('019')">019</a></li>
									</ul>
									<input type=hidden name='mobile[]' id = 'mobile1' value="<?=$mobile[0]?>">
								</div>
								<span class="txt-lh">-</span>
								<input type="text" class="input-def w70" name='mobile[]' id = 'mobile2' value="<?=$mobile[1]?>" maxlength=4 option=regNum required >
								<span class="txt-lh">-</span>
								<input type="text" class="input-def w70" name='mobile[]' id = 'mobile3' value="<?=$mobile[2]?>" maxlength=4 option=regNum required title="휴대폰번호를 입력해 주세요." onfocusout="ValidFormMobile('')">
								<?
									$checked['news_sms_yn'][$news_sms_yn] = "checked";
								?>
								<label class="with-check"><input type="checkbox" class="checkbox-def"  name='news_sms_yn' id="" value="Y" <?=$checked['news_sms_yn']['Y']?>> SMS 수신동의</label>
								<span class="join-att-ment"></span>
							</td>
						</tr>
						<?php
							if($recom_ok=="Y"){
						?>
						<tr>
							<th scope="row">추천인아이디</th>
							<td>
								<input class="w200" type="text" name="rec_id" title="추천인아이디를 입력하세요." />
							</td>
						</tr>
						<?php
							}
						?>
						<tr>
							<th scope="row">생년월일</th>
							<td>
								<input type="text" class="input-def w90" name='birth' value = '<?=$birth?>' required label="생년월일" size=12 maxlength=12 title="생년월일을 입력하세요." onclick="Calendar(event)" readonly>
								<span class="join-att-ment"></span>
							</td>
						</tr>
						<tr class='hide'>
							<th scope="row">기념일</th>
							<td>
								<input type="text" class="input-def w90" name='married_date' value = '<?=$married_date?>' required label="기념일" size=12 maxlength=12 title="기념일을 입력하세요." onclick="Calendar(event)" readonly> 
								<span class="join-att-ment"></span>
							</td>
						</tr>
					</tbody>
				</table>
			</div><!-- //테이블 Wrap -->

			<!-- 버튼 -->
			<div class="btn-place">
				<a href="javascript:CheckForm('<?=$mem_type?>');" class="btn-dib-function"><span>정보수정</span></a>&nbsp;&nbsp;
				<a href="/front/mypage.php"  class="btn-dib-function line"><span>취소하기</span></a>
			</div>
			<!-- //버튼 -->
		</form>
	 </div>
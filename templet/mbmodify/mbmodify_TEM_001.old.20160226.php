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

		<form name="Form1" method="post" action="<?=$_SERVER['PHP_SELF']?>">
			<!-- 테이블 Wrap -->
			<div class="table_wrap mt_10">
				<table class="th_left" summary="">
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
								<input type="password" id="user-pass" name="oldpasswd" value = '<?=$passwd1?>' maxlength="16" title="비밀번호를 입력하세요." />
							</td>
						</tr>
						<tr>
							<th scope="row">비밀번호</th>
							<td>
								<input type="password" id="user-pass1" name="passwd1" value = '<?=$passwd1?>' maxlength="16" title="비밀번호를 입력하세요." />
								<span class="reg_ment">(영문 대소문자/숫자/특수문자 중 2가지 이상 조합, 8자~16자)</span>
							</td>
						</tr>
						<tr>
							<th scope="row">비밀번호 확인</th>
							<td>
								<input type="password" id="identify-pass" name="passwd2" value = '<?=$passwd2?>' maxlength="16" title="비밀번호를 한번더 입력하세요." />
								<span class="reg_ment">입력하신 비밀번호를 한번더 입력해주세요.</span>
							</td>
						</tr>
						<tr>
							<th scope="row">*이름</th>
							<td>
								<?=$name?>								
							</td>
						</tr>
						<tr>
							<th scope="row">*주소</th>
							<td>
								<ul>
									<li><input type="text" class="w50" name='home_zonecode' id='home_zonecode' size=5 value="<?=$home_zonecode?>" label="우편번호" title="" readonly /><input type="hidden" name='home_post1' id='home_post1' value="<?=$home_post1?>"><input type="hidden" name='home_post2' id='home_post2' value="<?=$home_post2?>"> 
									<a href="javascript:openDaumPostcode();"><img src="../img/button/btn_zipcode_find.gif" alt="" /></a>
									</li>
									<li class="mt_5"><input type="text" name='home_addr1' id='home_addr1' value="<?=$home_addr1?>" size=30 required label="주소" class="w400" title="" readonlY /><span class="reg_ment">기본주소</span></li>
									<li class="mt_5"><input type="text" name='home_addr2' id='home_addr2' value="<?=$home_addr2?>" size=30 required label="세부주소" class="w400" title="나머지 주소를 입력하세요." /><span class="reg_ment">나머지주소</span></li>
								</ul>
							</td>
						</tr>
						<tr>
							<th scope="row">*휴대폰번호</th>
							<td>
								<?
									$selected['mobile'][$mobile[0]] = 'selected';
								?>
								<select  class="w50 defult" name='mobile[]' id = 'mobile1' option=regNum required>
									<option value="010" <?=$selected['mobile']['010']?>>010</option>
									<option value="011" <?=$selected['mobile']['011']?>>011</option>
									<option value="016" <?=$selected['mobile']['016']?>>016</option>
									<option value="017" <?=$selected['mobile']['017']?>>017</option>
									<option value="018" <?=$selected['mobile']['018']?>>018</option>
									<option value="019" <?=$selected['mobile']['019']?>>019</option>
								</select> - 
								<input type="text" class="w50" name='mobile[]' id = 'mobile2' title="" value="<?=$mobile[1]?>" maxlength=4 option=regNum required /> - 
								<input type="text" class="w50" name='mobile[]' id = 'mobile3' title="" value="<?=$mobile[2]?>" maxlength=4 option=regNum required />
							</td>
						</tr>
						<tr>
							<th scope="row">*SMS 수신여부</th>
							<td>
								<ul>
									<li class="mt_5">
										<?
											$checked['news_sms_yn'][$news_sms_yn] = "checked";
										?>
										<input type="radio" name='news_sms_yn' id="" value="Y" <?=$checked['news_sms_yn']['Y']?> /> 수신함&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name='news_sms_yn' id="" <?=$checked['news_sms_yn']['N']?> /> 수신안함
									</li>
									<li class="mt_5">
										<span class="reg_ment">쇼핑몰에서 제공하는 유익한 이벤트 소식을  받으실 수 있습니다.</span>
									</li>
								</ul>
							</td>
						</tr>
						<tr>
							<th scope="row">*이메일 </th>
							<td>
								<input type="text" class="w140" name='email_id' size=30 value="<?=$email[0]?>" title="이메일을 입력하세요" required /> @ 
								<input type="text" class="w140" name='email_addr' value="<?=$email[1]?>" title="이메일을 입력하세요" />
								<select class="w140 defult" name="email_select" title="이메일 도메인을 선택하세요." onchange="email_set(this.value);">
									<option value="">직접입력</option>
									<option value="naver.com"  >naver.com</option>
									<option value="hanmail.net"  >hanmail.net</option>
									<option value="yahoo.co.kr" >yahoo.co.kr</option>
									<option value="yahoo.com"  >yahoo.com</option>
									<option value="gmail.com"  >gmail.com</option>
									<option value="korea.com"  >korea.com</option>
									<option value="nate.com"  >nate.com</option>
									<option value="paran.com"  >paran.com</option>
									<option value="hanmir.com"  >hanmir.com</option>
									<option value="hitel.net"  >hitel.net</option>
									<option value="hotmail.com"  >hotmail.com</option>
									<option value="dreamwiz.com"  >dreamwiz.com</option>
									<option value="freechal.com"  >freechal.com</option>
									<option value="chol.com"  >chol.com</option>
									<option value="empal.com"  >empal.com</option>
									<option value="lycos.co.kr"  >lycos.co.kr</option>
									<option value="hanafos.com"  >hanafos.com</option>
									<option value="netian.com"  >netian.com</option>
								</select>
								<a href="javascript:;" id="btnCheckEmail" class="btn_util">중복확인</a>
								<input type="hidden" name="email"/>
							</td>
						</tr>
						<tr>
							<th scope="row">*이메일 수신여부</th>
							<td>
								<ul>
									<li class="mt_5">
										<?
											$checked['news_mail_yn'][$news_mail_yn] = "checked";
										?>
										<input type="radio" name='news_mail_yn' id="" value="Y" <?=$checked['news_mail_yn']['Y']?> /> 수신함&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name='news_mail_yn' id="" <?=$checked['news_mail_yn']['N']?> /> 수신안함
									</li>
									<li class="mt_5">
										<span class="reg_ment">쇼핑몰에서 제공하는 유익한 이벤트 소식을  받으실 수 있습니다.</span>
									</li>
								</ul>
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
					</tbody>
				</table>
			</div><!-- //테이블 Wrap -->

			<!-- 테이블 Wrap -->
			<div class="table_wrap mt_30">
				<h3>선택 항목</h3>
				<p class="table_info"></p>
				<table class="th_left" summary="추가 정보를 입력하시면 특별한 혜택을 제공합니다.">
					<colgroup>
						<col style="width:121px" />
						<col style="width:auto" />
					</colgroup>
					<tbody>
						<tr>
							<th scope="row">생년월일</th>
							<td>
								<input type="text" class="w100" name='birth' value = '<?=$birth?>' required label="생년월일" size=12 maxlength=12 title="년도를 입력하세요." onclick="Calendar(event)" readonly/>
							</td>
						</tr>
						<tr>
							<th scope="row">기념일</th>
							<td>
								<input type="text" class="w100" name='married_date' value = "<?=$married_date?>" required label="결혼기념일" size=12 maxlength=12 OnClick="Calendar(event)" readonly /> 
							</td>
						</tr>
					</tbody>
				</table>
			</div><!-- //테이블 Wrap -->

			<!-- 버튼 -->
			<div class="btn-place">
				<a href="javascript:CheckForm('<?=$mem_type?>');" class="btn-dib-function"><span>정보수정</span></a>&nbsp;&nbsp;
				<a href="/front/member_join.php?mem_type=<?=$mem_type?>"  class="btn-dib-function line"><span>취소하기</span></a>
			</div>
			<!-- //버튼 -->
		</form>
	 </div>
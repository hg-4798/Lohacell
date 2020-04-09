
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

<!-- 메인 컨텐츠 -->
 <div class="containerBody sub_skin">
	
		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li class="on"><a>CREATE AN ACCOUNT</a></li>
			</ul>
		</div>

		<div class="agreement-wrap">
		<div class="flow">
			<ul>
				<li><span>01</span> 약관동의 및 실명인증</li>
				<li class="on"><span>02</span> 정보입력</li>
				<li><span>03</span> 가입완료</li>
			</ul>
		</div>
		<!-- 사업자 정보 테이블 Wrap -->
		<?if($mem_type == "1"){?>
		<div class="table_wrap mt_30">
			<h3>사업자 정보 입력</h3>
			<p class="th-left-util">*표시는 필수입력 사항입니다.</p>
			<table class="th_left" summary="회원가입 정보를 입력합니다.">
				<colgroup>
					<col style="width:121px" >
					<col style="width:auto" >
				</colgroup>
				<tbody>
					<tr>
						<th scope="row">*회사명</th>
						<td>
							<input type="text" name=office_name required label="회사명" >
						</td>
					</tr>												
					<tr>
						<th scope="row">*대표자명</th>
						<td>
							<input type="text" name=office_representative required label="대표자명" >
						</td>
					</tr>												
					<tr>
						<th scope="row">*사업자번호</th>
						<td>
							<input type="text" id=office_no1 maxlength="3" size="4" required label="사업자번호" > - 
							<input type="text" id=office_no2 maxlength="2" size="3" required label="사업자번호" > - 
							<input type="text" id=office_no3 maxlength="5" size="6" required label="사업자번호" >
							<input type="hidden" name=office_no id=office_no >
						</td>
					</tr>
					<tr>
						<th scope="row">*업종/업태</th>
						<td>
							<input type="text" id=office_form1 name="office_form1" maxlength="50"  required label="업종" >
							&nbsp;/&nbsp;
							<input type="text" id=office_form2 name="office_form2" maxlength="50" required label="업태" >
						</td>
					</tr>
					<tr>
						<th scope="row"><div class="ml_5">*회사전화</div></th>
						<td>
							<select class="w50 defult" name='office_tel[]' id = 'office_tel1' required>
								<option value="02">02</option>
								<option value="031">031</option>
								<option value="032">032</option>
								<option value="041">041</option>
								<option value="042">042</option>
								<option value="043">043</option>
								<option value="044">044</option>
								<option value="051">051</option>
								<option value="052">052</option>
								<option value="053">053</option>
								<option value="054">054</option>
								<option value="055">055</option>
								<option value="061">061</option>
								<option value="062">062</option>
								<option value="063">063</option>
								<option value="064">064</option>
							</select> - 
							<input type="text" class="w50" name='office_tel[]' id = 'office_tel2' title="" maxlength=4 required > - <input type="text" class="w50" name='office_tel[]' id = 'office_tel3' title="" maxlength=4 required >
						</td>
					</tr>
					<tr>
						<th scope="row">*회사주소</th>
						<td>
							<ul>
								<li><input type="text" class="w50" name='office_zonecode' id='office_zonecode' size=5 value="<?=$office_zonecode?>" label="우편번호" title="" readonly ><input type="hidden" name='office_post1' id='office_post1' value="<?=$office_post1?>"><input type="hidden" name='office_post2' id='office_post2' value="<?=$office_post2?>"> 
								<a href="javascript:openDaumPostcode_office();"><img src="../img/button/btn_zipcode_find.gif" alt="" ></a>
								</li>
								<li class="mt_5"><input type="text" name='office_addr1' id='office_addr1' value="<?=$office_addr1?>" size=30 required label="주소" class="w400" title="" readonlY ></li>
								<li class="mt_5"><input type="text" name='office_addr2' id='office_addr2' value="<?=$office_addr2?>" size=30 required label="세부주소" class="w400" title="나머지 주소를 입력하세요." ></li>
							</ul>
						</td>
					</tr>
					
				</tbody>
			</table>
		</div><!-- //사업자 정보 테이블 Wrap -->
		<?}?>

		<!-- 테이블 Wrap -->
		<div class="user-reg">
			<p class="inner-title">정보입력</p>
			<table class="th-left-util" summary="회원가입 정보를 입력합니다.">
				<colgroup>
					<col style="width:121px" >
					<col style="width:auto" >
				</colgroup>
				<tbody>
					<tr>
						<th scope="row"><label for="user-id">아이디</label></th>
						<td>
							<input type="text" id="user-id" class="input-def w250" name="id" value = '<?=$id?>' maxlength="16" title='아이디를 입력해주세요.'  onfocusout="ValidFormId()">
							<!-- 인풋박스에 포인트를 줘야 하는경우 alert-line 클래스 추가 -->
							<span class="join-att-ment"></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="user-pwd1">비밀번호</label></th>
						<td>
							<input type="password" id="user-pwd1" class="input-def w380" name="passwd1" value = '<?=$passwd1?>' maxlength="20" title="비밀번호를 입력하세요."   onfocusout="ValidFormPassword()">
							<span class="join-att-ment"></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="user-pwd2">비밀번호 확인</label></th>
						<td>
							<input type="password" id="user-pwd2" class="input-def w380" name="passwd2" value = '<?=$passwd2?>' maxlength="20" title="비밀번호를 한 번 더 입력하세요."   onfocusout="ValidFormPasswordRe()">
							<span class="join-att-ment color"></span>
						</td>
					</tr>
					<tr>
					<?if($mem_type == "1"){
						$nametitle = "영업담당자";
					}else{
						$nametitle = "이름";
					}?>
						<th scope="row"><label for="user-name"><?=$nametitle?></label></th>
						<td>
						<?php
							if($name){
						?>
								<?=$name?>
								<input type="hidden" id="user-name" class="input-def w240" name="name" value="<?=$name?>" maxlength="30" title="이름를 입력하세요."   onfocusout="ValidFormName()">
						<?php
							}else{
						?>
								<input type="text" id="user-name" class="input-def w240" name="name" value="<?=$name?>" maxlength="30" title="이름를 입력하세요."   onfocusout="ValidFormName()">
						<?php	
							}
						?>
							<span class="join-att-ment"></span>
						</td>
					</tr>
				<?if($mem_type != "1"){?>
					<tr class="line-duble">
						<th scope="row" valign="top"><label for="post-code">주소</label></th>
						<td>
							<ul>
								<li>
									<input type="text" class="input-def w90" name='home_zonecode' id='home_zonecode' size=5 value="<?=$home_zonecode?>" label="우편번호" title="" readonly >
									<input type="hidden" name='home_post1' id='home_post1' value="<?=$home_post1?>" title="우편번호 앞 입력자리">
									<input type="hidden" name='home_post2' id='home_post2' value="<?=$home_post2?>" title="우편번호 뒤 입력자리">
									<a href="javascript:openDaumPostcode();" class="btn-dib-line">우편번호</a>
								</li>
								<li class="mt_5">
									<input type="text" name='home_addr1' id='home_addr1' value="<?=$home_addr1?>" size=30 required label="주소" class="input-def w380" title="주소를 입력해 주세요." readonlY >
									<input type="text" name='home_addr2' id='home_addr2' value="<?=$home_addr2?>" size=30 required label="세부주소" class="input-def w250" title="주소를 입력해 주세요."   onfocusout="ValidFormAddr()">
									<span class="join-att-ment"></span>
								</li>
							</ul>
						</td>
					</tr>
				<?}?>
					<tr>
						<th scope="row"><label for="user-email">이메일</label></th>
						<td>
							<div class="email-cover">
								<input type="text" id="user-email" name="email" class="input-def w250" title="이메일을 입력해 주세요." onkeyup="domail_list_up(this.value)" onfocusout="ValidFormEmail()" autocomplete="off">
								<ul class="domain-list">
									<li><a href="javascript:;" onClick="email_in(this)" alt='@naver.com'>@naver.com</a></li>
									<li><a href="javascript:;" onClick="email_in(this)" alt='@gmail.com'>@gmail.com</a></li>
									<li><a href="javascript:;" onClick="email_in(this)" alt='@nate.com'>@nate.com</a></li>
									<li><a href="javascript:;" onClick="email_in(this)" alt='@daum.net'>@daum.net</a></li>
									<li><a href="javascript:;" onClick="email_in(this)" alt='@yahoo.com'>@yahoo.com</a></li>
								</ul>
							</div>
							<label class="with-check"><input type="checkbox" class="checkbox-def"  name='news_mail_yn' id="" value="Y"> 이메일 수신동의</label>
							<span class="join-att-ment"></span>
						</td>
					</tr>
					<tr>
						<th scope="row">휴대폰번호</th>
						<td>
							<!-- <select  class="w50 defult" name='mobile[]' id = 'mobile1' option=regNum required>
								<option value="010">010</option>
								<option value="011">011</option>
								<option value="016">016</option>
								<option value="017">017</option>
								<option value="018">018</option>
								<option value="019">019</option>
							</select> -->
							<div class="select small">
								<span class="ctrl"><span class="arrow"></span></span>
								<button type="button" class="my_value selected"><span>010</span></button>
								<ul class="a_list">
									<li class="hover"><a href="javascript:;" onClick="javascript:mobile1_change('010')">010</a></li>
									<li><a href="javascript:;" onClick="javascript:mobile1_change('011')">011</a></li>
									<li><a href="javascript:;" onClick="javascript:mobile1_change('016')">016</a></li>
									<li><a href="javascript:;" onClick="javascript:mobile1_change('017')">017</a></li>
									<li><a href="javascript:;" onClick="javascript:mobile1_change('018')">018</a></li>
									<li><a href="javascript:;" onClick="javascript:mobile1_change('019')">019</a></li>
								</ul>
								<input type=hidden name='mobile[]' id = 'mobile1' value="010">
							</div>
							<span class="txt-lh">-</span>
							<input type="text" class="input-def w70" name='mobile[]' id = 'mobile2' maxlength=4 option=regNum required>
							<span class="txt-lh">-</span>
							<input type="text" class="input-def w70" name='mobile[]' id = 'mobile3' maxlength=4 option=regNum required title="휴대폰번호를 입력해 주세요."   onfocusout="ValidFormMobile()">
							<label class="with-check"><input type="checkbox" class="checkbox-def"  name='news_sms_yn' id="" value="Y"> SMS 수신동의</label>
							<span class="join-att-ment"></span>
						</td>
					</tr>
					<?if($mem_type == "1"){?>
					<tr>
						<th scope="row">회사 홈페이지</th>
						<td>
							<input type="text" name='homepage' id='homepage' value="<?=$homepage?>" size=30  class="w400" title="" >
						</td>
					</tr>
					<?}?>
					<?php
						if($recom_ok=="Y"){
					?>
					<tr>
						<th scope="row">추천인아이디</th>
						<td>
							<input class="w200" type="text" name="rec_id" title="추천인아이디를 입력하세요." >
						</td>
					</tr>
					<?php
						}
					?>
					<tr>
						<th scope="row">생년월일</th>
						<td>
							<?php if($strDateBirth){?>
								<input type="text" class="input-def w90" name='birth' value = '<?=$strDateBirth?>' required label="생년월일" size=12 maxlength=12 title="생년월일을 입력하세요." onclick="Calendar(event)" readonly>
							<?php }else{?>
								<input type="text" class="input-def w90" name='birth' value = '<?=$strDateBirth?>' required label="생년월일" size=12 maxlength=12 title="생년월일을 입력하세요." onclick="Calendar(event)" readonly>
							<?php }?>
							<span class="join-att-ment"></span>
						</td>
					</tr>
					<tr class='hide'>
						<th scope="row">기념일</th>
						<td>
							<input type="text" class="input-def w70" name='married_date' required label="기념일" size=12 maxlength=12 title="기념일을 입력하세요." onclick="Calendar(event)" readonly> 
							<span class="join-att-ment"></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div><!-- //테이블 Wrap -->

		<!-- 가입버튼 -->
		<div class="btn-place">
			<a href="javascript:CheckForm('<?=$mem_type?>');" class="btn-dib-function"><span>SIGN-UP</span></a>
		</div>
		<!-- //가입버튼 -->

		</div><!-- //.agreement-wrap -->
 </div>

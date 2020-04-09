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

		<div class="flow"><img src="../static/img/common/join_flow02.jpg" alt="02.정보입력"></div>

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
							<input type="text" id="user-id" class="input-def w250 alert-line" name="id" value = '<?=$id?>' maxlength="16" title="아이디를 입력하세요."  >
							<!-- 인풋박스에 포인트를 줘야 하는경우 alert-line 클래스 추가 -->
							<span class="join-att-ment">5~16자 이내 영문과 숫자만 사용 가능합니다.</span>
							<span class="join-att-ment color hide">"아이디"를(을) 입력해주세요.</span><!-- 아이디를 입력하지 않고 비밀번호 입력으로 넘어간경우 -->
							<span class="join-att-ment color hide">이미 사용중입니다.</span><!-- 아이디가 중복인경우 -->
							<span class="join-att-ment hide">사용 가능합니다.</span><!-- 아이디가 사용가능 한 경우 -->
							<!-- <a href="javascript:;" id="btnCheckId" class="btn_util">아이디 체크</a> <span class="reg_ment">(영문 소문자/숫자, 4~16자)</span> -->
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="user-pwd1">비밀번호</label></th>
						<td>
							<input type="password" id="user-pwd1" class="input-def w380" name="passwd1" value = '<?=$passwd1?>' maxlength="16" title="비밀번호를 입력하세요." >
							<span class="join-att-ment">8~20자 이내 영문, 숫자, 특수문자(!@#$%^&amp;*) 3가지 조합으로 이루어져야 합니다.</span>
							<!-- <span class="reg_ment">(영문 대소문자/숫자/특수문자 중 2가지 이상 조합, 8자~16자)</span> -->
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="user-pwd2">비밀번호 확인</label></th>
						<td>
							<input type="password" id="user-pwd2" class="input-def w380" value = '<?=$passwd2?>' maxlength="16" title="비밀번호를 한번더 입력하세요." >
							<span class="join-att-ment color hide">비밀번호를 다시 확인해 주세요.</span><!-- 재입력 틀린경우 -->
							<span class="join-att-ment hide">비밀번호가 일치 합니다.</span><!-- 재입력 맞는경우 -->
							<!-- <span class="reg_ment">입력하신 비밀번호를 한번더 입력해주세요.</span> -->
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
								<input type="hidden" id="user-name" class="input-def w240" name="name" value="<?=$name?>" maxlength="30" title="이름를 입력하세요." >
						<?php
							}else{
						?>
								<input type="text" id="user-name" class="input-def w240" name="name" value="<?=$name?>" maxlength="30" title="이름를 입력하세요." >
						<?php	
							}
						?>
						</td>
					</tr>
				<?if($mem_type != "1"){?>
					<tr class="line-duble">
						<th scope="row" valign="top"><label for="post-code">주소</label></th>
						<td>
							<ul>
								<li>
									<!-- <input type="text" class="w50" name='home_zonecode' id='home_zonecode' size=5 value="<?=$home_zonecode?>" label="우편번호" title="" readonly >
									<input type="hidden" name='home_post1' id='home_post1' value="<?=$home_post1?>">
									<input type="hidden" name='home_post2' id='home_post2' value="<?=$home_post2?>">  -->
									<input type="text" id="post-code" class="input-def w70" title="우편번호 앞 입력자리">
									<span class="txt-lh">-</span>
									<input type="text" class="input-def w70" title="우편번호 뒤 입력자리">
									<a href="javascript:openDaumPostcode();" class="btn-dib-line">우편번호</a>
								</li>
								<li class="mt_5">
									<input type="text" name='home_addr1' id='home_addr1' value="<?=$home_addr1?>" size=30 required label="주소" class="input-def w380" title="기본 주소 입력자리" readonlY >
									<input type="text" name='home_addr2' id='home_addr2' value="<?=$home_addr2?>" size=30 required label="세부주소" class="input-def w250" title="상세 주소 입력자리" >
								</li>
							</ul>
						</td>
					</tr>
				<?}?>
					<tr class="hide">
						<th scope="row">SMS 수신여부</th>
						<td>
							<ul>
								<li class="mt_5">
									<input type="radio" name='news_sms_yn' id="" value="Y" checked="checked" > 수신함&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name='news_sms_yn' id="" > 수신안함
								</li>
								<li class="mt_5">
									<span class="reg_ment">쇼핑몰에서 제공하는 유익한 이벤트 소식을  받으실 수 있습니다.</span>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="user-email">이메일</label></th>
						<td>
							<div class="email-cover">
								<input type="text" id="user-email" class="input-def w240" title="이메일 입력자리">
								<ul class="domain-list">
									<li><a href="#">@naver.com</a></li>
									<li><a href="#">@gmail.com</a></li>
									<li><a href="#">@nate.com</a></li>
									<li><a href="#">@daum.net</a></li>
									<li><a href="#">@yahoo.com</a></li>
								</ul>
							</div>
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
								<button type="button" class="my_value">010</button>
								<ul class="a_list">
									<li><a href="#1">011</a></li>
									<li><a href="#2">016</a></li>
								</ul>
							</div>
							<span class="txt-lh">-</span>
							<input type="text" class="input-def w70" name='mobile[]' id = 'mobile2' title="" maxlength=4 option=regNum required >
							<span class="txt-lh">-</span>
							<input type="text" class="input-def w70" name='mobile[]' id = 'mobile3' title="" maxlength=4 option=regNum required >
						</td>
					</tr>
					<tr class="hide">
						<th scope="row">이메일</th>
						<td>
							<input type="text" class="w140" name='email_id' value="" size=30 title="이메일을 입력하세요" required > @ <input type="text" class="w140" name='email_addr' title="이메일을 입력하세요" >
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
								<option value="lycos"  >lycos.co.kr</option>
								<option value="hanafos.com"  >hanafos.com</option>
								<option value="netian.com"  >netian.com</option>
							</select>
							<a href="javascript:;" id="btnCheckEmail" class="btn_util">중복확인</a>
							<input type="hidden" name="email" >
							<input type="hidden" id="email_check" value="0" >
						</td>
					</tr>
					<tr class="hide">
						<th scope="row">이메일 수신여부</th>
						<td>
							<ul>
								<li class="mt_5">
									<input type="radio" name='news_mail_yn' id="" value="Y" checked="checked" > 수신함&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name='news_mail_yn' id="" > 수신안함
								</li>
								<li class="mt_5">
									<span class="reg_ment">쇼핑몰에서 제공하는 유익한 이벤트 소식을  받으실 수 있습니다.</span>
								</li>
							</ul>
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
				</tbody>
			</table>
		</div><!-- //테이블 Wrap -->

		<!-- 테이블 Wrap -->
		<div class="table_wrap hide">
			<h3>선택 항목</h3>
			<p class="table_info">*추가 정보를 입력하시면 특별한 혜택을 제공합니다.</p>
			<table class="th_left" summary="추가 정보를 입력하시면 특별한 혜택을 제공합니다.">
				<colgroup>
					<col style="width:121px" >
					<col style="width:auto" >
				</colgroup>
				<tbody>
					<tr>
						<th scope="row">생년월일</th>
						<td>
							<?php if($strDateBirth){?>
								<input type="text" class="w100" name='birth' value = '<?=$strDateBirth?>' required label="생년월일" size=12 maxlength=12 title="년도를 입력하세요."  readonly>
							<?php }else{?>
								<input type="text" class="w100" name='birth' value = '<?=$strDateBirth?>' required label="생년월일" size=12 maxlength=12 title="년도를 입력하세요." onclick="Calendar(event)" readonly>
							<?php }?>
						</td>
					</tr>
					<tr>
						<th scope="row">기념일</th>
						<td>
							<input type="text" class="w100" name='married_date' required label="결혼기념일" size=12 maxlength=12 OnClick="Calendar(event)" readonly > 
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

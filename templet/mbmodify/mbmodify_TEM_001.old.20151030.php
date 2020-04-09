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
		

	<div class="containerBody sub_skin">

	<!-- LNB -->
	<div class="left_lnb">
		<? include ($Dir.FrontDir."mypage_TEM01_left.php");?> 
		<!---->
	</div><!-- //LNB -->

	 <div class="right_section mb_80">
		<h3 class="title mb_20">
			회원정보 수정/관리
			<p class="line_map"><a>홈</a> &gt; <a>정보관리</a>  &gt;  <a class="on">회원정보 수정/관리</a></p>
		</h3>

		<form name="Form1" method="post" action="<?=$_SERVER['PHP_SELF']?>">
			<!-- 테이블 Wrap -->
			<div class="table_wrap mt_10">
				<h3></h3>
				<div class="right_area">*표시는 필수입력 사항입니다.</div>
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
								<input type="password" id="user-pass" name="oldpasswd" value = '<?=$passwd1?>' maxlength="15" title="비밀번호를 입력하세요." />
							</td>
						</tr>
						<tr>
							<th scope="row">비밀번호</th>
							<td>
								<input type="password" id="user-pass1" name="passwd1" value = '<?=$passwd1?>' maxlength="15" title="비밀번호를 입력하세요." />
								<span class="reg_ment">6~20자 미만의 영문/숫자 (특수문자 사용불가)</span>
							</td>
						</tr>
						<tr>
							<th scope="row">비밀번호 확인</th>
							<td>
								<input type="password" id="identify-pass" name="passwd2" value = '<?=$passwd2?>' maxlength="15" title="비밀번호를 한번더 입력하세요." />
								<span class="reg_ment">입력하신 비밀번호를 한번더 입력해주세요.</span>
							</td>
						</tr>
						<tr>
							<th scope="row">이름</th>
							<td>
								<?=$name?>								
							</td>
						</tr>
						<tr>
							<th scope="row">성별</th>
							<td>
								<input type="radio" name=gender required label="성별" value="1" <?=$checked[gender]['1']?> /> 남자
								<input type="radio" name=gender required label="성별" value="2" <?=$checked[gender]['2']?> /> 여자
							</td>
						</tr>
						<tr>
							<th scope="row">생년월일</th>
							<td>
								<input type="text" class="w100" name='birth' value = '<?=$birth?>' required label="생년월일" size=12 maxlength=12 title="년도를 입력하세요." onclick="Calendar(event)" readonly/>
							</td>
						</tr>
						<tr>
							<th scope="row">주소</th>
							<td>
								<ul>
									<li><input type="text" class="w50" name='home_post1' id='home_post1' size=3 value="<?=$home_post1?>" label="우편번호" title="" readonly /> - <input type="text" class="w50" name='home_post2' id='home_post2' size=3 value="<?=$home_post2?>" label="우편번호" title="" readonly/> 
									<!--<a href="javascript:f_addr_search('form1','home_post','home_addr',2);"><img src="../img/button/btn_zipcode_find.gif" alt="" /></a>-->
									<a href="javascript:openDaumPostcode();" class="btn_util">우편번호 검색</a>
									</li>
									<li class="mt_5"><input type="text" name='home_addr1' id='home_addr1' value="<?=$home_addr1?>" size=30 required label="주소" class="w400" title="" readonlY /></li>
									<li class="mt_5"><input type="text" name='home_addr2' id='home_addr2' value="<?=$home_addr2?>" size=30 required label="세부주소" class="w400" title="나머지 주소를 입력하세요." /></li>
								</ul>
							</td>
						</tr>
						<tr>
							<th scope="row">전화번호</th>
							<td>
								<?
									$selected['tel'][$home_tel[0]] = 'selected';
									$selected['mobile'][$mobile[0]] = 'selected';
								?>
								<select class="w50 defult" name='home_tel[]' id = 'home_tel1' required>
									<option value="02" <?=$selected['tel']['02']?>>02</option>
									<option value="031" <?=$selected['tel']['031']?>>031</option>
									<option value="032" <?=$selected['tel']['032']?>>032</option>
									<option value="041" <?=$selected['tel']['041']?>>041</option>
									<option value="042" <?=$selected['tel']['042']?>>042</option>
									<option value="043" <?=$selected['tel']['043']?>>043</option>
									<option value="044" <?=$selected['tel']['044']?>>044</option>
									<option value="051" <?=$selected['tel']['051']?>>051</option>
									<option value="052" <?=$selected['tel']['052']?>>052</option>
									<option value="053" <?=$selected['tel']['053']?>>053</option>
									<option value="054" <?=$selected['tel']['054']?>>054</option>
									<option value="055" <?=$selected['tel']['055']?>>055</option>
									<option value="061" <?=$selected['tel']['061']?>>061</option>
									<option value="062" <?=$selected['tel']['062']?>>062</option>
									<option value="063" <?=$selected['tel']['063']?>>063</option>
									<option value="064" <?=$selected['tel']['064']?>>064</option>
								</select> - 
								<input type="text" class="w50" name='home_tel[]' id = 'home_tel2' title="" maxlength=4 value="<?=$home_tel[1]?>" required /> - 
								<input type="text" class="w50" name='home_tel[]' id = 'home_tel3' title="" maxlength=4 value="<?=$home_tel[2]?>" required />
							</td>
						</tr>
						<tr>
							<th scope="row">휴대폰번호</th>
							<td>
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
							<th scope="row">이메일 </th>
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
								<input type="hidden" name="email" />
							</td>
						</tr>
						<tr>
							<th scope="row">이메일/SMS <br />수신여부</th>
							<td>
								<ul>
									<li>ORYANY에서 제공되는 서비스에 대한 수신동의 여부를 확인해 주세요.</li>
									<li class="mt_5">
										<?
											$checked['news_mail_yn'][$news_mail_yn] = "checked";
											$checked['news_sms_yn'][$news_sms_yn] = "checked";
										?>
										<b>· 이메일</b>  
										<input type="radio" name='news_mail_yn' id="" value="Y" <?=$checked['news_mail_yn']['Y']?>/>수신동의 
										<input type="radio" name='news_mail_yn' id=""  <?=$checked['news_mail_yn']['N']?>/>수신하지 않음 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<b>· SMS</b>  
										<input type="radio" name='news_sms_yn' id="" value="Y"  <?=$checked['news_sms_yn']['Y']?>/>수신동의 
										<input type="radio" name='news_sms_yn' id=""  <?=$checked['news_sms_yn']['N']?>/>수신하지 않음
									</li>
									<li class="mt_10">
										<span class="reg_ment mt_5"><b>※ 이메일/SMS 수신동의 하시면</b></span><br />
										<span class="reg_ment mt_5">
											- 상품 할인혜택, 쿠폰 및 이벤트, 패밀리 세일 등의 정보를 받아 보실 수 있습니다. <br />
											- 회원가입/주문/배송/문의 관련 등의 메일은 수신동의와 상관없이 모든 회원에게 발송됩니다.
										</span>
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
				<p class="table_info">*추가 정보를 입력하시면 특별한 혜택을 제공합니다.</p>
				<table class="th_left" summary="추가 정보를 입력하시면 특별한 혜택을 제공합니다.">
					<colgroup>
						<col style="width:121px" />
						<col style="width:auto" />
					</colgroup>
					<tbody>
						<tr>
							<th scope="row">결혼여부</th>
							<td>
								<input type="radio" name='married_yn' value="N" <?=$checked[married_yn]['N']?> label="결혼여부" /> 미혼
								<input type="radio" name='married_yn' value="Y" <?=$checked[married_yn]['Y']?> label="결혼여부" /> 기혼
							</td>
						</tr>
						<tr>
							<th scope="row">결혼 기념일</th>
							<td>
								<input type="text" class="w100" name='married_date' value = "<?=$married_date?>" required label="결혼기념일" size=12 maxlength=12 OnClick="Calendar(event)" readonly /> 
							</td>
						</tr>
						<tr>
							<th scope="row">배우자 생일</th>
							<td>
								<input type="text" class="w100" name='partner_date' value = "<?=$partner_date?>" required label="배우자생일" size=12 maxlength=12 OnClick="Calendar(event)" readonly /> 
							</td>
						</tr>
					</tbody>
				</table>
			</div><!-- //테이블 Wrap -->

			<!-- 버튼 -->
			<div class="ta_c mt_40">
				<a href="javascript:CheckForm('<?=$mem_type?>');" class="btn_D on">정보수정</a>&nbsp;&nbsp;
				<a href="/front/member_join.php?mem_type=<?=$mem_type?>"  class="btn_D">취소하기</a>
			</div>
			<!-- //버튼 -->
		</form>
	 </div>

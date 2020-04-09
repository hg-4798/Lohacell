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
<!-- start container -->
<div id="container">
	<!-- start contents -->
	<div class="contents">
		<div class="title">
			<h2><img src="/image/join/join_title.gif" alt="회원가입" /></h2>
			<div class="path">
				<ul>
					<li class="home">홈&nbsp;&gt;&nbsp;</li>
					<li>회원정보입력</li>
				</ul>
			</div>
		</div>

		<div class="joinstep">
			<img src="/image/join/join_step2.gif" />
		</div>
        
		<div id="body">
			<form name="createUserForm" method="post" action="">
            <div class="write_form">
                <p class="explain2">(<font class="check"> * </font> ) 표시는 필수 입력 항목입니다. 반드시 입력해주시기 바랍니다.</p>
                <div class="basic_info">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="left_td">이름<font color="#F02800">＊</font></td>
							<td><input id="user-name" class="input_text" name="name" value="<?=$name?>" maxlength="30" title="이름" type="text"></td>
						</tr>
						<tr>
							<td class="left_td">아이디<font color="#F02800">＊</font></td>
							<td>
								<dl>
									<dd><input id="user-id" name="id" value = '<?=$id?>' maxlength="20" title="아이디" type="text" class="input_text"></dd>
									<dd><a href="javascript:;" id="btnCheckId"><img src="/image/join/btn_duplication_check.gif" alt="중복체크"> </a></dd>
									<dd>영문, 숫자 사용 3~20자</dd>
								</dl>
							</td>
						</tr>
						<tr>
							<td class="left_td">비밀번호<font color="#F02800">＊</font></td>
							<td>
								<dl>
									<dd><input id="user-pass" name="passwd1" value = '<?=$passwd1?>' maxlength="15" title="비밀번호" type="password" class="input_text"></dd>
									<dd>영문, 숫자, 특수문자 조합 10~12자</dd>
								</dl>
							</td>
						</tr>
						<tr>
							<td class="left_td">비밀번호 확인<font color="#F02800">＊</font></td>
							<td><input id="identify-pass" name="passwd2" value = '<?=$passwd2?>' maxlength="15" title="비밀번호 확인" type="password" class="input_text"></td>
						</tr>
							<tr>
							<td class="left_td">닉네임</td>
							<td>
								<dl>
									<dd><input id="user-nickname" name="nickname" maxlength="15" title="닉네임" class="input_text"></dd>
									<dd><a href="javascript:;" id="btnChecknickname"><img src="/image/join/btn_duplication_check.gif" alt="중복체크"> </a></dd>
								</dl>
							</td>
						</tr>
						<tr>
							<td class="left_td">성별<font color="#F02800">＊</font></td>
							<td> 
								<dd><input type=radio name=gender required label="성별" value="1" <?=$checked[gender]['1']?> class="input_radio"> 남자</dd>
								<dd><input type=radio name=gender required label="성별" value="2" <?=$checked[gender]['2']?> class="input_radio"> 여자</dd>
							</td>
						</tr>
						<tr>
							<td class="left_td">생년월일<font color="#F02800">＊</font></td>
							<td>
								<dl>
									<dd>
										<?if($strDateBirth){?>
											<input type=text name='birth' value = '<?=$strDateBirth?>' required label="생년월일" size=12 maxlength=12  class="input_text_s" readonly>
										<?}else{?>
											<input type=text name='birth' value = '<?=$strDateBirth?>' required label="생년월일" size=12 maxlength=12  class="input_text_s" OnClick="Calendar(event)" readonly>
										<?}?>
									</dd>
									<!--dd>
										<input type=radio name=calendar value="s" checked class="input_radio"> 양력
										<input type=radio name=calendar value="l"  class="input_radio"> 음력
									</dd-->
								</dl>
							</td>
						</tr>
					</table>
				</div><!-- end basic_info -->
				<div class="space30"></div>
				<div class="basic_info">
							
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="left_td">이메일<font color="#F02800">＊</font></td>
							<td>
								<dl>
									<dd><input type=text name='email' value="<?=$email?>" size=30 required option=regEmail label="이메일"  class="input_text"></dd>
									<dd><a href="javascript:;" id="btnCheckEmail"><img src="/image/join/btn_duplication_check.gif" alt="중복체크"> </a></dd>
								</dl>
								<dl>
									<dd>주문/배송관련, 쇼핑정보 안내 메일을 수신하시겠습니까?</dd>
									<dd><input type=checkbox name='news_mail_yn' id="idx_news_mail_yn" value="Y" <?if($news_mail_yn=="Y")echo"checked";?>></dd>
									<dd>정보메일수신</dd>
								</dl>
								<dl>
									<dd>이메일 수신거부와 상관없이 구매 관련 메세지, 에코팩토리의 주요정책 관련 메시지 등은 발송되며,<br>메일 수신 회원에게는 이메일로 특가상품등 다양한 이벤트 정보를 드립니다.</dd>
								</dl>
							</td>
						</tr>	
						<tr>
							<td class="left_td">주소<font color="#F02800">＊</font></td>
							<td>
								<dl>
									<dd>
										<input type=text name='home_post1' size=3 value="<?=$home_post1?>" label="우편번호"  class="input_text_s" readonly> - 
										<input type=text name='home_post2' size=3 value="<?=$home_post2?>" label="우편번호"  class="input_text_s" readonly>
									</dd>
									<dd><a href="javascript:f_addr_search('form1','home_post','home_addr1',2);"><img src="/image/join/btn_zipcode.gif" border=0 align=absmiddle></a></dd>
								</dl>
								<dl>
									<dd><input type=text name='home_addr1' value="<?=$home_addr1?>" readonlY size=30 required label="주소"  class="input_text"></dd>
									<dd><input type=text name='home_addr2' value="<?=$home_addr2?>" size=30 required label="세부주소"  class="input_text"></dd>
								</dl>
							</td>
						</tr>	

						<tr>
							<td class="left_td">핸드폰<font color="#F02800">＊</font></td>
							<td>
								<dl>
									<dd>
										<input type=text name='mobile[]' id = 'mobile1' value="" size=4 maxlength=4 required option=regNum label="핸드폰"  class="input_text_s"> -
										<input type=text name='mobile[]' id = 'mobile2' value="" size=4 maxlength=4 required option=regNum label="핸드폰"  class="input_text_s"> -
										<input type=text name='mobile[]' id = 'mobile3' value="" size=4 maxlength=4 required option=regNum label="핸드폰"  class="input_text_s">
									</dd>
									<dd><input type=checkbox name='news_sms_yn' id="idx_news_sms_yn" value="Y" <?if($news_sms_yn=="Y")echo"checked";?>></dd>
									<dd>문자서비스수신</dd>
								</dl>
							</td>
						</tr>	

						<tr>
							<td class="left_td">전화번호<font color="#F02800">＊</font></td>
							<td>
								<dl>
									<dd>
										<input type=text name='home_tel[]' id = 'home_tel1' value="" size=4 maxlength=4 required option=regNum label="전화번호"  class="input_text_s"> -
										<input type=text name='home_tel[]' id = 'home_tel2' value="" size=4 maxlength=4 required option=regNum label="전화번호"  class="input_text_s"> -
										<input type=text name='home_tel[]' id = 'home_tel3' value="" size=4 maxlength=4 required option=regNum label="전화번호"  class="input_text_s">
									</dd>
								</dl>
							</td>
						</tr>	
					</table>
				</div><!-- end basic_info -->

				<div class="space30"></div>
				<p class="explain2">아래 추가정보를 입력해주시면 다양한 서비스를 받으실 수 있습니다.</p>
				<div class="basic_info">							
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="left_td">결혼여부</td>
							<td>
								<dl>
									<dd><input type=radio name='married_yn' value="N" <?=$checked[married_yn]['N']?> label="결혼여부" > 미혼</dd>
									<dd><input type=radio name='married_yn' value="Y" <?=$checked[married_yn]['Y']?> label="결혼여부" > 기혼</dd>
								</dl>
							</td>
						</tr>	
						<tr>
							<td class="left_td">결혼기념일</td>
							<td>
								<dl>
									<dd>
										<input type=text name='married_date' required label="결혼기념일" size=12 maxlength=12  class="input_text_s" OnClick="Calendar(event)" readonly>
									</dd>
								</dl>
							</td>
						</tr>	
						<tr>
							<td class="left_td">직업</td>
							<td>
								<select name=job class="select">
									<option value="">==선택하세요==
									<option value="1" >학생
									<option value="2" >컴퓨터전문직
									<option value="3" >회사원
									<option value="4" >전업주부
									<option value="5" >건축/토목
									<option value="6" >금융업
									<option value="7" >교수직
									<option value="8" >공무원
									<option value="9" >의료계
									<option value="10" >법조계
									<option value="11" >언론/출판
									<option value="12" >자영업
									<option value="13" >방송/연예/예술
									<option value="14" >기타
								</select>
							</td>
						</tr>	

						<tr>
							<td class="left_td">관심분야</td>
							<td>
								<dl>
									<dd><input type=checkbox name=interest[] value="<?=pow(2,1)?>" ></dd><dd>화장품/향수/미용품</dd>
									<dd><input type=checkbox name=interest[] value="<?=pow(2,2)?>" ></dd><dd>컴퓨터/SW</dd>
									<dd><input type=checkbox name=interest[] value="<?=pow(2,3)?>" ></dd><dd>의류/패션잡화</dd>
									<dd><input type=checkbox name=interest[] value="<?=pow(2,4)?>" ></dd><dd>생활/주방용품</dd>
									<dd><input type=checkbox name=interest[] value="<?=pow(2,5)?>" ></dd><dd>보석/시계/악세사리</dd>
								</dl>
								<dl>
									<dd><input type=checkbox name=interest[] value="<?=pow(2,6)?>" ></dd><dd>가전/카메라</dd>
									<dd><input type=checkbox name=interest[] value="<?=pow(2,7)?>" ></dd><dd>서적/음반/비디오</dd>
									<dd><input type=checkbox name=interest[] value="<?=pow(2,8)?>" ></dd><dd>스포츠/레져용품</dd>
									<dd><input type=checkbox name=interest[] value="<?=pow(2,9)?>" ></dd><dd>꽃배달/케익서비스</dd>
								</dl>
							</td>
						</tr>
						
						<?
							$fieldarray=explode("=",$member_addform);
							$num=sizeof($fieldarray)/3;
							for($i=0;$i<$num;$i++) {
								if (substr($fieldarray[$i*3],-1,1)=="^") {
									$fieldarray[$i*3]=substr($fieldarray[$i*3],0,strlen($fieldarray[$i*3])-1);
									$field_check[$i]="OK";
								} else {
									$fieldarray[$i*3]=$fieldarray[$i*3];
								}
						?>

								<tr>
									<td class="left_td"><?=$fieldarray[$i*3]?> </td>
									<td colspan="3">
										<dl>
											<dd>
												<input type=text name="etc[<?=$i?>]" value="<?=$etc[$i]?>" size="<?=$fieldarray[$i*3+1]?>" maxlength="<?=$fieldarray[$i*3+2]?>" id="etc_<?=$i?>" class="input_text_l">
											</dd>
										</dl>
									</td>
								</tr>
						<?
							}
						?>
						<tr>
							<td class="left_td">남기는 말씀</td>
							<td>
								<dl>
									<dd>에코팩토리에 하시고 싶은 말씀이 있으시면 적어주세요</dd>
								</dl>
								<dl>
									<dd><textarea name=memo  style="width:600px;height:100px"></textarea></dd>
								</dl>
							</td>
						</tr>
					</table>
				</div><!-- end basic_info -->
                <div class="btn_set">
					<span><a href="/front/member_join.php?mem_type=<?=$mem_type?>"><img src="/image/join/bt_reset_join.gif" alt="초기화" id="btnReset"></a></span>
                    <span><a href="javascript:CheckForm('<?=$mem_type?>');"><img src="/image/join/bt_complete_join.gif" alt="초기화" id="btnReset"></a></span>
                </div><!-- end btn_set -->
            </div><!-- end write_form -->
        </form>
    </div><!-- end contents -->
</div><!-- //end container -->
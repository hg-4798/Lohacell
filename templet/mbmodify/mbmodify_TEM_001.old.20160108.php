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
				<h3>필수 입력 정보</h3>
				<p class="table_info">서비스 이용을 위한 필수 입력 정보입니다.</p>
				<table class="th_left" summary="">
					<colgroup>
						<col style="width:121px" />
						<col style="width:auto" />
					</colgroup>
					<tbody>
						<tr>
							<th scope="row">닉네임</th>
							<td>
								<input type="text" class="w100" name='nickname' value = '<?=$nickname?>' required label="닉네임" size=12 maxlength=12 title="닉네임을 입력하세요."/> <a href="javascript:;" id="btnCheckNick" class="btn_util">중복확인</a>
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
						<?if ($mb_facebook_id) {?>
						<tr>
							<th scope="row" colspan=2>페이스북 연동 회원입니다. 비밀번호를 설정하시면 웹회원으로 로그인 가능합니다.</th>
						</tr>
						<?}?>
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
							<th scope="row"><?=$affiliate_text?> </th>
							<td>
								<select class="w140 defult" name="mb_referrer2" title="<?=$affiliate_text?>를 선택하세요.">
								<?
								$col_sql = " select * from tblaffiliatesinfo where type='{$affiliatetype}' and output = '1' and area != '기타' order by name asc";
								//echo $mb_referrer2;
								$col_result = pmysql_query($col_sql,get_db_conn());
								while($col_row = pmysql_fetch_object($col_result)){
									$col_selected	= "";
									($mb_referrer2 == $col_row->idx) ? $col_selected	= " selected" : $col_selected	= "";
									$rf_idx				= $col_row->idx;
									$rf_referrername = $col_row->name;		
									echo "<option value='{$rf_idx}'{$col_selected}>{$rf_referrername}</option>\n";
								}
								pmysql_free_result($col_result);
								?>
								</select>&nbsp;&nbsp;&nbsp;&nbsp;
								<?=$department_text?>&nbsp;&nbsp;<input type="text" class="w140" name='mb_department' value="<?=$mb_department?>" title="<?=$department_text?>를 입력하세요" />
								<!--span class="reg_ment"><?=$affiliate_text?>정보가 있어야 레노버 포인트가 적립되며 외부에 공개되지 않습니다.</span-->
							</td>
						</tr>
					</tbody>
				</table>
			</div><!-- //테이블 Wrap -->

			<!-- 테이블 Wrap -->
			<div class="table_wrap mt_30">
				<h3>추가 입력 정보</h3>
				<p class="table_info">추가정보를 입력해 주시면 더욱 다양한 혜택을 받으실 수 있습니다.</p>
				<table class="th_left" summary="추가 정보를 입력하시면 특별한 혜택을 제공합니다.">
					<colgroup>
						<col style="width:121px" />
						<col style="width:auto" />
					</colgroup>
					<tbody>

						<tr>
							<th scope="row">이름</th>
							<td>
								<input type="text" class="w100" name='name' value = '<?=$name?>' required label="이름" size=12 maxlength=12 title="이름을 입력하세요."/>
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
							<th scope="row">이메일/SMS <br />수신여부</th>
							<td>
								<ul>
									<li><?=$_data->shopname?>에서 제공되는 서비스에 대한 수신동의 여부를 확인해 주세요.</li>
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

			<!-- 버튼 -->
			<div class="ta_c mt_40">
				<a href="javascript:CheckForm('<?=$mem_type?>');" class="btn_D on">정보수정</a>&nbsp;&nbsp;
				<a href="/front/member_join.php?mem_type=<?=$mem_type?>"  class="btn_D">취소하기</a>
			</div>
			<!-- //버튼 -->
		</form>
	 </div>

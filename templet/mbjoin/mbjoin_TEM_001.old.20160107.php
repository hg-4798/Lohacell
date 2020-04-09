<!-- 메인 컨텐츠 -->
 <div class="containerBody sub_skin">
	
	<h3 class="title mt_20">
			회원가입
			<p class="line_map"><a>홈</a> &gt; <a>약관/본인인증</a> &gt; <a class="on">회원정보입력</a> &gt; <a>가입완료</a></p>
		</h3>
		
		<!-- 테이블 Wrap -->
		<div class="table_wrap mt_30">
			<h3>정보 입력</h3>
			<p class="table_info">*표시는 필수입력 사항입니다.</p>
			<table class="th_left" summary="회원가입 정보를 입력합니다.">
				<colgroup>
					<col style="width:121px" />
					<col style="width:auto" />
				</colgroup>
				<tbody>
					<tr>
						<th scope="row">*이메일</th>
						<td>
							<input type="text" class="w140" name='email_id' value="" size=30 title="이메일을 입력하세요" required /> @ <input type="text" class="w140" name='email_addr' title="이메일을 입력하세요" />
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
							<input type="hidden" name="email" />
							<input type="hidden" id="email_check" value="0" />
						</td>
					</tr>
					<tr>
						<th scope="row">*비밀번호</th>
						<td>
							<input type="password" id="user-pass" name="passwd1" value = '<?=$passwd1?>' maxlength="15" title="비밀번호를 입력하세요." />
							<span class="reg_ment">6~20자 미만의 영문/숫자 (특수문자 사용불가)</span>
						</td>
					</tr>
					<tr>
						<th scope="row">*비밀번호 확인</th>
						<td>
							<input type="password" id="identify-pass" name="passwd2" value = '<?=$passwd2?>' maxlength="15" title="비밀번호를 한번더 입력하세요." />
							<span class="reg_ment">입력하신 비밀번호를 한번더 입력해주세요.</span>
						</td>
					</tr>					
				</tbody>
			</table>
		</div><!-- //테이블 Wrap -->

		<!-- 가입버튼 -->
		<div class="ta_c mt_40 mb_80">
			<a href="javascript:CheckForm('');" class="btn_D on">회원 가입</a> <a href="javascript:facebook_open('/front/member_join_facebook.php');" class="btn_D on">페이스북으로 가입</a> <a href="/front/member_agree.php" class="btn_D">취소 하기</a>
		</div>
		<!-- //가입버튼 -->
 </div>
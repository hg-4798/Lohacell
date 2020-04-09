<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="member-page">

		<article class="memberJoin-wrap">
			<header class="join-title">
				<h2>회원가입</h2>
				<ul class="flow clear">
					<li><div><i></i><span>STEP 1</span>본인인증</div></li>
					<li><div><i></i><span>STEP 2</span>약관동의</div></li>
					<li class="active"><div><i></i><span>STEP 3</span>정보입력</div></li>
					<li><div><i></i><span>STEP 4</span>가입완료</div></li>
				</ul>
			</header>
			<section class="inner-align join-reg">
				<header class="sub-title">
					<h3>회원정보 입력</h3>
					<p class="essential"><span class="point-color">*</span> 표시는 필수항목입니다.</p>
				</header>
				<fieldset>
					<legend>회원가입 양식 폼</legend>
					<table class="th-left">
						<caption>회원가입 양식</caption>
						<colgroup>
							<col style="width:178px">
							<col style="width:auto">
						</colgroup>
						<tbody>
							<tr>
								<th scope="row"><label>이름</label></th>
								<td>홍길동</td>
							</tr>
							<tr>
								<th scope="row"><label>생년월일</label></th>
								<td>
									1982년 11월 11일
									<div class="radio ml-20">
										<input type="radio" name="birth_type" id="birth_typeA" checked>
										<label for="birth_typeA">양력</label>
									</div>
									<div class="radio ml-10">
										<input type="radio" name="birth_type" id="birth_typeB" >
										<label for="birth_typeB">음력</label>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>성별</label></th>
								<td>남자</td>
							</tr>
							<tr>
								<th scope="row"><label for="mbReg_id" class="essential">아이디</label></th>
								<td>
									<div class="input-cover">
										<input type="text" style="width:270px" id="mbReg_id" title="아이디 입력자리" placeholder="아이디 입력">
										<button class="btn-basic"><span>중복확인</span></button>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="mbReg_pw1" class="essential">비밀번호</label></th>
								<td>
									<div class="input-cover">
										<input type="password" style="width:270px" id="mbReg_pw1" title="비밀번호 입력자리" placeholder="영문,숫자 포함 8~20자리">
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="mbReg_pw2" class="essential">비밀번호 확인</label></th>
								<td>
									<div class="input-cover">
										<input type="password" style="width:270px" id="mbReg_pw2" title="비밀번호 재입력자리">
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>주소</label></th>
								<td>
									<ul class="input-multi input-cover">
										<li><input type="text" title="우편번호 입력자리" style="width:125px"><button class="btn-basic"><span>주소찾기</span></button></li>
										<li><input type="text" title="검색된 주소" class="w100-per"></li>
										<li><input type="text" title="상세주소 입력" class="w100-per"></li>
									</ul>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="mbReg_tel2">전화번호</label></th>
								<td>
									<div class="input-cover">
										<div class="select">
											<select id="mbReg_tel2" style="width:110px">
												<option>02</option>
											</select>
										</div>
										<span class="txt">-</span>
										<input type="text" title="선택 전화번호 가운데 입력자리" style="width:110px">
										<span class="txt">-</span>
										<input type="text" title="선택 전화번호 마지막 입력자리" style="width:110px">
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="mbReg_phone2" class="essential">휴대폰 번호</label></th>
								<td>
									<div class="input-cover">
										<div class="select">
											<select id="mbReg_phone2" style="width:110px">
												<option>010</option>
											</select>
										</div>
										<span class="txt">-</span>
										<input type="text" title="필수 휴대폰 번호 가운데 입력자리" style="width:110px">
										<span class="txt">-</span>
										<input type="text" title="필수 휴대폰 번호 마지막 입력자리" style="width:110px">
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="mbReg_email" class="essential">이메일</label></th>
								<td>
									<div class="input-cover">
										<input type="text"  style="width:190px" title="이메일 입력" id="mbReg_email">
										<span class="txt">@</span>
										<div class="select">
											<select style="width:170px">
												<option value="">직접입력</option>
												<option value="">naver.com</option>
											</select>
										</div>
										<input type="text" title="도메인 직접 입력" class="ml-10" style="width:170px"> <!-- [D] 직접입력시 인풋박스 출력 -->
										<button class="btn-basic"><span>중복확인</span></button>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>추가정보</label></th>
								<td>
									<div class="input-cover">
										<label>
											<span class="fz-13 pr-5">키(cm)</span>
											<input type="text"  style="width:90px" title="이메일 입력">
										</label>
										<label class="pl-20">
											<span class="fz-13 pr-5">몸무게(kg)</span>
											<input type="text"  style="width:90px" title="이메일 입력">
										</label>
										<span class="fz-12 pl-20">※ 추가정보 모두 입력시 200 E포인트 적립</span>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="my_job">직업</label></th>
								<td>
									<div class="input-cover">
										<div class="select" id="my_job" title="직업 선택">
											<select style="width:190px" >
												<option>선택</option>
												<option>주부</option>
												<option>자영업</option>
												<option>사무직</option>
												<option>생산/기술직</option>
												<option>판매직</option>
												<option>보험업</option>
												<option>은행/증권업</option>
												<option>전문직</option>
												<option>공무원</option>
												<option>농축산업</option>
												<option>학생</option>
												<option>기타</option>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>마케팅 활동 동의</label></th>
								<td>
									<div class="mrk-agree">
										<p>신원몰이 제공하는 다양한 이벤트 및 혜택 안내에 대한 수신동의 여부를 확인해주세요.</p>
										<p>수신 체크 시 고객님을 위한 다양하고 유용한 정보를 제공합니다.</p>
										<div class="mt-10">
											<div class="checkbox">
												<input type="checkbox" id="mrkAgree_email">
												<label for="mrkAgree_email">이메일 수신</label>
											</div>
											<div class="checkbox ml-60">
												<input type="checkbox" id="mrkAgree_sms">
												<label for="mrkAgree_sms">SMS 수신</label>
											</div>
											<div class="checkbox ml-60">
												<input type="checkbox" id="mrkAgree_talk">
												<label for="mrkAgree_talk">카카오톡 수신</label>
											</div>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="btnPlace mt-40">
						<a href="" class="btn-line h-large">취소</a>
						<button type="submit" class="btn-point h-large"><span>확인</span></button>
					</div>
				</fieldset>
			</section>
			
		</article>

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>
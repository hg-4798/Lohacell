<?php
include_once('outline/header_m.php');
?>

<!-- 내용 -->
<main id="content" class="subpage with_bg">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>회원정보 수정</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="joinpage join_form my_modify sub_bdtop">
		<div class="form_notice"><strong class="point-color">*</strong> 표시는 필수항목입니다.</div>
		
		<div class="board_type_write">
			<dl>
				<dt>이름</dt>
				<dd>홍길동</dd>
			</dl>
			<dl>
				<dt>생년월일</dt>
				<dd>
					<span>1987년 01월 11일</span>
					<label class="ml-10">
						<input type="radio" class="radio_def" name="birthType" checked>
						<span>양력</span>
					</label>
					<label class="ml-5">
						<input type="radio" class="radio_def" name="birthType">
						<span>음력</span>
					</label>
				</dd>
			</dl>
			<dl>
				<dt>성별</dt>
				<dd>남자</dd>
			</dl>
			<dl>
				<dt>아이디</dt>
				<dd>nuneun</dd>
			</dl>
			<dl>
				<dt><span class="required">비밀번호</span></dt>
				<dd>
					<input type="password" class="w100-per" placeholder="비밀번호 입력 (영문, 숫자 포함 8~20자리)">
					<input type="password" class="w100-per mt-5" placeholder="비밀번호 확인">
				</dd>
			</dl>
			<dl>
				<dt><span class="required">주소</span></dt>
				<dd>
					<div class="input_addr">
						<input type="text" class="w100-per" placeholder="우편번호">
						<div class="btn_addr"><a href="javascript:;" class="btn-basic h-input">주소찾기</a></div>
					</div>
					<input type="text" class="w100-per mt-5" placeholder="기본주소">
					<input type="text" class="w100-per mt-5" placeholder="상세주소">
				</dd>
			</dl>
			<dl>
				<dt>전화번호</dt>
				<dd>
					<div class="input_tel">
						<select class="select_line">
							<option value="">선택</option>
							<option value=""></option>
							<option value=""></option>
						</select>
						<span class="dash"></span>
						<input type="tel" maxlength="4">
						<span class="dash"></span>
						<input type="tel" maxlength="4">
					</div>
				</dd>
			</dl>
			<dl>
				<dt><span class="required">휴대폰 번호</span></dt>
				<dd>
					<div class="input_tel">
						<select class="select_line">
							<option value="">010</option>
							<option value=""></option>
							<option value=""></option>
						</select>
						<span class="dash"></span>
						<input type="tel" maxlength="4">
						<span class="dash"></span>
						<input type="tel" maxlength="4">
					</div>
				</dd>
			</dl>
			<dl>
				<dt><span class="required">이메일</span></dt>
				<dd>
					<div class="input_addr">
						<div class="input_mail">
							<input type="text" class="w100-per">
							<span class="at">&#64;</span>
							<select class="select_line">
								<option value="">선택</option>
								<option value=""></option>
								<option value=""></option>
							</select>
						</div>
						<div class="btn_addr"><a href="javascript:;" class="btn-basic h-input">중복확인</a></div>
					</div>
					<input type="text" class="w100-per mt-5" placeholder="직접입력">
				</dd>
			</dl>
			<dl>
				<dt>추가정보</dt>
				<dd class="body_info">
					<label>키(cm)<input type="text" value="160"></label>
					<label>몸무게(kg)<input type="text" value="60"></label>
					<p class="ment mt-5">※ 추가정보 모두 입력시 200 E포인트 적립</p>
				</dd>
			</dl>
			<dl>
				<dt>직업</dt>
				<dd>
					<select class="select_line w100-per">
						<option value="">선택</option>
						<option value="">주부</option>
						<option value="">자영업</option>
						<option value="">사무직</option>
						<option value="">생산/기술직</option>
						<option value="">판매직</option>
						<option value="">보험업</option>
						<option value="">은행/증권업</option>
						<option value="">전문직</option>
						<option value="">공무원</option>
						<option value="">농축산업</option>
						<option value="">학생</option>
						<option value="">기타</option>
					</select>
				</dd>
			</dl>
			<dl>
				<dt>마케팅 활동 동의</dt>
				<dd>
					신원몰이 제공하는 다양한 이벤트 및 혜택 안내에 대한 수신동의 여부를 확인해주세요. <br>수신 체크 시 고객님을 위한 다양하고 유용한 정보를 제공합니다.
					<div class="btn_area mt-10">
						<ul class="ea3">
							<li><label><input type="checkbox" class="check_def"> <span>이메일 수신</span></label></li>
							<li><label><input type="checkbox" class="check_def"> <span>SMS 수신</span></label></li>
							<li><label><input type="checkbox" class="check_def"> <span>카카오톡 수신</span></label></li>
						</ul>
					</div>
				</dd>
			</dl>
		</div><!-- //.board_type_write -->

		<div class="btn_area mt-30">
			<ul>
				<li><a href="javascript:;" class="btn-point h-input">저장</a></li>
			</ul>
		</div>

	</section><!-- //.joinpage -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>
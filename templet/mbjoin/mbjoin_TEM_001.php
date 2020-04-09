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
								<td>
									
									<?=$name?><input type="hidden" id="user-name" name="name" value="<?=$name?>" title="이름을 입력하세요." tabindex="1">
									
								</td>
							</tr>
							<tr>
								<th scope="row"><label>생년월일</label></th>
								<td>
									<?=$birthdate1?>년 <?=$birthdate2?>월 <?=$birthdate3?>일
									<div class="radio ml-20">
										<input type="radio" name="lunar" id="birth_typeA" checked="" value="1">
										<label for="birth_typeA">양력</label>
									</div>
									<div class="radio ml-10">
										<input type="radio" name="lunar" id="birth_typeB" value="0">
										<label for="birth_typeB">음력</label>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>성별</label></th>
								<td>
									<?
									
										if($gender=='1' || $gender=='3'){
										 	echo "남자";
										}else if($gender=='0' || $gender=='4'){
											echo "여자";	
										}
									
									?>
									
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="user-id" class="essential">아이디</label></th>
								<td>
									<div class="input-cover">
                                        <?php
                                        if($sns_type !=''){
                                            $rand_num = sprintf('%02d',rand(00,99));
                                            if($id_che > 0){
                                                $re_id = $sns_email[0].$rand_num;
                                            }else{
                                                $re_id = $sns_email[0];
                                            }
                                        }
                                        ?>
										<input type="text" style="width:270px;text-transform: lowercase;" id="user-id" name="id" tabindex="2" title="아이디 입력자리" placeholder="아이디 입력" onChange="javascript:$('input[name=id_checked]').val('0');" value="<?=$re_id;?>" >
										
										<button class="btn-basic" onclick="ValidFormId('1','');return false;"><span>중복확인</span></button>
										
									</div>
								</td>
							</tr>
							<tr <?= ($sns_type == '') ? "" : "class='hide'"; ?>>
								<th scope="row"><label for="user-pwd1" class="essential">비밀번호</label></th>
								<td>
									<div class="input-cover">
										<input type="password" style="width:358px" id="user-pwd1" name="passwd1" title="비밀번호 입력자리" tabindex="3" placeholder="비밀번호 입력">
									</div>
									<p class="mt-5">영문 대소문자+숫자+특수문자 조합, 공백 없이 8자 ~ 20자리 이내로 입력해주세요.</p>
								</td>
							</tr>
							<tr <?= ($sns_type == '') ? "" : "class='hide'"; ?>>
								<th scope="row"><label for="mbReg_pw2" class="essential" placeholder="영문 대소문자+숫자+특수문자 조합, 공백 없이 8자 ~ 20자리 이내로 입력해주세요.">비밀번호 확인</label></th>
								<td>
									<div class="input-cover">
										<input type="password" style="width:358px" id="user-pwd2" name="passwd2" title="비밀번호를 한 번 더 입력하세요." tabindex="4" placeholder="비밀번호 재입력">
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>주소</label></th>
								<td>
									<input type="hidden" id="home_post1" name = 'home_post1' value="<?=$erp_home_post1?>">
									<input type="hidden" id='home_post2' name = 'home_post2' value="<?=$erp_home_post2?>">

									<ul class="input-multi input-cover">
										<li><input type="text" name = 'home_zonecode' id = 'home_zonecode' value="<?=$erp_home_zip_no?>" title="우편번호 입력자리" style="width:125px" readonly>
											<button class="btn-basic" onclick="openDaumPostcode();return false;" ><span tabindex="5">주소찾기</span></button></li>
										<li><input type="text" name = 'home_addr1' id = 'home_addr1' value="<?=$erp_home_addr1?>" title="검색된 주소" class="w100-per" readonly ></li>
										<li><input type="text" name = 'home_addr2' id = 'home_addr2' value="<?=$erp_home_addr2?>" title="상세주소 입력" class="w100-per" tabindex="6"></li>
									</ul>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="home_tel1">전화번호</label></th>
								<td>
									<div class="input-cover">
										<div class="select">
											<select id="home_tel1" name="home_tel1" style="width:110px" tabindex="7">
												<option value="02" <?if($erp_home_tel_no1=="02"){?>selected<?}?>>02</option>
												<option value="031" <?if($erp_home_tel_no1=="031"){?>selected<?}?>>031</option>
												<option value="032" <?if($erp_home_tel_no1=="032"){?>selected<?}?>>032</option>
												<option value="033" <?if($erp_home_tel_no1=="033"){?>selected<?}?>>033</option>
												<option value="041" <?if($erp_home_tel_no1=="041"){?>selected<?}?>>041</option>
												<option value="042" <?if($erp_home_tel_no1=="042"){?>selected<?}?>>042</option>
												<option value="043" <?if($erp_home_tel_no1=="043"){?>selected<?}?>>043</option>
												<option value="044" <?if($erp_home_tel_no1=="044"){?>selected<?}?>>044</option>
												<option value="051" <?if($erp_home_tel_no1=="051"){?>selected<?}?>>051</option>
												<option value="052" <?if($erp_home_tel_no1=="052"){?>selected<?}?>>052</option>
												<option value="053" <?if($erp_home_tel_no1=="053"){?>selected<?}?>>053</option>
												<option value="054" <?if($erp_home_tel_no1=="054"){?>selected<?}?>>054</option>
												<option value="055" <?if($erp_home_tel_no1=="055"){?>selected<?}?>>055</option>
												<option value="061" <?if($erp_home_tel_no1=="061"){?>selected<?}?>>061</option>
												<option value="062" <?if($erp_home_tel_no1=="062"){?>selected<?}?>>062</option>
												<option value="063" <?if($erp_home_tel_no1=="063"){?>selected<?}?>>063</option>
												<option value="064" <?if($erp_home_tel_no1=="064"){?>selected<?}?>>064</option>
											</select>
										</div>
										<span class="txt">-</span>
										<input type="text" class="numbersOnly" id="home_tel2" name="home_tel2" value="<?=$erp_home_tel_no2?>" title="선택 전화번호 가운데 입력자리" style="width:110px" tabindex="8" maxlength="4">
										<span class="txt">-</span>
										<input type="text" class="numbersOnly" id="home_tel3" name="home_tel3" value="<?=$erp_home_tel_no3?>" title="선택 전화번호 마지막 입력자리" style="width:110px" tabindex="9" maxlength="4">
										<input type="hidden" name="home_tel" id="home_tel">									
										
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="mobile1" class="essential">휴대폰 번호</label></th>
								<td>
									<div class="input-cover">
										<div class="select">
											<select id="mobile1" name="mobile1" style="width:110px" tabindex="10"  <?=$mobileno1!=""?" disabled=\"disabled\"":""?>>
												<option value="010" <?if($mobileno1=="010"){?>selected<?}?>>010</option>
												<option value="011" <?if($mobileno1=="011"){?>selected<?}?>>011</option>
												<option value="016" <?if($mobileno1=="016"){?>selected<?}?>>016</option>
												<option value="017" <?if($mobileno1=="017"){?>selected<?}?>>017</option>
												<option value="018" <?if($mobileno1=="018"){?>selected<?}?>>018</option>
												<option value="019" <?if($mobileno1=="019"){?>selected<?}?>>019</option>
											</select>
										</div>
										<span class="txt">-</span>
										<input type="text" class="numbersOnly" id="mobile2" name="mobile2" value="<?=$mobileno2?>" title="필수 휴대폰 번호 가운데 입력자리" style="width:110px" tabindex="11"<?=$mobileno2!=""?" readonly":""?> maxlength="4">
										<span class="txt">-</span>
										<input type="text" class="numbersOnly"  id="mobile3" name="mobile3" value="<?=$mobileno3?>" title="필수 휴대폰 번호 마지막 입력자리" style="width:110px" tabindex="12"<?=$mobileno3!=""?" readonly":""?> maxlength="4">
										<input type="hidden" name="mobile" id="mobile">
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="email" class="essential">이메일</label></th>
								<td>
									<div class="input-cover">
										<input type="text" id="email1" name="email1" value="<?=$sns_email[0]?>" style="width:190px" title="이메일 입력" tabindex="14" onChange="javascript:$('input[name=email_checked]').val('0');" >
										<span class="txt">@</span>
										<input type="text" id="email2" name="email2" value="<?=$sns_email[1]?>" title="도메인 직접 입력" class="ml-10" style="width:170px;<?if($erp_email_com!="custom"){?>display: none;<?}?>" onChange="javascript:$('input[name=email_checked]').val('0');"> <!-- [D] 직접입력시 인풋박스 출력 -->
										&nbsp;<div class="select" >
											<select style="width:170px" tabindex="15" id="email_com" onchange="customChk(this.value);" >
												<option value="">선택</option>
                                                <option value="custom" <? if($sns_email[1]=="custom"){?>selected<?}?>>직접입력</option>
                                                <?php foreach ($email_domain_arr AS $key =>$val) {?>
                                                    <option value="<?=$val?>" <? if($sns_email[1]==$val){?>selected<?}?>><?=$val?></option>
                                                <?php } ?>
											</select>
											<input type="hidden" id="email" name="email">
										</div>
										
										<button class="btn-basic" onclick="ValidFormEmail('1','');return false;"><span>중복확인</span></button>
									</div>
								</td>
							</tr>
							
							<tr>
								<th scope="row"><label for="" class="">관심카테고리</label></th>
								<td>
									<div class="input-cover">
                                        <div class="radio">
                                            <input type="radio" name="skintype" id="skintype1" value="1">
                                            <label for="skintype1">FACE</label>
                                        </div>
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype2" value="2">
                                            <label for="skintype2">LIP</label>
                                        </div>
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype3" value="3">
                                            <label for="skintype3">EYE</label>
                                        </div>
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype4" value="4">
                                            <label for="skintype4">CHEEK</label>
                                        </div>
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype5" value="5">
                                            <label for="skintype5">SKINCARE</label>
                                        </div>
                                        <!--
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype6" value="6">
                                            <label for="skintype6">민감성(과민감성)</label>
                                        </div>
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype7" value="7">
                                            <label for="skintype7">아토피</label>
                                        </div>
                                        -->
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype0" value="0" checked="">
                                            <label for="skintype0">없음</label>
                                        </div>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>마케팅 활동 동의</label></th>
								<td>
									<div class="mrk-agree">
										<p>i KNOW iONE 이 제공하는 다양한 이벤트 및 혜택 안내에 대한 수신동의 여부를 확인해주세요.</p>
										<p>수신 체크 시 고객님을 위한 다양하고 유용한 정보를 제공합니다.</p>
										<div class="mt-10">
											<div class="checkbox">
												<input type="checkbox" id="news_mail_yn" name="news_mail_yn" value="Y" checked>
												<label for="news_mail_yn">이메일 수신</label>
											</div>
											<div class="checkbox ml-60">
												<input type="checkbox" id="news_sms_yn" name="news_sms_yn" value="Y" checked>
												<label for="news_sms_yn">SMS 수신</label>
											</div>
										</div>
									</div>
								</td>
							</tr>

						</tbody>
					</table>
					<div class="btnPlace mt-40">
						<a href="javascript::" onclick="submitCancel();" class="btn-line h-large">취소</a>
						<button type="button" onclick="CheckForm('1');return false;" class="btn-point h-large" tabindex="20"><span>확인</span></button>
					</div>
				</fieldset>
			</section>
			
		</article>

	</div>
</div><!-- //#contents -->



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
<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">회원정보 수정</h2>

		<div class="inner-align page-frm clear">

			<!-- LNB -->
			<?php
			include ($Dir.FrontDir."mypage_TEM01_left.php");
			?>
			<!-- //LNB -->
			<article class="my-content">
				
				<fieldset>
					<legend>회원가입 양식 폼</legend>
					<p class="ta-r fz-13 txt-toneB"><strong class="point-color">*</strong>표시는 필수항목입니다.</p>
					<table class="th-left mt-10">
						<caption>회원가입 양식</caption>
						<colgroup>
							<col style="width:178px">
							<col style="width:auto">
						</colgroup>
						<tbody>
							<tr>
								<th scope="row"><label>이름</label></th>
								<td>
								<?=$name?>
								<input type="hidden" id="user-name" name="name" value="<?=$name?>" title="이름을 입력하세요." tabindex="1" maxlength="20">
                                </td>
							</tr>
							<tr>
								<th scope="row"><label>생년월일</label></th>
								<td>
								<?
								if ($auth_type!='old') {
								?>
									<?=$birth1?>년 <?=$birth2?>월 <?=$birth3?>일
								<?
								} else {
								?>
									<div class="select">
										<select id="birth1" name="birth1" style="width:70px" tabindex="2" >
										<?for($y=2017;$y >= 1900;$y--) {?>
											<option value="<?=$y?>"><?=$y?></option>
										<?}?>
										</select>
									</div>
									<span class="txt">-</span>
									<div class="select">
										<select id="birth2" name="birth2" style="width:50px" tabindex="2" >
										<?for($m=1;$m <= 12;$m++) {?>
											<option value="<?=$m<10?'0'.$m:$m?>"><?=$m?></option>
										<?}?>
										</select>
									</div>
									<span class="txt">-</span>
									<div class="select">
										<select id="birth3" name="birth3" style="width:50px" tabindex="2" >
										<?for($d=1;$d <= 31;$d++) {?>
											<option value="<?=$d<10?'0'.$d:$d?>"><?=$d?></option>
										<?}?>
										</select>
									</div>
								<?}?>
									<div class="radio ml-20">
										<input type="radio" name="lunar" id="lunarA" value="1"<?=$lunar=='1'?' checked':''?><?if ($auth_type!='old') {?> disabled='disabled'<?}?> >
										<label for="lunarA">양력</label>
									</div>
									<div class="radio ml-10">
										<input type="radio" name="lunar" id="lunarB" value="0"<?=$lunar=='0'?' checked':''?><?if ($auth_type!='old') {?> disabled='disabled'<?}?>>
										<label for="lunarB">음력</label>
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>성별</label></th>
								<td>
								<?
								if ($auth_type!='old') {
								?>
									<?
									
										if($gender=='1' || $gender=='3'){
										 	echo "남자";
										}
										if($gender=='0' || $gender=='4'){
											echo "여자";	
										}
									
									?>
								<?
								} else {
								?>
									<div class="radio ml-20">
										<input type="radio" name="gender" id="genderA" value="1"<?=$gender=='1' || $gender=='3'?' checked':''?>>
										<label for="genderA">남자</label>
									</div>
									<div class="radio ml-10">
										<input type="radio" name="gender" id="genderB" value="2"<?=$gender=='0' || $gender=='4'?' checked':''?>>
										<label for="genderB">여자</label>
									</div>
								<?}?>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>아이디</label></th>
								<td><?=$id?></td>
							</tr>
							<tr>
								<th scope="row"><label for="mbReg_pw1" class="essential">비밀번호</label></th>
								<td>
									<div class="input-cover">
										<input type="password" style="width:372px" id="mbReg_pw1" name="passwd1" title="비밀번호 입력자리" placeholder="비밀번호 입력">
									</div>
									<p class="mt-5">영문 대소문자+숫자+특수문자 조합, 공백 없이 8자 ~ 20자리 이내로 입력</p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="mbReg_pw2" class="essential">비밀번호 확인</label></th>
								<td>
									<div class="input-cover">
										<input type="password" style="width:372px" id="mbReg_pw2" name="passwd2" title="비밀번호 재입력자리" placeholder="비밀번호 재입력">
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label>주소</label></th>
								<td>
									<input type="hidden" id="home_post1" name = 'home_post1' value="<?=$home_post1?>">
									<input type="hidden" id='home_post2' name = 'home_post2' value="<?=$home_pos2?>">

									<ul class="input-multi input-cover">
										<li><input type="text" name = 'home_zonecode' id = 'home_zonecode' value="<?=$home_post?>" title="우편번호 입력자리" style="width:125px" readonly>
											<button type="button" class="btn-basic" onclick="openDaumPostcode();return false;" ><span tabindex="5">주소찾기</span></button></li>
										<li><input type="text" name = 'home_addr1' id = 'home_addr1' value="<?=$home_addr1?>" title="검색된 주소" class="w100-per" readonly ></li>
										<li><input type="text" name = 'home_addr2' id = 'home_addr2' value="<?=$home_addr2?>" title="상세주소 입력" class="w100-per" tabindex="6"></li>
									</ul>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="home_tel1">전화번호</label></th>
								<td>
									<div class="input-cover">
										<div class="select">
											<select id="home_tel1" name="home_tel1" style="width:110px" tabindex="7">
												<option value="02" <?if($home_tel_arr[0]=="02"){?>selected<?}?>>02</option>
												<option value="031" <?if($home_tel_arr[0]=="031"){?>selected<?}?>>031</option>
												<option value="032" <?if($home_tel_arr[0]=="032"){?>selected<?}?>>032</option>
												<option value="033" <?if($home_tel_arr[0]=="033"){?>selected<?}?>>033</option>
												<option value="041" <?if($home_tel_arr[0]=="041"){?>selected<?}?>>041</option>
												<option value="042" <?if($home_tel_arr[0]=="042"){?>selected<?}?>>042</option>
												<option value="043" <?if($home_tel_arr[0]=="043"){?>selected<?}?>>043</option>
												<option value="044" <?if($home_tel_arr[0]=="044"){?>selected<?}?>>044</option>
												<option value="051" <?if($home_tel_arr[0]=="051"){?>selected<?}?>>051</option>
												<option value="052" <?if($home_tel_arr[0]=="052"){?>selected<?}?>>052</option>
												<option value="053" <?if($home_tel_arr[0]=="053"){?>selected<?}?>>053</option>
												<option value="054" <?if($home_tel_arr[0]=="054"){?>selected<?}?>>054</option>
												<option value="055" <?if($home_tel_arr[0]=="055"){?>selected<?}?>>055</option>
												<option value="061" <?if($home_tel_arr[0]=="061"){?>selected<?}?>>061</option>
												<option value="062" <?if($home_tel_arr[0]=="062"){?>selected<?}?>>062</option>
												<option value="063" <?if($home_tel_arr[0]=="063"){?>selected<?}?>>063</option>
												<option value="064" <?if($home_tel_arr[0]=="064"){?>selected<?}?>>064</option>
											</select>
										</div>
										<span class="txt">-</span>
										<input type="text" class="numbersOnly" id="home_tel2" name="home_tel2" value="<?=$home_tel_arr[1]?>" title="선택 전화번호 가운데 입력자리" style="width:110px" tabindex="8" maxlength="4">
										<span class="txt">-</span>
										<input type="text" class="numbersOnly" id="home_tel3" name="home_tel3" value="<?=$home_tel_arr[2]?>" title="선택 전화번호 마지막 입력자리" style="width:110px" tabindex="9" maxlength="4">
										<input type="hidden" name="home_tel" id="home_tel" value="<?=$home_tel?>">
										
									</div>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="mobile1" class="essential">휴대폰 번호</label></th>
								<td>
									<div class="input-cover">
										<div class="select">
											<select id="mobile1" name="mobile1" style="width:110px" tabindex="10"  <?=$mobile_arr[0]!="" && $auth_type!='old'?" disabled=\"disabled\"":""?>>
												<option value="010" <?if($mobile_arr[0]=="010"){?>selected<?}?>>010</option>
												<option value="011" <?if($mobile_arr[0]=="011"){?>selected<?}?>>011</option>
												<option value="016" <?if($mobile_arr[0]=="016"){?>selected<?}?>>016</option>
												<option value="017" <?if($mobile_arr[0]=="017"){?>selected<?}?>>017</option>
												<option value="018" <?if($mobile_arr[0]=="018"){?>selected<?}?>>018</option>
												<option value="019" <?if($mobile_arr[0]=="019"){?>selected<?}?>>019</option>
											</select>
										</div>
										<span class="txt">-</span>
										<input type="text" class="numbersOnly" id="mobile2" name="mobile2" value="<?=$mobile_arr[1]?>" title="필수 휴대폰 번호 가운데 입력자리" style="width:110px" tabindex="11"<?=$mobile_arr[1]!="" && $auth_type!='old'?" readonly":""?> maxlength="4">
										<span class="txt">-</span>
										<input type="text" class="numbersOnly"  id="mobile3" name="mobile3" value="<?=$mobile_arr[2]?>" title="필수 휴대폰 번호 마지막 입력자리" style="width:110px" tabindex="12"<?=$mobile_arr[2]!="" && $auth_type!='old'?" readonly":""?> maxlength="4">
										<input type="hidden" name="mobile" id="mobile" value="<?=$mobile?>">
                                        <button class="btn-basic" onclick="MoCheckForm('mobile');return false;"><span>변경하기</span></button>
									</div>
                               </td>
							</tr>
							<tr>
								<th scope="row"><label for="email" class="essential">이메일</label></th>
								<td>
									<div class="input-cover">
										<input type="text" id="email1" name="email1" value="<?=$email_arr[0]?>" style="width:190px" title="이메일 입력" tabindex="14" onChange="javascript:$('input[name=email_checked]').val('0');" >
										<span class="txt">@</span>
										<input type="text" id="email2" name="email2" value="<?=$email_arr[1]?>" title="도메인 직접 입력" class="ml-10" style="width:170px;<?if($email_com!="custom"){?>display: none;<?}?>" onChange="javascript:$('input[name=email_checked]').val('0');"> <!-- [D] 직접입력시 인풋박스 출력 -->
										&nbsp;<div class="select" >
											<select style="width:170px" tabindex="15" id="email_com" onchange="customChk(this.value);" >
												<option value="">선택</option>
												<option value="custom" <?if($email_com=="custom"){?>selected<?}?>>직접입력</option>
                                                <?php foreach ($email_domain_arr AS $key =>$val) {?>
                                                    <option value="<?=$val?>" <? if($email_com==$val){?>selected<?}?>><?=$val?></option>
                                                <?php } ?>
											</select>
											<input type="hidden" id="email" name="email" value="<?=$email?>">
										</div>
										
										<button class="btn-basic" onclick="ValidFormEmail('1','');return false;"><span>중복확인</span></button>
									</div>
								</td>
							</tr>

                            <tr>
                                <th scope="row"><label for="" class="">추가정보</label></th>
                                <td>
                                    <div class="input-cover">
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype1" value="1" <?=$checked['skintype']['1']?>>
                                            <label for="skintype1">건성</label>
                                        </div>
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype2" value="2" <?=$checked['skintype']['2']?>>
                                            <label for="skintype2">지성</label>
                                        </div>
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype3" value="3" <?=$checked['skintype']['3']?>>
                                            <label for="skintype3">중성</label>
                                        </div>
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype4" value="4" <?=$checked['skintype']['4']?>>
                                            <label for="skintype4">복합성</label>
                                        </div>
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype5" value="5" <?=$checked['skintype']['5']?>>
                                            <label for="skintype5">트러블성</label>
                                        </div>
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype6" value="6"  <?=$checked['skintype']['6']?>>
                                            <label for="skintype6">민감성(과민감성)</label>
                                        </div>
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype7" value="7"  <?=$checked['skintype']['7']?>>
                                            <label for="skintype7">아토피</label>
                                        </div>
                                        <div class="radio ml-20">
                                            <input type="radio" name="skintype" id="skintype0" value="0" <?=$checked['skintype']['0']?>>
                                            <label for="skintype0">없음</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label>환불계좌 관리</label></th>
                                <td>
                                    <!-- 환불계좌 미등록 시 노출 -->
                                    <!--
                                    <div class="form-multi">
                                        <span class="text-tone-b fz-12">등록된 환불계좌가 없습니다.</span>
                                        <a href="#popup-modify03" class="btn-regular is-basic" style="min-width:80px"><span>계좌등록</span></a>
                                    </div>
                                    -->
                                    <!-- 환불계좌 등록 -->
                                    <div id="bank_div">
                                        <input type="hidden" name="bank_name" id="bank_name" value="">
                                        <div class="select" >
                                            <select style="width:170px" tabindex="15" id="bank_code" name="bank_code" onchange="bankCodeChange(this);" >
												<option value="">선택</option>
												<? foreach($kcp_bank_code AS $k=>$v) { ?>
												<option value="<?=$k?>"><?=$v?></option>
												<? } ?>
											</select>
                                        </div>
                                        <input type="text" title="계좌번호 입력자리" placeholder="계좌번호를 입력하세요" style="width:270px" name="account_num" id="account_num">
                                        <input type="text" title="예금주 입력자리" placeholder="예금주를 입력하세요" style="width:130px"  name="depositor"  id="depositor">
                                        <button type="button" class="btn-basic" style="min-width:90px" onclick="acnt_chk()"><span>본인계좌인증</span></button>
                                        <div class="board-attention" >∙ 계좌번호는 – 을 빼고 숫자만 입력해주세요.</div>
                                    </div>
                                    <!-- 등록된 계좌가 있을 시 노출 -->
                                    <div class="form-multi hide" id="bank_info">
                                        <span class="text-tone-a fz-13" id="bank_info_text">우리은행 / 1234567890 / 홍길동</span>
                                        <button type="button" class="btn-basic" style="min-width:90px" onclick="acnt_del()"><span>재인증</span></button>
                                    </div>

                                </td>
                            </tr>
							<tr>
								<th scope="row"><label>마케팅 활동 동의</label></th>
								<td>
									<div class="mrk-agree">
										<p><?=$_data->shopname?>이 제공하는 다양한 이벤트 및 혜택 안내에 대한 수신동의 여부를 확인해주세요.</p>
										<p>수신 체크 시 고객님을 위한 다양하고 유용한 정보를 제공합니다.</p>
										<div class="mt-10">
											<div class="checkbox">
												<input type="checkbox" id="news_mail_yn" name="news_mail_yn" value="Y" <?=$checked['news_mail_yn']['Y']?>>
												<label for="news_mail_yn">이메일 수신</label>
											</div>
											<div class="checkbox ml-60">
												<input type="checkbox" id="news_sms_yn" name="news_sms_yn" value="Y" <?=$checked['news_sms_yn']['Y']?>>
												<label for="news_sms_yn">SMS 수신</label>
											</div>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="btnPlace mt-40">
						<button type="button" onclick="CheckForm('1');return false;" class="btn-point h-large w250" tabindex="20"><span>확인</span></button>
					</div>
				</fieldset>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->


<script Language="JavaScript">
    function bankCodeChange(obj){
        var select_bankname = $(obj).find("option:selected").text();
        $("#bank_name").val(select_bankname);
    }
    $(document).ready(function (){
        //저장된 은행 선택
        var rBank= $('#bank_code').val();

        var kcp_bank_name = '<?=$kcp_bank_code[$bank_code]?>';
        var bank_code = '<?=$bank_code?>';
        var account_num = '<?=$account_num?>';
        var depositor = '<?=$depositor?>';

        if(bank_code !=''){
            $('#bank_div').addClass('hide');
            $('#bank_info').removeClass('hide');
            $('#bank_info_text').html(kcp_bank_name+ ' / '+account_num+' / '+ depositor);
            $("#bank_code").val(bank_code);
            $("#account_num").val(account_num);
            $("#depositor").val(depositor);
            $("input[name=bank_checked]").val("1");
        }

    });

    function acnt_chk(){
        var bank_code = $("#bank_code").val();
        var bank_name = $("#bank_name").val();
        var owner_nm = $("#depositor").val();
        var account_no = $("#account_num").val();
        $('.update_btn').addClass('hide');
        if(bank_name==""){
            alert('은행을 선택해주세요.');
            return false;
        }
        if(account_no==""){
            alert('계좌번호를 입력해 주세요.');
            $("#account_num").focus();
            return false;
        }
        if(owner_nm==""){
            alert('예금주를 입력해 주세요.');
            $("#depositor").focus();
            return false;
        }
        account_no = parseInt(account_no);
        $.ajax({
            cache: false,
            type: 'POST',
            url: '../third_party/pg/NHNKCP/hub_account.php',
            //contentType: "application/x-www-form-urlencoded;charset=ISO-8859-15",
            data: "pay_method=ACCO&req_tx=pay&bank_code="+bank_code+"&owner_nm="+owner_nm+"&account_no="+account_no,
            async: false,
            dataType:'json',
            success: function(r) {
                $('.update_btn').removeClass('hide');
                //res_code="0000";
                if(r.success) {
                    alert(r.msg);
                    $('#bank_div').addClass('hide');
                    $('#bank_info').removeClass('hide');
                    $('#bank_info_text').html(bank_name+ ' / '+account_no+' / '+ owner_nm);
                    $("input[name=bank_checked]").val("1");
                }else {
                    alert(r.msg);
                    return false;
                }
            },
            error: function(result) {
                alert("에러가 발생하였습니다.");

            }
        });
    }

    function acnt_del(){

        $('#bank_div').removeClass('hide');
        $('#bank_info').addClass('hide');
        $("#bank_code").val("");
        $("#bank_name").val("");
        $("#depositor").val("");
        $("#account_num").val("");
        $("input[name=bank_checked]").val("0");
    }

</script>

<?php
$id         =trim($_POST["id"]);
$name       = $_POST['name'];
$email      =trim($_POST["email"]);
$sns_type   =$_POST["sns_type"];
$gender     =$_POST['gender'];
$auth_type  =$_POST['auth_type'];
$mem_type   = $_POST['mem_type'];
?>

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
    <input type="hidden" id="user-id" class="input-def w250" name="id" value = '<?=$id?>'>
    <input type="hidden" id="user-id" class="input-def w250" name="sns_type" value = '<?=$sns_type?>'>

    <div class="breadcrumb">
        <ul>
            <li><a href="/">HOME</a></li>
            <li class="on"><a>CREATE AN ACCOUNT</a></li>
        </ul>
    </div>

    <div class="agreement-wrap">
        <div class="flow">
            <ul>
                <li class="on"><span>01</span> 정보입력</li>
                <li><span>02</span> 가입완료</li>
            </ul>
        </div>
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
                    <th scope="row"><label for="user-name">이름</label></th>
                    <td>
                        <?php
                        if($name){
                            ?>
                            <input type="text" id="user-name" class="input-def w240" name="name" value="<?=$name?>" maxlength="30" title="이름를 입력하세요.">
                            <?php
                        }else{
                            ?>
                            <input type="text" id="user-name" class="input-def w240" name="name" value="<?=$name?>" maxlength="30" title="이름를 입력하세요.">
                            <?php
                        }
                        ?>
                        <span class="join-att-ment"></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="user-email">이메일</label></th>
                    <td>
                        <div class="email-cover">
                            <?=$email?>
                            <input type="hidden" id="user-email" name="email" class="input-def w250" title="이메일을 입력해 주세요." onkeyup="domail_list_up(this.value)" value="<?=$email?>" autocomplete="off">
                        </div>
                        <label class="with-check"><input type="checkbox" class="checkbox-def"  name='news_mail_yn' id="" value="Y" checked> 이메일 수신동의</label>
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
                            <button type="button" class="my_value selected"><span><?=$hp1?></span></button>
                            <?if(!$mobileno){?>
                                <ul class="a_list">
                                    <li <?=$hover[mobileno]['010']?>><a href="javascript:;" onClick="javascript:mobile1_change('010')">010</a></li>
                                    <li <?=$hover[mobileno]['011']?>><a href="javascript:;" onClick="javascript:mobile1_change('011')">011</a></li>
                                    <li <?=$hover[mobileno]['016']?>><a href="javascript:;" onClick="javascript:mobile1_change('016')">016</a></li>
                                    <li <?=$hover[mobileno]['017']?>><a href="javascript:;" onClick="javascript:mobile1_change('017')">017</a></li>
                                    <li <?=$hover[mobileno]['018']?>><a href="javascript:;" onClick="javascript:mobile1_change('018')">018</a></li>
                                    <li <?=$hover[mobileno]['019']?>><a href="javascript:;" onClick="javascript:mobile1_change('019')">019</a></li>
                                </ul>
                            <?}?>
                            <input type=hidden name='mobile[]' id = 'mobile1' value="<?=$hp1?>">
                        </div>
                        <span class="txt-lh">-</span>
                        <input type="text" class="input-def w70" name='mobile[]' id = 'mobile2' maxlength=4 option=regNum required value="<?=$hp2?>" <?if($mobileno){?>readonly<?}?>>
                        <span class="txt-lh">-</span>
                        <input type="text" class="input-def w70" name='mobile[]' id = 'mobile3' maxlength=4 option=regNum required title="휴대폰번호를 입력해 주세요."   onfocusout="ValidFormMobile()" value="<?=$hp3?>" <?if($mobileno){?>readonly<?}?>>
                        <label class="with-check"><input type="checkbox" class="checkbox-def"  name='news_sms_yn' id="" value="Y" checked> SMS 수신동의(이벤트/쿠폰 등의 혜택안내를 받으실 수 있습니다.)</label>
                        <span class="join-att-ment"></span>

                    </td>
                </tr>
                <tr>
                    <th scope="row">생년월일</th>
                    <td>
                        <?php if($strDateBirth){?>
                            <input type="text" class="input-def w90" name='birth' value = '<?=$strDateBirth?>' required label="생년월일" size=12 maxlength=12 title="생년월일을 입력하세요." onclick="Calendar(event)" readonly onfocusout="ValidFormBirth()">
                        <?php }else{?>
                            <input type="text" class="input-def w90" name='birth' value = '<?=$strDateBirth?>' required label="생년월일" size=12 maxlength=12 title="생년월일을 입력하세요." onclick="Calendar(event)" readonly onfocusout="ValidFormBirth()">
                        <?php }?>
                        <span class="join-att-ment"></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">성별</th>
                    <td>
                        <div class="select small" style="width:100px; z-index:10">
                            <?
                            $selected['gender'][$gender] = ' class="hover"';
                            ?>
                            <span class="ctrl"><span class="arrow"></span></span>
                            <button type="button" class="my_value selected"><span><?=$mb_genders[$gender]?></span></button>
                            <ul class="a_list">
                                <?php foreach($mb_genders as $k=>$v) { ?>
                                    <li <?=$selected['gender'][$k]?>><a href="javascript:;" onClick="javascript:gender_change('<?=$k?>')"><?=$v?></a></li>
                                <?php }?>
                            </ul>
                            <input type=hidden name='gender' id = 'gender' value="<?=$gender?>">
                        </div>
                        <span class="join-att-ment"></span>
                    </td>
                </tr><!--
					<tr>
						<th scope="row">성별</th>
						<td>
                            <div>
                                <input type="radio"  name='gender' id="gender" value="1" <?/* echo "checked"*/?>>
                                <label for="gender">남</label>
                                <input type="radio"  name='gender' id="gender" value="2" <?/* if($gender==2) echo "checked"*/?>>
                                <label for="gender">여</label>
                            </div>
							<span class="join-att-ment"></span>
						</td>
					</tr>-->
                </tbody>
            </table>
        </div><!-- //테이블 Wrap -->

        <div class="agreement-wrap">
            <div class="agree-box-toggle">
                <p class="inner-title"><input type="checkbox"  id="all-agree" class="checkbox-def"><label for="all-agree">약관 전체 동의</label></p>
                <dl class="use">
                    <dt>
                        <input type="checkbox" class="chk_agree checkbox-def" name="agree" value="1" id="idx_agree" ><label for="idx_agree">이용약관</label>
                        <button type="button">내용 더보기</button>
                    </dt>
                    <dd><div class="content-box js-scroll"><?=$agreement?></div></dd>
                </dl>
                <dl class="privacy">
                    <dt>
                        <input type="checkbox" class="chk_agree checkbox-def" name="agreep" value="1" id="idx_agreep" ><label for="idx_agreep">개인정보 보호를 위한 이용자 동의사항</label>
                        <button type="button">내용 더보기</button>
                    </dt>
                    <dd><div class="content-box js-scroll"><?=$privercy?></div></dd>
                </dl>
            </div>
        </div>

        <!-- 가입버튼 -->
        <div class="btn-place">
            <a href="login.php"  class="btn-dib-function line"><span>취소하기</span></a>
            <a href="javascript:CheckForm('<?=$mem_type?>');" class="btn-dib-function"><span>가입완료</span></a>
        </div>
        <!-- //가입버튼 -->

    </div><!-- //.agreement-wrap -->
</div>

<?
header("Content-Type: text/html; charset=UTF-8");
if(strlen($Dir)==0) $Dir="../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
$out_access_type	= "mobile";

include_once(DOC_ROOT."/lib/shopdata.php");

include('./include/top.php');
include('./include/gnb.php');
$memoutinfo	= $_POST['memoutinfo'];

if($memoutinfo == '') {

    $sql = "SELECT a.*, b.group_name FROM tblmember a left join tblmembergroup b on a.group_code = b.group_code WHERE a.id='".$_ShopInfo->getMemid()."' ";
    //echo $sql;
    $result=pmysql_query($sql,get_db_conn());
    if($row=pmysql_fetch_object($result)) {
        if($row->member_out=="Y") {
            $_ShopInfo->SetMemNULL();
            $_ShopInfo->Save();
            alert_go('회원 아이디가 존재하지 않습니다.',$Dir.MDir."login.php");
        }

        if($row->authidkey!=$_ShopInfo->getAuthidkey()) {
            $_ShopInfo->SetMemNULL();
            $_ShopInfo->Save();
            alert_go('처음부터 다시 시작하시기 바랍니다.',$Dir.MDir."login.php");
        }

        $_mdata=$row;

        // 사용가능 쿠폰수
        //$cdate = date("YmdH");
        $coupon = new COUPON();
        list($t_coupon_sale_cnt)= $coupon->useCoupon(MEMID);

        //진행중인 주문건
        list($t_order_cnt)=pmysql_fetch_array(pmysql_query("SELECT COUNT(ob.*) t_order_cnt
                                                            FROM tblorder_basic ob
                                                            JOIN tblorder_product op on ob.order_num = op.order_num
                                                            WHERE ob.member_id = '".MEMID."'
                                                            AND op.order_status::integer > 0 and op.cs_type::integer >0 and op.cs_status::integer < 4"));
    }
    pmysql_free_result($result);

    if(strlen($_ShopInfo->getMemid())==0) {
        Header("Location:".$Dir.MDir."login.php?chUrl=".getUrl());
        alert_go(null,$Dir.MDir."login.php?chUrl=".getUrl());
        exit;
    }

    /*if($_data->memberout_type=="N") {
        alert_go("회원탈퇴를 하실 수 없습니다.\\n\\n쇼핑몰 운영자에게 문의하시기 바랍니다.",-1);
    }*/
}
?>

<?
//$my_passwd_check_arr	= explode("|", decrypt_md5($_POST['my_passwd_check']));

//if ($my_passwd_check_arr[0] == $_MShopInfo->getMemid() && $my_passwd_check_arr[1] == 'Y') { // 비밀번호 확인페이지를 확인한 경우
if ($_POST['my_passwd_check'] == 'Y') { // 비밀번호 확인페이지를 확인한 경우

    ?>
    <script>

        $(document).ready(function() {
            var cate_detail = new Array();
            cate_detail = <?=json_encode($arrMemberOutReason_m)?>;

            $('#out_reason1').change(function(){
                or_str = "";
                var cateval = $('#out_reason1').val();
                if(cateval) {
                    $.each(cate_detail[cateval], function (idx, val) {
                        or_str += "<option value='" + idx + "'>" + val + "</option>";
                    });
                    $('#out_reason').html(or_str);
                }else{
                    or_str = "<option value=''>상세 사유 선택</option>";
                    $('#out_reason').html(or_str);
                }
            });
        });

        function CheckForm() {
            if (document.form1.out_reason.value == '')
            {
                alert("탈퇴사유를 선택해 주세요.");
            } else {
                if(confirm("회원 탈퇴를 하시면 모든 혜택이 소멸됩니다.\n탈퇴에 대한 내용을 꼭 확인해주세요. 그래도 탈퇴하시겠습니까?")) {
                    if (document.form1.t_order_cnt.value == 0) {
                        document.form1.mode.value = "exit";
                        var formdata = $("#FrmMemberOut").serialize();
                        $.ajax({
                            url: '/proc/login.proc.php',
                            data: formdata,
                            dataType: 'json',
                            sync: false,
                            type: 'POST',
                            success: function (r) {
                                if (r.success) {
                                    UI.alert(r.msg, function() {
                                        window.location.href= "main.php";
                                    });
                                }
                                else {
                                    UI.error(r.msg);
                                }
                            }
                        });

                    } else {
                        alert("진행중인 주문이 완료 되어야 탈퇴처리 가능하십니다.");
                    }
                }
            }
        }

    </script>

    <div id="page">
        <!-- 내용 -->
        <main id="content" class="subpage">

            <section class="page_local">
                <h2 class="page_title">
                    <a href="javascript:history.back(); return false;" class="prev">이전페이지</a>
                    <span>회원탈퇴</span>
                </h2>
            </section><!-- //.page_local -->

            <!-- 서비스 해지 작성 -->
            <section class="my_withdrawal sub_bdtop ta-l">
				<form id="FrmMemberOut" name='form1' action="<?=$_SERVER['PHP_SELF']?>" method="POST"  onsubmit="CheckForm();return false;" >
					<input type=hidden name=mode>
					<input type=hidden name=t_order_cnt value="<?=$t_order_cnt?>">
					<input type=hidden name=mem_id value="<?=MEMID?>">
					<input type=hidden name=memoutinfo value="<?=encrypt_md5(($t_product_sale_cnt+$t_coupon_sale_cnt)."|".$_mdata->act_point."|".$_mdata->reserve."|".$_mdata->name."|".MEMID)?>">
					<div class="title-section mt-10">
						<h4 class="tit">회원 탈퇴 시 삭제 예정 혜택</h4>
					</div>
					<div class="my-point">
						<ul class="clear">
							<li>
								<div class="point-nm clear">
									<i class="icon-coupon"></i>
									<span>쿠폰</span>
								</div>
								<p><span class="underline"><strong><?=number_format($t_product_sale_cnt+$t_coupon_sale_cnt)?></strong>장</span></p>
							</li>
							<li>
								<div class="point-nm clear">
									<i class="icon-point"></i>
									<span>포인트</span>
								</div>
								<p><span class="underline"><strong><?=number_format($_mdata->act_point)?></strong>P</span></p>
							</li>
							<li>
								<div class="point-nm clear">
									<i class="icon-mileage"></i>
									<span>마일리지</span>
								</div>
								<p><span class="underline"><strong><?=number_format($_mdata->reserve)?></strong>M</span></p>
							</li>
						</ul>
					</div>
					<ul class="dash-list">
						<li>적립하신 마일리지, 포인트, 쿠폰 등의 혜택은 모두 소멸됩니다.</li>
						<li>거래 정보 관리를 위해서 회원ID, 상품정보, 거래내역 등의 기본정보는 5년간 보관됩니다.</li>
						<li>회원 탈퇴 후, 90일간 재가입이 불가합니다.</li>
						<li>재가입 시 신규회원 가입으로 처리되어 기존 아이디 사용이 불가하합니다.</li>
						<li>탈퇴 전의 회원정보/거래정보/마일리지/포인트/쿠폰 등은 복구되지 않습니다.</li>
						<li>구매확정 되지 않은 주문건이 있을 경우 탈퇴 불가 합니다.</li>
						<li>회원탈퇴처리가 진행되는 과정에서 뉴스레터가 발송될 수 있습니다.</li>
					</ul>

					<div class="title-section mt-40">
						<h4 class="tit">탈퇴 사유</h4>
					</div>
					<div class="drop-input-wrap">
						<select id="out_reason1" class="select_line">
							<option value="">탈퇴 사유 선택</option>
							<?foreach($arrMemberOutReason_m_cate as $k => $v){?>
								<option value="<?=$k?>"><?=$v?></option>
							<?}?>
						</select>
						<select id="out_reason" class="select_line" name="out_reason">
							<option value="">상세 사유 선택</option>
						</select>
						<textarea placeholder="내용 쓰기" name="out_reason_content" id="out_reason_content" cols="30" rows="10"></textarea>
					</div>
					<div class="btn_area mt-25">
						<ul class="ea2">
							<li><a href="" class="btn-line h-large">취소</a></li>
							<li><a href="javascript:CheckForm()" class="btn-point h-large">회원탈퇴</a></li>
						</ul>
					</div>
				</form>
            </section>
            <!-- //서비스 해지 작성 -->
        </main>
    </div>
    <?
} else {
    if($memoutinfo=='') {
        ?>

        <SCRIPT LANGUAGE="JavaScript">
            <!--


            function CheckForm() {

                form=document.form1;

                //기존 비밀번호 유효성 체크
                var val	= $("input[name=oldpasswd]").val();

                if (val == '') {
                    alert($("input[name=oldpasswd]").attr("title"));
                    $("input[name=oldpasswd]").focus();
                    return;
                } else {

                    $.ajax({
                        type: "GET",
                        url: "<?=$Dir.FrontDir?>iddup.proc.php",
                        data: "passwd=" + encodeURIComponent(val) + "&mode=passwd",
                        dataType:"json",
                        success: function(data) {
                            if (data.code == 0) {
                                //alert(data.msg); 알럿삭제
                                $("#uncorrect_pwd").css("display","block");
                                var block_cnt = '<?=$block_cnt?>';
                                if(block_cnt >= 5){
                                    alert("5회 이상 비밀번호를 잘못 입력하셨습니다.\n다시 로그인해주세요.");
                                    location.href ="<?=$Dir.FrontDir?>findid.php?block_cnt="+block_cnt;
                                }
                            } else if (data.code == '1') {
                                $("#uncorrect_pwd").css("display","none");
                                if(confirm("회원탈퇴를 신청하시겠습니까?")) {
                                    //document.form1.type.value="exit";
                                    document.form1.my_passwd_check.value="Y";
                                    document.form1.submit();
                                }
                                return;
                                /*if (document.form1.t_order_cnt.value == 0)
                                {
                                    if(confirm("회원탈퇴를 신청하시겠습니까?")) {
                                        document.form1.type.value="exit";
                                        document.form1.submit();
                                    }
                                    return;
                                } else {
                                    alert("진행중인 주문이 완료 되어야 탈퇴처리 가능하십니다.");
                                    return;
                                }*/
                            } else {
                                return;
                            }
                        },
                        error: function(result) {
                            alert("에러가 발생하였습니다.");
                        }
                    });
                }
            }
            //-->
        </SCRIPT>
        <div id="page">
            <!-- 내용 -->
            <main id="content" class="subpage with_bg">

                <section class="page_local">
                    <h2 class="page_title">
                        <a href="javascript:history.back();" class="prev">이전페이지</a>
                        <span>회원탈퇴</span>
                    </h2>
                </section><!-- //.page_local -->

                <section class="my_withdrawal sub_bdtop">
                    <form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method="post">
                        <input type=hidden name=mode>
                        <input type=hidden name=t_order_cnt value="<?=$t_order_cnt?>">
                        <input type=hidden name=mem_id value="<?=$_ShopInfo->getMemid()?>">
                        <input type=hidden name=memoutinfo value="<?=encrypt_md5(($t_product_sale_cnt+$t_coupon_sale_cnt)."|".$_mdata->act_point."|".$_mdata->reserve."|".$_mdata->name."|".$_ShopInfo->getMemid())?>">
                        <input type=hidden name=my_passwd_check value="N">
                        <div class="attn">
                            <p class="tit"><?=$_data->shopname?> 회원탈퇴 유의사항</p>
                            <ul class="mt-5">
                                <li>- 회원탈퇴시 적립된 포인트 및 쿠폰정보는 모두 소멸됩니다.</li>
                                <li>- 회원탈퇴시 오프라인 전용 쿠폰 및 마일리지 역시 함께 삭제처리 됩니다</li>
                                <li>- 동일 아이디로 재가입이 불가능합니다.</li>
                            </ul>
                        </div>

                        <div class="my_modify_pw">
                            <p>회원탈퇴를 위해 비밀번호를 입력해주세요.</p>
                            <input type="password" class="w100-per mt-25" id="pwd" name="oldpasswd" title="비밀번호를 입력해 주시기 바랍니다." placeholder="비밀번호 입력">
                            <input type="password" class="w100-per mt-5" id="pwdre" name="oldpasswdre" title="비밀번호를 재 입력해 주시기 바랍니다." placeholder="비밀번호 재입력">
                            <div class="btn_area mt-15">
                                <ul>
                                    <li><a href="javascript:CheckForm();" class="btn-point h-input">확인</a></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </section><!-- //.my_withdrawal -->

            </main>
            <!-- //내용 -->
        </div>
        <?
    } else {
        $memoutinfo_exp	= explode("|", decrypt_md5($memoutinfo));
        ?>
        <div id="page">
            <!-- 내용 -->
            <main id="content" class="subpage with_bg">

                <section class="page_local">
                    <h2 class="page_title">
                        <a href="javascript:history.back();" class="prev">이전페이지</a>
                        <span>회원탈퇴</span>
                    </h2>
                </section><!-- //.page_local -->

                <section class="my_withdrawal sub_bdtop">
                    <div class="end_msg">
                        <h3>회원탈퇴 완료</h3>
                        <p class="mt-10">그동안 신원몰 서비스를 이용해 주셔서 감사합니다.<br>더나은 서비스로 찾아뵙겠습니다.</p>
                        <div class="btn_area">
                            <ul>
                                <li><a href="/m/" class="btn-point h-input">메인으로 이동</a></li>
                            </ul>
                        </div>
                    </div>

                </section><!-- //.my_withdrawal -->

            </main>
            <!-- //내용 -->
        </div>
        <script type='text/javascript'>
            var m_jn = 'withdraw';
            var m_jid= '<?=$mem_id?>';
        </script>

        <?
    }
}

include('./include/bottom.php');

?>

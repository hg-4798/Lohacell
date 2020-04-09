
<!--@@ 이전소스 백업 @@ -->

<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

$memoutinfo	= $_GET['memoutinfo'];

if($memoutinfo == '') {
	$sql = "SELECT a.*, b.group_name FROM tblmember a left join tblmembergroup b on a.group_code = b.group_code WHERE a.id='".$_ShopInfo->getMemid()."' ";
	$result=pmysql_query($sql,get_db_conn());
	if($row=pmysql_fetch_object($result)) {
		if($row->member_out=="Y") {
			$_ShopInfo->SetMemNULL();
			$_ShopInfo->Save();
			alert_go('회원 아이디가 존재하지 않습니다.',$Dir.FrontDir."login.php");
		}

		if($row->authidkey!=$_ShopInfo->getAuthidkey()) {
			$_ShopInfo->SetMemNULL();
			$_ShopInfo->Save();
			alert_go('처음부터 다시 시작하시기 바랍니다.',$Dir.FrontDir."login.php");
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

	if(strlen(MEMID)==0) {
		Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
		exit;
	}

    /*if($_data->memberout_type=="N") {
        alert_go("회원탈퇴를 하실 수 없습니다.\\n\\n쇼핑몰 운영자에게 문의하시기 바랍니다.",-1);
    }*/
}

include('../front/include/top.php');
include('../front/include/gnb.php');


if ($_POST['my_passwd_check'] != 'Y') { // 비밀번호 확인페이지를 확인 안한 경우
	if($memoutinfo=='') {
		$menu_title_text	= "회원탈퇴신청";
		$menu_title_val	= "out";
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td align="">
<?include ($Dir.TempletDir."mypage/mypage_mbpasscheck{$_data->design_mbmodify}.php");?>
	</td>
</tr>
</table>

<?
	} else {
		$menu_title_text	= "회원탈퇴완료";
		$memoutinfo_exp	= explode("|", decrypt_md5($memoutinfo));
?>
<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">회원탈퇴</h2>

		<div class="inner-align page-frm clear">

			<!-- LNB -->
			<?php
			 include ($Dir.FrontDir."mypage_TEM01_left.php");
			?>
			<!-- //LNB -->
			<article class="my-content">
				
				<div class="gray-box out-end">
					<div class="mb-20"><img src="../sinwon/web/static/img/icon/icon_confirm.png" alt="완료"></div>
					<p class="fw-bold fz-20">회원탈퇴 완료</p>
					<p class="end-comment">그동안 신원몰 서비스를 이용해 주셔서 감사합니다. <br>더나은 서비스로 찾아뵙겠습니다.</p>
					<a href="/" class="btn-point h-large mt-25">메인으로 이동</a>
				</div>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->
<?
	}
} else {
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(auth_yn) {
    var reason = $("input[name='out_reason']:checked").val();
    if (!reason) {
        alert("탈퇴사유를 선택해 주세요.");
    } else {
        if(confirm("회원 탈퇴를 하시면 모든 혜택이 소멸됩니다.\n탈퇴에 대한 내용을 꼭 확인해주세요. 그래도 탈퇴하시겠습니까?")) {
            if (document.form1.t_order_cnt.value == 0) {
                document.form1.mode.value = "exit";
                var formdata = $("#FrmMemberOut").serialize();
                $.ajax({
                    url:'/proc/login.proc.php',
                    data:formdata,
                    dataType:'json',
                    sync:false,
                    type:'POST',
                    success: function(r) {
                        if(r.success){
                            UI.alert(r.msg, function() {
				window.location.href= "main.php";
				});
                            //document.location.href=r.data.url;
   	                     //window.location.href= "main.php";
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
//-->
</SCRIPT>

    <div id="contents">
        <div class="mypage-page">

            <h2 class="page-title">회원탈퇴</h2>

            <div class="inner-align page-frm clear">

                <!-- LNB -->
                <?php
                include ($Dir.FrontDir."mypage_TEM01_left.php");
                ?>
                <!-- //LNB -->
                <article class="my-content">
                    <!-- 컨텐츠 영역 -->
                    <div class="" style="height:auto">
                        <form id="FrmMemberOut" name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
                            <input type=hidden name=mode>
                            <input type=hidden name=t_order_cnt value="<?=$t_order_cnt?>">
                            <input type=hidden name=mem_id value="<?=$_ShopInfo->getMemid()?>">
                            <input type=hidden name=memoutinfo value="<?=encrypt_md5(($t_coupon_sale_cnt)."|".$_mdata->act_point."|".$_mdata->reserve."|".$_mdata->name."|".$_ShopInfo->getMemid())?>">
                            <div>

								<div class="clear">
									<div class="mygrade-con-drop">
										<div>
											<i class="icon-grade"></i>
											<div class="mygrade-info">
												<p>회원 탈퇴 시 삭제 예정 혜택</p>
											</div>
										</div>
									</div>
									<div class="mybenefit-con-drop">
										<div class="clear">
											<a href="">
												<dl class="mr-70 clear">
													<dt>
														<i class="icon-out-coupon">쿠폰</i>
													</dt>
													<dd>
														<p>쿠폰</p>
														<strong><?=number_format($t_coupon_sale_cnt)?>장</strong>
													</dd>
												</dl>
											</a>
											<a href="">
												<dl class="mr-70 clear">
													<dt>
														<i class="icon-out-point"></i>
													</dt>
													<dd>
														<p>포인트</p>
														<strong><?=number_format($_mdata->act_point)?>P</strong>
													</dd>
												</dl>
											</a>
											<a href="">
												<dl class="clear">
													<dt>
														<i class="icon-out-mileage"></i>
													</dt>
													<dd>
														<p>마일리지</p>
														<strong><?=number_format($_mdata->reserve)?>M</strong>
													</dd>
												</dl>
											</a>
										</div>
									</div>
								</div>

                                <ul class="c-txt-service mt-20">
                                    <li class="txt-2">
                                        <span>- 적립하신 마일리지, 포인트, 쿠폰 등의 혜택은 모두 소멸됩니다.</span>
                                    </li>
                                    <li class="txt-2">
                                        <span>- 거래 정보 관리를 위해서 회원ID, 상품정보, 거래내역 등의 기본정보는 5년간 보관됩니다.</span>
                                    </li>
                                    <li class="txt-2">
                                        <span>- 회원 탈퇴 후, 90일간 재가입이 불가합니다.</span>
                                    </li>
                                    <li class="txt-2">
                                        <span>- 재가입 시 신규회원 가입으로 처리되어 기존 아이디 사용이 불가합니다.</span>
                                    </li>
                                    <li class="txt-2">
                                        <span>- 탈퇴 전의 회원정보/거래정보/마일리지/포인트/쿠폰 등은  복구되지 않습니다.</span>
                                    </li>
                                    <li class="txt-2">
                                        <span>- 구매확정 되지 않은 주문건이 있을 경우 탈퇴 불가 합니다.</span>
                                    </li>
                                    <li class="txt-2">
                                        <span>- 회원탈퇴처리가 진행되는 과정에서 뉴스레터가 발송될 수 있습니다.</span>
                                    </li>
                                </ul>

                                <div class="edit-info-tit"><h2>탈퇴 사유</h2></div>
                                <div class="drop-reason-wrap clear">
                                    <div>
                                        <?
                                        $cate_detail = $arrMemberOutReason_m;
                                        foreach($arrMemberOutReason_m_cate as $k => $v){
                                            $cate_i = 1;?>
                                            <ul class="drop-reason-item">
                                                <li class="title"><?=$v?></li>
                                                <li>
                                    <span class="radio">
                                    <? foreach($cate_detail[$k] as $dtkey => $dtval) { ?>
                                        <input type="radio" id="radio<?=$k?>-<?=$cate_i?>" name="out_reason" value="<?=$dtkey?>"><label for="radio<?=$k?>-<?=$cate_i?>"><?=$dtval?></label><br>
                                        <?
                                        $cate_i++;
                                    } ?>
                                        </span>
                                                </li>
                                            </ul>
                                        <?}?>
                                    </div>
                                    <textarea class="drop-reason-textarea" name="out_reason_content" id="" cols="30" rows="10" placeholder="내용을 적어주세요."></textarea>
                                </div>
                                <div class="btnPlace mt-40">
                                    <button type="button" class="btn-line h-large"><span>취소</span></button>
									<button type="button" class="btn-point h-large" onclick="CheckForm('N');"><span>회원탈퇴</span></button>
                                </div>
                            </div>
                        </form>
                        <!-- //컨텐츠 영역 -->
                    </div>
                </article>
            </div>
        </div><!-- //#contents -->
    </div>
<?}?>
<script>
    $(function(){
        // 스크롤 시 헤더 컬러 화이트
        var beforeScroll = null;

        $(window).scroll(function(){
            var nowScroll = $(window).scrollTop();

            if(nowScroll > 0){
                $('#header').addClass('bg');
                if(nowScroll > beforeScroll){
                    // 마우스 휠 또는 스크롤 내릴때
                    $('#header').addClass('scroll');
                } else if ( nowScroll < beforeScroll){
                    // 마우스 휠 또는 스크롤 올릴 때
                    $('#header').removeClass('scroll');
                }
            } else if (nowScroll == 0){
                $('#header').removeClass('bg');
            }

            beforeScroll = nowScroll ;
        });

    });
</script>
<? include('../front/include/bottom.php'); ?>

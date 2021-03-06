<?php 
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$auth_type  = $_GET['auth_type']?$_GET['auth_type']:$_POST['auth_type'];
$mem_type   = $_GET['mem_type']?$_GET['mem_type']:$_POST['mem_type'];
$find_type  = $_GET['find_type']?$_GET['find_type']:$_POST['find_type'];
$cert_type  = $_GET['cert_type']?$_GET['cert_type']:$_POST['cert_type'];
$join_type  = $_GET['join_type']?$_GET['join_type']:$_POST['join_type'];
$staff_join  = $_GET['staff_join']?$_GET['staff_join']:$_POST['staff_join'];
$cooper_join  = $_GET['cooper_join']?$_GET['cooper_join']:$_POST['cooper_join'];
$birthdate  = $_GET['birthdate']?$_GET['birthdate']:$_POST['birthdate'];

include('./include/top.php');
include('./include/gnb.php');


session_start();
/**
* 
* 기존에 회원가입이 되어 있는지 안되어 있는지 체크하는 페이지
* member_agree.php의 ifram에서 실행하는 페이지
* 본래 member_join.php에서 체크해야 하지만 세이브힐즈의 경우 member_agree.php에서 
* 가입 결과를 레이어 팝업으로 띄워야 하기에 여기에서 체크한 다음 그 결과로 
* ipin_chk()를 호출함
* 
* 
* 
* 아이핀 인증의 경우 가입 확인 여부를 확인할 수 있지만 
* 핸드폰 인증의 경우 가입 여부를 확인 할 수 없다.
* 따라서 핸드폰 인증의 경우 본인 인증이 되면
* 무조건 회원가입 폼으로 넘어간다.
* 
*/

if ( !empty($find_type) ) {
    // 아이디찾기 or 비밀번호찾기
?>

<script type="text/javascript">
	function ipin_chk(ipin){	
        var now_find_type   = '<?=$find_type?>'; 
        var now_cert_type   = '<?=$cert_type?>'; 

		var name	= "";
		var id		= "";
		var email	= "";
        var redirect_page = "<?=$Dir.MDir?>findid.php?now_find_type="+now_find_type+"&now_cert_type="+now_cert_type;

        if(now_find_type!='wakeup'){
	        now_find_type = 'find'+now_find_type;
        }else{
	        redirect_page = "<?=$Dir.MDir?>member_sleep.php";
        }

		$.ajax({
			type: "POST", 
			url: "<?=$Dir.FrontDir?>find_idpw_indb.php", 
			data: {mode:now_find_type, cert_type:now_cert_type, name:name, id:id, email:email, access_type:'mobile'},
			dataType:"json", 
			success: function(data) {
				if (now_find_type == 'findid') {
					if (data.code == '1')
					{
                        document.location.href = redirect_page + "&mode=result&uid="+data.msg;
					} else {
						alert(data.msg);
                        document.location.href= redirect_page;
						return;
					}
				} else if (now_find_type == 'findpw') {
					if (data.code == '1')
					{
						//if (now_cert_type =='mobile') {
							document.location.href= redirect_page + "&mode=pw_change&uid="+data.msg;
						//}
					} else {
						alert(data.msg);
                        document.location.href= redirect_page;
						return;
					}
				}else if(now_find_type == 'wakeup'){
					if (data.code == '1') {
						alert("휴면이 해제되었습니다.");
						document.location.href= "<?=$Dir.MainDir?>main.php";
					} else {
						alert(data.msg);
						document.location.href= redirect_page;
					}
				}
			},
			error: function(result) {
				alert("에러가 발생하였습니다."); 
				document.location.href= redirect_page;
				return;
			}
		}); 
	}

    ipin_chk("");
</script>

<?php
} else {
    // 회원가입시 본인인증

    #####실명인증 결과에 따른 분기
    $CertificationData = pmysql_fetch_object(pmysql_query("select realname_id, realname_password, realname_check, realname_adult_check, ipin_id, ipin_password, ipin_check, ipin_adult_check from tblshopinfo"));

	$uname	= iconv("EUC-KR","UTF-8",$_SESSION[ipin][name]);

	//echo ($auth_type."|".$uname);
	if ($erp_member_yn == 'Y' && $erp_cust_name != $uname) {
?>

    <form name=form_agree id="form_agree" action="member_certi.php" method=post>
		<input type="hidden" name="erp_member_yn" value="<?=$erp_member_yn?>">
		<input type="hidden" name="erp_cust_name" value="<?=$erp_cust_name?>">
		<input type="hidden" name="erp_cell_phone_no" value="<?=$erp_cell_phone_no?>">
    </form>
<div id="page">
<!-- 내용 -->
<main id="content" class="subpage with_bg">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>회원가입</span>
		</h2>
		<div class="page_step join_step">
			<ul class="ea4 clear">
				<li class="on"><span class="icon_join_step01"></span>본인인증</li>
				<li><span class="icon_join_step02"></span>약관동의</li>
				<li><span class="icon_join_step03"></span>정보입력</li>
				<li><span class="icon_join_step04"></span>가입완료</li>
			</ul>
		</div>
	</section><!-- //.page_local -->

	<section class="joinpage">
		<div class="certi_notice">
			오프라인 매장 회원정보와 인증된 정보가 다릅니다.<br>같은 정보로 인증하시기 바랍니다.
		</div>
		<div class="btn_area">
			<ul class="ea1">
				<li><a href="javascript:;" class="btn-point w100-per h-input" onClick="javascript:backCerti();">확인</a></li>
			</ul>
		</div>
	</section><!-- //.joinpage -->

</main>
<!-- //내용 -->
</div>
    <script type="text/javascript">
        function backCerti() {
           document.form_agree.submit();
        }
    </script>
<?
	} else {
?>

    <form name=form_agree id="form_agree" action="member_agree.php" method=post>
        <input type="hidden" name="auth_type" value="<?=$auth_type?>" >
        <input type="hidden" name="mem_type" value="<?=$mem_type?>" >		
		<input type="hidden" name="join_type" id="join_type" value="<?=$join_type?>">
		<input type="hidden" name="staff_join" value="<?=$staff_join?>">
		<input type="hidden" name="cooper_join" value="<?=$cooper_join?>">
		<input type="hidden" name="erp_member_yn" value="<?=$erp_member_yn?>">
		<input type="hidden" name="erp_cust_name" value="<?=$erp_cust_name?>">
		<input type="hidden" name="erp_cell_phone_no" value="<?=$erp_cell_phone_no?>">
    </form>	
<div id="page">
<!-- 내용 -->
<main id="content" class="subpage with_bg">
	
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>회원가입</span>
		</h2>
		<div class="page_step join_step">
			<ul class="ea4 clear">
				<li class="on"><span class="icon_join_step01"></span>본인인증</li>
				<li><span class="icon_join_step02"></span>약관동의</li>
				<li><span class="icon_join_step03"></span>정보입력</li>
				<li><span class="icon_join_step04"></span>가입완료</li>
			</ul>
		</div>
	</section><!-- //.page_local -->

	<section class="joinpage hide" id='result_same_join'>
		<div class="certi_notice">
			<strong id="namespan"></strong> 고객님께서는<br><strong id="idspan"></strong>로 이미 가입하셨습니다.
		</div>
		<div class="btn_area">
			<ul class="ea1">
				<li><a href="findid.php" class="btn-point w100-per h-input">아이디/비밀번호찾기</a></li>
			</ul>
		</div>
	</section><!-- //.joinpage -->

</main>
<!-- //내용 -->
</div>
    <script type="text/javascript">
        function ipin_chk2(yn){
            if(yn=='1'){
                var ubirth   = '<?=$birthdate?>';
                var real_age = getAgeFromBirthDay(ubirth);  //새로운 분기처리부분
                if(real_age < 14){
                    alert('만 14세 미만은 회원가입이 불가능합니다'); //만14세 미만일경우 alert return
                    document.location.href= '/m/login.php';
                    return;
                }else{
                    document.form_agree.submit();
                }
            } else {
				$("#result_same_join").removeClass("hide");
			}
        }
        function getAgeFromBirthDay(birth_day) {

            var birthday = birth_day;
            var today = new Date();
            var years = today.getFullYear() - birthday.substring(0, 4).toString();

            /* 계속해서 만 나이 계산을 원하면 아래 처리를 계속해준다.
            * 연도가 같은 두 객체를 비교하여 생년월일 객체가 오늘 날짜 객체보다 크다면 -1 해준다.
            * (생일이 아직 지나지 않았다면 -1 을 한다.)*/

            // 생년월일 객체의 연도를 오늘 날짜 객체의 연도로 변경

            var now_year = today.getFullYear();

            var date_year = today.getFullYear().toString();
            var date_month = (today.getMonth()+1).toString();
            var date_date = today.getDate().toString();

            if(date_month.length < 2){
                date_month = '0'+(today.getMonth()+1).toString();
            }
            if(date_date.length < 2){
                date_date = '0'+today.getDate().toString();
            }

            var set_today = date_year+date_month+date_date;

            var get_birth_MD = birthday.substring(4, 8).toString();
            var set_birth = now_year+get_birth_MD;

            // 같은 연도가 된 객체를 비교하여 월일이 지났는 지 여부를 판단하여 years 를 뺀다.
            if (set_today < set_birth) years --;

            return years;

        }
    </script>

    <?php 

		if($CertificationData->realname_check || $CertificationData->ipin_check){
			if($_SESSION[ipin][dupinfo]){
				#####아이핀 인증의 경우
				$check_ipin=pmysql_fetch_object(pmysql_query("select count(id) as check_id from tblmember where dupinfo='{$_SESSION[ipin][dupinfo]}'"));
				$check_ipin_data = pmysql_fetch_object(pmysql_query("select id,name from tblmember where dupinfo='{$_SESSION[ipin][dupinfo]}'"));
				$check_ipin_data->id = substr($check_ipin_data->id,0,-4)."****";
				if($check_ipin->check_id){
		?>
					<script>
						$(document).ready(function() { 
							document.getElementById('namespan').innerHTML="<?=$check_ipin_data->name?>";
							document.getElementById('idspan').innerHTML="<?=$check_ipin_data->id?>";
						});

						ipin_chk2('0');
					</script>
		<?php
				}else{
		?>
					<script>
						ipin_chk2('1');
					</script>
		<?php
					
				}
			}else if($_SESSION[ipin][name]){
				#####핸드폰 인증의 경우
		?>
					<script>
						ipin_chk2('1');
					</script>
		<?php		
			}
		}
	}
}

?>



<? include('../m/include/bottom.php'); ?>

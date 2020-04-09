<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."conf/config.sns.php");
include('../front/include/top.php');
include('../front/include/gnb.php');
//로그인체크(로그인회원만 접근가능)
MEMBER::isMember();

?>

<SCRIPT LANGUAGE="JavaScript">


    // 소셜 로그인
    function sns_open(url, sns, sns_login){
        //document.frmSns.sns_login.value=sns_login;
        if(sns == 'nv'){
            var popup= window.open(url, "_naverPopupWindow", "width=500, height=500");
        }else if(sns == 'kt'){
            var popup= window.open(url, "_kakaoPopupWindow", "width=500, height=500");
        }else if(sns == 'fb'){
            var popup= window.open(url, "_facebookPopupWindow", "width=500, height=500");
        }
        popup.focus();
    }

</SCRIPT>

<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">SNS 연동관리</h2>

		<div class="inner-align page-frm clear">

			<? include  "mypage_TEM01_left.php";  ?>
			<article class="my-content">
				
				<div class="sns_account_wrp">
					<!-- SNS 계정 연결이 없는 경우 노출 -->
                    <?
                    $row_sns = pmysql_fetch_array(pmysql_query("SELECT * FROM tblmember_sns WHERE id='".MEMID."'"));
                    if(!$row_sns){
                    ?>
					<p class="ment">연결된 계정이 없습니다. 계정을 연결하려면 해당 SNS를 선택해주세요.</p>
					<ul class="sns_account_link">
						<li>
							<i class="icon-sns-naver-color big">네이버</i>
							<p class="sns_nm">NAVER</p>
							<a href="javascript:;" class="btn-basic h-medium" onclick="javascript:sns_open('/plugin/sns/sns_access.php?sns=<?=$snsNvConfig["use"]?>&sns_login=1&ac=front', '<?=$snsNvConfig["use"]?>','1');">계정연결</a>
						</li>
						<li>
							<i class="icon-sns-kakao-color big">카카오톡</i>
							<p class="sns_nm">KaKaotalk</p>
							<a href="javascript:;" class="btn-basic h-medium" onclick="javascript:sns_open('/plugin/sns/sns_access.php?sns=<?=$snsKtConfig["use"]?>&sns_login=1&ac=front', '<?=$snsKtConfig["use"]?>','1');">계정연결</a>
						</li>
						<li>
							<i class="icon-sns-facebook-color big">페이스북</i>
							<p class="sns_nm">facebook</p>
							<a href="javascript:;" class="btn-basic h-medium" onclick="javascript:sns_open('/plugin/sns/sns_access.php?sns=<?=$snsFbConfig["use"]?>&sns_login=1&ac=front', '<?=$snsFbConfig["use"]?>','1');">계정연결</a>
						</li>
					</ul>
                    <?}else{?>
					<!-- SNS 계정이 연결된 경우 노출 -->
					<div class="sns_termination">
						<div class="sns">
							<i class="icon-sns-<?=strtolower($row_sns['sns_type'])?>-color big"><?=$row_sns['sns_type']?></i><span class=""><?=$row_sns['sns_type']?></span>
						</div>
						<div class="account">
							<p class="emphasis-color"><?=$row_sns['sns_email']?></p>
							<p>* 연동정보 : <span class="point-color"><?=Common::format($row_sns['date_insert'],'Y-m-d H:i')?> 연동</span></p>
						</div>
					</div>
					<div class="board-attention">* 연결 해제 시 SNS 간편로그인 서비스를 이용하실 수 없습니다.</div>
					<div class="area-button mt-20">
                        <?if($row_sns['sns_type']=="NAVER"){?>
						    <a href="javascript:;" class="btn-point h-medium" style="width:120px" onclick="javascript:sns_disconnect('nv');"><span>해제</span></a>
                        <?}else if($row_sns['sns_type']=="KAKAO"){?>
                            <a href="javascript:;" class="btn-point h-medium" style="width:120px" onclick="javascript:disconn_kakao('kt');"><span>해제</span></a>
                        <?}else{?>
                            <a href="javascript:;" class="btn-point h-medium" style="width:120px" onclick="javascript:disconn_facebook('fb');"><span>해제</span></a>
                        <?}?>
					</div>
                    <?}?>
				</div>
                <?$sns_id = $_SESSION['sns']['sns_id'];?>
                <?$now_sns = $_SESSION['sns']['sns_type'];?>
                <input type="hidden" name="sns_id" id="sns_id" value="<?=$sns_id?>">
                <input type="hidden" name="now_sns" id="now_sns" value="<?=$now_sns?>">
                <input type="hidden" name="mem_id" id="mem_id" value="<?=MEMID?>">
                <input type="hidden" name="mem_name" id="mem_name" value="<?=MEMNAME?>">
                <input type="hidden" name="kt_sns_id" id="kt_sns_id" value="<?=$_SESSION['sns']['sns_id']?>">
                <input type="hidden" name="fb_sns_id" id="fb_sns_id" value="<?=$_SESSION['sns']['fb_sns_id']?>">
                <input type="hidden" name="access_token" id="access_token" value="<?=$_SESSION['access_token']?>">
			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->
<script>

    function sns_disconnect(part){

        var sns_id =$("#sns_id").val();
        var now_sns =$("#now_sns").val();
        var mem_id =$("#mem_id").val();
        var mem_name =$("#mem_name").val();
        var postdata= {sns_id : sns_id,
            now_sns : now_sns,
            mem_id : mem_id,
            mem_name : mem_name} ;

        if(part=='nv'){
            if(part !=now_sns){
                alert('네이버 간편로그인 후 연동해제 처리가 가능합니다.');
                return;
            }
        }

        if(confirm("연동해제 처리하시겠습니까?")){
            if(part=='nv'){
                $.ajax({
                    method : "GET",
                    url: "/plugin/sns/sns_access.php?sns=nvdel&sns_login=1&ac=m",
                    data: postdata,
                    dataType:"json"
                });
            }

            if(part=='nv'){
                $.ajax({
                    method : "POST",
                    url: "/plugin/sns/sns_join_ajax.php",
                    data: postdata,
                    dataType:"json",
                    success:function(response) {
                        if(response.msgs == 'success'){
                            alert("연동해제 되었습니다.");
                            document.location.reload();
                        }else{
                            alert("네이버 간편로그인 후 다시 시도해주세요.");
                        }
                    }
                });
            }
        }
    }

    function  disconn_kakao(part){

        var sns_id =$("#sns_id").val();
        var now_sns =$("#now_sns").val();
        var mem_id =$("#mem_id").val();
        var mem_name =$("#mem_name").val();
        var kt_sns_id =$("#kt_sns_id").val();
        var access = "<?=$_SESSION['access_token']?>";
        var adminKey = "<?=$snsKtConfig['adminKey']?>";

        if(part=='kt'){
            if(part !=now_sns){
                alert('카카오톡 간편로그인 후 연동해제 처리가 가능합니다.');
                return;
            }
        }

        var postData;
        var rows = Object();
        var rows= {
            target_id_type : 'user_id',
            target_id : kt_sns_id,
            Authorization : "KakaoAK "+adminKey,
            sns_id : sns_id,
            now_sns : now_sns,
            mem_id : mem_id,
            mem_name : mem_name
        };
        var postData = $.param(rows);
        var headers = {};
        headers["Authorization"] = "KakaoAK "+adminKey;   //  키
        if(confirm('연동해제 처리하시겠습니까?')){
            $.ajax({
                url:'/plugin/sns/kt_delete.php',
                data: postData,
                type:'post',
                dataType:'json',
                headers : headers,
                cache:false,
                success:function(response) {
                    if(response.msgs == 'success'){
                        alert("연동해제 되었습니다.");
                        document.location.reload();
                    }else{
                        alert("카카오톡 간편로그인 후 다시 시도해주세요.");
                    }
                }
            });
        }
    }

    function  disconn_facebook(part){

        var sns_id =$("#sns_id").val();
        var now_sns =$("#now_sns").val();
        var mem_id =$("#mem_id").val();
        var mem_name =$("#mem_name").val();
        var fb_sns_id =$("#fb_sns_id").val();
        var access = "<?=$_SESSION['access_token']?>";
        var adminKey = "<?=$snsfbConfig['adminKey']?>";

        if(part=='fb'){
            if(part !=now_sns){
                alert('facebook 로그인 후 연동해제 해주세요');
                return;
            }
        }

        var postData;
        var rows = Object();
        var rows= {
            access : access,
            sns_id : sns_id,
            now_sns : now_sns,
            mem_id : mem_id,
            mem_name : mem_name
        };
        var postData = $.param(rows);
        var headers = {};
        if(confirm('연동해제 처리하시겠습니까?')){
            $.ajax({
                url:'/plugin/sns/fb_delete.php',
                data: postData,
                type:'post',
                dataType:'json',
                headers : headers,
                cache:false,
                success:function(response) {
                    var success = (response.msgs == 'success');
                    if(success){
                        alert("연동해제 되었습니다.");
                        document.location.reload();
                    }else{
                        alert("facebook 로그인 후 다시 시도해주세요.");
                    }
                }
            });
        }
    }
</script>

<?php
include('../front/include/bottom.php');
?>

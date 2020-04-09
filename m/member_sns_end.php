<?php
session_start();

#---------------------------------------------------------------
# 기본정보 설정파일을 가져온다.
#---------------------------------------------------------------
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once("lib.inc.php");
include_once("shopdata.inc.php");
include_once($Dir."conf/config.sns.php");


if(strlen($_MShopInfo->getMemid())>0) {
    $mem_auth_type	= getAuthType($_MShopInfo->getMemid());
    if ($mem_auth_type != 'sns') {
        header("Location:".$Dir.MDir."index.php");
        exit;
    }
}


include('./include/top.php');
include('./include/gnb.php');

#####실명인증 결과에 따른 분기
$CertificationData = pmysql_fetch_object(pmysql_query("select realname_id, realname_password, realname_check, realname_adult_check, ipin_id, ipin_password, ipin_check, ipin_adult_check from tblshopinfo"));

if($CertificationData->realname_check || $CertificationData->ipin_check){

    if($_SESSION[ipin][dupinfo]){
        #####아이핀 인증의 경우

        $check_ipin=pmysql_fetch_object(pmysql_query("select count(id) as check_id from tblmember where dupinfo='{$_SESSION[ipin][dupinfo]}'"));
        $check_ipin_data = pmysql_fetch_object(pmysql_query("select id,name,date from tblmember where dupinfo='{$_SESSION[ipin][dupinfo]}'"));
        $check_full_id = $check_ipin_data->id;
        $check_ipin_data->id = substr($check_ipin_data->id,0,-4)."****";

        if($check_ipin->check_id){
            if($_SESSION[sns][sns_login_id]){
                $check_sns=pmysql_fetch_object(pmysql_query("select count(id) as check_sns from tblmember_sns where id='{$check_full_id}'"));
                list($sns_id,$date,$sns_type)=pmysql_fetch_array(pmysql_query("select id,date_insert,sns_type from tblmember_sns where id='{$check_full_id}'"));

                if($check_sns->check_sns){
                    $result_type ="2";
                    $sns_date = substr($date,0,4).".".substr($date,5,2).".".substr($date,8,2);
                }else{
                    if($_SESSION[sns][sns_type]=="kt"){
                        $sns_type="KAKAO";
                    }else if($_SESSION[sns][sns_type]=="nv"){
                        $sns_type="NAVER";
                    }
                    $sns_sql = "UPDATE tblmember SET sns_type = '{$_SESSION[sns][sns_login_id]}' WHERE id = '{$check_full_id}'";
                    pmysql_query($sns_sql,get_db_conn());

                    $sns_insert = "INSERT INTO tblmember_sns(id,name,sns_email,date_insert,sns_type) VALUES (
								'{$check_full_id}',
								'{$check_ipin_data->name}',
								'{$_SESSION[sns][sns_email]}',
								'{NOW}',
								'{$sns_type}') ";

                    //echo $sns_naver;exit;
                    pmysql_query($sns_insert,get_db_conn());
                    $result_type ="0";
                    $mem_date = substr($check_ipin_data->date,0,4).".".substr($check_ipin_data->date,4,2).".".substr($check_ipin_data->date,6,2);
                }
            }

        }else{
            $result_type ="1";
        }
    }
}
?>


    <div id="page">
        <!-- Content -->
        <main id="content" class="subpage">

            <section class="page_local">
                <h2 class="page_title">
                    <a href="javascript:history.btack();" class="prev">이전페이지</a>
                    <span>SNS 간편로그인</span>
                </h2>
            </section>

            <section class="wrap_inactive sub_bdtop">
                <?if($result_type =="0"){?>
                    <div class="notice">
                        <p class="tit">본인인증 정보로 확인 된 회원정보입니다.</p>
                        <p class="txt">로그인시 네이버, 카카오톡을 통해 간편하게<br>로그인할 수 있습니다. 연동 해제를 하셔도<br><?=$_data->shopname?>에 등록된 개인정보는 삭제되지 않습니다.</p>
                        <p class="txt mt-10">아이디 : <strong><?=$check_full_id?></strong></p>
                        <p class="txt mt-5">회원가입일 : <?=$mem_date?></p>
                        <div><a href="<?=$Dir.MDir?>login.php" class="btn-point h-large mt-15" style="width:140px">로그인</a></div>
                    </div>
                <?}else if($result_type =="2"){?>
                    <div class="notice">
                        <p class="tit">연동된 SNS 계정이 있습니다.</p>
                        <p class="txt">로그인시 네이버, 카카오톡을 통해 간편하게<br>로그인할 수 있습니다. 연동 해제를 하셔도<br><?=$_data->shopname?>에 등록된 개인정보는 삭제되지 않습니다.</p>
                        <div class="linked-sns">
                            <?if($sns_type=="NAVER"){?>
                                <i class="icon-sns-naver-color big">네이버</i><span>NAVER</span>
                            <?}else if($sns_type=="KAKAO"){?>
                                <i class="icon-sns-kakao-color big">카카오톡</i><span>KAKAO</span>
                            <?}else{?>
                                <i class="icon-sns-facebook-color big">페이스북</i><span>FACEBOOK</span>
                            <?}?>
                        </div>
                        <p class="txt mt-5">계정 연동일 : <?=$sns_date;?></p>
                        <div><a href="<?=$Dir.MDir?>login.php" class="btn-point h-large mt-15" style="width:140px">로그인</a></div>
                    </div>
                <?}?>
            </section>



        </main>
        <!-- //Content -->
    </div>
<?php
if($result_type !="1"){
    session_destroy();
}
?>

<?include_once('outline/footer_m.php'); ?>
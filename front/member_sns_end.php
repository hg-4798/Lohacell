<?php
/*********************************************************************
// 파 일 명		: member_certi.php
// 설     명		: 회원가입 인증 또는 확인
// 상세설명	: 회원가입시 약관 및 간편회원 추가입력폼
// 작 성 자		: 2016.07.28 - 김재수
// 수 정 자		:
//
//
*********************************************************************/
?>
<?php
	session_start();
#---------------------------------------------------------------
# 기본정보 설정파일을 가져온다.
#---------------------------------------------------------------
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");
	include_once($Dir."conf/config.sns.php");

	$mem_type = $_POST[mem_type];
	if (!$mem_type) $mem_type = 0;
	$join_type = $_POST[join_type];

	if(strlen($_ShopInfo->getMemid())>0) {
		$mem_auth_type	= getAuthType($_ShopInfo->getMemid());
		if ($mem_auth_type != 'sns') {
			header("Location:../index.php");
			exit;
		}
	}

$member_id = $_POST['member_id'];
$result_type = $_POST['result_type'];
$sns_type = $_POST['sns_type'];
$mem_date = substr($_POST['mem_date'],0,4).".".substr($_POST['mem_date'],4,2).".".substr($_POST['mem_date'],6,2);
$sns_date = substr($_POST['sns_date'],0,4).".".substr($_POST['sns_date'],5,2).".".substr($_POST['sns_date'],8,2);
?>

<?php
include('../front/include/top.php');
include('../front/include/gnb.php');
?>
<!-- content -->
<div id="content">
	<div class="member-page">
		<article class="memberJoin-wrap">
			<header class="join-title alone">
				<h2>SNS 간편로그인</h2>
			</header>
            <?php
            if($result_type=="0"){
            ?>
			<section class="align-inner join-certification">
				<header class="sub-title">
					<h3>본인인증 정보로 확인 된 회원정보입니다.</h3>
					<p class="att"><?=$_data->shopname?> 로그인시 네이버, 카카오톡에 가입되어 있는 정보를 통해 간편하게 로그인할 수 있습니다. <br>연동 해제를 하셔도 <?=$_data->shopname?>에 등록된 개인정보는 삭제되지 않습니다.</p>
				</header>
				<div class="sns-account">
					<div class="box">
						<p class="fz-15">회원님의 아이디는 <strong class="txt-toneA"><?=$member_id?></strong> 입니다.</p>
						<p style="margin-top:12px">가입일 : <?=$mem_date?></p>
						<a href="<?=$Dir.FrontDir?>login.php" class="btn-point h-large w150 mt-30"><span>로그인</span></a>
					</div>
				</div>
			</section>

            <?php
            }else if($result_type=="2"){
            ?>
			<section class="align-inner join-certification">
				<header class="sub-title">
					<h3>연동된 SNS 계정이 있습니다.</h3>
				</header>
				<div class="sns-account">
                    <div class="box">
						<span class="sns">
						<?if($sns_type=="NAVER"){?>
                            <i class="icon-sns-naver-color big"><?=$sns_type;?></i>
                        <?}else if($sns_type=="KAKAO") {?>
                            <i class="icon-sns-kakao-color big"><?=$sns_type;?></i>
                        <?}else if($sns_type=="FACEBOOK"){?>
                            <i class="icon-sns-facebook-color big"><?=$sns_type;?></i>
                        <?}?>
                            <span class=""><?=$sns_type;?></span></span>
                        <span class="">계정 연동일 : <?=$sns_date;?></span><br>
                        <a href="<?=$Dir.FrontDir?>login.php" class="btn-point h-large w150 mt-30"><span>로그인</span></a>
                    </div>
				</div>
			</section>
            <?php
            }
            ?>
		</article>
	</div>
</div>
<!-- //content -->


<?php
	if($result_type !="1"){
		session_destroy();
	}
?>

<div class="hide"><iframe name="ifrmHidden" id="ifrmHidden" width=1000 height=1000></iframe></div>
<?php  include ("../front/include/bottom.php") ?>
</BODY>
</HTML>

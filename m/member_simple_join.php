<?
	session_start();
	include_once('outline/header_m.php');

	if(strlen($_MShopInfo->getMemid())!=0) {
		echo ("<script>location.replace('/m/');</script>");
		exit;
	}



	######################## 회원가입 시작 ########################

	if(strlen($_MShopInfo->getMemid())>0) {
		header("Location:index.php");
		exit;
	}

	$type=$_POST["type"];

	if($type=="insert") {

		if($_data->group_code){
			$group_code=$_data->group_code;
		}else{
			$group_code="";
		}

		$id					= trim($_POST["id"]);
		$passwd1			= $_POST["passwd1"];
		$name				= trim($_POST["name"]);
		$email				= $_POST["email"]? trim($_POST["email"]):$id;
		$mobile				= $_POST['mobile1']."-".$_POST['mobile2']."-".$_POST['mobile3'];
		$news_sms_yn	= $_POST["news_sms_yn"];
		$news_mail_yn	= $_POST["news_mail_yn"];
		$sns_type			= $_POST["sns_type"];

		$onload="";

		$sql = "SELECT email FROM tblmember WHERE email='{$email}' ";
		$result=pmysql_query($sql,get_db_conn());
		if($row=pmysql_fetch_object($result)) {
			echo "<html><head></head><body onload=\"alert('아이디가 중복되었습니다.\\n\\n다른 아이디를 사용하시기 바랍니다.');parent.location.href='member_agree.php';\"></body></html>";exit;
		}
		pmysql_free_result($result);

		//insert

		if($news_mail_yn=="Y" && $news_sms_yn=="Y") {
			$news_yn="Y";
		} else if($news_mail_yn=="Y") {
			$news_yn="M";
		} else if($news_sms_yn=="Y") {
			$news_yn="S";
		} else {
			$news_yn="N";
		}

		$confirm_yn	= "Y";
		$ip				= $_SERVER['REMOTE_ADDR'];
		$date				= date("YmdHis");

		 $shadata = "*".strtoupper(SHA1(unhex(SHA1($passwd1))));

		BeginTrans();

		$sql = "INSERT INTO tblmember(id) VALUES('{$id}')";
		pmysql_query($sql,get_db_conn());

		$sql = "UPDATE tblmember SET ";
		$sql.= "id			= '{$id}', ";
		$sql.= "passwd		= '".$shadata."', ";
		$sql.= "name		= '{$name}', ";
		$sql.= "email		= '{$email}', ";
		$sql.= "mobile		= '{$mobile}', ";
		$sql.= "news_yn		= '{$news_yn}', ";
		$sql.= "joinip		= '{$ip}', ";
		$sql.= "ip			= '{$ip}', ";
		$sql.= "date		= '{$date}', ";
		$sql.= "sns_type		= '{$sns_type}', ";

		if(ord($group_code)) {
			$sql.= "group_code='{$group_code}', ";
		}

		$sql.= "confirm_yn	= '{$confirm_yn}' WHERE id='{$id}'";

		//echo $sql;
		//exit;
		$insert=pmysql_query($sql,get_db_conn());

		if (pmysql_errno()==0) {
			CommitTrans();

			if (get_session('ACCESS') == 'app') {
				$access_type	= "app";
			} else {
				$access_type	= "mobile";
			}

			//---------------------------------------------------- 가입시 로그를 등록한다. ----------------------------------------------------//
			$memLogSql = "INSERT INTO tblmemberlog (id,type,access_type,date) VALUES ('".$id."','join','".$access_type."','".date("YmdHis")."')";
			pmysql_query($memLogSql,get_db_conn());
			//---------------------------------------------------------------------------------------------------------------------------------//

			//가입메일 발송 처리
			if(ord($email)) {
				SendJoinMail($_data->shopname, $_data->shopurl, $_data->design_mail, $_data->join_msg, $_data->info_email, $email, $name, $id);
			}

			$mem_return_msg = sms_autosend( 'mem_join', $id, '', '' );
			$admin_return_msg = sms_autosend( 'admin_join', $id, '', '' );

			echo "<html><head></head><body onload=\"alert('회원가입이 완료되었습니다.\\n\\n감사합니다.');parent.location.href='".$Dir.MDir."member_joinend.php?name={$name}&id={$id}';\"></body></html>";exit;
		} else {
			RollbackTrans();
			echo "<html><head></head><body onload=\"alert('회원등록 중 오류가 발생하였습니다.\\n\\n관리자에게 문의하시기 바랍니다.');parent.location.href='member_agree.php';\"></body></html>";exit;
		}
	}

	$_ShopInfo->setCheckSns("");
	$_ShopInfo->setCheckSnsLogin("");
	$_ShopInfo->setCheckSnsAccess("");
	$_ShopInfo->setCheckSnsChurl("");
	$_ShopInfo->Save();

	if ($_POST["sns_email"]) $add_where	= " AND id='".$_POST["sns_email"]."' ";

	$snsCertiData = pmysql_fetch_object(pmysql_query("select id, name from tblmember where sns_type = '".$_POST["sns_type"]."||".$_POST['sns_id']."' {$add_where} "));

	#####실명인증 결과에 따른 분기
	if($snsCertiData->id){
		echo "<script>alert('".$snsCertiData->name." 고객님께서는 [".$snsCertiData->id."]로 이미 가입하셨습니다.');location.href='login.php';</script>";
		exit;
	}

######################## 회원가입 끝 ########################
?>

<script type="text/javascript">
$(document).ready(function(){
	$("#agreeAll").click(function(){
		$("#term01").prop('checked', $(this).prop('checked'));
		$("#term02").prop('checked', $(this).prop('checked'));
		$("#term03").prop('checked', $(this).prop('checked'));
	})
})

function ValidFormId(type) { //아이디 유효성 체크

	var val	= $("input[name=id]").val();

	if (val == '') {
		alert($("input[name=id]").attr("title"));
		$("input[name=id]").focus();
		return;
	} else {
		if (!(new RegExp(/^[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$/)).test(val)) {
			alert("잘못된 형식입니다.");
			$("input[name=id]").focus();
			return;
		} else {
			$.ajax({
				type: "GET",
				url: "<?=$Dir.FrontDir?>iddup.proc.php",
				data: "id=" + val + "&mode=id",
				dataType:"json",
				success: function(data) {
					$("#id_checked").val(data.code);
					if (data.code == 0) {
						alert(data.msg);
						$("input[name=id]").focus();
						return;
					} else {
						if (type == '')
						{
							ValidFormPassword();
						} else {
							alert(data.msg);
						}
					}
				},
				error: function(result) {
					alert("에러가 발생하였습니다.");
					$("input[name=id]").focus();
					return;
				}
			});
		}
	}
}

function ValidFormPassword(){//비밀번호 유효성 체크
	var val	= $("input[name=passwd1]").val();
	if (val == '') {
		alert($("input[name=passwd1]").attr("title"));
		$("input[name=passwd1]").focus();
		return;
	} else {
		if (!(new RegExp(/^.*(?=.{4,20})(?=.*[a-zA-Z])(?=.*[0-9]).*$/)).test(val)) {
			alert("4~20자 이내 영문, 숫자 2가지 조합으로 이루어져야 합니다.");
			$("input[name=passwd1]").focus();
			return;
		} else {
			$("#passwd1_checked").val("1");
			ValidFormPasswordRe();
		}
	}
}

function ValidFormPasswordRe(){//비밀번호 확인 유효성 체크
	var val			= $("input[name=passwd2]").val();
	var pw1_val	= $("input[name=passwd1]").val();
	if (val == '') {
		alert($("input[name=passwd2]").attr("title"));
		$("input[name=passwd2]").focus();
		return;
	} else {
		if (val != pw1_val) {
			alert("비밀번호를 다시 확인해 주세요.");
			$("input[name=passwd2]").focus();
			return;
		} else {
			$("#passwd2_checked").val("1");
			ValidFormName();
		}
	}
}

function ValidFormName(){ //이름 유효성 체크
	var val			= $("input[name=name]").val();

	if (val == '') {
		alert($("input[name=name]").attr("title"));
		$("input[name=name]").focus();
		return;
	} else {
		$("#name_checked").val("1");
		ValidFormMobile('');
	}
}

function ValidFormMobile() {
	var val1			= $("input[name=mobile1]").val();
	var val2			= $("input[name=mobile2]").val();
	var val3			= $("input[name=mobile3]").val();

	if (val1 == '' || val2 == '' || val3 == '') {
		alert($("input[name=mobile3]").attr("title"));
		if (val1 == '') {
			$("input[name=mobile1]").focus();
		} else if (val2 == '') {
			$("input[name=mobile2]").focus();
		} else if (val3 == '') {
			$("input[name=mobile3]").focus();
		}
	} else {
		$("#mobile_checked").val("1");
		CheckFormSubmit();
	}
}

function CheckForm(){
	$("input[name=id_checked]").val('0');
	$("input[name=passwd1_checked]").val('0');
	$("input[name=passwd2_checked]").val('0');
	$("input[name=name_checked]").val('0');
	$("input[name=mobile_checked]").val('0');
	ValidFormId('');
}

function CheckFormSubmit(){

	form=document.form1;

	var id_checked				= $("input[name=id_checked]").val();
	var passwd1_checked		= $("input[name=passwd1_checked]").val();
	var passwd2_checked		= $("input[name=passwd2_checked]").val();
	var name_checked			= $("input[name=name_checked]").val();
	var mobile_checked		= $("input[name=mobile_checked]").val();

	if(!$("#term01").prop('checked')){
		alert("이용약관에 동의 하지 않으셨습니다");
		$("#term01").focus();
		return;
	}
	if(!$("#term02").prop('checked')){
		alert("개인정보 수집 및 이용안내에 동의 하지 않으셨습니다");
		$("#term02").focus();
		return;
	}
	if(!$("#term03").prop('checked')){
		alert("개인정보 취급위탁 동의에 동의 하지 않으셨습니다");
		$("#term03").focus();
		return;
	}

	if (id_checked == '1' && passwd1_checked == '1' && passwd2_checked == '1' && name_checked == '1' && mobile_checked == '1')
	{
		form.type.value="insert";
		form.target	= "HiddenFrame";
	<?php if($_data->ssl_type=="Y" && ord($_data->ssl_domain) && ord($_data->ssl_port) && $_data->ssl_pagelist["MJOIN"]=="Y") {?>
			form.action='https://<?=$_data->ssl_domain?><?=($_data->ssl_port!="443"?":".$_data->ssl_port:"")?>/<?=RootPath.SecureDir?>member_join.php';
	<?php }?>
		if(confirm("회원가입을 하겠습니까?"))
			form.submit();
		else
			return;
	} else {
		return;
	}
}

</script>

	<div id="addressWrap" style="display:none;position:fixed;overflow:hidden;z-index:9999;-webkit-overflow-scrolling:touch;">
	<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;width:20px;right:0px;top:-1px;z-index:9999" onclick="foldDaumPostcode()" alt="접기 버튼">
	</div>
<?
	$sns_email	= explode("@",$_POST["sns_email"]);
?>

	<form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method="post" onSubmit="return CheckForm();">
	<input type="hidden" name="type" value="">
	<input type="hidden" name="sns_type" value="<?=$_POST["sns_type"]."||".$_POST['sns_id']?>">
	<input type="hidden" name="email" value="<?=$_POST["sns_email"]?>">
	<input type="hidden" name="id_checked" id="id_checked" value="0">
	<input type="hidden" name="passwd1_checked" id="passwd1_checked" value="0">
	<input type="hidden" name="passwd2_checked" id="passwd2_checked" value="0">
	<input type="hidden" name="name_checked" id="name_checked" value="0">
	<input type="hidden" name="mobile_checked" id="mobile_checked" value="0">
		<!-- <div class="sub-title">
			<h2>회원가입</h2>
			<a class="btn-prev" href="member_agree.php"><img src="./static/img/btn/btn_page_prev.png" alt="이전 페이지"></a>
			<div class="js-sub-menu">
				<button class="js-btn-toggle" title="펼쳐보기"><img src="./static/img/btn/btn_arrow_down.png" alt="메뉴"></button>
				<div class="js-menu-content">
					<ul>
						<li><a href="login.php">로그인</a></li>
						<li><a href="findid.php">아이디 찾기</a></li>
						<li><a href="findpw.php">비밀번호 찾기</a></li>
						<li><a href="member_agree.php">회원가입</a></li>
					</ul>
				</div>
			</div>
		</div> -->

		<!-- 핫티 서비스 이용약관 레이어팝업 -->
		<div class="layer-dimm-wrap layer_term_use01">
			<div class="dimm-bg"></div>
			<div class="layer-inner">
				<h3 class="layer-title">핫티 서비스 이용약관</h3>
				<button type="button" class="btn-close">창 닫기 버튼</button>
				<div class="layer-content">
					<div class="wrap_term_use">
						<h3>핫티 서비스 이용약관</h3>
						<h4>제1조(목적)</h4>
						<p>이 약관은 핫티(전자거래 사업자)이 운영하는 HOT:T 몰(이하 "몰"이라 한다)에서 제공하는 인터넷 관련 서비스(이하 "서비스"라 한다)를 이용함에 있어 사이버몰과 이용자의 권리·의무 및 책임사항을 규정함을 목적으로 합니다. <br>
						※ 「PC통신등을 이용하는 전자거래에 대해서도 그 성질에 반하지 않는한 이 약관을 준용합니다」</p>

						<h4>제2조(정의)</h4>
						<p>① "몰"이란  핫티 회사가 재화 또는 용역을 이용자에게 제공하기 위하여 컴퓨터등 정보통신설비를 이용하여 재화 또는 용역을 거래할 수 있도록 설정한 가상의 영업장을 말하며, 아울러 사이버몰을 운영하는 사업자의 의미로도 사용합니다.  <br>
						② "이용자"란 "몰"에 접속하여 이 약관에 따라 "몰"이 제공하는 서비스를 받는 회원 및 비회원을 말합니다.<br>
						③ ‘회원’이라 함은 "몰"에 개인정보를 제공하여 회원등록을 한 자로서, "몰"의 정보를 지속적으로 제공받으며, "몰"이 제공하는 서비스를 계속적으로 이용할 수 있는 자를 말합니다.<br>
						④ ‘비회원’이라 함은 회원에 가입하지 않고 "몰"이 제공하는 서비스를 이용하는 자를 말합니다.</p>

						<h4>제3조(약관등의 명시와 설명 및 개정) </h4>
						<p>① "몰"은 이 약관의 내용과 상호 및 대표자 성명, 영업소 소재지 주소(소비자의 불만을 처리할 수 있는 곳의 주소를 포함), 전화번호·모사전송번호·전자우편주소, 사업자등록번호, 통신판매업신고번호, 개인정보관리책임자등을 이용자가 쉽게 알 수 있도록 "몰"의 초기 서비스화면(전면)에 게시합니다. 다만, 약관의 내용은 이용자가 연결화면을 통하여 볼 수 있도록 할 수 있습니다.<br>
						② "몰"은 이용자가 약관에 동의하기에 앞서 약관에 정하여져 있는 내용 중 청약철회·배송책임·환불조건 등과 같은 중요한 내용을 이용자가 이해할 수 있도록 별도의 연결화면 또는 팝업화면 등을 제공하여 이용자의 확인을 구하여야 합니다.<br>
						③ "몰"은 전자상거래등에서의소비자보호에관한법률, 약관의규제에관한법률, 전자거래기본법, 전자서명법, 정보통신망이용촉진등에관한법률, 방문판매등에관한법률, 소비자보호법 등 관련법을 위배하지 않는 범위에서 이 약관을 개정할 수 있습니다.<br>
						④ "몰"이 약관을 개정할 경우에는 적용일자 및 개정사유를 명시하여 현행약관과 함께 몰의 초기화면에 그 적용일자 7일이전부터 적용일자 전일까지 공지합니다.
						다만, 이용자에게 불리하게 약관내용을 변경하는 경우에는 최소한 30일 이상의 사전 유예기간을 두고 공지합니다.  이 경우 "몰“은 개정전 내용과 개정후 내용을 명확하게 비교하여 이용자가 알기 쉽도록 표시합니다. <br>
						⑤ "몰"이 약관을 개정할 경우에는 그 개정약관은 그 적용일자 이후에 체결되는 계약에만 적용되고 그 이전에 이미 체결된 계약에 대해서는 개정전의 약관조항이 그대로 적용됩니다. 다만 이미 계약을 체결한 이용자가 개정약관 조항의 적용을 받기를 원하는 뜻을 제3항에 의한 개정약관의 공지기간내에 "몰"에 송신하여 "몰"의 동의를 받은 경우에는 개정약관 조항이 적용됩니다.<br>
						⑥ 이 약관에서 정하지 아니한 사항과 이 약관의 해석에 관하여는 전자상거래등에서의 소비자보호에 관한 법률, 약관의 규제 등에 관한 법률, 공정거래위원회가 정하는 전자상거래 등에서의 소비자보호지침 및 관계법령 또는 상관례에 따릅니다.</p>

						<h4>제4조(서비스의 제공 및 변경) </h4>
						<p>① "몰"은 다음과 같은 업무를 수행합니다.<br>
						1. 재화 또는 용역에 대한 정보 제공 및 구매계약의 체결<br>
						2. 구매계약이 체결된 재화 또는 용역의 배송<br>
						3. 기타 "몰"이 정하는 업무<br>
						② "몰"은 재화 또는 용역의 품절 또는 기술적 사양의 변경 등의 경우에는 장차 체결되는 계약에 의해 제공할 재화 또는 용역의 내용을 변경할 수 있습니다. 이 경우에는 변경된 재화 또는 용역의 내용 및 제공일자를 명시하여 현재의 재화 또는 용역의 내용을 게시한 곳에 즉시 공지합니다.<br>
						③ "몰"이 제공하기로 이용자와 계약을 체결한 서비스의 내용을 재화등의 품절 또는 기술적 사양의 변경 등의 사유로 변경할 경우에는 그 사유를 이용자에게 통지 가능한 주소로 즉시 통지합니다.<br>
						④ 전항의 경우 "몰"은 이로 인하여 이용자가 입은 손해를 배상합니다. 다만, "몰"이 고의 또는 과실이 없음을 입증하는 경우에는 그러하지 아니합니다.</p>

						<h4>제5조(서비스의 중단) </h4>
						<p>① "몰"은 컴퓨터 등 정보통신설비의 보수점검·교체 및 고장, 통신의 두절 등의 사유가 발생한 경우에는 서비스의 제공을 일시적으로 중단할 수 있습니다.<br>
						② "몰"은 제1항의 사유로 서비스의 제공이 일시적으로 중단됨으로 인하여 이용자 또는 제3자가 입은 손해에 대하여 배상합니다. 단, "몰"이 고의 또는 과실이 없음을 입증하는 경우에는 그러하지 아니합니다.<br>
						③ 사업종목의 전환, 사업의 포기, 업체간의 통합 등의 이유로 서비스를 제공할 수 없게 되는 경우에는 "몰"은 제8조에 정한 방법으로 이용자에게 통지하고 당초 "몰"에서 제시한 조건에 따라 소비자에게 보상합니다. 다만, "몰"이 보상기준 등을 고지하지 아니한 경우에는 이용자들의 마일리지 또는 적립금 등을 "몰"에서 통용되는 통화가치에 상응하는 현물 또는 현금으로 이용자에게 지급합니다.</p>

						<h4>제6조(회원가입) </h4>
						<p>① 이용자는 "몰"이 정한 가입 양식에 따라 회원정보를 기입한 후 이 약관에 동의한다는 의사표시를 함으로서 회원가입을 신청합니다.<br>
						1. 정보통신망 이용촉진 및 정보보호 등에 관한 법률에 의거하여 자사 서비스간의 원활한 이용을 위해 통합서비스가 추가되었습니다.<br>
						2.  핫티의 모든 사이트를 이용하실 수 있으며 기존 고객의 경우 회원 가입시 기재한 성명, 주소, 연락전화번호, 핸드폰번호, 이메일 주소, 적립금 이 통합되어 운영됩니다.<br>
						3. 신규 회원가입의 경우 통합사이트에 자동으로 가입되며 적립포인트를 각 사이트 구별 없이 사용 및 적립이 가능합니다.<br>
						② "몰"은 제1항과 같이 회원으로 가입할 것을 신청한 이용자 중 다음 각호에 해당하지 않는 한 회원으로 등록합니다.<br>
						1. 가입신청자가 이 약관 제7조제3항에 의하여 이전에 회원자격을 상실한 적이 있는 경우, 다만 제7조제3항에 의한 회원자격 상실후 3년이 경과한 자로서 "몰"의 회원재가입 승낙을 얻은 경우에는 예외로 한다.<br>
						2. 등록 내용에 허위, 기재누락, 오기가 있는 경우<br>
						3. 기타 회원으로 등록하는 것이 "몰"의 기술상 현저히 지장이 있다고 판단되는 경우<br>
						③ 회원가입계약의 성립시기는 "몰"의 승낙이 회원에게 도달한 시점으로 합니다.<br>
						④ 회원은 제15조제1항에 의한 등록사항에 변경이 있는 경우, 즉시 전자우편 기타 방법으로 "몰"에 대하여 그 변경사항을 알려야 합니다.</p>

						<h4>제7조(회원 탈퇴 및 자격 상실 등) </h4>
						<p>① 회원은 "몰"에 언제든지 탈퇴를 요청할 수 있으며 "몰"은 즉시 회원탈퇴를 처리합니다.<br>
						② 회원이 다음 각호의 사유에 해당하는 경우, "몰"은 회원자격을 제한 및 정지시킬 수 있습니다.<br>
						1. 가입 신청시에 허위 내용을 등록한 경우<br>
						2. "몰"을 이용하여 구입한 재화등의 대금, 기타 "몰"이용에 관련하여 회원이 부담하는 채무를 기일에 지급하지 않는 경우<br>
						3. 다른 사람의 "몰" 이용을 방해하거나 그 정보를 도용하는 등 전자상거래 질서를 위협하는 경우<br>
						4. "몰"을 이용하여 법령 또는 이 약관이 금지하거나 공서양속에 반하는 행위를 하는 경우<br>
						③ "몰"이 회원 자격을 제한·정지 시킨후, 동일한 행위가 2회이상 반복되거나 30일이내에 그 사유가 시정되지 아니하는 경우 "몰"은 회원자격을 상실시킬 수 있습니다.<br>
						④ "몰"이 회원자격을 상실시키는 경우에는 회원등록을 말소합니다. 이 경우 회원에게 이를 통지하고, 회원등록 말소전에 최소한 30일 이상의 기간을 정하여 소명할 기회를 부여합니다.</p>

						<h4>제8조(회원에 대한 통지)</h4>
						<p>① "몰"이 회원에 대한 통지를 하는 경우, 회원이 "몰"과 미리 약정하여 지정한 전자우편 주소로 할 수 있습니다.<br>
						② "몰"은 불특정다수 회원에 대한 통지의 경우 1주일이상 "몰" 게시판에 게시함으로서 개별 통지에 갈음할 수 있습니다. 다만, 회원 본인의 거래와 관련하여 중대한 영향을 미치는 사항에 대하여는 개별통지를 합니다.</p>

						<h4>제9조(구매신청)</h4> <p>"몰"이용자는 "몰"상에서 다음 또는 이와 유사한 방법에 의하여 구매를 신청하며, "몰"은 이용자가 구매신청을 함에 있어서 다음의 각 내용을 알기 쉽게 제공하여야 합니다.  단, 회원인 경우 제2호 내지 제4호의 적용을 제외할 수 있습니다.<br>
						1. 재화등의 검색 및 선택<br>
						2. 성명, 주소, 전화번호, 전자우편주소(또는 이동전화번호) 등의 입력<br>
						3. 약관내용, 청약철회권이 제한되는 서비스, 배송료·설치비 등의 비용부담과 관련한 내용에 대한 확인<br>
						4. 이 약관에 동의하고 위 3.호의 사항을 확인하거나 거부하는 표시(예, 마우스 클릭)<br>
						5. 재화등의 구매신청 및 이에 관한 확인 또는 "몰"의 확인에 대한 동의<br>
						6. 결제방법의 선택</p>

						<h4>제10조 (계약의 성립)</h4>
						<p>①  "몰"은 제9조와 같은 구매신청에 대하여 다음 각호에 해당하면 승낙하지 않을 수 있습니다. 다만, 미성년자와 계약을 체결하는 경우에는 법정대리인의 동의를 얻지 못하면 미성년자 본인 또는 법정대리인이 계약을 취소할 수 있다는 내용을 고지하여야 합니다.<br>
						1. 신청 내용에 허위, 기재누락, 오기가 있는 경우<br>
						2. 미성년자가 담배, 주류등 청소년보호법에서 금지하는 재화 및 용역을 구매하는 경우<br>
						3. 기타 구매신청에 승낙하는 것이 "몰" 기술상 현저히 지장이 있다고 판단하는 경우<br>
						② "몰"의 승낙이 제12조제1항의 수신확인통지형태로 이용자에게 도달한 시점에 계약이 성립한 것으로 봅니다.<br>
						③ "몰"의 승낙의 의사표시에는 이용자의 구매 신청에 대한 확인 및 판매가능 여부, 구매신청의 정정 취소등에 관한 정보등을 포함하여야 합니다.</p>

						<h4>제11조(지급방법)</h4> <p>"몰"에서 구매한 재화 또는 용역에 대한 대금지급방법은 다음 각호의 방법중 가용한 방법으로 할 수 있습니다. 단, "몰"은 이용자의 지급방법에 대하여 재화 등의 대금에 어떠한 명목의 수수료도  추가하여 징수할 수 없습니다.<br>
						1. 폰뱅킹, 인터넷뱅킹, 메일 뱅킹 등의 각종 계좌이체 <br>
						2. 선불카드, 직불카드, 신용카드 등의 각종 카드 결제<br>
						3. 온라인무통장입금<br>
						4. 전자화폐에 의한 결제<br>
						5. 수령시 대금지급<br>
						6. 마일리지 등 "몰"이 지급한 포인트에 의한 결제<br>
						7. "몰"과 계약을 맺었거나 "몰"이 인정한 상품권에 의한 결제  <br>
						8. 기타 전자적 지급 방법에 의한 대금 지급 등</p>

						<h4>제12조(수신확인통지·구매신청 변경 및 취소)</h4>
						<p>① "몰"은 이용자의 구매신청이 있는 경우 이용자에게 수신확인통지를 합니다.<br>
						② 수신확인통지를 받은 이용자는 의사표시의 불일치등이 있는 경우에는 수신확인통지를 받은 후 즉시 구매신청 변경 및 취소를 요청할 수 있고 "몰"은 배송전에 이용자의 요청이 있는 경우에는 지체없이 그 요청에 따라 처리하여야 합니다. 다만 이미 대금을 지불한 경우에는 제15조의 청약철회 등에 관한 규정에 따릅니다.</p>

						<h4>제13조(재화등의 공급)</h4>
						<p>① "몰"은 이용자와 재화등의 공급시기에 관하여 별도의 약정이 없는 이상, 이용자가 청약을 한 날부터 7일 이내에 재화 등을 배송할 수 있도록 주문제작, 포장 등 기타의 필요한 조치를 취합니다. 다만, "몰"이 이미 재화 등의 대금의 전부 또는 일부를 받은 경우에는 대금의 전부 또는 일부를 받은 날부터 2영업일 이내에 조치를 취합니다.  이때 "몰"은 이용자가 재화등의 공급 절차 및 진행 사항을 확인할 수 있도록 적절한 조치를 합니다.<br>
						② "몰"은 이용자가 구매한 재화에 대해 배송수단, 수단별 배송비용 부담자, 수단별 배송기간 등을 명시합니다. 만약 "몰"이 약정 배송기간을 초과한 경우에는 그로 인한 이용자의 손해를 배상하여야 합니다. 다만 "몰"이 고의·과실이 없음을 입증한 경우에는 그러하지 아니합니다.</p>

						<h4>제14조(환급)</h4>
						<p>"몰"은 이용자가 구매신청한 재화등이 품절 등의 사유로 인도 또는 제공을 할 수 없을 때에는 지체없이 그 사유를 이용자에게 통지하고 사전에 재화 등의 대금을 받은 경우에는 대금을 받은 날부터 2영업일 이내에 환급하거나 환급에 필요한 조치를 취합니다.</p>

						<h4>제15조(청약철회 등)</h4>
						<p>① "몰"과 재화등의 구매에 관한 계약을 체결한 이용자는 수신확인의 통지를 받은 날부터 7일 이내에는 청약의 철회를 할 수 있습니다.<br>
						② 이용자는 재화등을 배송받은 경우 다음 각호의 1에 해당하는 경우에는 반품 및 교환을 할 수 없습니다.<br>
						1. 이용자에게 책임 있는 사유로 재화 등이 멸실 또는 훼손된 경우(다만, 재화 등의 내용을 확인하기 위하여 포장 등을 훼손한 경우에는 청약철회를 할 수 있습니다)<br>
						2. 이용자의 사용 또는 일부 소비에 의하여 재화 등의 가치가 현저히 감소한 경우<br>
						3. 시간의 경과에 의하여 재판매가 곤란할 정도로 재화등의 가치가 현저히 감소한 경우<br>
						4. 같은 성능을 지닌 재화등으로 복제가 가능한 경우 그 원본인 재화 등의 포장을 훼손한 경우<br>
						③ 제2항제2호 내지 제4호의 경우에 "몰"이 사전에 청약철회 등이 제한되는 사실을 소비자가 쉽게 알 수 있는 곳에 명기하거나 시용상품을 제공하는 등의 조치를 하지 않았다면 이용자의 청약철회등이 제한되지 않습니다.<br>
						④ 이용자는 제1항 및 제2항의 규정에 불구하고 재화등의 내용이 표시·광고 내용과 다르거나 계약내용과 다르게 이행된 때에는 당해 재화등을 공급받은 날부터 3월이내, 그 사실을 안 날 또는 알 수 있었던 날부터 30일 이내에 청약철회 등을 할 수 있습니다.</p>

						<h4>제16조(청약철회 등의 효과)</h4>
						<p>① "몰"은 이용자로부터 재화 등을 반환받은 경우 3영업일 이내에 이미 지급받은 재화등의 대금을 환급합니다. 이 경우 "몰"이 이용자에게 재화등의 환급을 지연한 때에는 그 지연기간에 대하여 공정거래위원회가 정하여 고시하는 지연이자율을 곱하여 산정한 지연이자를 지급합니다.<br>
						② "몰"은 위 대금을 환급함에 있어서 이용자가 신용카드 또는 전자화폐 등의 결제수단으로 재화등의 대금을 지급한 때에는 지체없이 당해 결제수단을 제공한 사업자로 하여금 재화등의 대금의 청구를 정지 또는 취소하도록 요청합니다.<br>
						③ 청약철회등의 경우 공급받은 재화등의 반환에 필요한 비용은 이용자가 부담합니다. "몰"은 이용자에게 청약철회등을 이유로 위약금 또는 손해배상을 청구하지 않습니다. 다만 재화등의 내용이 표시·광고 내용과 다르거나 계약내용과 다르게 이행되어 청약철회등을 하는 경우 재화등의 반환에 필요한 비용은 "몰"이 부담합니다.<br>
						④ 이용자가 재화등을 제공받을때 발송비를 부담한 경우에 "몰"은 청약철회시 그 비용을  누가 부담하는지를 이용자가 알기 쉽도록 명확하게 표시합니다.</p>

						<h4>제17조(개인정보보호)</h4>
						<p>① "몰"은 이용자의 정보수집시 구매계약 이행에 필요한 최소한의 정보를 수집합니다. 다음 사항을 필수사항으로 하며 그 외 사항은 선택사항으로 합니다. <br>
						1. 성명<br>
						2. 주소<br>
						3. 전화번호<br>
						4. 희망ID(회원의 경우)<br>
						5. 비밀번호(회원의 경우)<br>
						6. 전자우편주소(또는 이동전화번호)<br>
						② "몰"이 이용자의 개인식별이 가능한 개인정보를 수집하는 때에는 반드시 당해 이용자의 동의를 받습니다.<br>
						③ 제공된 개인정보는 당해 이용자의 동의없이 목적외의 이용이나 제3자에게 제공할 수 없으며, 이에 대한 모든 책임은 몰이 집니다.  다만, 다음의 경우에는 예외로 합니다.<br>
						1. 배송업무상 배송업체에게 배송에 필요한 최소한의 이용자의 정보(성명, 주소, 전화번호)를 알려주는 경우<br>
						2. 통계작성, 학술연구 또는 시장조사를 위하여 필요한 경우로서 특정 개인을 식별할 수 없는 형태로 제공하는 경우<br>
						3. 재화등의 거래에 따른 대금정산을 위하여 필요한 경우<br>
						4. 도용방지를 위하여 본인확인에 필요한 경우<br>
						5. 법률의 규정 또는 법률에 의하여 필요한 불가피한 사유가 있는 경우<br>
						④ "몰"이 제2항과 제3항에 의해 이용자의 동의를 받아야 하는 경우에는 개인정보관리 책임자의 신원(소속, 성명 및 전화번호, 기타 연락처), 정보의 수집목적 및 이용목적, 제3자에 대한 정보제공 관련사항(제공받은자, 제공목적 및 제공할 정보의 내용) 등 정보통신망이용촉진등에관한법률 제22조제2항이 규정한 사항을 미리 명시하거나 고지해야 하며 이용자는 언제든지 이 동의를 철회할 수 있습니다.<br>
						⑤ 이용자는 언제든지 "몰"이 가지고 있는 자신의 개인정보에 대해 열람 및 오류정정을 요구할 수 있으며 "몰"은 이에 대해 지체없이 필요한 조치를 취할 의무를 집니다. 이용자가 오류의 정정을 요구한 경우에는 "몰"은 그 오류를 정정할 때까지 당해 개인정보를 이용하지 않습니다.<br>
						⑥ "몰"은 개인정보 보호를 위하여 관리자를 한정하여 그 수를 최소화하며 신용카드, 은행계좌 등을 포함한 이용자의 개인정보의 분실, 도난, 유출, 변조 등으로 인한 이용자의 손해에 대하여 모든 책임을  집니다.<br>
						⑦ "몰" 또는 그로부터 개인정보를 제공받은 제3자는 개인정보의 수집목적 또는 제공받은 목적을 달성한 때에는 당해 개인정보를 지체없이 파기합니다.</p>

						<h4>제18조(“몰“의 의무)</h4>
						<p>① "몰"은 법령과 이 약관이 금지하거나 공서양속에 반하는 행위를 하지 않으며 이 약관이 정하는 바에 따라 지속적이고, 안정적으로 재화·용역을 제공하는데 최선을 다하여야 합니다.<br>
						② "몰"은 이용자가 안전하게 인터넷 서비스를 이용할 수 있도록 이용자의 개인정보(신용정보 포함)보호를 위한 보안 시스템을 갖추어야 합니다.<br>
						③ "몰"이 상품이나 용역에 대하여 「표시·광고의공정화에관한법률」 제3조 소정의 부당한 표시·광고행위를 함으로써 이용자가 손해를 입은 때에는 이를 배상할 책임을 집니다.<br>
						④ "몰"은 이용자가 원하지 않는 영리목적의 광고성 전자우편을 발송하지 않습니다.</p>

						<h4>제19조(회원의 ID 및 비밀번호에 대한 의무)</h4>
						<p>① 제17조의 경우를 제외한 ID와 비밀번호에 관한 관리책임은 회원에게 있습니다.<br>
						② 회원은 자신의 ID 및 비밀번호를 제3자에게 이용하게 해서는 안됩니다.<br>
						③ 회원이 자신의 ID 및 비밀번호를 도난당하거나 제3자가 사용하고 있음을 인지한 경우에는 바로 "몰"에 통보하고 "몰"의 안내가 있는 경우에는 그에 따라야 합니다.</p>

						<h4>제20조(이용자의 의무)</h4> <p>이용자는 다음 행위를 하여서는 안됩니다.<br>
						1. 신청 또는 변경시 허위 내용의 등록<br>
						2. 타인의 정보 도용<br>
						3. "몰"에 게시된 정보의 변경<br>
						4. "몰"이 정한 정보 이외의 정보(컴퓨터 프로그램 등) 등의 송신 또는 게시<br>
						5. "몰" 기타 제3자의 저작권 등 지적재산권에 대한 침해<br>
						6. "몰" 기타 제3자의 명예를 손상시키거나 업무를 방해하는 행위<br>
						7. 외설 또는 폭력적인 메시지, 화상, 음성, 기타 공서양속에 반하는 정보를 몰에 공개 또는 게시하는 행위</p>

						<h4>제21조(연결"몰"과 피연결"몰" 간의 관계)</h4>
						<p>① 상위 "몰"과 하위 "몰"이 하이퍼 링크(예: 하이퍼 링크의 대상에는 문자, 그림 및 동화상 등이 포함됨)방식 등으로 연결된 경우, 전자를 연결 "몰"(웹 사이트)이라고 하고 후자를 피연결 "몰"(웹사이트)이라고 합니다.<br>
						② 연결"몰"은 피연결"몰"이 독자적으로 제공하는 재화등에 의하여 이용자와 행하는 거래에 대해서 보증책임을 지지 않는다는 뜻을 연결"몰"의 초기화면 또는 연결되는 시점의 팝업화면으로 명시한 경우에는 그 거래에 대한 보증책임을 지지 않습니다.</p>

						<h4>제22조(저작권의 귀속 및 이용제한)</h4>
						<p>① “몰“이 작성한 저작물에 대한 저작권 기타 지적재산권은 ”몰“에 귀속합니다.<br>
						② 이용자는 "몰"을 이용함으로써 얻은 정보 중 "몰"에게 지적재산권이 귀속된 정보를 "몰"의 사전 승낙없이 복제, 송신, 출판, 배포, 방송 기타 방법에 의하여 영리목적으로 이용하거나 제3자에게 이용하게 하여서는 안됩니다.<br>
						③ "몰"은 약정에 따라 이용자에게 귀속된 저작권을 사용하는 경우 당해 이용자에게 통보하여야 합니다.</p>

						<h4>제23조(분쟁해결)</h4>
						<p>① "몰"은 이용자가 제기하는 정당한 의견이나 불만을 반영하고 그 피해를 보상처리하기 위하여 피해보상처리기구를 설치·운영합니다.<br>
						② "몰"은 이용자로부터 제출되는 불만사항 및 의견은 우선적으로 그 사항을 처리합니다. 다만, 신속한 처리가 곤란한 경우에는 이용자에게 그 사유와 처리일정을 즉시 통보해 드립니다.<br>
						③ "몰"과 이용자간에 발생한 전자상거래 분쟁과 관련하여 이용자의 피해구제신청이 있는 경우에는 공정거래위원회 또는 시·도지사가 의뢰하는 분쟁조정기관의 조정에 따를 수 있습니다.</p>

						<h4>제24조(재판권 및 준거법)</h4>
						<p>① "몰"과 이용자간에 발생한 전자상거래 분쟁에 관한 소송은 제소 당시의 이용자의 주소에 의하고, 주소가 없는 경우에는 거소를 관할하는 지방법원의 전속관할로 합니다. 다만, 제소 당시 이용자의 주소 또는 거소가 분명하지 않거나 외국 거주자의 경우에는 민사소송법상의 관할법원에 제기합니다.<br>
						② "몰"과 이용자간에 제기된 전자상거래 소송에는 한국법을 적용합니다.</p>

						<h4>부칙</h4>
						<p>1. 이 약관은 2014년 9월 29일부터 적용됩니다. </p>
					<button type="button" class="btn-point">확인</button>
					</div>
				</div>
			</div>
		</div>
		<!-- //핫티 서비스 이용약관 레이어팝업 -->

		<!-- 개인정보 수집∙이용 동의 레이어팝업 -->
		<div class="layer-dimm-wrap layer_term_use02">
			<div class="dimm-bg"></div>
			<div class="layer-inner">
				<h3 class="layer-title">개인정보 수집 ∙ 이용 동의</h3>
				<button type="button" class="btn-close">창 닫기 버튼</button>
				<div class="layer-content">
					<div class="wrap_term_use">
						<h3>개인정보 수집 ∙ 이용 동의</h3>
						<h4>개인정보의 수집목적 및 이용목적</h4>
						<p>① 핫티 는 회원님께 최대한으로 최적화되고 맞춤화된 서비스를 제공하기 위하여 다음과 같은 목적으로 개인정보를 수집하고 있습니다. <br>
						- 성명, 아이디, 비밀번호 : 회원제 서비스 이용에 따른 본인 식별 절차에 이용 <br>
						- 이메일주소, 이메일 수신여부, 전화번호 : 고지사항 전달, 본인 의사 확인, 불만 처리 등 원활한 의사소통 경로의 확보, 새로운 서비스/신상품이나 이벤트 정보의 안내 <br>
						- 주소, 전화번호 : 경품과 쇼핑 물품 배송에 대한 정확한 배송지의 확보 <br>
						- 비밀번호 힌트용 질문과 답변 : 비밀번호를 잊은 경우의 신속한 처리를 위한 내용 <br>
						- 그 외 선택항목 : 개인맞춤 서비스를 제공하기 위한 자료 ② 단, 이용자의 기본적 인권 침해의 우려가 있는 민감한 개인정보(인종 및 민족, 사상 및 신조, 출신지 및 본적지, 정치적 성향 및 범죄기록, 건강상태 및 성생활 등)는 수집하지 않습니다.</p>
						<h4>개인정보의 수집범위</h4>
						<p>핫티 은 별도의 회원가입 절차 없이 대부분의 컨텐츠에 자유롭게 접근할 수 있습니다.핫티 의 회원제 서비스를 이용하시고자 할 경우 다음의 정보를 입력해주셔야 하며 선택항목을 입력하시지 않았다 하여 서비스 이용에 제한은 없습니다. <br>
						1) 회원 가입시 수집하는 개인정보의 범위 <br>
						- 필수항목 : 희망 ID, 비밀번호, 비밀번호 힌트용 질문과 답변, 성명, 주소, 전화번호, 이메일주소, 이메일 수신 여부 <br>
						- 선택항목 : 생년월일, 결혼여부, 결혼기념일</p>
						<h4>비회원의 고객 개인정보보호</h4>
						<p>핫티은 회원 뿐만 아니라 비회원 또한 물품 및 서비스 상품의 구매를 하실 수 있습니다.핫티은 비회원 주문의 경우 배송 및 대금 결제, 상품 배송에 반드시 필요한 개인정보 만을 고객에게 요청하고 있습니다.<br>
						핫티 에서 비회원으로 구매를 하신 경우 비회원 고객께서 입력하신 지불인 정보 및 수령인 정보는 대금 결제 및 상품 배송에 관련한 용도 외에는 다른 어떠한 용도로도 사용되지 않습니다. <br>
						핫티 비회원의 경우도핫티 회원과 동일하게 개인정보를 보호합니다.</p>
						<h4>쿠키에 의한 개인정보 수집</h4>
						<p>① 쿠키(cookie)란? <br>
						핫티 는 귀하에 대한 정보를 저장하고 수시로 찾아내는 쿠키(cookie)를 사용합니다. 쿠키는 웹사이트가 귀하의 컴퓨터 브라우저(넷스케이프, 인터넷 익스플로러 등)로 전송하는 소량의 정보입니다. 귀하께서 웹사이트에 접속을 하면 핫티 의 컴퓨터는 귀하의 브라우저에 있는 쿠키의 내용을 읽고, 귀하의 추가정보를 귀하의 컴퓨터에서 찾아 접속에 따른 성명 등의 추가 입력 없이 서비스를 제공할 수 있습니다. 쿠키는 귀하의 컴퓨터는 식별하지만 귀하를 개인적으로 식별하지는 않습니다. 또한 귀하는 쿠키에 대한 선택권이 있습니다. 웹브라우저 상단의 도구 > 인터넷옵션 탭(option tab)에서 모든 쿠키를 다 받아들이거나, 쿠키가 설치될 때 통지를 보내도록 하거나, 아니면 모든 쿠키를 거부할 수 있는 선택권을 가질 수 있습니다. <br>
						② 핫티 의 쿠키(cookie) 운용 <br>
						핫티 는 이용자의 편의를 위하여 쿠키를 운영합니다. 핫티 이 쿠키를 통해 수집하는 정보는 핫티 회원 ID에 한하며, 그 외의 다른 정보는 수집하지 않습니다. 핫티 이 쿠키(cookie)를 통해 수집한 회원 ID는 다음의 목적을 위해 사용됩니다. <br>
						- 개인의 관심 분야에 따라 차별화된 정보를 제공 <br>
						- 회원과 비회원의 접속빈도 또는 머문시간 등을 분석하여 이용자의 취향과 관심분야를 파악하여 타겟(target) 마케팅에 활용 <br>
						- 쇼핑한 품목들에 대한 정보와 관심있게 둘러본 품목들에 대한 자취를 추적하여 다음번 쇼핑 때 개인 맞춤 서비스를 제공 <br>
						- 회원들의 습관을 분석하여 서비스 개편 등의 척도 <br>
						- 게시판 글 등록 <br>
						쿠키는 브라우저의 종료시나 로그아웃시 만료됩니다.</p>
						<h4>개인정보의 보유기간 및 이용기간</h4>
						<p>① 귀하의 개인정보는 다음과 같이 개인정보의 수집목적 또는 제공받은 목적이 달성되면 파기됩니다. 단, 상법 등 관련법령의 규정에 의하여 다음과 같이 거래 관련 권리 의무 관계의 확인 등을 이유로 일정기간 보유하여야 할 필요가 있을 경우에는 일정기간 보유합니다. <br>
						- 회원가입정보의 경우, 회원가입을 탈퇴하거나 회원에서 제명된 경우 등 일정한 사전에 보유목적, 기간 및 보유하는 개인정보 항목을 명시하여 동의를 구합니다. <br>
						- 계약 또는 청약철회 등에 관한 기록 : 5년 <br>
						- 대금결제 및 재화등의 공급에 관한 기록 : 5년 <br>
						- 소비자의 불만 또는 분쟁처리에 관한 기록 : 3년 <br>
						② 귀하의 동의를 받아 보유하고 있는 거래정보 등을 귀하께서 열람을 요구하는 경우 핫티은 지체없이 그 열람,확인 할 수 있도록 조치합니다.</p>
						<button type="button" class="btn-point">확인</button>
					</div>
				</div>
			</div>
		</div>
		<!-- //개인정보 수집∙이용 동의 레이어팝업 -->

		<!-- 마케팅 정보 수신 동의 레이어팝업 -->
		<div class="layer-dimm-wrap layer_term_use03">
			<div class="dimm-bg"></div>
			<div class="layer-inner">
				<h3 class="layer-title">마케팅 정보 수신 동의</h3>
				<button type="button" class="btn-close">창 닫기 버튼</button>
				<div class="layer-content">
					<div class="wrap_term_use">
						<h3>마케팅 정보 수신 동의</h3>
						<p>1. 개인정보의 수집항목 회사는 회원가입, 주문, 배송, 상담, 기타 서비스 등 회원에게 최적의 서비스를 제공하기 위해 아래와 같은 개인정보를 수집하고 있습니다. 단, 이용자의 기본적 인권침해의 우려가 있는 민감한 개인정보(인종, 사상, 신조, 출신지, 정치적성향, 범 1. 개인정보의 수집항목 회사는 회원가입, 주문, 배송, 상담, 기타 서비스 등 회원에게 최적의 서비스를 제공하기 위해 아래와 같은 개인정보를 수집하고 있습니다. 단, 이용자의 기본적 인권침해의 우려가 있는 민감한 개인정보(인종, 사상, 신조, 출신지, 정치적성향, 범1. 개인정보의 수집항목 회사는 회원가입, 주문, 배송, 상담, 기타 서비스 등 회원에게 최적의 서비스를 제공하기 위해 아래와 같은 개인정보를 수집하고 있습니다. 단, 이용자의 기본적 인권침해의 우려가 있는 민감한 개인정보(인종, 사상, 신조, 출신지, 정치적성향, 범 1. 개인정보의 수집항목 회사는 회원가입, 주문, 배송, 상담, 기타 서비스 등 회원에게 최적의 서비스를 제공하기 위해 아래와 같은 개인정보를 수집하고 있습니다. 단, 이용자의 기본적 인권침해의 우려가 있는 민감한 개인정보(인종, 사상, 신조, 출신지, 정치적성향, 범</p>
						<button type="button" class="btn-point">확인</button>
					</div>
				</div>
			</div>
		</div>
		<!-- //마케팅 정보 수신 동의 레이어팝업 -->

		<section class="top_title_wrap">
			<h2 class="page_local">
				<a href="javascript:history.back();" class="prev"></a>
				<span>핫티 간편가입</span>
				<a href="/m/shop.php" class="home"></a>
			</h2>
		</section>

		<div class="member_page">
			<!-- <div class="inner">
				<div class="join-flow">
					<img src="./static/img/common/join_flow02.gif" alt="02.회원정보 입력">
					<h3 class="title hide">회원정보 입력</h3>
					<p>회원정보는 개인정보 보호방침.취급방침에 따라<br>안전하게 보호됩니다.</p>
				</div>
			</div>

			<div class="line-title">기본정보 입력<span class="point">항목은 필수 입력 항목입니다.</span></div>
			<div class="join-form">
				<ul>
					<li>
						<label for="join-id">아이디</label>
						<div class="input-cover">
							<?if($_POST["sns_email"]){?>
							<strong><?=$_POST["sns_email"]?></strong><input type="hidden" id="join-id" name="id" value = '<?=$_POST["sns_email"]?>' title='아이디를 입력하세요.'>
							<?}else{?>
							<div class="input"><input type="text" id="join-id" name="id" value = '<?=$_POST["sns_email"]?>' title='아이디를 입력하세요.'></div>
							<button class="btn-def" type='button' onClick="javascript:ValidFormId('chk')"><span>중복확인</span></button>
							<?}?>
						</div>
					</li>
					<li>
						<label for="join-pw1">비밀번호</label>
						<input type="password" class="w100-per" id="join-pw1" name="passwd1" value = '<?=$passwd1?>' title="비밀번호를 입력하세요." >
						<p class="att-ment">※ 영문, 숫자 2가지 조합하여 4~20자리로 만들어 주세요</p>
					</li>
					<li>
						<label for="join-pw2">비밀번호 확인</label>
						<input type="password" class="w100-per" id="join-pw2" name="passwd2" value = '<?=$passwd2?>' title="비밀번호를 한 번 더 입력하세요.">
					</li>
					<li>
						<label for="join-id">이름</label>
						<?if($_POST["sns_name"]){?>
						<strong><?=$_POST["sns_name"]?></strong><input type="hidden" id="join-name" name="name" value="<?=$_POST["sns_name"]?>" title="이름을 입력하세요." >
						<?}else{?>
						<input type="text" id="join-name" name="name" value="<?=$_POST["sns_name"]?>" title="이름을 입력하세요." >
						<?}?>
					</li>
					<li>
						<label for="mobile2">휴대폰 번호</label>
						<div class="tel-input">
							<div class="select-def">
								<select name='mobile1' id = 'mobile1' title="휴대폰번호를 입력하세요.">
									<option value="010">010</option>
									<option value="011">011</option>
									<option value="016">016</option>
									<option value="017">017</option>
									<option value="018">018</option>
									<option value="019">019</option>
								</select>
							</div>
							<div><input type="tel" name='mobile2' id="mobile2" title="휴대폰번호를 입력하세요."></div>
							<div><input type="tel" name='mobile3' id = 'mobile3' title="휴대폰번호를 입력하세요."></div>
						</div>
					</li>
					<li>
						<div class="mrk-agree">
							<div>
								<p>메일 수신여부</p>
								<input type="radio" id="mail-agree1"  name='news_mail_yn' id="" value="Y">
								<label for="mail-agree1">수신</label>
								<input type="radio" id="mail-agree2"  name='news_mail_yn' id="" value="N" checked>
								<label for="mail-agree2">비수신</label>
							</div>
							<div>
								<p>SMS 수신여부</p>
								<input type="radio" id="sms-agree1" name='news_sms_yn' id="" value="Y">
								<label for="sms-agree1">수신</label>
								<input type="radio" id="sms-agree2" name='news_sms_yn' id="" value="N" checked>
								<label for="sms-agree2">비수신</label>
							</div>
						</div>
					</li>
				</ul>
			</div> -->
			<div class="order_table">
				<table class="my-th-left form_table">
					<colgroup>
						<col style="width:30%;">
						<col style="width:70%;">
					</colgroup>
					<tbody>
						<tr>
							<th>이메일(ID)</th>
							<td class="email_check">
								<input type="email" title="" value="fwenifwneg@naver.com">
								<button type="button" class="btn-def">중복확인</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<section class="member_join_simple">
				<header>
					<h3>이용약관</h3>
					<p>이용약관과 개인정보 취급방침은 서비스 사이트 이용 및 상품 매매 규정사항입니다. 가입 전에 반드시 읽어보시고, 동의하셔야 회원가입이 완료됩니다.</p>
				</header>

				<div class="wrap_terms">
					<ul>
						<li class="term01">
							<div><input type="checkbox" id="term01" class="checkbox_custom"> <label for="term01">이용약관</label></div>
							<a href="javascript:;" class="term_view">내용보기</a>
						</li>
						<li class="term02">
							<div><input type="checkbox" id="term02" class="checkbox_custom"> <label for="term02">개인정보 수집 ∙ 이용 동의</label></div>
							<a href="javascript:;" class="term_view">내용보기</a>
						</li>
						<li class="term03">
							<div><input type="checkbox" id="term03" class="checkbox_custom"> <label for="term03">마케팅 정보 수신 동의</label></div>
							<a href="javascript:;" class="term_view">내용보기</a>
						</li>
						<li>
							<div class="term_all"><input type="checkbox" id="agreeAll" class="checkbox_custom"> <label for="agreeAll">모든 약관에 동의합니다.</label></div>
						</li>
					</ul>
				</div>
			</section>

			<!-- <div class="btnwrap page-end">
				<div class="box">
					<a class="btn-def" href="javascript:CheckForm('<?=$mem_type?>');">가입완료</a>
					<a class="btn-function" href="member_agree.php">취소</a>
				</div>
			</div> -->
			<div class="btnwrap">
				<ul class="ea1">
					<li><a class="btn-point" href="javascript:CheckForm('<?=$mem_type?>');">저장</a></li>
				</ul>
			</div>


		</div><!-- //.member-wrap -->
	</form>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    // 우편번호 찾기 찾기 화면을 넣을 element
    var element_layer = document.getElementById('addressWrap');

    function foldDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
        element_layer.style.display = 'none';
    }

    function openDaumPostcode() {
        // 현재 scroll 위치를 저장해놓는다.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('home_zonecode').value = data.zonecode; //5자리 새우편번호 사용
 				document.getElementById('home_post1').value = data.postcode1;
 				document.getElementById('home_post2').value = data.postcode2;
                document.getElementById('home_addr1').value = fullAddr;
 				document.getElementById('home_addr2').value = '';
	 			document.getElementById('home_addr2').focus();

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_layer.style.display = 'none';

                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                document.body.scrollTop = currentScroll;
            },
            // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
            onresize : function(size) {
            		//console.log("Size:", size, element_layer)
                //element_layer.style.height = size.height+'px';
            },
            width : '100%',
            height : '100%'
        }).embed(element_layer);

        // iframe을 넣은 element를 보이게 한다.
        element_layer.style.display = 'block';

        // iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
        initLayerPosition();
    }

    // 브라우저의 크기 변경에 따라 레이어를 가운데로 이동시키고자 하실때에는
    // resize이벤트나, orientationchange이벤트를 이용하여 값이 변경될때마다 아래 함수를 실행 시켜 주시거나,
    // 직접 element_layer의 top,left값을 수정해 주시면 됩니다.
    function initLayerPosition(){
        var width = (window.innerWidth || document.documentElement.clientWidth)-20; //우편번호서비스가 들어갈 element의 width
        var height = (window.innerHeight || document.documentElement.clientHeight)-200; //우편번호서비스가 들어갈 element의 height
        var borderWidth = 1; //샘플에서 사용하는 border의 두께

        // 위에서 선언한 값들을 실제 element에 넣는다.
        element_layer.style.width = width + 'px';
        element_layer.style.height = height + 'px';
        element_layer.style.border = borderWidth + 'px solid';
        // 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
        element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
        element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
    }
</script>

<IFRAME name="HiddenFrame" width=0 height=0 frameborder=0 scrolling="no" marginheight="0" marginwidth="0"></IFRAME>

<? include_once('outline/footer_m.php'); ?>
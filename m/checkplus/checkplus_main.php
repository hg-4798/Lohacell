<?php
	$Dir="../../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata2.php");
    //**************************************************************************************************************
    //NICE신용평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED

    //서비스명 :  체크플러스 - 안심본인인증 서비스
    //페이지명 :  체크플러스 - 메인 호출 페이지

    //보안을 위해 제공해드리는 샘플페이지는 서비스 적용 후 서버에서 삭제해 주시기 바랍니다.
    //**************************************************************************************************************

    $auth_type  = $_GET['auth_type']?$_GET['auth_type']:$_POST['auth_type'];
    $mem_type   = $_GET['mem_type']?$_GET['mem_type']:$_POST['mem_type'];
    $find_type  = $_GET['find_type']?$_GET['find_type']:$_POST['find_type'];
    $cert_type  = $_GET['cert_type']?$_GET['cert_type']:$_POST['cert_type'];
    $join_type  = $_GET['join_type']?$_GET['join_type']:$_POST['join_type'];
    $staff_join  = $_GET['staff_join']?$_GET['staff_join']:$_POST['staff_join'];
    $cooper_join  = $_GET['cooper_join']?$_GET['cooper_join']:$_POST['cooper_join'];
    $sns_type  = $_GET['sns_type']?$_GET['sns_type']:$_POST['sns_type'];

	//erp 오프라인 회원 정보 data
    $erp_member_data  = $_GET['erp_member_data']?encrypt_md5($_GET['erp_member_data']):encrypt_md5($_POST['erp_member_data']);

	//회원 수정시 data
    $mod_user_data  = encrypt_md5($_GET['mod_user_data']);

	//회원 탈퇴시 data
    $out_user_data  = encrypt_md5($_GET['out_user_data']);

    session_start();

//	print_r($_SESSION);

    $sitecode = $_data->realname_id;				// NICE로부터 부여받은 사이트 코드
    $sitepasswd = $_data->realname_password;			// NICE로부터 부여받은 사이트 패스워드

	$self_filename = basename($_SERVER['PHP_SELF']);
	$loc = strpos($_SERVER['PHP_SELF'], $self_filename);
	$loc = substr($_SERVER['PHP_SELF'], 0, $loc);

	$Port = ($_SERVER['SERVER_PORT'] == 80) ? "" : $_SERVER['SERVER_PORT'];
	if (strlen($Port) > 0) $Port = ":".$Port;
	$Protocol = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';


	//$cb_encode_path = $_SERVER[DOCUMENT_ROOT].$loc_front."CPClient";		// NICE로부터 받은 암호화 프로그램의 위치 (절대경로+모듈명)
	$cb_encode_path = $_SERVER['DOCUMENT_ROOT']."/front/checkplus/CPClient";

//	echo $cb_encode_path;

    $authtype = "M";      	// 없으면 기본 선택화면, X: 공인인증서, M: 핸드폰, C: 카드

		$popgubun 	= "N";		//Y : 취소버튼 있음 / N : 취소버튼 없음
		$customize 	= "Mobile";		//없으면 기본 웹페이지 / Mobile : 모바일페이지

    $reqseq = "REQ_0123456789";     // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로
                                    // 업체에서 적절하게 변경하여 쓰거나, 아래와 같이 생성한다.
    $reqseq = `$cb_encode_path SEQ $sitecode`;

    // CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
	if ($mod_user_data) {
		$form_data = urlencode("user_modify||{$mod_user_data}");
	} else if ($out_user_data) {
		$form_data = urlencode("user_out||{$out_user_data}");
	} else if ($erp_member_data) {
		$form_data = urlencode("erp_mem_join||{$auth_type}||{$mem_type}||${join_type}||{$staff_join}||{$cooper_join}||{$erp_member_data}");
	} else {
		$form_data = urlencode("{$auth_type}||{$mem_type}||${find_type}||{$cert_type}||{$join_type}||{$sns_type}");
	}

    $returnurl = $Protocol.$_SERVER['HTTP_HOST'].$Port.$loc."checkplus_success.php?form_data={$form_data}";	// 성공시 이동될 URL
    $errorurl = $Protocol.$_SERVER['HTTP_HOST'].$Port.$loc."checkplus_fail.php";		// 실패시 이동될 URL

    // reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.

    $_SESSION["REQ_SEQ"] = $reqseq;

    // 입력될 plain 데이타를 만든다.
    $plaindata =  "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
			    			  "8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
			    			  "9:AUTH_TYPE" . strlen($authtype) . ":". $authtype .
			    			  "7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
			    			  "7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
			    			  "11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
			    			  "9:CUSTOMIZE" . strlen($customize) . ":" . $customize ;

//							  echo $plaindata;

    $enc_data = `$cb_encode_path ENC $sitecode $sitepasswd $plaindata`;

    if( $enc_data == -1 )
    {
        $returnMsg = "암/복호화 시스템 오류입니다.";
        $enc_data = "";
    }
    else if( $enc_data== -2 )
    {
        $returnMsg = "암호화 처리 오류입니다.";
        $enc_data = "";
    }
    else if( $enc_data== -3 )
    {
        $returnMsg = "암호화 데이터 오류 입니다.";
        $enc_data = "";
    }
    else if( $enc_data== -9 )
    {
        $returnMsg = "입력값 오류 입니다.";
        $enc_data = "";
    }
?>


<html>
<head>
	<title>NICE신용평가정보 - CheckPlus 안심본인인증 테스트</title>

	<script language='javascript'>
	window.name ="Parent_window";

	function fnPopup(){
		// window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
//		document.form_chk.target = "popupChk";
		document.form_chk.submit();
	}

	</script>
</head>
<body onload="fnPopup();">
  <div style="width: 100%;height: 100%;position: absolute;top: 0px;z-index: 1;background-color: white;"></div>

	<!-- 본인인증 서비스 팝업을 호출하기 위해서는 다음과 같은 form이 필요합니다. -->
	<form name="form_chk" method="post">
		<input type="hidden" name="m" value="checkplusSerivce">						<!-- 필수 데이타로, 누락하시면 안됩니다. -->
		<input type="hidden" name="EncodeData" value="<?= $enc_data ?>">		<!-- 위에서 업체정보를 암호화 한 데이타입니다. -->

	    <!-- 업체에서 응답받기 원하는 데이타를 설정하기 위해 사용할 수 있으며, 인증결과 응답시 해당 값을 그대로 송신합니다.
	    	 해당 파라미터는 추가하실 수 없습니다. -->
		<input type="hidden" name="param_r1" value="">
		<input type="hidden" name="param_r2" value="">
		<input type="hidden" name="param_r3" value="">

		<!-- <a href="javascript:fnPopup();"> CheckPlus 안심본인인증 Click</a> -->
	</form>
</body>
</html>

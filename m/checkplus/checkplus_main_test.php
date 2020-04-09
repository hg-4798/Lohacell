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

    $auth_type  = $_GET['auth_type'];
    $mem_type   = $_GET['mem_type'];
    $find_type  = $_GET['find_type'];
    $cert_type  = $_GET['cert_type'];
    $join_type  = $_GET['join_type'];

	//회원 수정시 data
    $mod_user_data  = encrypt_md5($_GET['mod_user_data']);

	//회원 탈퇴시 data
    $out_user_data  = encrypt_md5($_GET['out_user_data']);

    session_start();

    // CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
	if ($mod_user_data) {
		$form_data = urlencode("user_modify||{$mod_user_data}");
	} else if ($out_user_data) {
		$form_data = urlencode("user_out||{$out_user_data}");
	} else {
		$form_data = urlencode("{$auth_type}||{$mem_type}||${find_type}||{$cert_type}||{$join_type}");
	}
    
?>


<html>
<head>
	<title>NICE신용평가정보 - CheckPlus 안심본인인증 테스트</title>

	<script language='javascript'>
	window.name ="Parent_window";

	function fnPopup(){
		// window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		document.form_chk.action = "checkplus_success_test.php?form_data=<?=$form_data?>";
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

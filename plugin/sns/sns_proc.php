<?php
	session_start();

	#---------------------------------------------------------------
	# 기본정보 설정파일을 가져온다.
	#---------------------------------------------------------------
	$Dir="../../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");
	include_once($Dir."conf/config.sns.php");


	$acDir		= $_ShopInfo->getCheckSnsAccess();
    $acType	= $acDir=='m'?'M':'P';

	$frmAction = "/{$acDir}/member_certi.php";

	if($_ShopInfo->getCheckSns() == "fb"){
		// library 로드, 변수 설정 등
        include_once($Dir."plugin/sns/facebook/src/facebookoauth.php");
		include_once($Dir."plugin/sns/facebook/fb.lib.php");

		$user = connectFacebookUser($snsFbConfig["authKey"], $snsFbConfig["secretKey"]);

		if($user -> email && $user -> name){		 
			$sns_id			= $user -> id;
			$sns_name		= $user -> name;
			$sns_email		= $user -> email;

			
			$sns_chk_data = $_ShopInfo->getCheckSns()."||".$sns_id;
			if($_ShopInfo->getCheckSnsLogin()){
				$frmAction = "/proc/login.proc.php";

                if($_ShopInfo->memid){
                    //echo $_ShopInfo->memid;exit;
                    $_ShopInfo->checksnschurl = "/front/mypage_sns.php";
                    $sns_sql = "UPDATE tblmember SET sns_type = '{$sns_chk_data}' WHERE id = '{$_ShopInfo->memid}'";
                    pmysql_query($sns_sql,get_db_conn());

                    $sns_facebook = "INSERT INTO tblmember_sns(id,name,sns_email,date_insert,sns_type) VALUES (
							'{$_ShopInfo->memid}',
							'{$_ShopInfo->memname}',
							'{$sns_email}',
							'{NOW}',
							'FACEBOOK') ";

                    //echo $sns_naver;exit;
                    pmysql_query($sns_facebook,get_db_conn());
                    if($acType=='M'){
                        echo "<script> ";
                        echo "location.href='/m/mypage_sns.php';";
                        echo "</script>";

                    }else{
                        echo "<script> ";
                        echo "opener.parent.location='/front/mypage_sns.php';";
                        echo"window.close();";
                        echo "</script>";
                    }
                }
			} else {
				list($memId) = pmysql_fetch_array(pmysql_query("SELECT id FROM tblmember WHERE id='".$sns_email."' OR sns_type='".$sns_chk_data."' "));
				if($memId) {
					$memId_exp	= explode("@", $memId);
					$memId=substr($memId_exp[0],0,-4)."****"."@".$memId_exp[1];
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
					echo '<script> ';
					echo 'if (window.opener == null){';
					echo "	alert('".$sns_name." 고객님께서는 [".$memId."]로 이미 가입하셨습니다.');location.href='/{$acDir}/login.php';";
					echo '} else {';
					echo "	opener.sns_alert('".$sns_name."', '".$memId."');";
					echo "	window.close();";
					echo "}";
					echo '</script>';

					exit;
				}
			}

            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
			echo "	<form action='".$frmAction."' method='POST' name='frmSns'>";
			echo "		<input type='hidden' name='action_mode' value='".($_ShopInfo->getCheckSnsLogin()?'SNS_LOGIN':'')."'>";
			echo "		<input type='hidden' name='sns_login' value='".($_ShopInfo->getCheckSnsLogin()?'1':'')."'>";
			echo "		<input type='hidden' name='sns_id' value='".$sns_id."'>";
			echo "		<input type='hidden' name='sns_name' value='".$sns_name."'>";
			echo "		<input type='hidden' name='sns_email' value='".$sns_email."'>";
			echo "		<input type='hidden' name='sns_type' value='".$snsFbConfig["use"]."'>";
			echo "		<input type='hidden' name='sns_token' value='".$token."'>";
            echo "		<input type='hidden' name='act' value='sns'>";
            echo "		<input type='hidden' name='mode' value='login'>";
            echo "		<input type='hidden' name='chUrl' value='".$_ShopInfo->checksnschurl."'>";
			echo "	</form>";
			echo '<script> ';
			echo 'if (window.opener == null){';
			echo "	document.frmSns.submit();";
			echo '} else {';
			echo "	opener.document.frmSns.action='".$frmAction."';";
			echo "	opener.document.frmSns.sns_id.value='".$sns_id."';";
			echo "	opener.document.frmSns.sns_name.value='".$sns_name."';";
			echo "	opener.document.frmSns.sns_email.value='".$sns_email."';";
			echo "	opener.document.frmSns.sns_type.value='".$snsFbConfig["use"]."';";
			echo "	opener.document.frmSns.sns_token.value='".$token."';";
            echo "	opener.document.frmSns.mode.value='login';";
            echo "	opener.document.frmSns.act.value='sns';";
			echo "	opener.document.frmSns.submit();";
			echo "	window.close();";
			echo "}";
			echo '</script>';

			exit;
		}else{
			$onload="페이스북 사용권한을 확인 해주셔야 이용가능합니다.";
			if(ord($onload)) {
				if($acType=='M') 
					alert_go($onload,'-1');					
				else
					alert_close($onload);
			}
		}
	}else if($_ShopInfo->getCheckSns() == "nv"){
		include_once("./naver/class.naverOAuth.php");
	
		$nid_ClientID = $snsNvConfig['clientId'];
		$nid_ClientSecret = $snsNvConfig['clientSecret'];
		$nid_RedirectURL = $snsNvConfig['callbackUrl'];
		$request = new OAuthRequest( $nid_ClientID, $nid_ClientSecret, $nid_RedirectURL );
		$request -> call_accesstoken();
		$request -> get_user_profile();
		$userData = $request -> get_userInfo();

        
		if($userData['userID']){


			$sns_chk_data = $_ShopInfo->getCheckSns()."||".$userData['userID'];
			if($_ShopInfo->getCheckSnsLogin()){
                $frmAction = "/proc/login.proc.php";
                if($_ShopInfo->memid){
                    //echo $_ShopInfo->memid;exit;
                    $_ShopInfo->checksnschurl = "/front/mypage_sns.php";
                    $sns_sql = "UPDATE tblmember SET sns_type = '{$sns_chk_data}' WHERE id = '{$_ShopInfo->memid}'";
                    pmysql_query($sns_sql,get_db_conn());

                    $sns_naver = "INSERT INTO tblmember_sns(id,name,sns_email,date_insert,sns_type) VALUES (
							'{$_ShopInfo->memid}',
							'{$_ShopInfo->memname}',
							'{$userData['email']}',
							'{NOW}',
							'NAVER') ";

                    //echo $sns_naver;exit;
                    pmysql_query($sns_naver,get_db_conn());
                    if($acType=='M'){
                        echo "<script> ";
                        echo "location.href='/m/mypage_sns.php';";
                        echo "</script>";

                    }else{
                        echo "<script> ";
                        echo "opener.parent.location='/front/mypage_sns.php';";
                        echo"window.close();";
                        echo "</script>";
                    }
                }
			} else {

				list($memId) = pmysql_fetch_array(pmysql_query("SELECT id FROM tblmember WHERE id='".$userData['email']."' OR sns_type='".$sns_chk_data."' "));
				if($memId) {
					$memId_exp	= explode("@", $memId);
					$memId=substr($memId_exp[0],0,-4)."****"."@".$memId_exp[1];
					echo '<script> ';
					echo 'if (window.opener == null){';
					echo "	alert('".$userData['name']." 고객님께서는 [".$memId."]로 이미 가입하셨습니다.');location.href='/{$acDir}/login.php';";
					echo '} else {';
					echo "	opener.sns_alert('".$userData['name']."', '".$memId."');";
					echo "	window.close();";
					echo "}";
					echo '</script>';

					exit;
				}
			}

			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
			echo "	<form action='".$frmAction."' method='POST' name='frmSns'>";
			echo "		<input type='hidden' name='action_mode' value='".($_ShopInfo->getCheckSnsLogin()?'SNS_LOGIN':'')."'>";
			echo "		<input type='hidden' name='sns_login' value='".($_ShopInfo->getCheckSnsLogin()?'1':'')."'>";
			echo "		<input type='hidden' name='sns_id' value='".$userData['userID']."'>";
			echo "		<input type='hidden' name='sns_name' value='".$userData['name']."'>";
			echo "		<input type='hidden' name='sns_email' value='".$userData['email']."'>";
			echo "		<input type='hidden' name='sns_type' value='".$snsNvConfig["use"]."'>";
            echo "		<input type='hidden' name='sns_token' value=''>";
            echo "		<input type='hidden' name='act' value='sns'>";
            echo "		<input type='hidden' name='mode' value='login'>";
            echo "		<input type='hidden' name='chUrl' value='".$_ShopInfo->checksnschurl."'>";
			echo "	</form>";
			echo '<script> ';
			echo 'if (window.opener == null){';
			echo "	document.frmSns.submit();";
			echo '} else {';
			echo "	opener.document.frmSns.action='".$frmAction."';";
			echo "	opener.document.frmSns.sns_id.value='".$userData['userID']."';";
			echo "	opener.document.frmSns.sns_name.value='".$userData['name']."';";
			echo "	opener.document.frmSns.sns_email.value='".$userData['email']."';";
			echo "	opener.document.frmSns.sns_type.value='".$snsNvConfig["use"]."';";
            echo "	opener.document.frmSns.mode.value='login';";
            echo "	opener.document.frmSns.act.value='sns';";
			echo "	opener.document.frmSns.sns_token.value='';";
			echo "	opener.document.frmSns.submit();";
			echo "	window.close();";
			echo "}";
			echo '</script>';

			exit;
		}else{
			$onload="네이버 데이터가 없습니다.";
			if(ord($onload)) {
				if($acType=='M') 
					alert_go($onload,'-1');					
				else
					alert_close($onload);
			}
		}
	}else if($_ShopInfo->getCheckSns() == "kt"){
		
				
		include_once($Dir."plugin/sns/kakao/kt.lib.php");
		

		$accessTokenJson = connectKakaoToken($snsKtConfig);

		$decodeToken = json_decode($accessTokenJson);

		$accessTokenJsonUser = connectKakaoUser($decodeToken);

		$decodeTokenUser = json_decode($accessTokenJsonUser);
		
            //print_r($decodeTokenUser);exit;

		if($decodeTokenUser->id){
						
			$sns_chk_data = $_ShopInfo->getCheckSns()."||".$decodeTokenUser->id;
			$_SESSION['sns']['kt_sns_id'] = $decodeTokenUser->id;
			if($_ShopInfo->getCheckSnsLogin()){
                $frmAction = "/proc/login.proc.php";
                if($_ShopInfo->memid){
                    //echo $_ShopInfo->memid;exit;
                    $_ShopInfo->checksnschurl = "/front/mypage_sns.php";
                    $sns_sql = "UPDATE tblmember SET sns_type = '{$sns_chk_data}' WHERE id = '{$_ShopInfo->memid}'";
                    pmysql_query($sns_sql,get_db_conn());

                    $sns_kakao = "INSERT INTO tblmember_sns(id,name,sns_email,date_insert,sns_type) VALUES (
							'{$_ShopInfo->memid}',
							'{$_ShopInfo->memname}',
							'{$decodeTokenUser->kaccount_email}',
							'{NOW}',
							'KAKAO') ";

                    //echo $sns_naver;exit;
                    pmysql_query($sns_kakao,get_db_conn());
                    if($acType=='M'){
                        echo "<script> ";
                        echo "location.href='/m/mypage_sns.php';";
                        echo "</script>";
                    }else{
                        echo "<script> ";
                        echo "opener.parent.location='/front/mypage_sns.php';";
                        echo"window.close();";
                        echo "</script>";
                    }

                }
			} else {
				list($memId) = pmysql_fetch_array(pmysql_query("SELECT id FROM tblmember WHERE sns_type='".$sns_chk_data."' "));
				if($memId) {
					$memId_exp	= explode("@", $memId);
					$memId=substr($memId_exp[0],0,-4)."****"."@".$memId_exp[1];
					echo '<script> ';
					echo 'if (window.opener == null){';
					echo "	alert('".$decodeTokenUser->properties->nickname." 고객님께서는 [".$memId."]로 이미 가입하셨습니다.');location.href='/{$acDir}/login.php';";
					echo '} else {';
					echo "	opener.sns_alert('".$decodeTokenUser->properties->nickname."', '".$memId."');";
					echo "	window.close();";
					echo "}";
					echo '</script>';

					exit;
				}
			}
			
			

			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
			echo "	<form action='".$frmAction."' method='POST' name='frmSns'>";
			echo "		<input type='hidden' name='action_mode' value='".($_ShopInfo->getCheckSnsLogin()?'SNS_LOGIN':'')."'>";
			echo "		<input type='hidden' name='sns_login' value='".($_ShopInfo->getCheckSnsLogin()?'1':'')."'>";
			echo "		<input type='hidden' name='sns_id' value='".$decodeTokenUser->id."'>";
			echo "		<input type='hidden' name='sns_name' value='".$decodeTokenUser->properties->nickname."'>";
            echo "		<input type='hidden' name='sns_email' value='".$decodeTokenUser->kaccount_email."'>";
			echo "		<input type='hidden' name='sns_type' value='".$snsKtConfig["use"]."'>";
			echo "		<input type='hidden' name='sns_token' value=''>";
            echo "		<input type='hidden' name='act' value='sns'>";
            echo "		<input type='hidden' name='mode' value='login'>";
            echo "		<input type='hidden' name='chUrl' value='".$_ShopInfo->checksnschurl."'>";
			echo "	</form>";
			echo '<script> ';
			echo 'if (window.opener == null){';
			echo "	document.frmSns.submit();";
			echo '} else {';
			echo "	opener.document.frmSns.action='".$frmAction."';";
			echo "	opener.document.frmSns.sns_id.value='".$decodeTokenUser->id."';";
			echo "	opener.document.frmSns.sns_name.value='".$decodeTokenUser->properties->nickname."';";
            echo "	opener.document.frmSns.sns_email.value='".$decodeTokenUser->kaccount_email."';";
			echo "	opener.document.frmSns.sns_type.value='".$snsKtConfig["use"]."';";
            echo "	opener.document.frmSns.mode.value='login';";
            echo "	opener.document.frmSns.act.value='sns';";
            echo "	opener.document.frmSns.sns_token.value='';";

			echo "	opener.document.frmSns.submit();";
			echo "	window.close();";
			echo "}";
			echo '</script>';

			exit;
		}else{
			$onload="카카오톡 데이터가 없습니다.";
			if(ord($onload)) {
				if($acType=='M') 
					alert_go($onload,'-1');					
				else
					alert_close($onload);
			}
		}
	}else if($_ShopInfo->getCheckSns() == "it"){
		include_once($Dir."plugin/sns/instagram/instagram.class.php");
		include_once($Dir."plugin/sns/instagram/instagram.config.php");

		$code = $_GET['code'];

		
		if (true === isset($code)) {
			$instagramData = $instagram->getOAuthToken($code);

		
			if($instagramData->user->id){

				$sns_chk_data = $_ShopInfo->getCheckSns()."||".$instagramData->user->id;
				if($_ShopInfo->getCheckSnsLogin()){
					if($acType=='M')
						$frmAction = "/{$acDir}/login.process.php";
					else 
						$frmAction = "/{$acDir}/login.php";
				} else {
					list($memId) = pmysql_fetch_array(pmysql_query("SELECT id FROM tblmember WHERE sns_type='".$sns_chk_data."' "));
					if($memId) {
						$memId_exp	= explode("@", $memId);
						$memId=substr($memId_exp[0],0,-4)."****"."@".$memId_exp[1];
						echo '<script> ';
						echo 'if (window.opener == null){';
						echo "	alert('".$instagramData->user->full_name." 고객님께서는 [".$memId."]로 이미 가입하셨습니다.');location.href='/{$acDir}/login.php';";
						echo '} else {';
						echo "	opener.sns_alert('".$instagramData->user->full_name."', '".$memId."');";
						echo "	window.close();";
						echo "}";
						echo '</script>';

						exit;
					}
				}

				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
				echo "	<form action='".$frmAction."' method='POST' name='frmSns'>";
			echo "		<input type='hidden' name='action_mode' value='".($_ShopInfo->getCheckSnsLogin()?'SNS_LOGIN':'')."'>";
			echo "		<input type='hidden' name='sns_login' value='".($_ShopInfo->getCheckSnsLogin()?'1':'')."'>";
				echo "		<input type='hidden' name='sns_id' value='".$instagramData->user->id."'>";
				echo "		<input type='hidden' name='sns_name' value='".$instagramData->user->full_name."'>";
				echo "		<input type='hidden' name='sns_email' value=''>";
				echo "		<input type='hidden' name='sns_type' value='".$snsItConfig["use"]."'>";
				echo "		<input type='hidden' name='sns_token' value=''>";
				echo "	</form>";
				echo '<script> ';
				echo 'if (window.opener == null){';
				echo "	document.frmSns.submit();";
				echo '} else {';
				echo "	opener.document.frmSns.action='".$frmAction."';";
				echo "	opener.document.frmSns.sns_id.value='".$instagramData->user->id."';";
				echo "	opener.document.frmSns.sns_name.value='".$instagramData->user->full_name."';";
				echo "	opener.document.frmSns.sns_email.value='';";
				echo "	opener.document.frmSns.sns_type.value='".$snsItConfig["use"]."';";
				echo "	opener.document.frmSns.sns_token.value='';";
				echo "	opener.document.frmSns.submit();";
				echo "	window.close();";
				echo "}";
				echo '</script>';

				exit;
			}else{
				$onload="인스타그램 데이터가 없습니다.";
				if(ord($onload)) {
				if($acType=='M') 
					alert_go($onload,'-1');					
				else
					alert_close($onload);
				}
			}

		}else{
			$onload="인스타그램 코드가 잘못되었습니다.";
			if(ord($onload)) {
				if($acType=='M') 
					alert_go($onload,'-1');					
				else
					alert_close($onload);
			}
		}
	}
?>
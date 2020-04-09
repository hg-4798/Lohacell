<?php
/**
 * 로그인 프로세싱
 * 비동기처리
 * @author hjlee
 */

 //실행파일 직접접근 방지
 if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { 
	//header("HTTP/1.0 404 Not Found");
	//exit;
}



$Dir = "../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$mode = $_POST['mode'];
$act = $_POST['act'];

$Member = new MEMBER();
$argu = $Member->xss_post();


if($mode == 'login') {
	$tbl = $Member->tbls['member'];
	if($act == 'sns') { //SNS로그인
		# SNS 관련 세션값 초기화
		$_ShopInfo->setCheckSns("");
		$_ShopInfo->setCheckSnsLogin("");
		$_ShopInfo->Save();

		$sns_id=$_POST['sns_id'];
		$sns_type=$_POST['sns_type'];
		$sns_login=$_POST['sns_login'];				// SNS 로그인인지 체크값
		$sns_email=$_POST['sns_email'];
		$sns_login_id=$sns_type."||".$sns_id;		// SNS결과와 DB 비교값



		$_SESSION[sns][sns_id]		= $sns_id;
		$_SESSION[sns][sns_type]	= $sns_type;
		$_SESSION[sns][sns_login]	= $sns_login;
		$_SESSION[sns][sns_email]	= $sns_email;
		$_SESSION[sns][sns_login_id]= $sns_login_id;

		$sql = "SELECT id, name, nickname, passwd, passwd_fail_count
					  , member_out, staff_yn, reserve, email, group_code
					  , authidkey, gender, birth 
				FROM {$tbl} WHERE sns_type='{$sns_login_id}'";
	}
	else {
		$member_id = $argu['id'];
		$member_pwd = $Member->_password($argu['passwd']); //비밀번호 복호화
		$sql = "SELECT id, name, nickname, passwd, passwd_fail_count
					  , member_out, staff_yn, reserve, email, group_code
					  , authidkey, gender , birth
				FROM {$tbl} WHERE id='{$member_id}'";
	}

	$member_info = $Member->adodb->getRow($sql);


		if($member_info) {

			//탈퇴회원여부체크
			if($member_info['member_out'] == 'Y') {
				if($act!='sns') {
					return_json(false, '탈퇴한 회원입니다.');
				}else{
					if(DIR_VIEW == '/m') alert_go('탈퇴회원입니다. 90일 이후 재가입 가능합니다.','/m/main.php');
					else return_json(true,'탈퇴회원입니다. 90일 이후 재가입 가능합니다.',array('url'=>'/front/main.php'));
				}
			}


			if($act!='sns') {
				//비밀번호 오입력 회수를 초과한경우 올바른 비밀번호를 입력해도 로그인 할 수 없음
				if ($member_info['passwd_fail_count'] >= $Member->_conts['fail_passwod']) {
					return_json(false, '비밀번호 ' . $member_info['passwd_fail_count'] . '회 입력 오류로 로그인을 할 수 없습니다.<br>비밀번호 찾기를 통해 비밀번호를 변경해주세요. '); //@check 재인증여부 체크
				}

				if ($member_info['passwd'] != $member_pwd) {
					$passwd_fail_count = $member_info['passwd_fail_count'] + 1;
					$sql = "UPDATE {$tbl} SET passwd_fail_count='{$passwd_fail_count}' WHERE id='{$member_id}'";
					$Member->adodb->Execute($sql);
					return_json(false, "로그인 정보가 올바르지 않습니다.<br>(로그인 실패횟수 : {$passwd_fail_count}회)");
				} else if($member_info['member_out'] == 'S'){ //휴면회원일 경우
					if(DIR_VIEW == '/m') alert_go('휴면회원입니다.','/m/member_sleep.php');
					else return_json(true,'휴면회원입니다.',array('url'=>'/front/member_sleep.php'));
				} else {
					//비밀번호 오류횟수 초기화
					$sql = "UPDATE {$tbl} SET passwd_fail_count=0 WHERE id='{$member_id}'";
					$Member->adodb->Execute($sql);
				}
			}

			//로그인처리
			$authidkey = md5(uniqid('JAYJUN'));
			$_ShopInfo->setMemid($member_info['id']); //회원아이디
			$gender = $member_info['gender']? 'man':'woman';
			$_ShopInfo->setGender($gender); //회원 성별
			$_ShopInfo->setBirth($member_info['birth']); //회원 생일
			$_ShopInfo->setAuthidkey($authidkey);
			$_ShopInfo->setMemgroup($member_info['group_code']); //등급코드
			$_ShopInfo->setMemname($member_info['name']); //회원명
			$_ShopInfo->setMemreserve($member_info['reserve']); //회원적립금
			$_ShopInfo->setMememail($member_info['email']); //회원이메일
			$_ShopInfo->setStaffYn($member_info['staff_yn']); //임직원여부
			$_ShopInfo->Save();

			//로그인접속정보처리(접속IP, 접속일시, 로그인횟수, 로그인 인증값)
			$sql = "UPDATE {$tbl} SET ip='".$_SERVER['REMOTE_ADDR']."', logindate='".date(YmdHis)."', logincnt=logincnt+1, authidkey='{$authidkey}' WHERE id='{$member_info['id']}'";
			$Member->adodb->Execute($sql);

			//로그인로그등록
			$Member->_log($member_id, 'login');


			//비회원장바구니 회원아이디 적용
			if(!$Basket) $Basket = new BASKET;
			$Basket->sync_login(); 

			//아이디 저장인경우 쿠키등록처리
			if($argu['save_id'] == 'Y') {
				setcookie('JID', base64_encode($member_info['id']), time()+(86400*30),'/');
			}
			else {
				setcookie('JID', '', time()-1); //쿠키삭제
			}

			if($act!='sns'){
				//리턴
				$url = urldecode($_POST['return_url']);
				$url .=($_POST['ret_url'])?"&ret_url=".$_POST['ret_url']:"";
				return_json(true, '', array('url'=>$url));
			}
			else{
				//리턴
				if(!$_REQUEST["chUrl"]){
					$chUrl = "/";
				}
				else $chUrl = trim(urldecode($_REQUEST["chUrl"]));
				alert_go('',$chUrl);
			}
		}
		else {
			if($act!='sns'){
				//비밀번호 입력오류회수 +1
				$sql = "UPDATE {$tbl} SET passwd_fail_count=passwd_fail_count+1 WHERE id='{$member_id}'";
				$Member->adodb->Execute($sql);

				return_json(false,'로그인 정보가 올바르지 않습니다.');
			}
			else{

				//마일리지 지급/사용로그 제거

				if(DIR_VIEW == '/m') alert_go('','/m/member_certi.php');
				else alert_go('','/front/member_certi.php');
			}
		}

}

else if($mode =='guest') { //비회원주문번호 로그인(쿠키처리)

}else if($mode == 'exit') {

	//탈퇴사유 추가 bshan
	$out_reason = $_POST["out_reason"];
	$out_reason_content = $_POST["out_reason_content"];
	$memoutinfo = $_REQUEST["memoutinfo"]; // 회원 탈퇴시 소멸내역정보(2016.08.18 - 김재수 추가)

	$sql = "SELECT name,email,mobile FROM tblmember WHERE id='" . MEMID . "' ";
	$result = pmysql_query($sql, get_db_conn());
	if ($row = pmysql_fetch_object($result)) {
		if ($row->member_out == "Y") {
			//alert_go('회원 아이디가 존재하지 않습니다.', -1);
			return_json(false,'이미 탈퇴회원 입니다.');
		}
		$exitname = $row->name;
		$exitemail = $row->email;
		$exitmobile = $row->mobile;

		//로그 저장 텍스트를 만든다.
		$savetemp = "====================" . date("Y-m-d H:i:s") . "====================\n";
		//if ($row=pmysql_fetch_object($result)) {
		foreach ($row as $key => $val) {
			$savetemp .= $key . " : " . $val . "\n";
		}
		//}
		$savetemp .= "\n";
	} else {
		//alert_go('회원 아이디가 존재하지 않습니다.', -1);
		return_json(false,'회원 아이디가 존재하지 않습니다.');
	}
	pmysql_free_result($result);

	$state = "N";

	$sql = "SELECT COUNT(*) as cnt FROM tblorder_basic WHERE member_id='" . MEMID . "' ";
	$result = pmysql_query($sql, get_db_conn());
	$row = pmysql_fetch_object($result);
	/*if($row->cnt==0) {
		$sql ="DELETE FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
		$state="Y";
	} else {*/
	$sql = "UPDATE tblmember SET ";
	$sql .= "passwd			= '', ";
	$sql .= "resno			= '', ";
	$sql .= "email			= '', ";
	$sql .= "mobile			= '', ";
	$sql .= "news_yn			= 'N', ";
	$sql .= "age				= '', ";
	$sql .= "gender			= '', ";
	$sql .= "job				= '', ";
	$sql .= "birth			= '', ";
	$sql .= "home_post		= '', ";
	$sql .= "home_addr		= '', ";
	$sql .= "home_tel		= '', ";
	$sql .= "office_tel		= '', ";
	$sql .= "memo			= '', ";
	$sql .= "reserve			= 0, ";
	$sql .= "joinip			= '', ";
	$sql .= "ip				= '', ";
	$sql .= "authidkey		= '', ";
	$sql .= "group_code		= '', ";
	$sql .= "member_out		= 'Y', ";
	$sql .= "dupinfo		= '', ";
	$sql .= "sns_type		= '', ";
	$sql .= "act_point		= 0, ";
	$sql .= "etcdata			= '' ";
	$sql .= "WHERE id = '" . MEMID . "'";
	$state = "V";
	//}
	pmysql_free_result($result);
	pmysql_query($sql, get_db_conn());

/*	//탈퇴회원정보를 파일로 저장한다.
	$file = "/data/backup/tblmember_out_" . date("Y") . "_" . date("m") . "_" . date("d") . ".txt";
	if (!is_file($file)) {
		$f = fopen($file, "a+");
		fclose($f);
		chmod($file, 0777);
	}
	file_put_contents($file, $savetemp, FILE_APPEND);*/

	//마일리지 지급/사용로그 제거
	$Mileage = new MILEAGE;
	$Mileage->delete("mem_id='".MEMID."'");

	//포인트 지급/사용로그 제거
	$Point = new POINT;
	$Point->delete("mem_id='".MEMID."'");

	//$sql = "DELETE FROM tblpoint WHERE mem_id='".$_ShopInfo->getMemid()."'";
	//pmysql_query($sql,get_db_conn());
	$sql = "DELETE FROM tblcouponissue WHERE id='" . MEMID . "'";
	pmysql_query($sql, get_db_conn());
	$sql = "DELETE FROM tblmemo WHERE id='" . MEMID . "'";
	pmysql_query($sql, get_db_conn());
	//$sql = "DELETE FROM tblrecommendmanager WHERE rec_id='".$_ShopInfo->getMemid()."'";
	//pmysql_query($sql,get_db_conn());
	//$sql = "DELETE FROM tblrecomendlist WHERE id='".$_ShopInfo->getMemid()."'";
	//pmysql_query($sql,get_db_conn());
	$sql = "DELETE FROM tblpersonal WHERE id='" . MEMID . "'";
	pmysql_query($sql, get_db_conn());

	//$text = "alert('해당 ID를 탈퇴처리해 드렸습니다.');";

	//list($out_reason, $out_reason_content) = pmysql_fetch("SELECT out_reason, out_reason_content FROM tblmemberout_temp WHERE id = '".$_ShopInfo->getMemid()."'");

	$sql = "INSERT INTO tblmemberout ( 
		id, name, email, tel, ip, 
		state, date, out_reason, out_reason_content) VALUES (
		'" . MEMID . "', '" . $exitname . "', '" . $exitemail . "', '" . $exitmobile . "', '" . $_SERVER['REMOTE_ADDR'] . "', 
		'" . $state . "', '" . date("YmdHis") . "', '{$out_reason}', '" . $out_reason_content . "') ";
	pmysql_query($sql, get_db_conn());

	pmysql_query("DELETE FROM tblmemberout_temp WHERE id='" . MEMID . "'", get_db_conn());

	if ($out_access_type == '') $out_access_type = "web";
	//---------------------------------------------------- 탈퇴시 로그를 등록한다. ----------------------------------------------------//
	//로그인로그등록
	$Member->_log($member_id, 'memberout');
	//---------------------------------------------------------------------------------------------------------------------------------//

	//SMS 발송
	//sms_autosend( 'mem_out', $_ShopInfo->getMemid(), '', '' );

	//SMS 관리자 발송
	//sms_autosend( 'admin_out', $_ShopInfo->getMemid(), '', '' );


	$_ShopInfo->SetMemNULL();
	$_ShopInfo->Save();

	if (file_exists($Dir . DataDir . "design/intro.htm")) {
		$url = $Dir . "index.php";
	} else {
		if ($out_access_type == 'mobile') {
			$url = $Dir . 'm/logout.php';
		} else {
			$url = $Dir;
		}
	}

	//echo $text;
	if ($memoutinfo) $url_add_pram = "?memoutinfo={$memoutinfo}";

	//탈퇴메일 발송 처리
	if (ord($exitemail)) {
		$mail = new MAIL;
		$mail->send_mail('memberout', MEMID);
		//SendOutMail($_data->shopname, $_data->shopurl, $_data->design_mail, $_data->out_msg, $_data->info_email, $exitemail, $exitname);
	}
	if ($basename == "mypage_memberout.php") { // 모바일 결제시 m으로 가게 변경
		/*
		$mobileBrower = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS|iPad)/';
		if(preg_match($mobileBrower, $_SERVER['HTTP_USER_AGENT']) && !$_GET[pc]) {
			//광고파라미터 "index.php?".$_SERVER["QUERY_STRING"] 로 가야하는것이 맞는지 확인
			//$mainurl="m/?".$_SERVER["QUERY_STRING"];
			$url=$Dir.'m/';
			exdebug( $url );
		}
		*/
		if ($out_access_type == 'mobile') {
			$url = $Dir . 'm/logout.php' . $url_add_pram;
		} else {
			//$url="/";
			$url = $Dir . 'front/mypage_memberout.php' . $url_add_pram;
		}
	}

//	echo "<script>{$text}parent.location.href='" . $url . "';</script>";
	return_json(true,'탈퇴되었습니다.',array('url'=>$url));
}
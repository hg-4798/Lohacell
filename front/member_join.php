<?php
/********************************************************************* 
// 파 일 명		: member_join.php 
// 설     명		: 회원가입 정보등록
// 상세설명	: 회원가입시 정보를 등록
// 작 성 자		: 2016.01.07 - 김재수
// 수 정 자		: 2016.07.28 - 김재수
// 
// 
*********************************************************************/

?>
<?php
	//IF(!$_SERVER[REMOTE_ADDR]=='218.234.32.97'){
	session_start();
	//header("Location:../index.php");
	//exit;
//}

#---------------------------------------------------------------
# 기본정보 설정파일을 가져온다.
#---------------------------------------------------------------
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");
	include_once($Dir."conf/config.sns.php");

	if(strlen($_ShopInfo->getMemid())>0) {
		$mem_auth_type	= getAuthType($_ShopInfo->getMemid());
		if ($mem_auth_type != 'sns') {
			header("Location:../index.php");
			exit;
		}
		
		$jmem_sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
		$jmem_result=pmysql_query($jmem_sql,get_db_conn());
		if($jmem_row=pmysql_fetch_object($jmem_result)) {
			if($jmem_row->member_out=="Y") {
				$_ShopInfo->SetMemNULL();
				$_ShopInfo->Save();
				echo "<html><head></head><body onload=\"alert('회원 아이디가 존재하지 않습니다.');parent.location.href='member_certi.php';\"></body></html>";exit;
			}

			if($jmem_row->authidkey!=$_ShopInfo->getAuthidkey()) {
				$_ShopInfo->SetMemNULL();
				$_ShopInfo->Save();
				echo "<html><head></head><body onload=\"alert('처음부터 다시 시작하시기 바랍니다.');parent.location.href='member_certi.php';\"></body></html>";exit;
			}

			$jmem_id=$jmem_row->id;
			$jmem_name=$jmem_row->name;		

			if($jmem_row->news_yn=="Y") {
				$news_mail_yn="Y";
				$news_sms_yn="Y";
			} else if($jmem_row->news_yn=="M") {
				$news_mail_yn="Y";
				$news_sms_yn="N";

			} else if($jmem_row->news_yn=="S") {
				$news_mail_yn="N";
				$news_sms_yn="Y";

			} else if($jmem_row->news_yn=="N") {
				$news_mail_yn="N";
				$news_sms_yn="N";

			} else if($jmem_row->news_yn=="1") {
				$news_mail_yn="Y";
				$news_sms_yn="Y";

			} else if($jmem_row->news_yn=="2") {
				$news_mail_yn="Y";
				$news_sms_yn="N";

			} else if($jmem_row->news_yn=="3") {
				$news_mail_yn="N";
				$news_sms_yn="Y";

			} else if($jmem_row->news_yn=="K") {
				$news_mail_yn="N";
				$news_sms_yn="N";

			}
			$checked['news_mail_yn'][$news_mail_yn] = "checked";
			$checked['news_sms_yn'][$news_sms_yn] = "checked";
		} else {
			$_ShopInfo->SetMemNULL();
			$_ShopInfo->Save();
			echo "<html><head></head><body onload=\"alert('회원 아이디가 존재하지 않습니다.');parent.location.href='member_certi.php';\"></body></html>";exit;
		}
		pmysql_free_result($jmem_result);
	}

	$ip = $_SERVER['REMOTE_ADDR'];

	$auth_type		= ($_GET['auth_type']!='')?$_GET['auth_type']:$_POST['auth_type']; // 인증타입
	$mem_type	= ($_GET['mem_type']!='')?$_GET['mem_type']:$_POST['mem_type']; // 회원구분 (0 : 일반)
	$join_type		= ($_GET['join_type']!='')?$_GET['join_type']:$_POST['join_type']; // 가입구분 (0 : 14세 미만, 1 : 14세 이상)
	$staff_join		= ($_GET['staff_join']!='')?$_GET['staff_join']:$_POST['staff_join']; // 임직원 가입유무
	$cooper_join		= ($_GET['cooper_join']!='')?$_GET['cooper_join']:$_POST['cooper_join']; // 협력업체 가입유무

	if ($auth_type != 'sns') {
		#####실명인증 결과에 따른 분기
		//if($CertificationData->realname_check || $CertificationData->ipin_check){
			
			//if(!$_SESSION[ipin][name]) {
			//	echo "<html><head></head><body onload=\"alert('회원가입을 위해 본인 인증이 필요합니다.');parent.location.href='member_certi.php';\"></body></html>";exit;
			//}
			if($_SESSION[ipin][dupinfo]){
				$check_ipin=pmysql_fetch_object(pmysql_query("select count(id) as check_id from tblmember where dupinfo='{$_SESSION[ipin][dupinfo]}'"));
				if($check_ipin->check_id){
					echo "<html><head></head><body onload=\"alert('이미 가입된 회원입니다.');parent.location.href='member_certi.php';\"></body></html>";exit;
				}	
			}
		//}

        $name=trim(iconv("CP949", "UTF-8", $_SESSION[ipin][name]));
        $gender=trim($_SESSION[ipin][gender]);
        $birthdate=mb_substr(trim($_SESSION[ipin][birthdate]),"2");
		$mobileno=trim($_SESSION[ipin][mobileno]);
		
		$birthdate1 = substr($_SESSION[ipin][birthdate],0,4);
		$birthdate2 = substr($_SESSION[ipin][birthdate],4,2);
		$birthdate3 = substr($_SESSION[ipin][birthdate],6,2);
		
		//echo strlen($mobileno);
		if(strlen($mobileno)==11){
			$mobileno1 = substr($mobileno,0,3);
			$mobileno2 = substr($mobileno,3,4);
			$mobileno3 = substr($mobileno,7,4);
		}else if(strlen($mobileno)==10){
			$mobileno1 = substr($mobileno,0,3);
			$mobileno2 = substr($mobileno,3,3);
			$mobileno3 = substr($mobileno,6,4);
		}
		
		
	}
	//$c_today = date("Ymd");

	//if($c_today < '20171231'){
		//include_once($Dir."conf/config.point.new.php"); 
	//}else{
		include_once($Dir."conf/config.point.2018.php"); 
	//}

	// 가입금 축하 적립금
	$reserve_join=(int)$pointSet_new['agree_point'];
	$reserve_join_over=(int)$pointSet_new['over_point'];
	

	//추천인 관련 셋팅
	$recom_ok					= 'Y';									// 추천인 사용유무	
	$recom_addreserve	= (int)$pointSet['addRecommand']['point'];		// 추천시 추가로받을 적립금 금액
	$recom_memreserve	= (int)$pointSet['recommand']['point'];			// 추천인에게 주는 적립금 금액
	$recom_limit		= (int)$pointSet['recommand']['count'];			// 추천수 제한

	if($_data->group_code){
		$group_code=$_data->group_code;
	}elseif($mem_type=="1"){
		$group_code="0004";
	}else{
		$group_code="0007";	
	}

	$member_addform=$_data->member_addform;
	$adultauthid='';
	$adultauthpw='';
	if(ord($_data->adultauth)) {
		$tempadult=explode("↑=↑",$_data->adultauth);
		if($tempadult[0]=="Y") {
			$adultauthid=$tempadult[1];
			$adultauthpw=$tempadult[2];
		}
	}
    $type=$_POST["type"];
    $sns_id = $_SESSION[sns][sns_id];
    $sns_type			= $_SESSION[sns][sns_login_id];
    $sns_email = explode("@",$_SESSION[sns][sns_email]);
	if($type=="insert") {

		//exdebug($_POST);
		//exit;
		
	
		$history="-1";
		$sslchecktype="";
		if($_POST["ssltype"]=="ssl" && strlen($_POST["sessid"])==64) {
			$sslchecktype="ssl";
			$history="-2";
		}
		if($sslchecktype=="ssl") {
			$secure_data=getSecureKeyData($_POST["sessid"]);
			if(!is_array($secure_data)) {
				echo "<html><head></head><body onload=\"alert('보안인증 정보가 잘못되었습니다.');\"></body></html>";exit;
			}
			foreach($secure_data as $key=>$val) {
				${$key}=$val;
			}
		} else {
			if ($mem_auth_type != 'sns') {
				$name				= trim($_POST["name"]);										// 이름
				$id					= trim($_POST["id"]);											// 아이디
			} else {
				$name				= $jmem_name;										// 이름
				$id					= $jmem_id;											// 아이디
			}
			//$birth					= trim($_POST["birth1"]).trim($_POST["birth2"]);		// 생년월일
			$birth					= trim($_SESSION[ipin][birthdate]);										// 생년월일
			$gender				= trim($_SESSION[ipin][gender]);									// 성별
			$dupinfo				= trim($_SESSION[ipin][dupinfo]);
			$conninfo				= trim($_SESSION[ipin][conninfo]);
			$passwd1			= $_POST["passwd1"];										// 비밀번호
			$passwd2			= $_POST["passwd2"];										// 비밀번호 확인
			$home_zonecode	= trim($_POST["home_zonecode"]);
			$home_post1			= trim($_POST["home_post1"]);
			$home_post2			= trim($_POST["home_post2"]);
			$home_addr1			= trim($_POST["home_addr1"]);
			$home_addr2			= trim($_POST["home_addr2"]);

			$email				= $_POST["email1"]? trim($_POST["email1"]."@".$_POST["email2"]):$id;		// 이메일
            $En_email				= Common::Enctypt_AES128CBC(trim($_POST["email"]),JayjunKey,JayjunIvKey);		// 이메일
			$news_mail_yn	= $_POST["news_mail_yn"];								// 메일수신여부
			$news_sms_yn	= $_POST["news_sms_yn"];								// SMS수신여부
			$nickname = $_POST['nickname'];
			//$home_tel			= trim($_POST['home_tel1'] ."-". $_POST['home_tel2'] ."-". $_POST['home_tel3']);	// 전화번호
			if ($_POST['home_tel2'] != '' && $_POST['home_tel3'] !='') {
				$home_tel			= Common::Enctypt_AES128CBC(trim($_POST['home_tel']),JayjunKey,JayjunIvKey);	// 전화번호
			} else {
				$home_tel			= "";
			}
			$lunar				= trim($_POST['lunar']);									// 음력여부 1양력 0음력
			$job				= trim($_POST['job']);									//직업text
			$job_code		= trim($_POST['job_code']);									//직업코드
			/*$mobile				= trim($_POST['mobile1'] ."-". $_POST['mobile2'] ."-". $_POST['mobile3']);		// 휴대폰
			if ($mobile) {
				$mobile	= addMobile($mobile);
			}*/
			$En_mobile		= Common::Enctypt_AES128CBC(trim($_POST['mobile']),JayjunKey,JayjunIvKey);									//휴대폰
            $mobile		= trim($_POST['mobile']);									//휴대폰
			//$sns_type			= $_POST["sns_type"];										// 간편 가입한 SNS : id||code


            $skintype = $_POST["skintype"]; // 제휴사코드

						
			//$emp_id		= trim($_POST['emp_id']);									//사번

			$rec_id				= trim($_POST["rec_id"]);									// 추천인 아이디
			//$erp_member_id				= trim($_POST["erp_member_id"]);									// 인증정보

			if (!$erp_member_id) {
				//echo $name."/".$mobile;
				$send_name	= iconv('utf-8','euc-kr',$name);
				if (!$send_name) $send_name	= $name;
				//var_dump($meberinfo);
				$code		= $meberinfo['p_err_code'];
				$p_data		= $meberinfo['p_data'];

			}
			
			$gdn_name		= trim($_POST["gdn_name"]);												// 보호자 이름
			//$gdn_birth			= trim($_POST["gdn_birth1"]).trim($_POST["gdn_birth2"]);		// 보호자 생년월일
			$gdn_birth			= trim($_POST["gdn_birth1"]);												// 보호자 생년월일
			$gdn_gender		= trim($_POST["gdn_gender"]);											// 보호자 성별
			$gdn_email		= trim($_POST["gdn_email"]);												// 보호자 이메일

			$gdn_mobile		= trim($_POST["gdn_mobile"]);												// 보호자 휴대폰
			if ($gdn_mobile) {
				$gdn_mobile	= addMobile($gdn_mobile);
			}
			//20170830 제휴사 추가
			if($_POST["cpCode"]){
				$company_code = $_POST["cpCode"]; // 제휴사코드
			}

		}

		$onload="";		

		if($recom_ok=="Y" && ord($rec_id)) {
			$sql = "SELECT COUNT(*) as cnt FROM tblmember WHERE id='".trim($rec_id)."' AND member_out!='Y' ";
			$rec_result = pmysql_query($sql,get_db_conn());
			$rec_row = pmysql_fetch_object($rec_result);
			$rec_num = $rec_row->cnt;
			pmysql_free_result($rec_result);

			$rec_cnt=0;
			$sql = "SELECT rec_cnt FROM tblmember_reccnt WHERE rec_id='".trim($rec_id)."'";
			$rec_result = pmysql_query($sql,get_db_conn());
			if($rec_row = pmysql_fetch_object($rec_result)) {
				$rec_cnt = (int)$rec_row->rec_cnt;
			}
			pmysql_free_result($rec_result);
		}

		//exdebug(strlen($gdn_birth));

		if(ord($onload)) {

		} else if($mem_auth_type != 'sns' && strlen(trim($name))==0) {
			$onload="이름 입력이 잘못되었습니다.";		
		} else if($mem_auth_type != 'sns' && strlen(trim($id))==0) {
			$onload="아이디 입력이 잘못되었습니다.";
		} else if(strlen($birth)!=0 && strlen($birth) < 6) {
			$onload="생년월일을 입력하세요.";		
		} else if($auth_type != 'sns' && strlen(trim($mobile))==0) {
			$onload="휴대전화를 입력하세요.";
		} else if($rec_num==0 && strlen($rec_id)!=0) {
			$onload="추천인 ID 입력이 잘못되었습니다.";
		} else if($mem_auth_type != 'sns' && $join_type=='0' && strlen($gdn_name)!=0) {
			$onload="보호자 이름 입력이 잘못되었습니다.";
		} else if($join_type=='0' && strlen($gdn_birth)!=0 && strlen($gdn_birth) < 6) {
			$onload="보호자 생년월일 입력이 잘못되었습니다.";
		} else if($join_type=='0' && strlen($gdn_email)==0) {
			$onload="보호자 이메일 입력이 잘못되었습니다.";
		} else if($join_type=='0' && strlen(trim($gdn_mobile))==0) {
			$onload="보호자 휴대폰 입력이 잘못되었습니다.";
		} else {	
			if(!$onload) {
				$month_date	= date("YmdHis", strtotime('-1 month'));
				$sql = "SELECT id FROM tblmemberout WHERE name='{$name}' AND tel = '{$mobile}' AND date > '".$month_date."' order by date desc limit 1";
				$result=pmysql_query($sql,get_db_conn());
				if($row=pmysql_fetch_object($result)) {
					$onload="기존에 탈퇴한 회원입니다.\\n\\n재가입은 한달후 가능합니다.";
				}
				pmysql_free_result($result);
			}
			if(!$onload) {
				if ($mem_auth_type != 'sns') {
					$sql = "SELECT id FROM tblmember WHERE id='{$id}' ";
					$result=pmysql_query($sql,get_db_conn());
					if($row=pmysql_fetch_object($result)) {
						$onload="ID가 중복되었습니다.\\n\\n다른 아이디를 사용하시기 바랍니다.";
					}
					pmysql_free_result($result);

					if(!$onload) {
						$sql = "SELECT id FROM tblmemberout WHERE id='{$id}' ";
						$result=pmysql_query($sql,get_db_conn());
						if($row=pmysql_fetch_object($result)) {
							$onload="ID가 중복되었습니다.\\n\\n다른 아이디를 사용하시기 바랍니다.";
						}
						pmysql_free_result($result);
					}
				}

				if(!$onload) {
					//insert
					$date=date("YmdHis");

					//뉴스레터수신여부(Y:메일,핸폰수신동의,M:메일수신동의,S:핸폰수신동의,N:수신거부)';
					//1카카오포함전체,2카카오+이메일,3카카오+SMS+,K카카오
					/*if($news_mail_yn=="Y") {
						$news_yn="M";
					}
					if($news_sms_yn=="Y") {
						$news_yn="S";
					}
					if($news_kakao_yn=="Y") {
						$news_yn="K";
					}
					if($news_mail_yn=="Y" && $news_sms_yn=="Y") {
						$news_yn="Y";
					}
					if($news_mail_yn=="Y" && $news_kakao_yn=="Y") {
						$news_yn="2";
					}
					if($news_sms_yn=="Y" && $news_kakao_yn=="Y") {
						$news_yn="3";
					}
					if($news_mail_yn=="Y" && $news_sms_yn=="Y" && $news_kakao_yn=="Y") {
						$news_yn="1";
					}*/

					
					if($news_mail_yn=="Y" && $news_sms_yn=="Y") {
						$news_yn="Y";
					} else if($news_mail_yn=="Y") {
						$news_yn="M";
					} else if($news_sms_yn=="Y") {
						$news_yn="S";
					} else {
						$news_yn="N";
					}


					//if(ord($sns_type)) $passwd1	= $sns_type;

					$shadata = "*".strtoupper(SHA1(unhex(SHA1($passwd1))));

					$confirm_yn="Y";

					$home_addr			=$home_addr1;
                    $home_addr_detail = Common::Enctypt_AES128CBC(trim($home_addr2),JayjunKey,JayjunIvKey);
					if ($job_code=='') $job	= "";

                    BeginTrans();
					if ($mem_auth_type != 'sns') {
						$sql = "INSERT INTO tblmember(id) VALUES('{$id}')";
						pmysql_query($sql,get_db_conn());
					}
					$sql = "UPDATE tblmember SET ";
					$sql.= "id			= '{$id}', ";
                    if(!$sns_type){
                        $sql.= "passwd		= '".$shadata."', ";
                    }
					$sql.= "name		= '{$name}', ";
					$sql.= "email		= '{$En_email}', ";
					$sql.= "mobile		= '{$En_mobile}', ";
					$sql.= "home_post	= '{$home_zonecode}', ";
					$sql.= "home_addr	= '{$home_addr}', ";
					$sql.= "home_addr_detail	= '{$home_addr_detail}', ";
					$sql.= "home_tel		= '{$home_tel}', ";
					$sql.= "lunar			= '{$lunar}', ";
					$sql.= "job				= '{$job}', ";
					$sql.= "job_code		= '{$job_code}', ";
					
					$sql.= "news_yn		= '{$news_yn}', ";
					if(ord($birth)) $sql.= "birth		= '{$birth}', ";
//					if(ord($birth)) $sql.= "gender		= '{$gender}', ";
					$sql.= "gender		= '{$gender}', ";
					if(ord($nickname)) $sql.=" nickname = '{$nickname}', ";
					$sql.= "joinip		= '{$ip}', ";
					$sql.= "ip			= '{$ip}', ";
					if ($mem_auth_type != 'sns') {
						$sql.= "date		= '{$date}', ";
					} else {
						$sql.= "trandate		= '{$date}', ";
					}
					$sql.= "confirm_yn	= '{$confirm_yn}', ";
					$sql.= "dupinfo	= '{$dupinfo}', ";
					$sql.= "conninfo	= '{$conninfo}', ";
					
					if($recom_ok=="Y" && $rec_num!=0 && $rec_cnt<$recom_limit && ord($rec_id)) {
						$sql.= "rec_id	= '{$rec_id}', ";
					}
					if(ord($group_code)) {
						$sql.= "group_code='{$group_code}', ";
					}
					if(ord($gdn_name)) {
						$sql.= "gdn_name		= '{$gdn_name}', ";
						if(ord($gdn_birth)) $sql.= "gdn_birth	= '{$gdn_birth}', ";
						if(ord($gdn_birth)) $sql.= "gdn_gender	= '{$gdn_gender}', ";
						$sql.= "gdn_mobile	= '{$gdn_mobile}', ";					
						$sql.= "gdn_email	= '{$gdn_email}', ";						
					}

					if(ord($sns_type)) $sql.= "sns_type		= '{$sns_type}', ";
					if(ord($emp_id)) {
						$sql.= "staff_yn		= 'Y', ";

					}

					if(ord($cooper_join) && $cooper_join == 'Y') {
						$sql.= "cooper_yn		= 'Y', ";
						$sql.= "company_code='{$company_code}', ";
						list($company_group)=pmysql_fetch_array(pmysql_query("select group_no from tblcompanygroup where group_code='{$company_code}'"));
						$sql.= "company_group='{$company_group}', ";
					}

					if(ord($company_code)) { //2017-08-04 제휴사 추가
						$sql.= "cooper_yn		= 'Y', ";
						$sql.= "company_code='{$company_code}', ";
						list($company_group)=pmysql_fetch_array(pmysql_query("select group_no from tblcompanygroup where group_code='{$company_code}'"));
						$sql.= "company_group='{$company_group}', ";
					}

					$sql.= "mb_type		= 'web', ";


                    $sql.= "skintype		= '{$skintype}', ";

					$sql.= "auth_type='{$auth_type}' WHERE id='{$id}'";

					//if($id!='ssuya') {
                    //echo  $sql;exit;
						$insert=pmysql_query($sql,get_db_conn());
					/*} else {
						exdebug($sql);
						exit;
					}*/

                    //exdebug($sql);
                    //exit;
                    if($sns_type && $_POST['sns_id']){
                        $cu_sns_type = explode("||",$sns_type);
                        if($cu_sns_type[0] =='nv'){
                            $c_type = "NAVER";
                        }else if($cu_sns_type[0] =='kt'){
                            $c_type = "KAKAO";
                        }else if($cu_sns_type[0] =='fb'){
                            $c_type = "FACEBOOK";
                        }

                        $sql3 = "INSERT INTO tblmember_sns(id,name,sns_email,date_insert,sns_type) VALUES (
							'{$id}',
							'{$name}',
							'{$En_email}',
							'{NOW}',
							'{$c_type}') ";
                       pmysql_query($sql3,get_db_conn());
                    }

					if (pmysql_errno()==0) {
						CommitTrans();
						
						// 회원가입 알림톡 회원정보 커및 완료후
						//$alim = new ALIM_TALK();
						//$alim->makeAlimTalkSearchData($id, 'SCC04', "", "");

                        // ERP로 회원정보 전송..2016-12-19


						//---------------------------------------------------- 가입시 로그를 등록한다. ----------------------------------------------------//
						$memLogSql = "INSERT INTO tblmemberlog (id,type,access_type,date) VALUES ('".$id."','join','web','".date("YmdHis")."')";
						pmysql_query($memLogSql,get_db_conn());
						//---------------------------------------------------------------------------------------------------------------------------------//

						//가입메일 발송 처리
						if(ord($email)) {
                            $mail = new MAIL;
                            $mail -> send_mail('join',$id);
							//SendJoinMail($_data->shopname, $_data->shopurl, $_data->design_mail, $_data->join_msg, $_data->info_email, $email, $name, $id);
						}

						//가입 SMS 발송 처리
                        $sms = new SMS;
						$sms->send_sms('MEMBER_002',$id);
						//$mem_return_msg = sms_autosend( 'mem_join', $id, '', '' );
						//$admin_return_msg = sms_autosend( 'admin_join', $id, '', '' );

                        //가입포인트
                        $Point = new POINT;
                        $Point->plus('join', $id);

						$_ShopInfo->setCheckSns("");
						$_ShopInfo->setCheckSnsLogin("");
						$_ShopInfo->setCheckSnsAccess("");
						$_ShopInfo->setCheckSnsChurl("");
						$_ShopInfo->Save();

						if ($mem_auth_type != 'sns') {
							$alert_join_text	= "회원";
						} else {
							$alert_join_text	= "정회원";
						}

						// 20170512 가입완료시 메인페이지 이동
						echo "<html><head></head><body onload=\"alert('{$alert_join_text}가입이 완료되었습니다.\\n{$msg}\\n감사합니다.');parent.location.href='member_joinend.php?auth_type={$auth_type}&name={$name}&id={$id}&bf_auth_type={$mem_auth_type}';\"></body></html>";exit;
						//echo "<html><head></head><body onload=\"alert('{$alert_join_text}가입이 완료되었습니다.\\n{$msg}\\n감사합니다.');parent.location.href='../index.htm';\"></body></html>";exit;
						//alert_go("{$alert_join_text}가입이 완료되었습니다.\\n{$msg}\\n감사합니다.","member_joinend.php?auth_type={$auth_type}&name={$name}&id={$id}&bf_auth_type={$mem_auth_type}");
					} else {
						RollbackTrans();
						$onload="ID가 중복되었거나 회원등록 중 오류가 발생하였습니다.";
					}
				}
			}
		}
		if(ord($onload)) {
			echo "<html><head></head><body onload=\"alert('회원등록 중 오류가 발생하였습니다.\\n{$onload}\\n관리자에게 문의하시기 바랍니다.');\"></body></html>";exit;
		}
	}


	if(ord($news_mail_yn)==0) $news_mail_yn="Y";
	if(ord($news_sms_yn)==0) $news_sms_yn="Y";
	//if(true) {
		//$temp=SendSMS($sms_id, $sms_authkey, $mobile, "", $fromtel, $date, $msg_mem_join, $etcmessage);
	//}
?>

<?php
include('../front/include/top.php');
include('../front/include/gnb.php');

?>

<script src="<?=POST_JS;?>"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--

function ValidFormId(jointype, type) { //아이디 유효성 체크

	var val	= $("input[name=id]").val();
	if (val == '') {
		alert("아이디를 입력해 주세요.");
		$("input[name=id]").focus();
		return;
	} else {
        var regexp = /^[0-9]*$/;
        var idReg = /^[a-z]+[a-z0-9]/g;
        if(regexp.test(val) ) {
            alert("영문 소문자 또는 영문 소문자+숫자 조합하여 입력 가능합니다.");
            $("input[name=id]").focus();
            return;
        }
        else if (!idReg.test( val ) ){
			alert("영문 소문자 또는 영문 소문자+숫자 조합하여 입력 가능합니다.");
			$("input[name=id]").focus();
			return;
		}else if (val.length < 3 || val.length > 20) {
			alert("최소 3자리이상 최대 20자리까지 입력 가능합니다.");
			$("input[name=id]").focus();
			return;
		} else {
			if (type=='S') {
				if ($("#id_checked").val() == "0") {
					alert("아이디 중복확인을 해주세요.");
					$("input[name=id]").focus();
					return;
				} else {
                    var sns_id = $("input[name=sns_id]").val();
                    //alert(sns_id);
                    if(sns_id != ''){

                        $("#passwd1_checked").val("1");
                        $("#passwd2_checked").val("1");
                        ValidFormAddr(jointype, type);
                    }else{

                        ValidFormPassword(jointype, type);
                    }
                    return;
				}
			} else {
				$.ajax({ 
					type: "GET", 
					url: "../front/iddup.proc.php", 
					data: "id=" + val + "&mode=id",
                    async: false,
					dataType:"json", 
					success: function(data) {
						if(data.code=='0'){
							alert(data.msg);
							$("input[name=id]").focus();
							return;
						}else{
							$("#id_checked").val('1');
							alert('사용 가능한 아이디 입니다.');
							return;
						}						
					},
					error: function(result) {
						$("input[name=id]").parent().find(".type_txt1").html("에러가 발생하였습니다."); 
						$("input[name=id]").focus();
					}
				}); 
			}
		}
	}
}

function ValidFormPassword(jointype, type){//비밀번호 유효성 체크
	var val	= $("input[name=passwd1]").val();
	if (val == '') {
		alert("비밀번호를 입력해 주세요.");
		$("input[name=passwd1]").focus();
		return;
	}else{
		if (!(new RegExp(/^(?=.*[a-zA-Z])(?=.*[~!@#$%^*+=-])(?=.*[0-9]).{8,20}$/)).test(val)) {
			alert('영문 대소문자+숫자+특수문자 조합, 공백 없이 8자 ~ 20자리 이내로 입력해주세요.');
			$("input[name=passwd1]").focus();
			return;
		} else {
			if (type=='S')
			$("#passwd1_checked").val("1");
            ValidFormPasswordRe(jointype, type);
			return;
		}
	}
}

function ValidFormPasswordRe(jointype, type){ //비밀번호 확인 유효성 체크
	var val			= $("input[name=passwd2]").val();
	var pw1_val	= $("input[name=passwd1]").val();

	if (val == '') {
		alert("비밀번호 확인을 입력해 주세요.");
		$("input[name=passwd2]").focus();
		return;
	} else {
		if (val != pw1_val) {			
			alert("비밀번호가 일치하지 않습니다.");	
			$("input[name=passwd2]").focus();
			return;
		} else {
			$("#passwd2_checked").val("1");
			if (type=='S') ValidFormAddr(jointype, type);
			return;
		}
	}
}

function ValidFormAddr(jointype, type){ // 주소 유효성 체크
	var home_zonecode	= $("input[name=home_zonecode]").val();
	var home_post1			= $("input[name=home_post1]").val();
	var home_post2			= $("input[name=home_post2]").val();
	var home_addr1			= $("input[name=home_addr1]").val();
	var home_addr2			= $("input[name=home_addr2]").val();

	if (home_zonecode != '' || home_addr1 != '' || home_addr2 != '') {
		if (home_zonecode.length > 5) {
			alert("신주소를 입력해 주세요.");
			return;
		} else {
			if (home_addr1 == '' || home_addr2 == '') {
				alert("주소를 입력해 주세요.");
				return;
			} else {
				$("#home_addr_checked").val("1");
				ValidFormMobile(jointype, type);
				return;
			}
		}
	} else {
		$("#home_addr_checked").val("1");
		ValidFormMobile(jointype, type);
		return;
	}
}

function ValidFormMobile(jointype, type) { //휴대폰번호 유효성 체크
	var mobile2			= $("input[name=mobile2]").val();
	var mobile3			= $("input[name=mobile3]").val();

	if (mobile2 == '' || mobile3 == '') {
		alert('휴대폰 번호를 입력해 주세요');
		if (mobile2 == '') {
			$("input[name=mobile2]").focus();
		} else if (mobile3 == '') {
			$("input[name=mobile3]").focus();
		}
		return;
	} else {
        $("#mobile_checked").val("1");
		ValidFormEmail(jointype, type);
        return;
    }



}


function ValidFormEmail(jointype, type) { //이메일 유효성 체크
	var val1	= $("input[name=email1]").val();
	var val2	= $("input[name=email2]").val();
	if (val1 == '') {
		alert('이메일을 등록해 주세요');
		$("input[name=email1]").focus();
		return;
	} else {

		if($('#email_com').val()==''){
			alert("이메일 도메인을 선택해 주세요");
			$('#email_com').focus();
			return;
		}

		if (val2 == '') {
			alert("이메일 도메인을 입력해 주세요");
			$("input[name=email2]").focus();		
			return;
		} else {
			if (type=='S') {
				if ($("#email_checked").val() == '0')	{
					alert("이메일 중복확인을 해주세요.");
					$("input[name=email1]").focus();
					return;
				} else {
				    CheckFormSubmit();
					return;
				}
			} else {			
				var val = val1 + '@' + val2;
				$.ajax({ 
					type: "GET", 
					url: "../front/iddup.proc.php", 
					data: "email=" + val + "&mode=email",
					dataType:"json",
                    async: false,
					success: function(data) {			
						if (data.code == 0) {							
							alert(data.msg);
							return;
						} else {
							$("#email_checked").val('1');
							alert("등록하신 이메일 "+val+" 는(은) 사용 가능 합니다.");
							return;
						}
						
					},
					error: function(result) {
						alert("에러가 발생하였습니다.");
						$("input[name=email1]").focus();
					}
				}); 
			}
		}
	}
}




function CheckForm(jointype) {
    $("#passwd1_checked").val("0");
	$("#passwd2_checked").val("0");
	$("#home_addr_checked").val("0");
	$("#mobile_checked").val("0");
    ValidFormId(jointype, "S");
}

function CheckFormSubmit() {
	form=document.form1;

	var id_checked				= $("input[name=id_checked]").val();
	var passwd1_checked		= $("input[name=passwd1_checked]").val();
	var passwd2_checked		= $("input[name=passwd2_checked]").val();
	var home_addr_checked	= $("input[name=home_addr_checked]").val();
	var mobile_checked		= $("input[name=mobile_checked]").val();
	var email_checked			= $("input[name=email_checked]").val();


	$('#home_tel').val($("select[name=home_tel1] option:selected").val()+"-"+$("input[name=home_tel2]").val()+"-"+$("input[name=home_tel3]").val());
	$('#mobile').val($("select[name=mobile1] option:selected").val()+"-"+$("input[name=mobile2]").val()+"-"+$("input[name=mobile3]").val());
	$('#email').val($("input[name=email1]").val()+"@"+$("input[name=email2]").val());

	//alert(id_checked+"/"+passwd1_checked+"/"+passwd2_checked+"/"+home_addr_checked+"/"+mobile_checked+"/"+email_checked+"/");

	if (id_checked == '1' && passwd1_checked == '1' && passwd2_checked == '1' && home_addr_checked == '1' && mobile_checked == '1' && email_checked == '1' ) {
		form.type.value="insert";
		//form.target	= "HiddenFrame";

		if(confirm("회원가입을 하시겠습니까?")){
			var _jn='join'; //마케팅 스크립트2017-09-12
           form.submit();
		}
	}
}

function customChk(val){

	if((val=='custom')){
		$('#email2').show();
		$('#email2').val('');	
		$('#email2').focus();	
	}else{
		$('#email2').hide();
		$('#email2').val(val);	
	}
	
	
}
//-->
</SCRIPT>
<script>
document.onkeydown = function () {
     var backspace = 8;
     var t = document.activeElement;

     if (event.keyCode == backspace) {
         if (t.tagName == "SELECT")
             return false;

         if ((t.tagName == "INPUT" || t.tagName == "TEXTAREA") && $(t).attr("readonly") == "readonly"){
             return false;
         }
     }
}

function openDaumPostcode() {
	new daum.Postcode({
		oncomplete: function(data) {
			$("#home_zonecode").val(data.zonecode);
			$("#home_post1").val(data.postcode1);
			$("#home_post2").val(data.postcode2);
			$("#home_addr1").val(data.address);
			$("#home_addr2").val('');
			$("#home_addr2").focus();
		}
	}).open();
}

function submitCancel(){
	if(confirm('입력된 내용이 모두 삭제됩니다. 취소하시겠습니까?')){
		var add_par	= "";
		if ($("input[name=staff_join]").val() == 'Y') {
			add_par	= "?staff_join=Y";
		} else if ($("input[name=cooper_join]").val() == 'Y') {
			add_par	= "?cooper_join=Y";
		}
		location.href="member_certi.php"+add_par;
	}
}
$(document).ready( function() {
	$('.numbersOnly').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
});

</script>
<?
$erp_member_yn				= $_POST['erp_member_yn'];
$erp_member_id					= $_POST['erp_member_id'];
$erp_cust_name					= $_POST['erp_cust_name'];
$erp_birthday						= $_POST['erp_birthday'];
$erp_birth_gb						= $_POST['erp_birth_gb'];
$erp_cell_phone_no1			= $_POST['erp_cell_phone_no1'];
$erp_cell_phone_no2			= $_POST['erp_cell_phone_no2'];
$erp_cell_phone_no3			= $_POST['erp_cell_phone_no3'];
$erp_sex_gb						= $_POST['erp_sex_gb'];
$erp_job_cd						= $_POST['erp_job_cd'];
$erp_home_zip_old_new		= $_POST['erp_home_zip_old_new'];
$erp_home_zip_no				= $_POST['erp_home_zip_no'];
$erp_home_addr1				= $_POST['erp_home_addr1'];
$erp_home_addr2				= $_POST['erp_home_addr2'];
$erp_sms_yn						= $_POST['erp_sms_yn'];
$erp_kakao_yn					= $_POST['erp_kakao_yn'];
$erp_email1						= $_POST['erp_email1'];
$erp_email2						= $_POST['erp_email2'];
$erp_home_tel_no1				= $_POST['erp_home_tel_no1'];
$erp_home_tel_no2				= $_POST['erp_home_tel_no2'];
$erp_home_tel_no3				= $_POST['erp_home_tel_no3'];

$erp_home_zip_no	= str_replace("-", "", $erp_home_zip_no);
$erp_home_post1	= substr($erp_home_zip_no,0,3);
$erp_home_post2	= substr($erp_home_zip_no,3,3);

$mobileno1	= $mobileno1?$mobileno1:$erp_cell_phone_no1;
$mobileno2	= $mobileno2?$mobileno2:$erp_cell_phone_no2;
$mobileno3	= $mobileno3?$mobileno3:$erp_cell_phone_no3;

if (in_array($erp_email2, $email_domain_arr)) {
	$erp_email_com	= $erp_email2;
} else {
	$erp_email_com	= $erp_email2?"custom":"";
}
?>
<form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method="post">

<input type=hidden name=type value="">
<input type=hidden name=staff_join value="<?=$staff_join?>">
<?if ($staff_join != 'Y') {?><input type=hidden name=emp_id value=""><?}?>
<input type=hidden name=cooper_join value="<?=$cooper_join?>">
<input type=hidden name=auth_type value="<?=$auth_type?>">
<input type="hidden" name="dupinfo" value="<?=$_SESSION[ipin][dupinfo]?>">
<input type="hidden" name="conninfo" value="<?=$_SESSION[ipin][conninfo]?>">
<input type=hidden name=mem_type value="<?=$mem_type?>">
<input type=hidden name=join_type value="<?=$join_type?>">
<input type=hidden name=name_checked id=name_checked value="<?if($name&&$join_type=='1') { echo "1";} else {echo "0";}?>">
<input type=hidden name=id_checked id=id_checked value="0">
<input type=hidden name=passwd1_checked id=passwd1_checked value="0">
<input type=hidden name=passwd2_checked id=passwd2_checked value="0">
<input type=hidden name=home_addr_checked id=home_addr_checked value="0">
<input type=hidden name=mobile_checked id=mobile_checked value="0">
<input type=hidden name=email_checked id=email_checked value="0">
<input type=hidden name=emp_checked id=emp_checked value="0">
<input type=hidden name=sns_id id=sns_id value="<?=$sns_id?>">

<?php if($_data->ssl_type=="Y" && ord($_data->ssl_domain) && ord($_data->ssl_port) && $_data->ssl_pagelist["MJOIN"]=="Y") {?>
<input type=hidden name=shopurl value="<?=$_SERVER['HTTP_HOST']?>">
<?php }?>
<?php 
$leftmenu="Y";
if($_data->design_mbjoin=="U") {
	$sql="SELECT body,leftmenu FROM tbldesignnewpage WHERE type='mbjoin'";
	$result=pmysql_query($sql,get_db_conn());
	if($row=pmysql_fetch_object($result)) {
		$body=$row->body;
		$body=str_replace("[DIR]",$Dir,$body);
		$leftmenu=$row->leftmenu;
		$newdesign="Y";
	}
	pmysql_free_result($result);
}

list($id_che)=pmysql_fetch_array(pmysql_query("SELECT count(id) AS cnt FROM tblmember where id='".$sns_email[0]."'"));

include ($Dir.TempletDir."mbjoin/mbjoin{$_data->design_mbjoin}.php");

?>
</form>

<?=$onload?>

<IFRAME name="HiddenFrame" width=0 height=0 frameborder=0 scrolling="no" marginheight="0" marginwidth="0"></IFRAME>
<?php  include ($Dir."lib/bottom.php") ?>

</body>

</html>

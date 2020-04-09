<?
	header("Content-type: text/xml;charset=utf-8");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	
	$Dir="../../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."app/lib.app.php");
	include_once($Dir."lib/cache_main.php");
	include_once($Dir."lib/shopdata.php");
	include_once($Dir."lib/product.class.php");
	
	//exdebug($_POST);exit;
	$target_id = $_POST['target_id'];
	$Authorization = $_POST['Authorization'];

	$headersKey = "Authorization: ".$Authorization;
	/*
		실패시 결과 값
		stdClass Object
		(
			[msg] => NotRegisteredUserException
			[code] => -101
		)

		성공시 결과 값
		stdClass Object
		(
			[id] => 552639481
		)
	*/

	# target_id_type : 고정으로 변경 X
	# target_id : 로그인으로 생성된 회원의 키값
	$array = array('target_id_type' => 'user_id', 'target_id' => $target_id);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://kapi.kakao.com/v1/user/unlink");

	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION, 1 );
	# KakaoAK 뒤의 코드값은 기프츄 API 앱키 중 Admin 키를 입력.
	$headers[] = $headersKey;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$server_output = curl_exec ($ch);

	$apiOrderData = json_decode( $server_output );

	curl_close ($ch);
	

    
	if($apiOrderData->id){
	$sql_del = "DELETE FROM tblmember_sns WHERE id = '".$_POST['mem_id']."' and name = '".$_POST['mem_name']."' and sns_type = 'KAKAO' ";
		pmysql_query($sql_del,get_db_conn());
	//$sql_update = "UPDATE tblmember SET sns_type = replace(sns_type, '".$_POST['sns_id']."','')";
	$sql_update = "UPDATE tblmember SET sns_type = '' WHERE id = '".$_POST['mem_id']."' and name = '".$_POST['mem_name']."' ";
		pmysql_query($sql_update,get_db_conn());
	

	$responce['uid'] = $apiOrderData->id;
	$responce['msgs'] = "success";
	}else{
		$responce['msgs'] = "fail";
	}
	
	echo json_encode($responce);
	

?>
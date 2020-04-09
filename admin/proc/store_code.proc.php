<?php
/********************************************************************* 
// 파 일 명		: iddup.proc.php 
// 설     명		: 아이디, 닉네임, 이메일 체크
// 상세설명	: 아이디, 닉네임, 이메일 유무를 체크함
// 작 성 자		: hspark
// 수 정 자		: 2015.10.29 - 김재수
// 
// 
*********************************************************************/ 
?>
<?php
#---------------------------------------------------------------
# 기본정보를 설정한다.
#---------------------------------------------------------------
Header("Content-type: text/html; charset=utf-8");

$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");



if($_GET['mode'] == 'store_code'){
    $store_code=$_GET["store_code"];
	if($store_code){
		$code="0";
		$sql = "SELECT store_code FROM tblstore WHERE store_code='{$store_code}'";
		$result = pmysql_query($sql,get_db_conn());
		if ($row=pmysql_fetch_object($result)) {
			$message="매장코드가 중복되었습니다.";
		} else {
			$message="사용가능한 매장코드 입니다.";
			$code=1;
		}
	}else{
		$message="매장코드을 입력해주세요.";
	}
	pmysql_free_result($result);
}

$resultData = array("msg"=>$message, "code"=>$code);
echo urldecode(json_encode($resultData));
?>
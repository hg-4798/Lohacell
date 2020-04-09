<?
	header("Content-type: text/xml;charset=utf-8");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	
	$Dir="../../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");
	


	//exdebug($_POST);exit;
	if($_POST['now_sns']=='nv'){
	$sql_del = "DELETE FROM tblmember_sns WHERE id = '".$_POST['mem_id']."' and name = '".$_POST['mem_name']."' and sns_type = 'NAVER' ";
	 pmysql_query($sql_del,get_db_conn());
	
	
		
	$sql_update = "UPDATE tblmember SET sns_type = '' WHERE id = '".$_POST['mem_id']."' and name = '".$_POST['mem_name']."' ";

	 pmysql_query($sql_update,get_db_conn());
	

	//echo $sql_update;exit;
	$responce['msgs'] = "success";
	}else{
		$responce['msgs'] = "네이버 로그인후 다시 시도해주세요.";
	}
	echo json_encode($responce);
?>


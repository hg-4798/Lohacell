<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST, PUT');
	header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	$id = $_REQUEST['id'];
	if($id!=null) {
		$sql = "SELECT COUNT(*) as cnt FROM tblmember WHERE id='".$id."' ";
		$result= pmysql_query($sql,get_db_conn());
		$row = pmysql_fetch_object($result);
		if($row->cnt==0) {
			echo json_encode( array("result" => true) );
		} else {
			echo json_encode( array("result" => false) );
		}
	} else {
		echo json_encode( array("result" => "error") );
	}
?>
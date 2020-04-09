<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

$no = $_POST["no"];
$mode = $_POST['mode'];

if($mode == "getdata") {
    $delivery_addr = array(); // 배송지 정보

    $sql = "SELECT * FROM tbldestination WHERE no = '" . $no . "'";
    $result = pmysql_query($sql, get_db_conn());

    while ($row = pmysql_fetch_object($result)) {
        $delivery_addr[] = array(
            'no' => $row->no,
            'mem_id' => $row->mem_id,
            'destination_name' => $row->destination_name,
            'get_name' => $row->get_name,
            'mobile' => $row->mobile,
            'postcode' => $row->postcode,
            'postcode_new' => $row->postcode_new,
            'addr1' => $row->addr1,
            'addr2' => $row->addr2,
            'base_chk' => $row->base_chk
        );
    }
    echo json_encode( $delivery_addr );
}else if($mode == "delete"){
    #삭제
    $no = $_POST['no'];
    $dSql = "DELETE FROM tbldestination WHERE no = '".$no."'";
    pmysql_query($dSql, get_db_conn());

    if(!pmysql_error()){
        echo return_json(true, '삭제가 완료되었습니다.');
    }else{
        echo return_json(false, '오류가 발생하였습니다.');
    }
}else if($mode == "insert"){

    //print_r($_POST);
    Common::escapeData();
    $destination_name = $_POST["destination_name"];
    $get_name = $_POST["get_name"];
    $mobile = $_POST["mobile"];
    $postcode = $_POST["postcode"];
    $postcode_new = $_POST["postcode_new"];
    $addr1 = $_POST["addr1"];
    $addr2 = $_POST["addr2"];
    $base_chk = $_POST["chk"];
    $today = date("Y-m-d");
    #등록

    #새로 등록될 배송지가 기본 배송지 일 경우
    if($base_chk == "Y"){
        #기본 배송지로 등록되어 있는 no 조회
        $chkY = "SELECT no FROM tbldestination WHERE mem_id = '".$_ShopInfo->getMemid()."'
					AND base_chk = 'Y'";
        $chkRes = pmysql_query( $chkY, get_db_conn());
        $chkRow = pmysql_fetch_object($chkRes);

        if($chkRow->no){
            #기존 기본 배송지로 등록되어 있는 데이터를 N으로 업데이트
            $usql = "UPDATE tbldestination SET  base_chk = 'N' WHERE no = ".$chkRow->no;
            pmysql_query( $usql, get_db_conn());
        }
    }

    $iSql = "INSERT INTO tbldestination (
				mem_id,
				destination_name,
				get_name,
				mobile,
				postcode,
				postcode_new,
				addr1,
				addr2,
				base_chk,
				reg_date
				)values(
				'{$_ShopInfo->getMemid()}',
				'{$destination_name}',
				'{$get_name}',
				'{$mobile}',
				'{$postcode}',
				'{$postcode_new}',
				'{$addr1}',
				'{$addr2}',
				'{$base_chk}',
				'{$today}'
			)";

    $result = pmysql_query($iSql,get_db_conn());

    if(!pmysql_error()){
        echo return_json(true, '등록이 완료되었습니다.');
    }else{
        echo return_json(false, '오류가 발생하였습니다.');
    }

}else if($mode == "modify"){
    Common::escapeData();
    #수정
    $destination_name = $_POST["destination_name"];
    $get_name = $_POST["get_name"];
    $mobile = $_POST["mobile"];
    $postcode = $_POST["postcode"];
    $postcode_new = $_POST["postcode_new"];
    $addr1 = $_POST["addr1"];
    $addr2 = $_POST["addr2"];
    $base_chk = $_POST["chk"];
    $today = date("Y-m-d");

    if($base_chk == "Y" ){
        #기본 배송지로 등록되어 있는 no 조회
        $chkY = "SELECT no FROM tbldestination WHERE mem_id = '".$_ShopInfo->getMemid()."'
					AND base_chk = 'Y'";
        $chkRes = pmysql_query( $chkY, get_db_conn());
        $chkRow = pmysql_fetch_object($chkRes);

        if($chkRow->no){
            #기존 기본 배송지로 등록되어 있는 데이터를 N으로 업데이트
            $usql = "UPDATE tbldestination SET  base_chk = 'N' WHERE no = ".$chkRow->no."";
            pmysql_query( $usql, get_db_conn());
        }
    }

    $where[]="destination_name='".$destination_name."'";
    $where[]="get_name='".$get_name."'";
    $where[]="mobile='".$mobile."'";
    $where[]="postcode='".$postcode."'";
    $where[]="postcode_new='".$postcode_new."'";
    $where[]="addr1='".$addr1."'";
    $where[]="addr2='".$addr2."'";
    $where[]="base_chk='".$base_chk."'";

    $usql = "UPDATE tbldestination SET ";
    $usql.= implode(", ",$where);
    $usql.=" WHERE no = '".$no."'";

    pmysql_query( $usql, get_db_conn() );
    if(!pmysql_error()){
        echo return_json(true, '수정이 완료되었습니다.');
    }else{
        echo return_json(false, '오류가 발생하였습니다.');
    }

}

?>



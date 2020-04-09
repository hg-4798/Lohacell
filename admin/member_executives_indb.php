<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

@set_time_limit(0);

$conn = GetErpDBConn();
$year = date("Y");

$mem_id		=$_POST['mem_id'];
$emp_id = explode("sw", $_POST['chn_mem_id']);
$chn_mem_id =$_POST['chn_mem_id'];

$query = "select count(emp_id) as num from sw_emp_member where mem_id='{$mem_id}' or chn_mem_id='{$chn_mem_id}' or emp_id='{$emp_id[1]}'"; 


$row = pmysql_fetch( $query );


if($row['num'] == 0){ 
$sql = "insert into sw_emp_member (emp_id,mem_id,chn_mem_id) values('{$emp_id[1]}','{$mem_id}','{$chn_mem_id}')"; 
pmysql_query($sql,get_db_conn());

}else{ 
$sql = "update sw_emp_member set mem_id='{$mem_id}',chn_mem_id='{$chn_mem_id}' where emp_id='{$emp_id[1]}'"; 
pmysql_query($sql,get_db_conn());

} 




$mem_sql = "select emp_id, mem_id, chn_mem_id, erp_shopmem_id from (select a.*, b.id, b.erp_shopmem_id from sw_emp_member a left join tblmember b on a.mem_id=b.id AND b.staff_yn='N' AND a.mem_id != a.chn_mem_id) c where c.id is not null ";


$mem_result  = pmysql_query($mem_sql,get_db_conn());
$cnt = 0;
$row = pmysql_fetch_object($mem_result);

//exdebug($mem_sql);exit;

	$mem_id					= trim($row->mem_id);
	$emp_id						= trim($row->emp_id);
	$chn_mem_id				= trim($row->chn_mem_id);
	$erp_shopmem_id		= trim($row->erp_shopmem_id);
	$erp_emp_id				= trim($row->erp_emp_id);


if($emp_id){

	$meberinfo	= sendErpEshopidChange($erp_shopmem_id, $mem_id);	

	$code			= $meberinfo['p_err_code'];
	$p_err_text		= $meberinfo['p_err_text'];
	//$code=0;
	if ($code == '0') {
		$sql = "UPDATE tblbasket SET id='{$chn_mem_id}' WHERE id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblboardcomment_promo SET c_mem_id='{$chn_mem_id}' WHERE c_mem_id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblcouponissue SET id='{$chn_mem_id}' WHERE id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tbldestination SET mem_id='{$chn_mem_id}' WHERE mem_id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblexcelinfo SET mem_id='{$chn_mem_id}' WHERE mem_id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblmemberlog SET id='{$chn_mem_id}' WHERE id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblmykeyword SET id='{$chn_mem_id}' WHERE id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblorder_cancel SET reg_id='{$chn_mem_id}' WHERE reg_id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblorder_cancel_log SET reg_id='{$chn_mem_id}' WHERE reg_id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblorder_log SET reg_id='{$chn_mem_id}' WHERE reg_id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblorder_status_log SET reg_id='{$chn_mem_id}' WHERE reg_id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblorderproduct_log SET reg_id='{$chn_mem_id}' WHERE reg_id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblorderinfo SET id='{$chn_mem_id}' WHERE id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblorderinfotemp SET id='{$chn_mem_id}' WHERE id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblpoint_act SET mem_id='{$chn_mem_id}', rel_mem_id=replace(rel_mem_id, '{$mem_id}', '{$chn_mem_id}'), rel_job=replace(rel_job, '{$mem_id}', '{$chn_mem_id}') WHERE mem_id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";

		$sql = "UPDATE tblmember SET id='{$chn_mem_id}', erp_emp_id='{$emp_id}', staff_yn='Y' WHERE id='{$mem_id}' ";
		pmysql_query($sql,get_db_conn());
		//echo $sql."\r\n";
		$point = $year.'년 임직원 포인트';
		insert_staff_point_emp($chn_mem_id, '3000000', $point, '@start_staff_point', 'admin', date("YmdHis"), 0);
		//echo $sql; 
		//exit;
	}

	$cnt++;
	if( ($cnt%100) == 0) echo "cnt = ".$cnt."\r\n";
	if( ($cnt%1000) == 0) {
		sleep(10);
	}


//oci_free_statement($smt);
oci_close($conn);

pmysql_free_result($mem_result);


alert_go('적용되었습니다.','member_executives.php');

}else{
alert_go('잘못된 회원정보입니다.다시 시도해주세요.','member_executives.php');
}



// 임직원 포인트 지급 / 차감 2016-05-10 유동혁
function insert_staff_point_emp( $mem_id, $point, $body='', $rel_flag='', $rel_mem_id='', $rel_job='', $expire=0  ) {
    global $_data;

    // 포인트 사용을 하지 않는다면 return
    //if ($_data->reserve_maxuse < 0) { return 0; }

    // 포인트가 없다면 업데이트 할 필요 없음
    if ($point == 0) { return 0; }

    // 회원아이디가 없다면 업데이트 할 필요 없음
    if ($mem_id == '') { return 0; }
    $mb = pmysql_fetch(" SELECT id FROM tblmember WHERE id = '$mem_id' AND staff_yn = 'Y' ");
    //echo " select id from tblmember where id = '$mem_id' "."<br>";
    if (!$mb['id']) { return 0; }

    // 회원포인트
    $mb_point = get_point_staff_sum( $mem_id );
    //echo "mb_point = ".$mb_point."<br>";

    // 이미 등록된 내역이라면 건너뜀
    if ($rel_flag || $rel_mem_id || $rel_job)
    {
        $sql = " SELECT count(*) as cnt FROM tblpoint_staff
                  WHERE mem_id = '$mem_id'
                    AND rel_flag = '$rel_flag'
                    AND rel_mem_id = '$rel_mem_id'
                    AND rel_job = '$rel_job' ";
        $row = pmysql_fetch( $sql );
        //echo "sql2 = ".$sql."<br>";
        if ($row['cnt'])
            return -1;
    }

    // 포인트 건별 생성
    // expire : 1 => 만료
	$year = date("Y");
	$expire_date = $year.'1231';
    /* 소멸 포인트가 존재하지 않음
    if($_data->reserve_term > 0) {
        if($expire > 0) {
            //$expire_date = date('Ymd', strtotime('+'.($expire - 1).' days', time()));
			$lastdate	= date("t",strtotime('+'.($expire - 1).' days', time()));
			$expire_date = date('Ym', strtotime('+'.($expire - 1).' days', time())).$lastdate;
        } else {
            //$expire_date = date('Ymd', strtotime('+'.($_data->reserve_term - 1).' days', time()));
			$lastdate	= date("t",strtotime('+'.($_data->reserve_term - 1).' days', time()));
			$expire_date = date('Ym', strtotime('+'.($_data->reserve_term - 1).' days', time())).$lastdate;
		}
    }
    */
    $expire_chk = 0;
    if($point < 0) {
        $expire_chk = 1;
        $expire_date = date("Ymd");
    }
    $tot_point = $mb_point + $point;

    $sql = "INSERT INTO tblpoint_staff (mem_id, regdt, body, point, use_point, tot_point, expire_chk, expire_date, rel_flag ,rel_mem_id, rel_job)
            VALUES 
            ('$mem_id', '".date("YmdHis")."', '".addslashes($body)."', '$point', '0', '$tot_point', '$expire_chk', '$expire_date', '$rel_flag', '$rel_mem_id', '$rel_job') 
            ";
    pmysql_query($sql);
    //echo "sql3 = ".$sql."\r\n";

	// 임직원 포인트 ERP 전송 (김재수 - 2017.04.12 추가)
	erpTotalPointIns("staffpoint", $mem_id, addslashes($body), $rel_flag, $rel_job, $point, date("Ymd"));

    // 포인트를 사용한 경우 포인트 내역에 사용금액 기록
    if($point < 0) {
        //insert_use_staff_point( $mem_id, $point );
    }

    // 포인트 UPDATE
    $sql = " UPDATE tblmember SET staff_reserve = '$tot_point' WHERE id = '$mem_id' ";
    pmysql_query($sql);
    //echo "sql4 = ".$sql."\r\n";

    return 1;
}

function sendErpEshopidChange($shopmem_id, $mem_id) {

    global $conn;

	$data[p_data]			= "";
	$data[p_err_code]	= -9999;
	$data[p_err_text]	= "";


	$sql = "
				BEGIN 
					PA_ONLINE_MALL.SP_ESHOP_ID_CHANGE_PROCESS (  
						:P_NEW_MEMBER_ID, 
						:P_NEW_ESHOP_ID, 
						:P_ERR_CODE,
						:P_ERR_TEXT
					); 
				END;
			";
	
	//exdebug($sql);

	$smt_erp = oci_parse($conn, $sql);
	$_param	= array();

	//입력값
	oci_bind_by_name($smt_erp, ':P_NEW_MEMBER_ID', $shopmem_id);
	oci_bind_by_name($smt_erp, ':P_NEW_ESHOP_ID', $mem_id);

	$_param[':P_NEW_MEMBER_ID']	= $shopmem_id;
	$_param[':P_NEW_ESHOP_ID']	= $mem_id;

	//exdebug($_param);
	//exit;

	//출력값
	oci_bind_by_name($smt_erp, ':P_ERR_CODE', $data[p_err_code],32);
	oci_bind_by_name($smt_erp, ':P_ERR_TEXT', $data[p_err_text],300);

	$stid   = oci_execute($smt_erp);
	foreach($data as $k => $v)
	{
		$data[$k] = trim($v)==''?'':trim(utf8encode($v));
	}
	
	//exdebug($smt_erp);
	//exdebug($data);
	
	if(!$stid) 
	{ 
		$error = oci_error();
		print_r($error);
		$bt = debug_backtrace();
		error_log("\r\n".date("Y-m-d H:i:s ").realpath($_SERVER['DOCUMENT_ROOT'].$_SERVER['SCRIPT_NAME']).$error.$bt[0]['line'],3,"/tmp/error_log_sw_erp");
		error_log($sql."\r\n",3,"/tmp/error_log_sw_erp");
	}
	return $data;
}


?>

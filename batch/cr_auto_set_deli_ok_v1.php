#!/usr/local/php/bin/php
<?php
#######################################################################################
# FileName          : cr_auto_set_deli_ok_v1.php
# Desc              : 매일 자정에 돌면서 5영업일일이 지나면 자동으로 '배송완료'로 update시킨다.
# Last Updated      : 2018.02.06
# By                : spritoes(안성민)
#######################################################################################

$Dir="../";
include ($Dir."lib/init.php");
include ($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."conf/config.purchase_date.php");

list($order_day)=pmysql_fetch("select order_day from tblshopinfo ");
//사용안할경우 튕겨낸다.
if($order_day=="N") exit;

$today_date  =  date('Ymd');
$pre_date = chk_date($today_date,'6'); 

///////////////////////////////////// 오늘 기준으로 5영업일 이전날짜를 구한다/////////////////////////////////////
function chk_date($date,$count) { 
	$timestamp = strtotime($date);
	$step = $plus = 0; 
	while ( $step < $count ) { 

		list($d,$w) = explode(' ',date('Ymd w',$timestamp)); 
		$day_check = daycheck($d);

		$timestamp-= 86400; 
		$step++; 
		if ( $w%6==0 || $day_check >=1 || in_array($d,$GLOBALS['YY_'.$Y]) ) { 
			$plus++; $step--; 
		}
	}
	$pre_date = $d;
	return $pre_date;
}

function daycheck($date){

	$array = array(
	'type'=>'h',
	'year'=>date('Y', strtotime($date)),
	'month'=>date('m', strtotime($date)),
	'day'=>date('d', strtotime($date)),
	'TDCProjectKey' => 'cbca4ef3-40ca-46fc-907d-4a82c65540f3'
	);

	$c = json_decode(get('https://apis.sktelecom.com/v1/eventday/days', $array));
	return $c->totalResult;
}

function get($url, $params=array()) { 

    $url = $url.'?'.http_build_query($params, '', '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

///////////////////////////////////// 오늘 기준으로 5영업일 이전날짜를 구한다/////////////////////////////////////

$exe_id		= "||batch";	// 실행자 아이디|이름|타입

$sql = "Select	ordercode, idx, deli_date, op_step, order_conf_date
        From	tblorderproduct 
        Where 	1=1 
		And	    deli_date >= '{$pre_date}000000'
		And	    deli_date <= '{$pre_date}235959'
        And	    (order_conf_date is null  OR order_conf_date = '') 
        And	    op_step = 3 
		order by idx asc
        ";  
	exdebug($sql);

$result = pmysql_query($sql);

while ( $row = pmysql_fetch_object($result) ) {
    $ordercode          = $row->ordercode;
    $productorder_idx   = $row->idx;

    list($m_id) = pmysql_fetch("select id from  tblorderinfo where ordercode = '".$ordercode."' ");

    list($deli_reserve)=pmysql_fetch_array(pmysql_query("select reserve from tblorderproduct WHERE ordercode='".trim($ordercode)."' AND idx IN ('".str_replace("|", "','", $productorder_idx)."')"));
	exdebug($deli_reserve);

    $sql = "UPDATE tblorderproduct SET receive_ok = '1' ,deli_gbn='F', order_conf = '1', order_conf_date = '" .$today_date.date('His')."' ";
    $sql.= "WHERE ordercode='{$ordercode}' AND idx='{$productorder_idx}' ";
    $sql.= "AND op_step < 40 ";

   //pmysql_query($sql,get_db_conn());//실서버 적용시 주석제거
	exdebug($sql);
    if( !pmysql_error() ){
        // 신규상태 변경 추가 - (2016.02.18 - 김재수 추가)
		//실서버 적용시 주석제거
        //orderProductStepUpdate($exe_id, $ordercode, $productorder_idx, '4'); // 배송완료 실서버 적용시 주석제거
		//실서버 적용시 주석제거

        //적립 예정 적립금을 지급한다. 통합포인트는 erp에서 관리2017-04-28
        //if ($deli_reserve != 0) insert_point($m_id, $deli_reserve, "주문 ".$ordercode." 배송완료(".count($productorder_idx)."건)에 의한 포인트 지급", '','',"admin-".uniqid(''), $return_point_term);

        //주문중 배송완료, 취소완료상태가 아닌경우
        list($op_idx_cnt)=pmysql_fetch_array(pmysql_query("select count(idx) as op_idx_cnt from tblorderproduct WHERE ordercode='".trim($ordercode)."' AND (op_step != '4' AND op_step != '44')"));

		// 전체주문품목중 모든품목이 배송완료됬을때만 이므로 다음구문제거 >  AND idx NOT IN ('".str_replace("|", "','", $productorder_idx)."')
		// 배송완료가 아닌 갯수가 0일때만 tblorderinfo  update
        if ($op_idx_cnt == 0) {
            $sql = "UPDATE tblorderinfo SET receive_ok = '1', deli_gbn = 'F', order_conf = '1', order_conf_date = '" .$today_date.date('His')."' ";
            $sql.= "WHERE ordercode='{$ordercode}' ";

			exdebug($sql);
            //pmysql_query($sql,get_db_conn());//실서버 적용시 주석제거
        }

		//배송완료시 erp로 전송
		//sendErpOrderEndInfo($ordercode, $productorder_idx);//실서버 적용시 주석제거

        $msg    = "구매확정 되었습니다.";
        $msgType = "1";
    } else {
        $msg = "구매확정 실패. 관리자에게 문의해주세요.";
        $msgType = "0";
    }

    echo "주문번호 : " . $ordercode . ", 주문idx : " . $productorder_idx . " ===> " . $msg . "\n";
}

?>

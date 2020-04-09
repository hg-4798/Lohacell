<?php
/**
 * kcp 카드/실시간계좌이체 취소신청
 */


$Dir = "../../../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");


if($_POST['order_status'] == '1') { //주문완료(입금대기)인경우 패스
	return_json(true);
}

//POST 형식 체크부분
if ( $_SERVER['REQUEST_METHOD'] != "POST" ) {
	return_json(false,'잘못된 경로로 접속하였습니다.');
	exit;
}


//02. 지불 요청 정보 설정 START
include(DOC_ROOT.'/../_pg/NHNKCP/cfg/conf.php'); //환경설정 파일 include
require "hub_lib.php"; // library [수정불가]


$req_tx         = "mod"; 							// 요청 종류		
$tran_cd        = $_POST["tran_cd"];   // 처리 종류	
$cust_ip        = getenv("REMOTE_ADDR");   // 요청 IP
/* = -------------------------------------------------------------------------- = */
$res_cd         = "";                                                     // 응답코드
$res_msg        = "";                                                     // 응답 메세지
$tno            = $_POST["tno"];                              // KCP 거래 고유 번호
$card_cd        = $_POST["card_cd"];							  // 카드 코드
$card_name      = $_POST["card_name"];	  						  // 카드 명
$amount      	= $_POST["amount"];	  						  // 취소요청시간
$coupon_mny     = $_POST["coupon_mny"];   						      // 쿠폰 금액
$canc_time      = $_POST["canc_time"];    						  // 취소 시간 
/* = -------------------------------------------------------------------------- = */
$mod_type       = $_POST["mod_type"];                              // 변경TYPE(승인취소시 필요)
$mod_desc       = "";                                                     // 변경사유
$panc_mod_mny   = str_replace(',','',$_POST["mod_mny"]);                              // 부분취소 금액
$panc_rem_mny   = str_replace(',','',$_POST["rem_mny"]);                              // 부분취소 가능 금액
$panc_coupon_mod_mny = "";												  // 쿠폰 부분 취소 요청 금액
$panc_card_mod_mny ="";													  // 카드 부분 취소 요청 금액 
$mod_tax_mny    = "";                                                     // 공급가 부분 취소 요청 금액
$mod_vat_mny    = "";                                                     // 부과세 부분 취소 요청 금액
$mod_free_mny   = "";                                                     // 비과세 부분 취소 요청 금액


//03. 인스턴스 생성 및 초기화(변경 불가) START
//결제에 필요한 인스턴스를 생성하고 초기화 합니다.
$c_PayPlus = new C_PP_CLI;
$c_PayPlus->mf_clear();


//04. 처리 요청 정보 설정 START
//04-1. 취소/매입 요청
if ( $req_tx == "mod" ) {
	$tran_cd = "00200000";

	$c_PayPlus->mf_set_modx_data( "tno",        $tno        ); // KCP 원거래 거래번호
	$c_PayPlus->mf_set_modx_data( "mod_type",   $mod_type   ); // 전체취소 STSC / 부분취소 STPC 
	$c_PayPlus->mf_set_modx_data( "mod_ip",     $cust_ip    ); // 변경 요청자 IP
	$c_PayPlus->mf_set_modx_data( "mod_desc",   ""          ); // 변경 사유

	if ( $mod_type == "STPC" ) { // 부분취소의 경우
	

		$c_PayPlus->mf_set_modx_data( "mod_mny", $panc_mod_mny ); // 취소요청금액
		$c_PayPlus->mf_set_modx_data( "rem_mny", $panc_rem_mny ); // 취소가능잔액

		//복합거래 부분 취소시 주석을 풀어 주시기 바랍니다.
		//$c_PayPlus->mf_set_modx_data( "tax_flag",     "TG03"          ); // 복합과세 구분
		//$c_PayPlus->mf_set_modx_data( "mod_tax_mny",  mod_tax_mny     ); // 공급가 부분 취소 요청 금액
		//$c_PayPlus->mf_set_modx_data( "mod_vat_mny",  mod_vat_mny     ); // 부과세 부분 취소 요청 금액
		//$c_PayPlus->mf_set_modx_data( "mod_free_mny", mod_free_mny    ); // 비과세 부분 취소 요청 금액
	}
}

// 05. 실행
if ( $tran_cd != "" )  {
	//$c_PayPlus->mf_do_tx( "", $home_dir, $site_cd, $site_key, $tran_cd, "",
							//$gw_url, $gw_port, "payplus_cli_slib", "",
							//$cust_ip, "3", 0, 0, $log_path ); // 응답 전문 처리

	// windows 사용시
	$c_PayPlus->mf_do_tx( "", $g_conf_home_dir, $g_conf_site_cd, $g_conf_site_key, $tran_cd, "",
							$g_conf_gw_url, $g_conf_gw_port, "payplus_cli_slib", "",
							"", "3" , 0, 0, $g_conf_key_dir, $g_conf_log_dir); // 응답 전문 처리
}
else {
	$c_PayPlus->m_res_cd  = "9562";
	$c_PayPlus->m_res_msg = "연동 오류|Payplus Plugin이 설치되지 않았거나 tran_cd값이 설정되지 않았습니다.";
}

$res_cd  = $c_PayPlus->m_res_cd;  // 결과 코드
$res_msg = $c_PayPlus->m_res_msg; // 결과 메시지

$res_msg = iconv('euc-kr','utf-8',$res_msg);

if ( $res_cd == "0000" ){ // 정상결제 인 경우
	$card_cd    = $c_PayPlus->mf_get_res_data( "card_cd"    ); 	// 카드사 코드
	$card_name  = $c_PayPlus->mf_get_res_data( "card_name"  ); 	// 카드 명
	$amount     = $c_PayPlus->mf_get_res_data( "amount"  	); 	// 결제 금액
	$coupon_mny = $c_PayPlus->mf_get_res_data( "coupon_mny" ); 	// 쿠폰금액 
	$canc_time  = $c_PayPlus->mf_get_res_data( "canc_time"   ); // 취소 시간

	if ( $mod_type == "STPC") { //부분 취소 정상결제인 경우 
		$card_cd      		= $c_PayPlus->mf_get_res_data( "card_cd"    );
		$card_name    		= $c_PayPlus->mf_get_res_data( "card_name"  );
		$amount    	 		= $c_PayPlus->mf_get_res_data( "amount"  	);
		$coupon_mny 		= $c_PayPlus->mf_get_res_data( "coupon_mny" ); 
		$canc_time 			= $c_PayPlus->mf_get_res_data( "canc_time"  ); 
		$panc_mod_mny 		= $c_PayPlus->mf_get_res_data( "panc_mod_mny" ); 	
		$panc_rem_mny   	= $c_PayPlus->mf_get_res_data( "panc_rem_mny" );
		$panc_card_mod_mny  = $c_PayPlus->mf_get_res_data( "panc_card_mod_mny" );
		$panc_coupon_mod_mny = $c_PayPlus->mf_get_res_data( "panc_coupon_mod_mny" );
	}
}

if ( $res_cd== "0000" && $mod_type == "STSC") {
	$return = array(
		'res_cd'=>$res_cd,
		'card_cd'=>$card_cd,
		'card_name'=>iconv('euc-kr','utf-8',$card_name),
		'amount'=>$amount,
		'coupon_mny'=>$coupon_mny,
		'canc_time'=>$canc_time,
		'res_msg'=>$res_msg
	);

	return_json(true, '취소요청이 완료되었습니다.', $return);
}
else if ( $res_cd == "0000" && $mod_type == "STPC") {
	$return = array(
		'res_cd'=>$res_cd,
		'card_cd'=>$card_cd,
		'card_name'=>iconv('euc-kr','utf-8',$card_name),
		'amount'=>$amount,
		'coupon_mny'=>$coupon_mny,
		'canc_time'=>$canc_time,
		'panc_mod_mny'=>$panc_mod_mny,
		'panc_rem_mny'=>$panc_rem_mny,
		'panc_card_mod_mny'=>$panc_card_mod_mny,
		'panc_coupon_mod_mny'=>$panc_coupon_mod_mny,
		'res_msg'=>$res_msg
	);

	return_json(true, '취소요청이 완료되었습니다.', $return);
}
else {
	return_json(false, '취소요청이 처리 되지 못하였습니다', array('res_cd'=>$res_cd, 'res_msg'=>$res_msg));
}

?>
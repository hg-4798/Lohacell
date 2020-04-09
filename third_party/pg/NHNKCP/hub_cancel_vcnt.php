<?php
/**
 * kcp 가상계좌 취소처리
 */

setlocale(LC_CTYPE, 'ko_KR.euc-kr');

$Dir = "../../../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");


if($_POST['order_status'] == '1') { //주문완료(입금대기)인경우 패스
	return_json(true);
}


include(DOC_ROOT.'/../_pg/NHNKCP/cfg/conf.php'); //환경설정 파일 include
require "hub_lib.php";			  // library [수정불가]


if ( $_SERVER['REQUEST_METHOD'] != "POST" ) {
	return_json(false,'잘못된 경로로 접속하였습니다.');
	exit;
}


/* ============================================================================== */
/* =   01. 지불 요청 정보 설정                                                  = */
/* = -------------------------------------------------------------------------- = */
$cust_ip      = getenv( "REMOTE_ADDR"    );    // 요청 IP
$req_tx       = $_POST[ "req_tx"       ];      // 요청 종류
$tran_cd      = "";                            // 트랜잭션 코드
/* = -------------------------------------------------------------------------- = */
$res_cd       = "";                            // 결과코드
$res_msg      = "";                            // 결과메시지
/* = -------------------------------------------------------------------------- = */
$mod_type      = $_POST[ "mod_type"      ];     // 변경유형
$mod_desc      = $_POST[ "mod_desc"      ];     // 변경유형
$tno           = $_POST[ "tno"           ];     // KCP 거래번호
$mod_mny       = str_replace(',','',$_POST[ "mod_mny"       ]);     // 환불금액
$rem_mny       = str_replace(',','',$_POST[ "rem_mny"       ]);     // 환불 전 금액
$mod_bankcode  = 'BK'.$_POST[ "mod_bankcode"  ];     // 은행 코드
$mod_account   = $_POST[ "mod_account"   ];     // 발급 계좌
$mod_depositor = iconv('utf-8','euc-kr',$_POST["mod_depositor"]);


$mod_comp_type = $_POST[ "mod_comp_type" ];     // 은행 코드
$mod_socno     = $_POST[ "mod_socno"     ];     // 발급 계좌
$mod_socname   = $_POST[ "mod_socname"   ];     // 예금주

/* ============================================================================== */

/* ============================================================================== */
/* =   02. 인스턴스 생성 및 초기화                                              = */
/* = -------------------------------------------------------------------------- = */
/* =       결제에 필요한 인스턴스를 생성하고 초기화 합니다.                     = */
/* = -------------------------------------------------------------------------- = */
$c_PayPlus = new C_PP_CLI;

$c_PayPlus->mf_clear();
/* ------------------------------------------------------------------------------ */
/* =   02. 인스턴스 생성 및 초기화 END                                          = */
/* ============================================================================== */


/* ============================================================================== */
/* =   03. 처리 요청 정보 설정, 실행                                            = */
/* = -------------------------------------------------------------------------- = */

/* = -------------------------------------------------------------------------- = */
/* =   03-1. 승인 요청                                                          = */
/* = -------------------------------------------------------------------------- = */
// 업체 환경 정보
if ( $req_tx == "mod" ) {
	$tran_cd = "00200000";
	$c_PayPlus->mf_set_modx_data( "mod_type",  $mod_type              );     // 원거래 변경 요청 종류
	$c_PayPlus->mf_set_modx_data( "tno",       $tno                   );     // 거래번호
	$c_PayPlus->mf_set_modx_data( "mod_ip",    $cust_ip               );     // 변경 요청자 IP
	$c_PayPlus->mf_set_modx_data( "mod_desc",  $mod_desc              );     // 변경 사유


	$c_PayPlus->mf_set_modx_data( "mod_bankcode",   $mod_bankcode    );      // 환불 요청 은행 코드
	$c_PayPlus->mf_set_modx_data( "mod_account",    $mod_account     );      // 환불 요청 계좌
	$c_PayPlus->mf_set_modx_data( "mod_depositor",  $mod_depositor   );      // 환불 요청 계좌주명

	if ( $mod_type == "STHD" )
	{ //전체환불
		$c_PayPlus->mf_set_modx_data( "mod_sub_type",   "MDSC00"        );      // 변경 유형
	}
	else if ( $mod_type == "STPD" )
	{

		$c_PayPlus->mf_set_modx_data( "mod_sub_type",   "MDSC03"        );      // 변경 유형
		$c_PayPlus->mf_set_modx_data( "mod_mny",        $mod_mny        );      // 환불 요청 금액
		$c_PayPlus->mf_set_modx_data( "rem_mny",        $rem_mny        );      // 환불 전 금액
	}

	if ( $mod_comp_type == "MDCP01" )
	{
		$c_PayPlus->mf_set_modx_data( "mod_comp_type",   "MDCP01"       );      // 변경 유형
	}
	else if ( $mod_comp_type == "MDCP02" )
	{
		$c_PayPlus->mf_set_modx_data( "mod_comp_type",   "MDCP02"        );      // 변경 유형
		$c_PayPlus->mf_set_modx_data( "mod_socno",       $mod_socno       );      // 실명확인 주민번호
		$c_PayPlus->mf_set_modx_data( "mod_socname",     $mod_socname     );      // 실명확인 성명
	}
}

// 04. 실행
if ( $tran_cd != "" ) {
	$c_PayPlus->mf_do_tx( $trace_no, $g_conf_home_dir, $g_conf_site_cd, $g_conf_site_key, $tran_cd, "",
							$g_conf_gw_url, $g_conf_gw_port, "payplus_cli_slib", $ordr_idxx,
							$cust_ip, $g_conf_log_level, 0, 0, $g_conf_log_path ); // 응답 전문 처리
}
else {
	$c_PayPlus->m_res_cd  = "9562";
	$c_PayPlus->m_res_msg = "연동 오류|Payplus Plugin이 설치되지 않았거나 tran_cd값이 설정되지 않았습니다.";
}

$res_cd  = $c_PayPlus->m_res_cd;  // 결과 코드
$res_msg = $c_PayPlus->m_res_msg; // 결과 메시지
/* ============================================================================== */


// 05. 가상계좌 환불 결과 처리
if ($req_tx == "mod") {
	$res_msg = iconv('euc-kr','utf-8',$res_msg);

	if ($res_cd == "0000" ) {
		$tno = $c_PayPlus->mf_get_res_data( "tno" );       // KCP 거래 고유 번호
		$res = array(
			'req_tx'=>$req_tx,
			'res_cd'=>$res_cd,
			'res_msg'=>$res_msg,
			'mod_account'=>$mod_account,
			'mod_depositor'=>iconv('euc-kr','utf-8',$mod_depositor),
			'mod_bankcode'=>$mod_bankcode,
			'tno'=>$tno
		);

		return_json(true, '취소요청이 완료되었습니다.', $res);
	}
	else if ( $res_cd != "0000" ) {
		$res = array(
			'req_tx'=>$req_tx,
			'res_cd'=>$res_cd,
			'res_msg'=>$res_msg,
			'mod_account'=>$mod_account,
			'mod_depositor'=>iconv('euc-kr','utf-8',$mod_depositor),
			'mod_bankcode'=>$mod_bankcode,
		);
		return_json(false,'취소요청이 처리 되지 못하였습니다 '.$res_cd, $res);
	}
}
/* End of Process */
exit;
?>
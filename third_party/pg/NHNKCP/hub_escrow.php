<?php
/**
 * 에스크로 상태변경
 * @date 2018-11-01
 */
setlocale(LC_CTYPE, 'ko_KR.euc-kr');

$Dir = "../../../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");


//POST 형식 체크부분
if ( $_SERVER['REQUEST_METHOD'] != "POST" ) {
	return_json(false,'잘못된 경로로 접속하였습니다.');
	exit;
}

//02. 지불 요청 정보 설정 START
include(DOC_ROOT.'/../_pg/NHNKCP/cfg/conf.php'); //환경설정 파일 include
require "hub_lib.php"; // library [수정불가]

$Order = new ORDER;
$order_num = $_POST['order_num'];

$order_basic = $Order->getBasicRow($order_num);
if($order_basic['order_status'] < 2) return_json(true, ''); //입금전 취소는 에스크로프로세스가 필요없음

//01. 구매후 취소 요청 정보 설정 START

$req_tx            = $_POST[ "req_tx"           ]; // 요청종류
$cust_ip           = getenv( "REMOTE_ADDR"      ); // 요청 IP
$tran_cd           = "";
$res_cd            = "";                                                       // 응답코드
$res_msg           = "";                                                       // 응답메시지
/* ============================================================================== */
$mod_type          = $_POST[ "mod_type"         ]; // 변경수단 
$tno               = $_POST[ "tno"              ]; // 거래번호
$mod_desc          = $_POST[ "mod_desc"         ]; // 취소사유
$mod_depositor     = iconv('utf-8','euc-kr',$_POST[ "mod_depositor"    ]); // 환불계좌주명(환불시에만 사용)
$mod_account       = $_POST[ "mod_account"      ]; // 환불계좌번호(환불시에만 사용)
$mod_bankcode      = $_POST[ "mod_bankcode"     ]; // 환불은행코드(환불시에만 사용)
$mod_sub_type      = $_POST[ "mod_sub_type"     ]; // 취소상세구분
$sub_mod_type      = $_POST[ "sub_mod_type"     ]; // 취소유형
/* ============================================================================== */
$vcnt_yn           = $_POST[ "vcnt_yn"          ]; // 상태변경시 계좌이체, 가상계좌 여부
/* = -------------------------------------------------------------------------- = */
$y_rem_mny         = $_POST[ "rem_mny"          ]; // 환불 가능 금액
$y_mod_mny         = str_replace(',','',$_POST[ "mod_mny"          ]); // 환불 금액
$y_tax_mny         = $_POST[ "tax_mny"          ]; // 부분취소 과세금액
$y_free_mod_mny    = $_POST[ "free_mod_mny"     ]; // 부분취소 비과세금액
$y_add_tax_mny     = $_POST[ "add_tax_mny"      ]; // 부분취소 부과세 금액
$y_refund_account  = $_POST[ "a_refund_account" ]; // 환불계좌번호
$y_refund_nm       = iconv('utf-8','euc-kr',$_POST[ "a_refund_nm"      ]); // 환불계좌주명
$y_bank_code       = $_POST[ "a_bank_code"      ]; // 은행코드
$y_mod_desc_cd     = $_POST[ "mod_desc_cd"      ]; // 취소구분
$y_mod_desc        = $_POST[ "mod_desc"         ]; // 취소사유

// 01. 구매후 취소 요청 정보 설정 END

// 02. 인스턴스 생성 및 초기화(변경 불가) START
$c_PayPlus = new C_PP_CLI;
$c_PayPlus->mf_clear();
//  02. 인스턴스 생성 및 초기화 END

//pre($_POST);
// 03. 처리 요청 정보 설정 START
// 03-1. 에스크로 상태변경 요청
if ( $req_tx == "mod_escrow" ) {

	$c_PayPlus->mf_set_modx_data( "tno",        $_POST[ "tno"       ] );      // KCP 원거래 거래번호
	$c_PayPlus->mf_set_modx_data( "mod_ip",     $cust_ip              );      // 변경 요청자 IP
	$c_PayPlus->mf_set_modx_data( "mod_desc",   $_POST[ "mod_desc"  ] );      // 변경 사유
	
	//부분취소여부체크
	if(in_array($mod_type, array('STE9_C','STE9_A','STE9_V'))) {
		
		
		$payment_info = $Order->getPaymentRow($order_num);

		if($payment_info['tx_cd'] !='TX01' && $mod_type == 'STE9_V') {
			return_json(false, '에스크로 상태변경에 실패하였습니다.', array('res_msg'=>'진행중인 환불건이 있습니다.'));
			exit;
		}

		if($y_rem_mny != $mod_mny || $payment_info['tx_cd'] != 'TX02') {
			$mod_type.='P';
		}

		$_POST['mod_type'] = $mod_type;

	}


	if( $mod_type == "STE9_C"  || $mod_type == "STE9_CP" ||
		$mod_type == "STE9_A"  || $mod_type == "STE9_AP" ||
		$mod_type == "STE9_AR" || $mod_type == "STE9_V"  ||
		$mod_type == "STE9_VP" )
	{
		$tran_cd = "70200200";
		$c_PayPlus->mf_set_modx_data( "mod_type"    , "STE9"         );
		$c_PayPlus->mf_set_modx_data( "mod_desc_cd" , $y_mod_desc_cd );
		$c_PayPlus->mf_set_modx_data( "mod_desc"    , $y_mod_desc    );

		if( $mod_type == "STE9_C" )
		{
			$c_PayPlus->mf_set_modx_data( "sub_mod_type"    , "STSC"            );
			$c_PayPlus->mf_set_modx_data( "mod_sub_type"    , "MDSC03"          );
		}
		else if( $mod_type == "STE9_CP" )
		{
			$c_PayPlus->mf_set_modx_data( "sub_mod_type"    , "STPC"            );
			$c_PayPlus->mf_set_modx_data( "part_canc_yn"    , "Y"               );
			$c_PayPlus->mf_set_modx_data( "rem_mny"         , $y_rem_mny        );
			$c_PayPlus->mf_set_modx_data( "amount"          , $y_mod_mny        );
			$c_PayPlus->mf_set_modx_data( "mod_mny"         , $y_mod_mny        );
			$c_PayPlus->mf_set_modx_data( "mod_sub_type"    , "MDSC03"          );
			//$c_PayPlus->mf_set_modx_data( "tax_flag"        , "TG03"            ); // 복합과세 부분취소
			//$c_PayPlus->mf_set_modx_data( "mod_tax_mny"     , $y_tax_mny        ); // 공급가 부분취소 금액
			//$c_PayPlus->mf_set_modx_data( "mod_free_mny"    , $y_free_mod_mny   ); // 비과세 부분취소 금액
			//$c_PayPlus->mf_set_modx_data( "mod_vat_mny"     , $y_add_tax_mny    ); // 부가세 부분취소 금액
		}
		else if( $mod_type == "STE9_A")
		{
			$c_PayPlus->mf_set_modx_data( "sub_mod_type"    , "STSC"            );
			$c_PayPlus->mf_set_modx_data( "mod_sub_type"    , "MDSC03"          );
		}
		else if( $mod_type == "STE9_AP")
		{
			$c_PayPlus->mf_set_modx_data( "sub_mod_type"    , "STPC"            );
			$c_PayPlus->mf_set_modx_data( "part_canc_yn"    , "Y"               );
			$c_PayPlus->mf_set_modx_data( "rem_mny"         , $y_rem_mny        );
			$c_PayPlus->mf_set_modx_data( "amount"          , $y_mod_mny        );
			$c_PayPlus->mf_set_modx_data( "mod_mny"         , $y_mod_mny        );
			$c_PayPlus->mf_set_modx_data( "mod_sub_type"    , "MDSC04"          );
			//$c_PayPlus->mf_set_modx_data( "tax_flag"        , "TG03"            ); // 복합과세 부분취소
			//$c_PayPlus->mf_set_modx_data( "mod_tax_mny"     , $y_tax_mny        ); // 공급가 부분취소 금액
			//$c_PayPlus->mf_set_modx_data( "mod_free_mny"    , $y_free_mod_mny   ); // 비과세 부분취소 금액
			//$c_PayPlus->mf_set_modx_data( "mod_vat_mny"     , $y_add_tax_mny    ); // 부가세 부분취소 금액
		}
		else if( $mod_type == "STE9_AR")
		{
			$c_PayPlus->mf_set_modx_data( "sub_mod_type"    , "STHD"            );
			$c_PayPlus->mf_set_modx_data( "mod_mny"         , $y_mod_mny        );
			$c_PayPlus->mf_set_modx_data( "mod_sub_type"    , "MDSC04"          );
			$c_PayPlus->mf_set_modx_data( "mod_bankcode"    , $y_bank_code      );
			$c_PayPlus->mf_set_modx_data( "mod_account"     , $y_refund_account );
			$c_PayPlus->mf_set_modx_data( "mod_depositor"   , $y_refund_nm      );
		}
		else if( $mod_type == "STE9_V")
		{
			$c_PayPlus->mf_set_modx_data( "sub_mod_type"    , "STHD"            );
			$c_PayPlus->mf_set_modx_data( "mod_mny"         , $y_mod_mny        );
			$c_PayPlus->mf_set_modx_data( "mod_sub_type"    , "MDSC00"          );
			$c_PayPlus->mf_set_modx_data( "mod_bankcode"    , $y_bank_code      );
			$c_PayPlus->mf_set_modx_data( "mod_account"     , $y_refund_account );
			$c_PayPlus->mf_set_modx_data( "mod_depositor"   , $y_refund_nm      );
		}
		else if( $mod_type == "STE9_VP")
		{
			$y_bank_code = 'BK'.$y_bank_code;

			$c_PayPlus->mf_set_modx_data( "sub_mod_type"    , "STPD"            );
			$c_PayPlus->mf_set_modx_data( "mod_mny"         , $y_mod_mny        );
			$c_PayPlus->mf_set_modx_data( "rem_mny"         , $y_rem_mny        );
			$c_PayPlus->mf_set_modx_data( "mod_sub_type"    , "MDSC04"          );
			$c_PayPlus->mf_set_modx_data( "mod_bankcode"    , $y_bank_code      );
			$c_PayPlus->mf_set_modx_data( "mod_account"     , $y_refund_account );
			$c_PayPlus->mf_set_modx_data( "mod_depositor"   , $y_refund_nm      );
			//$c_PayPlus->mf_set_modx_data( "tax_flag"        , "TG03"            ); // 복합과세 부분취소           
			//$c_PayPlus->mf_set_modx_data( "mod_tax_mny"     , $y_tax_mny        ); // 공급가 부분취소 금액
			//$c_PayPlus->mf_set_modx_data( "mod_free_mny"    , $y_free_mod_mny   ); // 비과세 부분취소 금액
			//$c_PayPlus->mf_set_modx_data( "mod_vat_mny"     , $y_add_tax_mny    ); // 부가세 부분취소 금액
			$c_PayPlus->mf_set_modx_data( "part_canc_yn"    , "Y"               );
		}
	}
	else
	{
		$tran_cd = "00200000";
	
		$c_PayPlus->mf_set_modx_data( "mod_type",   $mod_type                           );      // 원거래 변경 요청 종류

		if ( $mod_type == "STE1")                                                                  // 상태변경 타입이 [배송요청]인 경우
		{
			$c_PayPlus->mf_set_modx_data( "deli_numb", $_POST[ "deli_numb" ] );      // 운송장 번호
			$deli_corp = iconv('utf-8','euc-kr',$_POST[ "deli_corp" ]);
			$c_PayPlus->mf_set_modx_data( "deli_corp", $deli_corp );      // 택배 업체명
		}
		if ( $mod_type == "STE2" || $mod_type == "STE4" )                                       // 상태변경 타입이 [즉시취소] 또는 [취소]인 계좌이체, 가상계좌의 경우
		{	
			$c_PayPlus->mf_set_modx_data( "refund_account", $mod_account    );  // 환불수취계좌번호
			$c_PayPlus->mf_set_modx_data( "refund_nm",      $mod_depositor  );  // 환불수취계좌주명
			$c_PayPlus->mf_set_modx_data( "bank_code",      $mod_bankcode      );  // 환불수취은행코드
			
		}
	}
}
// 03. 에스크로 상태변경 요청 END

// 04. 실행 START
if ( $tran_cd != "" )
{
	$c_PayPlus->mf_do_tx( "", $g_conf_home_dir, $g_conf_site_cd, $g_conf_site_key, $tran_cd, "",
							$g_conf_gw_url, $g_conf_gw_port, "payplus_cli_slib", $ordr_idxx,
							$cust_ip, $g_conf_log_level, 0, 0, $g_conf_log_path ); // 응답 전문 처리

	$res_cd  = $c_PayPlus->m_res_cd;  // 결과 코드
	$res_msg = $c_PayPlus->m_res_msg; // 결과 메시지

}
else
{
	$c_PayPlus->m_res_cd  = "9562";
	$c_PayPlus->m_res_msg = "연동 오류|Payplus Plugin이 설치되지 않았거나 tran_cd값이 설정되지 않았습니다.";
}
//  04. 실행 END


//05.구매확인 후 취소 성공/실패 결과 처리 START

$res_msg = iconv('euc-kr','utf-8',$res_msg);
$return = array(
	'res_code'=>$res_code,
	'res_msg'=>$res_msg
);

$Order = new ORDER;
$Order->log_file(array_merge($_POST,$return));


if ( $req_tx == "mod_escrow" ) { 
	if( $res_cd == "0000" ) { //취소성공 결과 처리
		return_json(true, '', $return);
	} 
	else { //취소실패 결과 처리
		return_json(false, '에스크로 상태변경에 실패하였습니다.', $return);
	}
}

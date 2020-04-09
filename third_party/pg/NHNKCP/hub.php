<?php
/**
 * kcp 결제완료처리페이지
 * @author hjlee
 */

if($_SERVER['REMOTE_ADDR'] == '218.234.32.14') {
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	ini_set("display_errors", 1);
}

$Dir = "../../../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");


include(DOC_ROOT.'/../_pg/NHNKCP/cfg/conf.php'); //환경설정 파일 include
require "hub_lib.php";			  // library [수정불가]


if($_POST['res_cd'] == '3001') {
	alert_go('사용자가 취소하였습니다.', $_POST['param_opt_2']);
	exit;	
}


//POST 형식 체크부분
if ( $_SERVER['REQUEST_METHOD'] != "POST" ) {
	go('/');
	//exit;
}

$Order = new ORDER;

//$Order->adodb->debug = true;

// 01. 지불 요청 정보 설정
$req_tx		 = $_POST[ "req_tx"		 ]; // 요청 종류
$tran_cd		= $_POST[ "tran_cd"		]; // 처리 종류
/* = -------------------------------------------------------------------------- = */
$cust_ip		= getenv( "REMOTE_ADDR"	); // 요청 IP
$ordr_idxx	  = $_POST[ "ordr_idxx"	  ]; // 쇼핑몰 주문번호
$good_name	  = $_POST[ "good_name"	  ]; // 상품명
/* = -------------------------------------------------------------------------- = */
$res_cd		 = "";						 // 응답코드
$res_msg		= "";						 // 응답메시지
$res_en_msg	 = "";						 // 응답 영문 메세지
$tno			= $_POST[ "tno"			]; // KCP 거래 고유 번호
/* = -------------------------------------------------------------------------- = */
$buyr_name	  = $_POST[ "buyr_name"	  ]; // 주문자명
$buyr_tel1	  = $_POST[ "buyr_tel1"	  ]; // 주문자 전화번호
$buyr_tel2	  = $_POST[ "buyr_tel2"	  ]; // 주문자 핸드폰 번호
$buyr_mail	  = $_POST[ "buyr_mail"	  ]; // 주문자 E-mail 주소
/* = -------------------------------------------------------------------------- = */
$use_pay_method = $_POST[ "use_pay_method" ]; // 결제 방법
$bSucc		  = "";						 // 업체 DB 처리 성공 여부
/* = -------------------------------------------------------------------------- = */
$app_time	   = "";						 // 승인시간 (모든 결제 수단 공통)
$amount		 = "";						 // KCP 실제 거래 금액
$total_amount   = 0;						  // 복합결제시 총 거래금액
$coupon_mny	 = "";						 // 쿠폰금액
/* = -------------------------------------------------------------------------- = */
$card_cd		= "";						 // 신용카드 코드
$card_name	  = "";						 // 신용카드 명
$app_no		 = "";						 // 신용카드 승인번호
$noinf		  = "";						 // 신용카드 무이자 여부
$quota		  = "";						 // 신용카드 할부개월
$partcanc_yn	= "";						 // 부분취소 가능유무
$card_bin_type_01 = "";					   // 카드구분1
$card_bin_type_02 = "";					   // 카드구분2
$card_mny	   = "";						 // 카드결제금액
/* = -------------------------------------------------------------------------- = */
$bank_name	  = "";						 // 은행명
$bank_code	  = "";						 // 은행코드
$bk_mny		 = "";						 // 계좌이체결제금액
/* = -------------------------------------------------------------------------- = */
$bankname	   = "";						 // 입금할 은행명
$depositor	  = "";						 // 입금할 계좌 예금주 성명
$account		= "";						 // 입금할 계좌 번호
$va_date		= "";						 // 가상계좌 입금마감시간
/* = -------------------------------------------------------------------------- = */
$pnt_issue	  = "";						 // 결제 포인트사 코드
$pnt_amount	 = "";						 // 적립금액 or 사용금액
$pnt_app_time   = "";						 // 승인시간
$pnt_app_no	 = "";						 // 승인번호
$add_pnt		= "";						 // 발생 포인트
$use_pnt		= "";						 // 사용가능 포인트
$rsv_pnt		= "";						 // 총 누적 포인트
/* = -------------------------------------------------------------------------- = */
$commid		 = "";						 // 통신사 코드
$mobile_no	  = "";						 // 휴대폰 번호
/* = -------------------------------------------------------------------------- = */
$shop_user_id   = $_POST[ "shop_user_id"   ]; // 가맹점 고객 아이디
$tk_van_code	= "";						 // 발급사 코드
$tk_app_no	  = "";						 // 상품권 승인 번호
/* = -------------------------------------------------------------------------- = */
$cash_yn		= $_POST[ "cash_yn"		]; // 현금영수증 등록 여부
$cash_authno	= "";						 // 현금 영수증 승인 번호
$cash_tr_code   = $_POST[ "cash_tr_code"   ]; // 현금 영수증 발행 구분
$cash_id_info   = $_POST[ "cash_id_info"   ]; // 현금 영수증 등록 번호
$cash_no		= "";						 // 현금 영수증 거래 번호	

// 01-1. 에스크로 지불 요청 정보 설정
$escw_used	  = $_POST[  "escw_used"	 ]; // 에스크로 사용 여부
$pay_mod		= $_POST[  "pay_mod"	   ]; // 에스크로 결제처리 모드
$deli_term	  = $_POST[  "deli_term"	 ]; // 배송 소요일
$bask_cntx	  = $_POST[  "bask_cntx"	 ]; // 장바구니 상품 개수
$good_info	  = $_POST[  "good_info"	 ]; // 장바구니 상품 상세 정보
$rcvr_name	  = $_POST[  "rcvr_name"	 ]; // 수취인 이름
$rcvr_tel1	  = $_POST[  "rcvr_tel1"	 ]; // 수취인 전화번호
$rcvr_tel2	  = $_POST[  "rcvr_tel2"	 ]; // 수취인 휴대폰번호
$rcvr_mail	  = $_POST[  "rcvr_mail"	 ]; // 수취인 E-Mail
$rcvr_zipx	  = $_POST[  "rcvr_zipx"	 ]; // 수취인 우편번호
$rcvr_add1	  = $_POST[  "rcvr_add1"	 ]; // 수취인 주소
$rcvr_add2	  = $_POST[  "rcvr_add2"	 ]; // 수취인 상세주소
$escw_yn		= "";						 // 에스크로 여부


// 02. 인스턴스 생성 및 초기화
$c_PayPlus = new C_PP_CLI;
$c_PayPlus->mf_clear();


// 03. 처리 요청 정보 설정
if ( $req_tx == "pay" ) {

	//결제금액 유효성 검증
	
	$order_row = $Order->getBasicRow($ordr_idxx); 
	$ordr_mony = $order_row['pay_pg']; //실결제금액
	$c_PayPlus->mf_set_ordr_data( "ordr_mony", $ordr_mony);

	$c_PayPlus->mf_set_encx_data( $_POST[ "enc_data" ], $_POST[ "enc_info" ] ); //승인 요청
}


//04. 실행
if ( $tran_cd != "" ) {
	$c_PayPlus->mf_do_tx( "", $g_conf_home_dir, $g_conf_site_cd, $g_conf_site_key, $tran_cd, "",$g_conf_gw_url, $g_conf_gw_port, "payplus_cli_slib", $ordr_idxx,$cust_ip, $g_conf_log_level, 0, 0, $g_conf_log_path ); // 응답 전문 처리

	$res_cd  = $c_PayPlus->m_res_cd;  // 결과 코드
	$res_msg = $c_PayPlus->m_res_msg; // 결과 메시지
	$res_en_msg = $c_PayPlus->mf_get_res_data( "res_en_msg" ); /*  // 결과 영문 메세지 */ 
}
else {
	$c_PayPlus->m_res_cd  = "9562";
	$c_PayPlus->m_res_msg = "연동 오류 tran_cd값이 설정되지 않았습니다.";
}

// ECHO 'res_msg : '.iconv('euc-kr','utf-8',$res_msg);
// ECHO 'res_en_msg : '.$res_en_msg;

$res_msg = iconv('euc-kr','utf-8',$res_msg);
// if($res_en_msg) $res_msg.="({$res_en_msg})";

//  05. 승인 결과 값 추출
if ( $req_tx == "pay" ) {
	//지불정보
	$record_pay = array(
		'pg'=>PG,
		'site_code'=>$g_conf_site_cd,
		'order_num'=>$ordr_idxx,
		'req_tx'=>$req_tx,
		'res_cd'=>$res_cd,
		'res_msg'=>$res_msg,
		'date_insert'=>NOW,
		'date_update'=>NOW
	); 




	if( $res_cd == "0000" ) {
		$tno	   = $c_PayPlus->mf_get_res_data( "tno"	   ); // KCP 거래 고유 번호
		$amount	= $c_PayPlus->mf_get_res_data( "amount"	); // KCP 실제 거래 금액
		$pnt_issue = $c_PayPlus->mf_get_res_data( "pnt_issue" ); // 결제 포인트사 코드
		$coupon_mny = $c_PayPlus->mf_get_res_data( "coupon_mny" ); // 쿠폰금액

		//지불정보
		$record_pay['tno'] = $tno;
		$record_pay['amount'] = $amount; //총결제금액
		$record_pay['amount_delivery'] = $order_row['pay_delivery']-$order_row['coupon_delivery_discount']; //배송비-배송비할인액(무료배송쿠폰)
		$record_pay['pnt_issue'] = $pnt_issue;
		$record_pay['coupon_mny'] = $coupon_mny;
		




		$rtn_arr = array();
		//05-1. 신용카드 승인 결과 처리
		if ( $use_pay_method == "100000000000" ) {
			
			$rtn_arr['card_cd']   = $c_PayPlus->mf_get_res_data( "card_cd"   ); // 카드사 코드
			$card_name = iconv('euc-kr','utf-8',$c_PayPlus->mf_get_res_data( "card_name" )); // 카드 종류
			$rtn_arr['card_name'] = $card_name;
			$rtn_arr['app_time']  = $c_PayPlus->mf_get_res_data( "app_time"  ); // 승인 시간
			$rtn_arr['app_no']	= $c_PayPlus->mf_get_res_data( "app_no"	); // 승인 번호
			$rtn_arr['noinf']	 = $c_PayPlus->mf_get_res_data( "noinf"	 ); // 무이자 여부 ( 'Y' : 무이자 )
			$rtn_arr['quota']	 = $c_PayPlus->mf_get_res_data( "quota"	 ); // 할부 개월 수
			$rtn_arr['partcanc_yn'] = $c_PayPlus->mf_get_res_data( "partcanc_yn" ); // 부분취소 가능유무
			$rtn_arr['card_bin_type_01'] = $c_PayPlus->mf_get_res_data( "card_bin_type_01" ); // 카드구분1
			$rtn_arr['card_bin_type_02'] = $c_PayPlus->mf_get_res_data( "card_bin_type_02" ); // 카드구분2
			$rtn_arr['card_mny'] = $c_PayPlus->mf_get_res_data( "card_mny" ); // 카드결제금액

			//05-1.1. 복합결제(포인트+신용카드) 승인 결과 처리
			if ( $pnt_issue == "SCSK" || $pnt_issue == "SCWB" )
			{
				$rtn_arr['pnt_amount']   = $c_PayPlus->mf_get_res_data ( "pnt_amount"   ); // 적립금액 or 사용금액
				$rtn_arr['pnt_app_time'] = $c_PayPlus->mf_get_res_data ( "pnt_app_time" ); // 승인시간
				$rtn_arr['pnt_app_no']   = $c_PayPlus->mf_get_res_data ( "pnt_app_no"   ); // 승인번호
				$rtn_arr['add_pnt']	  = $c_PayPlus->mf_get_res_data ( "add_pnt"	  ); // 발생 포인트
				$rtn_arr['use_pnt']	  = $c_PayPlus->mf_get_res_data ( "use_pnt"	  ); // 사용가능 포인트
				$rtn_arr['rsv_pnt']	  = $c_PayPlus->mf_get_res_data ( "rsv_pnt"	  ); // 총 누적 포인트
				$rtn_arr['total_amount'] = $amount + $pnt_amount;						  // 복합결제시 총 거래금액
			}

			$record_pay['pay_method'] = 'card';
		}

	
		//05-2. 계좌이체 승인 결과 처리/
		if ( $use_pay_method == "010000000000" ) {
			$rtn_arr['app_time']  = $c_PayPlus->mf_get_res_data( "app_time"   );  // 승인 시간
			$rtn_arr['bank_name'] = iconv('euc-kr','utf-8',$c_PayPlus->mf_get_res_data( "bank_name" ));  // 은행명
			$rtn_arr['bank_code'] = $c_PayPlus->mf_get_res_data( "bank_code"  );  // 은행코드
			$rtn_arr['bk_mny'] = $c_PayPlus->mf_get_res_data( "bk_mny" ); // 계좌이체결제금액

			$record_pay['pay_method'] = 'acnt';
		}

		//05-3. 가상계좌 승인 결과 처리
		if ( $use_pay_method == "001000000000" ) {
			$rtn_arr['bankname']  = iconv('euc-kr','utf-8',$c_PayPlus->mf_get_res_data("bankname")); // 입금할 은행 이름
			$rtn_arr['depositor'] = iconv('euc-kr','utf-8',$c_PayPlus->mf_get_res_data("depositor")); // 입금할 계좌 예금주
			$rtn_arr['account']   = $c_PayPlus->mf_get_res_data( "account"   ); // 입금할 계좌 번호
			$rtn_arr['va_date']   = $c_PayPlus->mf_get_res_data( "va_date"   ); // 가상계좌 입금마감시간
			

			$record_pay['pay_method'] = 'vcnt';
			
			$date_vcnt = date('Y-m-d H:i:s', strtotime($rtn_arr['va_date']));
			$record_pay['date_vcnt'] = $date_vcnt;
		}

	
		//05-4. 포인트 승인 결과 처리
		if ( $use_pay_method == "000100000000" ) {
			$rtn_arr['pnt_amount']   = $c_PayPlus->mf_get_res_data( "pnt_amount"   ); // 적립금액 or 사용금액
			$rtn_arr['pnt_app_time'] = $c_PayPlus->mf_get_res_data( "pnt_app_time" ); // 승인시간
			$rtn_arr['pnt_app_no']   = $c_PayPlus->mf_get_res_data( "pnt_app_no"   ); // 승인번호 
			$rtn_arr['add_pnt']	  = $c_PayPlus->mf_get_res_data( "add_pnt"	  ); // 발생 포인트
			$rtn_arr['use_pnt']	  = $c_PayPlus->mf_get_res_data( "use_pnt"	  ); // 사용가능 포인트
			$rtn_arr['rsv_pnt']	  = $c_PayPlus->mf_get_res_data( "rsv_pnt"	  ); // 적립 포인트
		}

		//05-5. 휴대폰 승인 결과 처리
		if ( $use_pay_method == "000010000000" ){
			$rtn_arr['app_time']  = $c_PayPlus->mf_get_res_data( "hp_app_time"  ); // 승인 시간
			$rtn_arr['commid']	= $c_PayPlus->mf_get_res_data( "commid"	   ); // 통신사 코드
			$rtn_arr['mobile_no'] = $c_PayPlus->mf_get_res_data( "mobile_no"	); // 휴대폰 번호
		}


		//05-6. 상품권 승인 결과 처리
		if ( $use_pay_method == "000000001000" ) {
			$rtn_arr['app_time']	= $c_PayPlus->mf_get_res_data( "tk_app_time"  ); // 승인 시간
			$rtn_arr['tk_van_code'] = $c_PayPlus->mf_get_res_data( "tk_van_code"  ); // 발급사 코드
			$rtn_arr['tk_app_no']   = $c_PayPlus->mf_get_res_data( "tk_app_no"	); // 승인 번호
		}

	
		//05-7. 현금영수증 결과 처리
		$rtn_arr['cash_yn']	  = $c_PayPlus->mf_get_res_data( "cash_yn"	  ); // 현금 영수증 신청여부
		$rtn_arr['cash_authno']  = $c_PayPlus->mf_get_res_data( "cash_authno"  ); // 현금 영수증 승인 번호
		$rtn_arr['cash_no']	  = $c_PayPlus->mf_get_res_data( "cash_no"	  ); // 현금 영수증 거래 번호
		
		$record_pay['res_info'] = serialize($rtn_arr);
	}

	$escw_yn = $c_PayPlus->mf_get_res_data( "escw_yn" ); // 에스크로 여부 
	$record_pay['escrow_yn'] = $escw_yn;
}


//06. 승인 및 실패 결과 DB처리 - 자체적으로 DB처리
if ( $req_tx == "pay" ) {
	

	if( $res_cd == "0000" ) { //승인성공

		$Order->transBegin(); //트랜잭션시작

		try{

			$bSucc = "";

			// 06-1-1. 신용카드
			if ( $use_pay_method == "100000000000" ) {
				// 06-1-1-1. 복합결제(신용카드 + 포인트)
				if ( $pnt_issue == "SCSK" || $pnt_issue == "SCWB" ){
				}
				$order_status = 2; //결제완료
			}
			// 06-1-2. 계좌이체
			if ( $use_pay_method == "010000000000" ) {
				$order_status = 2; //결제완료
			}
			// 06-1-3. 가상계좌
			if ( $use_pay_method == "001000000000" ) {
				$order_status = 1; //주문완료
			}
			// 06-1-4. 포인트
			if ( $use_pay_method == "000100000000" ) {
				$order_status = 2; //결제완료
			}
			// 06-1-5. 휴대폰
			if ( $use_pay_method == "000010000000" ) {
				$order_status = 2; //결제완료
			}
			// 06-1-6. 상품권
			if ( $use_pay_method == "000000001000" ) {
				$order_status = 2; //결제완료
			}



			if($order_row['member_id'] == MEMID) {
				$Member = new MEMBER; //회원클래스인스턴스
				$member_info = $Member->getMemberRow($order_row['member_id']);

				//포인트 사용처리
				if($order_row['use_point'] > 0) {
					if($order_row['use_point'] > $member_info['act_point']) {
						$bSucc = "false";
						$mod_desc = "포인트보유액 초과사용 - 자동취소";
						$Order->log_file(array('order_num'=>$ordr_idxx,'msg'=>$mod_desc,'file'=>__FILE__,'line'=>__LINE__));
					}

					$Point = new POINT;
					$rs_point = $Point->pay($order_row['use_point'], $ordr_idxx);
					if(!$rs_point) {
						$bSucc = "false";
						$mod_desc = "포인트사용처리 실패 - 자동취소";
						$Order->log_file(array('order_num'=>$ordr_idxx,'msg'=>$mod_desc,'file'=>__FILE__,'line'=>__LINE__));
					}
				}


				//마일리지 사용처리
				if($order_row['use_mileage'] > 0) {
					if($order_row['use_mileage'] > $member_info['reserve']) {
						$bSucc = "false";
						$mod_desc = "마일리지보유액 초과사용 - 자동취소";
						$Order->log_file(array('order_num'=>$ordr_idxx,'msg'=>$mod_desc,'file'=>__FILE__,'line'=>__LINE__));
					}

					$Mileage = new MILEAGE;
					$rs_mileage = $Mileage->pay($order_row['use_mileage'], $ordr_idxx, $order_row['member_id']);
					if(!$rs_mileage) {
						$bSucc = "false";
						$mod_desc = "마일리지사용처리 실패 - 자동취소";
						$Order->log_file(array('order_num'=>$ordr_idxx,'msg'=>$mod_desc,'file'=>__FILE__,'line'=>__LINE__));
					}
				}

				//장바구니 쿠폰사용처리
				if($order_row['coupon_basket'] > 0) {
					$Coupon = new COUPON;
					$ci_no = $order_row['coupon_basket'];
					$valid = $Coupon->getValid(MEMID, $ci_no); //쿠폰유효성검증
					if(!$valid) {
						$bSucc = "false";
						$mod_desc = "사용불가 장바구니쿠폰 사용 - 자동취소";
						$Order->log_file(array('order_num'=>$ordr_idxx,'msg'=>$mod_desc,'file'=>__FILE__,'line'=>__LINE__));
					}
					else {
						//쿠폰사용처리
						$coupon_rs = $Coupon->useProcessing($ci_no, $ordr_idxx);
						if(!$coupon_rs) {
							$bSucc = "false";
							$mod_desc = "장바구니쿠폰 사용처리중 오류 - 자동취소";
							$Order->log_file(array('order_num'=>$ordr_idxx,'msg'=>$mod_desc,'file'=>__FILE__,'line'=>__LINE__));
						}
					}
				}

				//무료배송쿠폰 사용처리
				if($order_row['coupon_delivery'] > 0) {
					$Coupon = new COUPON;
					$ci_no = $order_row['coupon_delivery'];
					$valid = $Coupon->getValid(MEMID, $ci_no);  //쿠폰유효성검증
					if(!$valid) {
						$bSucc = "false";
						$mod_desc = "사용불가 무료배송쿠폰 사용 - 자동취소";
						$Order->log_file(array('order_num'=>$ordr_idxx,'msg'=>$mod_desc,'file'=>__FILE__,'line'=>__LINE__));
					}
					else {
						//쿠폰사용처리
						$coupon_rs = $Coupon->useProcessing($ci_no, $ordr_idxx);
						if(!$coupon_rs) {
							$bSucc = "false";
							$mod_desc = "무료배송쿠폰 사용처리중 오류 - 자동취소";
							$Order->log_file(array('order_num'=>$ordr_idxx,'msg'=>$mod_desc,'file'=>__FILE__,'line'=>__LINE__));
						}
					}
				}

				//상품쿠폰 사용처리
				$order_product = $Order->getProductList($ordr_idxx);
				if(is_array($order_product)) {

					$Coupon = new COUPON;
					foreach($order_product['list'] as $row) {
						if($row['coupon_issue_no'] > 0) { //상품쿠폰을 사용한경우
							$ci_no = $row['coupon_issue_no'];
							$valid = $Coupon->getValid(MEMID, $ci_no); //쿠폰유효성검증
							if(!$valid) {
								$bSucc = "false";
								$mod_desc = "사용불가 상품쿠폰({$ci_no}) 사용 - 자동취소";
								$Order->log_file(array('order_num'=>$ordr_idxx,'msg'=>$mod_desc,'file'=>__FILE__,'line'=>__LINE__));
							}
							else {
								//쿠폰사용처리
								$coupon_rs = $Coupon->useProcessing($ci_no, $ordr_idxx, $row['idx']);
								if(!$coupon_rs) {
									$bSucc = "false";
									$mod_desc = "상품쿠폰({$ci_no}) 사용처리중 오류 - 자동취소";
									$Order->log_file(array('order_num'=>$ordr_idxx,'msg'=>$mod_desc,'file'=>__FILE__,'line'=>__LINE__));
								}
							}
						}
					}
				}
			}



			if($bSucc!="false") {

				//주문완료 로그
				$record_log = array(
					'order_num'=>$ordr_idxx,
					'order_product_idx'=>'all',
					'type'=>'order_status',
					'value'=>$order_status,
					'msg'=>($order_status == 1)?'사용자 > 주문완료':'사용자 > 주문/결제완료'
				);
				$Order->log_order($record_log); //로그등록

				$record_op = array(
					'order_status'=>$order_status,
					'date_order_1'=>NOW //주문완료일
				);
				if($order_status == '2') {
					$record_op['date_order_2'] = NOW; //결제완료일
				}
				$sql = sqlUpdate($record_op, $Order->tbls['order_product'], array('order_num'=>$ordr_idxx));
				$rs_product = $Order->adodb->Execute($sql);
				if(!$rs_product) {
					$bSucc = "false";
					$mod_desc = "주문상품별 업데이트 실패";
					$Order->log_file(array('sql'=>$sql,'msg'=>$mod_desc,'file'=>__FILE__,'line'=>__LINE__));
				}
			}
		}
		catch(Exception $e) {
			$Order->log_file(array('msg'=>$e->getMessage(),'file'=>__FILE__, 'line'=>__LINE__));
			$Order->transRollback(); //트랜잭션롤백
		}

		if($bSucc == "false") {
			$Order->transRollback(); //트랜잭션롤백
		}
		else {
			$Order->transCommit(); //트랜잭션종료

			// 주문서 거래번호 및 주문상태 업데이트
			$sql = sqlUpdate(array('pg_tno'=>$tno, 'order_status'=>$order_status), $Order->tbls['order'], array('order_num'=>$ordr_idxx));

			$rs_basic = $Order->adodb->Execute($sql);
			if(!$rs_basic) {
				$bSucc = "false";
				$mod_desc = "주문서 거래번호 및 주문상태 업데이트 실패";
				$Order->log_file(array('sql'=>$sql,'msg'=>$mod_desc,'file'=>__FILE__,'line'=>__LINE__));
			}
		}
	}
	
	//06. 승인 및 실패 결과 DB처리
	else if ( $res_cd != "0000" ){ //승인실패

		$sql = sqlUpdate(array('cs_flag'=>'PD'), $Order->tbls['order_product'], array('order_num'=>$ordr_idxx)); //상태플래그처리(PD:결제실패)
		$rs_product = $Order->adodb->Execute($sql);
		if(!$rs_product) {
			$bSucc = "false";
		}
	}


	if($bSucc == "false") {
		if($res_cd == "0000" && $mod_desc) {
			$record_pay['res_msg'] = $mod_desc;
		} 

		$sql = sqlInsert($record_pay, $Order->tbls['order_payment']);
		$rs_payment = $Order->adodb->Execute($sql);
	}
	else {
		$sql = sqlInsert($record_pay, $Order->tbls['order_payment']);
		$rs_payment = $Order->adodb->Execute($sql);
		if(!$rs_payment) {
			$bSucc = "false";  // DB 작업 실패 또는 금액 불일치의 경우 "false" 로 세팅
		}

		$Order->buyBasket($ordr_idxx); //장바구니 구매처리 2018-12-08


		//메일 발송
		$mail_code = "";
		$sms_code = "";
		$mail = new MAIL();
		switch ($order_status){
			default:
			case 1 : //주문완료
				$mail_code = "order_check";
				$sms_code = "ORDER_001";
				break;
			case 2 : //결제완료
				$mail_code = "order_pay";
				$sms_code = "ORDER_002";

				break;
		}
		$mail->send_mail($mail_code,$ordr_idxx);


		$sms = new SMS();
		$sms->send_sms($sms_code,$ordr_idxx);
	}
}




//07. 승인 결과 DB처리 실패시 : 자동취소
//07-1. DB 작업 실패일 경우 자동 승인 취소
if ( $req_tx == "pay" ) {
	if( $res_cd == "0000" ) {
		if ( $bSucc == "false" ) {
			$c_PayPlus->mf_clear();

			$tran_cd = "00200000";

			//07-1.자동취소시 에스크로 거래인 경우
			// 취소시 사용하는 mod_type
			$bSucc_mod_type = "";

			// 에스크로 가상계좌 건의 경우 가상계좌 발급취소(STE5)
			if ( $escw_yn == "Y" && $use_pay_method == "001000000000" ) {
				$bSucc_mod_type = "STE5";
			}
			// 에스크로 가상계좌 이외 건은 즉시취소(STE2)
			else if ( $escw_yn == "Y" ) {
				$bSucc_mod_type = "STE2";
			}
			// 에스크로 거래 건이 아닌 경우(일반건)(STSC)
			else {
				$bSucc_mod_type = "STSC"; 
			}
			// 07-1. 자동취소시 에스크로 거래인 경우 처리 END


			$c_PayPlus->mf_set_modx_data( "tno",	  $tno						 );  // KCP 원거래 거래번호
			$c_PayPlus->mf_set_modx_data( "mod_type", $bSucc_mod_type			  );  // 원거래 변경 요청 종류
			$c_PayPlus->mf_set_modx_data( "mod_ip",   $cust_ip					 );  // 변경 요청자 IP
			$c_PayPlus->mf_set_modx_data( "mod_desc", $mod_desc );  // 변경 사유

			$c_PayPlus->mf_do_tx( "", $g_conf_home_dir, $g_conf_site_cd, $g_conf_site_key, $tran_cd, "",
							$g_conf_gw_url, $g_conf_gw_port, "payplus_cli_slib", $ordr_idxx,
							$cust_ip, $g_conf_log_level, 0, 0, $g_conf_log_path ); // 응답 전문 처리

			$res_cd  = $c_PayPlus->m_res_cd;
			$res_msg = $c_PayPlus->m_res_msg;

		}
		else {
		}
	}
} // End of [res_cd = "0000"]




//08. 폼 구성 및 결과페이지 호출			
$order_num = $Order->Enctypt_AES128CBC($ordr_idxx);

$action = ($_POST['param_opt_1'] == 'mobile')?'/m/order_end.php':'/front/order_end.php';


?>
<html>
<head>
	<title>*** NHN KCP [AX-HUB Version] ***</title>
	<script type="text/javascript">
		function goResult(){
			document.pay_info.submit()
			//alert('submit');
		}

		// 결제 중 새로고침 방지 샘플 스크립트 (중복결제 방지)
		function noRefresh()
		{
			/* CTRL + N키 막음. */
			if ((event.keyCode == 78) && (event.ctrlKey == true))
			{
				event.keyCode = 0;
				return false;
			}
			/* F5 번키 막음. */
			if(event.keyCode == 116)
			{
				event.keyCode = 0;
				return false;
			}
		}
		document.onkeydown = noRefresh ;
	</script>
</head>

<body onload="goResult()">
<form name="pay_info" method="GET" action="<?=$action?>">
	<input type="hidden" name="orn" value="<?=$order_num ?>">	<!-- 사이트코드 -->
</form>
</body>
</html>
